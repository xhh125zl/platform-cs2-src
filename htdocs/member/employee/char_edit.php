<?php 
if(empty($_SESSION["Users_ID"])){
	header("location:../login.php");
}
$DB->showErr=false;
/*edit in 20160318*/
if(!empty($_GET['id'])){
		$rsUsers = $DB->GetRs("users","*","where Users_ID='".$_SESSION["Users_ID"]."'");
$Users_Right = json_decode($rsUsers['Users_Right'],true);
foreach($rmenu as $key=>$value){
	if (array_key_exists($key,$Users_Right)){
	foreach($value as $k=>$v){
		$Users_Right[$key][] = $k;
	}
	}
}
$myrmenu = array();
foreach($sysrmenu as $key=>$value){
	if (array_key_exists($key,$Users_Right)){
		foreach($value as $k=>$v){
			if($key == 'weicuxiao'){
				$cux = 0;
				$cux = $k == 'weicuxiao'?1:0;
				if($k != 'weicuxiao' && in_array($k,$Users_Right[$key])){			
				$cux = 1;
				}
			}else{
				$cux = 1;
			}
		if($cux == 1){
					$myrmenu[$key][$k] = $v;					
				}				
			}
		}
	}
$my_righta = array_merge($rmenu,$myrmenu);
		$role = $DB->GetRs("users_roles","*","where id='".$_GET['id']."' and users_account='".$_SESSION["Users_Account"]."'");
		$my_right = json_decode($role['role_right'],true);		
	}

if($_POST){
	if(empty($_POST["id"])){
				echo '<script language="javascript">alert("请选择员工");window.location="javascript:history.back()";</script>';
				exit();
			}else{
		$Data = array(			
			'status'=>$_POST['status'],			
			'role_note'=>$_POST['char_note'],
			'role_right'=>json_encode(isset($_POST['rmenu'])?$_POST['rmenu']:'',JSON_UNESCAPED_UNICODE),
			'update_time'=>time()
		);		
		
		if($DB->set('users_roles',$Data,' where id='.$_POST['id'].' and users_account="'.$_SESSION['Users_Account'].'"')){
				echo '<script language="javascript">alert("修改成功");window.location="charlist.php";</script>';
			}else{
				echo mysql_error();
				echo '<script language="javascript">alert("修改失败");window.location="charlist.php";</script>';
			}
		
	}
}
$nokey = 0;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<link href='/static/css/buttoncss.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script charset="utf-8" src="/third_party/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
/*edit in 20160318*/
        $(function () {
            // 全选
            $("#btnCheckAll").bind("click", function () {
                $("[id = checkitem]:checkbox").attr("checked", true);
            });
 
            // 全不选
            $("#btnChecknone").bind("click", function () {
                $("[id = checkitem]:checkbox").attr("checked", false);
            });
 
            // 反选
            $("#btnCheckflase").bind("click", function () {
                $("[id = checkitem]:checkbox").each(function () {
                    $(this).attr("checked", !$(this).attr("checked"));
                });
            });
			// 分类选
            $(".rotop").bind("click", function () {
				var catg = $(this).parent().parent().attr("id");
				if($(this).attr("checked")){
					$("#b"+catg+" input[type=checkbox]:checkbox").attr("checked", true);
				}else{
					$("#b"+catg+" input[type=checkbox]:checkbox").attr("checked", false);
				}
            });
			//权限列表
   $(".sctrl").toggle(function(){
     $(".role").animate({height: 'toggle', opacity: 'toggle'}, "slow");
   },function(){
$(".role").animate({height: 'toggle', opacity: 'toggle'}, "slow");
   });

        });
    </script>
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
		 <li class="redonly"><strong>角色修改</strong></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />      
      <form class="r_con_form" method="post" action="?" id="emp_add_form">
	  <div class="rows">
                <label>角色名称</label>
                <span class="input"><input type="text" name="charname" disabled="true" class="form_input" id="charname" value="<?php echo !empty($role)?$role['role']:''?>" required />
				<input type="hidden" name="id" value="<?php echo !empty($role)?$role['id']:''?>"><font class="fc_red">*</font></span>
                <div class="clear"></div>
            </div> 
			<!--edit in 20160318-->
            <div class="rows">
                <label>权限</label>
				<span class="input">
				<div class="sctrl">点击选择权限</div>
				<ul class="role" style="display:none">				
				<div class="sbtnp">				
				<input id="btnCheckAll" type="button" class="button gray" value="全选" />
				<input id="btnChecknone" type="button" class="button gray" value="不选" />
				<input id="btnCheckflase" type="button" class="button gray" value="返选" />
				</div>
                <?php foreach($my_righta as $key=>$value){?>
		   <?php foreach($value as $k=>$v){ ?>
			<?php if($k == $key){
				$nokey = 0;				
				if(!empty($my_right)){
			if (array_key_exists($k,$my_right)){
					if(in_array($k,$my_right[$k])){
				   $checked = 'checked';
			   }else{
				   $checked = '';
			   }
				}else{
					$nokey = 1;
					$checked = '';
				}
			}else{
				$checked = '';
			}
				?>
			<div class="sitemone" id="<?php echo $k;?>"><li><input type="checkbox" name="rmenu[<?php echo $key;?>][]" id="checkitem" class="rotop" value="<?php echo $k;?>" <?php echo $checked;?> /><strong>&nbsp;<?php echo $v;?></strong></li></div>
			<div class="sitemtwo" id="b<?php echo $k;?>">					
				<?php }else{
					if(!empty($my_right)){
					if($nokey != 1){
						if(in_array($k,$my_right[$key])){
				   $checked = 'checked';
			   }else{
				   $checked = '';
			   }
					}else{						
						$checked = '';
					}
				}
				?>
				<li class="oitem"><input type="checkbox" name="rmenu[<?php echo $key;?>][]" id="checkitem" value="<?php echo $k;?>" <?php echo $checked;?> />&nbsp;<?php echo $v;?></li>
              <?php }}?>
			 </div>
				<?php } ?>
			 </ul>
			 </span>
                <div class="clear"></div>
            </div>
			<div class="rows">
                <label>是否启用</label>
                <span class="input">
                    <label><input name="status" type="radio" value="1" <?php if(!empty($role['status']) && $role['status'] == 1) echo "checked";?>>启用</label>
                    <label><input name="status" type="radio" value="0" <?php if(!empty($role['status']) && $role['status'] == 0) echo "checked";?>>禁用</label>
                </span>
                <div class="clear"></div>
            </div>
			<div class="rows">
                <label>描述</label>
                <span class="input"><textarea id="char_note" name="char_note" rows="5" style="width:200px"><?php echo !empty($role['role_note'])?$role['role_note']:''?></textarea></span>
                <div class="clear"></div>
            </div>
            <div class="rows">
                <label></label>
                <span class="inputrol"><input type="submit" name="Submit" value="修改" class="submit">
                  <input type="reset" value="重置" ></span>
                <div class="clear"></div>
            </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>