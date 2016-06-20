<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("biz_apply","Users_ID='".$_SESSION["Users_ID"]."' and ItemID=".$_GET["itemid"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
	
	if($_GET["action"]=="read"){
		$Flag=$DB->Set("biz_apply",array("IsRead"=>1),"where Users_ID='".$_SESSION["Users_ID"]."' and ItemID=".$_GET["itemid"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("处理成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("处理失败");history.back();</script>';
		}
		exit;
	}
}
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
if(isset($_GET['search'])){
	if($_GET['Keyword']){
		$condition .= " and ".$_GET['Fields']." like '%".$_GET['Keyword']."%'";
	}
	if($_GET['Status']!=""){
		$condition .= " and IsRead=".intval($_GET['Status']);
	}
}

$salesman_array = array();
$is_salesman_array = array();
$DB->Get("distribute_account","Real_Name,Invitation_Code,Is_Salesman","where Users_ID='".$_SESSION["Users_ID"]."' and Is_Salesman=1 and Invitation_Code <> ''");
while($row = $DB->fetch_assoc()){
    if (!empty($row['Invitation_Code'])){
        $salesman_array[$row['Invitation_Code']] = $row['Real_Name'];
	$is_salesman_array[$row['Invitation_Code']] = $row['Is_Salesman'];
    }
}

$shop_cate = array();
$DB->get("shop_category","Category_ID,Category_Name","where Users_ID='".$_SESSION["Users_ID"]."'");
while ($r = $DB->fetch_assoc()) {
    $shop_cate[$r['Category_ID']] = $r['Category_Name']; 
}
$condition .= " order by CreateTime desc";

$_Status = array('<font style="color:#ff0000">未处理</font>','<font style="color:blue">已处理</font>');
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
<style type="text/css">
#bizs .search{padding:10px; background:#f7f7f7; border:1px solid #ddd; margin-bottom:8px; font-size:12px;}
#bizs .search *{font-size:12px;}
#bizs .search .search_btn{background:#1584D5; color:white; border:none; height:22px; line-height:22px; width:50px;}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li><a href="index.php">商家列表</a></li>
        <li><a href="group.php">商家分组</a></li>
		<li class="cur"><a href="apply.php">入驻申请列表</a></li>
		<li><a href="apply_config.php">入驻设置</a></li>
      </ul>
    </div>
	
    <div id="bizs" class="r_con_wrap">
      <form class="search" method="get" action="?">
        <select name="Fields">
          <option value="Name">企业名称</option>
          <option value="Contact">联系人</option>
          <option value="Mobile">联系电话</option>
		  <option value="Email">电子邮箱</option>
        </select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />       
        状态：
        <select name="Status">
          <option value="">全部</option>
          <option value="0">未处理</option>
          <option value="1">已处理</option>
        </select>
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="6%" nowrap="nowrap">ID</td>
            <td width="8%" nowrap="nowrap">邀请码</td>
            <td width="8%" nowrap="nowrap">推荐人</td>
            <td width="14%" nowrap="nowrap">企业名称</td>
            <td width="18%" nowrap="nowrap">行业类别</td>
            <td width="11%" nowrap="nowrap">联系人</td>
            <td width="11%" nowrap="nowrap">联系电话</td>
			<td width="11%" nowrap="nowrap">电子邮箱</td>
            <td width="13%" nowrap="nowrap">申请时间</td>
            <td width="8%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php 
		  $lists = array();
		  $DB->getPage("biz_apply","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$rsBiz){
		?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $rsBiz["ItemID"] ?></td>
            <td><?php echo $rsBiz["Invitation_Code"] ?></td>
            <td><?php if(!empty($rsBiz["Invitation_Code"])){if($is_salesman_array[$rsBiz["Invitation_Code"]]!= 1){echo '业务员被删除';}else{echo strlen($salesman_array[$rsBiz["Invitation_Code"]])>0?$salesman_array[$rsBiz["Invitation_Code"]]:'无昵称';} }?></td>
            <td><?php echo $rsBiz["Biz_Name"] ?></td>
            <td><?php echo !empty($shop_cate[$rsBiz["Category_ID"]])?$shop_cate[$rsBiz["Category_ID"]]:''; ?></td>
            <td nowrap="nowrap"><?php echo $rsBiz['Contact']; ?></td>
            <td nowrap="nowrap"><?php echo $rsBiz["Mobile"];?></td>
			<td nowrap="nowrap"><?php echo $rsBiz["Email"];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsBiz["CreateTime"]) ?></td>
            <td nowrap="nowrap"><?php echo $_Status[$rsBiz["IsRead"]]; ?></td>
            <td class="last" nowrap="nowrap"><?php if($rsBiz["IsRead"] < 1){?><a href="?action=read&itemid=<?php echo $rsBiz["ItemID"] ?>">[处理]</a><?php } ?><a href="?action=del&itemid=<?php echo $rsBiz["ItemID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};">[删除]</a></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
      <div style="background:#F7F7F7; border:1px #dddddd solid; height:40px; line-height:40px; font-size:12px; margin:10px 0px; padding-left:15px; color:#ff0000">提示：商家入驻地址 <a href="/api/<?php echo $_SESSION["Users_ID"];?>/biz_apply/" target="_blank">http://<?php echo $_SERVER['HTTP_HOST'];?>/api/<?php echo $_SESSION["Users_ID"];?>/biz_apply/</a></div>
    </div>
  </div>
</div>
</body>
</html>