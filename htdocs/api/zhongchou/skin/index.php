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
</head>

<body>
<div class="header">
 <?php echo $rsConfig["name"];?>首页
 <a href="/api/<?php echo $UsersID;?>/zhongchou/orders/" id="user"></a>
</div>

<div class="main">
  <?php
    foreach($lists as $k=>$v){
        $item = $DB->GetRs("user_order","count(*) as num,sum(Order_TotalPrice) as amount","where Order_Type='zhongchou_".$v["itemid"]."' and Order_Status=2 and Users_ID='".$UsersID."'");
		$v["people"] = empty($item["num"]) ? 0 : $item["num"];
		$v["complete"] = empty($item["amount"]) ? 0 : $item["amount"];
  ?>
  <div class="item">
  	<div class="flag">
    <?php if($v["fromtime"]>time()){?>
    未开始
    <?php }elseif($v["totime"]<time()){?>
    已过期
    <?php }else{?>
    众筹中
    <?php }?>
    </div>
   <div class="time">活动时间：<?php echo date("Y.m.d",$v["fromtime"]);?> - <?php echo date("Y.m.d",$v["totime"]);?></div>
   <div class="title"><a href="/api/<?php echo $UsersID;?>/zhongchou/detail/<?php echo $v["itemid"];?>/"><?php echo $v["title"];?></a></div>
   <div class="info">
    <a href="/api/<?php echo $UsersID;?>/zhongchou/detail/<?php echo $v["itemid"];?>/"><img src="<?php echo $v["thumb"];?>" ></a>
     <?php echo $v["introduce"];?>
   </div>
   <div class="jindu">
    <p>目标<font style="font-family:'Times New Roman'; font-size:14px;">￥<?php echo $v["amount"];?></font></p>
    <p>筹集<font style="font-family:'Times New Roman'; font-size:14px; color:#F60">￥<?php echo $v["complete"];?></font></p>
    <p class="nobg">支持<font style="font-family:'Times New Roman'; font-size:14px; color:#0dc05d"><?php echo $v["people"];?></font>人</p>
    <div class="clear"></div>    
   </div>
  </div>
  <?php }?>
</div>
<?php if($share_flag==1 && $signature<>""){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_user["Users_WechatAppId"];?>",		   
		   timestamp:<?php echo $timestamp;?>,
		   nonceStr:"<?php echo $noncestr?>",
		   url:"<?php echo $url?>",
		   signature:"<?php echo $signature;?>",
		   title:'<?php echo $rsConfig["name"];?>',
		   desc:'投资你关注的项目，有机会获得丰厚的回报',
		   img_url:'http://<?php echo $_SERVER["HTTP_HOST"];?>/static/api/images/cover_img/zhongchou.jpg',
		   link:''
		};
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
<?php ad($UsersID,2,2);//第一个数字参数代表广告位置：1顶部2底部；第二个数字参数代表广告位的编号，从后台查看?>
</body>
</html>