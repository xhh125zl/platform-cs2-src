<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

$html_mes = '';

if($rsAccount["Enable_Tixian"] == 1){
	header("location:".$shop_url."distribute/withdraw/");
}else{
	if($rsConfig["Withdraw_Type"]==0){
		$DB->Set("shop_distribute_account",array("Enable_Tixian"=>1),"where Users_ID='".$UsersID."' and Account_ID=".$rsAccount["Account_ID"]);
		header("location:".$shop_url."distribute/withdraw/");
	}elseif($rsConfig["Withdraw_Type"]==1){
		if($rsAccount['Total_Income'] >= $rsConfig["Withdraw_Limit"]){
			$DB->Set("shop_distribute_account",array("Enable_Tixian"=>1),"where Users_ID='".$UsersID."' and Account_ID=".$rsAccount["Account_ID"]);
			header("location:".$shop_url."distribute/withdraw/");
		}else{
			$html_mes = '当您所得佣金满<span>'.$rsConfig["Withdraw_Limit"].'</span>元时，即可拥有提现权利；您当前获得佣金<span>'.$rsAccount['Total_Income'].'</span>元';
		}
	}elseif($rsConfig["Withdraw_Type"]==2){
		$arr_temp = explode("|",$rsConfig["Withdraw_Limit"]);
		$arr_temp[1] = !empty($arr_temp[1]) ? $arr_temp[1] : 0;
		if($arr_temp[0]==0){
			$html_mes = '在本店购买<span>任意商品</span>即可拥有提现权利<a href="'.$shop_url.'">马上购买</a>';
		}else{
			$html_mes = '在本店购买以下任一商品即可拥有提现权利：<br />';
			$arr_temp[1] = $arr_temp[1] ? $arr_temp[1] : 0;
			$DB->Get("shop_products","Products_Name,Products_ID","where Products_ID in(".$arr_temp[1].")");
			while($r = $DB->fetch_assoc()){
				$html_mes .= '<a href="'.$shop_url.'products/'.$r["Products_ID"].'/" class="pro">'.$r["Products_Name"].'</a>';
			}
		}
	}
}

?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户提现</title>
<link href="/static/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="/static/css/font-awesome.css">
<link href="/static/api/distribute/css/style.css" rel="stylesheet">
<link href="/static/api/distribute/css/withdraw.css" rel="stylesheet">
</head>

<body>
<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/shop/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">申请提现</h1>
</header>
<div class="wrap">
	<div class="container">
		<div class="html_mes_red">您还未拥有提现权利</div>
		<div class="html_mes"><?php echo $html_mes;?></div>
	</div>
</div>
<?php require_once('../skin/distribute_footer.php');?>
</body>
</html>
