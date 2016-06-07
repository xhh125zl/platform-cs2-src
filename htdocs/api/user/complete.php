<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if (isset($_GET["UsersID"])) {
	$UsersID = $_GET["UsersID"];
} else {
	echo '缺少必要的参数';
	exit;
}

$base_url = base_url();
$user_url = $base_url.'api/'.$UsersID.'/user/';

$is_login = 1;
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php';
$User_ID = $_SESSION[$UsersID."User_ID"];
$user = User::find($User_ID);

$error = $success= false;

if($_POST){	
	$flag = false;
	$User_Mobile = $request->input('User_Mobile');	
	$is_check = 1;
	if($setting["sms_enabled"]==1){
		$Sms_Code = $request->input('Ran_Code');
		if(check_short_msg($User_Mobile,$Sms_Code,$UsersID)){
			$is_check = 1;
		}else{
			$is_check = 0;
		}
	}
	//如果手机短信验证码正确
	if($is_check==1){
		$user->User_Profile = 1 ;
		$user->User_Mobile = $User_Mobile ;
		$user->User_Password = md5($request->input('User_Password'));
		$success = $user->save();
		if(!empty($_SESSION[$UsersID."HTTP_REFERER"])){	
			echo '<script type="text/javascript">alert("恭喜你，绑定成功!");window.location.href="'.$_SESSION[$UsersID."HTTP_REFERER"].'";</script>';
			exit;
		}
	}else{
		$error = true;
	}
}

$header_title = '完善资料';
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $header_title;?></title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>
    <script type='text/javascript' src='/static/api/js/global.js'></script>
    <script src="/static/api/distribute/js/distribute.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>
<body>
<link href="/static/api/distribute/css/bind_mobile.css" rel="stylesheet">
<link href='/static/api/css/user.css' rel='stylesheet' type='text/css' />
<script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type='text/javascript' src='/static/js/jquery.validate.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.metadata.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.zh_cn.js'></script>
<script src="/static/api/js/mobile_bind.js"></script>
<script type="text/javascript">
	var base_url = '<?=$base_url?>';
    var user_url = '<?=$user_url?>';
	var Users_ID = '<?=$UsersID?>';
	$(document).ready(function(){
		mobile_bind_obj.init();
	});
</script>
<div class="wrap">
	<div class="container">
    	<div class="row page-title">
           <a href="javascript:history.go(-1)" id="back-arrow" class="pull-left"><img src="/static/api/shop/skin/default/images/white_arrow_left.png"></a>
           <h4>绑定手机号码</h4>
        </div>
		
  <div class="row">
  
  <?php if($error):?>
  	<div class="alert alert-danger alert-dismissable">
   	<button type="button" class="close" data-dismiss="alert" 
    	  aria-hidden="true">
      &times;
   </button>
   	短信验证码错误，请核对后再绑定!!!
	</div>
  <?php endif;?>
  
  <?php if($success):?>
  <div class="alert alert-success alert-dismissable">
   	<button type="button" class="close" data-dismiss="alert" 
    	  aria-hidden="true">
      &times;
   </button>
   	恭喜你，绑定成功!
	</div>
  
  <?php endif;?>
        	
    <ul class="list-group" id="bind_mobile_panel">
	  <form method="post" action="/api/<?=$UsersID?>/user/complete/"  id="bind_mobile_form">
	 
	  <li class="list-group-item" >
		 <span class="red">*</span>&nbsp;&nbsp;<label>手机号码：</label><input type="text" name="User_Mobile" value="" required isMobile="true"  id="User_Mobile" placeholder="" />
		 
	  </li>
	  <?php if($setting["sms_enabled"]==1){?>
	   <li class="list-group-item" >
		 <span class="red">*</span>&nbsp;&nbsp;<label id="label-rancode">&nbsp;&nbsp;验证码：</label><input type="text" id="ran-code" name="Ran_Code" value="" required   size="12" placeholder="" />
		 <a class="btn btn-default pull-right" id="send-rancode">发送验证码</a>
		 <div class="clearfix"></div>
	  </li>
	  <?php }?>
	  <li class="list-group-item" >
			 <span class="red">*</span>&nbsp;&nbsp;<label>设置密码：</label><input type="password" name="User_Password" size="18" equalTo="#Password_Confirm" id="User_Password" value="" required   placeholder="" />
	  </li>
	  
		<li class="list-group-item" >
			 <span class="red">*</span>&nbsp;&nbsp;<label>确认密码：</label><input type="password" name="Password_Confirm" size="18"  equalTo="#User_Password" id="Password_Confirm" value="" required  placeholder="" />
	  </li>

	  
	  
		<li class="list-group-item  text-center">

		 <input type="submit" value="确认绑定" class="btn btn-default" id="submit-btn"/>
	  </li>


	  </form>
     
    </ul>
          
        </div>

        <!-- 绑定描述begin -->
	    <?php require_once('bind_mobile_desc.php');?> 
        <!-- 绑定描述end -->
    </div>
    
</div>

<?php require_once('footer.php');?> 
 
 
</body>
</html>
