<?php 
/*edit in 20160317*/
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("users_roles","users_account='".$_SESSION["Users_Account"]."' and id=".$_GET["id"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
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
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/guanggao.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/guanggao.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="charlist.php">角色管理</a></li>
        <li class=""><a href="emplist.php">员工管理</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(shop_obj.products_list_init);</script>
      <div class="control_btn">
      <a href="char_add.php" class="btn_green btn_w_120">添加角色</a>  
      </div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="10%" nowrap="nowrap">角色名称</td>
            <td width="8%" nowrap="nowrap">是否启用</td>
            <td width="64%" nowrap="nowrap">简要说明</td>            
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $j=0;
		  $lists = array();		  
		  $DB->getPage("users_roles","id,role,status,role_note,users_account,role_right","where users_account='".$_SESSION["Users_Account"]."' order by id desc",10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }		  
		  foreach($lists as $k=>$chars){
			  $j++;
			  ?>              
          <tr>
            <td nowrap="nowrap"><?php echo $j; ?></td>
            <td nowrap="nowrap"><?php echo $chars["role"]; ?></td>
            <td nowrap="nowrap"><?php echo $chars["status"] == 1?'是':'否'; ?></td>            
            <td nowrap="nowrap"><?php echo $chars["role_note"]; ?></td>
            <td class="last" nowrap="nowrap"><a href="char_edit.php?id=<?php echo $chars["id"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="charlist.php?action=del&id=<?php echo $chars["id"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
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