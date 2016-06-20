<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
$base_url = base_url();
if(isset($_GET["UsersID"])){
	$UsersID = $_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

$TypeID = empty($_GET["TypeID"]) ? 0 : $_GET["TypeID"];

if(isset($_GET['action']) && isset($_GET['AddressID'])){
	if($_GET["action"]=="del")
	{
		$AddressID = empty($_GET['AddressID'])?0:$_GET['AddressID'];
		$condition = "Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Address_ID='".$AddressID."'";
		$rsAddress = $DB->GetRs("user_address","*",'where '.$condition);
		
		//将另一个地址设置为默认地址
		$set_another_default = 0;
		if($rsAddress['Address_Is_Default'] == 1){
			$set_another_default = 1;	
		}
		
		$Flag=$DB->Del("user_address",$condition);
		
		if($set_another_default == 1){
			set_anoter_default($UsersID,$_SESSION[$UsersID."User_ID"]);
		}
		
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	
}

if(isset($_GET['OpenID'])){
	$_SESSION[$UsersID.'OpenID']=$_GET['OpenID'];
	header("location:/api/".$UsersID."/user/my/address/".(empty($TypeID)?'':$TypeID.'/')."?wxref=mp.weixin.qq.com");
	exit;
}else{
	if(empty($_SESSION[$UsersID.'OpenID'])){
		$_SESSION[$UsersID.'OpenID']=session_id();
	}
}
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}

$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
if(isset($_SESSION[$UsersID."User_ID"])){
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
}else{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/my/address/?wxref=mp.weixin.qq.com";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}

//用户收货地址列表
$rsAddress = $DB->get("user_address","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'");
$address_list = $DB->toArray($rsAddress);

$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
$area_array = json_decode($area_json,TRUE);
$province_list = $area_array[0];

if(!empty($_GET['AddressID'])){
	 $Select_Model = TRUE;
	 $AddressID = $_GET['AddressID'];
	 $_SESSION[$UsersID."Select_Model"] = 1;

}else{
	 $Select_Model = FALSE;
}

$redirect_url = empty($_SESSION[$UsersID."HTTP_REFERER"]) ? $_SERVER['HTTP_REFERER'] : $_SESSION[$UsersID."HTTP_REFERER"];

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收货地址管理</title>

<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css?t=1436336112' rel='stylesheet' type='text/css' />
<link href="/static/css/bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" href="/static/css/font-awesome.css" />
<link rel="stylesheet" href="/static/api/shop/skin/default/css/address_list.css" />
	
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=1436336112'></script>
<script type='text/javascript' src='/static/api/shop/js/user.js?t=1436336112'></script>
<script type='text/javascript'>
   var base_url = '<?=$base_url?>';
   var Users_ID = '<?=$UsersID?>';
	$(document).ready(function(){
		var redirect_url = '<?=$redirect_url?>';
		function handler(AddressID){
	        
			window.location.href = redirect_url+AddressID+'/';
		}
		
		$('.select_address').click(function(){
			$(".select_address").each(function(){
				$(this).find('span').attr("class","fa fa-check grey");
			});
			$(this).find('span').attr("class","fa fa-check red");
			
			var address_id = $(this).attr('address_id');
			setTimeout(handler(address_id),500);
		});
	});
</script>

</head>
<body >
	
<header class="bar bar-nav">
        <a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a>
        <a href="/api/<?=$UsersID?>/shop/cart/" class="pull-right"><img src="/static/api/shop/skin/default/images/cart_two_points.png" /></a>
        <h1 class="title" id="page_title">收货地址管理 </h1>
</header>
    
    
    <div id="wrap">
    	<!-- 地址信息简述begin -->
      
		<div class="container">
        
		<?php if(count($address_list) >0 ):?>
			<?php foreach($address_list as $key=>$address): ?>
			<?php
				$Province = $province_list[$address['Address_Province']];
				$City = $area_array['0,'.$address['Address_Province']][$address['Address_City']];
				$Area = $area_array['0,'.$address['Address_Province'].','.$address['Address_City']][$address['Address_Area']];
			
			?>
				<div  class="row receiver-info">
					<dl>
						<dd class="col-xs-1">
						<?php if($Select_Model):?>
						<a href="javascript:void(0)" address_id = '<?=$address['Address_ID']?>' class="select_address">&nbsp;&nbsp;<span class="fa fa-check <?=($AddressID == $address['Address_ID'])?'red':'grey'?>"></span></a>
						<?php endif;?>
						</dd>
						<dd class="col-xs-9"><p><?=$address['Address_Name']?>&nbsp;&nbsp;&nbsp;&nbsp;<?=$address['Address_Mobile']?><br/>
							所在地区:<?=$Province?>&nbsp;&nbsp;<?=$City?>&nbsp;&nbsp;<?=$Area?><br/>
							详细地址:<?=$address['Address_Detailed']?>
							<?=$address['Address_Is_Default'] == 1?'&nbsp;&nbsp;<span class="red">默认</span>':''?>	
						</p></dd>
						<dd class="col-xs-1">
                    <a class="edit_address" href="/api/<?=$UsersID?>/user/my/address/del/<?=$address['Address_ID']?>/"><span class="fa fa-remove red"></span></a>    <br/>
                    <a class="edit_address" href="/api/<?=$UsersID?>/user/my/address/edit/<?=$address['Address_ID']?>/"><span class="fa fa-pencil"></span></a>
                     </dd>
					</dl>
				</div>
			<?php endforeach; ?>   
			<br/>
	    <?php else: ?>
			<div  class="row">
				<p style="text-align:center"><br/>暂无收货地址，请添加</p>
			</div>
        <?php endif; ?>
		</div> 
		
		<a id="manage-address-btn" href="/api/<?=$UsersID?>/user/my/address/edit/">新增收货地址</a>
    	<!-- 地址信息简述end-->
   
     
    </div>
    
    <!-- 属性选择内容begin -->
	</body>

</html>
