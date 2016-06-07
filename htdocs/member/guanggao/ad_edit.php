<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$ID=empty($_REQUEST['id'])?0:$_REQUEST['id'];
$rsAD=$DB->GetRs("ad_list","*","where Users_ID='".$_SESSION["Users_ID"]."' and AD_ID=".$ID);
$ImgPath=json_decode($rsAD['AD_Img'],true);
if($_POST)
{

	$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
	$StartTime=strtotime($Time[0]);
	$EndTime=strtotime($Time[1]);
	$Data=array(
	    "AD_IDS"=>$_POST['IDS'],
		"AD_Img"=>json_encode((isset($_POST["ImgPath"])?$_POST["ImgPath"]:array()),JSON_UNESCAPED_UNICODE),
		"AD_Link"=>$_POST['Link'],
		"AD_StarTime"=>$StartTime,
		"AD_EndTime"=>$EndTime
	);
	$Flag=$DB->Set("ad_list",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and AD_ID=".$ID);
	if($Flag)
	{
		echo '<script language="javascript">alert("更新成功");window.location="ad_list.php";</script>';
	}else
	{
		echo '<script language="javascript">alert("更新失败");history.back();</script>';
	}
	exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type='text/javascript' src='/static/member/js/guanggao.js'></script>
<script>
$(document).ready(shop_obj.products_init);
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=tuan_products',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		if(K('#PicDetail').children().length>=1){
			alert('您已上传了图片，不能再上传！');
			return;
		}
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align) {
					K('#PicDetail').append('<div><a href="'+url+'" target="_blank"><img src="'+url+'" /></a> <span>删除</span><input type="hidden" name="ImgPath[]" value="'+url+'" /></div>');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#PicDetail div span').click(function(){
		K(this).parent().remove();
	});
})
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/guanggao.css' rel='stylesheet' type='text/css' />
    
    
    <div class="r_nav">
      <ul>
         <li class=""><a href="config.php">广告位管理</a></li>
         <li class="cur"><a href="ad_list.php">广告列表</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <form id="products_form" class="r_con_form" method="post" action="ad_edit.php?id=<?php echo $ID;?>">
       <div class="rows">
          <label>隶属分类</label>
          <span class="input">
          <select name='IDS'>
            <option value=''>--请选择--</option>
            <?php
$DB->get("ad_advertising","AD_IDS,AD_Name","where Users_ID='".$_SESSION["Users_ID"]."' order by AD_IDS asc");
$ParentCategory=array();
$i=1;
while($rsPCategory=$DB->fetch_assoc()){
	$ParentCategory[$i]=$rsPCategory;
	$i++;
}
foreach($ParentCategory as $key=>$value){
	    $selected = $value['AD_IDS'] == $rsAD["AD_IDS"]?' selected="selected"':'';
		echo '<option value="'.$value["AD_IDS"].'" '.$selected.'>'.$value["AD_Name"].'</option>';
}
?>
          </select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>广告图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片大小建议：640*60像素</div>
            <div class="clear"></div>
          </div>
          </span>
          <div class="img" id="PicDetail"><?php foreach($ImgPath as $key=>$value){?>
            <div><a target="_blank" href="<?php echo $value ?>"> <img src="<?php echo $value ?>"></a><span>删除</span>
              <input type="hidden" name="ImgPath[]" value="<?php echo $value ?>">
            </div>
            <?php }?></div>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>广告链接</label>
          <span class="input">
          <input type="text" name="Link" value="<?php echo $rsAD['AD_Link'];?>" class="form_input" notnull style="width:600px;" />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>起止时间</label>
          <span class="input">
          <input name="Time" type="text" value="<?php echo date("Y/m/d H:i:s",$rsAD["AD_StarTime"])." - ".date("Y/m/d H:i:s",$rsAD["AD_EndTime"]) ?>" class="form_input" size="42" readonly notnull />
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
      <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
      <script language="javascript">
        var date_str=new Date();
		$('#products_form input[name=Time]').daterangepicker({
			timePicker:true,
			//minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			format:'YYYY/MM/DD HH:mm:00'}
		);
       </script>
</body>
</html>