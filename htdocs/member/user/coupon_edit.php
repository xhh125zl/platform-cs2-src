<?php 
$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$CouponID=empty($_REQUEST['CouponID'])?0:$_REQUEST['CouponID'];
$rsCoupon=$DB->GetRs("user_coupon","*","where Users_ID='".$_SESSION["Users_ID"]."' and Coupon_ID=".$CouponID);
$rsMaterial=$DB->GetRs("wechat_material","Material_Json","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='coupon' and Material_TableID=".$CouponID);
$Material_Json=json_decode($rsMaterial['Material_Json'],true);
if($_POST)
{
	//开始事务定义
	$Flag=true;
	$msg="";
	mysql_query("begin");
	$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
	$StartTime=strtotime($Time[0]);
	$EndTime=strtotime($Time[1]);
	$Data=array(
		"Coupon_Keywords"=>$_POST["Keywords"],
		"Coupon_Title"=>$_POST["Title"],
		"Coupon_Subject"=>$_POST["Subject"],
		"Coupon_PhotoPath"=>$_POST["PhotoPath"],
		"Coupon_UsedTimes"=>$_POST["UsedTimes"],
		"Coupon_UserLevel"=>$_POST["UserLevel"],
		"Coupon_StartTime"=>$StartTime,
		"Coupon_EndTime"=>$EndTime,
		"Coupon_Description"=>$_POST["Description"],
		"Coupon_UseArea"=>$_POST["UseArea"],
		"Coupon_UseType"=>$_POST["UseType"],
		"Coupon_Condition"=>$_POST["Condition"],
		"Coupon_Discount"=>$_POST["Discount"],
		"Coupon_Cash"=>$_POST["Cash"]
	);
	$Set=$DB->Set("user_coupon",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Coupon_ID=".$CouponID);
	$Flag=$Flag&&$Set;
	
	$Material=array(
		"Title"=>$_POST["Title"],
		"ImgPath"=>$_POST["ImgPath"],
		"TextContents"=>$_POST["TextContents"],
		"Url"=>"/api/".$_SESSION["Users_ID"]."/user/coupon/1/"
	);
	$Data=array(
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE)
	);
	$Set=$DB->Set("wechat_material",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='coupon' and Material_TableID=".$CouponID);
	$Flag=$Flag&&$Set;
	
	$Data=array(
		"Reply_Keywords"=>$_POST["Keywords"]
	);
	$Set=$DB->Set("wechat_keyword_reply",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='coupon' and Reply_TableID=".$CouponID);
	$Flag=$Flag&&$Set;
		
	if($Flag){
		mysql_query("commit");
		echo '<script language="javascript">alert("修改成功");window.location=coupon.php";</script>';
	}else{
		mysql_query("roolback");
		echo '<script language="javascript">alert("修改失败");history.back();</script>';
	}
	exit;
}
$rsConfig=$DB->GetRs("user_config","UserLevel","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsConfig)){
	header("location:config.php");
}else{
	if(empty($rsConfig['UserLevel'])){
		$UserLevel[0]=array(
			"Name"=>"普通会员",
			"UpIntegral"=>0,
			"ImgPath"=>""
		);
		$Data=array(
			"UserLevel"=>json_encode($UserLevel,JSON_UNESCAPED_UNICODE)
		);
		$DB->Set("user_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	}else{
		$UserLevel=json_decode($rsConfig['UserLevel'],true);
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
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	K.create('textarea[name="Description"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=coupon',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', 'undo', 'redo', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link' , '|', 'preview']
	});
})
</script>
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
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
        <li class=""><a href="config.php">基本设置</a>
          <dl>
            <dd class="first"><a href="lbs.php">一键导航设置</a></dd>
          </dl>
        </li>
        <li class=""> <a href="user_list.php">会员管理</a>
          <dl>
            <dd class="first"><a href="user_level.php">会员等级设置</a></dd>
            <dd class=""><a href="user_profile.php">会员注册资料</a></dd>
            <dd class=""><a href="card_benefits.php">会员权利说明</a></dd>
            <dd class=""><a href="user_list.php">会员管理</a></dd>
          </dl>
        </li>
        <li class=""> <a href="card_config.php">会员卡设置</a></li>
        <li class="cur"> <a href="coupon_config.php">优惠券</a>
          <dl>
            <dd class="first"><a href="coupon_config.php">优惠券设置</a></dd>
            <dd class=""><a href="coupon_list.php">优惠券管理</a></dd>
            <dd class=""><a href="coupon_list_logs.php">优惠券使用记录</a></dd>
          </dl>
        </li>
        <li class=""> <a href="gift_orders.php">礼品兑换</a>
          <dl>
            <dd class="first"><a href="gift.php">礼品管理</a></dd>
            <dd class=""><a href="gift_orders.php">兑换订单管理</a></dd>
          </dl>
        </li>
        <li class=""><a href="business_password.php">商家密码设置</a></li>
        <li class=""><a href="message.php">消息发布管理</a></li>
      </ul>
    </div>
    <div id="coupon_list" class="r_con_wrap">
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
      <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
      <script language="javascript">$(document).ready(user_obj.coupon_list_init);</script>
      <form id="coupon_list_form" class="r_con_form" method="post" action="coupon_edit.php">
        <div class="rows">
          <label>触发关键词</label>
          <span class="input">
          <input type="text" class="form_input" name="Keywords" value="<?php echo $rsCoupon["Coupon_Keywords"] ?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息标题</label>
          <span class="input">
          <input type="text" class="form_input" name="Title" value="<?php echo empty($Material_Json["Title"])?'':$Material_Json["Title"] ?>" maxlength="100" size="35" notnull />
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息封面</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input id="ImgUpload" name="ImgUpload" type="file">
            </div>
            <div class="tips">图片建议尺寸：640*360px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail"><?php echo empty($Material_Json["ImgPath"])?'':'<img src="'.$Material_Json["ImgPath"].'" />' ?></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>图文消息简介</label>
          <span class="input">
          <textarea name="TextContents" class="textarea"><?php echo empty($Material_Json["TextContents"])?'':$Material_Json["TextContents"] ?></textarea>
          <span class="tips">显示在图文封面下方</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>优惠券名称</label>
          <span class="input">
          <input name="Subject" value="<?php echo $rsCoupon["Coupon_Subject"] ?>" type="text" class="form_input" size="40" maxlength="100" notnull>
          <font class="fc_red">*</font></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>优惠券图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input id="PhotoUpload" name="PhotoUpload" type="file">
            </div>
            <div class="tips">图片建议尺寸：640*360px</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="PhotoDetail"><?php echo empty($rsCoupon["Coupon_PhotoPath"])?'':'<img src="'.$rsCoupon["Coupon_PhotoPath"].'" />' ?></div>
          </span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>适用于</label>
          <span class="input time">
          <select name="UseArea" onChange="if(this.value==1){document.getElementById('store_part').style.display='';}else{document.getElementById('store_part').style.display='none';}">
            <option value="0"<?php echo $rsCoupon["Coupon_UseArea"]==0 ? " selected" : "";?>>实体店</option>
            <option value="1"<?php echo $rsCoupon["Coupon_UseArea"]==1 ? " selected" : "";?>>微商城</option>
          </select>
          </span>
          <div class="clear"></div>
        </div>
        <div id="store_part" style="display:<?php echo $rsCoupon["Coupon_UseArea"]==0 ? "none" : "";?>">
        	<div class="rows">
            	<label>优惠方式</label>
                <span class="input">
                	<!--<input type="radio" name="UseType" value="0"<?php echo $rsCoupon["Coupon_UseType"]==0 ? " checked" : "";?>/>折扣 <input name="Discount" class="form_input" value="<?php echo $rsCoupon["Coupon_Discount"];?>" size="5" /> <font class="tips">在0-1之间，1为不打折</font>
					<div class="blank6"></div>-->
                    <input type="hidden" name="UseType" value="1" />抵现金 ￥<input name="Cash" class="form_input" value="<?php echo $rsCoupon["Coupon_Cash"];?>" size="5" />
                </span>
                <div class="clear"></div>
            </div>
            <div class="rows">
            	<label>使用条件</label>
                <span class="input">￥<input name="Condition" class="form_input" value="<?php echo $rsCoupon["Coupon_Condition"];?>" size="5" /> <font class="tips">消费满一定金额才可以使用</font></span>
                <div class="clear"></div>
            </div>
        </div>
        <div class="rows">
          <label>可用次数</label>
          <span class="input">
          <select name="UsedTimes">
            <option value="-1">不限</option>
            <?php
				for($i=1;$i<=100;$i++){
				  echo '<option value="'.$i.'"'.($rsCoupon['Coupon_UsedTimes']==$i?' selected':'').'>'.$i.'次</option>';
				}
			?>
          </select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>领取条件</label>
          <span class="input time">
          <select name="UserLevel">
            <option value="-1">不限</option>
            <?php foreach($UserLevel as $k=>$v){
			  echo '<option value="'.$k.'"'.($rsCoupon['Coupon_UserLevel']==$k?' selected':'').'>'.$v['Name'].'</option>';
		  }?>
          </select>
          <span class="tips">只有此等级的会员才可领取和使用本优惠券</span> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>有效时间</label>
          <span class="input time">
          <input name="Time" type="text" value="<?php echo date("Y/m/d H:i:s",$rsCoupon["Coupon_StartTime"])." - ".date("Y/m/d H:i:s",$rsCoupon["Coupon_EndTime"]) ?>" class="form_input" size="40" readonly="readonly" notnull />
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>详细内容</label>
          <span class="input">
          <textarea name="Description" style="width:500px; height:300px;"><?php echo $rsCoupon["Coupon_Description"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="提交保存" name="submit_btn">
          <a href="" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo empty($Material_Json["ImgPath"])?'':$Material_Json["ImgPath"] ?>" />
        <input type="hidden" id="PhotoPath" name="PhotoPath" value="<?php echo $rsCoupon["Coupon_PhotoPath"] ?>" />
        <input type="hidden" name="CouponID" value="<?php echo $rsCoupon["Coupon_ID"] ?>">
      </form>
    </div>
  </div>
</div>
</body>
</html>