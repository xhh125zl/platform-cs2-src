<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
if(isset($_POST['action'])){
	if($_POST['action']=='used'){
		$Data=array(
			"SN_UsedTimes"=>time(),
			"SN_Status"=>2
		);
		$Flag=$DB->Set("scratch_sn",$Data,"where Users_ID='".$_SESSION['Users_ID']."' and Scratch_ID=".$_POST["ScratchID"]." and SN_ID=".$_POST['SNID']);
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
	echo json_encode(empty($Data)?array("status"=>0):$Data,JSON_UNESCAPED_UNICODE);
	exit;
}
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Scratch_ID=".$_GET['ScratchID']." and SN_Code<>0 and SN_Status>0";
if(!empty($_GET["Keyword"])){
	$condition .= " and (SN_Code like '%".$_GET["Keyword"]."%' or User_Mobile like '%".$_GET["Keyword"]."%')";
}
if(!empty($_GET["SnStatus"])){
	$condition .= " and SN_Status=".$_GET["SnStatus"];
}
if(!empty($_GET["PrizeClass"])){
	$condition .= " and Scratch_PrizeID=".$_GET["PrizeClass"];
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
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
    <link href='/static/member/css/scratch.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/scratch.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="index.php">刮刮卡</a></li>
      </ul>
    </div>
    <script language="javascript">$(document).ready(scratch_obj.sn_init);</script>
    <div id="sncode" class="r_con_wrap">
      <div class="control_btn"><a href="index.php" class="btn_gray">返回</a></div>
      <form class="search" id="search_form" method="get" action="">
        关键字：
        <input type="text" name="Keyword" value="" class="form_input" size="15" />
        状态：
        <select name="SnStatus">
          <option value="">--请选择--</option>
          <option value="1">未使用</option>
          <option value="2">已使用</option>
        </select>
        奖品类别：
        <select name="PrizeClass">
          <option value="">--请选择--</option>
          <option value="1">一等奖</option>
          <option value="2">二等奖</option>
          <option value="3">三等奖</option>
        </select>
        <input type="submit" class="search_btn" value=" 搜索 " />
        <input type="hidden" name="ScratchID" value="<?php echo $_GET['ScratchID'];?>" />
      </form>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%">序号</td>
            <td width="14%">SN码</td>
            <td width="10%">状态</td>
            <td width="12%">奖品类别</td>
            <td width="16%">手机号码</td>
            <td width="16%">生成时间</td>
            <td width="16%">使用时间</td>
            <td width="8%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("scratch_sn","*",$condition." order by SN_CreateTime desc",$pageSize=10);
		$i=1;
		$level=array('谢谢参与','一等奖','二等奖','三等奖');
		while($rsSN=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td nowrap="nowrap"><?php echo $rsSN["SN_Code"] ?></td>
            <td nowrap="nowrap"><?php echo $rsSN["SN_Status"]==1?'未使用':'已使用' ?></td>
            <td nowrap="nowrap"><?php echo $level[$rsSN["Scratch_PrizeID"]] ?></td>
            <td nowrap="nowrap"><?php echo $rsSN["User_Mobile"] ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsSN["SN_CreateTime"]) ?></td>
            <td nowrap="nowrap"><?php echo empty($rsSN["SN_UsedTimes"])?'-':date("Y-m-d H:i:s",$rsSN["SN_UsedTimes"]) ?></td>
            <td nowrap="nowrap" class="last" Data='{"SNID":"<?php echo $rsSN["SN_ID"] ?>","ScratchID":"<?php echo $rsSN["Scratch_ID"] ?>"}'><?php echo $rsSN["SN_Status"]==1?'[<a href="#used">使用</a>]':'' ?></td>
          </tr>
          <?php $i++;
		  }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>