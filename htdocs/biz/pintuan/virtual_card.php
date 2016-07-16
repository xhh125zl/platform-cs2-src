<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/biz/global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

//设置常规固定常量
define('NO_RELATION_PRODUCTS_STATUS', '未设置关联商品');
define('RELATION_PRODUCTS_ERROR', '关联产品设置出错');
define('CARD_NORMAL', '未使用');
define('CARD_ABNORMAL', '已使用');

if(isset($_POST['action']) && $_POST['action'] == 'use')
{
    $cardid = intval($_POST['Card_Id']);
    $status = $_POST['Card_Status'];
    if($cardid){
        $DB->Set("pintuan_virtual_card", [ 'Card_Status'=> $status], "WHERE Users_ID='".$_SESSION["Users_ID"]."' AND Card_Id='{$cardid}'");
        die(json_encode([ 'status' => 1 ,'code'=> $status ]));
    }else{
        die(json_encode([ 'status' => 0 ,'code'=> $status ]));
    }
}

if (isset($_GET['action'])) {
  if ($_GET['action'] == 'del') {
    $Flag=$DB->Del("pintuan_virtual_card","Users_ID='".$_SESSION["Users_ID"]."' AND Card_Id=".$_GET["CardId"]);
    if($Flag){
      echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
    }else{
      echo '<script language="javascript">alert("删除失败");history.back();</script>';
    }
    exit;
  }
}

$Type_Arr = $DB->Get('pintuan_virtual_card_type', 'Type_Id, Type_Name', '');

$condition = "WHERE `Users_ID` = '".$_SESSION['Users_ID']."'";

if (isset($_GET['search'])) {
  if($_GET['Card_Name']){
    $condition .= " AND Card_Name LIKE '%".$_GET['Card_Name']."%'";
  }

  if($_GET["Type_Id"]){
    $condition .= " AND Type_Id=".(int)$_GET["Type_Id"];
  }
}

$condition .= " ORDER BY Card_Id DESC";
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
	span.red, span.error, span.normal { display: inline-block; overflow: hidden; padding: 2px 5px; background: #c09853; color: #FFF; border-radius: 3px; cursor:pointer;}
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
        <li class="cur"><a href="virtual_card.php">虚拟卡密列表</a></li>
        <li><a href="virtual_card_add.php">添加卡密</a></li>
        <li><a href="virtual_card_type.php">卡密类型列表</a></li>
        <li><a href="virtual_card_type_add.php">添加卡密类型</a></li>
      </ul>
    </div>
    
    <div id="products" class="r_con_wrap"> 
      <form class="search" method="get" action="?" style="display:block">
        卡号：
        <input type="text" name="Card_Name" value="" class="form_input" size="15" />&nbsp;
        栏目：
        <select name='Type_Id'>
          <option value=''>--请选择--</option>
          <?php while ($r = $DB->fetch_assoc($Type_Arr)) { ?>
          <option value="<?php echo $r['Type_Id']; ?>"><?php echo $r['Type_Name']; ?></option>
          <?php } ?>
        </select>&nbsp;
        
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form> 
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="8%" nowrap="nowrap" style="text-align:left; padding-left:10px;">虚拟卡号</td>
            <td width="8%" nowrap="nowrap" style="text-align:left; padding-left:10px;">密码</td>
            <td width="8%" nowrap="nowrap">卡类型</td>
            <td width="8%" nowrap="nowrap" style="text-align:left; padding-left:10px;" >关联产品名称</td>
            <td width="8%" nowrap="nowrap">卡状态</td>
            <td width="15%" nowrap="nowrap">添加时间</td>
            <td width="22%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
        	$List = Array();
        	$DB->getPage("pintuan_virtual_card","*",$condition,10);
        	while ($r=$DB->fetch_assoc()) { $List[] = $r; }
			
        	foreach ($List as $k => $v) {
        ?>
          <tr>
            <td nowrap="nowrap"><?php echo $v['Card_Id']; ?></td>
            <td width="15%" style="text-align:left; padding-left:10px;"><?php echo $v['Card_Name']; ?></td>
            <td width="15%" style="text-align:left; padding-left:10px;"><?php echo $v['Card_Password']; ?></td>
            <td nowrap="nowrap"><?php 
              if (!empty($v['Type_Id'])) {
                $type = $DB->GetRs('pintuan_virtual_card_type', 'Type_Name', 'WHERE Type_Id='.$v['Type_Id']);
                echo $type['Type_Name'];
              }
            ?></td>
            <td nowrap="nowrap" width="25%" style="text-align:left; padding-left:10px;"><?php 
            	if(empty($v['Products_Relation_ID'])){ 
            		echo '<span class="red">'.NO_RELATION_PRODUCTS_STATUS.'</span>'; 
            	} elseif(is_numeric($v['Products_Relation_ID'])) { 
            		$One = $DB->GetRs('pintuan_products', 'Products_Name', 'WHERE Products_ID='.$v['Products_Relation_ID']);
            		echo !empty($One) ? $One['Products_Name'] : '<span class="red">'."产品已清除".'</span>';
            	} ?>
            </td>
			<td><?php if ($v['Card_Status'] == 1) { echo '<span class="error">' . CARD_ABNORMAL .'</span>'; } else { echo '<span class="normal">' . CARD_NORMAL .'</span>'; } ?></td>
            <td><?php echo date('Y-m-d H:i:s', $v['Card_CreateTime']); ?></td>
            
            <td class="last" nowrap="nowrap"><a href="virtual_card_edit.php?CardId=<?php echo $v['Card_Id']; ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改"></a>
			<a href="virtual_card.php?action=del&amp;CardId=<?php echo $v['Card_Id']; ?>" onclick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除"></a>
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