<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET['OpenID'])){
	$_SESSION['OpenID']=$_GET['OpenID'];
	header("location:/api/".$UsersID."/stores/");
	exit;
}else{
	if(empty($_SESSION['OpenID'])){
		$_SESSION['OpenID']=session_id();
	}
}
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsConfig=$DB->GetRs("stores_config","StoresName","where Users_ID='".$UsersID."'");
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["StoresName"] ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/stores.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/stores.js'></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo $ak_baidu;?>"></script>
<script language="javascript">var baidu_map_ak='Xbq3g4meudxD5Q0MB9osTLpg'; $(document).ready(stores_obj.stores_init);</script>
</head>

<body>
<dl id="your_address">
  <dt>您现在的位置</dt>
  <dd></dd>
</dl>
<div id="stores">
  <?php $DB->get("stores","*","where Users_ID='".$UsersID."' order by Stores_ID asc",10);
  while($rsStores=$DB->fetch_assoc()){?>
  <div class="store" lng="<?php echo $rsStores["Stores_PrimaryLng"] ?>" lat="<?php echo $rsStores["Stores_PrimaryLat"] ?>">
    <h1> <span class="store_name"><?php echo $rsStores["Stores_Name"] ?></span> <span class="dis">0m</span> </h1>
    <div class="item ">
      <div class="address_ico"></div>
      <a href="http://api.map.baidu.com/marker?location=<?php echo $rsStores["Stores_PrimaryLat"] ?>,<?php echo $rsStores["Stores_PrimaryLng"] ?>&title=<?php echo $rsStores["Stores_Name"] ?>&name=<?php echo $rsStores["Stores_Name"] ?>&content=<?php echo $rsStores["Stores_Address"] ?>&output=html"><div class="address"><?php echo $rsStores["Stores_Address"] ?></div></a>
      <div class="lbs"><a href="http://api.map.baidu.com/marker?location=<?php echo $rsStores["Stores_PrimaryLat"] ?>,<?php echo $rsStores["Stores_PrimaryLng"] ?>&title=<?php echo $rsStores["Stores_Name"] ?>&name=<?php echo $rsStores["Stores_Name"] ?>&content=<?php echo $rsStores["Stores_Address"] ?>&output=html"></a></div>
    </div>
    <div class="item last">
      <div class="tel_ico"></div>
      <ul class="telephone">
        <?php $Telephone=explode("\n",$rsStores["Stores_Telephone"]);
		foreach($Telephone as $k=>$v){
			echo '<li><a href="tel:'.$v.'">'.$v.'</a></li>';
		}?>
      </ul>
      <div class="clear"></div>
    </div>
  </div>
  <?php }?>
  <?php $DB->showWechatPage('/api/'.$UsersID.'/stores/'); ?>
  </div>
</body>
</html>