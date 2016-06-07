<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["BIZ_ID"]))
{
	header("location:/biz/login.php");
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
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li> <a href="coupon_list.php">优惠券管理</a></li>
        <li> <a href="coupon_add.php">添加优惠券</a></li>
        <li class="cur"> <a href="coupon_list_logs.php">使用记录</a></li>
      </ul>
    </div>
    <div id="coupon_list" class="r_con_wrap">
      <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="r_con_table">
        <thead>
          <tr>
            <td width="12%"><strong>序号</strong></td>
            <td width="28%"><strong>优惠券名称</strong></td>
            <td width="12%"><strong>会员名称</strong></td>
            <td width="12%"><strong>消费金额</strong></td>
            <td width="12%"><strong>剩余使用次数</strong></td>
            <td width="12%"><strong>使用时间</strong></td>
            <td width="12%" class="last"><strong>操作员</strong></td>
          </tr>
        </thead>
        <tbody>
         <?php
		  $DB->getPage("user_coupon_logs","*","where Biz_ID=".$_SESSION["BIZ_ID"]." order by Logs_CreateTime desc",$pageSize=10);
		  $i=1;
		  while($rsLogs=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsLogs['Coupon_Subject'] ?></td>
            <td nowrap="nowrap"><?php echo $rsLogs['User_Name'] ?></td>
            <td nowrap="nowrap">￥<?php echo $rsLogs['Logs_Price'] ?></td>
            <td nowrap="nowrap"><?php echo $rsLogs['Coupon_UsedTimes']==-1?'不限':$rsLogs['Coupon_UsedTimes'].'次' ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsLogs["Logs_CreateTime"]) ?></td>
            <td nowrap="nowrap" class="last"><?php echo $rsLogs['Operator_UserName'] ?></td>
          </tr>
          <?php
		  	  $i++;
		  }
		  ?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>