<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

$base_url = base_url();
$shop_url = shop_url();

if(isset($_GET["UsersID"])){
  $UsersID = $_GET["UsersID"];
}else{
  echo '缺少必要的参数';
  exit;
}

$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

$is_login = 1;
$owner = get_owner($rsConfig,$UsersID);
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php';
$owner = get_owner($rsConfig,$UsersID);

//分销级别处理文件
include($_SERVER["DOCUMENT_ROOT"].'/api/distribute/distribute.php');

$User_ID=$_SESSION[$UsersID."User_ID"];

if(!$distribute_flag){
	header("location:/api/" . $UsersID . "/distribute/join/");
	exit;
}
$accountObj =  Dis_Account::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID))
			   ->first();
$Fuxiao = $rsConfig["Fuxiao_Rules"] ? json_decode($rsConfig['Fuxiao_Rules'],true) : array();
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>复销规则</title>
<link href="/static/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="/static/css/font-awesome.css">
<link href="/static/api/distribute/css/style.css" rel="stylesheet">
<link href="/static/api/distribute/css/apply_distribute.css" rel="stylesheet">

</head>

<body>
<div class="wrap">
	<div class="container">
	<?php if($accountObj->Is_Delete==1){?>
	 <div class="html_mes_red">您的分销商账号已被删除</div>
	 <div class="html_mes">由于您没有在规定的时间内复销，您的分销商账号已被删除</div>
	<?php }else{?>
	 <div class="html_mes_red">您的分销商账号已被冻结</div>
	 <div class="html_mes">由于您没有在一个月内消费满<span><?php echo $Fuxiao[0];?></span>元，所以您的分销商账号已被冻结；请在<span><?php echo $Fuxiao[1];?></span>天内消费满<span><?php echo $Fuxiao[0];?></span>元进行复销，否则账号将被删除。</div>	 
	<?php }?>
</div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
</body>
</html>

