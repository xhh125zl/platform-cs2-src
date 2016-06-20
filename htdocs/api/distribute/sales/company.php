<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/api/shop/global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/compser_library/Salesman.php');

require_once($_SERVER["DOCUMENT_ROOT"].'/include/models/Biz.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/models/Distribute_Sales_Record.php');
//require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/biz.class.php');

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

if(empty($_SESSION[$UsersID."User_ID"]))
{
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
	
	
	
//会员信息
$front_title = get_dis_pro_title($DB,$UsersID);

$fields = "User_ID,Shop_Name,Group_Sales,Up_Group_Sales,last_award_income,Total_Income,Qrcode,Salesman_Income";
$fields .= ",Professional_Title,Ex_Bonus,Total_Sales,Group_Num,Up_Group_Num,Invitation_Code";
$condition = "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID'];
$rsDistirbuteAccount = $DB->getRs('distribute_account',$fields,$condition);

$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");

//业务数据
$Invitation_Code = $salesman->invitation_code($rsDistirbuteAccount['Invitation_Code']);
$paginator = Biz::where('Invitation_Code',$Invitation_Code)->paginate(10);
$paginator->setPath(base_url('api/shop/distribute/sales/company.php'));
$paginator->appends(['UsersID'=>$UsersID]);
$page_links = $paginator->render();



//明细
$salesman_record = Distribute_Sales_Record::join('biz','biz.Biz_ID','=','distribute_sales_record.Biz_ID')->join('shop_products','shop_products.Products_ID','=','distribute_sales_record.Products_ID')->where('User_ID',$_SESSION[$UsersID."User_ID"])->take(5)->get()->toArray();
?>


<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>我的商家</title>
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
  <h1 class="title">我的商家</h1>
  
</header>
<div class="sales_company">
<?php
$seller_list = $paginator->toArray();
$my_sellers = $seller_list['data']; 
?>
<?php foreach($my_sellers as $key=>$seller){?>
	<div class="items">
		<div class="img"><img src="<?php echo $seller["Biz_Logo"]?>" /></div>
		<p class="biz_title"><?php echo $seller['Biz_Name']?></p>
		
		<p><?php echo '入驻时间：'.date('Y-m-d',$seller['Biz_CreateTime'])?></p>
	</div>
<?php }?>
	<div class="sales_page"><?=$page_links?></div>
</div>
 
<?php require_once '../../shop/skin/distribute_footer.php';?>
 
</body>
</html>

