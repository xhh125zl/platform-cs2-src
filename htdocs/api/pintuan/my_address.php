 <?php
 require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
 
//通过ajax改变默认地址
if(isset($_POST['ajax']) && 1==$_POST['ajax']){
    $address_id = $_POST['address_id'];
    $usersid = $_POST['usersid'];
    $userid = $_SESSION[$usersid."User_ID"];
    if(!$address_id || !$usersid || !$userid){
        die(json_encode(array(
            'status'=>0,
            'msg'=>'缺少必要的参数'
        ),JSON_UNESCAPED_UNICODE));
    }
    $flag = $DB->set("user_address","Address_Is_Default=0","where Users_ID='{$usersid}' and User_ID='{$userid}'");
    $flag1 = $DB->set("user_address","Address_Is_Default=1","where Users_ID='{$usersid}' and User_ID='{$userid}' and Address_ID='{$address_id}'");
    if($flag && $flag1){
        die(json_encode(array(
            'status'=>1,
            'msg'=>'修改默认地址成功'
        ),JSON_UNESCAPED_UNICODE));
    }else{
        die(json_encode(array(
            'status'=>0,
            'msg'=>'设置默认地址失败'
        ),JSON_UNESCAPED_UNICODE));
    }
}
$TypeID=empty($_GET["TypeID"])?0:$_GET["TypeID"];

if(isset($_GET['action']) && isset($_GET['AddressID'])){
	if($_GET["action"]=="del")
	{
		$AddressID=empty($_GET['AddressID'])?0:$_GET['AddressID'];
		$condition = "Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."' and Address_ID='".$AddressID."'";
		$rsAddress=$DB->GetRs("user_address","*",'where '.$condition);
		
		//将另一个地址设置为默认地址
		$set_another_default = 0;
		if($rsAddress['Address_Is_Default'] == 1){
			$set_another_default = 1;	
		}
		
		$Flag=$DB->Del("user_address",$condition);
		
		if($set_another_default == 1){
			set_anoter_default($UsersID,$_SESSION[$UsersID."User_ID"]);
		}
		
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	
}

if(isset($_GET['OpenID'])){
	$_SESSION[$UsersID.'OpenID']=$_GET['OpenID'];
	header("location:/api/".$UsersID."/pintuan/my/address/".(empty($TypeID)?'':$TypeID.'/')."?wxref=mp.weixin.qq.com");
	exit;
}else{
	if(empty($_SESSION[$UsersID.'OpenID'])){
		$_SESSION[$UsersID.'OpenID']=session_id();
	}
}
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}

