<?php
require_once('global.php');

if ($rsConfig['Distribute_Customize'] == 0) {
    $show_name = $rsUser['User_NickName'];
    $show_logo = !empty($rsUser['User_HeadImg']) ? $rsUser['User_HeadImg'] : '/static/api/images/user/face.jpg';
} else {
    $show_name = !empty($rsAccount['Shop_Name']) ? $rsAccount['Shop_Name'] : '暂无';
    $show_logo = !empty($rsAccount['Shop_Logo']) ? $rsAccount['Shop_Logo'] : '/static/api/images/user/face.jpg';
}

$levelids = array_keys($dis_level);
$LastLevel = end($levelids);
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>级别升级</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/distribute/css/style.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/distribute/css/upgrade.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="/static/css/font-awesome.css">
<link href="/static/api/distribute/css/distribute_center.css" rel="stylesheet">
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/distribute/js/upgrade.js?t=<?php echo time();?>'></script>
<script language="javascript">
var UsersID = "<?=$UsersID?>";
var ajax_url  = "<?=distribute_url()?>ajax/";
$(document).ready(upgrade_obj.upgrade_init);
</script>
<style type="text/css">
.distribute_header #header_cushion #account_info #txt { margin-top: 25px; }
    #txt li { color: #FFF; font-size: 14px; line-height: 25px; }
    .upgrade_head { margin-top: 20px; }
	.error_msg{height:60px; line-height:60px; text-align:center; width:100%}
</style>
</head>

<body>

    <div class="container">

        <div class="row">
         <div class="distribute_header" style="height:115px; border:none">
      <div id="header_cushion">
     
            <div id="account_info" style="width:100%">
                <div class="pull-left" style="width:25%">
                         <img id="hd_image" src="<?=$show_logo?>"/>
                </div>
            
                <div class="pull-right" style="width:75%;">
                    <ul id="txt" style="padding-left:0px;">
                      <li>昵称：<?=$show_name?>　　ID：<?php echo $rsAccount['User_ID']; ?></li>
                      <li style="padding-right:3px;">分销级别：<?php echo $dis_level[$rsAccount['Level_ID']]['Level_Name']; ?></li>
                      <li style="padding-right:3px;">
                        <?php if(strlen($rsUser['User_Mobile'])==0): ?> 
                           <a style="color:#4878C6;text-decoration:underline;line-height:20px;" href="<?=distribute_url('bind_mobile/')?>">&nbsp;【绑定手机】</a>
                        <?php else:?>
                          手机：<?php echo $rsUser['User_Mobile'];?>　　<a style="color:#4878C6;line-height:20px;" href="<?=distribute_url('change_bind/')?>">&nbsp;【更改手机】</a>
                        <?php endif;?>
                      </li>
                  
                    </ul>
                </div>
                
                <div class="clearfix">
                </div>
            </div>
      </div>      
      </div>
    </div>
<?php if($LastLevel<=$rsAccount['Level_ID']){?>
<div class="error_msg">你已经是最高级，无需升级！</div>
<?php }else{?>
<form id="upgrade_buy" class="upgrade_form">
	<div class="level_list">
    	<h2>请选择级别</h2>
    	<ul>
<?php
$update_fee = 0;
foreach($dis_level as $key=>$value){
	if($key<=$rsAccount['Level_ID']){
		continue;
	}
	if($value['Level_UpdateType']==0){
		$update_fee = $update_fee + $value['Level_UpdateValue'];
		$limit_value = '需支付'.$update_fee.'元';
	}else{
		$limit_value = '需购买指定商品';
	}
?>
			
        	<li lid="<?php echo $value['Level_ID'];?>"><img src="<?php echo $value['Level_ImgPath'];?>"><strong><?php echo $value['Level_Name'];?></strong><b><?php echo $limit_value;?></b></li>
<?php }?>            
        </ul>
    </div>
    <div class="level_total">总计：<span></span></div>
    <div class="level_submit">
    <input type="hidden" name="action" value="upgrade_level_order" />
    <input name="LevelID" type="hidden" value="" />
    <input type="submit" name="submit" value="确定" class="submit">
    </div>
    <?php $rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");?>
    <div class="pay_select_list" id="">
        <h2><span id="close-btn">[×]</span>请选择支付方式</h2>
		<h3>支付总额：<span></span></h3>
        <?php if(!empty($rsPay["PaymentWxpayEnabled"])){ ?>
		<a href="javascript:void(0)" class="btn btn-default btn-pay direct_pay" id="wzf" data-value="1"><img  src="/static/api/shop/skin/default/wechat_logo.jpg" width="16px" height="16px"/>&nbsp;微信支付</a>
		<?php }?>
		<?php if(!empty($rsPay["Payment_AlipayEnabled"])){?>
		<a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="zfb" data-value="2"><img  src="/static/api/shop/skin/default/alipay.png" width="16px" height="16px"/>&nbsp;支付宝支付</a>
		<?php }?>
<?php
if ($rsPay['Payment_RmainderEnabled'] == 1) {
?>
		<a href="javascript:void(0)" class="btn btn-warning  btn-pay" id="money" data-value="余额支付"><img  src="/static/api/shop/skin/default/money.jpg"  width="16px" height="16px"/>&nbsp;余额支付</a>
<?php
}
?>


        <p class="money_info">
			<b style="display:block; width:85%; height:40px; line-height:38px; font-size:14px; margin:0px auto; font-weight:normal">您当前余额：<span style="padding:0px 5px; font-size:16px; color:#F00">&yen;<?php echo $rsUser['User_Money'];?></span></b>
        	<input type="password" placeholder="请输入支付密码，默认123456" style="display:block; width:85%; margin:5px auto 0px; border:1px #dfdfdf solid; border-radius:5px; height:40px; line-height:40px;">
            <button style="display:block; width:85%; margin:5px auto 0px; border:none; border-radius:5px; background:#4F93CE; color:#FFF; font-size:14px; text-align:center; height:40px; line-height:38px;">确认支付</button>
        </p>
    </div>
    
    <div class="products_list_tobuy">
	<h2><span>[×]</span>请选择商品</h2>
    <div class="products_list_content">
    </div>
</div>
</form>
<div class="body_bg"></div>
<?php }?>

<?php require_once('../shop/skin/distribute_footer.php');?> 

</body>
</html>
