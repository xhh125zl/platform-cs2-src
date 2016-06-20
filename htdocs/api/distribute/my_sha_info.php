<?php
require_once('global.php');
$Sha_Rate = NULL;

if (isset($dis_config) && !empty($dis_config['Sha_Rate'])) 
{
	$Sha_Rate = json_decode($dis_config['Sha_Rate'], true);
}


//$shaInfo = $DB->Get('distribute_sha_rec', '*', ' where `Users_ID`="' .$UsersID. '" AND Account_ID='.$rsAccount['Account_ID']);
$shaInfo = $DB->Get('distribute_sha_rec', '*', ' where `Users_ID`="' .$UsersID. '" order by Order_CreateTime desc'); 
$shaList = array();
while ($row = $DB->fetch_assoc()) {
        $Accountid = explode(',', $row['Sha_Accountid']);
        if(in_array($rsAccount['Account_ID'],$Accountid)){
            $shaList[] = $row;
        }

}
$header_title = '我的股东分红明细';
require_once('header.php');
?>

<body>
<link href="/static/api/distribute/css/detaillist.css?t=<?php echo time();?>" rel="stylesheet">
<link href='/static/api/distribute/css/area_proxy.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/api/distribute/js/sha.js?t=<?php echo time();?>'></script>
<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">我的股东分红明细</h1>
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
	.dislist { width: 97%; margin: 10px auto; background: #FFF; border-radius: 5px; display: block; overflow: hidden; border: 1px solid #ddd;}
	.disContent { padding: 10px; }
	.disContent dd { display: block; overflow: hidden; height: 30px; line-height: 30px; font-size: 12px; } 
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
	<?php foreach ($shaList as $key => $s) :?>
	<div class="dislist ll_">
		<div class="disContent">
			<dd><b>订单编号：</b><?php echo date("Ymd",$s["Order_CreateTime"]).$s["Order_ID"] ?></dd>
			<dd><b>商品名称：</b><?php echo $s['Products_Name']; ?></dd>
			<dd><b>商品价格：</b><?php echo $s['Products_PriceX']; ?></dd>

<!--			<dd><b>股东佣金比例：</b><?php //echo $s['sha_Reward']."%"; ?></dd>-->

			<dd><b>购买数量：</b><?php echo $s['Products_Qty']; ?></dd>
			<dd><b>佣金：</b><font color="#ef363e">¥<?php echo number_format(($s['Record_Money']/$s['Sha_Qty']),2,'.',''); ?></font></dd>
		</div>

	</div>
	<?php endforeach; ?>

</div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>
