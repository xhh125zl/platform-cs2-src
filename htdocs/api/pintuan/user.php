<?php 
	require_once('comm/global.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');

    if (isset($_GET["UsersID"])) {
        $UsersID = $_GET["UsersID"];
    } else {
        echo '缺少必要的参数';
        exit;
    }

    if (empty($_SESSION)) {
        header("location:/api/".$UsersID."/pintuan/");
        exit();
    }

    setcookie('url_referer', $_SERVER["REQUEST_URI"], time()+3600, '/', $_SERVER['HTTP_HOST']);

    $UserID = $_SESSION[$UsersID."User_ID"];

    $sql = "select * from user where user_id ='$UserID';";
    $result = $DB->query($sql);
    $user = $DB->fetch_assoc($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>我的抢购</title>
	<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
</head>
<body>
	<div class="w">
		<div class="txbj">
			<div class="txk">
				<span class="txb"><img src="<?php echo $user['User_HeadImg']; ?>"></span><br/>
				<span class="txt"><?php echo empty($user['User_NickName']) ? $user['User_Mobile'] : $user['User_NickName']; ?></span>
			</div>
		</div>
		<div class="clear"></div>
	<div class="dingdan_qb">
		<div class="dingdan_t">
			<span class="l">我的订单</span>
			<span class="r"><a href="<?php echo "/api/$UsersID/pintuan/orderlist/0/"; ?>">查看全部订单></a></span>
			<div class="clear"></div>
		</div>
		<div class="nav_dingdan">
	    	<ul>
		        <li class="l"><a href="<?php echo "/api/$UsersID/pintuan/orderlist/0/"; ?>"><span><img width="25px" height="25px" src="/static/api/pintuan/images/552cd71a8a190_128.png"></span><br><span>待付款</span></a></li>
		        <li class="l"><a href="<?php echo "/api/$UsersID/pintuan/teamlist/1/"; ?>"><span><img width="25px" height="25px" src="/static/api/pintuan/images/552cd7015c801_128.png"></span><br><span>拼团中</span></a></li>
		        <li class="l"><a href="<?php echo "/api/$UsersID/pintuan/teamlist/2/"; ?>"><span><img width="25px" height="25px" src="/static/api/pintuan/images/552cd6dc78241_128.png"></span><br><span>拼团成功</span></a></li>
	            <li class="l"><a href="<?php echo "/api/$UsersID/pintuan/orderlist/3/"; ?>"><span><img width="25px" height="25px" src="/static/api/pintuan/images/552cd6f0b1fea_128.png"></span><br><span>已发货</span></a></li>
	            <li class="l"><a href="<?php echo "/api/$UsersID/pintuan/teamlist/3/"; ?>"><span><img width="25px" height="25px" src="/static/api/pintuan/images/552cd70fd621a_128.png"></span><br><span>退款</span></a></li>
	        </ul>
		</div>
	</div>
	<div class="clear"></div>
   	<div class="nav_lb">
        <ul class="nav_list">
			<li class="l"><a href="<?php echo "/api/$UsersID/pintuan/teamlist/0/"; ?>"><span><img width="35px" height="35px" src="/static/api/pintuan/images/001.png"></span><br><span>我的团</span></a></li>
			<li class="l"><a href="<?php echo "/api/$UsersID/pintuan/choujiang/0/"; ?>"><span><img width="35px" height="35px" src="/static/api/pintuan/images/004.png"></span><br><span>我的抽奖</span></a></li>
			<li class="l"><a href="<?php echo "/api/$UsersID/pintuan/mycart/"; ?>"><span><img width="35px" height="35px" src="/static/api/pintuan/images/002.png"></span><br><span>我的收藏</span></a></li>
			<li class="l"><a href="<?php echo "/api/$UsersID/pintuan/my/address/"; ?>"><span><img width="35px" height="35px" src="/static/api/pintuan/images/003.png"></span><br><span>收货地址</span></a></li>
        </ul>
    </div>
	<div class="clear"></div>
	<div class="shuaxin"><a href="javascript:location.reload();">刷新页面</a></div>
	<div class="kb"></div>
	<div class="clear"></div>
	<div style="height:70px;"></div>

	<div class="cotrs">
		<a  href="<?php echo "/api/$UsersID/pintuan/"; ?>"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br />首页</a>
		<a  href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"><img src="/static/api/pintuan/images/002-2.png" width="22px" height="22px" style="margin-top:3px;"/><br />搜索</a>
		<a  href="<?php echo "/api/$UsersID/pintuan/user/"; ?>" class="thisclass"><img src="/static/api/pintuan/images/002-3.png" width="22px" height="22px" style="margin-top:3px;"/><br />我的</a>
	</div>

</div>
	<script type="text/javascript">
		$(function(){
			$('.shuaxin').click(function(){
				location.reload();
			});
		});
	</script>
</body>
</html>
