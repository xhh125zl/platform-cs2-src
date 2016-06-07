<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$rsConfig=$DB->GetRs("user_config","UserLevel","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsConfig)){
	header("location:config.php");
}else{
	if(empty($rsConfig['UserLevel'])){
		$UserLevel[0]=array(
			"Name"=>"普通会员",
			"UpIntegral"=>0,
			"ImgPath"=>""
		);
		$Data=array(
			"UserLevel"=>json_encode($UserLevel,JSON_UNESCAPED_UNICODE)
		);
		$DB->Set("user_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	}else{
		$UserLevel=json_decode($rsConfig['UserLevel'],true);
	}
}
$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];
$UserID=empty($_REQUEST['UserID'])?'':$_REQUEST['UserID'];
$rsUser=$DB->GetRs("user","*","where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$UserID);
$RecordType=array("每日签到","手动修改","获取积分","使用积分","使用优惠券获取积分","积分兑换礼品","","大转盘消耗积分","刮刮卡消耗积分");
if(isset($action))
{
	if($action=="del"){
		//1删除用户表记录
		$Flag=$DB->Del("user","Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_GET["UserID"]);
		//删除用户订单记录
		//删除用户签到
		//删除用户团购记录
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
	if($action=="mod_password")
	{
		$Data=array(
			"User_Password"=>md5($_POST['Password'])
		);
		$Set=$DB->Set("user",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_POST["UserID"]);
		if($Set){
			$Data=array("status"=>1);
		}else{
			$Data=array("status"=>0);
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($action=="user_mod")
	{
		$FieldName=array("User_No","User_Name","User_Mobile","User_Integral","","User_Level");
		$Data=array(
			$FieldName[$_POST['field']]=>$_POST['Value']
		);
		$Set=$DB->Set("user",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_POST["UserID"]);
		if($Set){
			$Data=array("status"=>1);
		}else{
			$Data=array("status"=>0,"msg"=>"写入数据库失败");
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($action=="integral_mod")
	{
		$rsUser=$DB->GetRs("user","User_Integral,User_Level","where Users_ID='".$_SESSION["Users_ID"]."' and User_ID='".$_POST["UserID"]."'");
		if($rsUser['User_Integral']+$_POST['Value']<0){
			$Data=array("status"=>2);
		}else{
			//增加
			$Data=array(
				'Record_Integral'=>$_POST['Value'],
				'Record_SurplusIntegral'=>$rsUser['User_Integral']+$_POST['Value'],
				'Operator_UserName'=>$_SESSION["Users_Account"],
				'Record_Type'=>1,
				'Record_Description'=>"手动修改积分",
				'Record_CreateTime'=>time(),
				'Users_ID'=>$_SESSION['Users_ID'],
				'User_ID'=>$_POST["UserID"]
			);
			$Add=$DB->Add('user_Integral_record',$Data);
			foreach($UserLevel as $k=>$v){
				 if($rsUser['User_Integral']+$_POST['Value']>=$v['UpIntegral']){
					 $levelID=$k;
					 $levelName=$v['Name'];
				 }
			}
			$Set=$DB->Set("user","User_Level=".$levelID.",User_Integral=User_Integral+".$_POST['Value'],"where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_POST["UserID"]);
			if($Set){
				$Data=array("status"=>1,"lvl"=>1,"level"=>$levelName);
			}else{
				$Data=array("status"=>0,"msg"=>"写入数据库失败");
			}
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $SiteName;?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a>
          <dl>
            <dd class="first"><a href="lbs.php">一键导航设置</a></dd>
          </dl>
        </li>
        <li class="cur"> <a href="user_list.php">会员管理</a>
          <dl>
            <dd class="first"><a href="user_level.php">会员等级设置</a></dd>
            <dd class=""><a href="user_profile.php">会员注册资料</a></dd>
            <dd class=""><a href="card_benefits.php">会员权利说明</a></dd>
            <dd class=""><a href="user_list.php">会员管理</a></dd>
          </dl>
        </li>
        <li class=""> <a href="card_config.php">会员卡设置</a></li>
        
        <li class=""><a href="business_password.php">商家密码设置</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
    <script language="javascript">
var level_ary=Array();
	<?php foreach($UserLevel as $k=>$v){
		echo 'level_ary['.$k.']="'.$v['Name'].'";';
	}?>
	$(document).ready(function(){user_obj.user_init();});
</script>
    <div id="update_post_tips"></div>
    <div id="user" class="r_con_wrap">
      
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">来源</td>
            <td width="10%" nowrap="nowrap">手机号</td>
            <td width="10%" nowrap="nowrap">姓名</td>
            <td width="12%" nowrap="nowrap">所在地区</td>
            <td width="10%" nowrap="nowrap">头像</td>
            <td width="10%" nowrap="nowrap">会员卡号</td>
            <td width="10%" nowrap="nowrap">会员等级</td>
            <td width="7%" nowrap="nowrap">积分</td>
            <td width="12%" nowrap="nowrap">注册时间</td>
			<td width="7%" nowrap="nowrap">操作</td>
            
        </thead>
        <tbody>
          <tr UserID="<?php echo $rsUser['User_ID'] ?>">
            <td nowrap="nowrap"><?php echo $rsUser['User_From']==0?'微信':'注册'?></td>
            <td nowrap="nowrap" class="upd_rows" field="2"><span class="upd_txt"><?php echo $rsUser['User_Mobile'] ?></span></td>
            <td nowrap="nowrap" class="upd_rows" field="1"><span class="upd_txt"><?php echo $rsUser['User_Name'] ?></span></td>
            <td nowrap="nowrap"><?php echo $rsUser['User_Province'].$rsUser['User_City'] ?></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap" class="upd_rows" field="0">No. <span class="upd_txt"><?php echo $rsUser['User_No'] ?></span></td>
            <td nowrap="nowrap" class="upd_select" field="5"><span class="upd_txt"><?php echo $UserLevel[$rsUser["User_Level"]]["Name"] ?></span></td>
            <td nowrap="nowrap" class="upd_points" field="3"><span class="upd_txt"><?php echo $rsUser['User_Integral'] ?></span></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsUser['User_CreateTime']) ?></td>
            <td nowrap="nowrap" class="last">
			<?php echo $rsUser['User_From']!=0?'<a href="#modpass"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> ':'' ?>
			</td>
		  </tbody>
      </table>
      <div class="blank20"></div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="15%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">类型</td>
            <td width="10%" nowrap="nowrap">积分</td>
            <td width="10%" nowrap="nowrap">帐户剩余积分</td>
            <td width="25%" nowrap="nowrap">内容</td>
            <td width="10%" nowrap="nowrap">操作员</td>
            <td width="20%" nowrap="nowrap">时间</td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("user_integral_record","*","where Users_ID='".$_SESSION['Users_ID']."' and User_ID=".$UserID." order by Record_ID desc",$pageSize=10);
		$i=1;
		while($rsRecord=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td nowrap="nowrap"><?php echo $RecordType[$rsRecord["Record_Type"]] ?></td>
            <td nowrap="nowrap"><?php echo $rsRecord["Record_Integral"] ?></td>
            <td nowrap="nowrap"><?php echo $rsRecord["Record_SurplusIntegral"] ?></td>
            <td nowrap="nowrap"><?php echo $rsRecord["Record_Description"] ?></td>
            <td nowrap="nowrap"><?php echo $rsRecord["Operator_UserName"] ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsRecord["Record_CreateTime"]) ?></td>
          </tr>
          <?php $i++;
		  }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
    <div id="mod_user_pass" class="lean-modal lean-modal-form">
      <div class="h">修改会员密码<span></span><a class="modal_close" href="#"></a></div>
      <form class="form">
        <div class="rows">
          <label>密码：</label>
          <span class="input">
          <input name="Password" value="" type="text" class="form_input" size="26" maxlength="16" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="submit">
          <input type="submit" value="确定提交" name="submit_btn">
          </span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="UserID" value="">
        <input type="hidden" name="action" value="mod_password">
      </form>
      <div class="tips"></div>
    </div>
  </div>
</div>
</body>
</html>