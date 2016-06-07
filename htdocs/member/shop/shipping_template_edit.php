<?php
ini_set("display_errors","On");

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$base_url = base_url();
$DB->showErr = true;

//设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$template_dir = $_SERVER["DOCUMENT_ROOT"].'/member/shop/html';
$smarty->template_dir = $template_dir;

$Template_ID = !empty($_REQUEST['Template_ID'])?$_REQUEST['Template_ID']:null;
if(empty($Template_ID)){
	echo '缺少必要的参数模板ID';
	exit();
}

if($_POST){
	$Data = handle_template_post_data($_SESSION['Users_ID'],$_POST);
	
	/*判断是否存在免运费条件*/
	if(!empty($_POST['areas'])){
		$free_content = handle_shipping_free_data($_POST);
	}else{
		$free_content = '';
	}
	
	$Data['Free_Content'] = $free_content;
	
	$condition = "where Users_ID='".$_SESSION['Users_ID']."' and Template_ID=".$Template_ID;

	$flag=$DB->Set("shop_shipping_template",$Data,$condition);
	
	if($flag){
		echo '<script language="javascript">alert("编辑成功！");window.open("shipping_template.php","_self");</script>';
		exit();
	}else{
		echo '<script language="javascript">alert("编辑失败！");window.location="javascript:history.back()";</script>';
		exit();
	}
}

$region_list = get_regison_list();

$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
$area_array = json_decode($area_json,TRUE);
$province_list = $area_array[0];

//获取可用快递公司列表
$Shipping_Compny_List = get_shipping_company($_SESSION['Users_ID'],1);
$by_methods = array('by_qty'=>'按件数','by_weight'=>'按重量');

$condition = "where Users_ID='".$_SESSION['Users_ID']."' and Template_ID=".$Template_ID;
$rsTemplate = $DB->getRs('shop_shipping_template','*',$condition);
if(!$rsTemplate){
	echo '无此快递模板';
	exit();
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>添加新的运费模板</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/deliver_content.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.form.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.js'></script>
<script type='text/javascript' src='/static/js/jquery.tmpl.min.js'></script>
<script type='text/javascript' src='/static/js/location.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/shipping.js'></script>
<script type='text/javascript'>
    var base_url = '<?=$base_url?>';
	$(document).ready(function(){
		shipping_obj.tpl_add_init();
	});
	
</script>

<?php require_once('section_jquery_tmpl.php')?>
<?php require_once('shipping_set_free_tmpl.php')?>
<?php require_once('area_dialog.php')?>

<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<!-- dialog area begin -->

<!-- dialog area end -->



<div id="iframe_page">
  
  <div class="iframe_content">
	<div class="r_nav">
      <ul>
		
         <li> <a href="shipping.php">快递公司管理</a> </li>
        <li  class="cur" ><a href="shipping_template.php">快递模板管理</a></li>
       
      </ul>
    </div>
    <div class="r_con_wrap">
      
        <form class="r_con_form" method="post" id="shipping_template_add_form" action="?Template_ID=<?=$Template_ID?>">
        	<div class="rows">
                <label>模板名称</label>
                <span class="input"><input type="text" name="Template_Name" value="<?=$rsTemplate['Template_Name']?>" size="30" class="form_input" notnull/></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
            	<label>快递公司</label>
                <span class="input">
                  <select name="Shipping_ID" id="Shipping_ID" notnull>
                  <?php if($Shipping_Compny_List != false):?>
                      <?php foreach($Shipping_Compny_List as $key=>$company):?>
                      	 <option value="<?=$company['Shipping_ID']?>" <?=($company['Shipping_ID'] == $rsTemplate['Shipping_ID'])?'selected':''?>><?=$company['Shipping_Name']?></option>
                      <?php endforeach;?>
                  <?php endif; ?>
                  </select>
                
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>计价方式</label>
                <span class="input">
                    <label><input name="By_Method" type="radio" value="by_qty" by_method_name='件数' by_method_unit='件' <?=('by_qty' == $rsTemplate['By_Method'])?'checked':''?>>按件数</label>
                    <label><input name="By_Method" type="radio" value="by_weight" by_method_name='重量' by_method_unit= 'kg' <?=('by_weight' == $rsTemplate['By_Method'])?'checked':''?>>按重量</label>
                </span>
                <div class="clear"></div>
            </div>
            
            <!-- 运送方式begin -->
           	<div class="rows deliver_content_container">
                <label>运送方式</label>
                <div class="input">
                	
                    <div id="deliver_content">
               	    
                        	<?=build_shipping_section_html($smarty,$_SESSION['Users_ID'],$rsTemplate['Shipping_ID'],$rsTemplate['By_Method'],$rsTemplate['Template_Content'])?>
					
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <!-- 运送方式end -->

			<!-- 免运费方式begin -->
            <div class="rows">
               <label>指定包邮条件</label>
               
                <div class="input">
             	
                	<input type="checkbox" id="J_SetFree" <?=!empty($rsTemplate['Free_Content'])?'checked':''?> value="1"/>
                       <a href="javascript:void(0)" id="set_shipping_free_link">勾选启用</a>
                   	<div id="deliver_free_content">
						<?php if(!empty($rsTemplate['Free_Content'])):?>
                        	<?=build_shipping_free_html($smarty,$rsTemplate['Free_Content']); ?>
                        <?php endif;?>
					</div>
                </div>
                <div class="clear"></div>
            </div>
            
            <!-- 免运费方式end -->
            <div class="rows">
                <label>状态</label>
                <span class="input">
                    <label><input name="status" type="radio" value="1" <?=$rsTemplate['Template_Status']?'checked':'';?>>显示</label>
                    <label><input name="status" type="radio" value="0" <?=!$rsTemplate['Template_Status']?'checked':'';?>>不显示</label>
                </span>
                <div class="clear"></div>
            </div>
          
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="确定" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
        </form>
    </div>
  </div>
</div>
<div id="overlay"></div>

</body>
</html>