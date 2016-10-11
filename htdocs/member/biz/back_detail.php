<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
        $id = $_GET['itemid'];
        $BizBackInfo = $DB->GetRS('biz_bond_back','*','WHERE id = '.$id); 
        
 
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script src="/static/js/plugin/layer/layer.js"></script>
<style type="text/css">
#bizs .search{padding:10px; background:#f7f7f7; border:1px solid #ddd; margin-bottom:8px; font-size:12px;}
#bizs .search *{font-size:12px;}
#bizs .search .search_btn{background:#1584D5; color:white; border:none; height:22px; line-height:22px; width:50px;}
</style>
 <link href='/static/member/css/audit.css' rel='stylesheet' type='text/css' />
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">

         
     <div class="w">
	<div class="main_xx">
		<p>支付宝账号：<?php echo !empty($BizBackInfo['alipay_account'])?$BizBackInfo['alipay_account']:'未填写!'?></p>
		<p>支付宝姓名：<?php echo !empty($BizBackInfo['alipay_username'])?$BizBackInfo['alipay_username']:'未填写!'?></p>
    	<p>申请理由：
        
      
            <span>&nbsp;&nbsp;&nbsp;</span>
            <span>
                <?php echo !empty($BizBackInfo['info'])?$BizBackInfo['info']:'未填写!'?>
            </span>
       
         </p>
         
    </div>
     
     
    

</div> 
    
  </div>
</div>
    
</body>
</html>