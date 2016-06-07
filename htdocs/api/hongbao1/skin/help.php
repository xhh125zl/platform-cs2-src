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
<script language="javascript">
var UsersID = '<?php echo $UsersID;?>';
var actid = '<?php echo $actid;?>';
$(document).ready(hongbao_obj.help_init);
</script>
</head>

<body>
<div class="main">
 <img src="/static/api/hongbao/images/top_bg.jpg" class="main_bg" />
 <div class="act_name"><?php echo $rsConfig["name"];?></div>
 <div class="help_div">
  <?php if($chai_pass==1){?>
  <img src="/static/api/hongbao/images/btn_chaipass.png" />
  <?php }else{if($myrecord==1){?>
   <img src="/static/api/hongbao/images/btn_helppass.png" />
  <?php }else{?>
   <img src="/static/api/hongbao/images/btn_help.png" id="help" />
  <?php }}?>
 </div>
 <div class="myhong_div"><a href="/api/<?php echo $UsersID;?>/hongbao/"><img src="/static/api/hongbao/images/btn_qiangto.png" border="0" /></a></div>
 <div class="main_other">
  <p><a href="/api/<?php echo $UsersID;?>/hongbao/rules/">活动规则&gt;&gt;</a>&nbsp;&nbsp;</p>
  <p>已有<span><?php echo $people;?></span>人抢红包&nbsp;</p>
 </div>
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
<?php ad($UsersID,2,8);?>
</body>
</html>