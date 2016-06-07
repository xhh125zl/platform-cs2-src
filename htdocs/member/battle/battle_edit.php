<?php 
$DB->showErr=false;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$BattleID=empty($_REQUEST['BattleID'])?0:$_REQUEST['BattleID'];
$rsBattle=$DB->GetRs("battle","*","where Users_ID='".$_SESSION["Users_ID"]."' and Battle_ID=".$BattleID);
$rsMaterial=$DB->GetRs("wechat_material","Material_Json","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='battle' and Material_TableID=".$BattleID);
$Material_Json=json_decode($rsMaterial['Material_Json'],true);
if($_POST){
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
	$StartTime=strtotime($Time[0]);
	$EndTime=strtotime($Time[1]);
	if($rsBattle){
		$Data=array(
			"Battle_Title"=>$_POST["Title"],
			"Battle_Keywords"=>$_POST["Keywords"],
			"Battle_ActivityName"=>$_POST["ActivityName"],
			"Battle_QuestionNum"=>$_POST["QuestionNum"],
			"Battle_AnswerQuertionNum"=>$_POST["AnswerQuertionNum"],
			"Battle_Integral"=>$_POST["Integral"],
			"Battle_BackgroundMusic"=>$_POST["BackgroundMusic"],
			"Battle_MusicPath"=>$_POST["MusicPath"],
			"Battle_IsSound"=>empty($_POST["IsSound"])?0:1,
			"Battle_LimitTime"=>$_POST["LimitTime"],
			"Battle_StartTime"=>$StartTime,
			"Battle_EndTime"=>$EndTime,
			"Battle_LotteryTimes"=>$_POST["LotteryTimes"],
			"Battle_EveryDayLotteryTimes"=>$_POST["EveryDayLotteryTimes"],
			"Battle_Rule1"=>$_POST["Rule1"],
			"Battle_Rule2"=>$_POST["Rule2"],
			"Battle_Rule3"=>$_POST["Rule3"],
			"Battle_Rule4"=>$_POST["Rule4"],
			"Battle_Rule5"=>$_POST["Rule5"],
			"Battle_Game1"=>$_POST["Game1"],
			"Battle_Game2"=>$_POST["Game2"],
			"Battle_Game3"=>$_POST["Game3"],
			"Battle_Game4"=>$_POST["Game4"],
			"Battle_Game5"=>$_POST["Game5"]
		);
		$Set=$DB->Set("battle",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Battle_ID=".$BattleID);
		$Flag=$Flag&&$Set;
		
		$Material=array(
			"Title"=>$_POST["Title"],
			"ImgPath"=>$_POST["ImgPath"],
			"TextContents"=>$_POST["TextContents"],
			"Url"=>"/api/".$_SESSION["Users_ID"]."/battle/".$BattleID."/"
		);
		$Data=array(
			"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE)
		);
		$Set=$DB->Set("wechat_material",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='battle' and Material_TableID=".$BattleID);
		$Flag=$Flag&&$Set;
		
		$Data=array(
			"Reply_Keywords"=>$_POST["Keywords"]
		);
		$Set=$DB->Set("wechat_keyword_reply",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='battle' and Reply_TableID=".$BattleID);
		$Flag=$Flag&&$Set;
	}else{
		$Data=array(
			"Battle_Title"=>$_POST["Title"],
			"Battle_Keywords"=>$_POST["Keywords"],
			"Battle_ActivityName"=>$_POST["ActivityName"],
			"Battle_QuestionNum"=>$_POST["QuestionNum"],
			"Battle_AnswerQuertionNum"=>$_POST["AnswerQuertionNum"],
			"Battle_Integral"=>$_POST["Integral"],
			"Battle_BackgroundMusic"=>$_POST["BackgroundMusic"],
			"Battle_MusicPath"=>$_POST["MusicPath"],
			"Battle_IsSound"=>empty($_POST["IsSound"])?0:1,
			"Battle_LimitTime"=>$_POST["LimitTime"],
			"Battle_StartTime"=>$StartTime,
			"Battle_EndTime"=>$EndTime,
			"Battle_LotteryTimes"=>$_POST["LotteryTimes"],
			"Battle_EveryDayLotteryTimes"=>$_POST["EveryDayLotteryTimes"],
			"Battle_Rule1"=>$_POST["Rule1"],
			"Battle_Rule2"=>$_POST["Rule2"],
			"Battle_Rule3"=>$_POST["Rule3"],
			"Battle_Rule4"=>$_POST["Rule4"],
			"Battle_Rule5"=>$_POST["Rule5"],
			"Battle_Game1"=>$_POST["Game1"],
			"Battle_Game2"=>$_POST["Game2"],
			"Battle_Game3"=>$_POST["Game3"],
			"Battle_Game4"=>$_POST["Game4"],
			"Battle_Game5"=>$_POST["Game5"],
			"Users_ID"=>$_SESSION["Users_ID"]
		);
		$Add=$DB->Add("battle",$Data);
		$TableID=$DB->insert_id();
		$Flag=$Flag&&$Add;
		
		$Material=array(
			"Title"=>$_POST["Title"],
			"ImgPath"=>$_POST["ImgPath"],
			"TextContents"=>$_POST["TextContents"],
			"Url"=>"/api/".$_SESSION["Users_ID"]."/battle/".$TableID."/"
		);
		$Data=array(
			"Users_ID"=>$_SESSION["Users_ID"],
			"Material_Table"=>"battle",
			"Material_TableID"=>$TableID,
			"Material_Display"=>0,
			"Material_Type"=>0,
			"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE),
			"Material_CreateTime"=>time()
		);
		$Add=$DB->Add("wechat_material",$Data);
		$MaterialID=$DB->insert_id();
		$Flag=$Flag&&$Add;
		
		$Data=array(
			"Users_ID"=>$_SESSION["Users_ID"],
			"Reply_Table"=>"battle",
			"Reply_TableID"=>$TableID,
			"Reply_Display"=>0,
			"Reply_Keywords"=>$_POST["Keywords"],
			"Reply_PatternMethod"=>1,
			"Reply_MsgType"=>1,
			"Reply_MaterialID"=>$MaterialID,
			"Reply_CreateTime"=>time()
		);
		$Add=$DB->Add("wechat_keyword_reply",$Data);
		$Flag=$Flag&&$Add;
	}
	
		
	if($Flag){
		$DB->query("commit");
		$Data=array(
			"status"=>1
		);
	}else{
		$DB->query("roolback");
		$Data=array(
			"status"=>0
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/third_party/uploadify/jquery.uploadify.min.js'></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content"> 
    <script type='text/javascript' src='/static/member/js/battle.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class=""><a href="exam.php">题库管理</a></li>
        <li class="cur"><a href="battle.php">活动管理</a></li>
        <li class=""><a href="battle_user.php">用户列表</a></li>
      </ul>
    </div>
    <div id="battle" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
      <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
      <script language="javascript">$(document).ready(battle_obj.activity_init);</script>
      <form id="battle_form" class="r_con_form" method="post" action="battle.php">
        <div class="rows">
          <label>触发关键词</label>
          <span class="input">
          <input type="text" class="form_input" name="Keywords" value="<?php echo $rsBattle["Battle_Keywords"] ?>" size="25" notnull />
          <font class="fc_red">*</font> <span class="tips">如果有多个关键词请用空格隔开每个关键词</span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息标题</label>
          <span class="input">
          <input type="text" class="form_input" name="Title" value="<?php echo $rsBattle["Battle_Title"] ?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息封面</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input name="ImgUpload" id="ImgUpload" type="file" />
            </div>
            <div class="tips">图片建议尺寸：640*360px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="TextContents" class="textarea"><?php echo empty($Material_Json["TextContents"])?'':$Material_Json["TextContents"] ?></textarea>
          <br>
          <span class="tips">显示在图文封面下方</span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动名称</label>
          <span class="input">
          <input type="text" name="ActivityName" value="<?php echo $rsBattle["Battle_ActivityName"] ?>" class="form_input" size="40" maxlength="100" notnull />
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>题目数量</label>
          <span class="input">
          <select name="QuestionNum">
            <option value="1"<?php echo $rsBattle["Battle_QuestionNum"]==1?' selected':'' ?>>1</option>
            <option value="2"<?php echo $rsBattle["Battle_QuestionNum"]==2?' selected':'' ?>>2</option>
            <option value="3"<?php echo $rsBattle["Battle_QuestionNum"]==3?' selected':'' ?>>3</option>
            <option value="4"<?php echo $rsBattle["Battle_QuestionNum"]==4?' selected':'' ?>>4</option>
            <option value="5"<?php echo $rsBattle["Battle_QuestionNum"]==5?' selected':'' ?>>5</option>
            <option value="6"<?php echo $rsBattle["Battle_QuestionNum"]==6?' selected':'' ?>>6</option>
          </select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>得分方式</label>
          <span class="input"><span class="tips">每答对
          <input type="text" name="AnswerQuertionNum" value="<?php echo empty($rsBattle["Battle_AnswerQuertionNum"])?1:$rsBattle["Battle_AnswerQuertionNum"] ?>" class="form_input" size="4" maxlength="10" notnull />
          题可获得
          <input type="text" name="Integral" value="<?php echo empty($rsBattle["Battle_Integral"])?10:$rsBattle["Battle_Integral"] ?>" class="form_input" size="4" maxlength="10" notnull />
          积分</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>背景音乐</label>
          <span class="input">
          <input type="text" name="BackgroundMusic" value="<?php echo empty($rsBattle["Battle_BackgroundMusic"])?'/static/api/media/battle_timer.mp3':$rsBattle["Battle_BackgroundMusic"] ?>" class="form_input" size="50" maxlength="10" />
          <input type="checkbox" name="IsSound" value="1"<?php echo empty($rsBattle["Battle_IsSound"])?'':' checked' ?>/>
          <span class="tips">开启背景音乐(音乐在倒数10秒后响起)</span> <span class="upload_file">
          <div>
            <div class="up_input">
              <input name="MusicUpload" id="MusicUpload" type="file" />
            </div>
            <div class="tips">500KB以内，mp3格式</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="MusicDetail"></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>答题超时</label>
          <span class="input">
          <input type="text" name="LimitTime" value="<?php echo empty($rsBattle["Battle_LimitTime"])?20:$rsBattle["Battle_LimitTime"] ?>" class="form_input" size="4" maxlength="100" />
          <span class="tips">秒</span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动起止时间</label>
          <span class="input">
          <input name="Time" type="text" value="<?php echo date("Y-m-d H:i:s",empty($rsBattle["Battle_StartTime"])?time():$rsBattle["Battle_StartTime"])." - ".date("Y-m-d H:i:s",empty($rsBattle["Battle_EndTime"])?time():$rsBattle["Battle_EndTime"]) ?>" class="form_input" size="42" readonly notnull />
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人可参与的总次数</label>
          <span class="input">
          <input type="text" class="form_input" name="LotteryTimes" value="<?php echo empty($rsBattle["Battle_LotteryTimes"])?999:$rsBattle["Battle_LotteryTimes"] ?>" size="8" maxlength="4" notnull />
          <span class="fc_red">*</span> <span class="tips">次数必须是大于1且小于10000</span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人每天可参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="EveryDayLotteryTimes" value="<?php echo empty($rsBattle["Battle_EveryDayLotteryTimes"])?5:$rsBattle["Battle_EveryDayLotteryTimes"] ?>" maxlength="4" size="8" notnull />
          <span class="fc_red">*</span> <span class="tips">次数必须是大于1且不能大于每人可以参与的总次数</span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>积分规则1</label>
          <span class="input">
          <input type="text" name="Rule1" value="<?php echo empty($rsBattle["Battle_Rule1"])?'每答对一题可获得10积分':$rsBattle["Battle_Rule1"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>积分规则2</label>
          <span class="input">
          <input type="text" name="Rule2" value="<?php echo empty($rsBattle["Battle_Rule2"])?'积分计入会员中心':$rsBattle["Battle_Rule2"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>积分规则3</label>
          <span class="input">
          <input type="text" name="Rule3" value="<?php echo empty($rsBattle["Battle_Rule3"])?'使用积分可在会员中心兑换礼品':$rsBattle["Battle_Rule3"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>积分规则4</label>
          <span class="input">
          <input type="text" name="Rule4" value="<?php echo empty($rsBattle["Battle_Rule4"])?'':$rsBattle["Battle_Rule4"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>积分规则5</label>
          <span class="input">
          <input type="text" name="Rule5" value="<?php echo empty($rsBattle["Battle_Rule5"])?'':$rsBattle["Battle_Rule5"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>游戏说明1</label>
          <span class="input">
          <input type="text" name="Game1" value="<?php echo empty($rsBattle["Battle_Game1"])?'每天可参与5次':$rsBattle["Battle_Game1"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>游戏说明2</label>
          <span class="input">
          <input type="text" name="Game2" value="<?php echo empty($rsBattle["Battle_Game2"])?'每道题目答题时间为20秒':$rsBattle["Battle_Game2"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>游戏说明3</label>
          <span class="input">
          <input type="text" name="Game3" value="<?php echo empty($rsBattle["Battle_Game3"])?'每周统计排行榜':$rsBattle["Battle_Game3"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>游戏说明4</label>
          <span class="input">
          <input type="text" name="Game4" value="<?php echo empty($rsBattle["Battle_Game4"])?'':$rsBattle["Battle_Game4"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>游戏说明5</label>
          <span class="input">
          <input type="text" name="Game5" value="<?php echo empty($rsBattle["Battle_Game5"])?'':$rsBattle["Battle_Game5"] ?>" class="form_input" size="60" maxlength="100" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>&nbsp;</label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="javascript:void(0);" onClick="history.back();" class="btn_gray">返回</a> </span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="BattleID" value="<?php echo empty($rsBattle["Battle_ID"])?0:$rsBattle["Battle_ID"] ?>" />
        <input type="hidden" name="ImgPath" value="<?php echo empty($Material_Json["ImgPath"])?'':$Material_Json["ImgPath"] ?>" />
        <input type="hidden" name="MusicPath" value="<?php echo empty($rsBattle["Battle_MusicPath"])?'/static/api/media/battle_timer.mp3':$rsBattle["Battle_MusicPath"] ?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>