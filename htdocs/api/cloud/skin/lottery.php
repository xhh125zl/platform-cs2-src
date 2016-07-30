<?php require_once('top.php'); ?>
<body class="g-acc-bg">
<link href="/static/api/cloud/css/lottery.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<div>
	<!--期数信息-->
	<?php if(!empty($cloud_products_detail_list)){?>
	<div class="issue">
		<ul>
		<?php foreach($cloud_products_detail_list as $key => $val){?>
		<li><a href="<?php echo $cloud_url.'lottery/'.$val['Cloud_Detail_ID'].'/';?>" class="<?php if($val['Cloud_Detail_ID'] == $DetailID){?>hover<?php }?>"><s class="fl"></s>第<?php echo $val['qishu'];?>云<i class="fr"></i></a></li>
		<?php }?>
			<li class="z-more"><a href="<?php echo $cloud_url.'Morelottery/'.$val['Products_ID'].'/';?>"><s class="fl"></s><em class="z-set"></em></a></li>
		</ul>
	</div>
	<?php }?>
	<!--获得者信息-->
	<div class="bgColor-white clearfix">
		<div class="g-winn-con clearfix">
			<div class="winn-info clearfix">
				<p class="fl"> <img src="<?php echo $rsUser['User_HeadImg'];?>"> </p>
				<dl class="gray9">
					<dd> 来自：
						<?php echo $User_Info[1];?> </dd>
					<dd> 本云参与： <i class="orange"> <?php echo $User_Info[2];?></i>人次 </dd>
					<dd> 幸运云购码： <i class="orange"> <?php echo $rsDetail['Luck_Sn'];?></i> </dd>
					<dd class="ann-time"> 揭晓时间：
					    <?php 
						if(strpos($rsDetail['Products_End_Time'], '.')){
							list($usec, $sec) = explode('.', $rsDetail['Products_End_Time']);
							$date = date('Y-m-d H:i:s', $usec);
							//exit($rsDetail['Products_End_Time']);
						}else{
							$date = date('Y-m-d H:i:s', $rsDetail['Products_End_Time']);
							$sec = 0;
						}
						?>
						<?php echo $date.'.'.$sec;?> </dd>
				</dl>
				<div class="rNowTitle">获得者</div>
			</div>
			<cite><a href="<?php echo $cloud_url.'lottery_detail/'.$DetailID.'/';?>" class="gray9">获得者本云所有云购码<s class="z-set"></s></a></cite> </div>
	</div>
	<!--商品信息-->
	<div class="announced-detail clearfix">
		<ul>
			<li class="fl ann-pic"> <a href="<?php echo $cloud_url.'products/'.$rsProducts['Products_ID'].'/';?>" style="display:block;height:100%;overflow:hidden;"><img src="<?php echo $ImgPath;?>"></a> </li>
			<li class="ann-con gray9">
				<p class="gray6"><?php echo $rsProducts['Products_Name'];?></p>
				价值：￥<?php echo $rsProducts['Products_PriceY'];?> </li>
		</ul>
	</div>
	<div class="ann_btn clearfix"> 
		<!--计算详情--> 
		<a href="<?php echo $cloud_url.'lottery_result/'.$DetailID.'/';?>">计算详情<s class="fr"></s></a> 
		<!--所有参与记录--> 
		<a href="<?php echo $cloud_url.'buyrecords/'.$DetailID.'/';?>">参与记录<s class="fr"></s></a> 
		<!--商品晒单--> 
		<a style="display:none;" href="<?php echo $cloud_url.'commit/'.$rsProducts['Products_ID'].'/';?>">商品晒单<s class="fr"></s></a> </div>
	<div class="pro_foot clearfix"> <a href="javascript:;" id="a_sc" class="z-set z-foot-fans fl"></a> <a href="http://weixin.1yyg.com/mycart/index.do" id="btnCart"><i class="fr"></i></a>
		<div class="btn">
			<ul>
				<li class="conductBtn"> <a href="<?php echo $cloud_url.'products/'.$rsProducts['Products_ID'].'/';?>">第<?php echo $rsProducts['qishu'];?>云正在进行中…</a> </li>
			</ul>
		</div>
	</div>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>