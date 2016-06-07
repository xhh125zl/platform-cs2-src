<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');

//获取帮助此用户的记录
//Record_Type 为1的记录就是提现记录

$condition ="where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'";

if(isset($_GET['status'])){
	$status = $_GET['status']; 
	$condition .= " and Record_Status=".$status;
}else{
	$status = 'all';
}

$rsRecords = $DB->get('distribute_withdraw_record','*',$condition);
$records = $DB->toArray($rsRecords);

$Status = array('<font style="color:#F60">申请中</font>','<font style="color:blue">已执行</font>','<font style="color:#F00; text-decoration:line-through">已驳回</font>');
$pay_order = new pay_order($DB, 0);
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
		var base_url = '<?=$base_url?>';
		var UsersID = '<?=$UsersID?>';
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
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">提现记录</h1>
  
</header>

<div class="wrap">
	<ul id="withdraw-record-nav" class="list-group">
         <li class="item  <?php if($status == 'all'){echo 'cur';}?>" ><a href="<?=$base_url?>api/<?=$UsersID?>/distribute/withdraw_record/" />全部</a></li>
         <li class="item  <?php if($status == '0'){echo 'cur';}?>" ><a href="<?=$base_url?>api/<?=$UsersID?>/distribute/withdraw_record/status/0/" />申请中</a></li>
         <li class="item  <?php if($status == '1'){echo 'cur';}?>" ><a href="<?=$base_url?>api/<?=$UsersID?>/distribute/withdraw_record/status/1/" />已执行</a></li>
         <li class="item  <?php if($status == '2'){echo 'cur';}?>" ><a href="<?=$base_url?>api/<?=$UsersID?>/distribute/withdraw_record/status/2/" />已驳回</a></li>
         <li class="clearfix"></li>
      </ul>
      
     
      	<ul class="list-group" id="withdraw-list">
        	<?php foreach($records as $key=>$item): ?>
			<?php
				if($item['Record_Status'] == 1 && $item['Record_SendType']=='wx_hongbao' && $item['Record_SendTime']<time()-29500){
					$chaxun_status = $pay_order->checkhongbao($UsersID,$item['Record_SendID']);
				}else{
					$chaxun_status = 2;
				}
			?>
        	<li class="list-group-item">
     <a class="record-title" status="close">
      <i class="fa fa-sign-out"></i>
      <?=date('Y-m-d H:i:s',$item['Record_CreateTime'])?><span class="red">(¥<?=$item['Record_Money']?>)</span>
      <span class="fa fa-chevron-up"></span>
    </a>
    	<div  class="detail">
    <div>
    
   	 <p style="line-height:26px; margin-top:5px; border-top:1px #dfdfdf dotted; padding-top:8px;">
		提现方式：<?=$item['Method_Name']?><br />
		<?php if(!$item['Record_SendType']){?>
		提现账号：<?php echo $item['Method_No'];?>，<?php echo $item['Method_Account'];?><?php echo $item['Method_Bank'] ? '，'.$item['Method_Bank'] : '';?><br />
		<?php }?>
    	金额:&nbsp;&nbsp;¥<?=$item['Record_Money']?><br/>
    	状态:&nbsp;&nbsp;<?=$Status[$item['Record_Status']];?>
		<?php if($item['Record_Status'] == 2):?>
    	<br /><span style="color:#999">驳回原因:&nbsp;&nbsp;<?=$item['No_Record_Desc']?></span><br/>
    	<?php endif;?>
		<?php if($chaxun_status==1){?>
		<a href="javascript:void(0);" ret="<?php echo $item['Record_ID'];?>" class="send_hongbao" style="display:block; border-radius:5px; margin-top:5px; height:30px; line-height:28px; width:80px; background:#F60; color:#FFF; text-align:center">领取红包</a>
		<?php }?>
    	</p>
    </div>
  	</div>
            </li>
            <?php endforeach; ?>
        </ul>
     
</div> 	
<?php require_once('../shop/skin/distribute_footer.php');?> 

</body>
</html>