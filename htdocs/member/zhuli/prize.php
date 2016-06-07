<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
if($_POST)
{
	if(!empty($_POST['Prizes'])){
		$Prizes=$_POST['Prizes'];
		foreach($Prizes as $k=>$v){
			if(empty($v['Num'])){
				unset($Prizes[$k]);
			}elseif(empty($v['Name'])){
				unset($Prizes[$k]);				
			}
		}
		$Data=array(
			"Prizes"=>json_encode($Prizes,JSON_UNESCAPED_UNICODE)
		);
		$DB->Set("zhuli",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	}
}

$rsConfig=$DB->GetRs("zhuli","Prizes","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsConfig)){
	header("location:config.php");
}else{
	$Prizes= empty($rsConfig['Prizes']) ? array() : json_decode($rsConfig['Prizes'],true);
	$LEVEL = array('一等奖','二等奖','三等奖','四等奖','五等奖');
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
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=app_wedding',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload_0').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_0').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_0').val(url);
					K('#ImgDetail_0').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload_1').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_1').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_1').val(url);
					K('#ImgDetail_1').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload_2').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_2').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_2').val(url);
					K('#ImgDetail_2').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload_3').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_3').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_3').val(url);
					K('#ImgDetail_3').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	K('#ImgUpload_4').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath_4').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath_4').val(url);
					K('#ImgDetail_4').html('<img src="'+url+'" />');
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
    <link href='/static/member/css/zhuli.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/zhuli.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
		<li class=""><a href="rules.php">活动规则</a></li>
		<li class=""><a href="awordrules.php">兑奖规则</a></li>
        <li class="cur"><a href="prize.php">奖品设置</a></li>
		<li class=""><a href="users.php">用户列表</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">$(document).ready(zhuli_obj.prize_init);</script>
    <div id="prize" class="r_con_config r_con_wrap">
      <form id="prize_form" method="post" action="?">
	    <div style="padding:10px; background:#f5f5f5; border:1px #dfdfdf solid; font-size:12px; color:#f00">注：图片尺寸100px*100px</div>
        <table border="0" cellpadding="5" cellspacing="0" class="level_table">
          <thead>
            <tr>
              <td width="10%">等级</td>
              <td width="20%">奖品名称</td>			  
              <td width="20%">奖品数量</td> 
			  <td width="30%">奖品图片</td>      
              <td width="20%">操作</td>
            </tr>
          </thead>
          <tbody>
            <?php for($i=0; $i<5; $i++){?>
            <tr<?php if($i>0){?> style="display:<?php echo empty($Prizes[$i])?'none':'' ?>;"<?php }?> FieldType="text">
              <td><?php echo $LEVEL[$i];?></td>
              <td><input type="text" class="form_input" value="<?php echo empty($Prizes[$i]['Name'])?'':$Prizes[$i]['Name'] ?>" name="Prizes[<?php echo $i;?>][Name]"  /></td>
              <td><input type="text" class="form_input" value="<?php echo empty($Prizes[$i]['Num'])?'':$Prizes[$i]['Num'] ?>" name="Prizes[<?php echo $i;?>][Num]" /></td>
              <td>
                <input type="button" id="ImgUpload_<?php echo $i;?>" value="选择图片" style="width:80px;" />
                
                <span class="pic" id="ImgDetail_<?php echo $i;?>">100px*100px</span>
                <input type="hidden" id="ImgPath_<?php echo $i;?>" name="Prizes[<?php echo $i;?>][ImgPath]" value="<?php echo empty($Prizes[$i]['ImgPath'])?'':$Prizes[$i]['ImgPath'] ?>" /></td>
              <td>
			   <?php if($i>0){?>
			    <a href="javascript:void(0);" class="input_del"><img src="/static/member/images/ico/del.gif" /></a>
			   <?php }else{?>
			    <a href="javascript:void(0);" class="input_add"><img src="/static/member/images/ico/add.gif" /></a>
			   <?php }?>
			  </td>
            </tr>
            <?php }?>
          </tbody>
        </table>
        <div class="blank20"></div>
        <div class="submit">
          <input type="submit" name="submit_button" value="提交保存" />
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>