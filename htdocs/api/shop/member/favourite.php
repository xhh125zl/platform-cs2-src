<?php 
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

$Status  = !empty($_GET['status'])?$_GET['status']:0;

//获取此次request的action,若无action,用默认值list
$action = isset($_GET['action'])?$_GET['action']:'list';

//显示收藏夹内商品
if($action == 'list'){
	//获取此用户所收藏的商品
	$sql = "select f.FAVOURITE_ID,p.Products_ID,p.Products_Name,Products_PriceX,p.Products_JSON
from Shop_Products as p
join user_favourite_products as f
on p.Products_id = f.Products_ID and f.User_ID =".$_SESSION[$UsersID.'User_ID'];

	$resource = $DB->query($sql);
	$result = $DB->toArray($resource);

	foreach($result as $key=>$item){
		$JSON = json_decode($item['Products_JSON'],TRUE);
		$product = $item;
		$product['ImgPath'] = $JSON["ImgPath"][0];
		$favourList[$product['Products_ID']] = $product;
	}


}elseif($action == 'del'){
	
	//删除收藏夹内指定商品
	$condition = 'User_ID='.$_SESSION[$UsersID.'User_ID'].' and FAVOURITE_ID='.$_GET['favour_id'];
	echo $condition;
	
	$Flag=$DB->Del("user_favourite_products",$condition);

	header("location:".$_SERVER['HTTP_REFERER']);
	exit;
}



?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>个人中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">$(document).ready(shop_obj.page_init);</script>
</head>

<body>
<div id="shop_page_contents">
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/default/css/member.css' rel='stylesheet' type='text/css' />
  <div id="favourite_list"> 
	<?php if(isset($favourList)):?>
	  <?php foreach($favourList as $key=>$item):?>
      <div class="item">
        <div class="del">
          <div cartid="5_0"><a href="/api/<?=$UsersID?>/shop/member/favourite/del/<?=$item['FAVOURITE_ID']?>/"><img src="/static/api/shop/skin/default/images/del.gif"></a></div>
        </div>
        <div class="img"><a href="/api/<?=$UsersID?>/shop/products/<?=$key?>/"><img src="<?=$item['ImgPath']?>" height="100" width="100"></a></div>
        <dl class="info">
          <dd class="name"><a href="/api/<?=$UsersID?>/shop/products/<?=$key?>/"><?=$item['Products_Name']?></a> </dd>
          <dd class="price">价格:<span>￥<?=$item['Products_PriceX']?></span></dd>
        </dl>
        <div class="clear"></div>
      </div>	
	  <?php endforeach;?>
   <?php else:?>
   	  <p style="margin-left:20px;">&nbsp;&nbsp;&nbsp;收藏夹中暂无产品!</p>
   <?php endif;?>     	
     
   
 
  </div>
  
  
</div>
<?php
 	require_once('../skin/distribute_footer.php');
 ?>
</body>
</html>
