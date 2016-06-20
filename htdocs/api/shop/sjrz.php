<?php
ini_set("display_errors","On");
require_once($_SERVER["DOCUMENT_ROOT"].'/api/shop/global.php'); 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

$base_url = base_url();
$shop_url = shop_url();


if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/shop/index.php?UsersID=".$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(isset($_GET["CategoryID"]) && isset($_GET["typeid"])){
	$html = array();
	
	$table = $_GET["typeid"] == 0 ? "shop_category" : "shop_union_category";
	$DB->get($table,"*","where Category_ParentID=".$_GET["CategoryID"]." and Users_ID='".$UsersID."' order by Category_Index asc,Category_ID asc");
	while($r=$DB->fetch_assoc()){
		$html[] = $r;
	}
	
	$Data = array(
		"status"=> count($html)>0 ? 1 : 0,
		"html"=>count($html)>0 ? $html : ""
	);
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}elseif($_POST){
	$Data = array(
		"Users_ID"=>$UsersID,
		"Invitation_Code"=>isset($_POST["Invitation_Code"]) ? $_POST["Invitation_Code"] : '',
		"Category_ID"=>isset($_POST["trade_1"]) ? $_POST["trade_1"] : $_POST["trade_0"],
		"Biz_Name"=>$_POST["company"],
		"CreateTime"=>time(),
		"Contact"=>$_POST["contact"],
		"Mobile"=>$_POST["telephone"],
		"Email"=>$_POST["email"]
	);
	
	$Flag = $DB->Add("biz_apply",$Data);
	if($Flag){
		$Data=array(
			"status"=>1
		);
	}else{
		$Data=array(
			"status"=>0,
			"msg"=>"提交失败"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}else{

	$shopConfig=$DB->GetRs("shop_config","*","where Users_ID='".$UsersID."'");
        $disConfig = $DB->GetRs("distribute_config","*","where Users_ID='".$UsersID."'");
        $rsConfig = array_merge($shopConfig,$disConfig);
	$owner = get_owner($rsConfig,$UsersID);
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
	$error_msg = pre_add_distribute_account($rsConfig,$UsersID);
	$owner = get_owner($rsConfig,$UsersID);

	$share_name = '';
	if($owner['id'] != '0'){
		$shop_url = $shop_url.$owner['id'].'/';
	};

	$share_link = $shop_url;
	require_once('share.php');
}
require_once('./skin/top.php');
?>
<body>
<script type='text/javascript' src='/static/api/shop/js/reserve.js?t=<?php echo time();?>'></script>
<script language="javascript">
var UsersID = '<?php echo $UsersID;?>';
$(document).ready(reserve_obj.reserve_init);
</script>
<link href='/static/api/shop/skin/default/css/sjrz.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<div id="reserve_success">提交成功！</div>
<div id="shop_page_contents">
	<div id="cover_layer"></div>
    <!--header-->
 	<div id="header_common">
  		<div class="remark"><span onClick="history.go(-1);"></span>商家入驻</div>
  		<div class="clear"></div>
 	</div>
	<div id="reserve">
	  <form name="reserve_form">
		<div class="reserve_table">
		  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
			  <tr>
				<td colspan="2">请认真填写表单</td>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td class="label1">邀请码</td>
				<td>
				<?php if(isset($_GET['Invitation_Code'])){?>
					<span class="form_input">  <?=$_GET['Invitation_Code']?><span/>
					<input type="hidden" name="Invitation_Code" value="<?=$_GET['Invitation_Code']?>" class="form_input" notnull />
				<?php }else{?>
					<input type="text" name="Invitation_Code" value="" class="form_input" notnull />
				<?php }?>
				</td>
			  </tr>
			  <tr>
				<td class="label1">商家名称</td>
				<td><input type="text" name="company" value="" class="form_input" notnull /></td>
			  </tr>
			  <tr>
				<td class="label1">行业分类</td>
				<td>
					<select id="trade_0" name="trade_0">
					 <option value="">请选择分类</option>
					 <?php
						$lists = array();
						$DB->get("shop_category","*","where Category_ParentID=0 and Users_ID='".$UsersID."' order by Category_Index asc,Category_ID asc");
						while($r=$DB->fetch_assoc()){
							$lists[] = $r;
						}
						foreach($lists as $t){
							echo '<option value="'.$t["Category_ID"].'">'.$t["Category_Name"].'</option>';
						}
					 ?>
					</select>
					<select id="trade_1" name="trade_1">
					 <option value="">请选择分类</option>
					</select>
				</td>
			  </tr>
			  <tr>
				<td class="label1">联系人</td>
				<td><input type="text" name="contact" value="" class="form_input" notnull /></td>
			  </tr>
			  <tr>
				<td class="label1">联系电话</td>
				<td><input type="text" name="telephone" value="" class="form_input" notnull /></td>
			  </tr>
			  <tr>
				<td class="label1">电子邮箱</td>
				<td><input type="text" name="email" value="" class="form_input" /></td>
			  </tr>
			</tbody>
		  </table>
		</div>
		<div class="blank9"></div>
		<div>
		  <input type="button" class="submit" value="提 交" />
		</div>
	  </form>
	</div>
</div>
<?php require_once('./skin/distribute_footer.php');?>