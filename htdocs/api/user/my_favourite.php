<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$TypeID=empty($_GET["TypeID"])?0:$_GET["TypeID"];

if(!strpos($_SERVER['REQUEST_URI'],"OpenID=")){
	if(empty($_SESSION[$UsersID.'OpenID'])){
		$_SESSION[$UsersID.'OpenID']=session_id();
	}
}else{
	$url_arr = explode("OpenID=",$_SERVER['REQUEST_URI']);
	$endpos = explode("&",$url_arr[1]);
	$_SESSION[$UsersID.'OpenID']=$endpos[0];
}

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}
}

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}

/*若用户没有登陆，跳转到登陆页面*/
if(empty($_SESSION[$UsersID."User_ID"]) || !isset($_SESSION[$UsersID."User_ID"])){
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/coupon/";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}

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
<meta name="viewport"
	content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
	<script type="text/javascript">$(document).ready(user_obj.coupon_init);</script>
	<div id="favourite_list"> 
	<?php if(isset($favourList)):?>
	  <?php foreach($favourList as $key=>$item):?>
      <div class="item">
        <div class="del">
          <div cartid="5_0"><a href="/api/<?=$UsersID?>/user/my/favourite/del/<?=$item['FAVOURITE_ID']?>/"><img src="/static/api/shop/skin/default/images/del.gif"></a></div>
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
   	  <p style="margin-left:20px;">&nbsp;&nbsp;&nbsp;搜藏夹中暂无产品!</p>
   <?php endif;?>     	
     
   
 
  </div>
	
    
    
    
	
	<?php require_once('footer.php'); ?>
</body>
</html>
