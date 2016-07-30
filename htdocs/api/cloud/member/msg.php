<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
$base_url = base_url();
$shop_url = shop_url();
/*分享页面初始化配置*/
$share_flag = 1;
$signature = '';

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(!empty($_SESSION[$UsersID."User_ID"])){
	$userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
	if(!$userexit){
		$_SESSION[$UsersID."User_ID"] = "";
	}	
}
if(empty($_SESSION[$UsersID."User_ID"]))
{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/shop/distribute/";
	header("location:/api/".$UsersID."/user/login/");
}
$Status=empty($_GET["Status"])?0:$_GET["Status"];
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsConfig=$DB->GetRs("shop_config","ShopName,NeedShipping","where Users_ID='".$UsersID."'");

//获得此用户的通知
$condition = "where Users_ID='".$UsersID."' and User_ID= '".$_SESSION[$UsersID.'User_ID']."'";
$rsMessages = $DB->Get('shop_distribute_msg',"*",$condition);

$message_list = $DB->toArray($rsMessages );



?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["ShopName"] ?>-分销中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<link href='/static/api/dist/css/font-awesome.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/distribute.css' rel='stylesheet' type='text/css' />

<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/inputFormat.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">
var base_url = '<?=$base_url?>';
$(document).ready(shop_obj.page_init);
$(document).ready(shop_obj.distribute_init);
</script>
</head>

<body>

<header class="bar bar-nav">
  <a  href="javascript:history.back()"class="icon icon-2x icon-chevron-left grey pull-left"></a>
  <a  href="<?=$base_url?>api/<?=$UsersID?>/shop/distribute/" class="icon icon-2x icon-sitemap grey pull-right"></a>
  <h1 class="title">消息中心</h1>
  
</header>

<div id="page_contents">

  <div id="cover_layer"></div>
  

  <div id="message-list">
      <?php foreach($message_list as $key=>$message):?>
      <div class="item">
      
       <h1><i class="icon icon-comment-alt green"></i>&nbsp;&nbsp;<?=$message['Message_Title']?></h1>
       <p><?=$message['Message_Description']?></p>
       <p>
       <span style="float:left"><?=date("Y-m-d H:i:s",$message['Message_CreateTime'])?></span>
       <span style="float:right"><a  class="remove-msg" href="javascript:void(0)"><i class="red icon icon-remove"></i></a></span>
       </p>
      </div>
      <?php endforeach;?>
    
  </div>
  
  
    
</div> 	
<?php
 	require_once('../distribute_footer.php');
 ?>
</body>
</html>