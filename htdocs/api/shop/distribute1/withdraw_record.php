<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

//获取帮助此用户的记录
//Record_Type 为1的记录就是提现记录

$condition ="where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and  Record_Type=1";

if(isset($_GET['status'])){
	$status = $_GET['status']; 
	$condition .= " and Record_Status=".$status;
}else{
	$status = 'all';
}

$rsRecords = $DB->get('shop_distribute_account_record','*',$condition);
$records = $DB->toArray($rsRecords);

$Status = array("申请中","已执行","已驳回");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>分销账户明细</title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/css/withdraw_record.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>
    <script type='text/javascript' src='/static/api/js/global.js'></script>
    <script src="/static/api/distribute/js/distribute.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			distribute_obj.withdraw_record_init();
		});
    </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body>

<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/shop/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">提现记录</h1>
  
</header>

<div class="wrap">
	<ul id="withdraw-record-nav" class="list-group">
         <li class="item  <?php if($status == 'all'){echo 'cur';}?>" ><a href="<?=$base_url?>api/<?=$UsersID?>/shop/distribute/withdraw_record/" />全部</a></li>
         <li class="item  <?php if($status == '0'){echo 'cur';}?>" ><a href="<?=$base_url?>api/<?=$UsersID?>/shop/distribute/withdraw_record/status/0/" />申请中</a></li>
         <li class="item  <?php if($status == '1'){echo 'cur';}?>" ><a href="<?=$base_url?>api/<?=$UsersID?>/shop/distribute/withdraw_record/status/1/" />已执行</a></li>
         <li class="item  <?php if($status == '2'){echo 'cur';}?>" ><a href="<?=$base_url?>api/<?=$UsersID?>/shop/distribute/withdraw_record/status/2/" />已驳回</a></li>
         <li class="clearfix"></li>
      </ul>
      
     
      	<ul class="list-group" id="withdraw-list">
        	<?php foreach($records as $key=>$item): ?>
        	<li class="list-group-item">
     <a class="record-title" status="close">
      <i class="fa fa-sign-out"></i>
      <?=$item['Record_Sn']?><span class="red">(¥<?=$item['Record_Money']?>)</span>
      <span><?=$Status[$item['Record_Status']];?></span>
      <span class="fa fa-chevron-up"></span>
    </a>
    	<div  class="detail">
    <div>
    <p class="account_info">
    提现账号:&nbsp;&nbsp;<?=$item['Account_Info']?>
    </p>
   	 	<p>
    	申请时间：&nbsp;&nbsp;<?=sdate($item['Record_CreateTime'])?><br/>
    	金额:&nbsp;&nbsp;¥<?=$item['Record_Money']?><br/>
		<?php if($item['Record_Status'] == 2):?>
    	<span class="red">驳回原因:&nbsp;&nbsp;<?=$item['Record_Description']?></span><br/>
    	<?php endif;?>
    	状态:&nbsp;&nbsp;<?=$Status[$item['Record_Status']];?>
    	</p>
    </div>
  	</div>
            </li>
            <?php endforeach; ?>
        </ul>
     
</div> 	
<?php require_once('../skin/distribute_footer.php');?> 

</body>
</html>