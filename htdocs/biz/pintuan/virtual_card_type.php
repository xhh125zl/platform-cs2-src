<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

//设置常规固定常量
define('NO_RELATION_PRODUCTS_STATUS', '未设置关联商品');
define('RELATION_PRODUCTS_ERROR', '关联产品设置出错');
define('CARD_NORMAL', '未使用');
define('CARD_ABNORMAL', '已使用');

if (isset($_GET['action'])) {
  if ($_GET['action'] == 'del') {
    $Flag=$DB->Del("pintuan_virtual_card_type","Users_ID='{$UsersID}' and Type_id=".$_GET["TypeId"]);
    if($Flag){
      echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
    }else{
      echo '<script language="javascript">alert("删除失败");history.back();</script>';
    }
    exit;
  }
}


$condition = "WHERE `Users_ID` = '{$UsersID}'";



$condition .= " ORDER BY Type_Id DESC";
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
<style type="text/css">
	span.red, span.error, span.normal { display: inline-block; overflow: hidden; padding: 2px 5px; background: #c09853; color: #FFF; border-radius: 3px; }
	span.error { background: red; }
	span.normal { background: #468847; }
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    
    <div class="r_nav">
      <ul>
        <li><a href="virtual_card.php">虚拟卡密列表</a></li>
        <li><a href="virtual_card_add.php">添加卡密</a></li>
        <li class="cur"><a href="virtual_card_type.php">卡密类型列表</a></li>
        <li><a href="virtual_card_type_add.php">添加卡密类型</a></li>
      </ul>
    </div>
    
    <div id="products" class="r_con_wrap"> 
      <div class="control_btn"><a href="virtual_card_type_add.php" class="btn_green btn_w_120">添加类型</a></div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="8%" nowrap="nowrap" style="text-align:left; padding-left:10px;">类型名称</td>
            <td width="15%" nowrap="nowrap">添加时间</td>
            <td width="22%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
        	$List = Array();
        	$DB->getPage("pintuan_virtual_card_type","*",$condition,10);
        	while ($r=$DB->fetch_assoc()) { $List[] = $r; }
        	foreach ($List as $k => $v) {
        ?>
          <tr>
            <td nowrap="nowrap"><?php echo $v['Type_Id']; ?></td>
            <td width="15%" style="text-align:left; padding-left:10px;"><?php echo $v['Type_Name']; ?></td>
            <td><?php echo date('Y-m-d H:i:s', $v['Type_CreateTime']); ?></td>
            
            <td class="last" nowrap="nowrap"><a href="virtual_card_type_edit.php?TypeId=<?php echo $v['Type_Id']; ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改"></a>
			<a href="virtual_card_type.php?action=del&amp;TypeId=<?php echo $v['Type_Id']; ?>" onclick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除"></a>
			</td>
          </tr>

        <?php } ?>
        </tbody>
      </table>

      <div class="blank20"></div>
      <?php $DB->showPage(); ?>

    </div>
  </div>
</div>
</body>
</html>