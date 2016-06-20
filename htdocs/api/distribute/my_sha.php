<?php
require_once('global.php');
$Sha_Rate = NULL;
if (isset($dis_config) && !empty($dis_config['Sha_Rate'])) 
{
	$Sha_Rate = json_decode($dis_config['Sha_Rate'], true);
}


//判断是不是股东
$DisRs = $DB->GetRs('distribute_account','sha_level,Enable_Agent',"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
$shaInfo = $DB->GetRs('sha_order', '*', ' where `Users_ID`="' .$UsersID. '" AND `User_ID`="' .$User_ID. '" AND `Order_Status` not in(2,3)');
                                        
if (empty($shaInfo)) 
{
        header('Location:/api/'.$UsersID.'/distribute/sha/'); exit();
	
}

$header_title = '我的股东申请信息'; 

require_once('header.php');
?>

<body>
<link href="/static/api/distribute/css/detaillist.css?t=<?php echo time();?>" rel="stylesheet">
<link href='/static/api/distribute/css/area_proxy.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/api/distribute/js/sha.js?t=<?php echo time();?>'></script>
<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">我的股东申请信息</h1>
</header>
<style type="text/css">
	.row { width: 100%; margin: 10px auto 0; }
	table { width: 97%; margin: 0 auto; border-color: #ddd; margin-bottom: 20px; }
	table tr td, table tr th.bodytable { height: 40px; line-height: 40px; text-align: center; }
	table tr th.bodytable { background: #eee; }
	tbody { border: 1px solid #ddd; background: #FFF; }
	h4 { width: 97%; margin: 10px  auto; }
	td.l { text-align: left; font-size: 12px; }
	span.m { display: block; overflow: hidden; font-size: 12px; height: 30px; line-height: 30px; border-bottom: 1px dotted #ddd; text-indent: 5px; }
	.leftContent { width: 20%; float: left; display: block; overflow: hidden; font-weight: bold; text-align: center; }
	.rightContent { width: 78%; float: left; display: block; overflow: hidden; border-left: 1px dotted #ddd; }
	.cleftContent, .crightContent { height: 30px; line-height: 30px; border-top: 1px dotted #ddd; }
	.tdContent { display: block; overflow: hidden; }
	span.bold { color: #ef363e; font-weight: bold; }
	td.bold { font-weight: bold; }
	form { width: 97%; margin: 0 auto; }
	.list-group-item { position: relative; display: block; padding: 10px 15px; margin-bottom: -1px; background-color: #fff; border: 1px solid #ddd; }
	.submit-btn { width: 80%; background: #3396FE; color: #ffffff; }
	.red { border-color: red; color: #333; }
	span.hideMsg { display: none; }
	h1.tips { background: red; color: #FFF; width: %inherit; width: 97%; margin: 0 auto; font-size: 16px; text-align: center; padding: 8px 0; border-radius: 5px; font-weight: bold; -webkit-box-shadow: 0 0 10px #F29611; -moz-box-shadow: 0 0 10px #F29611; box-shadow: 0 0 10px #F29611; margin-top: 20px; }
	.show_detail_link { display: block; overflow: hidden; width: 97%; padding: 0; margin: 10px auto; }
	.show_detail_link  a { display: inline-block; overflow: hidden; float: left; width: 40%; text-align: center; padding: 10px 0; background: #FFF; border: 1px solid #ddd; }
	.show_detail_link  a.r { float: right; }
	.dislist { width: 97%; margin: 10px auto; background: #FFF; border-radius: 5px; display: block; overflow: hidden; border: 1px solid #ddd; padding-bottom: 20px; }
	.disContent { padding: 10px; }
	.disContent dd { display: block; overflow: hidden; height: 30px; line-height: 30px; font-size: 12px; } 

	.disAction { display: block; overflow: hidden; padding: 13px 0; margin-right: 10px; padding-bottom: 10px; }
	.disAction a { display: block; overflow: hidden; width: 90%; margin: 0 auto; height: 30px; line-height: 30px; text-align: center; background: #ccc; margin-bottom: 15px; color: #FFF; border-radius: 5px; font-weight: bold; font-size: 12px; }
	.disAction a.cancel { background-color: #F00019; border: 1px solid #c10001; background-image: linear-gradient(bottom, #E20018 0, #DC3749 100%); background-image: -moz-linear-gradient(bottom, #E20018 0, #DC3749 100%); background-image: -webkit-linear-gradient(bottom, #E20018 0, #DC3749 100%); box-shadow: 0 1px 0 #D95260 inset, 0 1px 2px rgba(0,0,0,0.5); }
    .disAction a.pay { background-color: #f78d1d; border: 1px solid #da7c0c; background-image: linear-gradient(bottom,#faa51a 0, #f47a20 100%); background-image: -moz-linear-gradient(bottom, #faa51a 0, #f47a20 100%); background-image: -webkit-linear-gradient(bottom, #faa51a 0, #f47a2 100%); box-shadow: 0 1px 0 #D95260 inset, 0 1px 2px rgba(0,0,0,0.5);}
</style>
<script type="text/javascript">
var UsersID = "<?=$UsersID?>";
var ajax_url  = "<?=distribute_url()?>ajax/";
$(document).ready(sha_proxy_obj.sha_proxy_init);
$(document).ready(function(){
	$('a.cancel').click(function(){
		var UsersID = "<?php echo $UsersID; ?>";
		var currentObj = $('a.cancel');
		$.get('/api/distribute/ajax.php?UsersID='+UsersID, {'orderId':currentObj.attr('data-id'), 'action':'removeShaOrder'}, function(data){
			if (data.status == 1) { global_obj.win_alert('股东申请已取消！', function() {}); window.location.href="/api/"+UsersID+"/distribute/"; };
		}, 'json');
	});
});
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('#btn-addcard').click(function(){
		$('#area_post_box').submit();
	});
});
	
</script>
<div class="wrap">
	<div class="dislist ll_">
		<div class="disContent">
			<dd><b>申请姓名：</b><?php echo !empty($shaInfo['Applyfor_Name']) ? $shaInfo['Applyfor_Name'] : ''; ?></dd>
			<dd><b>状态：</b><span class="bold">
				<?php
				switch ($shaInfo['Order_Status']) {
					case '0':
						echo "待审核";
						break;
					
					case '1':
						echo "待付款";
						break;

					case '2':
						echo "已完成";
						break;

					case '3':
						echo "取消申请";
						break;

					case '4':
						echo "已拒绝";
						break;
				}
				?>
			</span></dd>

                        <dd><b>申请级别：</b><?php echo !empty($Sha_Rate['sha'][$shaInfo['Applyfor_level']]['name']) ? $Sha_Rate['sha'][$shaInfo['Applyfor_level']]['name'] : ''; ?></dd>

			<?php if($shaInfo['Order_Status'] == '4'): ?>
			<dd><b>拒绝原因：</b><?php echo $shaInfo['Refuse_Be']; ?></dd>
			<?php endif; ?>
			<dd><b>所需金额：</b><font color="#ef363e">¥<?php echo $shaInfo['Order_TotalPrice']; ?></font></dd>
			<dd><b>联系电话：</b><?php echo $shaInfo['Applyfor_Mobile']; ?></dd>
			<dd><b>申请时间：</b><?php echo date('Y-m-d H:i:s', $shaInfo['Order_CreateTime']); ?></dd>
		</div>

		<div class="disAction">
			<?php if($shaInfo['Order_Status'] == 1): ?>

                            <?php 
                            if($shaInfo['Order_TotalPrice']==0 && !empty($shaInfo['Applyfor_level'])){
                                $flaga = $DB->Set('distribute_account', array('Enable_Agent' => 1,'sha_level'=>$shaInfo['Applyfor_level']), ' WHERE `Users_ID`="' .$shaInfo['Users_ID']. '" AND `User_ID` = '.$shaInfo['User_ID']);
                                $flagb= $DB->Set('sha_order',array('Order_Status'=>2,'Order_PaymentMethod'=>'免费申请,费用0元','Order_PayTime'=>time()),'where Order_ID='.$shaInfo['Order_ID']);
                                if($flaga || $flagb){
                                    echo '<script>window.location.reload();</script>';
                                }
                            }
                            ?>

			<a href="javascript:void(0);" class="pay payaction" data-value="<?php echo $shaInfo['Order_TotalPrice']; ?>">马上付款</a>
			<?php endif; ?>
			<?php if($shaInfo['Order_Status'] != 2 && $shaInfo['Order_Status'] != 3): ?>
			<a href="javascript:void(0);" class="cancel" data-id="<?php echo $shaInfo['Order_ID']; ?>">取消申请</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="show_detail_link">
		<a href="/api/<?=$UsersID?>/distribute/my_sha_info/" class="">我的股东分红明细</a>

                <a style="float:right">当前身份:<?=!empty($DisRs['sha_level'])?$Sha_Rate['sha'][$DisRs['sha_level']]['name']:'不是股东';?></a>

	</div>

	<?php if (!empty($Sha_Rate)) : ?>
	<div class="row">
      <h4>申请成为股东需满足以下对应条件：</h4>
      <table width="100%" border="1" cellspacing="0"> 
	    <tbody>
	    <tr> 
	        <th class="bodytable">申请条件描述</th> 
	    </tr> 
	    
            <?php
                    foreach($Sha_Rate['sha'] as $k=>$v){
                    ?>
                    <tr> 
                        <td class="l">
                                <div class="tdContent">
                                        <div class="leftContent">申请<?=!empty($v['name'])?$v['name']:''?>条件</div>
                                        <div class="rightContent">
                                                <span class="m">分销商等级：<?php echo !empty($dis_level[$v['Level']]['Level_Name']) ? $dis_level[$v['Level']]['Level_Name'] : '无要求'; ?></span>				        					        	
                                                <span class="m">个人消费额：<span class="bold">¥<?php echo !empty($v['Selfpro']) ? $v['Selfpro'] : '0'; ?></span></span>		        	
                                                <span class="m">团队销售额：<span class="bold">¥<?php echo !empty($v['Teampro']) ? $v['Teampro'] : '0'; ?></span></span>        		
                                        </div>
                                </div>
                                <div class="tdContent">
                                        <div class="leftContent cleftContent">所需金额</div>
                                        <div class="rightContent crightContent"><span class="m bold">¥<?php echo !empty($v['price']) ? $v['price'] : '0'; ?></span></div>
                                </div>
                        </td> 
                    </tr> 
                    <?php    
                        }
                    ?>        

	  </tbody></table> 
    </div>
	<?php endif; ?>

</div>

<?php $rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");?>
<div class="pay_select_list" id="<?php echo $shaInfo['Order_ID']; ?>">
    <h2><span>[×]</span>请选择支付方式</h2>
	<h3>支付总额：<span></span></h3>
    <?php if(!empty($rsPay["PaymentWxpayEnabled"])){ ?>
	<a href="javascript:void(0)" class="btn btn-default btn-pay direct_pay" id="wzf" data-value="1"><img  src="/static/api/shop/skin/default/wechat_logo.jpg" width="16px" height="16px"/>&nbsp;微信支付</a>
	<?php }?>
	<?php if(!empty($rsPay["Payment_AlipayEnabled"])){?>
	<a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="zfb" data-value="2"><img  src="/static/api/shop/skin/default/alipay.png" width="16px" height="16px"/>&nbsp;支付宝支付</a>
	<?php }?>
	<a href="javascript:void(0)" class="btn btn-warning  btn-pay" id="money" data-value="余额支付"><img  src="/static/api/shop/skin/default/money.jpg"  width="16px" height="16px"/>&nbsp;余额支付</a>
    <p class="money_info">
		<b style="display:block; width:85%; height:40px; line-height:38px; font-size:14px; margin:0px auto; font-weight:normal">您当前余额：<span style="padding:0px 5px; font-size:16px; color:#F00">&yen;<?php echo $rsUser['User_Money'];?></span></b>
    	<input type="password" value="" placeholder="请输入支付密码，默认123456" style="display:block; width:85%; margin:5px auto 0px; border:1px #dfdfdf solid; border-radius:5px; height:40px; line-height:40px;">
        <button style="display:block; width:85%; margin:5px auto 0px; border:none; border-radius:5px; background:#4F93CE; color:#FFF; font-size:14px; text-align:center; height:40px; line-height:38px;">确认支付</button>
    </p>
</div>

<div class="products_list_tobuy">
	<h2><span>[×]</span>请选择商品</h2>
    <div class="products_list_content"></div>
</div>
<div class="body_bg"></div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>
