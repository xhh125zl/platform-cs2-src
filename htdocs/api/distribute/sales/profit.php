<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/api/shop/global.php');        
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/compser_library/Salesman.php');

$base_url = base_url();
$shop_url = shop_url();

/*分享页面初始化配置*/
$share_flag = 1;
$signature = '';

if(isset($_GET["UsersID"])){
  $UsersID = $_GET["UsersID"];
}else{
  echo '缺少必要的参数';
  exit;
}

if(empty($_SESSION[$UsersID."User_ID"])){
  	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/shop/distribute/";
  	header("location:/api/".$UsersID."/user/login/");
}

$salesman = new Salesman($UsersID, $_SESSION[$UsersID."User_ID"]);
$limit = $salesman->up_salesman(1);
$is_salesman = $salesman->get_salesman();
if(!$is_salesman){
	header("location:".$base_url."api/".$UsersID."/shop/distribute/sales/join/");
	exit;
}

$page_url = $base_url."api/distribute/sales/profit.php?UsersID=".$UsersID;

if(isset($_GET["action"])){
	$action = $_GET["action"];
	$page_url .= "&action=".$action;
}else{
	$action = "all";
	
}

$page_url .= "&page=";	
	
//会员信息
$front_title = get_dis_pro_title($DB,$UsersID);

$fields = "User_ID,Shop_Name,Group_Sales,Up_Group_Sales,last_award_income,Total_Income,Qrcode,Salesman_Income";
$fields .= ",Professional_Title,Ex_Bonus,Total_Sales,Group_Num,Up_Group_Num,Invitation_Code";
$condition = "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID'];
$rsDistirbuteAccount = $DB->getRs('distribute_account',$fields,$condition);

$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");

$_STATUS = array('<font style="color:#999">未付款</font>','<font style="color:#F60">已付款</font>','<font style="color:blue">已完成</font>');
//明细

$condition = "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"];
$bizids = array();
switch($action){
	case 'self'://直接商家
		$DB->get("biz","Biz_ID","where Users_ID='".$UsersID."' and Invitation_Code='".$rsDistirbuteAccount["Invitation_Code"]."'");
		while($r = $DB->fetch_assoc()){
			$bizids[] = $r["Biz_ID"];
		}
		
		if(count($bizids)>0){
			$condition .= " and Biz_ID in(".(implode(",",$bizids)).")";
		}else{
			$condition .= " and Biz_ID in(0)";
		}
	break;
	case 'down'://下属的商家
		$childs = array();
		$self_str = ','.$_SESSION[$UsersID."User_ID"].',';
		$DB->get("distribute_account","Invitation_Code","where Users_ID='".$UsersID."' and Dis_Path like '%".$self_str."%'");
		while($r = $DB->fetch_assoc()){
			$childs[] = $r["Invitation_Code"];
		}
		
		if(count($childs)>0){
			$code = implode("','",$childs);
			$DB->get("biz","Biz_ID","where Users_ID='".$UsersID."' and Invitation_Code in('".$code."')");
			while($r = $DB->fetch_assoc()){
				$bizids[] = $r["Biz_ID"];
			}
		}
		
		if(count($bizids)>0){
			$condition .= " and Biz_ID in(".(implode(",",$bizids)).")";
		}else{
			$condition .= " and Biz_ID in(0)";
		}
	break;
}

$record_list = array();
$rsRecords = $DB->getPage("distribute_sales_record","*",$condition,10);
$record_list = $DB->toArray($rsRecords);

$result_money = $DB->GetRs("distribute_sales_record","sum(Sales_Money) as money","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
$my_money = empty($result_money["money"]) ? 0 : $result_money["money"];
?>


<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>我的利润</title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/sales/style.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>
    <script src="/static/api/js/ZeroClipboard.min.js"></script>
    <script type='text/javascript' src='/static/api/js/global.js'></script>
    <script src="/static/api/distribute/js/distribute.js"></script>
     <script language="javascript">
	 
	var base_url = '<?=$base_url?>';
	var UsersID = '<?=$UsersID?>';
	$(document).ready(distribute_obj.pro_file_init);
</script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body>

<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/shop/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">我的利润</h1>
  
</header>
<div class="dline"></div>
<a href="javascript:void(0)" class="my_money"><img src="/static/api/distribute/images/coin_stack.png"/>&nbsp;&nbsp;我的利润&nbsp;&nbsp;<span class="pink font17">(<?php echo $my_money;?>)</span></a>
<div class="dline"></div>
<ul class="select_item">
 <li class="first"><a class="<?php echo $action=='all' ? ' cur' : '';?>" href="<?php echo $base_url;?>api/<?php echo $UsersID;?>/distribute/sales/profit/">全部</a></li>
 <li><a class="<?php echo $action=='self' ? ' cur' : '';?>" href="<?php echo $base_url;?>api/<?php echo $UsersID;?>/distribute/sales/profit/self/">直接商家</a></li>
 <li><a class="<?php echo $action=='down' ? ' cur' : '';?>" href="<?php echo $base_url;?>api/<?php echo $UsersID;?>/distribute/sales/profit/down/">下属商家</a></li>
</ul>
<table width="100%" cellspacing="0" cellpadding="0" class="sales_record">

<?php
foreach($record_list as $key=>$record){
	$products = $DB->GetRs("shop_products","Products_JSON,Products_Name","where Products_ID=".$record["Products_ID"]);
	$JSON = json_decode($products['Products_JSON'],TRUE);
	if(isset($JSON["ImgPath"])){
		$record['Products_Img'] = $JSON["ImgPath"][0];
	}else{
		$record['Products_Img'] =  'static/api/shop/skin/default/nopic.jpg';
	}
	$record['Products_Name'] = $products["Products_Name"];
	
	$bizinfo = $DB->GetRs("biz","Biz_Name","where Biz_ID=".$record["Biz_ID"]);
	$record["Biz_Name"] = $bizinfo["Biz_Name"];
?>
  <tr>
	<td width="86" align="center"><img src="<?php echo $record['Products_Img'];?>" /></td>
	<td>
		<p class="pro_name"><?php echo $record['Products_Name'];?></p>
		<p><?php echo $record['Biz_Name'];?></p>
	</td>
	<td width="80"><span class="record_price">￥<?php echo $record['Sales_Money']?></span><span><?php echo $_STATUS[$record['Status']];?></span></td>
 </tr>
<?php }?>
</table>
<div style="margin:8px 0px">
<?php $DB->showWechatPage($page_url); ?>
</div>

<?php require_once '../../shop/skin/distribute_footer.php';?> 
</body>
</html>

