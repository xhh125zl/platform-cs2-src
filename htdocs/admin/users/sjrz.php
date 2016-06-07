<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$Keywords=empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);
$Status=empty($_REQUEST["Status"])?0:$_REQUEST["Status"];
$orderby=empty($_REQUEST["orderby"])?"addtime desc":trim($_REQUEST["orderby"]);
$condition = "where 1";
if($Keywords){
	$condition .= " and (company like '%".$Keywords."%' or contact like '%".$Keywords."%')";
}
if($Status){
	$condition .= " and status=".($Status-1);
}
$orderby = $orderby ? " order by ".$orderby : " order by addtime desc";

//删除开始
if(!empty($_GET["action"])&&$_GET["action"]=="Del"){
	$ID=empty($_GET["ID"]) ? "0" : $_GET["ID"];
	mysql_query("delete from comein where itemid='".$ID."'");
	echo "<script language='javascript'>";
	echo "alert('删除成功！');";
	echo "window.open('sjrz.php','_self');";
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
        <li><a href="index.php">商家管理</a></li>
        <li><a href="add.php">添加商家</a></li>
		<li class="cur"><a href="sjrz.php">入驻申请</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="">
        关键字：<input name="Keywords" value="<?php echo $Keywords;?>" type="text" class="form_input" size="30"/>&nbsp;
		状态：
		<select name="Status">
			<option value="0">全部</option>
			<option value="1"<?php echo $Status==1 ? ' selected' : ''?>>未查看</option>
			<option value="2"<?php echo $Status==2 ? ' selected' : ''?>>已查看</option>
		</select>
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
		    <td width="15%" nowrap="nowrap">ID</td>
            <td width="20%" nowrap="nowrap">商家名称</td>
            <td width="15%" nowrap="nowrap">所属行业</td>
            <td width="20%" nowrap="nowrap">状态</td>
            <td width="20%" nowrap="nowrap">申请时间</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
			$lists = array();
			$DB->getPage("comein","*",$condition.$orderby,10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			foreach($lists as $t){
				$industry = $DB->GetRs("industry","name","where id=".$t["industry"]);
				$t["Industry"] = $industry["name"];
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $t["itemid"] ?></td>
            <td nowrap="nowrap"><?php echo $t["company"] ?></td>
            <td nowrap="nowrap"><?php echo $t["Industry"] ?></td>
            <td nowrap="nowrap"><?php echo $t["status"]==0 ? '<font style="color:red">未查看</font>' : '<font style="color:blue">已查看</font>'; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$t["addtime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="sjrz_view.php?itemid=<?php echo $t["itemid"];?>"><img src="/static/admin/images/ico/view.gif" align="absmiddle" alt="详情" title="详情" /></a>&nbsp;<a href="?action=Del&ID=<?php echo $t["itemid"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
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