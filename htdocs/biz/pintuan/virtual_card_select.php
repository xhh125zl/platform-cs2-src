<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$condition = "WHERE `Users_ID` = '".$_SESSION['Users_ID']."' AND Card_Status=0";

$productId = "-1";
if (isset($_GET['productId']) && !empty($productId)) 
{
    $productId = intval($_GET['productId']);
    $condition .= " AND Products_Relation_ID IN(0, ".$productId.")";
} else {
    $condition .= " AND Products_Relation_ID=0";
}

if (isset($_GET['search'])) {
  if($_GET['Card_Name']){
    $condition .= " and Card_Name like '%".$_GET['Card_Name']."%'";
  }

  if($_GET["Type_Id"]){
    $condition .= " and Type_Id=".(int)$_GET["Type_Id"];
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
	span.red, span.error, span.normal { display: inline-block; overflow: hidden; padding: 2px 5px; background: #c09853; color: #FFF; border-radius: 3px; }
	span.error { background: red; }
	span.normal { background: #468847; }
	#iframe_page .iframe_content { padding: 0; }
	.control_btn a { display: block; overflow: hidden; float: left; height: 20px; width: 20px; text-align: center; line-height: 20px; background: #f5f5f5; margin-right: 3px; border: 1px solid #ccc; }
	.control_btn a.select { background: #1584D5; color: #fff; border-color: #1584D5; }
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div id="products" class="r_con_wrap"> 
      <form class="search" method="get" action="?" style="display:block">
        卡号：
        <input type="text" name="Card_Name" value="" class="form_input" size="15" />&nbsp;
        栏目：
        <select name='Type_Id'>
          <option value=''>--请选择--</option>
          <?php 
            $Type_Arr = $DB->Get('pintuan_virtual_card_type', 'Type_Id, Type_Name,Users_ID', "WHERE Users_ID = '".$_SESSION['Users_ID']."'");
            while ($r = $DB->fetch_assoc($Type_Arr)) { 
          ?>
          <option value="<?php echo $r['Type_Id']; ?>"><?php echo $r['Type_Name']; ?></option>
          <?php } ?>
        </select>&nbsp;
        
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form> 
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">选择</td>
            <td width="8%" nowrap="nowrap">虚拟卡号</td>
            <td width="8%" nowrap="nowrap">密码</td>
            <td width="15%" nowrap="nowrap">添加时间</td>
          </tr>
        </thead>
        <tbody>
    	<?php
	    	$List = Array();
	    	$DB->getPage("pintuan_virtual_card","*",$condition);
	    	while ($r=$DB->fetch_assoc()) { $List[] = $r; }
	    	foreach ($List as $k => $v) {
        ?>
	        <tr>
	          <td nowrap="nowrap"><input type="checkbox" name="select" class="listNum<?php echo $v['Card_Id']; ?>" value="<?php echo $v['Card_Id']; ?>" <?php if($v['Products_Relation_ID'] == $productId) { ?>checked="checked"<?php } ?>></td>
	          <td><?php echo $v['Card_Name']; ?></td>
	          <td><?php echo $v['Card_Password']; ?></td>
	          <td nowrap="nowrap"><?php echo date('Y-m-d H:i:s', $v['Card_CreateTime']); ?></td>
	        </tr>

	    <?php } ?>
        </tbody>
      </table>

      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
	    <div id="actionBox">
            <a href="javascript:void(0);" id="choiceAll">全选</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="javascript:void(0);" id="noChoice">反选</a>
            <button class="btn_green" style="float:right;margin-right:40%;" href="javascript:void(0);" id="addInsert">插入</button>
      	</div>
    </div>
  </div>
</div>
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
<script>
$(document).ready(function(){
    var ids = $('#cardids', parent.document).val();
    var str = new String();
    var arr = new Array();

    idsArr = ids.split(',');

    for (var i = 0; i < idsArr.length; i++) {
      $('.listNum'+idsArr[i]).attr('checked', 'checked');
    };

	$('#addInsert').click(function(){
    	var idList = '';
		$('input[name="select"]:checked').each(function(){
			idList += $(this).val() + ',';
		});

        $('input[name=Count]', parent.document).val($('input[name="select"]:checked').size());
    
        $('#cardids', parent.document).val(idList);
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
	});
    
    $('#choiceAll').click(function(){ $('input[name="select"]').attr('checked', 'checked'); });
    $('#noChoice').click(function(){ $('input[name="select"]').removeAttr('checked'); });
});
</script>
</body>
</html>