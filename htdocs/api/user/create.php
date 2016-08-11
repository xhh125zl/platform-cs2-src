<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}

//获取来源网站owner_id
$shop_config = shop_config($UsersID);
$dis_config = dis_config($UsersID);
//合并参数
$shop_config = array_merge($shop_config,$dis_config);
$owner = get_owner($shop_config,$UsersID);
$dis_config_limit_result = $DB->GetRs('distribute_config','Distribute_Limit','where Users_ID="'.$UsersID.'"');
if($dis_config_limit_result['Distribute_Limit'] == 1 && $owner['id']==0){
	echo '必须通过邀请人才能成为会员';
	exit;
}

if(empty($_SESSION[$UsersID."HTTP_REFERER"])){
	$HTTP_REFERER="/api/".$UsersID."/user/";
}else{
	$HTTP_REFERER=$_SESSION[$UsersID."HTTP_REFERER"];
}
$item = $DB->GetRs("user_config","ExpireTime","where Users_ID='".$UsersID."'");
$expiretime = $item["ExpireTime"];

$rsConfig=$DB->GetRs("user_profile","*","where Users_ID='".$UsersID."'");
if($_POST){
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_Mobile='".$_POST["Mobile"]."'");
	if($rsUser){
		$Data=array(
			"status"=>0,
			"msg"=>"对不起，此手机号已经注册，请勿重复注册！"
		);
	}else{
		$rsUser=$DB->GetRs("user","User_No","where Users_ID='".$UsersID."' order by User_No desc");
		if(empty($rsUser["User_No"])){
			$User_No="600001";
		}else{
			$User_No=$rsUser["User_No"]+1;
		}
		if(isset($rsConfig['Profile_Input'])){
			$Home_Json_Input=json_decode($rsConfig['Profile_Input'],true);
			for($no=0;$no<=4;$no++){
				if(isset($Home_Json_Input[$no]['InputName'])){
					$User_Json_Input[] = array(
						"InputName"=>$Home_Json_Input[$no]['InputName'],
						"InputValue"=>isset($_POST["InputValue_".$no]) ? $_POST["InputValue_".$no] : ""
					);
				}
			}
		}
		if(isset($rsConfig['Profile_Select'])){
			$Home_Json_Select=json_decode($rsConfig['Profile_Select'],true);
			for($no=0;$no<=4;$no++){
				if(isset($Home_Json_Select[$no]['SelectName'])){
					$User_Json_Select[] = array(
						"SelectName"=>$Home_Json_Select[$no]['SelectName'],
						"SelectValue"=>isset($_POST["SelectValue_".$no]) ? $_POST["SelectValue_".$no] : ""
					);
				}
			}
		}
		$Data=array(
			"User_Mobile"=>$_POST['Mobile'],
			"User_Password"=>md5($_POST['Password']),
			"User_PayPassword"=>md5($_POST['Password']),				
			"User_From"=>1,
			"User_CreateTime"=>time(),
			"User_Status"=>1,
			"User_Remarks"=>"",
			"User_No"=>$User_No,
			"User_Json_Input"=>isset($User_Json_Input) ? json_encode($User_Json_Input,JSON_UNESCAPED_UNICODE) : "",
			"User_Json_Select"=>isset($User_Json_Select) ? json_encode($User_Json_Select,JSON_UNESCAPED_UNICODE) : "",
			"User_ExpireTime"=>$expiretime==0 ? 0 : ( time() + $expiretime*86400 ),
			"Users_ID"=>$UsersID,
		);
		
		if($rsConfig["Profile_Name"]){
			$Data["User_Name"] = $_POST['Name'];
		}
		
		if($rsConfig["Profile_Area"]){
			$Data["User_Province"] = $_POST['Province'];
			$Data["User_City"] = $_POST['City'];
			$Data["User_Area"] = $_POST['Area'];
		}
		
		if($rsConfig["Profile_Gender"]){
			$Data["User_Gender"] = $_POST['Gender'];
		}
		
		if($rsConfig["Profile_Age"]){
			$Data["User_Age"] = $_POST['Age'];
		}
		
		if($rsConfig["Profile_NickName"]){
			$Data["User_NickName"] = removeEmoji($_POST['NickName']);
		}
		
		if($rsConfig["Profile_IDNum"]){
			$Data["User_IDNum"] = $_POST['IDNum'];
		}
		if($rsConfig["Profile_Telephone"]){
			$Data["User_Telephone"] = $_POST['Telephone'];
		}
		if($rsConfig["Profile_Fax"]){
			$Data["User_Fax"] = $_POST['Fax'];
		}
		if($rsConfig["Profile_QQ"]){
			$Data["User_QQ"] = $_POST['QQ'];
		}
		if($rsConfig["Profile_Email"]){
			$Data["User_Email"] = $_POST['Email'];
		}
		if($rsConfig["Profile_Company"]){
			$Data["User_Company"] = $_POST['Company'];
		}
		if($rsConfig["Profile_Address"]){
			$Data["User_Address"] = $_POST['Address'];
		}
	
		if($owner['id'] != 0){
			$Data["Owner_Id"] = $owner['id'] ;
			$Data["Root_ID"] = $owner['Root_ID'];
		}
		$Flag = $DB->Add("user",$Data);
		if($Flag){
			$_SESSION[$UsersID."User_ID"]=$DB->insert_id();
			$_SESSION[$UsersID."User_Mobile"]=$_POST['Mobile'];
			$OpenID = md5(session_id() . $_SESSION[$UsersID."User_ID"]);
			$DB->Set("user",array('User_OpenID'=>$OpenID),'where Users_ID="'.$UsersID.'" and User_ID='.$_SESSION[$UsersID."User_ID"]);
			$Data=array(
				"status"=>1,
				"msg"=>"恭喜，注册成功！",
				"url"=>$HTTP_REFERER
			);
		}else{
			$Data=array(
				"status"=>0,
				"msg"=>"注册失败！"
			);
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}
if(!empty($_SESSION[$UsersID."User_ID"]))
{
	header("location:/api/".$UsersID."/shop/member/");
}
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<style type="text/css">
html, body{background:#fff;}
</style>
<div id="header_unlogin" class="wrap">
  <ul>
    <li class="home first"><a href="/api/<?php echo $UsersID ?>/user/"></a></li>
  </ul>
</div>

<script language="javascript">$(document).ready(user_obj.user_create_init);</script>
<form  method="post" id="user_form">
  <h1>会员注册</h1>
  <div class="input">
    <input type="tel" name="Mobile" value="" maxlength="11" placeholder="手机号码" pattern="[0-9]*" notnull />
  </div>
  <div class="input">
    <input type="password" name="Password" value="" maxlength="16" placeholder="登录密码" notnull />
  </div>
  <div class="input">
    <input type="password" name="ConfirmPassword" value="" maxlength="16" placeholder="确认密码" notnull />
  </div>
  <?php if($rsConfig["Profile_Name"]){?>
  <div class="input">
    <input type="text" name="Name" value="" maxlength="10" placeholder="您的姓名"<?php echo $rsConfig["Profile_NameNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_Area"]){?>
  <div class="input">
    <select name="Province"<?php echo $rsConfig["Profile_AreaNotNull"]==1 ? " notnull" : ""?>>
    </select>
    <select name="City"<?php echo $rsConfig["Profile_AreaNotNull"]==1 ? " notnull" : ""?>>
    </select>
    <select name="Area"<?php echo $rsConfig["Profile_AreaNotNull"]==1 ? " notnull" : ""?>>
    </select>
	<script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script>
    <script language="javascript">new PCAS('Province', 'City', 'Area');</script>
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_Gender"]){?>
  <div class="input">
    <select name="Gender">
	 <option value="男">男</option>
	 <option value="女">女</option>
    </select>
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_Age"]){?>
  <div class="input">
    <input type="text" name="Age" value="" maxlength="10" placeholder="您的年龄"<?php echo $rsConfig["Profile_AgeNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_NickName"]){?>
  <div class="input">
    <input type="text" name="NickName" value="" placeholder="您的昵称"<?php echo $rsConfig["Profile_NickNameNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_IDNum"]){?>
  <div class="input">
    <input type="text" name="IDNum" value="" placeholder="您的身份证号"<?php echo $rsConfig["Profile_IDNumNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_Telephone"]){?>
  <div class="input">
    <input type="text" name="Telephone" value="" placeholder="您的电话"<?php echo $rsConfig["Profile_TelephoneNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_Fax"]){?>
  <div class="input">
    <input type="text" name="Fax" value="" placeholder="您的传真"<?php echo $rsConfig["Profile_FaxNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_QQ"]){?>
  <div class="input">
    <input type="text" name="QQ" value="" placeholder="您的QQ"<?php echo $rsConfig["Profile_QQNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_Email"]){?>
  <div class="input">
    <input type="text" name="Email" value="" placeholder="您的邮箱"<?php echo $rsConfig["Profile_EmailNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_Company"]){?>
  <div class="input">
    <input type="text" name="Company" value="" placeholder="您的公司"<?php echo $rsConfig["Profile_CompanyNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  <?php if($rsConfig["Profile_Address"]){?>
  <div class="input">
    <input type="text" name="Address" value="" placeholder="您的详细地址"<?php echo $rsConfig["Profile_AddressNotNull"]==1 ? " notnull" : ""?> />
  </div>
  <?php }?>
  
  
  						<?php
								if(isset($rsConfig['Profile_Input'])){
									$Home_Json_Input=json_decode($rsConfig['Profile_Input'],true);
									for($no=0;$no<=4;$no++){
										if(isset($Home_Json_Input[$no]['InputName'])){
						?>
                        <div class="input"><input type="text" name="InputValue_<?php echo $no;?>" value="" class="form_input" placeholder="<?php echo $Home_Json_Input[$no]['InputValue'];?>" /></div>
                        <?php }}}?>
                        <?php
								$arr_value = array();
								if(isset($rsConfig['Profile_Select'])){
									$Home_Json_Select=json_decode($rsConfig['Profile_Select'],true);
									for($no=0;$no<=4;$no++){
										if(isset($Home_Json_Select[$no]['SelectName'])){
											$arr_value = explode("|", $Home_Json_Select[$no]['SelectValue']);
						?>
                        <div class="input">
                        	<select name="SelectValue_<?php echo $no;?>">
                            <?php foreach($arr_value as $k=>$v){?>
								<option value='<?php echo $v;?>' ><?php echo $v;?></option>
                            <?php }?>
                            </select>
                         </div>
                        <?php }}}?>
  <div class="submit">
    <input name="提交" type="button" value="立即注册" />
  </div>
</form>
</body>
</html>
<?php exit;?>