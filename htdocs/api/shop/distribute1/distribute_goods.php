<?php
require_once('global.php');

if(!empty($_GET["ProductID"])){
  $ProductsID = $_GET["ProductID"];	
   
  $where = array('Users_Id'=>$UsersID,'Products_SoldOut'=>0,'Products_ID'=>$ProductsID,'Products_Status'=>1);
  $product_obj  = Product::multiWhere($where)->first();
  if(empty($product_obj)){
	  	echo '无此产品';
		exit();
  }else{
	$rsProducts =   $product_obj->toArray();
  	$product = handle_product($rsProducts);
  }

}else{
	echo '缺少必要的参数产品ID';
}		 

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_qrcode.class.php');
$weixin_qrcode = new weixin_qrcode($DB,$UsersID);
$qrcode_path = $weixin_qrcode->get_qrcode("products_".$owner['id']."_".$ProductsID);

$ds_list = $product['Products_Distributes'] ? json_decode($product['Products_Distributes']) : array();
if(empty($ds_list)){
	$ds_money = 0;
}else{
	$profit = 0;
	$rsBiz = $DB->GetRs("biz","Finance_Type,Finance_Rate","where Biz_ID=".$product["Biz_ID"]);
	if($rsBiz["Finance_Type"]==0){
		$profit = $product['Products_PriceX']*$rsBiz["Finance_Rate"]/100;
	}elseif($product["Products_FinanceType"]==0){
		$profit = $product['Products_PriceX']*$product["Products_FinanceRate"]/100;
	}else{
		$profit = $product['Products_PriceX']-$product['Products_PriceS'];
	}
	$ds_money = $profit*$product['commission_ratio']*$ds_list[0]/10000;  //分销佣金
}
$ds_money = number_format($ds_money,2,'.','');
//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();
if(!empty($share_config)){
	$share_config["link"] = $shop_url.$owner['id'].'/products/'.$ProductsID.'/';
	$share_config["title"] = $product['Products_Name'];
	$share_config["desc"] = $product['Products_Name'];
	$share_config["img"] = strpos($product['ImgPath'],"http://")>-1 ? $product['ImgPath'] : 'http://'.$_SERVER["HTTP_HOST"].$product['ImgPath'];
	//商城分享相关业务
	include("../share.php");
}

$header_title = $product['Products_Name'];
require_once('header.php');
?>
<body>
<link href="/static/api/distribute/css/goods_distribute.css" rel="stylesheet">

<div class="wrap">
	<div class="container">
    	<h4 class="row text-center">分销商品</h4>
   </div>
   
   <?php if($rsAccount['status']): ?>
   		<div class="container">
        <div class="row" id="distribute_brief">
           <div class="col-xs-1"><span class="fa  golden fa-usd fa-3x"></span></div>
           <div class="col-xs-5"><p>分销佣金<span class="red"><?=$ds_money?></span>元<br/>
           已销售<span class="red"><?=$product['Products_Sales']?></span>件</p>
           </div>
        </div>
    
   	<div class="row">
      <div class="activity-image">
      	<img  width="100%" src="<?=$product['ImgPath']?>"/>
     	 <a class="deadline" href="">
        	<span class="product_name"><?=$product['Products_Name']?></span><br/>
        	<span class="price golden">&yen;<?=$product['Products_PriceX']?></span>&nbsp;&nbsp;&nbsp;&nbsp;
    	</a>
      </div>
    </div>
    
  
   </div>
   		<div class="container"> 
        <div class="row text-center">
        	
        	<img class="qrcode_image" src="<?=$qrcode_path?>" style="max-width:100%"/>
            
        </div>
      
  
    </div>
    	<footer class="footer">
      <div class="container">
      	<div class="button-panel">
     		 <button class="btn btn-default" id="distribute-btn">分销此产品</button>
      	</div>
      </div>
      
    </footer>
   <?php else: ?>
          <div class="container">
          		<div class="row">
                	<p>&nbsp;&nbsp;&nbsp;&nbsp;您的分销账号已被禁用,&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=$share_link?>" class="red">返回</a></p>
                    
                </div>
          </div>
   <?php endif; ?>
</div>

 <?php require_once('../skin/distribute_footer.php');?>    
 </body>
</html>
