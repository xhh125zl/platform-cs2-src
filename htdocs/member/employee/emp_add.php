<?php 
/*edit in 20160317*/
if(empty($_SESSION["Users_ID"])){
	header("location:../login.php");
}
$DB->showErr=false;

if($_POST){		
	if($_POST["emppwd"]!=$_POST["emprepwd"]){
		echo '<script language="javascript">alert("登录密码和确认密码不一致，请修改！");window.location="javascript:history.back()";</script>';
		exit();
	}
	//$rsUsers=$DB->GetRs("users_employee","*","where users_account='".$_SESSION['Users_Account']."' and employee_login_name='".$_POST['employee_login_name']."'");
	$rsUsers=$DB->GetRs("users_employee","*","where employee_login_name='".$_POST['empaccount']."'");
	if($rsUsers){
		echo '<script language="javascript">alert("该用户已经存在，请修改！");window.location="javascript:history.back()";</script>';
		exit();
	}else{
		$Data = array(
			'employee_name'=>$_POST['empname'],
			'employee_login_name'=>$_POST['empaccount'],
			'employee_pass'=>md5($_POST['emppwd']),
			'employee_expiretime'=>strtotime($_POST['employee_expiretime']),
			'status'=>$_POST['status'],
			'role_id'=>$_POST['role_id'],
			'employee_note'=>$_POST['employee_note'],
			'users_account'=>$_SESSION['Users_Account'],
			'create_time'=>time()
		);
		
		if($DB->Add("users_employee",$Data)){
			echo '<script language="javascript">
				if(confirm("增加成功！您还要继续增加用户吗？")){
					window.open("emp_add.php","_self");
				}else{
					//window.open("index.php","_self");
				}
				</script>';
		}else{
			echo '<script language="javascript">alert("添加失败！");history.go(-1);</script>';
		}
		
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script src="/static/js/jquery-1.11.1.min.js"></script>
<script charset="utf-8" src="/third_party/My97DatePicker/WdatePicker.js"></script>
<style type="text/css">
.right_top{font-size:14px; font-weight:bold; height:36px; line-height:36px; padding-left:15px; background:#fff}
.right_ul{padding-left:5px; padding-top:10px; background:#fff; list-style:none; margin:0px}
.right_ul li{height:28px; line-height:28px;}
input[type='submit']{height:30px; line-height:30px; background:url(/static/member/images/global/ok-btn-bg.jpg); border:none; border-radius:8px; color:#fff; width:145px; font-size:14px;}
input[type='reset']{height:30px; line-height:30px; background:url(/static/member/images/global/ok-btn-bg.jpg); border:none; border-radius:8px; color:#fff; width:145px; font-size:14px;}
</style>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">   
    <div class="r_nav">
      <ul>
         <li class=""><a href="charlist.php">角色管理</a></li>
         <li class=""><a href="emplist.php">员工管理</a></li>
		 <li class="red"><strong>员工添加</strong></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />      
      <form class="r_con_form" method="post" action="?" id="emp_add_form">
       <div class="rows">
                <label>员工称呼</label>
                <span class="input"><input type="text" name="empname" class="form_input" id="empname" required /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
        <div class="rows">
                <label>登录帐号</label>
                <span class="input"><input type="text" name="empaccount" class="form_input" id="empaccount" required /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>登录密码</label>
                <span class="input"><input type="password" name="emppwd" class="form_input" id="emppwd" required /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>确认密码</label>
                <span class="input"><input type="password" name="emprepwd" class="form_input" id="emprepwd" required /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>到期时间</label>
                <span class="input">
                    <input type="text" name="employee_expiretime" style="Width:150px;" value="<?php echo date("Y-m-d H:i:s",(time()+86400*7)); ?>" onClick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly>
                </span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>权限</label>
                <span class="input">
                    <select name='role_id'>
						<option value=''>选择角色</option>
						<?php 	$condition = "where users_account='{$_SESSION['Users_Account']}'";
								$my_all_roles = $DB->Get('users_roles','',$condition);
								$my_role = array();
								while($r = $DB->fetch_assoc()){
									$my_role[] = $r;
								}
						?>
						<?php if(!empty($my_role)){
							foreach($my_role as $key=>$val){
								echo "<option value='".$val['id']."'>".$val['role']."</option>";
							}
						}?>				
					</select>
                </span>
                <div class="clear"></div>
            </div>
			<div class="rows">
                <label>是否启用</label>
                <span class="input">
                    <label><input name="status" type="radio" value="1" checked>启用</label>
                    <label><input name="status" type="radio" value="0">禁用</label>
                </span>
                <div class="clear"></div>
            </div>
			<div class="rows">
                <label>描述</label>
                <span class="input"><textarea id="employee_note" name="employee_note" rows="5" style="width:200px"></textarea></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="添加" class="submit">
                  <input type="reset" value="重置" ></span>
                <div class="clear"></div>
            </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>