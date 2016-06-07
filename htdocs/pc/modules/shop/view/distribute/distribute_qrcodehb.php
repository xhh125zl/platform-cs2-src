<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1">
<title><?php echo $output['title']?></title>
<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/qrlogin.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/distribute_qrcodehb.js"></script>
<script>
	$(document).ready(function(){
		distribute_qrcodehb_obj.distribute_qrcodehb_init();
	});
</script>
</head>
<body class="icx-mobi-detail">
	<div class="scan-page"> 
		<div class="mask"></div>
	    <!--#=start column-->
	    <?php if($output['rsUser']['Is_Distribute'] && $output['rsAccount']['status']){?>
		<div class="scan-column">
			<div class="scan-box">
				<div class="scan-box-title"><?php echo $output['rsConfig']['shopname']?>微名片</div>
				<div class="scan-box-qrcode"> <div class="qrcode" id="qrcode"><img style="display: block; width:300px;height:300px;" src="<?php echo $output['qrcode_url'];?>" /></div> </div>
				<div class="info">
					<div class="loginTip">
						<div class="loginTipL"></div>
						<div class="loginTipR"></div>
						<p id="msgTxt">我是<?=$output['rsAccount']['Shop_Name']?></p><p>我为“<?=$output['rsConfig']['shopname']?>”代言</p>
					</div>
					<img class="guide" src="<?php echo $output['_site_url'];?>/static/pc/shop/images/login_guide.png">
				</div>
			</div>
			<!--#=start footer--> 
			<div class="footer">
				<a class="icon_faq" href="javascript:;"><img src="<?php echo $output['_site_url'];?>/static/pc/shop/images/spacer.gif"></a>
				<p class="webwx"><?php echo $_SERVER['HTTP_HOST'];?>微名片网页版</p>
			</div>
		</div>
		<?php }else{?>
		<div class="scan-column">
			<div class="scan-box">
				<div class="info">
					<div class="loginTip">
						<div class="loginTipL"></div>
						<div class="loginTipR"></div>
						<p id="msgTxt" style="color:#F60;">您的分销账号已被禁用</p>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
	</div>
</body>
</html>