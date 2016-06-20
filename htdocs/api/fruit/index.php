<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}	
}

$rsConfig=$DB->GetRs("fruit_config","*","where Users_ID='".$UsersID."'");
$img_url = '';
$json=$DB->GetRs("wechat_material","*","where Users_ID='".$UsersID."' and Material_Table='fruit' and Material_TableID=0 and Material_Display=0");
if($json){
	$rsMaterial=json_decode($json['Material_Json'],true);
	$img_url = $rsMaterial["ImgPath"];
}
$rsFruit=$DB->GetRs("fruit","*","where Users_ID='".$UsersID."' and Fruit_StartTime<=".time()." and Fruit_EndTime>=".time()." and Fruit_Status=2 order by Fruit_CreateTime desc");
if(isset($_POST["action"]) && $_POST["action"]!="move"){//不用判断
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$action=$_POST['action'];
	switch($action){
		case 'mobile':
			$rsSN=$DB->GetRs("fruit_sn","*","where Users_ID='".$UsersID."' and Open_ID='".$_SESSION[$UsersID."OpenID"]."' order by SN_CreateTime desc");
			if(empty($rsSN['SN_Code'])){
				$Data=array(
					"status"=>0,
					"msg"=>'请勿非法操作'
				);
			}else{
				if($rsSN['SN_Status']>=1){
					$Data=array(
						"status"=>0,
						"msg"=>'请勿非法操作'
					);
				}else{
					$Data=array(
						"User_Mobile"=>$_POST['MobilePhone'],
						"SN_Status"=>1
					);
					$Flag=$DB->Set("fruit_sn",$Data,"where Users_ID='".$UsersID."' and Open_ID='".$_SESSION[$UsersID."OpenID"]."' and SN_ID=".$rsSN['SN_ID']);
					if($Flag){
						$Data=array(
							"status"=>1
						);
					}else{
						$Data=array(
							"status"=>0
						);
					}
				}
			}
			break;
		case 'used':
			if($_POST['bp']==$rsFruit['Fruit_BusinessPassWord']){
				$Data=array(
					"SN_UsedTimes"=>time(),
					"SN_Status"=>2
				);
				$Flag=$DB->Set("fruit_sn",$Data,"where Users_ID='".$UsersID."' and Open_ID='".$_SESSION[$UsersID."OpenID"]."' and SN_ID=".$_POST['SNID']);
				$Data=array(
					"status"=>1,
					"msg"=>'已成功兑换'
				);
			}else{
				$Data=array(
					"status"=>0,
					"msg"=>'商家密码不正确'
				);
			}
		break;
	}
	if($Flag){
		mysql_query("commit");
	}else{
		mysql_query("roolback");
	}
	echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
	exit;
}else{//判断
	$error_message = "";
	$r = $DB->GetRs("fruit_sn","Count(*) as count","where Open_ID='".$_SESSION[$UsersID."OpenID"]."' and Fruit_ID=".(isset($rsFruit['Fruit_ID']) ? $rsFruit['Fruit_ID'] : 0));
	if(isset($rsFruit["Fruit_LotteryTimes"]) && $r["count"]>=$rsFruit["Fruit_LotteryTimes"]){
		$error_message = "本次活动的抽奖机会您已用完";
	}else{
		$fromtime = strtotime(date("Y-m-d")." 00:00:00");
		$totime = strtotime(date("Y-m-d")." 23:59:59");
		$r = $DB->GetRs("fruit_sn","Count(*) as count","where Open_ID='".$_SESSION[$UsersID."OpenID"]."' and Fruit_ID=".(isset($rsFruit['Fruit_ID']) ? $rsFruit['Fruit_ID'] : 0)." and SN_CreateTime>=".$fromtime." and SN_CreateTime<=".$totime);
		if(isset($rsFruit["Fruit_EveryDayLotteryTimes"]) && $r["count"]>=$rsFruit["Fruit_EveryDayLotteryTimes"]){
			$error_message = "您的今天的抽奖机会已用完，请明天再来吧！";
		}else{
			if($rsFruit["Fruit_UsedIntegral"]){
				if(isset($_SESSION[$UsersID."User_ID"])){
					$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
					if($rsUser["User_Integral"]<$rsFruit["Fruit_UsedIntegralValue"]){
						$error_message = "<font style='color:#FF0000'>本次活动每次需花费".$rsFruit["Fruit_UsedIntegralValue"]."积分，您的积分余额不足！</font>";
					}else{
						if(isset($_POST['action']) && $_POST["action"]=="move"){
							//开始事务定义
							$Flag=true;
							$msg="";
							mysql_query("begin");
							$action=$_POST['action'];
							switch($action){
								case 'move':
									function get_rand($proArr) { 
										$result = '';
										//概率数组的总概率精度
										$proSum = array_sum($proArr)*100;
										//概率数组循环
										foreach ($proArr as $key => $proCur) {
											$randNum = mt_rand(1, $proSum);
											if ($randNum <= $proCur*100) {
												$result = $key;
												break;
											} else {
												$proSum -= $proCur*100; 
											}
										}
										unset ($proArr);
										return $result;
									}
									$PrizeCount=array(0,0,0,0,0);
									$DB->get("fruit_sn","Fruit_PrizeID,Count(Fruit_PrizeID) as Fruit_PrizeCount","where Users_ID='".$UsersID."' and Open_ID='".$_SESSION[$UsersID."OpenID"]."' and Fruit_ID=".(isset($rsFruit['Fruit_ID']) ? $rsFruit['Fruit_ID'] : 0)." and SN_Code<>0 and SN_Status>0 GROUP BY Fruit_PrizeID");
									while($rsPrizeCount=$DB->fetch_assoc()){
										$PrizeCount[$rsPrizeCount['Fruit_PrizeID']]=$rsPrizeCount['Fruit_PrizeCount'];
									}
									$rsFruit['Fruit_FirstPrizeProbability']=$PrizeCount[1]>=$rsFruit['Fruit_FirstPrizeCount']?0:$rsFruit['Fruit_FirstPrizeProbability'];
									$rsFruit['Fruit_SecondPrizeProbability']=$PrizeCount[2]>=$rsFruit['Fruit_SecondPrizeCount']?0:$rsFruit['Fruit_SecondPrizeProbability'];
									$rsFruit['Fruit_ThirdPrizeProbability']=$PrizeCount[3]>=$rsFruit['Fruit_ThirdPrizeCount']?0:$rsFruit['Fruit_ThirdPrizeProbability'];
									$prize_arr = array(
										'0' => array('id'=>1,'rand'=>mt_rand(345,375),'level'=>'一等奖','prize'=>$rsFruit['Fruit_FirstPrize'],'v'=>$rsFruit['Fruit_FirstPrizeProbability']),
										'1' => array('id'=>2,'rand'=>mt_rand(225,255),'level'=>'二等奖','prize'=>$rsFruit['Fruit_SecondPrize'],'v'=>$rsFruit['Fruit_SecondPrizeProbability']),
										'2' => array('id'=>3,'rand'=>mt_rand(105,135),'level'=>'三等奖','prize'=>$rsFruit['Fruit_ThirdPrize'],'v'=>$rsFruit['Fruit_ThirdPrizeProbability']),
										'3' => array('id'=>4,'rand'=>mt_rand(0,360),'prize'=>'谢谢您参与，下次再努力！','v'=>100-$rsFruit['Fruit_FirstPrizeProbability']-$rsFruit['Fruit_SecondPrizeProbability']-$rsFruit['Fruit_ThirdPrizeProbability']), 
									);
									foreach ($prize_arr as $key => $val) {
										$arr[$val['id']] = $val['v'];
									}
									$rid = get_rand($arr); //根据概率获取奖项id
									$SN_Code=mt_rand(1000,9999).mt_rand(1000,9999);
									if($rsFruit["Fruit_UsedIntegral"]){
										$Data = array(
											"User_Integral"=>($rsUser["User_Integral"]-$rsFruit["Fruit_UsedIntegralValue"])
										);
										$DB->Set("User",$Data,"where User_ID=".$_SESSION[$UsersID."User_ID"]);
										$Data=array(
											'Record_Integral'=>-$rsFruit["Fruit_UsedIntegralValue"],
											'Record_SurplusIntegral'=>$rsUser['User_Integral']-$rsFruit["Fruit_UsedIntegralValue"],
											'Operator_UserName'=>'',
											'Record_Type'=>3,
											'Record_Description'=>'水果达人消费积分',
											'Record_CreateTime'=>time(),
											'Users_ID'=>$UsersID,
											'User_ID'=>$_SESSION[$UsersID."User_ID"]
										);
										$DB->Add('user_Integral_record',$Data);
										require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
										$weixin_message = new weixin_message($DB,$UsersID,$_SESSION[$UsersID."User_ID"]);
										$contentStr = "水果达人消费".$rsFruit["Fruit_UsedIntegralValue"]."积分";
										$weixin_message->sendscorenotice($contentStr);
									}
									//加入访问记录
									$Data=array(
										"Users_ID"=>$UsersID,
										"S_Module"=>"fruit",
										"S_CreateTime"=>time()
									);
									$DB->Add("statistics",$Data);
									$Data=array(
										"Fruit_ID"=>$rsFruit['Fruit_ID'],
										"Fruit_PrizeID"=>$rid,
										"Fruit_Prize"=>$prize_arr[$rid-1]['prize'],
										"SN_Code"=>$rid<4?$SN_Code:0,
										"Users_ID"=>$UsersID,
										"Open_ID"=>$_SESSION[$UsersID."OpenID"],
										"SN_CreateTime"=>time()
									);
									$Flag=$Flag&&$DB->Add("fruit_sn",$Data);
									if($rid<4&&$Flag){
										$return=array(
											"type"=>$rid,
											"prize"=>$prize_arr[$rid-1]['level'],
											"msg"=>'恭喜您，抽中'.$prize_arr[$rid-1]['level'].'！',
											"sn"=>$SN_Code,
											"left"=>$rid==1?0:$rid*2,
											"middle"=>$rid==1?0:$rid*2,
											"right"=>$rid==1?0:$rid*2
										);
										$Data=array(
											"data"=>json_encode($return,JSON_UNESCAPED_UNICODE)
										);
									}else{
										$return=array(
											"type"=>0,
											"prize"=>'谢谢参与！',
											"msg"=>'谢谢您参与，下次再努力！',
											"sn"=>'',
											"left"=>mt_rand(0,3),
											"middle"=>mt_rand(4,6),
											"right"=>mt_rand(5,8)
										);
										$Data=array(
											"data"=>json_encode($return,JSON_UNESCAPED_UNICODE)
										);
									}
								break;
							}
							if($Flag){
								mysql_query("commit");
							}else{
								mysql_query("roolback");
							}
							echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
							exit;
						}
					}
				}else{
					$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/fruit/?wxref=mp.weixin.qq.com";
					header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
				}
			}else{
				if(isset($_POST['action']) && $_POST["action"]=="move"){
					//开始事务定义
					$Flag=true;
					$msg="";
					mysql_query("begin");
					$action=$_POST['action'];
						switch($action){
							case 'move':
								function get_rand($proArr) { 
									$result = '';
									//概率数组的总概率精度
									$proSum = array_sum($proArr)*100;
									//概率数组循环
									foreach ($proArr as $key => $proCur) {
										$randNum = mt_rand(1, $proSum);
										if ($randNum <= $proCur*100) {
											$result = $key;
											break;
										} else {
											$proSum -= $proCur*100; 
										}
									}
									unset ($proArr);
									return $result;
								}
								$PrizeCount=array(0,0,0,0,0);
								$DB->get("fruit_sn","Fruit_PrizeID,Count(Fruit_PrizeID) as Fruit_PrizeCount","where Users_ID='".$UsersID."' and Open_ID='".$_SESSION[$UsersID."OpenID"]."' and Fruit_ID=".(isset($rsFruit['Fruit_ID']) ? $rsFruit['Fruit_ID'] : 0)." and SN_Code<>0 and SN_Status>0 GROUP BY Fruit_PrizeID");
								while($rsPrizeCount=$DB->fetch_assoc()){
									$PrizeCount[$rsPrizeCount['Fruit_PrizeID']]=$rsPrizeCount['Fruit_PrizeCount'];
								}
								$rsFruit['Fruit_FirstPrizeProbability']=$PrizeCount[1]>=$rsFruit['Fruit_FirstPrizeCount']?0:$rsFruit['Fruit_FirstPrizeProbability'];
								$rsFruit['Fruit_SecondPrizeProbability']=$PrizeCount[2]>=$rsFruit['Fruit_SecondPrizeCount']?0:$rsFruit['Fruit_SecondPrizeProbability'];
								$rsFruit['Fruit_ThirdPrizeProbability']=$PrizeCount[3]>=$rsFruit['Fruit_ThirdPrizeCount']?0:$rsFruit['Fruit_ThirdPrizeProbability'];
								$prize_arr = array(
									'0' => array('id'=>1,'rand'=>mt_rand(345,375),'level'=>'一等奖','prize'=>$rsFruit['Fruit_FirstPrize'],'v'=>$rsFruit['Fruit_FirstPrizeProbability']),
									'1' => array('id'=>2,'rand'=>mt_rand(225,255),'level'=>'二等奖','prize'=>$rsFruit['Fruit_SecondPrize'],'v'=>$rsFruit['Fruit_SecondPrizeProbability']),
									'2' => array('id'=>3,'rand'=>mt_rand(105,135),'level'=>'三等奖','prize'=>$rsFruit['Fruit_ThirdPrize'],'v'=>$rsFruit['Fruit_ThirdPrizeProbability']),
									'3' => array('id'=>4,'rand'=>mt_rand(0,360),'prize'=>'谢谢您参与，下次再努力！','v'=>100-$rsFruit['Fruit_FirstPrizeProbability']-$rsFruit['Fruit_SecondPrizeProbability']-$rsFruit['Fruit_ThirdPrizeProbability']), 
								);
								foreach ($prize_arr as $key => $val) {
									$arr[$val['id']] = $val['v'];
								}
								$rid = get_rand($arr); //根据概率获取奖项id
								$SN_Code=mt_rand(1000,9999).mt_rand(1000,9999);
								//加入访问记录
								$Data=array(
									"Users_ID"=>$UsersID,
									"S_Module"=>"fruit",
									"S_CreateTime"=>time()
								);
								$DB->Add("statistics",$Data);
								$Data=array(
									"Fruit_ID"=>$rsFruit['Fruit_ID'],
									"Fruit_PrizeID"=>$rid,
									"Fruit_Prize"=>$prize_arr[$rid-1]['prize'],
									"SN_Code"=>$rid<4?$SN_Code:0,
									"Users_ID"=>$UsersID,
									"Open_ID"=>$_SESSION[$UsersID."OpenID"],
									"SN_CreateTime"=>time()
								);
								$Flag=$Flag&&$DB->Add("fruit_sn",$Data);
								if($rid<4&&$Flag){
									$return=array(
										"type"=>$rid,
										"prize"=>$prize_arr[$rid-1]['level'],
										"msg"=>'恭喜您，抽中'.$prize_arr[$rid-1]['level'].'！',
										"sn"=>$SN_Code,
										"left"=>$rid==1?0:$rid*2,
										"middle"=>$rid==1?0:$rid*2,
										"right"=>$rid==1?0:$rid*2
									);
									$Data=array(
										"data"=>json_encode($return,JSON_UNESCAPED_UNICODE)
									);
								}else{
									$return=array(
										"type"=>0,
										"prize"=>'谢谢参与！',
										"msg"=>'谢谢您参与，下次再努力！',
										"sn"=>'',
										"left"=>mt_rand(0,3),
										"middle"=>mt_rand(4,6),
										"right"=>mt_rand(5,8)
									);
									$Data=array(
										"data"=>json_encode($return,JSON_UNESCAPED_UNICODE)
									);
								}
							break;
						}
						if($Flag){
							mysql_query("commit");
						}else{
							mysql_query("roolback");
						}
						echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
						exit;
				}
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsFruit?$rsFruit['Fruit_Title']:'当前没有进行中的活动！' ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/fruit.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<link href='/static/js/plugin/jquery/jquery.mobile-1.3.1.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/jquery/jquery.mobile-1.3.1.js'></script>
<script type='text/javascript' src='/static/api/js/fruit.js'></script>
<script language="javascript">
$(window).load(function(){
	fruit_obj.fruit_init();
	fruit_obj.use_sn_init();
	global_obj.share_init({
		'img_url':'http://'+document.domain+'<?php echo $img_url;?>',
		'img_width':100,
		'img_height':100,
		'link':window.location.href,
		'desc':'<?php echo $rsFruit ? $rsFruit["Fruit_Description"] : '当前没有进行中的活动！';?>',
		'title':$('title').html()
	});
});
var start=false;
</script>
</head>

<body>
<?php
ad($UsersID, 1, 3);
?>
<div id="fruit">
  <div id="WheelEvent" class="none">
    <div class="tigerslot">
      <div class="machine">
        <div class="strip left">
          <div class="box"></div>
          <div class="cover"></div>
        </div>
        <div class="strip middle">
          <div class="box"></div>
          <div class="cover"></div>
        </div>
        <div class="strip right">
          <div class="box"></div>
          <div class="cover"></div>
        </div>
        <?php if(!$error_message){?><div class="gamebutton"></div><?php }?>
      </div>
    </div>
    <script type="text/javascript">var start=true;</script>
    <div id="WinPrize" class="refer none"> <span> 恭喜您在本活动中抽中<font id="PrizeClass"></font>！<br>
      SN码：<font id="SnNumber"></font><br>
      请输入您的手机号，作为领奖凭证！ </span>
      <div>
        <form id="GetPrize">
          <div class="input">
            <input type="tel" name="MobilePhone" value="" class="form_input" pattern="[0-9]*" maxlength="11" placeholder="请输入您的手机号" />
          </div>
          <div class="input">
            <input type="submit" value="提交" class="submit" />
          </div>
        </form>
      </div>
    </div>
    <div id="fruit_success">提交成功！</div>
    <div class="refer"> <span>活动说明</span>
      <div><?php echo $error_message ? $error_message : $rsFruit['Fruit_Description']; ?></div>
    </div>
    <div class="refer"> <span>兑换说明（<font color="#FF0000">亲，中奖后请务必输入您的手机号并提交，否则无法领奖喔！</font>）</span>
      <div> 一等奖：<?php echo $rsFruit['Fruit_FirstPrize'];if($rsFruit['Fruit_IsShowPrizes']){ ?>。奖品数量：<?php echo $rsFruit['Fruit_FirstPrizeCount'];} ?><br />
        二等奖：<?php echo $rsFruit['Fruit_SecondPrize'];if($rsFruit['Fruit_IsShowPrizes']){ ?>。奖品数量：<?php echo $rsFruit['Fruit_SecondPrizeCount'];} ?><br />
        三等奖：<?php echo $rsFruit['Fruit_ThirdPrize'];if($rsFruit['Fruit_IsShowPrizes']){ ?>。奖品数量：<?php echo $rsFruit['Fruit_ThirdPrizeCount'];} ?><br />
      </div>
    </div>
    <div id="UsedSn" class="refer none"> <span>兑奖申请(SN码：<font id="UsedSnNumber"></font>)</span>
      <div class="use">
        <form id="UsedPrize">
          <div class="input">
            <input type="password" name="bp" value="" class="form_input" placeholder="请输入商家兑奖密码" maxlength="16" />
          </div>
          <div class="input">
            <input type="submit" value="提交" class="submit" />
            <input type="button" value="关闭" class="close" />
          </div>
          <div class="clear"></div>
          <input type="hidden" name="SNID" value="" />
        </form>
      </div>
    </div>
    <div id="PrizeTips" class="refer"> <span>我的中奖记录</span>
      <div class="prize_list">
        <ul>
          <?php $level=array('谢谢参与','一等奖','二等奖','三等奖');
		$DB->get("fruit_sn","*","where Users_ID='".$UsersID."' and Open_ID='".$_SESSION[$UsersID."OpenID"]."' and Fruit_ID=".(isset($rsFruit['Fruit_ID']) ? $rsFruit['Fruit_ID'] : 0)." and SN_Code<>0 and SN_Status>0 order by SN_CreateTime desc");
		$i=1;
		while($rsSN=$DB->fetch_assoc()){
			echo '<li'.($i==1?' class="bn"':'').'>
            <label>'.$i.'、</label>
            <span sn="'.$rsSN['SN_Code'].'" snid="'.$rsSN['SN_ID'].'"> 奖项：'.$level[$rsSN['Fruit_PrizeID']].'（'.$rsSN['Fruit_Prize'].'）<br />
            中奖SN码：<font class="sn">'.$rsSN['SN_Code'].'</font>'.($rsSN['SN_Status']==2?'':' <a href="#usesn">申请兑奖</a>').'<br />
            中奖时间：'.date("Y-m-d H:i:s",$rsSN["SN_CreateTime"]).'<br />
            '.($rsSN['SN_Status']==2?'兑奖时间：<font class="time">'.date("Y-m-d H:i:s",$rsSN["SN_UsedTimes"]).'</font>':'').'</span>
            <div class="clear"></div>
          </li>';
		  $i++;
		}?>
        </ul>
      </div>
    </div>
    
  </div>
</div>
</body>
</html>