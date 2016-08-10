<?php require_once('top.php'); ?>
<body class="g-acc-bg">
<link href="/static/api/cloud/css/lottery.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<div>
	<div class="g-statistics gray9" style="" id="div_title">幸运得主本云总共参与<em class="orange"><?php echo count($rsDetail_sns);?></em>人次</div>
	<div class="buy-codes-con clearfix">
		<ul id="ul_list">
			<li class="gray6">
				<p class="colorbbb">
				<?php 
					if(strpos($rsDetail['Products_End_Time'], '.')){
						list($usec, $sec) = explode('.', $rsDetail['Products_End_Time']);
						$date = date('Y-m-d H:i:s', $usec);
					}else{
						$date = date('Y-m-d H:i:s', $rsDetail['Products_End_Time']);
						$sec = 0;
					}
				?>
				<?php echo $date.'.'.$sec;?> 
				</p>
				<?php if(!empty($rsDetail_sns)){?>
				<?php foreach($rsDetail_sns as $v){?>
				<span><?php echo $v;?></span>
				<?php }?>
				<?php }?>
			</li>
		</ul>
	</div>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>