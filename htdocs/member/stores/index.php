<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("stores","Users_ID='".$_SESSION["Users_ID"]."' and Stores_ID=".$_GET["StoresID"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
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
    <link href='/static/member/css/stores.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/stores.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="index.php">门店管理</a></li>
      </ul>
    </div>
    <div id="stores" class="r_con_wrap">
      <div class="control_btn"> <a href="add.php" class="btn_green btn_w_120">新增门店</a>
        <div class="tips_info"><strong>提示：</strong>向公众帐号发送地理位置信息即可查看距离最近的门店信息并一键导航到门店</div>
      </div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%">序号</td>
            <td width="25%">门店名称</td>
            <td width="25%">联系电话</td>
            <td width="30%">详细地址</td>
            <td width="10%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("stores","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Stores_ID asc",10);
		  while($rsStores=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $rsStores["Stores_ID"] ?></td>
            <td><?php echo $rsStores["Stores_Name"] ?></td>
            <td nowrap="nowrap">【<?php echo str_replace("\n","】【",$rsStores["Stores_Telephone"]) ?>】</td>
            <td><?php echo $rsStores["Stores_Address"] ?></td>
            <td class="last"><a href="edit.php?StoresID=<?php echo $rsStores["Stores_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="index.php?action=del&StoresID=<?php echo $rsStores["Stores_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>