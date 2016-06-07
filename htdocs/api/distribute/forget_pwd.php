<?php
require_once('global.php');

$user = User::find($User_ID);

$error = false;
$success = false;

if($_POST){	
	$flag = false;
	$User_Mobile = $request->input('User_Mobile');
	$Sms_Code = $request->input('Ran_Code');	
	//如果手机短信验证码正确
	if(check_short_msg($User_Mobile,$Sms_Code,$UsersID)){
		$user->User_Mobile = $User_Mobile ;
		$user->User_Password = md5($request->input('User_Password'));
		$success = $user->save();
	
	}else{
		$error = true;
	}
}

$header_title = '忘记密码';
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
    var distribute_url = '<?=distribute_url();?>';
	var Users_ID = '<?=$UsersID?>';
	$(document).ready(function(){
		mobile_bind_obj.init();
	});
</script>
<div class="wrap">
	<div class="container">
    	<div class="row page-title">
           <a href="javascript:history.go(-1)" id="back-arrow" class="pull-left"><img src="/static/api/shop/skin/default/images/white_arrow_left.png"></a>
           <h4>忘记密码</h4>
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
   	恭喜你，密码重置成功&nbsp;&nbsp;<a href="<?=shop_url()?>">点击返回主页</a>
	</div>
  
  <?php endif;?>
        	
            <ul class="list-group" id="bind_mobile_panel">
  <form method="post" action="/api/<?=$UsersID?>/distribute/forget_pwd/"  id="bind_mobile_form">
 
  <li class="list-group-item" >
	 <input type="hidden" name="User_Mobile" id="User_Mobile" value="<?=$user['User_Mobile']?>" />
     您的绑定手机是&nbsp;&nbsp;<span class="red"><?=star_mobile($user['User_Mobile'])?></span>
  </li>
  
   <li class="list-group-item" >
  	 <span class="red">*</span>&nbsp;&nbsp;<label id="label-rancode">&nbsp;&nbsp;验证码：</label><input type="text" id="ran-code" name="Ran_Code" value="" required   size="12" placeholder="" />
     <a class="btn btn-default pull-right" id="send-rancode">发送验证码</a>
     <div class="clearfix"></div>
  </li>
  
  <li class="list-group-item" >
      	 <span class="red">*</span>&nbsp;&nbsp;<label>设置密码：</label><input type="password" name="User_Password" size="18" equalTo="#Password_Confirm" id="User_Password" value="" required   placeholder="" />
  </li>
  
    <li class="list-group-item" >
      	 <span class="red">*</span>&nbsp;&nbsp;<label>确认密码：</label><input type="password" name="Password_Confirm" size="18"  equalTo="#User_Password" id="Password_Confirm" value="" required  placeholder="" />
  </li>

  
  
 	<li class="list-group-item  text-center">

     <input type="submit" value="重置密码" class="btn btn-default" id="submit-btn"/>
  </li>


 </form>
     
</ul>
          
        </div>

         <!-- 绑定描述begin -->
	    <?php require_once('bind_mobile_desc.php');?> 
        <!-- 绑定描述end -->
    </div>
    
  	
  
    
</div>

<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>
