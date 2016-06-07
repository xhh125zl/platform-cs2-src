<?php
 
if(empty($_SESSION["Users_ID"])){
	header("location:../login.php");
}
$DB->showErr=false;

	if(empty($_POST)){
		if(!isset($_GET['id'])){
			
		}else if($_GET['id'] == ''){
			echo "<script>alert('请选择角色1')</script>";
		}
	}else{
		if(empty($_POST['id'])){
			echo "<script>alert('请选择角色2')</script>";
		}else{
			$data = array();
			$data['role'] = $_POST['role'];
			$data['role_note'] = $_POST['role_note'];
			$data['status'] = $_POST['status'];
			$data['role_right'] = json_encode(isset($_POST['role_right'])?$_POST['role_right']:'',JSON_UNESCAPED_UNICODE);
			
			if($DB->set('users_roles',$data,'where id='.$_POST['id'])){
				echo "<script>alert('修改成功')</script>";
			}else{
				//echo mysql_error();
				echo "<script>alert('修改失败')</script>";
			}
		}
	}
		
	$rsUsers = $DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
	$Users_Right = json_decode($rsUsers['Users_Right'],true);
	$right = array();
	foreach ($Users_Right as $key=>$val){
		foreach($val as $k=>$v){
			$right[$key][$v] = $file_all[$key][$v];
		}
	}
	
	$my_users_right = array_merge($file,$right);
	$id = (isset($_GET['id'])?$_GET['id']:(isset($_POST['id'])?$_POST['id']:''));
	$role_mess = $DB->GetRs("users_roles","*","where id='".$id."' and users_account='".$_SESSION['Users_Account']."'");
	$my_right = json_decode($role_mess['role_right'],true);
	
	if(!empty($my_right)){
		foreach ($my_right as $key=>$val){
			foreach($val as $k=>$v){
				$my_rights[$key][$v] = $file_all[$key][$v];
			}
		}
	}else{
		$my_right = '';
	}

	$condition = "where users_account='{$_SESSION['Users_Account']}'";
	$my_all_roles = $DB->Get('users_roles','*',$condition);
	$my_role = array();
	while($r = $DB->fetch_assoc()){
		$my_role[] = $r;
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
			<li class="cur"><a href="role_edit.php">角色信息</a></li>
			<li><a href="employee_add.php">添加员工</a></li>
			<li><a href="employee_edit.php">员工信息</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
	<form action='role_edit.php' method='get' style='position:absolute;top:68px;left:50%;'>
		<select name='id'>
			<option value=''>选择角色</option>
			<?php if(!empty($my_role)){
				var_dump($my_role);
				foreach($my_role as $key=>$val){
					$selected = '';
					if($val['id'] == $id){
						$selected = ' selected';
					}
					echo "<option value='".$val['id']."'".$selected.">".$val['role']."</option>";
				}
			}?>
			
		</select>
		<input type='submit' value='编辑'>
	</form>
	 <form class="r_con_form" method="post" action="?">
	 <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
       <td valign="top">
        
            <div class="rows">
                <label>角色名称</label>
                <span class="input" style='width:20%;'>
				<input type="text" name="role" class="form_input" value='<?php echo !empty($role_mess)?$role_mess['role']:''?>'/> 
				<input type="hidden" name="id" class="form_input" value='<?php echo !empty($role_mess)?$role_mess['id']:''?>'/> 
				<font class="fc_red">*</font></span>
				<span class="input" style='width:20%;border:0;color:#DDD;'>(如员工职位等)</span>
				<span class="input" style='width:30%;'>
				
				</span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>是否启用</label>
                <span class="input">
                    <label><input name="status" type="radio" value="1" <?php if($role_mess['status'] == 1) echo "checked";?> >启用</label>
                    <label><input name="status" type="radio" value="0" <?php if($role_mess['status'] == 0) echo "checked";?>>禁用</label>
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>简要说明</label>
                <span class="input"><textarea id="role_note" name="role_note" rows="5" style="width:200px"><?php echo !empty($role_mess)?$role_mess['role_note']:''?></textarea></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" value="修改" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
         
		</td>
		<td width="10">&nbsp;</td>
        <td width="240" style="border-left:1px #dddddd solid; padding:10px" valign="top">
         <div class="right_top">开通权限</div>
		 <style>
			li.title div{
				display:inline-block;height:15px;width:15px;border:1px solid #ddd;line-height:15px;text-align:center;margin-left:5px;background-color:#ddd;
				}
			li.title div:hover{cursor:pointer;}
		 </style>
         <ul class="right_ul" style='margin-left:30px;'>
		 
		 <?php 
			 foreach($my_users_right as $key=>$value){?>
			  <?php foreach($file_all[$key] as $k=>$v){
			    $checked = "";
				if(isset($my_right[$key])){
					if(in_array($k,$my_right[$key])){
						$checked = " checked";
					}
				}
				
				if($k == $key){?>
					<li class='title'><input type="checkbox" onclick='ob = $(this);$("ul.right_ul li.<?php echo $k;?> input").attr("checked",!$("ul.right_ul li.<?php echo $k;?> input").attr("checked"))' style='margin-left:-20px;' name="role_right[<?php echo $key;?>][]" id="Right_<?php echo $k;?>" value="<?php echo $k;?>" onClick="Set_Price(0,'<?php echo $k;?>');"<?php echo $checked;?>><span style='display:inline-block;width:80px;'><?php echo $v;?></span><?php if(count($file_all[$key]) > 1){?><div onclick='if($(this).html() == "-"){$("li.<?php echo $key;?>").slideUp();$(this).html("+");}else{$("ul.right_ul li.next_act").slideUp();$(".right_ul li div").html("+");$("ul.right_ul li.<?php echo $k;?>").slideDown();$(this).html("-")}'>+</div><?php }?></li>
				<?php }else{
			  ?>
                <li class='<?php echo $key;?> next_act' style='display:none;'>|--<input type="checkbox"  name="role_right[<?php echo $key;?>][]" id="Right_<?php echo $k;?>" value="<?php echo $k;?>" onClick="Set_Price(0,'<?php echo $k;?>');"<?php echo $checked;?>> <?php echo $v;?></li>
             <?php }}}?>
         </ul>
        </td>
	   </tr>
      </table>
      </form>	  
    </div>
  </div>
</div>
<SCRIPT type=text/javascript>
function Set_Price(p,k){
	if(document.getElementById("Right_"+k).checked==false){
		document.getElementById("Right_"+k).value = "";
	}else{
		document.getElementById("Right_"+k).value = k;
	}
}
</SCRIPT>
</body>
</html>