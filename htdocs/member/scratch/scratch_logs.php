<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
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
    <div id="sncode" class="r_con_wrap">
      <div class="control_btn"><a href="index.php" class="btn_gray">返回</a></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="40%">序号</td>
            <td width="60%">抽奖时间</td>
          </tr>
        </thead>
        <tbody>
        <?php $DB->getPage("scratch_sn","*","where Users_ID='".$_SESSION["Users_ID"]."' and Scratch_ID=".$_GET['ScratchID']." order by SN_CreateTime desc",$pageSize=10);
		$i=1;
		while($rsSN=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsSN["SN_CreateTime"]) ?></td>
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