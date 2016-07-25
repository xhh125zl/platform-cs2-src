<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

if (isset($_GET["UsersID"])) {
    $UsersID = $_GET["UsersID"];
} else {
    echo '缺少必要的参数';
    exit();
}
if (isset($_GET["OrderID"])) {
    $OrderID = $_GET["OrderID"];
} else {
    echo '缺少必要的参数';
    exit();
}
if (isset($_GET["TeamsID"])) {
    $TeamsID = $_GET["TeamsID"];
} else {
    echo '缺少必要的参数';
    exit();
}

$userid = $_SESSION[$UsersID."User_ID"];
$base_url = base_url().'api/'.$UsersID.'/pintuan/';
$rsConfig = shop_config($UsersID);
$dis_config = dis_config($UsersID);
$rsConfig = array_merge($rsConfig,$dis_config);
//授权
$owner = get_owner($rsConfig,$UsersID);
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$owner = get_owner($rsConfig,$UsersID);

$sql = "select * from user_order u left join pintuan_order p on u.Order_ID=p.Order_ID where u.Users_ID='{$UsersID}' and  u.User_ID='{$userid}' and u.Order_ID='{$OrderID}'";

$result=$DB->query($sql);
if(!$result) die("已付款的订单不存在");
$orderInfo = $DB->fetch_assoc($result);
$goodslist=json_decode(htmlspecialchars_decode($orderInfo["Order_CartList"]),true);
if(!$goodslist) die("无效的订单");
?> 
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>申请退款 - 个人中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
</head>

<body>
<div id="shop_page_contents">
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/default/css/member.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
  
  <div id="order_detail">
    <div class="item">
        <div class="pro" >
            <div class="img"><a href="<?=$base_url ?>xiangqing/<?=$goodslist['Products_ID']?>/<?= $orderInfo['is_vgoods'] ?>/<?=$goodslist['Is_Draw']?>/"><img src="<?=$goodslist["ImgPath"]?>" height="100" width="100"></a></div>
            <dl class="info" style="padding-top:0px; padding-bottom:0px; margin-top:0px; margin-bottom:0px">
                <dd class="name" style="padding:0px; margin:0px"><a href="<?=$base_url ?>xiangqing/<?=$goodslist['Products_ID']?>/<?= $orderInfo['is_vgoods'] ?>/<?=$goodslist['Is_Draw']?>/"><?=$goodslist["ProductsName"]?></a></dd>
                <dd style="padding:0px; margin:0px">￥<?php echo 'dangou'===$orderInfo['Order_Type']?$goodslist['ProductsPriceD']:$goodslist['ProductsPriceT'];  ?>×1=￥<?php echo 'dangou'===$orderInfo['Order_Type']?$goodslist['ProductsPriceD']:$goodslist['ProductsPriceT'];  ?>*1</dd>	    
            </dl>
            <div class="clear"></div>
         </div>
    </div>
	<form name="apply_form" id="apply_form" />	
     <input type="hidden" name="action" value="tuikuan" />
     <input type="hidden" name="OrderID" value="<?=$OrderID;?>" />
     <input type="hidden" name="UsersID" value="<?=$UsersID;?>" />
     <input type="hidden" name="UserID" value="<?=$userid;?>" />
     <input type="hidden" name="Teams_ID" value="<?=$TeamsID;?>" />
     <input type="hidden" name="ProductsID" value="<?=$goodslist['Products_ID'];?>" />
     	<div class="backup_reason">
         <dl>
         	<dd>退款账号<br /><textarea name="Account" placeholder="请填写退款账号和户名" notnull></textarea></dd>
            <dd>退款原因<br /><textarea name="Reason" notnull></textarea></dd>
           <dt><input type="button" id="payfor" name="submit" class="submit" value="确定退款" /></dt>
         </dl>
        </div>
    </form>
  </div>
</div>
<script type="text/javascript">

$("#payfor").click(function(){
    var action=$('input[name="action"]').val(),
    	OrderID=$('input[name="OrderID"]').val(),
    	UsersID=$('input[name="UsersID"]').val(),
    	UserID=$('input[name="UserID"]').val(),
    	Account=$('textarea[name="Account"]').val(),
    	Reason=$('textarea[name="Reason"]').val(),
    	Teams_ID=$('input[name="Teams_ID"]').val(),
    	ProductsID=$('input[name="ProductsID"]').val();
        if(Account=="" || Account.length<5){
        	layer.msg("退款用户名不能为空！",{icon:1,time:2000});
        }
        if(Account=="" || Account.length<10){
        	layer.msg("退款原因写的太少！",{icon:1,time:2000});
        }
     $.ajax({
       url: "/api/<?=$UsersID ?>/pintuan/ajax/",
       data: {"action":action,"OrderID":OrderID,"UsersID":UsersID,
       "UserID":UserID,"Teams_ID":Teams_ID,'ProductsID':ProductsID,'Account':Account,'Reason':Reason},
       type: "POST",
       dataType: "json",
       success: function (data) {
           if(data.code=="1001"){
         	  layer.msg("退款提交成功！",{icon:1,time:3000},function(){
         		  window.location="<?=$base_url?>member/backup/status/0/";
               });
           }else if(data.code=="1002"){
             layer.msg("退款提交失败！",{icon:1,time:3000});
             return ;
           }
       }
   }) 

});
</script>
</body>
</html>



