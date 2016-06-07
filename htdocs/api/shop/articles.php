<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$shop_url = shop_url();

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

//授权
$owner = get_owner($rsConfig,$UsersID);
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);

//更换商城配置信息
if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$shop_url = $shop_url.$owner['id'].'/';
};

//分销级别处理文件
include($_SERVER["DOCUMENT_ROOT"].'/api/distribute/distribute.php');

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();

//自定义分享
if(!empty($share_config)){
	$share_config["link"] = $shop_url;
	$share_config["title"] = $rsConfig["ShopName"];
	if($owner['id'] != '0' && $rsConfig["Distribute_Customize"]==1){	
		$share_config["desc"] = $owner['shop_announce'] ? $owner['shop_announce'] : $rsConfig["ShareIntro"];
		$share_config["img"] = strpos($owner['shop_logo'],"http://")>-1 ? $owner['shop_logo'] : 'http://'.$_SERVER["HTTP_HOST"].$owner['shop_logo'];
	}else{
		$share_config["desc"] = $rsConfig["ShareIntro"];
		$share_config["img"] = strpos($rsConfig['ShareLogo'],"http://")>-1 ? $rsConfig['ShareLogo'] : 'http://'.$_SERVER["HTTP_HOST"].$rsConfig['ShareLogo'];
	}
	
	//商城分享相关业务
	include("share.php");
}

if(isset($_GET["CategoryID"])){
  $CategoryID=$_GET["CategoryID"];
}else{
  $CategoryID=0;
}

if($CategoryID == 0){
	$title = '常见问题';
}else{
	$category = $DB->GetRs('shop_articles_category','Category_Name',"where Category_ID=".$CategoryID." and Users_ID='".$UsersID."'");
	$title = $category['Category_Name'];
}

$condition = "where Users_ID='".$UsersID."' and Article_Status=1";
if($CategoryID>0){
	$condition .= " and Category_ID=".$CategoryID;
}
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=$title?></title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/css/article.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body>
<div class="wrap">
  <header class="bar bar-nav">
	<a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
	<a href="/api/<?=$UsersID?>/shop/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
	<h1 class="title"><?=$title?></h1>
  </header>
  <div class="wrap">
	 <div class="container">
          	
        <div id="articles" class="row">    
       		<ul class="list-group">
			 <?php
				$i=0;
				$DB->getPage("shop_articles","*",$condition,$pageSize=10);
				while($item=$DB->fetch_assoc()){
					$i++;
			 ?>
             
			 <li class="list-group-item"><a href="<?=$shop_url?>article/<?=$item['Article_ID']?>/"><?php echo $i;?>、<?=$item['Article_Title']?></a></li>
			 <?php }?>
	   </ul>
    	</div>
     </div>
	 <?php $DB->showWechatPage($shop_url.'articles/'.($CategoryID>0 ? $CategoryID.'/' : '')); ?>
  </div>
</div>
<?php require_once('skin/distribute_footer.php');?> 
</body>
</html>
