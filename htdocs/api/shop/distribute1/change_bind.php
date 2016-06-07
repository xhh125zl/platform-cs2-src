<?php
require_once('global.php');
$user = User::find($User_ID);

if(strlen($user['User_Mobile']) == 0 ){
	header("location:/api/".$UsersID."/shop/distribute/bind_mobile/");
}

$error = $success= false;

if($_POST){
	$flag = false;
	$User_Mobile = $request->input('User_Mobile');
	$Sms_Code = $request->input('Ran_Code');
	
	//如果手机短信验证码正确
	if(check_short_msg($User_Mobile,$Sms_Code,$UsersID)){
		$user->User_Mobile = $User_Mobile ;
		$success = $user->save();
	}else{
		$error = true;
	}
	
}
$header_title = '更改绑定手机';
require_once('header.php');

?>
<body>
<link href="/static/api/distribute/css/bind_mobile.css" rel="stylesheet">
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script type='text/javascript' src='/static/js/jquery.validate.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.metadata.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.zh_cn.js'></script>
<script src="/static/api/distribute/js/mobile_bind.js"></script>
<script type="text/javascript">
	var base_url = '<?=$base_url?>';
    var shop_url = '<?=$shop_url?>';
	var Users_ID = '<?=$UsersID?>';	     
	$(document).ready(function(){		
		mobile_bind_obj.init();
	});
</script>

<div class="wrap">
	<div class="container">
    	<div class="row page-title">
           <a href="javascript:history.go(-1)" id="back-arrow" class="pull-left"><img src="/static/api/shop/skin/default/images/white_arrow_left.png"></a>
           <h4>更改绑定手机</h4>
        </div>
		
  <div class="row">
  
  <?php if($error):?>
  	  <div class="alert alert-success alert-dismissable">
   	<button type="button" class="close" data-dismiss="alert" 
    	  aria-hidden="true">
      &times;
   </button>
   	短信验证码错误，请核对后再绑定!!!
	</div>
  <?php endif;?>
  
  <?php if($success):?>
  <div class="alert alert-info alert-dismissable">
   	<button type="button" class="close" data-dismiss="alert" 
    	  aria-hidden="true">
      &times;
   </button>
   	号码更改成功 &nbsp;&nbsp<a href="<?=shop_url()?>">点击返回主页</a>
	</div>
  
  <?php endif;?>
        	
            <ul class="list-group" id="bind_mobile_panel">
  <form method="post" action="/api/<?=$UsersID?>/shop/distribute/change_bind/"  id="bind_mobile_form">
 
  <li class="list-group-item">
  <span class="pull-left">
     您现在的号码是:<?=star_mobile($user['User_Mobile'])?> 
  </span>
  <span class="pull-right">
     <a class="red" href="<?=shop_url('distribute/forget_pwd/')?>">忘记密码</a>
  </span>
  <div class="clearfix"></div>
  </li>
  
  <li class="list-group-item" >
  	 <span class="red">*</span>&nbsp;&nbsp;<label>手机号码：</label><input type="text" name="User_Mobile" value="" required isMobile="true"  id="User_Mobile" placeholder="" />
     
  </li>
  
   <li class="list-group-item" >
  	 <span class="red">*</span>&nbsp;&nbsp;<label id="label-rancode">&nbsp;&nbsp;验证码：</label><input type="text" id="ran-code" name="Ran_Code" value="" required   size="12" placeholder="" />
     <a class="btn btn-default pull-right" id="send-rancode">发送验证码</a>
     <div class="clearfix"></div>
  </li>  
 	<li class="list-group-item  text-center">

     <input type="submit" value="确认修改" class="btn btn-default" id="submit-btn"/>
  </li>


 </form>
     
</ul>
          
        </div>

       <!-- 绑定描述begin -->
	    <?php require_once('bind_mobile_desc.php');?> 
        <!-- 绑定描述end -->
        
    </div>
    
  	
  
    
</div>

<?php require_once('../skin/distribute_footer.php');?> 
 
 
</body>
</html>
