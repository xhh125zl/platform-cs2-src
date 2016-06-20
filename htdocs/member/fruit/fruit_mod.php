<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
$FruitID=empty($_REQUEST['FruitID'])?0:$_REQUEST['FruitID'];
$rsFruit=$DB->GetRs("fruit","*","where Users_ID='".$_SESSION["Users_ID"]."' and Fruit_ID=".$FruitID);
if($_POST){
	$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
	$StartTime=strtotime($Time[0]);
	$EndTime=strtotime($Time[1]);
	$Data=array(
		"Fruit_Title"=>$_POST['Title'],
		"Fruit_OverTimesTipsToday"=>$_POST['OverTimesTipsToday'],
		"Fruit_OverTimesTips"=>$_POST['OverTimesTips'],
		"Fruit_FirstPrizeCount"=>$_POST['FirstPrizeCount'],
		"Fruit_FirstPrizeProbability"=>$_POST['FirstPrizeProbability'],
		"Fruit_SecondPrizeCount"=>$_POST['SecondPrizeCount'],
		"Fruit_SecondPrizeProbability"=>$_POST['SecondPrizeProbability'],
		"Fruit_ThirdPrizeCount"=>$_POST['ThirdPrizeCount'],
		"Fruit_ThirdPrizeProbability"=>$_POST['ThirdPrizeProbability'],
		"Fruit_IsShowPrizes"=>empty($_POST['IsShowPrizes'])?0:$_POST['IsShowPrizes'],
		"Fruit_LotteryTimes"=>$_POST['LotteryTimes'],
		"Fruit_EveryDayLotteryTimes"=>$_POST['EveryDayLotteryTimes'],
		"Fruit_BusinessPassWord"=>$_POST['BusinessPassWord'],
		"Fruit_UsedIntegralValue"=>$_POST['UsedIntegralValue'],
		"Fruit_Description"=>$_POST['Fruit_Description'],
		"Fruit_Status"=>2
	);
	if(empty($_POST['status'])){
		$Data["Fruit_StartTime"]=$StartTime;
		$Data["Fruit_EndTime"]=$EndTime;
		$Data["Fruit_FirstPrize"]=$_POST['FirstPrize'];
		$Data["Fruit_SecondPrize"]=$_POST['SecondPrize'];
		$Data["Fruit_ThirdPrize"]=$_POST['ThirdPrize'];
		$Data["Fruit_UsedIntegral"]=empty($_POST['UsedIntegral'])?0:$_POST['UsedIntegral'];
	}
	$Flag=$DB->Set("fruit",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Fruit_ID=".$FruitID);
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

</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/fruit.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/fruit.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="index.php">欢乐水果达人</a></li>
      </ul>
    </div>
    <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
    <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
    <script language="javascript">$(document).ready(fruit_obj.wheel_mod);</script>
    <div id="wheel" class="r_con_wrap">
      <form class="r_con_form" id="wheel_form">
        <div class="rows">
          <label>活动名称</label>
          <span class="input">
          <input type="text" class="form_input" name="Title" value="<?php echo $rsFruit['Fruit_Title'] ?>" size="50" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动时间</label>
          <span class="input">
          <input name="Time" type="text" value="<?php echo date("Y-m-d H:i:s",$rsFruit["Fruit_StartTime"])." - ".date("Y-m-d H:i:s",$rsFruit["Fruit_EndTime"]) ?>" class="form_input" size="42" readonly notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>超过当天参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="OverTimesTipsToday" value="<?php echo empty($rsFruit['Fruit_OverTimesTipsToday'])?'亲，您今日的参与次数已经用完了，请明天再来吧！':$rsFruit['Fruit_OverTimesTipsToday'] ?>" size="80" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>超过总参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="OverTimesTips" value="<?php echo empty($rsFruit['Fruit_OverTimesTips'])?'亲，本次活动的累计可玩次数您已经用完了喔！敬请关注我们的下个活动吧~。':$rsFruit['Fruit_OverTimesTips'] ?>" size="80" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrize" value="<?php echo $rsFruit['Fruit_FirstPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrizeCount" value="<?php echo empty($rsFruit['Fruit_FirstPrizeCount'])?0:$rsFruit['Fruit_FirstPrizeCount'] ?>" maxlength="2" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于100</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>一等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="FirstPrizeProbability" value="<?php echo empty($rsFruit['Fruit_FirstPrizeProbability'])?0.00:$rsFruit['Fruit_FirstPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrize" value="<?php echo $rsFruit['Fruit_SecondPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrizeCount" value="<?php echo empty($rsFruit['Fruit_SecondPrizeCount'])?0:$rsFruit['Fruit_SecondPrizeCount'] ?>" maxlength="3" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于1000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>二等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="SecondPrizeProbability" value="<?php echo empty($rsFruit['Fruit_SecondPrizeProbability'])?0.00:$rsFruit['Fruit_SecondPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖奖品设置</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrize" value="<?php echo $rsFruit['Fruit_ThirdPrize'] ?>" size="40" maxlength="50" notnull />
          <font class="fc_red">*</font><span class="tips">不能超过50个字，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖奖品数量</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrizeCount" value="<?php echo empty($rsFruit['Fruit_ThirdPrizeCount'])?0:$rsFruit['Fruit_ThirdPrizeCount'] ?>" maxlength="4" size="8" notnull />
          <font class="fc_red">*</font><span class="tips">数量必须是大于1且小于10000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>三等奖中奖概率</label>
          <span class="input">
          <input type="text" class="form_input" name="ThirdPrizeProbability" value="<?php echo empty($rsFruit['Fruit_ThirdPrizeProbability'])?0.00:$rsFruit['Fruit_ThirdPrizeProbability'] ?>" size="8" notnull />
          <font class="tips">%</font> <font class="fc_red">*</font><span class="tips">百分比，在0-100之间, 支持小数点</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>是否显示奖品数量</label>
          <span class="input">
          <input type="checkbox" name="IsShowPrizes" value="1"<?php echo empty($rsFruit["Fruit_IsShowPrizes"])?"":" checked"; ?> />
          <span class="tips">取消选择后在活动页面中将不会显示奖品数量</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人参与的总次数</label>
          <span class="input">
          <input type="text" class="form_input" name="LotteryTimes" value="<?php echo empty($rsFruit['Fruit_LotteryTimes'])?400:$rsFruit['Fruit_LotteryTimes'] ?>" size="8" maxlength="4" notnull />
          <span class="fc_red">*</span><span class="tips">数量必须是大于1且小于10000</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>每人每天可参与次数</label>
          <span class="input">
          <input type="text" class="form_input" name="EveryDayLotteryTimes" value="<?php echo empty($rsFruit['Fruit_EveryDayLotteryTimes'])?1:$rsFruit['Fruit_EveryDayLotteryTimes'] ?>" maxlength="4" size="8" notnull />
          <span class="fc_red">*</span><span class="tips">数量必须是大于1且不能大于每人可以参与的总次数</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家兑奖密码</label>
          <span class="input">
          <input type="text" class="form_input" name="BusinessPassWord" value="<?php echo empty($rsFruit['Fruit_BusinessPassWord'])?'666666':$rsFruit['Fruit_BusinessPassWord'] ?>" size="20" maxlength="16" notnull />
          <span class="fc_red">*</span><span class="tips">在中奖手机上输入此码可直接使用SN码，不能超过16个字符。</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>积分抽奖</label>
          <span class="input">
          <input type="checkbox" value="1" name="UsedIntegral"<?php echo empty($rsFruit["Fruit_UsedIntegral"])?"":" checked"; ?> />
          <span class="tips">参与抽奖需要使用积分，此功能需要会员登录后才能参与活动，首次设置后不能修改</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows none integral"<?php echo empty($rsFruit["Fruit_UsedIntegral"])?'':' style="display: block;"'; ?>>
          <label>抽奖所需积分</label>
          <span class="input">
          <input name="UsedIntegralValue" type="text" value="<?php echo empty($rsFruit['Fruit_UsedIntegralValue'])?0:$rsFruit['Fruit_UsedIntegralValue'] ?>" class="form_input" size="5" maxlength="5" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动说明</label>
          <span class="input">
          <textarea name="Fruit_Description" id="Fruit_Description"><?php echo empty($rsFruit['Fruit_Description'])?'本次活动规则受中国法律管辖':$rsFruit['Fruit_Description'] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>&nbsp;</label>
          <span class="input">
          <?php if($rsFruit['Fruit_Status']!=1){ ?><input type="submit" class="btn_green" name="submit_button" value="提交保存" /><?php }?>
          <a href="" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="action" value="mod">
        <input type="hidden" name="FruitID" value="<?php echo $FruitID ?>" />
        <input type="hidden" name="status" value="<?php echo $rsFruit['Fruit_Status'] ?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>