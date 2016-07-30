<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
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

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
$owner = get_owner($rsConfig,$UsersID);
$is_login=1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}

$rsConfig = $DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");

$rsUser = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]);

$rsConfig1=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
$LevelName = '普通会员';
if(!empty($rsConfig1["UserLevel"])){
	$level_arr = json_decode($rsConfig1["UserLevel"],true);
	if(!empty($level_arr[$rsUser["User_Level"]])){
		$LevelName = $level_arr[$rsUser["User_Level"]]["Name"];
	}
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';
$show_support = true;

?>
<!DOCTYPE html>
<html lang="zh-cn">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>个人中心</title>
	<link href="/static/css/bootstrap.css" rel="stylesheet">
	<link href="/static/api/cloud/css/comm.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="./static/css/font-awesome.css">
	<link href="/static/api/distribute/css/style.css" rel="stylesheet">
	<link href="/static/api/shop/skin/default/css/member.css?t=<?php echo time();?>" rel="stylesheet">
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="/static/js/jquery-1.11.1.min.js"></script>
	<script type='text/javascript' src='/static/api/js/global.js'></script>
	<script type='text/javascript' src='/static/api/cloud/js/shop.js'></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script language="javascript">
		$(document).ready(shop_obj.page_init);
	</script>
	</head>

	<body>
<div class="wrap">
		<div class="contaienr">
		<div id="member_header" style="background:#FF6600">
				<div class="header_r">会员级别: <font style="font-weight:bold">
					<?=$LevelName?>
					</font> </div>
				<div class="header_l"> <span class="img"><img src="<?=$rsUser['User_HeadImg']?>"></span> <span class="nickname">
					<?php 
						if(strlen($rsUser['User_NickName']) >0){
							echo $rsUser['User_NickName'];	
						}else{
							echo '暂无';
						}
					?>
					</span>
				<div class="clearfix"></div>
			</div>
				<div class="clearfix"></div>
			</div>
	</div>
		<div class="list_item">
		<div class="dline"></div>
		<a href="/api/<?=$UsersID?>/cloud/member/status/2/" class="item item_0"><span class="ico"></span><span>云购记录</span></a> 
		<a href="/api/<?=$UsersID?>/cloud/member/products/" class="item item_5"><span class="ico"></span>获得的商品<span class="jt"></span></a> 
		<a href="/api/<?=$UsersID?>/user/my/address/" class="item item_3"><span class="ico"></span>收货地址管理<span class="jt"></span></a> 
		<a href="/api/<?=$UsersID?>/cloud/member/favourite/" class="item item_6"><span class="ico"></span>我的云购收藏夹<span class="jt"></span></a>
        <a href="/api/<?=$UsersID?>/cloud/distribute/" class="item item_1" style="display:none;"><span class="ico"></span>我的云购分销<span class="jt"></span></a> 		
	</div>
	</div>
<?php require_once('../footer.php'); ?>
</body>
</html>