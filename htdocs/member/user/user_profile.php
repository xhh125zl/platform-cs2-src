<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

$rsProfile=$DB->GetRs("user_profile","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsProfile)){
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"]
	);
	$DB->Add("user_profile",$Data);
	$rsProfile=$DB->GetRs("user_profile","*","where Users_ID='".$_SESSION["Users_ID"]."'");
}
if(isset($_GET["action"])){
	if($_GET["action"]=="profile"){
		$Flag=$DB->Set("user_profile","Profile_".$_GET['field']."=".(empty($_GET['Status'])?1:0),"where Users_ID='".$_SESSION["Users_ID"]."'");
		$Data=array("status"=>1);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}

if($_POST)
{
	//开始事务定义
	$Flag=true;
	$msg="";
	for($no=0; $no<5; $no++){
		if(isset($_POST["InputName"][$no]) && $_POST["InputName"][$no]<>""){
			$Json_Input[]=array(
				"InputName"=>$_POST["InputName"][$no],
				"InputValue"=>$_POST["InputValue"][$no]
			);
		}
		if(isset($_POST["SelectName"][$no]) && $_POST["SelectName"][$no]<>""){
			$Json_Select[]=array(
				"SelectName"=>$_POST["SelectName"][$no],
				"SelectValue"=>$_POST["SelectValue"][$no]
			);
		}
	}
	$Data=array(
		"Profile_Input"=>isset($Json_Input) ? json_encode($Json_Input,JSON_UNESCAPED_UNICODE) : '',
		"Profile_Select"=>isset($Json_Select) ? json_encode($Json_Select,JSON_UNESCAPED_UNICODE) : '',
	);
	if(empty($rsProfile)){
		$Add=$DB->Add("user_profile",$Data);
	}else{
		$Add=$DB->SET("user_profile",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	}
	$Flag=$Flag&&$Add;
	if($Flag){
		$Data=array(
			"status"=>1
		);
	}else{
		$Data=array(
			"status"=>0
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a>
          <dl>
            <dd class="first"><a href="lbs.php">一键导航设置</a></dd>
          </dl>
        </li>
        <li class="cur"> <a href="user_list.php">会员管理</a>
          <dl>
            <dd class="first"><a href="user_level.php">会员等级设置</a></dd>
            <dd class=""><a href="user_profile.php">会员注册资料</a></dd>
            <dd class=""><a href="card_benefits.php">会员权利说明</a></dd>
            <dd class=""><a href="user_list.php">会员管理</a></dd>
          </dl>
        </li>
        <li class=""> <a href="card_config.php">会员卡设置</a></li>
        
        <li class=""><a href="business_password.php">商家密码设置</a></li>
      </ul>
    </div>
    <script language="javascript">$(document).ready(user_obj.profile_init);</script>
    <div id="user_profile" class="r_con_wrap">
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="20%">字段</td>
            <td width="30%">启用 / 必填</td>
            <td width="20%">字段</td>
            <td width="30%" class="last">启用 / 必填</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>手机【系统固定不能更改】</td>
            <td><img src="/static/member/images/ico/on.gif" /> <img src="/static/member/images/ico/on.gif" /></td>
            <td>姓名</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Name'])?'off':'on' ?>.gif" field="Name" Status="<?php echo $rsProfile['Profile_Name'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_NameNotNull'])?'off':'on' ?>.gif" field="NameNotNull" Status="<?php echo $rsProfile['Profile_NameNotNull']?>" /></td>
          </tr>
          <tr>
            <td>性别</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Gender'])?'off':'on' ?>.gif" field="Gender" Status="<?php echo $rsProfile['Profile_Gender'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_GenderNotNull'])?'off':'on' ?>.gif" field="GenderNotNull" Status="<?php echo $rsProfile['Profile_GenderNotNull'] ?>" /></td>
            <td>年龄</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Age'])?'off':'on' ?>.gif" field="Age" Status="<?php echo $rsProfile['Profile_Age'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_AgeNotNull'])?'off':'on' ?>.gif" field="AgeNotNull" Status="<?php echo $rsProfile['Profile_AgeNotNull'] ?>" /></td>
          </tr>
          <tr>
            <td>昵称</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_NickName'])?'off':'on' ?>.gif" field="NickName" Status="<?php echo $rsProfile['Profile_NickName'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_NickNameNotNull'])?'off':'on' ?>.gif" field="NickNameNotNull" Status="<?php echo $rsProfile['Profile_NickNameNotNull'] ?>" /></td>
            <td>身份证号</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_IDNum'])?'off':'on' ?>.gif" field="IDNum" Status="<?php echo $rsProfile['Profile_IDNum'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_IDNumNotNull'])?'off':'on' ?>.gif" field="IDNumNotNull" Status="<?php echo $rsProfile['Profile_IDNumNotNull'] ?>" /></td>
          </tr>
          <tr>
            <td>电话</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Telephone'])?'off':'on' ?>.gif" field="Telephone" Status="<?php echo $rsProfile['Profile_Telephone'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_TelephoneNotNull'])?'off':'on' ?>.gif" field="TelephoneNotNull" Status="<?php echo $rsProfile['Profile_TelephoneNotNull'] ?>" /></td>
            <td>传真</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Fax'])?'off':'on' ?>.gif" field="Fax" Status="<?php echo $rsProfile['Profile_Fax'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_FaxNotNull'])?'off':'on' ?>.gif" field="FaxNotNull" Status="<?php echo $rsProfile['Profile_FaxNotNull'] ?>" /></td>
          </tr>
          <tr>
            <td>生日</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Birthday'])?'off':'on' ?>.gif" field="Birthday" Status="<?php echo $rsProfile['Profile_Birthday'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_BirthdayNotNull'])?'off':'on' ?>.gif" field="BirthdayNotNull" Status="<?php echo $rsProfile['Profile_BirthdayNotNull'] ?>" /></td>
            <td>QQ</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_QQ'])?'off':'on' ?>.gif" field="QQ" Status="<?php echo $rsProfile['Profile_QQ'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_QQNotNull'])?'off':'on' ?>.gif" field="QQNotNull" Status="<?php echo $rsProfile['Profile_QQNotNull'] ?>" /></td>
          </tr>
          <tr>
            <td>邮箱</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Email'])?'off':'on' ?>.gif" field="Email" Status="<?php echo $rsProfile['Profile_Email'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_EmailNotNull'])?'off':'on' ?>.gif" field="EmailNotNull" Status="<?php echo $rsProfile['Profile_EmailNotNull'] ?>" /></td>
            <td>公司</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Company'])?'off':'on' ?>.gif" field="Company" Status="<?php echo $rsProfile['Profile_Company'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_CompanyNotNull'])?'off':'on' ?>.gif" field="CompanyNotNull" Status="<?php echo $rsProfile['Profile_CompanyNotNull'] ?>" /></td>
          </tr>
          <tr>
            <td>地区</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Area'])?'off':'on' ?>.gif" field="Area" Status="<?php echo $rsProfile['Profile_Area'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_AreaNotNull'])?'off':'on' ?>.gif" field="AreaNotNull" Status="<?php echo $rsProfile['Profile_AreaNotNull'] ?>" /></td>
            <td>详细地址</td>
            <td><img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_Address'])?'off':'on' ?>.gif" field="Address" Status="<?php echo $rsProfile['Profile_Address'] ?>" /> <img src="/static/member/images/ico/<?php echo empty($rsProfile['Profile_AddressNotNull'])?'off':'on' ?>.gif" field="AddressNotNull" Status="<?php echo $rsProfile['Profile_AddressNotNull'] ?>" /></td>
          </tr>
          <tr> </tr>
        </tbody>
      </table>
      <form class="r_con_form" id="Profile_form" style="margin-top:10px">
             <div class="rows">
                    <label>其他字段</label>
                    <span class="input">
                        <div class="tips"></div>
                        <table border="0" cellpadding="5" cellspacing="0" class="reverve_field_table">
                            <thead>
                                <tr>
                                    <td width="16%">字段类型</td>
                                    <td width="32%">字段名称</td>
                                    <td width="32%">初始内容</td>
                                    <td width="20%">操作</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								if(isset($rsProfile['Profile_Input']) && !empty($rsProfile['Profile_Input'])){
									$Home_Json_Input=json_decode($rsProfile['Profile_Input'],true);
									for($no=1;$no<=5;$no++){
										if(isset($Home_Json_Input[$no-1]['InputName'])){
								?>
                                <tr style="display:;" FieldType="text">
                                    <td>新增文本框<?php echo $no;?>：</td>
                                    <td><input type="text" class="form_input" value="<?php echo $Home_Json_Input[$no-1]['InputName'];?>" name="InputName[]" /></td>
                                    <td><input type="text" class="form_input" value="<?php echo $Home_Json_Input[$no-1]['InputValue'];?>" name="InputValue[]" /></td>
                                    <td><a href="javascript:void(0);" class="input_add"><img src="/static/member/images/ico/add.gif" /></a></td>
                                </tr>	
                                <?php }else{?>
                                <tr style="display:none;" FieldType="text">
                                    <td>新增文本框<?php echo $no;?>：</td>
                                    <td><input type="text" class="form_input" value="" name="InputName[]" /></td>
                                    <td><input type="text" class="form_input" value="" name="InputValue[]" /></td>
                                    <td><a href="javascript:void(0);" class="input_del"><img src="/static/member/images/ico/del.gif" /></a></td>
                                </tr>
								<?php }
									}
								}else{
									for($no=1;$no<=5;$no++){
								?>
                                <tr style="display:<?php echo $no==1 ? '' : 'none';?>;" FieldType="text">
                                    <td>新增文本框<?php echo $no;?>：</td>
                                    <td><input type="text" class="form_input" value="" name="InputName[]" /></td>
                                    <td><input type="text" class="form_input" value="" name="InputValue[]" /></td>
                                    <td><a href="javascript:void(0);" class="input_add"><img src="/static/member/images/ico/add.gif" /></a></td>
                                </tr>
								<?php }}
								?>
                                <?php
								if(isset($rsProfile['Profile_Select']) && !empty($rsProfile['Profile_Select'])){
									$Home_Json_Select=json_decode($rsProfile['Profile_Select'],true);
									for($no=1;$no<=5;$no++){
										if(isset($Home_Json_Select[$no-1]['SelectName'])){
								?>
                                <tr style="display:;" FieldType="select">
                                    <td>新增下拉框<?php echo $no;?>：</td>
                                    <td><input type="text" class="form_input" value="<?php echo $Home_Json_Select[$no-1]['SelectName'];?>" name="SelectName[]" /></td>
                                    <td><input type="text" class="form_input" value="<?php echo $Home_Json_Select[$no-1]['SelectValue'];?>" name="SelectValue[]" placeholder="每个选项之间以“|”分割" /></td>
                                    <td><a href="javascript:void(0);" class="select_add"><img src="/static/member/images/ico/add.gif" /></a></td>
                                </tr>
                                <?php }else{?>
                                <tr style="display:none;" FieldType="select">
                                    <td>新增下拉框<?php echo $no;?>：</td>
                                    <td><input type="text" class="form_input" value="" name="SelectName[]" /></td>
                                    <td><input type="text" class="form_input" value="" name="SelectValue[]" placeholder="每个选项之间以“|”分割" /></td>
                                    <td><a href="javascript:void(0);" class="select_del"><img src="/static/member/images/ico/del.gif" /></a></td>
                                </tr>
								<?php }
									}
								}else{
									for($no=1;$no<=5;$no++){
								?>
                                <tr style="display:<?php echo $no==1 ? '' : 'none';?>;" FieldType="select">
                                    <td>新增下拉框<?php echo $no;?>：</td>
                                    <td><input type="text" class="form_input" value="" name="SelectName[]" /></td>
                                    <td><input type="text" class="form_input" value="" name="SelectValue[]" placeholder="每个选项之间以“|”分割" /></td>
                                    <td><a href="javascript:void(0);" class="select_add"><img src="/static/member/images/ico/add.gif" /></a></td>
                                </tr>
                                <?php }}?>
                              </tbody>
                        </table>
                    </span>
                    <div class="clear"></div>
                </div>
                <div class="rows">
                    <label>&nbsp;</label>
                    <span class="input"><input type="submit" class="btn_ok" name="submit_button" value="提交保存" /><a href="index.php" class="btn_cancel">返回</a></span>
                    <div class="clear"></div>
                </div>
            </form>
    </div>
  </div>
</div>
</body>
</html>