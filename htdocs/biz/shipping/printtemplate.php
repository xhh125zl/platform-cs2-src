<?php
require_once('../global.php');
$UsersID = $rsBiz['Users_ID'];
if(!$UsersID) header("Location: /api/{$UsersID}/user/");
$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];
if(!empty($action)){
	if($action=="del"){
		//删除
		$Flag=$DB->Del("shop_shipping_print_template","usersid='".$UsersID."' and bizid=".$_SESSION["BIZ_ID"]." and itemid=".$_GET["itemid"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="printtemplate.php";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}else{
	$condition = "where usersid='".$UsersID."' and bizid=".$_SESSION["BIZ_ID"];
	if(!empty($_GET["search"])){
		if(!empty($_GET["kw"])){
			$condition .= " and title like '%".$_GET["kw"]."%'";
		}
		
		if(!empty($_GET["companyid"])){
			$condition .= " and companyid=".$_GET["companyid"];
		}
		
		if(!empty($_GET["enabled"])){
			$condition .= " and enabled=".$_GET["enabled"];
		}
	}
	$condition .= " order by itemid desc";
	$companys = $templates = array();
	$DB->Get("shop_shipping_company","Shipping_ID,Shipping_Name","where Users_ID='".$UsersID."' and Biz_ID=".$_SESSION["BIZ_ID"]);
	while($r = $DB->fetch_assoc()){
		$companys[$r["Shipping_ID"]] = $r;
	}
	
	$DB->getPage("shop_shipping_print_template","*",$condition,10);
	while($rs = $DB->fetch_assoc()){
		$templates[] = $rs;
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
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li><a href="config.php">运费设置</a></li>
        <li><a href="company.php">快递公司管理</a></li>
        <li><a href="template.php">快递模板</a></li>
		<li class="cur"><a href="printtemplate.php">运单模板</a></li>
      </ul>
    </div>
    <div class="r_con_wrap">
      <div class="control_btn">
      <a href="printtemplate_add.php" class="btn_green btn_w_120">添加</a>
      </div>
      
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">模板名称</td>
            <td width="15%" nowrap="nowrap">快递公司</td>
            <td width="20%" nowrap="nowrap">运单图例</td>
            <td width="10%" nowrap="nowrap">上偏移量</td>
            <td width="10%" nowrap="nowrap">左偏移量</td>
            <td width="10%" nowrap="nowrap">是否启用</td>
            <td width="15%" nowrap="nowrap" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
		<?php foreach($templates as $key=>$value):?>
           <tr>
            <td><?php echo $key+1;?></td>
            <td><?php echo $value["title"];?></td>
            <td><?php echo empty($companys[$value["companyid"]]) ? '' : $companys[$value["companyid"]]["Shipping_Name"];?></td>
            <td><?php echo $value["thumb"] ? '<img src="'.$value["thumb"].'" width="70" />' : '';?></td>
            <td><?php echo $value["offset_top"];?> mm</td>
            <td><?php echo $value["offset_left"];?> mm</td>
            <td><?php echo $value["enabled"] ? '<font style="color:blue">启用</font>' : '<font style="color:red">未启用</font>';?></td>
            <td nowrap="nowrap" class="last" style="line-height:22px;">
            <a href="printtemplate_set.php?itemid=<?php echo $value["itemid"];?>">[ 设计 ]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="printtemplate_view.php?itemid=<?php echo $value["itemid"];?>" target="_blank">[ 预览 ]</a><br />
            <a href="printtemplate_edit.php?itemid=<?php echo $value["itemid"];?>">[ 修改 ]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?action=del&itemid=<?php echo $value["itemid"];?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};">[ 删除 ]</a>
            </td>
          </tr>
      <?php endforeach; ?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>