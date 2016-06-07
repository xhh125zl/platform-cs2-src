<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/General_tree.php');

$my_shop_url = shop_url().$_SESSION[$UsersID.'User_ID'].'/';

$qrcode_path = generate_qrcode($my_shop_url );

?>
<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>我的推广</title>
	 <link rel="stylesheet" href="/static/css/font-awesome.css">
     <link href="/static/css/bootstrap.css" rel="stylesheet">
     <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/css/distribute.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="./static/js/jquery-1.11.1.min.js"></script>
	
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body>
<div class="wrap">
	<div class="container">
    <h4 class="row page-title">我的推广</h4>
    </div>
    
  
    <ul id="distribute_group">
   		 <li class="item "><a href="/api/<?=$UsersID?>/shop/distribute/group/?wxref=mp.weixin.qq.com">我的团队</a></li>
   		<li class="item cur"><a href="/api/<?=$UsersID?>/shop/distribute/my_distribute/?wxref=mp.weixin.qq.com">我的推广</a></li>
   		<li class="item"><a href="/api/<?=$UsersID?>/shop/distribute/income/?wxref=mp.weixin.qq.com">分销佣金</a></li>
  		<li class="clearfix"></li>
  	</ul>

  
  	<div id="recommend_list">
    	 <div class="recommend_item">
         	<a href="javascript:void(0)"/>
            	<img src="/static/api/distribute/images/order.png"/><br/>
                推广销售订单
            </a>
         </div>
         
         <div class="recommend_item">
         	 <a href="javascript:void(0)"/>
            	<img src="/static/api/distribute/images/link.png"/><br/>
                推广链接订单
            </a>
         </div>
         
        
         <div class="clearfix">
         </div>
    </div>
  
  
  	<div class="container">
    	<h5 class="text-center">我的二维码</h5>
        <div class="row text-center">
        	<img src="<?=$qrcode_path?>"/>
        </div>
    </div>
</div>
</body>
</html>


