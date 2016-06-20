<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["BIZ_ID"]))
{
	header("location:/biz/login.php");
}
$rsBiz=$DB->GetRs("weicbd_biz","*","where Biz_ID=".$_SESSION["BIZ_ID"]);
$GiftID=empty($_REQUEST['GiftID'])?0:$_REQUEST['GiftID'];
$rsGift=$DB->GetRs("user_gift","*","where Biz_ID=".$_SESSION["BIZ_ID"]." and Gift_ID=".$GiftID);
if($_POST)
{
	$Data=array(
		"Gift_Name"=>$_POST['Name'],
		"Gift_ImgPath"=>$_POST['ImgPath'],
		"Biz_ID"=>$_SESSION["BIZ_ID"],
		"Gift_Integral"=>$_POST['Integral'],
		"Gift_Qty"=>$_POST['Qty'],
		"Gift_Shipping"=>$_POST['Shipping'],
		"Gift_MyOrder"=>$_POST['MyOrder'],
		"Gift_BriefDescription"=>$_POST['BriefDescription']
	);
	$Flag=$DB->Set("user_gift",$Data,"where Gift_ID=".$GiftID);
	if($Flag)
	{
		echo '<script language="javascript">alert("修改成功");window.location="gift.php";</script>';
	}else
	{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
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
	K.create('textarea[name="BriefDescription"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/biz/upload_json.php?TableField=user_gift',
		fileManagerJson : '/biz/file_manager_json.php',
		allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
	});
	var editor = K.editor({
		uploadJson : '/biz/upload_json.php?TableField=user_gift',
		fileManagerJson : '/biz/file_manager_json.php',
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
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"> <a href="gift.php">礼品管理</a></li>
        <li> <a href="gift_add.php">添加礼品</a></li>
        <li class=""> <a href="gift_orders.php">兑换订单管理</a></li>
      </ul>
    </div>
    <div id="gift" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script language="javascript">$(document).ready(user_obj.gift_edit_init);</script>
      <form id="gift_edit_form" class="r_con_form" method="post" action="?">
        <div class="rows">
          <label>礼品名称</label>
          <span class="input">
          <input type="text" name="Name" value="<?php echo $rsGift['Gift_Name'] ?>" class="form_input" size="35" maxlength="100" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>礼品图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片大小建议：640*360px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail"><?php echo empty($rsGift["Gift_ImgPath"])?'':'<img src="'.$rsGift["Gift_ImgPath"].'" />' ?></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>兑换所需积分</label>
          <span class="input price">
          <input type="text" name="Integral" value="<?php echo $rsGift['Gift_Integral'] ?>" class="form_input" size="5" maxlength="10" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>剩余数量</label>
          <span class="input price">
          <input type="text" name="Qty" value="<?php echo $rsGift['Gift_Qty'] ?>" class="form_input" size="5" maxlength="10" notnull />
          <font class="fc_red">*</font> <span class="tips">礼品兑换完，将自动下架</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>需要物流</label>
          <span class="input price">
          <select name="Shipping">
            <option value='0'<?php echo $rsGift['Gift_Shipping']==0?' selected':'' ?>>否</option>
            <option value='1'<?php echo $rsGift['Gift_Shipping']==1?' selected':'' ?>>是</option>
          </select>
          <span class="tips">如果您提供的是本地化服务或礼品是虚拟物品，请选择"否"</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>排序优先级</label>
          <span class="input">
          <select name="MyOrder">
            <option value='0'<?php echo $rsGift['Gift_MyOrder']==0?' selected':'' ?>>默认</option>
            <option value='1'<?php echo $rsGift['Gift_MyOrder']==1?' selected':'' ?>>一级优先</option>
            <option value='2'<?php echo $rsGift['Gift_MyOrder']==2?' selected':'' ?>>二级优先</option>
            <option value='3'<?php echo $rsGift['Gift_MyOrder']==3?' selected':'' ?>>三级优先</option>
            <option value='4'<?php echo $rsGift['Gift_MyOrder']==4?' selected':'' ?>>四级优先</option>
            <option value='5'<?php echo $rsGift['Gift_MyOrder']==5?' selected':'' ?>>五级优先</option>
          </select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>简短介绍</label>
          <span class="input">
          <textarea name="BriefDescription" class="briefdesc" style="width:500px; height:300px;"><?php echo $rsGift['Gift_BriefDescription'] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="GiftID" value="<?php echo $GiftID ?>">
        <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo $rsGift['Gift_ImgPath'] ?>" />
      </form>
    </div>
  </div>
</div>
</body>
</html>