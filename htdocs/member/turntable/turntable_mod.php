<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$TurntableID=empty($_REQUEST['TurntableID'])?0:$_REQUEST['TurntableID'];
$rsTurntable=$DB->GetRs("turntable","*","where Users_ID='".$_SESSION["Users_ID"]."' and Turntable_ID=".$TurntableID);
if($_POST){
	$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
	$StartTime=strtotime($Time[0]);
	$EndTime=strtotime($Time[1]);
	$Data=array(
		"Turntable_Title"=>$_POST['Title'],
		"Turntable_OverTimesTipsToday"=>$_POST['OverTimesTipsToday'],
		"Turntable_OverTimesTips"=>$_POST['OverTimesTips'],
		"Turntable_FirstPrizeCount"=>$_POST['FirstPrizeCount'],
		"Turntable_FirstPrizeProbability"=>$_POST['FirstPrizeProbability'],
		"Turntable_SecondPrizeCount"=>$_POST['SecondPrizeCount'],
		"Turntable_SecondPrizeProbability"=>$_POST['SecondPrizeProbability'],
		"Turntable_ThirdPrizeCount"=>$_POST['ThirdPrizeCount'],
		"Turntable_ThirdPrizeProbability"=>$_POST['ThirdPrizeProbability'],
		"Turntable_IsShowPrizes"=>empty($_POST['IsShowPrizes'])?0:$_POST['IsShowPrizes'],
		"Turntable_LotteryTimes"=>$_POST['LotteryTimes'],
		"Turntable_EveryDayLotteryTimes"=>$_POST['EveryDayLotteryTimes'],
		"Turntable_BusinessPassWord"=>$_POST['BusinessPassWord'],
		"Turntable_UsedIntegralValue"=>$_POST['UsedIntegralValue'],
		"Turntable_Description"=>$_POST['Description'],
		"Turntable_Status"=>2,
		"Turntable_More_Integral"=>$_POST["Turntable_More_Integral"],
		"Turntable_Share_num"=>$_POST['Share_num'],
	);
	if(empty($_POST['status'])){
		$Data["Turntable_StartTime"]=$StartTime;
		$Data["Turntable_EndTime"]=$EndTime;
		$Data["Turntable_FirstPrize"]=$_POST['FirstPrize'];
		$Data["Turntable_SecondPrize"]=$_POST['SecondPrize'];
		$Data["Turntable_ThirdPrize"]=$_POST['ThirdPrize'];
		$Data["Turntable_UsedIntegral"]=empty($_POST['UsedIntegral'])?0:$_POST['UsedIntegral'];
		$Data["Turntable_If_Share"]=empty($_POST['If_Share'])?0:$_POST['If_Share'];
	}
	$Flag=$DB->Set("turntable",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Turntable_ID=".$TurntableID);
	if($Flag){
		$Data=array(
			"status"=>1
		);
	}else{
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
var editor;
KindEditor.ready(function(K) {
	editor = K.create('textarea[name="Turntable_Description"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=turntable',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
	});
})
var editor2;
KindEditor.ready(function(K) {
	editor2 = K.create('textarea[name="Turntable_More_Integral"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=turntable',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
	});
})
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/turntable.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/turntable.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="index.php">欢乐大转盘</a></li>
      </ul>
    </div>
    <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
    <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
    <script language="javascript">$(document).ready(turntable_obj.wheel_mod);</script>
    <div id="wheel" class="r_con_wrap">
      <form class="r_con_form" id="wheel_form">
        <div class="rows">
          <label>活动名称</label>
          <span class="input">
          <input type="text" class="form_input" name="Title" value="<?php echo $rsTurntable['Turntable_Title'] ?>" size="50" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动时间</label>
          <span class="input">
          <input name="Time" type="text" value="<?php echo date("Y-m-d H:i:s",$rsTurntable["Turntable_StartTime"])." - ".date("Y-m-d H:i:s",$rsTurntable["Turntable_EndTime"]) ?>" class="form_input" size="42" readonly notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>超过当天参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="OverTimesTipsToday" value="<?php echo empty($rsTurntable['Turntable_OverTimesTipsToday'])?'亲，您今日的参与次数已经用完了，请明天再来吧！':$rsTurntable['Turntable_OverTimesTipsToday'] ?>" size="80" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>超过总参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="OverTimesTips" value="<?php echo empty($rsTurntable['Turntable_OverTimesTips'])?'亲，本次活动的累计可玩次数您已经用完了喔！敬请关注我们的下个活动吧~。':$rsTurntable['Turntable_OverTimesTips'] ?>" size="80" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrize" value="<?php echo $rsTurntable['Turntable_FirstPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrizeCount" value="<?php echo empty($rsTurntable['Turntable_FirstPrizeCount'])?0:$rsTurntable['Turntable_FirstPrizeCount'] ?>" maxlength="2" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于100</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrizeProbability" value="<?php echo empty($rsTurntable['Turntable_FirstPrizeProbability'])?0.00:$rsTurntable['Turntable_FirstPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrize" value="<?php echo $rsTurntable['Turntable_SecondPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrizeCount" value="<?php echo empty($rsTurntable['Turntable_SecondPrizeCount'])?0:$rsTurntable['Turntable_SecondPrizeCount'] ?>" maxlength="3" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于1000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrizeProbability" value="<?php echo empty($rsTurntable['Turntable_SecondPrizeProbability'])?0.00:$rsTurntable['Turntable_SecondPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrize" value="<?php echo $rsTurntable['Turntable_ThirdPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrizeCount" value="<?php echo empty($rsTurntable['Turntable_ThirdPrizeCount'])?0:$rsTurntable['Turntable_ThirdPrizeCount'] ?>" maxlength="4" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于10000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrizeProbability" value="<?php echo empty($rsTurntable['Turntable_ThirdPrizeProbability'])?0.00:$rsTurntable['Turntable_ThirdPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>是否显示奖品数量</label>
          <span class="input">
          <input type="checkbox" name="IsShowPrizes" value="1"<?php echo empty($rsTurntable["Turntable_IsShowPrizes"])?"":" checked"; ?> />
          <span class="tips">取消选择后在活动页面中将不会显示奖品数量</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人参与的总次数</label>
          <span class="input">
          <input type="text" class="form_input" name="LotteryTimes" value="<?php echo empty($rsTurntable['Turntable_LotteryTimes'])?400:$rsTurntable['Turntable_LotteryTimes'] ?>" size="8" maxlength="4" notnull />
          <span class="fc_red">*</span><span class="tips">数量必须是大于1且小于10000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人每天可参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="EveryDayLotteryTimes" value="<?php echo empty($rsTurntable['Turntable_EveryDayLotteryTimes'])?1:$rsTurntable['Turntable_EveryDayLotteryTimes'] ?>" maxlength="4" size="8" notnull />
          <span class="fc_red">*</span><span class="tips">数量必须是大于1且不能大于每人可以参与的总次数</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家兑奖密码</label>
          <span class="input">
          <input type="text" class="form_input" name="BusinessPassWord" value="<?php echo empty($rsTurntable['Turntable_BusinessPassWord'])?'666666':$rsTurntable['Turntable_BusinessPassWord'] ?>" size="20" maxlength="16" notnull />
          <span class="fc_red">*</span><span class="tips">在中奖手机上输入此码可直接使用SN码，不能超过16个字符。</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>积分抽奖</label>
          <span class="input">
          <input type="checkbox" value="1" name="UsedIntegral"<?php echo empty($rsTurntable["Turntable_UsedIntegral"])?"":" checked"; ?> />
          <span class="tips">参与抽奖需要使用积分，此功能需要会员登录后才能参与活动，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows none integral"<?php echo empty($rsTurntable["Turntable_UsedIntegral"])?'':' style="display: block;"'; ?>>
          <label>抽奖所需积分</label>
          <span class="input">
          <input name="UsedIntegralValue" type="text" value="<?php echo empty($rsTurntable['Turntable_UsedIntegralValue'])?0:$rsTurntable['Turntable_UsedIntegralValue'] ?>" class="form_input" size="5" maxlength="5" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>获取更多积分说明</label>
          <span class="input">
          <textarea name="Turntable_More_Integral" id="Turntable_More_Integral"><?php echo $rsTurntable['Turntable_More_Integral']; ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>分享抽奖</label>
          <span class="input">
          <input type="checkbox" value="1" name="If_Share"<?php echo empty($rsTurntable["Turntable_If_Share"])?"":" checked"; ?> />
          <span class="tips">此功能需要会员登录后才能参与活动，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows none Share_num"<?php echo empty($rsTurntable["Turntable_If_Share"])?'':' style="display: block;"'; ?>>
          <label>需分享人数</label>
          <span class="input">
          <input name="Share_num" type="text" value="<?php echo empty($rsTurntable['Turntable_Share_num'])?0:$rsTurntable['Turntable_Share_num'] ?>" class="form_input" size="5" maxlength="5" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动说明</label>
          <span class="input">
          <textarea name="Turntable_Description" id="Turntable_Description"><?php echo empty($rsTurntable['Turntable_Description'])?'本次活动规则受中国法律管辖':$rsTurntable['Turntable_Description'] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>&nbsp;</label>
          <span class="input">
          <?php if($rsTurntable['Turntable_Status']!=1){ ?><input type="submit" class="btn_green" name="submit_button" value="提交保存" /><?php }?>
          <a href="" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="action" value="mod">
        <input type="hidden" name="TurntableID" value="<?php echo $TurntableID ?>" />
        <input type="hidden" name="status" value="<?php echo $rsTurntable['Turntable_Status'] ?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>