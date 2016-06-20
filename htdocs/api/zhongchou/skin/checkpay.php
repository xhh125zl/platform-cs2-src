<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["name"] ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/zhongchou/js/zhongchou.js'></script>
<link href='/static/api/zhongchou/css/zhongchou.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script>
<script language="javascript">$(document).ready(zhongchou_obj.checkpay_init);</script>
</head>

<body>
<div class="header">
 确认信息
 <a href="javascript:history.go(-1);" id="goback"></a>
 <a href="/api/<?php echo $UsersID;?>/zhongchou/orders/" id="user"></a>
</div>
<div id="checkout">
  <form id="checkout_form">
  	 <div class="xmxx">
       <h1>项目信息</h1>
       <ul>
         <li>
           <p>项目名称：</p><?php echo $item["title"];?>
         </li>
       </ul>
     </div>
     <div class="address i-ture">
       <h1 class="t">支付金额</h1>
       <input type="text" name="money" value="" placeholder="请输入支持金额" notnull class="amount" />
     </div>
     <div class="checkout">
        <input type="submit" value="付款" />
     </div>
     <input type="hidden" name="itemid" value="<?php echo $itemid;?>" />
     <input type="hidden" name="type" value="checkpay" />
     <input type="hidden" id="action" name="action" value="/api/<?php echo $UsersID;?>/zhongchou/ajax/" />
   </form>
</div>
</body>
</html>