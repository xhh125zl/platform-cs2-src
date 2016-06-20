<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["name"];?></title>
<link href='/static/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/zhongchou/css/zhongchou.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/zhongchou/js/zhongchou.js?t=<?php echo time();?>'></script>
<script type="text/javascript">
$(document).ready(zhongchou_obj.detail_init);
</script>
</head>

<body>
<div class="header">
 项目详情
 <a href="javascript:history.go(-1);" id="goback"></a>
 <a href="/api/<?php echo $UsersID;?>/zhongchou/orders/" id="user"></a>
</div>

<div class="main">
 <div id="description">
 	<h2><?php echo $item["title"];?></h2>
    <div class="description">
     <?php echo $item["description"];?> 
    </div>
 </div>
</div>
<div class="pay_items">
  <p>[ ╳ ]</p>
  <a href="/api/<?php echo $UsersID;?>/zhongchou/checkpay/<?php echo $itemid;?>/">无私奉献</a>
  <?php
	foreach($lists as $k=>$v){
		$prize_flag=0;
		if($v["maxtimes"]>0){
			$num = $DB->GetRs("user_order","count(*) as num","where Order_Type='zhongchou_".$itemid."' and Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_TotalPrice=".$v["money"]);
			if($num["num"]>=$v["maxtimes"]){
				$prize_flag=1;
			}
		}
		if($prize_flag==0){
  ?>
  <a href="/api/<?php echo $UsersID;?>/zhongchou/check/<?php echo $itemid;?>/<?php echo $v["prizeid"];?>/">￥<?php echo $v["money"];?></a>
  <?php }}?>
</div>
<div class="detail_footer_bg"></div>
<div class="detail_footer">
 <?php if($item["fromtime"]>time()){?>
 <p class="footer_btns_1">未开始</p> 
 <?php }elseif($item["totime"]<time()){?>
 <p class="footer_btns_1">已结束</p>
 <?php }else{?>
 <p class="footer_btns">支持众筹</p>
 <?php }?>
 <p class="footer_other">
    <span>筹集<font style="font-family:'Times New Roman'; font-size:16px; color:#F60"> ￥<?php echo str_replace('.00','',$item["complete"]);?></font></span>
    <span>&nbsp;目标<font style="font-family:'Times New Roman'; font-size:16px;"> ￥<?php echo str_replace('.00','',$item["amount"]);?></font></span>
 </p>
</div>
<?php if($share_flag==1 && $signature<>""){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_user["Users_WechatAppId"];?>",		   
		   timestamp:<?php echo $timestamp;?>,
		   nonceStr:"<?php echo $noncestr?>",
		   url:"<?php echo $url?>",
		   signature:"<?php echo $signature;?>",
		   title:'<?php echo $item["title"];?>',
		   desc:'<?php echo str_replace(array("\r\n", "\r", "\n"), "", $item["introduce"]);?>',
		   img_url:'http://<?php echo $_SERVER["HTTP_HOST"].$item["thumb"];?>',
		   link:''
		};
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
<?php ad($UsersID,2,2);//第一个数字参数代表广告位置：1顶部2底部；第二个数字参数代表广告位的编号，从后台查看?>
</body>
</html>