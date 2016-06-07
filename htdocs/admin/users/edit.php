<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
require_once('right.php');
$UsersID=empty($_GET["UsersID"])?"0":$_GET["UsersID"]; 
$rsUsers=$DB->GetRs("users","*","where Users_ID='".$UsersID."'");

if(empty($rsUsers)){
	echo '<script language="javascript">alert("此用户已经不存在！");window.location="javascript:history.back()";</script>';
	exit();
}
if($_POST){
	if(empty($_POST["Users_Account"])){
		echo '<script language="javascript">alert("登录帐号不能为空！");window.location="javascript:history.back()";</script>';
		exit();
	}
	$rsUsers=$DB->GetRs("users","*","where Users_Account='".$_POST["Users_Account"]."' and Users_ID<>'".$UsersID."'");
	if($rsUsers){
		echo '<script language="javascript">alert("该用户已经存在，请修改！");window.location="javascript:history.back()";</script>';
		exit();
	}
	if($_POST["Users_PasswordA"]!=$_POST["Users_PasswordB"]){
		echo '<script language="javascript">alert("登录密码和确认密码不一致，请修改！");window.location="javascript:history.back()";</script>';
		exit();
	}
	$Data=array(
		"Users_Account"=>$_POST["Users_Account"],
		"Users_Right"=>json_encode((isset($_POST["JSON"])?$_POST["JSON"]:array()),JSON_UNESCAPED_UNICODE),
		"Users_ExpireDate"=>strtotime($_POST["Users_ExpireDate"]),
		"Users_Status"=>$_POST["Users_Status"],
		"Users_Industry"=>$_POST["Users_Industry"],
		"Users_Remarks"=>$_POST["Users_Notes"],
	);
	$DB->Set("users",$Data,"where Users_ID='".$_POST["Users_CKID"]."'");
	$password = trim($_POST["Users_PasswordA"]);
	if(!empty($password)){
		$Data=array(
			"Users_Password"=>md5($password)
		);
		$DB->Set("users",$Data,"where Users_ID='".$UsersID."'");
	}
	
	echo '<script language="javascript">';
	echo 'alert("修改成功！");';
	echo '	window.open("index.php","_self");';
	echo '</script>';
	exit();
}else{
	$RIGHT = json_decode($rsUsers["Users_Right"],true);
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
<script charset="utf-8" src="/third_party/My97DatePicker/WdatePicker.js"></script>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
#ImgDetail img{width:60px; height:60px;}
.right_top{font-size:14px; font-weight:bold; height:36px; line-height:36px; padding-left:15px; background:#fff}
.right_ul{padding-left:5px; padding-top:10px; background:#fff; list-style:none; margin:0px}
.right_ul li{height:28px; line-height:28px;}
</style>
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
		<ul>
        <li class="cur"><a href="index.php">商家管理</a></li>
        <li><a href="add.php">添加商家</a></li>
		<li><a href="sjrz.php">入驻申请</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
	  <form class="r_con_form" method="post" action="?UsersID=<?php echo $UsersID;?>">
	    <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
		  <td>
        	<div class="rows">
                <label>用户编号</label>
                <span class="input"><input type="text" name="Users_CKID" class="form_input" value="<?php echo $rsUsers["Users_ID"];?>" readonly></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>登录帐号</label>
                <span class="input"><input type="text" name="Users_Account" class="form_input" value="<?php echo $rsUsers["Users_Account"];?>"> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>登录密码</label>
                <span class="input"><input type="password" name="Users_PasswordA" class="form_input" /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>确认密码</label>
                <span class="input"><input type="password" name="Users_PasswordB" class="form_input" /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>到期时间</label>
                <span class="input">
                    <input type="text" name="Users_ExpireDate" style="Width:150px;" value="<?php echo date("Y-m-d H:i:s",$rsUsers["Users_ExpireDate"]); ?>" onClick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly>
                </span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>是否启用</label>
                <span class="input">
                <label><input name="Users_Status" type="radio" value="1"<?php echo $rsUsers["Users_Status"]==1 ? " checked" : "";?>>启用</label>
                <label><input name="Users_Status" type="radio" value="0"<?php echo $rsUsers["Users_Status"]==0 ? " checked" : "";?>>禁用</label>
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>所属行业</label>
                <span class="input">
                 <select name="Users_Industry">
                   <?php
				   $lists = array();
                   $DB->get("industry","*","where parentid=0 order by id asc");
				   while($r=$DB->fetch_assoc()){
					   $lists[] = $r;
				   }
				   foreach($lists as $r){
					    echo '<option value="'.$r["id"].'"'.($rsUsers["Users_Industry"]==$r["id"] ? ' selected' : '').'>'.$r["name"].'</option>';
					   	$DB->get("industry","*","where parentid=".$r["id"]." order by id asc");
						while($t=$DB->fetch_assoc()){
							echo '<option value="'.$t["id"].'"'.($rsUsers["Users_Industry"]==$t["id"] ? ' selected' : '').'>&nbsp;└&nbsp;'.$t["name"].'</option>';
						}
				   }
			       ?>
                 </select>
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>商家简介</label>
                <span class="input"><textarea id="Users_Notes" name="Users_Notes" rows="5" style="width:200px"><?php echo $rsUsers["Users_Remarks"]?></textarea></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="确定" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
		  </td>
		  <td width="10">&nbsp;</td>
          <td width="240" style="border-left:1px #dddddd solid; padding:10px" valign="top">
            <div class="right_top">开通权限</div>
            <ul class="right_ul">
             <?php foreach($right as $key=>$value){?>
			  <?php foreach($file[$value] as $k=>$v){
			    $checked = "";
			    if(isset($RIGHT[$value])){
					if(in_array($k,$RIGHT[$value])){
						$checked = " checked";
					}
				}
			  ?>
                <li><input type="checkbox" name="JSON[<?php echo $value;?>][]" id="Right_<?php echo $k;?>" value="<?php echo $k;?>" onClick="Set_Price(0,'<?php echo $k;?>');"<?php echo $checked;?>> <?php echo $v;?></li>
             <?php }}?>
            </ul>
          </td>
		 </tr>
        </table>
      </form>
     </div>
  </div>
</div>
<script type="text/javascript">
function Set_Price(p,k){
	if(document.getElementById("Right_"+k).checked==false){
		document.getElementById("Right_"+k).value = "";
	}else{
		document.getElementById("Right_"+k).value = k;
	}
}
</script>
</body>
</html>