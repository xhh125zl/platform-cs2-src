<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$Keywords=empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);
$Status=empty($_REQUEST["Status"])?"":trim($_REQUEST["Status"]);
$orderby=empty($_REQUEST["orderby"])?"Users_CreateTime desc":trim($_REQUEST["orderby"]);
$condition = "where 1";
if($Keywords){
	$condition .= " and (Users_ID like '%".$Keywords."%' or Users_Account like '%".$Keywords."%')";
}
if($Status){
	$condition .= " and Users_Status=".$Status;
}
$orderby = $orderby ? " order by ".$orderby : " order by Users_ID desc";

//删除开始
if(!empty($_GET["action"])&&$_GET["action"]=="Del"){
	$ID=empty($_GET["ID"]) ? "0" : $_GET["ID"];
	mysql_query("delete from users where Users_ID='".$ID."'");
	echo "<script language='javascript'>";
	echo "alert('删除成功！');";
	echo "window.open('index.php','_self');";
	echo "</script>";
	exit();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="index.php">商家管理</a></li>
        <li><a href="add.php">添加商家</a></li>
		<li><a href="sjrz.php">入驻申请</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="">
        关键字：<input name="Keywords" value="<?php echo $Keywords;?>" type="text" class="form_input" size="30"/>
        <input type="submit" class="search_btn" value="用户搜索" />
      </form>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">商家ID</td>
            <td width="18%" nowrap="nowrap">登陆名称</td>
            <td width="15%" nowrap="nowrap">所属行业</td>
			<td width="15%" nowrap="nowrap">手机号码</td>			
            <td width="18%" nowrap="nowrap">到期时间</td>
            <td width="18%" nowrap="nowrap">注册时间</td>
            <td width="8%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
			$lists = array();
			$DB->getPage("users","*",$condition.$orderby,10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			foreach($lists as $rsUsers){
				$industry = $DB->GetRs("industry","name","where id=".$rsUsers["Users_Industry"]);
				$rsUsers["Industry"] = $industry["name"];
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $rsUsers["Users_ID"] ?></td>
            <td nowrap="nowrap"><?php echo $rsUsers["Users_Account"] ?></td>
            <td nowrap="nowrap"><?php echo $rsUsers["Industry"] ?></td>
			<td nowrap="nowrap"><?php echo $rsUsers["Users_Mobile"] ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsUsers["Users_ExpireDate"]); ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsUsers["Users_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="edit.php?UsersID=<?php echo $rsUsers["Users_ID"];?>"><img src="/static/admin/images/ico/mod.gif" align="absmiddle" alt="修改" title="修改" /></a>&nbsp;<a href="?action=Del&ID=<?php echo $rsUsers["Users_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
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