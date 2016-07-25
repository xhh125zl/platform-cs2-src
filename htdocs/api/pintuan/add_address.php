<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

//拿到session的  Address_ID的数组  看数组中是否有address_id  这个值
if (isset($_SESSION['Address_ID '])) {
	$arr=$_SESSION['Address_ID '];
	$id=$arr['address_id'];
	$users_id=$_SESSION['Users_ID'];
	//判断$id是否设值  有跳转
	if(isset($id)){
	$url="/api/". $users_id."/user/gift/gift_order/";
	echo "<script language='javascript' 
	type='text/javascript'>";  
	echo "window.location.href='".$url."'";  
	echo "</script>"; 
	}
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
<title>新增收货地址</title>

<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css?t=1436336112' rel='stylesheet' type='text/css' />
<link href="/static/css/select2.css" rel="stylesheet"/>

<link href="/static/css/bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" href="/static/css/font-awesome.css" />
<link rel="stylesheet" href="/static/api/shop/skin/default/css/address_edit.css" />

<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.metadata.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.zh_cn.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=1436336112'></script>
<script type='text/javascript' src='/static/api/js/user.js?t=1436336112'></script>
<script type='text/javascript' src="/static/js/select2.js"></script>
<script type="text/javascript" src="/static/js/location.js"></script>
<script type="text/javascript" src="/static/js/area.js"></script>

<script type="text/javascript">
   var base_url = 'http://hfx.netcnnet.net/';
   var Users_ID = 'nrrw4thjn4';
   jQuery.validator.addMethod("isMobile", function(value, element) {       
    var length = value.length;   
    var mobile = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;   
     return this.optional(element) || (length == 11 && mobile.test(value));       
}, "格式错"); 

	$(document).ready(function(){
		showLocation(0,0,0);
		user_obj.my_address_init();
	});
	
</script>	

</head>


<body >


<header class="bar bar-nav">
        <a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a>
        <a href="/api/yd0tcni067/shop/cart/" class="pull-right"><img src="/static/api/shop/skin/default/images/cart_two_points.png" /></a>
        <h1 class="title" id="page_title">新增收货地址</h1>
</header>
    
    
    <div id="wrap">
    	<!-- 地址信息简述begin -->
        
		<div class="container">
        	<div class="row">
            <form  class="user_address_form" id="address_form" method="post" action="/api/<?php echo $UsersID ?>/user/gift/address/" >
        	<ul class="list-group">
            	<li class="list-group-item">
                <label>收货人：&nbsp;</label>
                <input type="text" name="Name"  required value="" />
                </li>
                <li class="list-group-item">
                <label>手机号码：&nbsp;</label>
                <input type="text" name="Mobile" required isMobile="true" value="" />
                </li>
                <li class="list-group-item">
					<label>所在省份：</label>
					<select name="Province"  id="loc_province" required style="width:65%">
						<option>选择省份</option>
					</select>
				</li>
				
				<li class="list-group-item">
					<label>所在城市：</label>
					<select name="City" id="loc_city"  required  style="width:65%">
						<option>选择城市</option>
					</select>
				</li>
				
				<li class="list-group-item">
					<label>所在区县：</label>
					<select name="Area"  id="loc_town" required  style="width:65%">
						<option>选择区县</option>
					</select>
				</li>
			
               
               
               
                <li class="list-group-item">
					<label>详细地址：&nbsp;</label>
					<input type="text" name="Detailed"  required value=""/>
					</li>
                                  	 <input type="hidden" name="default" value="1"  >
                 				<li class="list-group-item">
					
					<button id="submit-btn" type="submit">提交</button>
				
					<div class="clearfix"></div>
				</li>
            </ul>
			<!-- <input type="hidden" name="AddressID" value="0" />
			<input type="hidden" name="action" value="address_edit_save" /> -->
            </form>
            </div>
        </div> 
    	<!-- 地址信息简述end-->
    	
      
     
    </div>
    
    <!-- 属性选择内容begin -->



	</body>

</html>
