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

//获取文章
$rsArticle = $DB->getRs("shop_articles","*","where Users_ID='".$UsersID."' and Article_Status=1 and Article_ID=".$_GET['ID']);
if(!$rsArticle){
	echo "不存在该文章";
	exit;
}
$rsArticle["Article_Hits"] = $rsArticle["Article_Hits"]+1;
$DB->Set("shop_articles",array("Article_Hits"=>$rsArticle["Article_Hits"]),"where Users_ID='".$UsersID."' and Article_Status=1 and Article_ID=".$_GET['ID']);

$rsArticle['Article_Content'] = str_replace('&quot;','"',$rsArticle['Article_Content']);
$rsArticle['Article_Content'] = str_replace("&quot;","'",$rsArticle['Article_Content']);
$rsArticle['Article_Content'] = str_replace('&gt;','>',$rsArticle['Article_Content']);
$rsArticle['Article_Content'] = str_replace('&lt;','<',$rsArticle['Article_Content']);
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=$rsArticle["Article_Title"]?></title>
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
    

<body style="background:#FFF;">
<style type="text/css">
.article_header{width:98%; margin:0px auto; padding:8px; line-height:22px; font-size:16px; font-weight:bold}
.article_second{width:95%; margin:0px auto; padding:0px 0px 6px;}
.article_second span{font-size:14px;}
.article_second span.span_editor{padding-left:10px; color:#1580C2}
.article_content{padding:0px 10px}
.article_content img{max-width:100%}
.article_footer{width:95%; margin:0px auto; padding:10px 0px 10px;}
.article_footer span{font-size:14px;color:#1580C2}
</style>
<div class="article_header">
<?php echo $rsArticle["Article_Title"];?>
</div>
<div class="article_second">
<span><?php echo date('Y-m-d',$rsArticle['Article_CreateTime'])?></span><span class="span_editor"><?php echo $rsArticle['Article_Editor'];?></span>
</div>
<div class="article_content">
<?php echo $rsArticle['Article_Content'];?>
</div>
<div class="article_footer">
	<span>阅读&nbsp;<?php echo $rsArticle["Article_Hits"];?></span>
</div>
<?php
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1 and KF_Code<>''");
if($kfConfig){
	echo htmlspecialchars_decode($kfConfig["KF_Code"],ENT_QUOTES);
}
?>

<?php if($rsConfig["CallEnable"] && $rsConfig["CallPhoneNumber"]){?>
<script language='javascript'>var shop_tel='<?php echo $rsConfig["CallPhoneNumber"];?>';</script>
<script type='text/javascript' src='/static/api/shop/js/tel.js?t=<?php echo time();?>'></script>
<?php }?>

<?php if(!empty($share_config)){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_config["appId"];?>",   
		   timestamp:<?php echo $share_config["timestamp"];?>,
		   nonceStr:"<?php echo $share_config["noncestr"];?>",
		   url:"<?php echo $share_config["url"];?>",
		   signature:"<?php echo $share_config["signature"];?>",
		   title:"<?php echo $share_config["title"];?>",
		   desc:"<?php echo str_replace(array("\r\n", "\r", "\n"), "", $share_config["desc"]);?>",
		   img_url:"<?php echo $share_config["img"];?>",
		   link:"<?php echo $share_config["link"];?>"
		};
		
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
<div class='conver_favourite'><img src="/static/api/images/global/share/favourite.png" /></div>
</body>
</html>
