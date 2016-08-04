<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $output['_site_url'];?>/static/css/select2.css" rel="stylesheet" />
<script type='text/javascript' src="<?php echo $output['_site_url'];?>/static/js/select2.js"></script>
<script type="text/javascript" src="<?php echo $output['_site_url'];?>/static/js/location.js"></script>
<script type="text/javascript" src="<?php echo $output['_site_url'];?>/static/js/area.js"></script>
<style>
.select2-container, .select2-drop, .select2-search, .select2-search input{
	width:140px;
}
</style>
<div class="comtent">
    <?php include(dirname(__DIR__) . '/home/_menu.php');?>
</div>
<div class="breadcrumb">
    <?php if(!empty($output['Bread'])){?>
    <?php foreach($output['Bread'] as $link => $name){?>
    <span><a href="<?php echo $link;?>"><?php echo $name;?></a></span> &nbsp;>&nbsp;
    <?php }?>
    <?php }?>
</div>
<div class="my_con">
    <div class="body">
        <?php include(__DIR__ . '/_left.php');?>
        <div class="body_center_pub all_dingdan">
        <label style="margin-left:15px;">个人信息</label>
		<div class="xinxi_form">
		    <form method="POST" id="personal_form" action="<?php url('member/personal_information');?>">
				<div style="margin-top:20px;" class="name"><span class="xiang"><i>*</i>昵称：</span>
				  <input type="text" id="nicheng" name="User_Name" value="<?php echo $output['rsUser']['User_Name'];?>">
				</div>
				<div style="margin-top:10px;" class="xingbie"><span class="xiang"><i>*</i>性别：</span>
				  <label>
					<input type="radio" <?php if($output['rsUser']['User_Gender'] == 1){?>checked="checked"<?php }?> id="RadioGroup1_0" value="1" name="RadioGroup1">
					男
				  </label>
				  <label>
					<input type="radio" <?php if($output['rsUser']['User_Gender'] == 2){?>checked="checked"<?php }?> id="RadioGroup1_1" value="2" name="RadioGroup1">
					女</label>
				  <label>
					<input type="radio" <?php if($output['rsUser']['User_Gender'] == 3){?>checked="checked"<?php }?> id="RadioGroup1_1" value="3" name="RadioGroup1">
					保密
				  </label>
				</div>
				<div style="margin-top:10px;" class="birthday"><span class="xiang">生日：</span>
				  <select name="birthday_year">
					<option>请选择</option>
					<?php $birthday_year = array_shift($output['Birthday_arr']);?>
					<?php foreach($output['birthday_year'] as $k => $v){?>
					<option <?php if($birthday_year == $v){?> selected="selected"<?php }?>><?php echo $v;?></option>
					<?php }?>
				  </select>
				  年
				  <select name="birthday_month">
					<option>请选择</option>
					<?php $birthday_month = array_shift($output['Birthday_arr']);?>
					<?php foreach($output['birthday_month'] as $k => $v){?>
					<option <?php if($birthday_month == $v){?> selected="selected"<?php }?>><?php echo $v;?></option>
					<?php }?>
				  </select>
				  月
				  <select name="birthday_day">
					<option>请选择</option>
					<?php $birthday_day = array_shift($output['Birthday_arr']);?>
					<?php for($i=1;$i<=31;$i++){?>
					<option <?php if($birthday_day == $i){?> selected="selected"<?php }?>><?php echo $i;?></option>
					<?php }?>
				  </select>
				  日 </div>
				<div style="margin-top:9px;" class="emailadd"><span class="xiang"><i>*</i>邮箱：</span>
				  <input type="text" id="emailadd" name="User_Email" value="<?php echo $output['rsUser']['User_Email']?>">
				</div>
				<div style="margin-top:20px;" class="truename"><span class="xiang"><i>*</i>真实姓名：</span>
				  <input type="text" id="truename" name="User_NickName" value="<?php echo $output['rsUser']['User_NickName']?>">
				</div>
				<div style="margin-top:20px;" class="diqu"><span class="xiang">地区：</span>
				  <div class="info">
					<div>
					  <select name="s_province" id="loc_province"></select>
					  <select name="s_city" id="loc_city"></select>
					  <select name="s_county" id="loc_town"></select>
					  <script>
						showLocation(<?php echo $output['rsUser']['User_Province'] ? $output['rsUser']['User_Province'] : 0;?>,<?php echo $output['rsUser']['User_City'] ? $output['rsUser']['User_City'] : 0;?>,<?php echo $output['rsUser']['User_Area'] ? $output['rsUser']['User_Area'] : 0;?>);
					  </script>
					</div>
					<div id="show"></div>
				  </div>
				</div>
				
				<div style="margin-top:20px;"><span class="xiang"><i>*</i>手机号码：</span>
                                    <input type="text" name="Mobile" readonly="" value="<?php echo $output['rsUser']['User_Mobile']?>">
				</div>
				<div style="margin-top:20px;"><span class="xiang">登录密码：</span>
				  <input type="text" name="Password" value="">
				</div>
				<a class="submit" href="javascript:;">提交</a>
			</form>
		</div>
    </div>
    </div>
</div>
<script>
$('.submit').click(function(){
	$('#personal_form').submit();
})
</script>