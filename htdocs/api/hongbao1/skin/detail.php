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
<link href='/static/api/hongbao/css/hongbao.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/hongbao/js/hongbao.js?t=<?php echo time();?>'></script>

</head>

<body>
<div class="main">
 <img src="/static/api/hongbao/images/top_bg.jpg" class="main_bg" />
 <div class="act_name"><?php echo $rsConfig["name"];?></div>
 <div class="chai_div"><a href="/api/hongbao/chai.php?UsersID=<?php echo $UsersID."_".$actid;?>"><img src="/static/api/hongbao/images/btn_chai.png" /></a></div>
 <div class="syhy"><p>需 <?php echo $diff;?> 位好友拆开后到微信钱包</p></div>
 <div class="myhong_div"><a href="/api/<?php echo $UsersID;?>/hongbao/mycenter/"><img src="/static/api/hongbao/images/btn_myhong.png" border="0" /></a></div>
 <?php if($chai==0){?>
 <div class="invite">
  <p><a href="#" onClick="$('.share_layer').show();">邀请好友</a>&nbsp;&nbsp;</p>
 </div>
 <?php }?>
</div>
<div class="main"><img src="/static/api/hongbao/images/middle.jpg" class="main_bg" /></div>
<table cellpadding="0" cellspacing="0" class="ranklist">
<?php
$i=0;
foreach($rank as $t){
	if($t["money"]>0){
?>
  <tr>
   <td width="20%"><img src="<?php echo $t["User_HeadImg"];?>" width="100%" /></td>
   <td style="padding-left:8px;"><?php echo $t["User_NickName"];?></td> 
   <td width="20%">￥<?php echo $t["money"];?></td> 
  </tr>
<?php }}?>
</table>
<div class='share_layer'><img src='/static/api/hongbao/images/share.png' /></div>
<script language="javascript">
var UsersID = '<?php echo $UsersID;?>';
var actid = '<?php echo $actid;?>';
var chai = <?php echo $chai;?>;
$(document).ready(hongbao_obj.detail_init);
</script>
<?php if(!empty($share_config)){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_config["appId"];?>",   
		   timestamp:<?php echo $share_config["timestamp"];?>,
		   nonceStr:"<?php echo $share_config["noncestr"];?>",
		   url:"<?php echo $share_config["url"];?>",
		   signature:"<?php echo $share_config["signature"];?>",
		   title:"<?php echo $share_config["title"];?>",
		   desc:"<?php echo str_replace(array("\r\n", "\r", "\n"), "", $share_config["desc"]);?>",
		   img_url:"<?php echo $share_config["img"];?>",
		   link:""
		};
		
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
<?php ad($UsersID,2,7);?>
</body>
</html>