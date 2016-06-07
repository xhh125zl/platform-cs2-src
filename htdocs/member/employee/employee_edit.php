<?php
 
if(empty($_SESSION["Users_ID"])){
	header("location:../login.php");
}
$DB->showErr=false;
	/* if(empty($_GET['id']) && empty($_POST['id'])){
		echo '<script language="javascript">alert("参数错误");window.location="javascript:history.back()";</script>';
	} */
	if(!empty($_GET['id'])){
		$employee = $DB->GetRs("users_employee","*","where id='".$_GET['id']."' and users_account='".$_SESSION["Users_Account"]."'");
	}
	if(!empty($_POST)){
			if(empty($_POST["id"])){
				echo '<script language="javascript">alert("请选择员工");window.location="javascript:history.back()";</script>';
				exit();
			}
			if(empty($_POST["employee_name"])){
				echo '<script language="javascript">alert("登录帐号不能为空！");window.location="javascript:history.back()";</script>';
				exit();
			}
			
			$Data = array(
				'employee_name'=>$_POST['employee_name'],
				'employee_login_name'=>$_POST['employee_login_name'],
				'employee_expiretime'=>strtotime($_POST['employee_expiretime']),
				'role_id'=>$_POST['role_id'],
				'status'=>$_POST['status'],
				'employee_note'=>$_POST['employee_note'],
				'update_time'=>time()
			);
			if(!empty($_POST["employee_passA"])){
				$Data['employee_pass'] = md5($_POST['employee_passA']);
			}
			//var_dump($Data);
			if($DB->set('users_employee',$Data,' where id='.$_POST['id'].' and users_account="'.$_SESSION['Users_Account'].'"')){
				echo "<script>alert('修改成功')</script>";
			}else{
				echo mysql_error();
				echo '<script language="javascript">alert("修改失败");window.location="employee.php";</script>';
			}
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
<style>
.right_top{font-size:14px; font-weight:bold; height:36px; line-height:36px; padding-left:15px; background:#fff}
.right_ul{padding-left:5px; padding-top:10px; background:#fff; list-style:none; margin:0px}
.right_ul li{height:28px; line-height:28px;}
</style>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
		<ul>
			<li><a href="roles.php">创建角色</a></li>
			<li><a href="role_edit.php">角色信息</a></li>
			<li><a href="employee_add.php">添加员工</a></li>
			<li class="cur"><a href="employee_edit.php">员工信息</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
	 <form class="r_con_form" method="post" action="?">
	 <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
       <td valign="top">
			
			<div class="rows">
                <label>员工称呼</label>
                <span class="input"><input type="text" name="employee_name" class="form_input" value='<?php echo !empty($employee['employee_name'])?$employee['employee_name']:''?>'/> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>登录帐号</label>
                <span class="input">
				<input type="text" name="employee_login_name" class="form_input" value='<?php echo !empty($employee['employee_login_name'])?$employee['employee_login_name']:''?>'/> <font class="fc_red">*</font>
				<input type="hidden" name="id" class="form_input" value='<?php echo !empty($employee['id'])?$employee['id']:'';?>'/>
				</span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label>登录密码</label>
                <span class="input"><input type="password" name="employee_passA" class="form_input" /> <font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>到期时间</label>
                <span class="input">
                    <input type="text" name="employee_expiretime" style="Width:150px;" value="<?php echo !empty($employee['employee_expiretime'])?date("Y-m-d H:i:s",$employee['employee_expiretime']):''?>" onClick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly>
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
							$role_id = !empty($employee['role_id'])?$employee['role_id']:'';
							foreach($my_role as $key=>$val){
								$str = '';
								if($val['id'] == $role_id) $str = "selected";
								echo "<option value='".$val['id']."'".$str.">".$val['role']."</option>";
							}
						}?>				
					</select>
                </span>
                <div class="clear"></div>
            </div>
			<div class="rows">
                <label>是否启用</label>
                <span class="input">
                    <label><input name="status" type="radio" value="1" <?php if(!empty($employee['status']) && $employee['status'] == 1) echo "checked";?> checked>启用</label>
                    <label><input name="status" type="radio" value="0" <?php if(!empty($employee['status']) && $employee['status'] == 0) echo "checked";?>>禁用</label>
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>描述</label>
                <span class="input"><textarea id="employee_note" name="employee_note" rows="5" style="width:200px"><?php echo !empty($employee['employee_note'])?$employee['employee_note']:''?></textarea></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="修改" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
         
		</td>
		<td width="10">&nbsp;</td>
		<td width="440" style="border-left:1px #dddddd solid; padding:10px" valign="top">
         <div class="right_top">员工列表(点击修改)</div>
		
			<ul class="right_ul" style='margin-left:30px;'>
			<?php 
			
				$condition = "where users_account='{$_SESSION['Users_Account']}'";
				$my_all_roles = $DB->Get('users_roles','*',$condition);
				
				$my_role = array();
				while($r = $DB->fetch_assoc($my_all_roles)){
					$my_role[] = $r;
				}
			
				$emp = $DB->Get('users_employee','*',"where users_account='".$_SESSION['Users_Account']."'");
				echo mysql_error();
				$my_employee = array();
				while($r = $DB->fetch_assoc($emp)){
					$my_employee[] = $r;
				}
				if(!empty($my_employee)){
					foreach($my_employee as $key=>$val){
						foreach($my_role as $k=>$v){
							if($v['id'] == $val['role_id']){
								$role = $v['role'];
							}
						}
						echo '<li style="border-bottom:1px solid #DDD;"><a href="employee_edit.php?id='.$val['id'].'"><table width="100%"><tr><td width="100" align="left">称呼：'.$val['employee_name'].'</td><td align="left">账号：'.$val['employee_login_name'].' </td><td width="120px">角色：'.(empty($role) ? '' : $role).'</td></tr></table></a></li>';
					}
				}
			?>
		</ul>
        </td>
	   </tr>
      </table>
      </form>	  
    </div>
  </div>
</div>
</body>
</html>