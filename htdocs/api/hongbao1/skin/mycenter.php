<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的红包 - <?php echo $rsConfig["name"];?></title>
<link href='/static/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/hongbao/css/hongbao.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
</head>

<body>
<div class="mycenter_top"><img src="/static/api/hongbao/images/mycenter.jpg" /></div>
<div class="mycenter_middle">
    <div class="myinfo">
     <img src="<?php echo $myinfo["User_HeadImg"];?>" />
     <span><?php echo $myinfo["User_NickName"];?></span>
    </div>
    <div class="mymoney">
     <div class="mymoney_div">
	   <div class="f1">
		<p class="total_title">已拆启</p>
		<p class="total">￥<?php echo $money_1;?></p>
	  </div>
	 </div>
	 <div class="add_flag">+</div>
	 <div class="mymoney_div">
	   <div class="f0">
		<p class="total_title">未拆启</p>
		<p class="total">￥<?php echo $money_0;?></p>
	  </div>
	 </div>
	 <div class="add_flag">=</div>
	 <div class="mymoney_div">
	   <div class="f2">
		<p class="total_title">总额</p>
		<p class="total">￥<?php echo $money;?></p>
	  </div>
	 </div>
	 <div class="clear"></div>
    </div>
</div>
<div class="b10"></div>
<div class="mylist">
 <table cellpadding="0" cellspacing="0" class="list_table">
 <tr>
  <th width="20%">金额</th>
  <th>时间</td>
  <th width="20%">操作</th>
 </tr>
 <?php
  $i=0;
  $DB->Get("hongbao_act","*","where usersid='".$UsersID."' and userid=".$_SESSION[$UsersID."User_ID"]." and money>0");
  while($r=$DB->fetch_assoc()){
	  $i++;
 ?>	
 <tr>
  <td>￥<?php echo $r["money"];?></td>
  <td><?php echo date("Y-m-d H:i:s",$r["addtime"]);?></td>
  <td>
   <?php if($r["status"]==0){?>
   <a href="/api/<?php echo $UsersID."_".$r["actid"];?>/hongbao/detail/"><font style="color:blue">拆红包</font></a>
   <?php }else{?>
   <font style="color:#F00">已拆开</font>
   <?php }?>
  </td>
 </tr>  
<?php }?>
 </table>
</div>
<div class="notice">
 已拆开的红包金额会自动转入你的微信零钱<br />
 打开微信 -> <font style="color:#F00">"微信" -> "我" -> "钱包" -> "钱包" -> "零钱"</font>
</div>
<a href="/api/<?php echo $UsersID;?>/hongbao/" class="goback">返回</a>
</body>
</html>