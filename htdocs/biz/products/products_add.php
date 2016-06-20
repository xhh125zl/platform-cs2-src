<?php
require_once('../global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');
//print_r($_SESSION);print_r($rsBiz);
//商品属性的html
if($_POST)
{
	if(!is_numeric($_POST["PriceY"])){
		echo '<script language="javascript">alert("产品原价请填写数字");history.back();</script>';
		exit;
	}
	
	if(!is_numeric($_POST["PriceX"])){
		echo '<script language="javascript">alert("产品原现价请填写数字");history.back();</script>';
		exit;
	}
	
	if($_POST["PriceY"]<$_POST["PriceX"]){
		echo '<script language="javascript">alert("产品原价不能小于产品现价");history.back();</script>';
		exit;
	}
	
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
	
	if(!isset($_POST["JSON"])){
		echo '<script language="javascript">alert("请上传商品图片");history.back();</script>';
		exit;
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
	
	//产品结算形式
	if($rsBiz["Finance_Type"]==0){//该商家的结算方式若为按交易额提成
		$Data["Products_FinanceType"] = 0;
		$Data["Products_FinanceRate"] = $rsBiz["Finance_Rate"];
		$Data["Products_PriceS"] = number_format($_POST['PriceX'] * (1-$rsBiz["Finance_Rate"]/100),2,'.','');
	}else{
		if($_POST["FinanceType"]==0){//商品按交易额比例
			if(!is_numeric($_POST["FinanceRate"]) || $_POST["FinanceRate"]<=0){
				echo '<script language="javascript">alert("网站提成比例必须大于零！");history.back();</script>';
				exit();
			}
			$Data["Products_FinanceType"] = 0;
			$Data["Products_FinanceRate"] = $_POST["FinanceRate"];
			$Data["Products_PriceS"] = number_format($_POST['PriceX'] * (1-$_POST["FinanceRate"]/100),2,'.','');
		}else{//商品按供货价
			if(!is_numeric($_POST["PriceS"]) || $_POST["PriceS"]<=0 || $_POST["PriceS"]>$_POST['PriceX']){
				echo '<script language="javascript">alert("供货价格必须大于零，且小于售价！");history.back();</script>';
				exit();
			}
			$Data["Products_FinanceType"] = 1;
			$rate = ($_POST['PriceX']-$_POST["PriceS"])*100/$_POST['PriceX'];
			$Data["Products_FinanceRate"] = number_format($rate,2,'.','');
			$Data["Products_PriceS"] = $_POST['PriceS'];
		}
	}
	//$shop_config = shop_config($_SESSION["Users_ID"]); 
	$shop_config = shop_config($rsBiz["Users_ID"]); 
	if (!is_null($shop_config['Shop_Commision_Reward_Json'])) 
		{
		  $Shop_Commision_Reward_Arr = json_decode($shop_config['Shop_Commision_Reward_Json'], true);
		}	
	
	//商品基本信息
	$Data["Users_ID"] = $rsBiz["Users_ID"];
	$Data["Biz_ID"] = $_SESSION['BIZ_ID'];
	$Data["Products_Name"] = $_POST['Name'];
	$Data["Products_Category"] = ','.implode(",",$Category).',';
	$Data["Products_PriceY"] = empty($_POST['PriceY'])?"0":$_POST['PriceY'];
	$Data["Products_PriceX"] = empty($_POST['PriceX'])?"0":$_POST['PriceX'];
	$Data["Products_JSON"] = json_encode((isset($_POST["JSON"])?$_POST["JSON"]:array()),JSON_UNESCAPED_UNICODE);
	$Data["Products_BriefDescription"] = htmlspecialchars($_POST['BriefDescription'], ENT_QUOTES);
	$Data["Products_Description"] = htmlspecialchars($_POST['Description'], ENT_QUOTES);
	$Data["Products_Type"] = empty($_POST["TypeID"]) ?  0: $_POST["TypeID"];
	$Data["Products_Count"] = empty($_POST["Count"]) ? 10000 : intval($_POST["Count"]);
	$Data["Products_Weight"] = empty($_POST["Weight"]) ? 0 : $_POST["Weight"];
	$Data["Products_CreateTime"] = time();
	$Data["Products_IsVirtual"] = $isvirtual;
	$Data["Products_IsRecieve"] = $isrecieve;
	$Data["Products_Status"] = 0;
	$Data["commission_ratio"] = !empty($Shop_Commision_Reward_Arr['commission_Reward']) ? $Shop_Commision_Reward_Arr['commission_Reward'] : 0;;
	$Data["Products_SoldOut"] = isset($_POST["SoldOut"])?$_POST["SoldOut"]:0;
	$Data["Products_IsShippingFree"] = isset($_POST["IsShippingFree"])?$_POST["IsShippingFree"]:0;
 
	//商家店铺相关,只有商家拥有带你普时才可有此设置
	if($IsStore==1){
		$Data["Products_BizCategory"] = empty($_POST["BizCategory"]) ? 0 : $_POST["BizCategory"];
		$Data["Products_BizIsNew"] = isset($_POST["BizIsNew"])?$_POST["BizIsNew"]:0;
		$Data["Products_BizIsHot"] = isset($_POST["BizIsHot"])?$_POST["BizIsHot"]:0;
		$Data["Products_BizIsRec"] = isset($_POST["BizIsRec"])?$_POST["BizIsRec"]:0;
	}
	
	$Flag=$DB->Add("shop_products",$Data);
	$product_id = $DB->insert_id();
	
	//处理传递过来的卡编号
	  /* if ($_POST["ordertype"] == 2 && !empty($_POST['cardids'])) {
	    $CardArr = array();
	    $CardIdArr = explode(',', $_POST['cardids']);
	    foreach ($CardIdArr as $k => $v) {
	      if (!empty($v)) { $CardArr[] = $v; }
	    }

	    $CardData = array('Products_Relation_ID' => $product_id);
	    $DB->Set("shop_virtual_card", $CardData, "where Card_Id IN(".implode(',', $CardArr).")");
	  } */
	  
	 //处理产品属性 
	if(strlen($_POST['TypeID'])>0){
		deal_with_attr($product_id);
	}
	 
	//生成二维码
	$product_url = base_url().'api/'.$rsBiz['Users_ID'].'/shop/products/'.$product_id.'/';
	$qrcode = array(
		"Products_Qrcode"=>generate_qrcode($product_url),
	);
	$DB->set("shop_products",$qrcode,"where Products_ID=".$product_id);
	
	if($Flag){
		echo '<script language="javascript">alert("添加成功");window.location="products.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}else{
	$shop_category = array();
	$DB->get("shop_category","*","where Users_ID='".$rsBiz["Users_ID"]."' order by Category_ParentID asc,Category_Index asc");
	while($r=$DB->fetch_assoc()){
		if($r["Category_ParentID"]==0){
			$shop_category[$r["Category_ID"]] = $r;
		}else{
			$shop_category[$r["Category_ParentID"]]["child"][] = $r;
		}
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
<script type='text/javascript' src='/static/member/js/products_attr_helper.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src='/biz/js/shop.js?t=<?php echo time()?>'></script>
<script>
var Browser = new Object();	
$(document).ready(shop_obj.products_init);
KindEditor.ready(function(K) {
	K.create('textarea[name="Description"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/biz/upload_json.php?TableField=shop_products&BIZ_ID=<?php echo $_SESSION["BIZ_ID"]?>',
		fileManagerJson : '/biz/file_manager_json.php',
		allowFileManager : true,
		
	});
	var editor = K.editor({
		uploadJson : '/biz/upload_json.php?TableField=shop_products&BIZ_ID=<?php echo $_SESSION["BIZ_ID"]?>',
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
					K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a> <span onclick="$(this).parent().remove();">删除</span><input type="hidden" name="JSON[ImgPath][]" value="'+url+'" /></div>');
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

$(document).ready(shop_obj.products_edit_init);

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
    
    <div class="r_nav">
      <ul>
        <li><a href="products.php">产品列表</a></li>
        <li class="cur"><a href="products_add.php">添加产品</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <form id="product_add_form" class="r_con_form" method="post" action="products_add.php">
        <div class="rows">
          <label>产品名称</label>
          <span class="input">
          <input type="text" name="Name" value="" class="form_input" size="35" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>商城分类</label>
          <span class="input">
            <a href="#select_category">[选择分类]</a>
            <div id="category_name"></div>
			<div id="classs"></div>
          </span>
          <div class="clear"></div>
        </div>
        <?php if($IsStore==1){?>
        <div class="rows">
          <label>商家自定义分类</label>
          <span class="input">
            <select name="BizCategory" style="width:180px;" notnulll>
             <?php
			  $DB->get("biz_category","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." order by Category_ParentID asc,Category_Index asc");
			  $diy_cate = array();
			  while($r=$DB->fetch_assoc()){
				  if($r["Category_ParentID"]==0){
					  $diy_cate[$r["Category_ID"]] = $r;
				  }else{
					  $diy_cate[$r["Category_ParentID"]]["child"][] = $r;
				  }
			  }
			  foreach($diy_cate as $key=>$value){
				  echo '<option value="'.$value["Category_ID"].'">'.$value["Category_Name"].'</option>';
				  if(!empty($value["child"])){
					  foreach($value["child"] as $v){
						  echo '<option value="'.$v["Category_ID"].'">└'.$v["Category_Name"].'</option>';
					  }
				  }
			  }?>
            </select>
          </span>
          <div class="clear"></div>
        </div>
        <?php }?>
        <div class="rows">
          <label>产品价格</label>
          <span class="input price"> 原价:￥
          <input type="text" name="PriceY" value="" class="form_input" size="5" maxlength="10" notnull /> <font class="fc_red">*</font> 
          &nbsp;&nbsp;现价:￥
          <input type="text" name="PriceX" value="" class="form_input" size="5" maxlength="10" notnull /> <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <?php if($rsBiz["Finance_Type"]==1){?>
        <div class="rows">
          <label>财务结算类型</label>
          <span class="input">
              <input type="radio" name="FinanceType" value="0" id="FinanceType_0" onClick="$('#PriceS').hide();$('#FinanceRate').show();" checked /><label for="FinanceType_0"> 按交易额比例</label>&nbsp;&nbsp;<input type="radio" name="FinanceType" value="1" id="FinanceType_1" onClick="$('#FinanceRate').hide();$('#PriceS').show();" /><label for="FinanceType_1"> 按供货价</label><br />
          <span class="tips">注：若按交易额比例，则网站提成为：产品售价*比例%</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="FinanceRate">
          <label>网站提成</label>
          <span class="input">
          <input type="text" name="FinanceRate" value="" class="form_input" size="10" /> %
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="PriceS" style="display:none">
          <label>供货价</label>
          <span class="input">
          <input type="text" name="PriceS" value="" class="form_input" size="10" /> 元
          </span>
          <div class="clear"></div>
        </div>
        <?php }?>
		
        <div class="rows">
          <label>产品重量</label>
          <span class="input">
         <input type="text" name="Weight" value="" notnull class="form_input" size="5" />&nbsp;&nbsp;千克 <font class="fc_red">*</font>
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
          <div class="img" id="PicDetail"></div>
          </span>
          <div class="clear"></div>
        </div>
          <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="BriefDescription" class="briefdesc"></textarea>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows" id="type_html">
           <label>产品类型：</label>
           <span class="input">
           <select name="TypeID" style="width:180px;" id="Type_ID" notnull>
            <option value="">请选择类型</option>
               <?php
				$DB->get("shop_product_type","*","where Users_ID='".$rsBiz["Users_ID"]."' and Biz_ID=".$_SESSION["BIZ_ID"]." order by Type_Index asc");
				while($rsType= $DB->fetch_assoc()){
					echo '<option value="'.$rsType["Type_ID"].'"'.'>'.$rsType["Type_Name"].'</option>';
				}
			  ?>
              <option value="0">其他</option>
           </select>
           <font class="fc_red">*</font></span>
           <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>产品属性</label>
          <span class="input" id="attrs">
           
          </span>
          <div class="clear"></div>
  
        </div>
        
        <div id="propertys" display="none"></div>
		
        <div class="rows">
          <label>其他属性</label>
          <span class="input attr">
		  <input type="checkbox" value="1" name="SoldOut" /> 下架 &nbsp;|&nbsp;
          <input type="checkbox" value="1" name="IsShippingFree" /> 免运费           
          <?php if($IsStore==1){?>
          &nbsp;|&nbsp;<input type="checkbox" value="1" name="BizIsNew" /> 新品 &nbsp;|&nbsp;
          <input type="checkbox" value="1" name="BizIsHot" /> 热卖 &nbsp;|&nbsp;
          <input type="checkbox" value="1" name="BizIsRec" /> 推荐 <span class="tips">(新品、热卖、推荐在商家店铺主页的相应位置显示)</span>
          <?php }?>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>订单流程</label>
          <span class="input" style="font-size:12px; line-height:22px;">
              <input type="radio" id="order_0" value="0" name="ordertype" checked /><label for="order_0"> 实物订单&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 商家发货 -> 买家收货 -> 订单完成 ) </label><br />
              <input type="radio" id="order_1" value="1" name="ordertype" /><label for="order_1"> 虚拟订单&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 系统发送消费券码到买家手机 -> 商家认证消费 -> 订单完成 ) </label><br />
              <input type="radio" id="order_2" value="2" name="ordertype" /><label for="order_2"> 其他&nbsp;&nbsp;( 买家下单 -> 买家付款 -> 订单完成 ) </label>
          </span>
          <div class="clear"></div>
        </div>
		
		<div class="rows">
          <label>库存</label>
          <span class="input">
          <input type="text" name="Count" value="10000" class="form_input" size="5" maxlength="10" /> <span class="tips">&nbsp;注:若不限则填写10000.</span>
          </span>
          <div class="clear"></div>
        </div>
		
        <div class="rows">
          <label>详细介绍</label>
          <span class="input">
          <textarea class="ckeditor" name="Description" style="width:700px; height:300px;"></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
          <div class="clear"></div>
        </div>

         <input type="hidden" id="UsersID" value="<?=$rsBiz["Users_ID"]?>" />
        <input type="hidden" id="ProductsID" value="0">
        <div id="select_category" class="lean-modal lean-modal-form">
          <div class="h">产品分类<a class="modal_close" href="#"></a></div>
          <div class="catlist" style="height:360px; overflow:auto">
           <dl>
           <?php
               foreach($shop_category as $first=>$items){
           ?>
            <dt><input type="checkbox" rel="1" cate="<?php echo $items["Category_Name"];?>" name="Category[<?php echo $items["Category_ID"];?>][]" value="<?php echo $items["Category_ID"];?>" /> <qq544731308><?php echo $items["Category_Name"];?></qq544731308></dt>
            <dd>
            <?php
               if(!empty($items["child"])){
				   foreach($items["child"] as $second=>$item){
					   echo '<span><input type="checkbox" rel="0" name="Category['.$items["Category_ID"].'][]" value="'.$item["Category_ID"].'" /> <qq544731308son>'.$item["Category_Name"].'<qq544731308son></span>';
				   }
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
		<input type="hidden" id="UsersID" value="<?=$rsBiz["Users_ID"]?>" />
		<input type="hidden" id="ProductsID" value="0" />
      </form>
    </div>
    
  </div>
</div>
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>

<script type="text/javascript">
  $(document).ready(function(){
    /* $('.rows input[name=ordertype]').click(function(){
      var sVal = $('.rows input[name=ordertype]:checked').val();
      if (sVal == 2) { 
        if (confirm('是否设置绑定虚拟卡密?')) {
          layer.open({
              type: 2,
              area: ['800px', '500px'],
              fix: false,
              maxmin: true,
              content: '/biz/products/virtual_card_select.php'
          });
          $('input[name=Count]').val('0').attr('readonly', 'readonly');
        }
      };
    }); */
	$(document).on('click','.modal_close',function(){
		var dtcontent = [];
		var ddcontent = [];
		var select_class = '';
		$('#select_category dt').each(function(i){
			if($(this).children('input').attr("checked")=='checked'){
				dtcontent[i] = $(this).children('qq544731308').html();
			}
			if(typeof(dtcontent[i])=='undefined'){
				dtcontent[i]='&nbsp';
			} 
			$(this).next('dd').children('span').each(function(){
				if($(this).children('input').attr('checked')=='checked'){
					if(typeof(ddcontent[i])=='undefined'){
						ddcontent[i]='&nbsp';
					} 
					ddcontent[i] += $(this).children('qq544731308son').html()+'&nbsp';
				}
			})
			if(typeof(ddcontent[i])=='undefined'){
				ddcontent[i]='&nbsp';
			} 
			
			if(typeof(dtcontent[i])=='&nbsp' && typeof(ddcontent[i])=='&nbsp'){
				
			}else{
				select_class += '<p style="margin:5px 0px; padding:0px; font-size:12px; color:#666"><font style="color:#333; font-size:12px;">' +dtcontent[i]+'</font>&nbsp;'+ddcontent[i]+'</p>'; 
				
			}
		})
		 
		$('#classs').html(select_class);
	})
    
  });
</script>
</body>
</html>