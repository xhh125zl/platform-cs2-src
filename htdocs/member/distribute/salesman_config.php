<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');


$base_url = base_url();

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
//获取分销商称号配置

if($_POST){	
	
	$Dis_salesman['Salesman'] =  $_POST['Dis_salesman'];
	$Dis_salesman['Salesman_ImgPath'] =  $_POST['ImgPath'];
	$flag = $DB->Set('distribute_config', $Dis_salesman, "where Users_ID='" . $_SESSION['Users_ID'] . "'");
	if($flag){
		echo "<script>alert('设置成功');window.location='salesman_config.php';</script>";
	}
}

//获取此商家业务设置
$rsDsLvel = $DB->GetRs('distribute_config', 'Salesman,Salesman_ImgPath', "where Users_ID='" . $_SESSION['Users_ID'] . "'");
if(!$rsDsLvel){
	$Data = array(
		"Users_ID"=>$_SESSION['Users_ID'],
		"Salesman"=>0,
		"Salesman_ImgPath"=>'/static/api/distribute/images/sales_join_header.jpg'
	);
	
	$DB->Set('distribute_config', $Data,"where Users_ID='" . $_SESSION['Users_ID'] . "'");
	$rsDsLvel = $Data;
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
<script type='text/javascript' src='/static/js/jquery.formatCurrency-1.4.0.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=shop_sales_config&UsersID=<?php echo $_SESSION["Users_ID"];?>',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	
	K('#ImgUpload').click(function(){
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                imageUrl : K('#ImgPath').val(),
                clickFn : function(url, title, width, height, border, align){
                    K('#ImgPath').val(url);
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
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
     <ul>
        <li class="cur"><a href="salesman_config.php" target="iframe">创始人设置</a></li>
        <li class=""><a href="salesman.php">创始人列表</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">
	$(document).ready(function(){
		shop_obj.dis_title_init();
	});
    </script>
    <div id="bizs" class="r_con_wrap">
      <form id="level_form" method="post" action="?" class="r_con_form">
        <div class="rows">
          <label>成为创始人条件</label>
          <span class="input">
          消费满 <input type="text" name="Dis_salesman" value="<?=$rsDsLvel['Salesman']?>" class="form_input" size="10" maxlength="50" notnull /> 元
          <font class="fc_red">*</font> <span class="tips">(满足消费额条件即可成为创始人,创始人可发展商家获取利润)</span></span>
          <div class="clear"></div>
        </div>
		
        <div class="rows">
          <label>申请创始人页面顶部图</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：640*自定义</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail" style="margin-top:8px"><img src="<?=!empty($rsDsLvel['Salesman_ImgPath'])?$rsDsLvel['Salesman_ImgPath']:'/static/api/distribute/images/sales_join_header.jpg'?>" /></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" /></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="ImgPath" name="ImgPath" value="<?=$rsDsLvel['Salesman_ImgPath']?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>