$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
if(isset($_SESSION[$UsersID."User_ID"])){
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
}else{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/pintuan/my/address/?wxref=mp.weixin.qq.com";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}

//用户收货地址列表
$rsAddress = $DB->get("user_address","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'");
$address_list = $DB->toArray($rsAddress);

$area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
$area_array = json_decode($area_json,TRUE);
$province_list = $area_array[0];

if(!empty($_GET['AddressID'])){
	 $Select_Model = TRUE;
	 $AddressID = $_GET['AddressID'];
	 $_SESSION[$UsersID."Select_Model"] = 1;

}else{
	 $Select_Model = FALSE;
}

//$redirect_url = empty($_SESSION[$UsersID."HTTP_REFERER"]) ? $_SERVER['HTTP_REFERER'] : $_SESSION[$UsersID."HTTP_REFERER"];

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收货地址管理</title>

<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css?t=<?php echo time(); ?>' rel='stylesheet' type='text/css' />
<link href="/static/css/bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" href="/static/css/font-awesome.css" />
<link rel="stylesheet" href="/static/api/shop/skin/default/css/address_list.css" />
	
<script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time(); ?>'></script>
</head>
<body >

<header class="bar bar-nav">
        <a href="<?php echo isset($_COOKIE['url_referer']) ? "javascript:location.href='{$_COOKIE['url_referer']}';" : 'javascript:history.go(-1)'; ?>" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" width="17px" height="17px"/></a>
        <h1 class="title" id="page_title">收货地址管理 </h1>
</header>
    
    <div id="wrap">
    	<!-- 地址信息简述begin -->
      
		<div class="container">
        
		<?php if(count($address_list) >0 ):?>
			<?php foreach($address_list as $key=>$address): ?>
			<?php
				$Province = $province_list[$address['Address_Province']];
				$City = $area_array['0,'.$address['Address_Province']][$address['Address_City']];
				$Area = $area_array['0,'.$address['Address_Province'].','.$address['Address_City']][$address['Address_Area']];
			
			?>
				<div  class="row receiver-info" id="myaddress">
					<dl address_id="<?=$address['Address_ID']?>">
						<dd class="col-xs-1" style="line-height: 60px;">
						<input name="Fruit" type="radio" value="<?php echo $address['Address_ID'];?>"  class="dz" <?=$address['Address_Is_Default'] == 1?"checked":"";  ?>/>
						<?php if($Select_Model):?>
						<a href="javascript:void(0)" address_id = '<?=$address['Address_ID']?>' class="select_address">&nbsp;&nbsp;<span class="fa fa-check <?=($AddressID == $address['Address_ID'])?'red':'grey'?>"></span></a>
						<?php endif;?>
						</dd>
						<dd class="col-xs-9"><p><?=$address['Address_Name']?>&nbsp;&nbsp;&nbsp;&nbsp;<?=$address['Address_Mobile']?><br/>
							所在地区:<?=$Province?>&nbsp;&nbsp;<?=$City?>&nbsp;&nbsp;<?=$Area?><br/>
							详细地址:<?=$address['Address_Detailed']?>
							<?=$address['Address_Is_Default'] == 1?'&nbsp;&nbsp;<span class="red">默认</span>':''?>	
						</p></dd>
						<dd class="col-xs-1">
                    <a class="edit_address" href="/api/<?=$UsersID?>/pintuan/my/address/del/<?=$address['Address_ID']?>/"><span class="fa fa-remove red"></span></a>    <br/>
                    <a class="edit_address" href="/api/<?=$UsersID?>/pintuan/my/address/edit/<?=$address['Address_ID']?>/"><span class="fa fa-pencil"></span></a>
                     </dd>
					</dl>
				</div>
			<?php endforeach; ?>   
			<br/>
	    <?php else: ?>
			<div  class="row">
				<p style="text-align:center"><br/>暂无收货地址，请添加</p>
			</div>
        <?php endif; ?>
		</div> 
		
		<a id="manage-address-btn" href="/api/<?=$UsersID?>/pintuan/my/address/edit/" style="background-color:#f61d4b;">新增收货地址</a>
    	<!-- 地址信息简述end-->
   
    </div>
    <?php if(count($address_list) >1 ){  ?>
    <script>
    $(function(){
        var myid = $("input[name='Fruit']:checked").val();
        if(localStorage.getItem("<?=$UsersID ?>MyAddressID")){
        	localStorage.removeItem("<?=$UsersID ?>MyAddressID");
        }
        localStorage.setItem("<?=$UsersID ?>MyAddressID",myid);
        $("#myaddress dl").bind("click",function(){
            $(this).find("input[name='Fruit']").prop("checked",true);
            var address_id = $(this).attr("address_id");
            if(localStorage.getItem("<?=$UsersID ?>MyAddressID")){
            	localStorage.removeItem("<?=$UsersID ?>MyAddressID");
            }
            localStorage.setItem("<?=$UsersID ?>MyAddressID",address_id);
            <?php if(isset($_SESSION[$UsersID.'csid'])){ ?>
            location.href = "/api/<?=$UsersID?>/pintuan/order/<?=$_SESSION[$UsersID.'csid']?>/"+address_id+"/";
            <?php } ?>
        });
    });
    </script>
    <?php } ?>
    <!-- 属性选择内容begin -->
	</body>

</html>
