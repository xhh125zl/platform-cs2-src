<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

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

if(!empty($_SESSION[$UsersID."User_ID"])){
  $userexit = $DB->GetRs("user","*","where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."'");
  if(!$userexit){
    $_SESSION[$UsersID."User_ID"] = "";
  } 
}

if(empty($_SESSION[$UsersID."User_ID"]))
{
  	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/shop/distribute/";
  	header("location:/api/".$UsersID."/user/login/");
}

if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
  	header("location:?wxref=mp.weixin.qq.com");
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
$rsDistirbuteAccount = $DB->getRs('shop_distribute_account',$fields,$condition);

$rsConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");

//业务数据
	$Invitation_Code = $salesman->invitation_code($rsDistirbuteAccount['Invitation_Code']);
	$my_sellers = Biz::where('Invitation_Code',$Invitation_Code)->take(5)->get()->toArray();;
	//明细
	$salesman_record = Shop_Distribute_Sales_Record::join('shop_biz','shop_biz.Biz_ID','=','shop_distribute_sales_record.Biz_ID')->join('shop_products','shop_products.Products_ID','=','shop_distribute_sales_record.Products_ID')->where('User_ID',$_SESSION[$UsersID."User_ID"])->take(5)->get()->toArray();
?>


<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>业务管理</title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/css/protitle.css" rel="stylesheet">
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
  <h1 class="title">业务详情</h1>
  
</header>

<div class="wrap">
 <div class="container">
  	<div class="row">
        <div class="panel panel-default">
  <!-- Default panel contents -->
 
  <div class="panel-body">
    <p>
		<div style='font-size:18px;'>我的邀请码</div>
		<input style='width:80%;' id='copy_1' value='<?php echo $Invitation_Code;  
			?>' />
		<!--<button style='border:0;background-color:#FF5764;color:#FFF;padding:2px 10px;border-radius:3px;' onclick='copyUrl2("copy_1")' >复制</button>-->
	</p>
	<p style='width:100%;border-top:10px solid #DDD;'>
		<div style='font-size:18px;'>我的邀请链接</div>
		<input style='width:80%;' id='copy_2' value='<?php	
				
				echo $qrcode_data = 'http://'.$_SERVER['HTTP_HOST'].'/api/'.$UsersID.'/shop/sjrz/code/'.$Invitation_Code.'/';
			?> '/>
		<!--<button style='border:0;background-color:#FF5764;color:#FFF;padding:2px 10px;border-radius:3px;' onclick='copyUrl2("copy_2")'  >复制</button>-->
		<script>
			function copyUrl2(str)
			{
				var Url2=document.getElementById(str);
				if (window.clipboardData){
					window.clipboardData.setData("Text", Url2.value);
					alert("已复制好，可贴粘。");
					}else{
						alert("未复制");
					}
			}
		</script>
	</p>
	<p style='width:100%;border-top:10px solid #DDD;'>
		<div style='font-size:18px;'>我的二维码</div>
		<div style='position:relative;left:25%;'>
			<?php	
				if(empty($rsDistirbuteAccount['Qrcode'])){
					$qrcode_file = $salesman->get_qrcode($qrcode_data);
				}else{
					$qrcode_file = $rsDistirbuteAccount['Qrcode'];
				}
			?> 
			<img  style='margin:0px auto;' width="180" height="180" src="<?=$qrcode_file?>" />
		</div>
	</p>
    <div style='margin:10px 0;border-top:10px solid #DDD;text-indent:20px;'>
		<p style='font-size:18px;'>我的提成：</p>
		<p style='margin-top:10px;'>待付款提成:&nbsp;&nbsp;&yen;<span class="red"><?=$salesman->sales_statistics($_SESSION[$UsersID."User_ID"],0);?></span></p>
		<p style='margin-top:10px;'>已付款提成:&nbsp;&nbsp;&yen;<span class="red"><?=$salesman->sales_statistics($_SESSION[$UsersID."User_ID"],1);?></span></p>
		<p style='margin-top:10px;'>累积可提现提成:&nbsp;&nbsp;<span class="red"><?=$rsDistirbuteAccount['Salesman_Income'];?></p>
    </div>
	<div style='margin:10px 0;border-top:10px solid #DDD;border-bottom:10px solid #DDD;text-indent:20px;'>
		<div>
			<p style='font-size:18px;'>我的商家：</p>
			<?php foreach($my_sellers as $key=>$seller){?>
				<ul>
					<li><?php echo $seller['Biz_Name']?></li>
					<li><?php echo $seller['Biz_Address']?></li>
					<li><?php echo '入驻时间'.date('Y-m-d H:i:s',$seller['Biz_CreateTime'])?></li>
				</ul>
			<?php }?>
		</div>
		<div style='margin-top:10px;'>
			<p style='font-size:18px;'>我的提成明细:</p>
		<?php foreach($salesman_record as $key=>$record){?>
			<p><?php echo $record['Biz_Name'];?></p>
			<p><?php echo $record['Sales_Money']?></p>
			<p><?php echo $record['Status']?></p>
			<p><?php echo $record['Products_Name']?></p>
		<?php }?>
		</div>
    </div>
    
    <?php if($rsDistirbuteAccount['Ex_Bonus'] >0):?>
    <div class="button-panel text-center">
    	<button class="btn btn-default" id="get_ex_btn">获得奖励</button>
    </div>
    <?php endif;?>
  </div>
		<table class="table">
        <thead>
          <tr>
           	<th></th>
				<th>成为业务员消费限额</th>
          </tr>
        </thead>
        <tbody>
		  
          <tr>
            <td scope="row"><?='￥'.$limit?></td>
          </tr>
        </tbody>
      </table>
    	
    </div>
  </div>
</div>
 
<?php require_once('../skin/distribute_footer.php');?> 
 
</body>
</html>

