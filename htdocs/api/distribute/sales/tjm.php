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
	
	
	
//会员信息
$front_title = get_dis_pro_title($DB,$UsersID);

$fields = "User_ID,Shop_Name,Group_Sales,Up_Group_Sales,last_award_income,Total_Income,Qrcode,Salesman_Income";
$fields .= ",Professional_Title,Ex_Bonus,Total_Sales,Group_Num,Up_Group_Num,Invitation_Code";
$condition = "where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID'];
$rsDistirbuteAccount = $DB->getRs('distribute_account',$fields,$condition);
$Invitation_Code = $salesman->invitation_code($rsDistirbuteAccount['Invitation_Code']);
$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
?>


<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>我的推荐码</title>
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
  <h1 class="title">我的推荐码</h1>
  
</header>
<table width="100%" cellspacing="0" cellpadding="0" class="sales_my_tjm">
 <tr>
	<td class="td_l">邀请码</td>
	<td class="td_r">
		<?php echo $Invitation_Code;?>
	</td>
 </tr>
 <tr>
	<td class="td_l">邀请链接</td>
	<td class="td_r">
		<?php	
				
				echo $qrcode_data = 'http://'.$_SERVER['HTTP_HOST'].'/api/'.$UsersID.'/shop/sjrz/code/'.$Invitation_Code.'/';
			?>
	</td>
 </tr>
 <tr>
	<td class="td_l">我的二维码</td>
	<td class="td_r">
		<?php	
				if(empty($rsDistirbuteAccount['Qrcode'])){
					$qrcode_file = $salesman->get_qrcode($qrcode_data);
				}else{
					$qrcode_file = $rsDistirbuteAccount['Qrcode'];
				}
			?> 
			<img  style='margin:0px auto;' width="180" height="180" src="<?=$qrcode_file?>" />
	</td>
 </tr>
</table>

<?php require_once('../../shop/skin/distribute_footer.php');?> 
 
</body>
</html>

