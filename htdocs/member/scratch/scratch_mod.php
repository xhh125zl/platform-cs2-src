<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$ScratchID=empty($_REQUEST['ScratchID'])?0:$_REQUEST['ScratchID'];
$rsScratch=$DB->GetRs("scratch","*","where Users_ID='".$_SESSION["Users_ID"]."' and Scratch_ID=".$ScratchID);
if($_POST){
	$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
	$StartTime=strtotime($Time[0]);
	$EndTime=strtotime($Time[1]);
	$Data=array(
		"Scratch_Title"=>$_POST['Title'],
		"Scratch_OverTimesTipsToday"=>$_POST['OverTimesTipsToday'],
		"Scratch_OverTimesTips"=>$_POST['OverTimesTips'],
		"Scratch_FirstPrizeCount"=>$_POST['FirstPrizeCount'],
		"Scratch_FirstPrizeProbability"=>$_POST['FirstPrizeProbability'],
		"Scratch_SecondPrizeCount"=>$_POST['SecondPrizeCount'],
		"Scratch_SecondPrizeProbability"=>$_POST['SecondPrizeProbability'],
		"Scratch_ThirdPrizeCount"=>$_POST['ThirdPrizeCount'],
		"Scratch_ThirdPrizeProbability"=>$_POST['ThirdPrizeProbability'],
		"Scratch_IsShowPrizes"=>empty($_POST['IsShowPrizes'])?0:$_POST['IsShowPrizes'],
		"Scratch_LotteryTimes"=>$_POST['LotteryTimes'],
		"Scratch_EveryDayLotteryTimes"=>$_POST['EveryDayLotteryTimes'],
		"Scratch_BusinessPassWord"=>$_POST['BusinessPassWord'],
		"Scratch_UsedIntegralValue"=>$_POST['UsedIntegralValue'],
		"Scratch_Description"=>$_POST['Description'],
		"Scratch_Status"=>2,
		"Scratch_More_Integral"=>$_POST["Scratch_More_Integral"],
		"Scratch_Share_num"=>$_POST['Share_num'],
	);
	if(empty($_POST['status'])){
		$Data["Scratch_StartTime"]=$StartTime;
		$Data["Scratch_EndTime"]=$EndTime;
		$Data["Scratch_FirstPrize"]=$_POST['FirstPrize'];
		$Data["Scratch_SecondPrize"]=$_POST['SecondPrize'];
		$Data["Scratch_ThirdPrize"]=$_POST['ThirdPrize'];
		$Data["Scratch_UsedIntegral"]=empty($_POST['UsedIntegral'])?0:$_POST['UsedIntegral'];
		$Data["Scratch_If_Share"]=empty($_POST['If_Share'])?0:$_POST['If_Share'];
	}
	$Flag=$DB->Set("scratch",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Scratch_ID=".$ScratchID);
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
	editor = K.create('textarea[name="Scratch_Description"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=scratch',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
	});
})
var editor2;
KindEditor.ready(function(K) {
	editor2 = K.create('textarea[name="Scratch_More_Integral"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=scratch',
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
    <link href='/static/member/css/scratch.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/scratch.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="index.php">刮刮卡</a></li>
      </ul>
    </div>
    <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
    <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
    <script language="javascript">$(document).ready(scratch_obj.wheel_mod_init);</script>
    <div id="wheel" class="r_con_wrap">
      <form class="r_con_form" id="wheel_form">
        <div class="rows">
          <label>活动名称</label>
          <span class="input">
          <input type="text" class="form_input" name="Title" value="<?php echo $rsScratch['Scratch_Title'] ?>" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动时间</label>
          <span class="input">
          <input name="Time" type="text" value="<?php echo date("Y-m-d H:i:s",$rsScratch["Scratch_StartTime"])." - ".date("Y-m-d H:i:s",$rsScratch["Scratch_EndTime"]) ?>" class="form_input" size="40" readonly notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>超过当天参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="OverTimesTipsToday" value="<?php echo empty($rsScratch['Scratch_OverTimesTipsToday'])?'亲，您今日的参与次数已经用完了，请明天再来吧！':$rsScratch['Scratch_OverTimesTipsToday'] ?>" size="60" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>超过总参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="OverTimesTips" value="<?php echo empty($rsScratch['Scratch_OverTimesTips'])?'亲，本次活动的累计可玩次数您已经用完了喔！敬请关注我们的下个活动吧~。':$rsScratch['Scratch_OverTimesTips'] ?>" size="60" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrize" value="<?php echo $rsScratch['Scratch_FirstPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrizeCount" value="<?php echo empty($rsScratch['Scratch_FirstPrizeCount'])?0:$rsScratch['Scratch_FirstPrizeCount'] ?>" maxlength="2" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于100</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrizeProbability" value="<?php echo empty($rsScratch['Scratch_FirstPrizeProbability'])?0.00:$rsScratch['Scratch_FirstPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrize" value="<?php echo $rsScratch['Scratch_SecondPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrizeCount" value="<?php echo empty($rsScratch['Scratch_SecondPrizeCount'])?0:$rsScratch['Scratch_SecondPrizeCount'] ?>" maxlength="3" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于1000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrizeProbability" value="<?php echo empty($rsScratch['Scratch_SecondPrizeProbability'])?0.00:$rsScratch['Scratch_SecondPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrize" value="<?php echo $rsScratch['Scratch_ThirdPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrizeCount" value="<?php echo empty($rsScratch['Scratch_ThirdPrizeCount'])?0:$rsScratch['Scratch_ThirdPrizeCount'] ?>" maxlength="4" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于10000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrizeProbability" value="<?php echo empty($rsScratch['Scratch_ThirdPrizeProbability'])?0.00:$rsScratch['Scratch_ThirdPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>是否显示奖品数量</label>
          <span class="input">
          <input type="checkbox" name="IsShowPrizes" value="1"<?php echo empty($rsScratch["Scratch_IsShowPrizes"])?"":" checked"; ?> />
          <span class="tips">取消选择后在活动页面中将不会显示奖品数量</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人参与的总次数</label>
          <span class="input">
          <input type="text" class="form_input" name="LotteryTimes" value="<?php echo empty($rsScratch['Scratch_LotteryTimes'])?400:$rsScratch['Scratch_LotteryTimes'] ?>" size="8" maxlength="4" notnull />
          <span class="fc_red">*</span><span class="tips">数量必须是大于1且小于10000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人每天可参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="EveryDayLotteryTimes" value="<?php echo empty($rsScratch['Scratch_EveryDayLotteryTimes'])?1:$rsScratch['Scratch_EveryDayLotteryTimes'] ?>" maxlength="4" size="8" notnull />
          <span class="fc_red">*</span><span class="tips">数量必须是大于1且不能大于每人可以参与的总次数</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家兑奖密码</label>
          <span class="input">
          <input type="text" class="form_input" name="BusinessPassWord" value="<?php echo empty($rsScratch['Scratch_BusinessPassWord'])?'666666':$rsScratch['Scratch_BusinessPassWord'] ?>" size="20" maxlength="16" notnull />
          <span class="fc_red">*</span><span class="tips">在中奖手机上输入此码可直接使用SN码，不能超过16个字符。</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>积分抽奖</label>
          <span class="input">
          <input type="checkbox" value="1" name="UsedIntegral"<?php echo empty($rsScratch["Scratch_UsedIntegral"])?"":" checked"; ?> />
          <span class="tips">参与抽奖需要使用积分，此功能需要会员登录后才能参与活动，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows none integral"<?php echo empty($rsScratch["Scratch_UsedIntegral"])?'':' style="display: block;"'; ?>>
          <label>抽奖所需积分</label>
          <span class="input">
          <input name="UsedIntegralValue" type="text" value="<?php echo empty($rsScratch['Scratch_UsedIntegralValue'])?0:$rsScratch['Scratch_UsedIntegralValue'] ?>" class="form_input" size="5" maxlength="5" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>获取更多积分说明</label>
          <span class="input">
          <textarea name="Scratch_More_Integral" id="Scratch_More_Integral"><?php echo $rsScratch['Scratch_More_Integral']; ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>分享抽奖</label>
          <span class="input">
          <input type="checkbox" value="1" name="If_Share"<?php echo empty($rsScratch["Scratch_If_Share"])?"":" checked"; ?> />
          <span class="tips">此功能需要会员登录后才能参与活动，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows none Share_num"<?php echo empty($rsScratch["Scratch_If_Share"])?'':' style="display: block;"'; ?>>
          <label>需分享人数</label>
          <span class="input">
          <input name="Share_num" type="text" value="<?php echo empty($rsScratch['Scratch_Share_num'])?0:$rsScratch['Scratch_Share_num'] ?>" class="form_input" size="5" maxlength="5" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动说明</label>
          <span class="input">
          <textarea name="Scratch_Description" id="Scratch_Description"><?php echo empty($rsScratch['Scratch_Description'])?'本次活动规则受中国法律管辖':$rsScratch['Scratch_Description'] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>&nbsp;</label>
          <span class="input">
          <?php if($rsScratch['Scratch_Status']!=1){ ?><input type="submit" class="btn_green" name="submit_button" value="提交保存" /><?php }?>
          <a href="" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="action" value="mod">
        <input type="hidden" name="ScratchID" value="<?php echo $ScratchID ?>" />
        <input type="hidden" name="status" value="<?php echo $rsScratch['Scratch_Status'] ?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>