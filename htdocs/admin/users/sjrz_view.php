<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
if(empty($_GET["itemid"])){
	echo "缺少必要参数";
	exit;
}else{
	$itemid=$_GET["itemid"]; 
}

$r=$DB->GetRs("comein","*","where itemid='".$itemid."'");
if($r["status"]==0){
	$DB->Set("comein",array("status"=>1),"where itemid=$itemid");
}
$industry = $DB->GetRs("industry","name,parentid","where id=".$r["industry"]);
$r["Industry"] = $industry["name"];
if($industry["parentid"] > 0){
	$industry = $DB->GetRs("industry","name","where id=".$industry["parentid"]);
	$r["Industry"] = $industry["name"].' -> '.$r["Industry"];
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>

<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
<style type="text/css">
.detail_card{border:1px solid #ddd; padding:15px;}
.detail_card .order_info{border-collapse:collapse;}
.detail_card .order_info *{font-size:12px;}
.detail_card .order_info td{padding:10px 7px; border-bottom:1px solid #ddd; empty-cells:show;}
.detail_card .order_info td input{height:28px; line-height:28px; border:1px solid #ddd; border-radius:5px;}
.detail_card .order_info td textarea{vertical-align:top; border-radius:5px; width:350px; height:80px; padding:5px; line-height:150%;}
.detail_card .order_info td select{height:32px; border:1px solid #ddd; padding:5px; vertical-align:middle; border-radius:5px;}
.detail_card .cp_item_mod{display:none;}
.detail_card .cp_item_mod td input{border:0; height:30px; line-height:30px;}
.item_info{height:20px; line-height:20px; font-weight:bold;}
.order_item_list{border:1px solid #ddd; margin:5px 0; border-collapse:collapse;}
.order_item_list td{empty-cells:show; font-size:12px;}
.order_item_list .tb_title td{border-right:1px solid #ddd; height:32px; font-weight:bold; text-align:center; background:#f1f1f1;}
.order_item_list .tb_title td.last{border-right:none;}
.order_item_list .item_list td{padding:7px 5px; border-top:1px solid #ddd; background:#fff;}
.order_item_list .item_list td img{width:100px;}
.order_item_list .item_list:hover td{background:#E4F1FC;}
.order_item_list .total td{height:26px; background:#efefef; text-align:center; color:#B50C08; font-weight:bold;}
</style>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
	  <ul>
        <li><a href="index.php">商家管理</a></li>
        <li><a href="add.php">添加商家</a></li>
		<li class="cur"><a href="sjrz.php">入驻申请</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
		<div class="detail_card">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order_info">
            <tr>
              <td width="8%" nowrap>申请ID：</td>
              <td width="92%"><?php echo $r["itemid"]; ?></td>
            </tr>
            <tr>
              <td width="8%" nowrap>商家名称：</td>
              <td width="92%"><?php echo $r["company"];?></td>
            </tr>
			<tr>
              <td width="8%" nowrap>所属行业：</td>
              <td width="92%"><?php echo $r["Industry"];?></td>
            </tr>
			<tr>
              <td nowrap>联系人：</td>
              <td width="92%" style="color:blue"><?php echo $r["contact"];?></td>
            </tr>
            <tr>
              <td nowrap>电话：</td>
              <td width="92%" style="color:blue"><?php echo $r["telephone"];?></td>
            </tr>
			<tr>
              <td nowrap>手机号码：</td>
              <td width="92%" style="color:blue"><?php echo $r["mobile"];?></td>
            </tr>
            <tr>
              <td nowrap>电子邮箱：</td>
              <td width="92%" style="color:blue"><?php echo $r["email"];?></td>
            </tr>
            <tr>
              <td nowrap>申请时间：</td>
              <td><?php echo date("Y-m-d H:i:s",$r["addtime"]) ?></td>
            </tr>            
          </table>        
      </div>
    </div>
  </div>
</div>
</body>
</html>