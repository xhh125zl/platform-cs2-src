<?php

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$condition = "where Users_ID='".$_SESSION["Users_ID"]."' order by Act_Score desc";
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
        <li class=""><a href="battle.php">活动管理</a></li>
        <li class="cur"><a href="battle_user.php">用户列表</a></li>
      </ul>
    </div>
    <div id="battle" class="r_con_wrap">
      <div class="control_btn"><a href="javascript:void(0);" onClick="history.back();" class="btn_gray">返回</a></div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">手机号</td>
            <td width="15%" nowrap="nowrap">姓名</td>
            <td width="15%" nowrap="nowrap">头像</td>
            <td width="15%" nowrap="nowrap">获得积分</td>
            <td width="15%" nowrap="nowrap">参与时间</td>
          </tr>
        </thead>
        <tbody>
<?php $DB->getPage("battle_sn","*","where Users_ID='".$_SESSION["Users_ID"]."' group by User_ID order by SN_ID asc",$pageSize=10);
		  $i=1;
		  while($rsBattle=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td nowrap="nowrap"><?php echo $rsBattle["User_Mobile"]; ?></td>
            <td nowrap="nowrap"><?php echo $rsBattle["User_Name"]; ?></td>
            <td nowrap="nowrap"><img height="50px" width="50px" src='<?php echo isset($rsBattle["User_Head"]) ? $rsBattle["User_Head"] : '/static/api/zhuli/images/user.jpg'; ?>' /></td>
            <td nowrap="nowrap"><?php echo $rsBattle["SN_Integral"]; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsBattle["SN_CreateTime"]); ?></td>
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