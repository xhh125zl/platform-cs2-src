<?php
require_once('../global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/flow.php');

$Users_ID = $rsBiz['Users_ID'];

if($_POST){
	$Data = array();
	if($_POST['Default_Template']){
	   $Data["Shipping"] = json_encode($_POST['Default_Template'],JSON_UNESCAPED_UNICODE);
	}
	$Data["Default_Shipping"]= isset($_POST["Default_Shipping"])?$_POST["Default_Shipping"]:null;
	$Data["Default_Business"] = 'express';

	$Flag=$DB->Set("biz",$Data,"where Biz_ID=".$_SESSION["BIZ_ID"]);
	
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="config.php";</script>';
	}else{
		echo '<script language="javascript">alert("修改失败");history.back();</script>';
	}
	exit;
}

//获取物流模板设置信息
$rsShippingConfig = array(
	'Shipping'=>$rsBiz['Shipping'],
	'Default_Shipping'=>$rsBiz['Default_Shipping'],
	'Default_Business'=>$rsBiz['Default_Business']
);
$Shipping_Config = json_decode($rsShippingConfig['Shipping'],true);
$Shipping_Brief = get_shipping_brief($Users_ID,$_SESSION['BIZ_ID']);

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
<script type='text/javascript' src='/static/member/js/shopbiz.js'></script>
<script language="javascript">
$(document).ready(shopbiz_obj.biz_shipping_default_edit);
</script>

</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    
    <div class="r_nav">
      <ul>
        <li  class="cur"><a href="config.php">运费设置</a></li>
        <li><a href="company.php">快递公司管理</a></li>
        <li><a href="template.php">快递模板</a></li>
		<li><a href="printtemplate.php">运单模板</a></li>
      </ul>
    </div>
    <div id="bizs" class="r_con_wrap">
    <?php if(count($Shipping_Brief['Shipping_List']) > 0 ):?>
       		<form action="config.php" method="post" id="shipping_default_config" >
                	<table style="width:80%;" class="r_con_table">
                <tbody>
                   	<tr>
                   	<td>快递公司</td><td>运费模板</td><td>首选快递公司</td>
                    </tr>
                   
				   <?php foreach($Shipping_Brief['Shipping_List'] as $key=>$company):?>
                   	<tr>
                    	<td><?=$company['Shipping_Name']?></td>
                    	<td>
                        	<?php if(!empty($Shipping_Brief['Template_Dropdown'][$company['Shipping_ID']])): ?>
                            	<select name="Default_Template[<?=$company['Shipping_ID']?>]" notnull >
                                	<option value="">请选择默认物流模板</option>
                                    <?php foreach($Shipping_Brief['Template_Dropdown'][$company['Shipping_ID']] as $k=>$template):?>
                                    	<?php
											
											//如果存在此快递公司的默认模板配置
											//并且默认模板配置ID等于当前模板ID
											if(!empty($Shipping_Config[$company['Shipping_ID']])&&$Shipping_Config[$company['Shipping_ID']] == $template['Template_ID']){											
												$selected = 'selected';
											}else{
												$selected = '';
											}
										
										?>
                                    <option value="<?=$template['Template_ID']?>"  <?=$selected;?> ><?=$template['Template_Name']?></option>
                                    <?php endforeach;?>	
                                   
                                </select>
                            <?php else: ?>
                                请去添加物流模板
                            <?php endif; ?>
                        </td>
                    	<td>
             <?php if(!empty($rsShippingConfig['Default_Shipping'])):?>
             	<?php $Default_Shipping = $rsShippingConfig['Default_Shipping']; ?>
             	  <input type="radio" name="Default_Shipping" class="Default_Shipping" value="<?=$company['Shipping_ID']?>" <?=($Default_Shipping == $company['Shipping_ID'])?'checked':''?> />
             <?php else: ?>
            	 <input type="radio" name="Default_Shipping" class="Default_Shipping" value="<?=$company['Shipping_ID']?>" <?=($key == 0)?'checked':''?> />
			 <?php endif;?>
                        </td>
                    </tr>
				   <?php endforeach; ?>
                   
               		</tbody>
                </table>
                	<input type="hidden" name="action" value="shipping">
                	<div class="submit clearfix" style="margin-top:10px;" >
                 		<input class="btn_green" type="submit" value="提交保存" />
                	</div>
                </form>
    <?php else:?>
    		<p>
               请添加快递公司
            </p>
    <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
