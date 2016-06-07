<?php

ini_set("display_errors","On");

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/Utf8pinyin.php');

$Pinyin = new Utf8pinyin();
$base_url = base_url();
$_SERVER['HTTP_REFERER'] =  $base_url.'member/shop/distribute_record.php';
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];

if(!empty($action))
{	
   
	
	if($action=="del"){
		//删除快递模板
		$Flag=$DB->Del("shop_shipping_template","Users_ID='".$_SESSION["Users_ID"]."' and Template_ID=".$_GET["Template_ID"]);
	
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="shipping_template.php";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}


$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
$condition .= " order by Template_CreateTime";

$rsShippings = $DB->getPage("shop_shipping_template","*",$condition,$pageSize=10);
$template_list = $DB->toArray($rsShippings);

$method_names = array('by_qty'=>'件数','by_weight'=>'重量','by_volume'=>'体积');

//取出快递公司名称
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
$condition .= " order by Shipping_CreateTime";
$rsShippings = $DB->getPage("shop_shipping_company","Shipping_ID,Shipping_Name",$condition);

$shipping_company_list = $DB->toArray($rsShippings);
//生成drop_down数组
$company_dropdown = array();

foreach($shipping_company_list as $key=>$company){
 	$company_dropdown[$company['Shipping_ID']] = $company['Shipping_Name'];	
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
<script type='text/javascript' src='/static/js/jquery.validate.js'></script>
<script type='text/javascript' src='/static/js/jquery.metadata.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.zh_cn.js'></script>


<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type="text/javascript">
	var base_url = '<?=$base_url?>';	
</script>

</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>

        <li > <a href="shipping.php">快递公司管理</a> </li>
        <li  class="cur" ><a href="shipping_template.php">快递模板管理</a></li>
       
      </ul>
    </div>
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
     <script type='text/javascript' src='/static/js/inputFormat.js'></script>
    <script language="javascript">
	$(document).ready(function(){shop_obj.shop_shiping_init()});
	
</script>
    <div id="update_post_tips"></div>
    <div id="user" class="r_con_wrap">
      <div class="control_btn">
      <?php if(!empty($shipping_company_list)):?>
      	<a href="/member/shop/shipping_template_add.php" id="create_shipping_template_btn" class="btn_green btn_w_120">添加</a> 
      <?php else: ?>
      <a href="javascript:void(0)" id="create_shipping_template_btn" class="btn_green btn_w_120" disabled="true">添加</a> 
         无法添加快递模板，请先添加快递公司。
	  <?php endif;?>
      </div>
      
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
              <td width="10%" nowrap="nowrap">模板名</td>
            <td width="10%" nowrap="nowrap">快递公司</td>
            <td width="10%" nowrap="nowrap">计价方式</td>
          
            <td width="5%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap">添加时间</td>
            <td width="8%" nowrap="nowrap" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
      
	
	<?php foreach($template_list as $key=>$template):?>
           <tr UserID="<?php echo $template['Template_ID'] ?>">
            <td><?=$template['Template_ID']?></td>
            <td><?=$template['Template_Name']?></td>
            <td>
            	<?=$company_dropdown[$template['Shipping_ID']]?>
            </td>
            
            <td><?=$method_names[$template['By_Method']]?></td>
           
            <td><?php if($template['Template_Status'] ==1 ){ echo '<img src="/static/member/images/ico/yes.gif"/>';}else{echo '<img src="/static/member/images/ico/no.gif"/>';}?></td>
            <td nowrap="nowrap"><?php echo ldate($template['Template_CreateTime']) ?></td>
            
            <td nowrap="nowrap" class="last">
            <a href="/member/shop/shipping_template_edit.php?Template_ID=<?=$template['Template_ID']?>" shipping-id="<?=$template['Template_ID']?>" class="shipping_edit_btn"><img src="/static/member/images/ico/mod.gif" alt="修改" align="absmiddle"></a>
            <a href="shipping_template.php?action=del&Template_ID=<?php echo $template['Template_ID'] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
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