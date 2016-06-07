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
<link href='/static/api/zhongchou/css/zhongchou.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
</head>

<body style="background:#f1f2f1">
<div class="header">
 我的众筹记录
 <a href="/api/<?php echo $UsersID;?>/zhongchou/" id="home"></a>
 <a href="/api/<?php echo $UsersID;?>/zhongchou/orders/" id="user"></a>
</div>
<div id="order_list">
<?php
foreach($lists as $k=>$v){
	$arr = explode("_",$v["Order_Type"]);
	$item = $DB->GetRs("zhongchou_project","title,itemid","where itemid=".$arr[1]);
	if($item){
		$v["title"] = '<a href="/api/'.$UsersID.'/detail/'.$item["itemid"].'/"><font style="color:#0DC05D">'.$item["title"].'</font></a>';
	}else{
		$v["title"] = '<font style="color:#666">发起人已删除</font>';
	}
?>
  <div class="order_item">
      <table width="100%" cellpadding="0" cellspacing="0">
       <tr>
        <td class="t1">支持金额</td>
        <td class="t2"><font style="font-family:'Times New Roman'; font-size:14px; font-weight:bold; color:#F60">￥<?php echo $v["Order_TotalPrice"];?></font></td>
       </tr>
       <tr>
        <td class="t1">项目</td>
        <td class="t3"><?php echo $v["title"];?></td>
       </tr>
       <tr>
        <td class="t1">回报</td>
        <td class="t2"><font style="color:#F00"><?php echo $v["Order_CartList"] ? $v["Order_CartList"] : "无私奉献";?></font></td>
       </tr>
       <?php if($v["Order_CartList"]){?>
       <tr>
        <td class="t1">收获人</td>
        <td class="t3"><?php echo $v["Address_Name"];?><br /><?php echo $v["Address_Mobile"];?></td>
       </tr>
       <tr>
        <td class="t1">收货地址</td>
        <td class="t3"><?php echo $v["Address_Province"].' '.$v["Address_City"].' '.$v["Address_Area"];?><br /><?php echo $v["Address_Detailed"];?></td>
       </tr>
       <tr>
        <td class="t1">备注</td>
        <td class="t3"><?php echo $v["Order_Remark"];?></td>
       </tr>
       <?php }?>
       <tr>
        <td class="t1">时间</td>
        <td class="t2"><?php echo date("Y-m-d H:i:s",$v["Order_CreateTime"]);?></td>
       </tr>
      </table>
  </div>
<?php }?>
  </div>
</div>
</body>
</html>