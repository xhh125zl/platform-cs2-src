<?php  
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$ProductsID = empty($_REQUEST['ProductsID']) ? 0 : $_REQUEST['ProductsID'];
$rsProducts = $DB->GetRs("cloud_Products","*","where Users_ID='{$UsersID}' and Products_ID=".$ProductsID);
$shop_config = $DB->GetRs('shop_config','*',"where Users_ID='{$UsersID}'");

$JSON = json_decode($rsProducts['Products_JSON'],true);

$distribute_list = json_decode($rsProducts['Products_Distributes'],true);  //分佣金额列表

if(!isset($JSON["Wholesale"][0]["Qty"])) $JSON["Wholesale"] = array();

//计算物流模板数量
$condition = "where Users_ID='{$UsersID}' and Template_Status = 1 ";
$rsShippingTemplates = $DB->Get("shop_shipping_template","*",$condition);
$Templates = $DB->toArray($rsShippingTemplates);
$ShippingNum = count($Templates);
//获取有物流模板的物流公司
$ShippingIDS = '';
if($ShippingNum > 0 ){
	foreach($Templates as $key=>$item){
		$Shipping_ID_List[] = $item['Shipping_ID'];
	}  
    $ShippingIDS = implode(',',$Shipping_ID_List);
	
	$condition = "where Users_ID='{$UsersID}' and Shipping_Status = 1 And Shipping_ID in (". $ShippingIDS.")";
	$rsCompanies = $DB->Get("shop_shipping_company","Shipping_ID,Shipping_Name",$condition);
	$Company_List = $DB->toArray($rsCompanies);
}else{
    $Company_List = array();
}


