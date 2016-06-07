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
<script language="javascript">$(document).ready(zhongchou_obj.checkout_init);</script>
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
         <li>
           <p>支持金额：</p><font style="font-family:'Times New Roman'; color:#F60; font-size:16px; font-weight:blod">￥<?php echo $prize["money"];?></font>
         </li>
         <li>
           <p>应得回报：</p><?php echo $prize["title"];?>
         </li>
       </ul>
     </div>
     <div class="address i-ture">
       <h1 class="t">联系人信息</h1>
       <ul>
<?php $DB->get("user_address","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'");
$i=0;
while($rsAddress=$DB->fetch_assoc()){
	echo '<li><label><input type="radio" name="AddressID" value="'.$rsAddress["Address_ID"].'"'.($i==0?" checked":"").' /> '.$rsAddress["Address_Province"].$rsAddress["Address_City"].$rsAddress["Address_Area"].$rsAddress["Address_Detailed"].'【'.$rsAddress["Address_Name"].'，'.$rsAddress["Address_Mobile"].'】</label></li>';
	$i++;
}?>
         <li>
           <label>
           <input type="radio" name="AddressID" value="0"<?php echo $i==0?" checked":"" ?> />
              使用新的联系人信息</label>
         </li>
       </ul>
       <dl>
         <dd> 姓名 <font class="fc_red">*</font><br />
            <input type="text" name="Name" value="<?php echo !empty($_SESSION[$UsersID."User_Name"]) ? $_SESSION[$UsersID."User_Name"] : '';?>" notnull />
         </dd>
         <dd> 手机 <font class="fc_red">*</font><br />
            <input type="text" name="Mobile" value="<?php echo !empty($_SESSION[$UsersID."User_Mobile"]) ? $_SESSION[$UsersID."User_Mobile"] : ''; ?>" pattern="[0-9]*" notnull />
         </dd>
         <dd> 所在地区 <font class="fc_red">*</font><br />
            
	<select name="Province" notnull></select><select name="City" notnull></select><select name="Area"></select>
	<script language="javascript">new PCAS("Province","City","Area");</script>
         </dd>
         <dd> 详细地址 <font class="fc_red">*</font><br />
            <input type="text" name="Detailed" value="" notnull />
         </dd>
       </dl>
     </div>
     <div class="remark i-ture">
       <h1 class="t">备注</h1>
       <div>
          <textarea name="Remark" placeholder="选填，填写您的特殊需求，如送货时间等"></textarea>
       </div>
     </div>
     <div class="checkout">
        <input type="submit" value="付款" />
     </div>
     <input type="hidden" name="money" value="<?php echo $prize["money"];?>" />
     <input type="hidden" name="itemid" value="<?php echo $itemid;?>" />
     <input type="hidden" name="prizeid" value="<?php echo $prizeid;?>" />
     <input type="hidden" name="type" value="check" />
     <input type="hidden" id="action" name="action" value="/api/<?php echo $UsersID;?>/zhongchou/ajax/" />
   </form>
</div>
</body>
</html>