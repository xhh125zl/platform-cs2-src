<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');

if(empty($_SESSION["BIZ_ID"])){
	header("location:/biz/login.php");
}
$rsBiz=$DB->GetRs("biz","*","where Biz_ID='".$_SESSION["BIZ_ID"]."'");

$ProductsID=empty($_REQUEST['ProductsID'])?0:$_REQUEST['ProductsID'];
$rsProducts = $DB->GetRs("shop_Products","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Products_ID=".$ProductsID);

if($_POST){
	//处理产品属性 
	
	$Category = array();
	if(!isset($_POST["Category"])){
		echo '<script language="javascript">alert("请选择分类");history.back();</script>';
		exit;
	}
	foreach($_POST["Category"] as $k=>$vv){
		$Category[] = $k;
		foreach($vv as $v){
			$Category[] = $v;
		}
	}
	
	if($_POST["ordertype"]==0){
		$isvirtual = 0;
		$isrecieve = 0;
	}elseif($_POST["ordertype"]==1){
		$isvirtual = 1;
		$isrecieve = 0;
	}else{
		$isvirtual = 1;
		$isrecieve = 1;
	}
	
	if(strlen($_POST['TypeID'])>0){
		deal_with_attr($ProductsID);
	}else{
		remove_product_attr($ProductsID);
	}

	if(!isset($_POST["JSON"])){
		echo '<script language="javascript">alert("请上传商品图片");history.back();</script>';
		exit;
	}
	
	$_POST['Description'] = str_replace('"','&quot;',$_POST['Description']);
	$_POST['Description'] = str_replace("'","&quot;",$_POST['Description']);
	$_POST['Description'] = str_replace('>','&gt;',$_POST['Description']);
	$_POST['Description'] = str_replace('<','&lt;',$_POST['Description']);
	
 
	$Data=array(
		"Products_Name"=>$_POST['Name'],
		"Products_Category"=>','.implode(",",$Category).',',
		"Products_Type"=>empty($_POST["TypeID"]) ?  0: $_POST["TypeID"],
		"Products_PriceY"=>empty($_POST['PriceY'])?"0":$_POST['PriceY'],
		"Products_PriceX"=>empty($_POST['PriceX'])?"0":$_POST['PriceX'],
		"Products_Profit"=>empty($_POST['Products_Profit'])?"0":$_POST['Products_Profit'],
		"Products_Distributes"=>empty($_POST['Distribute'])?"":json_encode($_POST['Distribute'],JSON_UNESCAPED_UNICODE),
		"Products_JSON"=>json_encode((isset($_POST["JSON"])?$_POST["JSON"]:array()),JSON_UNESCAPED_UNICODE),
		"Products_BriefDescription"=>$_POST['BriefDescription'],
		"Products_SoldOut"=>isset($_POST["SoldOut"])?$_POST["SoldOut"]:0,
		"Products_IsNew"=>isset($_POST["IsNew"])?$_POST["IsNew"]:0,
		"Products_IsHot"=>isset($_POST["IsHot"])?$_POST["IsHot"]:0,
		"Products_IsRecommend"=>isset($_POST["IsRecommend"])?$_POST["IsRecommend"]:0,
		"Products_IsShippingFree"=>isset($_POST["IsShippingFree"])?$_POST["IsShippingFree"]:0,
		"Products_IsVirtual"=>$isvirtual,
		"Products_IsRecieve"=>$isrecieve,
		"Products_Count"=>empty($_POST["Count"]) ? 10000 : intval($_POST["Count"]),
		"Products_Description"=>$_POST['Description'],
		"Products_Weight"=>$_POST['Products_Weight'],
		"Products_SupplyPrice"=>isset($_POST['SupplyPrice']) ? $_POST['SupplyPrice'] : 0,
		"Products_BizCategory"=>$_POST["BizCategory"],
		"Products_Status"=>0,
		"Products_BizIsNew"=>isset($_POST["BizIsNew"])?$_POST["BizIsNew"]:0,
		"Products_BizIsHot"=>isset($_POST["BizIsHot"])?$_POST["BizIsHot"]:0,
		"Products_BizIsRec"=>isset($_POST["BizIsRec"])?$_POST["BizIsRec"]:0
	);

		
	$Flag=$DB->Set("shop_Products",$Data,"where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Products_ID=".$ProductsID);
	if($Flag){
		echo '<script language="javascript">alert("修改成功");window.location="products.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}else{
	$JSON=json_decode($rsProducts['Products_JSON'],true);	
	$distribute_list = json_decode($rsProducts['Products_Distributes'],true);  //分佣金额列表	
	if(!isset($JSON["Wholesale"][0]["Qty"])) $JSON["Wholesale"] = array();	
	//支付方式列表
	$Pay_List =  get_enabled_pays($DB,$rsBiz['Users_ID']);
	//商品属性的html	
	if(!empty($rsProducts['Products_Type'])){
		$product_attr_html = build_attr_html($rsProducts['Products_Type'],$ProductsID);
	}else{
		$product_attr_html = '';
	}
	$catids = substr($rsProducts["Products_Category"],1,-1);
	$Categorys = explode(",",$catids);
	$ordertype = 0;
	if($rsProducts["Products_IsVirtual"]==1 && $rsProducts["Products_IsRecieve"]==1){
		$ordertype = 2;
	}elseif($rsProducts["Products_IsVirtual"]==1){
		$ordertype = 1;
	}
	
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
		uploadJson : '/biz/upload_json.php?TableField=shop_products',
		fileManagerJson : '/biz/file_manager_json.php',
		allowFileManager : true,
	
	});
	var editor = K.editor({
		uploadJson : '/biz/upload_json.php?TableField=shop_products',
		fileManagerJson : '/biz/file_manager_json.php',
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
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script>
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <script type='text/javascript'>
	
    	$(document).ready(shop_obj.products_edit_init);
    </script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="products.php">产品列表</a></li>
        <li><a href="products_add.php">添加产品</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form class="r_con_form" id="product_edit_form" method="post" action="products_edit.php">
        <div class="rows">
          <label>产品名称</label>
          <span class="input">
          <input type="text" name="Name" value="<?php echo $rsProducts["Products_Name"] ?>" class="form_input" size="35" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商城分类</label>
          <span class="input">
            <a href="#select_category">[选择分类]</a>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商家自定义分类</label>
          <span class="input">
            <select name="BizCategory" style="width:180px;" notnulll>
             <?php
			 $bizcate_f = array();
             $DB->Get("biz_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ParentID=0 order by Category_Index asc,Category_ID asc");
			 while($r=$DB->fetch_assoc()){
				 $bizcate_f[] = $r;
			 }
			 foreach($bizcate_f as $f){
				 echo '<option value="'.$f["Category_ID"].'"'.($rsProducts["Products_BizCategory"] == $f["Category_ID"] ? ' selected' : '').'>'.$f["Category_Name"].'</option>';
				 $DB->Get("biz_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." and Category_ParentID=".$f["Category_ID"]." order by Category_Index asc,Category_ID asc");
				 while($item=$DB->fetch_assoc()){
				 	echo '<option value="'.$item["Category_ID"].'"'.($rsProducts["Products_BizCategory"] == $item["Category_ID"] ? ' selected' : '').'>└─'.$item["Category_Name"].'</option>';
			 	}
			 }
			 ?>
            </select>
          </span>
          <div class="clear"></div>
        </div>
		<div class="rows">
          <label>库存</label>
          <span class="input">
          <input type="text" name="Count" value="<?php echo $rsProducts["Products_Count"] ?>" class="form_input" size="5" maxlength="10" /> <span class="tips">&nbsp;注:若不限则填写10000.</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>产品价格</label>
          <span class="input price"> 原价:￥
          <input type="text" name="PriceY" value="<?php echo $rsProducts["Products_PriceY"] ?>" class="form_input" size="5" maxlength="10" />
          现价:￥
          <input type="text" name="PriceX" value="<?php echo $rsProducts["Products_PriceX"] ?>" class="form_input" size="5" maxlength="10" />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>网站提成</label>
          <span class="input price">
          <span>%</span>
          <input type="text" name="SupplyPrice" value="<?php echo $rsProducts["Products_SupplyPrice"] ?>" class="form_input" size="5" maxlength="10" notnull />
          <span>(占产品现价的百分比，用于网站提成计算)</span>
          </span>
          <div class="clear"></div>
        </div>
          <!-- 产品利润begin -->
       	<div class="rows">
          <label>产品利润</label>
          <span class="input price">
          <span>%</span>
          <input type="text" name="Products_Profit" value="<?=$rsProducts["Products_Profit"]?>" class="form_input" size="5" maxlength="10" notnull />
          <span>(占产品现价的百分比，用于分销返利额计算)</span>
          </span>
          <div class="clear"></div>
        </div>
        <!-- 产品利润end -->
        <div class="rows">
        	<label>佣金返利</label>
            <span class="input">
            	<table id="wholesale_price_list" class="item_data_table" border="0" cellpadding="3" cellspacing="0">
               		   <tr>
              <td>一级&nbsp;&nbsp;%
                <input name="Distribute[0]" value="<?=$distribute_list[0]?>" class="form_input" size="5" maxlength="10" type="text">
                (产品利润的百分比)
           </td>
           <tr>
           		<td>二级&nbsp;&nbsp;%
                	<input name="Distribute[1]" value="<?=$distribute_list[1]?>" class="form_input" size="5" maxlength="10" type="text">
                    (产品利润的百分比)
                </td>
           </tr>
           
                
               <tr> 
                <td>三级&nbsp;&nbsp;%
                	<input name="Distribute[2]" value="<?=$distribute_list[2]?>" class="form_input" size="5" maxlength="10" type="text">
                    (产品利润的百分比)
                </td>
                </tr>
               
            </tr>
                        <tr>
                        
                </table>
                
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
          <label>简短介绍</label>
          <span class="input">
          <textarea name="BriefDescription" class="briefdesc"><?php echo $rsProducts["Products_BriefDescription"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows" id="type_html">
           <label>产品类型</label>
           <span class="input">
           <select name="TypeID" style="width:180px;" id="Type_ID" >
            <option value="">请选择类型</option>
               <?php
				$DB->get("shop_product_type","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." order by Type_Index asc");
				while($rsType= $DB->fetch_assoc()){
					echo '<option value="'.$rsType["Type_ID"].'"'.($rsProducts["Products_Type"]==$rsType["Type_ID"] ? " selected" : "").'>'.$rsType["Type_Name"].'</option>';
				}
			  ?>
             <option value="0"<?php echo $rsProducts["Products_Type"]==0 ? " selected" : "";?>>其他</option>
         
              
           </select>
           <font class="fc_red">*</font></span>
           <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>产品属性</label>
          <span class="input" id="attrs">
            <?=$product_attr_html;?>
          </span>
          <div class="clear"></div>  
        </div>
         
        
        <div class="rows">
          <label>产品重量</label>
          <span class="input">
         <input type="text" name="Products_Weight" value="<?=$rsProducts["Products_Weight"]?>" notnull class="form_input" size="5" />&nbsp;&nbsp;千克
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>订单流程</label>
          <span class="input" style="font-size:12px; line-height:22px;">
              <input type="radio" id="order_0" value="0" name="ordertype"<?php echo $ordertype==0 ? ' checked' : '';?> /><label for="order_0"> 实物订单&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 商家发货 -> 买家收货 -> 订单完成 ) </label><br />
              <input type="radio" id="order_1" value="1" name="ordertype"<?php echo $ordertype==1 ? ' checked' : '';?> /><label for="order_1"> 虚拟订单&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 系统发送消费券码到买家手机 -> 商家认证消费 -> 订单完成 ) </label><br />
              <input type="radio" id="order_2" value="2" name="ordertype"<?php echo $ordertype==2 ? ' checked' : '';?> /><label for="order_2"> 其他&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 订单完成 ) </label>
          </span>
          <div class="clear"></div>
        </div>
       
        <div class="rows">
          <label>其他属性</label>
          <span class="input attr"> 下架:
          <input type="checkbox" value="1" name="SoldOut" <?php echo empty($rsProducts["Products_SoldOut"])?"":" checked" ?> />&nbsp;|&nbsp;
          新品:
          <input type="checkbox" value="1" name="IsNew" <?php echo empty($rsProducts["Products_IsNew"])?"":" checked" ?> />&nbsp;|&nbsp;
          热卖:
          <input type="checkbox" value="1" name="IsHot" <?php echo empty($rsProducts["Products_IsHot"])?"":" checked" ?> />&nbsp;|&nbsp;
          推荐:
           <input type="checkbox" value="1" name="IsRecommend" <?php echo empty($rsProducts["Products_IsRecommend"])?"":" checked" ?> />&nbsp;|&nbsp;
          免运费:
          <input type="checkbox" value="1" name="IsShippingFree" <?php echo empty($rsProducts["Products_IsShippingFree"])?"":" checked" ?> />&nbsp;|&nbsp;
          虚拟产品(电子券消费形式):
          <input type="checkbox" value="1" name="IsVirtual" <?php echo empty($rsProducts["Products_IsVirtual"])?"":" checked" ?> />&nbsp;|&nbsp;
		  虚拟产品(订单付款后即刻完成):
          <input type="checkbox" value="1" name="IsRecieve" <?php echo empty($rsProducts["Products_IsRecieve"])?"":" checked" ?> />
          </span>
          <div class="clear"></div>
        </div>
      
	    <div class="rows">
          <label>店铺主页选项</label>
          <span class="input attr">
          新品:
          <input type="checkbox" value="1" name="BizIsNew"<?php echo empty($rsProducts["Products_BizIsNew"])?"":" checked" ?> />&nbsp;|&nbsp;
          热卖:
          <input type="checkbox" value="1" name="BizIsHot"<?php echo empty($rsProducts["Products_BizIsHot"])?"":" checked" ?> />&nbsp;|&nbsp;
          推荐:
          <input type="checkbox" value="1" name="BizIsRec"<?php echo empty($rsProducts["Products_BizIsRec"])?"":" checked" ?> />&nbsp;|&nbsp;<span class="tips">在商家店铺主页的相应位置显示</span>
          </span>
          <div class="clear"></div>
        </div>
	    
        <div class="rows">
          <label>详细介绍</label>
          <span class="input">
          <textarea class="ckeditor" name="Description" style="width:700px; height:300px;"><?php echo $rsProducts["Products_Description"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div id="select_category" class="lean-modal lean-modal-form">
          <div class="h">产品分类<a class="modal_close" href="#"></a></div>
          <div class="catlist">
           <dl>
           <?php
               $first = array();
               $DB->get("shop_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Category_ParentID=0 order by Category_Index asc");
               while($r = $DB->fetch_assoc()){
                   $first[] = $r;
               }
               foreach($first as $k=>$v){
           ?>
            <dt><input type="checkbox" rel="1" name="Category[<?php echo $v["Category_ID"];?>][]" value="<?php echo $v["Category_ID"];?>"<?php echo in_array($v["Category_ID"],$Categorys) ? ' checked' : '';?>/> <?php echo $v["Category_Name"];?></dt>
            <dd>
                <?php
                   $DB->get("shop_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Category_ParentID=".$v["Category_ID"]." order by Category_Index asc");
                   while($r = $DB->fetch_assoc()){
                       echo '<span><input type="checkbox" rel="0" name="Category['.$v["Category_ID"].'][]" value="'.$r["Category_ID"].'"'.(in_array($r["Category_ID"],$Categorys) ? ' checked' : '').' /> '.$r["Category_Name"].'</span>';
                   }
               ?>
            </dd>
            <?php }?>
           </dl>
          </div>
		  <div class="rows">
            <label></label>
            <span class="submit"><a class="modal_close" style="border-radius:8px;padding:5px 20px; color:#FFF; text-align:center; background:#3AA0EB" href="#">选好了</a></span>
            <div class="clear"></div>
          </div>
         </div>
        <div class="rows">
          <label></label>
          <span class="input">
            <input type="hidden" id="UsersID" value="<?=$rsBiz["Users_ID"]?>" />
            <input type="hidden" name="ProductsID" id="ProductsID"  value="<?php echo $rsProducts["Products_ID"] ?>">
        
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
          <div class="clear"></div>
        </div>
     
        
      </form>
    </div>
  </div>
</div>
</body>
</html>