if($_POST){

	if(!isset($_POST["JSON"])){
		echo '<script language="javascript">alert("请上传商品图片");history.back();</script>';
		exit;
	}
	
	if($_POST['PriceY'] < $_POST['PriceX']){
		echo '<script language="javascript">alert("商品价格不能小于购买价格");history.back();</script>';
		exit;
	};					
	$zongrenci = ceil($_POST['PriceY']/$_POST['PriceX']);
	
	$_POST['Description'] = str_replace('"','&quot;',$_POST['Description']);
	$_POST['Description'] = str_replace("'","&quot;",$_POST['Description']);
	$_POST['Description'] = str_replace('>','&gt;',$_POST['Description']);
	$_POST['Description'] = str_replace('<','&lt;',$_POST['Description']);
	
 
	$Data=array(
		"Products_Name"=>$_POST['Name'],
		"Products_Category"=>empty($_POST['Category'])?"0":$_POST['Category'],
		"Products_PriceY"=>empty($_POST['PriceY'])?"0":$_POST['PriceY'],
		"Products_PriceX"=>empty($_POST['PriceX'])?"1":$_POST['PriceX'],
		"Products_Profit"=>empty($_POST['Products_Profit'])?"0":$_POST['Products_Profit'],
		"Products_Distributes"=>empty($_POST['Distribute'])?"":json_encode($_POST['Distribute'],JSON_UNESCAPED_UNICODE),
		"Products_JSON"=>json_encode((isset($_POST["JSON"])?$_POST["JSON"]:array()),JSON_UNESCAPED_UNICODE),
		"Products_SoldOut"=>isset($_POST["SoldOut"])?$_POST["SoldOut"]:0,
		"Products_IsNew"=>isset($_POST["IsNew"])?$_POST["IsNew"]:0,
		"Products_IsHot"=>isset($_POST["IsHot"])?$_POST["IsHot"]:0,
		"Products_IsRecommend"=>isset($_POST["IsRecommend"])?$_POST["IsRecommend"]:0,
		"Products_IsShippingFree"=>isset($_POST["Products_IsShippingFree"])?$_POST["Products_IsShippingFree"]:0,
		"Products_IsVirtual"=>isset($_POST["IsVirtual"])?$_POST["IsVirtual"]:0,
		"commission_ratio"=>isset($_POST["commission_ratio"])?intval($_POST["commission_ratio"]):0,
		"Products_IsRecieve"=>isset($_POST["IsRecieve"])?$_POST["IsRecieve"]:0,
		"Products_Description"=>$_POST['Description'],
		"Products_Weight"=>$_POST['Products_Weight'],
		"Shipping_Free_Company"=>isset($_POST["Shipping_Free_Company"])?intval($_POST["Shipping_Free_Company"]):0,
		"Products_Qrcode"=>generate_qrcode(base_url().'api/'.$UsersID.'/cloud/products/'.$rsProducts['Products_ID'].'/'),
		"Products_xiangoutimes"=>empty($_POST['xiangoutimes'])?"0":$_POST['xiangoutimes'],
		"zongrenci"=>$zongrenci,
		"Products_Status"=>0
	);
	
	$Flag = $DB->Set("cloud_Products",$Data,"where Users_ID='{$UsersID}' and Products_ID=".$ProductsID);
	//跟新云购码
	$Flag = $Flag && $DB->Del("cloud_shopcodes","s_id=".$ProductsID);
	$Flag = $Flag && content_get_go_codes($zongrenci, 3000, $ProductsID);
	if($Flag) {
		echo '<script language="javascript">alert("修改成功");window.location="products.php";</script>';
	}else {
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/products_attr_helper.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>


KindEditor.ready(function(K) {
	K.create('textarea[name="Description"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&Users_ID=<?php echo $UsersID;?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=web_article&Users_ID=<?php echo $UsersID;?>',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		if(K('#PicDetail').children().length>=5){
			alert('您上传的图片数量已经超过5张，不能再上传！');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a> <span>删除</span><input type="hidden" name="JSON[ImgPath][]" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#PicDetail div span').click(function(){
		K(this).parent().remove();
	});
})
function insertRow(){
	var newrow=document.getElementById('wholesale_price_list').insertRow(-1);
	newcell=newrow.insertCell(-1);
	newcell.innerHTML='数量： <input type="text" name="JSON[Wholesale]['+(document.getElementById('wholesale_price_list').rows.length-2)+'][Qty]" value="" class="form_input" size="5" maxlength="3" /> 价格：￥ <input type="text" name="JSON[Wholesale]['+(document.getElementById('wholesale_price_list').rows.length-2)+'][Price]" value="" class="form_input" size="5" maxlength="10" /><a href="javascript:;" onclick="document.getElementById(\'wholesale_price_list\').deleteRow(this.parentNode.parentNode.rowIndex);"> <img src="/static/member/images/ico/del.gif" hspace="5" /></a>';
}
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
	<div class="iframe_content">
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
		<script type='text/javascript' src='/static/member/js/shop.js'></script> 
		<script type='text/javascript'>
    	$(document).ready(shop_obj.products_edit_init);
        </script>
		<?php include "top.php"; ?>
		<div id="products" class="r_con_wrap">
			<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
			<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
			<style>
			.tips_info {
				background: #f7f7f7 none repeat scroll 0 0;
				border: 1px solid #ddd;
				border-radius: 5px;
				font-size: 12px;
				line-height: 22px;
				margin-bottom: 10px;
				padding: 10px;
			}
			</style>
			<div class="tips_info">
			第(<font style="color:#F00; font-size:12px;"><?php echo $rsProducts['qishu']?></font>)期&nbsp;&nbsp;<?php echo $rsProducts["Products_Name"] ?><br />
			总价格 <font style="color:#F00; font-size:12px;"><?php echo $rsProducts["Products_PriceY"];?></font>&nbsp;&nbsp;&nbsp;&nbsp;单次云购价格 <font style="color:#F00; font-size:12px;"><?php echo $rsProducts["Products_PriceX"];?></font>&nbsp;&nbsp;&nbsp;&nbsp;
            总人次 <font style="color:#F00; font-size:12px;"><?php echo $rsProducts["zongrenci"];?></font>&nbsp;&nbsp;&nbsp;&nbsp;已参与 <font style="color:#F00; font-size:12px;"><?php echo $rsProducts["canyurenshu"];?></font>
			</div>
			<form class="r_con_form" id="product_edit_form" method="post" action="products_edit.php">
				<div class="rows">
					<label>产品名称</label>
					<span class="input">
					<input type="text" name="Name" value="<?php echo $rsProducts["Products_Name"] ?>" class="form_input" size="35" maxlength="100" notnull />
					<font class="fc_red">*</font></span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>隶属分类</label>
					<span class="input">
					<select name='Category'>
						<option value=''>--请选择--</option>
						<?php
						$DB->get("cloud_category","*","where Users_ID='{$UsersID}' and Category_ParentID=0 order by Category_Index asc");
						$ParentCategory=array();
						$i=1;
						while($rsPCategory=$DB->fetch_assoc()){
							$ParentCategory[$i]=$rsPCategory;
							$i++;
						}
						foreach($ParentCategory as $key=>$value){
							$DB->get("cloud_category","*","where Users_ID='{$UsersID}' and Category_ParentID=".$value["Category_ID"]." order by Category_Index asc");
							if($DB->num_rows()>0){
								echo '<optgroup label="'.$value["Category_Name"].'">';
								while($rsCategory=$DB->fetch_assoc()){
									echo '<option value="'.$rsCategory["Category_ID"].'"'.($rsCategory["Category_ID"]==$rsProducts["Products_Category"]?" selected":"").'>'.$rsCategory["Category_Name"].'</option>';
								}
								echo '</optgroup>';
							}else{
								echo '<option value="'.$value["Category_ID"].'"'.($value["Category_ID"]==$rsProducts["Products_Category"]?" selected":"").'>'.$value["Category_Name"].'</option>';
							}
						}
						?>
					</select>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>产品价格</label>
					<span class="input price"> 商品总价格:￥
					<input type="text" name="PriceY" value="<?php echo $rsProducts["Products_PriceY"] ?>" class="form_input" size="5" maxlength="10" />
					云购单次价格:￥
					<input type="text" name="PriceX" value="<?php echo $rsProducts["Products_PriceX"] ?>" class="form_input" size="5" maxlength="10" />
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>限购次数</label>
					<span class="input price">
					<input type="text" name="xiangoutimes" value="<?=empty($rsProducts["Products_xiangoutimes"])?0:$rsProducts["Products_xiangoutimes"]?>" class="form_input" size="5" maxlength="10" />
					<span>(0或空表示不限制)</span>
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="rows">
					<label>产品图片</label>
					<span class="input"> <span class="upload_file">
					<div>
						<div class="up_input">
							<input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
						</div>
						<div class="tips">共可上传<span id="pic_count">5</span>张图片，图片大小建议：640*640像素</div>
						<div class="clear"></div>
					</div>
					</span>
					<div class="img" id="PicDetail">
						<?php if(isset($JSON["ImgPath"])){
						foreach($JSON["ImgPath"] as $key=>$value){?>
						<div><a target="_blank" href="<?php echo $value ?>"> <img src="<?php echo $value ?>"></a><span>删除</span>
							<input type="hidden" name="JSON[ImgPath][]" value="<?php echo $value ?>">
						</div>
						<?php }
						}?>
					</div>
					</span>
					<div class="clear"></div>
				</div>
				
				<div class="rows">
					<label>产品重量</label>
					<span class="input">
					<input type="text" name="Products_Weight" value="<?=$rsProducts["Products_Weight"]?>" notnull class="form_input" size="5" />
					&nbsp;&nbsp;千克 </span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>运费计算方式</label>
					<span class="input">
					<?php if($ShippingNum >0 ): ?>
					&nbsp;&nbsp;
					<input type="radio" value="1" <?=$rsProducts['Products_IsShippingFree']?'checked':''?> name="Products_IsShippingFree"  />
					免运费&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;
					<input type="radio"  value="0"  <?=$rsProducts['Products_IsShippingFree']?'':'checked'?> name="Products_IsShippingFree"  />
					物流模板
					&nbsp;&nbsp;
					<?php else: ?>
					&nbsp;&nbsp;
					<input type="radio" value="1" checked="checked"name="Products_IsShippingFree" <?php echo empty($rsProducts["Products_IsShippingFree"])?"":" checked" ?> />
					免运费&nbsp;&nbsp;&nbsp;&nbsp;
					没有可用的物流模板
					<?php endif;?>
					</span>
					<div class="clear"></div>
				</div>
				<?php 
					$display = ($rsProducts['Products_IsShippingFree'] == 1)?'block':'none';
				?>
				<div class="rows" id="free_shipping_company" style="display:<?=$display?>">
					<label>指定免运费快递公司</label>
					<span class="input">
					<select name="Shipping_Free_Company" notnull>
						<option value="">请选择</option>
						<option value="0" <?=($rsProducts['Shipping_Free_Company'] == 0)?'selected':''?> >全部</option>
						<?php foreach($Company_List as $key=>$item):?>
						<option value="<?=$item['Shipping_ID']?>" <?=($rsProducts['Shipping_Free_Company'] == $item['Shipping_ID'])?'selected':''?> >
						<?=$item['Shipping_Name']?>
						</option>
						<?php endforeach; ?>
					</select>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>其他属性</label>
					<span class="input attr"> <label>下架:
					<input type="checkbox" value="1" name="SoldOut" <?php echo empty($rsProducts["Products_SoldOut"])?"":" checked" ?> /></label>
					&nbsp;|&nbsp;
					<label>新品:
					<input type="checkbox" value="1" name="IsNew" <?php echo empty($rsProducts["Products_IsNew"])?"":" checked" ?> /></label>
					&nbsp;|&nbsp;
					<label>热卖:
					<input type="checkbox" value="1" name="IsHot" <?php echo empty($rsProducts["Products_IsHot"])?"":" checked" ?> /></label>
					&nbsp;|&nbsp;
					<label>推荐:
					<input type="checkbox" value="1" name="IsRecommend" <?php echo empty($rsProducts["Products_IsRecommend"])?"":" checked" ?> /></label>
					&nbsp;|&nbsp;
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label>详细介绍</label>
					<span class="input">
					<textarea class="ckeditor" name="Description" style="width:600px; height:300px;"><?php echo $rsProducts["Products_Description"] ?></textarea>
					</span>
					<div class="clear"></div>
				</div>
				<div class="rows">
					<label></label>
					<span class="input">
					<input type="hidden" id="UsersID" value="<?=$UsersID?>" />
					<input type="hidden" name="ProductsID" id="ProductsID"  value="<?php echo $rsProducts["Products_ID"] ?>">
					<input type="submit" class="btn_green" name="submit_button" value="提交保存" />
					<a href="" class="btn_gray">返回</a></span>
					<div class="clear"></div>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>