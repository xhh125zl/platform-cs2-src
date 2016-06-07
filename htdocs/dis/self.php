<?php
ini_set("display_errors","On");
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$base_url = base_url();

if(empty($_SESSION["Distribute_ID"]))
{
	header("location:/dis/login.php");
}

$Users_ID  =  $_SESSION["Dis_Users_ID"]; 
$User_ID = $_SESSION["Distribute_ID"];

$rsConfig = shop_config($Users_ID);
//分销相关设置
$dis_config = dis_config($Users_ID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
$level_config = $rsConfig['Dis_Level'];
$accountObj = Dis_Account::Multiwhere(array('Users_ID'=>$Users_ID,'User_ID'=>$User_ID)) 
                          ->first();

$action = $request->input('action',false);
$rsAccount = $accountObj->toArray();

//更改账号信息
if($action != false){	
	if($action == 'edit_shop'){
		$request->input('Shop_Name');
		//如果允许自定义店名和头像
		
		if($request->has('Shop_Name')){
			 $accountObj->Shop_Name = $request->input('Shop_Name');
			 $accountObj->Shop_Logo = $request->input('Img');	
		}
		$accountObj->Shop_Announce = $request->input('Shop_Announce');
	
      
		$Flag = $accountObj->save();
		if($Flag)
		{
			echo '<script language="javascript">alert("修改成功");window.location="self.php";</script>';
		}else
		{
			echo '<script language="javascript">alert("保存失败");history.back();</script>';
		}
		exit;
		
	}
}


//获取此用户的所有下属
$posterity = $accountObj->getPosterity($level_config);							 

$total_sales = get_my_leiji_sales($Users_ID,$User_ID,$posterity);
$total_income = get_my_leiji_income($Users_ID,$User_ID);

$posterity = $accountObj->getPosterity($level_config);	
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>

<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/dis/upload_json.php?TableField=admin&Users_ID=<?=$Users_ID?>',
		fileManagerJson : '/dis/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	
	K('#ImgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#Img').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#Img').val(url);
					K('#ImgDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
})
</script>
    
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/distribute.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    
    <!-- nav begin -->
   		<?php $cur_nav = 'self'?>
   		<?php require_once('nav.php')?>
    <!-- nav end-->
   
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
  
    <div id="update_post_tips"></div>
    <div id="user" class="r_con_wrap">
      
      <div class="r_con_wrap">
		<form class="r_con_form" method="post" action="?action=edit_shop">
        	 <?php if($rsConfig['Distribute_Customize'] == 1): ?>
            <div class="rows">
                <label>店铺名称</label>
                <span class="input"><input name="Shop_Name" value="<?=$rsAccount['Shop_Name']?>" size="30" class="form_input" type="text"></span>
                <div class="clear"></div>
            </div>
           
            <div class="rows">
              <label>店铺头像</label>
              <span class="input"> <span class="upload_file">
              <div>
                <div class="up_input">
                  <input id="ImgUpload" value="上传图片" style="width:80px;" type="button">
                </div>
                <div class="tips">图片建议尺寸：100*100px</div>
                <div class="clear"></div>
              </div>
              <div class="img" id="ImgDetail" style="padding-top:8px;">
                              <img src="<?=$rsAccount["Shop_Logo"]?>">
                            </div>
              </span></span>
              <div class="clear"></div>
            </div>
            <input type="hidden" name="Img" id="Img" value="<?=$rsAccount['Shop_Logo']?>" />
            <?php endif; ?>
            
           <div class="rows">
                <label>自定义分享语</label>
                <span class="input"><textarea name="Shop_Announce" style="width:300px;" rows="5"><?=$rsAccount['Shop_Announce']?></textarea><br /></span> 
                <div class="clear"></div>
            </div>
             
           <div class="rows">
                <label>老板身份</label>
                <span class="input">
               <?=$rsAccount['Enable_Tixian'] == 1?'已是老板':'不是老板';?>
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="rows">
                <label>团队人数</label>
                <span class="input" style="color:#f40000">
               	  <?=$rsAccount['Group_Num']?>(含自己)
                </span>
                <div class="clear"></div>
            </div>
           
           <div class="rows">
                <label>累计佣金</label>
                <span class="input">&yen;&nbsp;<?=$total_income?></span> 
                              
                <div class="clear"></div>
            </div>
            
            <div class="rows">
            	<label>累计销售额</label>
                <span class="input">&yen;&nbsp;<?=$total_sales?></span>
                  <div class="clear"></div>
            </div>

            	<div class="rows">
                	<label></label>
                	<span class="input"><input name="Submit" value="确定" class="submit" type="submit">
                 	 <input value="重置" type="reset"></span>
                	<div class="clear"></div>
            	</div>
    
            
        	
        </form>
     </div>
     
    </div>
  </div>
  
  
  
</div>
</div>
</body>
</html>