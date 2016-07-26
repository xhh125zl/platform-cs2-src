<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
  
$rsConfig=$DB->GetRs("pintuan_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsConfig)){
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"SiteName"=>"拼团",
		"CallEnable"=>0,
	);
	$DB->Add("pintuan_config",$Data);
	$rsConfig=$DB->GetRs("pintuan_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");
}
//print_r($rsConfig); 
$json=$DB->GetRs("wechat_material","*","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='pintuan' and Material_TableID=0 and Material_Display=0");
if(empty($json)){
	$Material=array(
		"Title"=>"拼团",
		"ImgPath"=>"/static/api/images/cover_img/web.jpg",
		"TextContents"=>"",
		"Url"=>"/api/".$_SESSION["Users_ID"]."/pintuan/"
	);
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Material_Table"=>"pintuan",
		"Material_TableID"=>0,
		"Material_Display"=>0,
		"Material_Type"=>0,
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE),
		"Material_CreateTime"=>time()
	);
	$DB->Add("wechat_material",$Data);
	$MaterialID=$DB->insert_id();
	$rsMaterial=$Material;
}else{
	$rsMaterial=json_decode($json['Material_Json'],true);
}
 
$rsKeyword=$DB->GetRs("wechat_keyword_reply","*","where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='pintuan' and Reply_TableID=0 and Reply_Display=0");
if(empty($rsKeyword)){
	$MaterialID=empty($json['Material_Json'])?$MaterialID:$json['Material_ID'];
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Reply_Table"=>"pintuan",
		"Reply_TableID"=>0,
		"Reply_Display"=>0,
		"Reply_Keywords"=>"拼团",
		"Reply_PatternMethod"=>0,
		"Reply_MsgType"=>1,
		"Reply_MaterialID"=>$MaterialID,
		"Reply_CreateTime"=>time()
	);
	$DB->Add("wechat_keyword_reply",$Data);
	$rsKeyword=$Data;
}
if($_POST)
{
	//开始事务定义
	$flag=true;
	$msg="";
	mysql_query("begin");
	$Data=array(
                "info"=>isset($_POST["info"])?$_POST["info"]:0,
                "is_ems"=>isset($_POST["is_ems"])?$_POST["is_ems"]:0, 
                "is_back"=>isset($_POST["is_back"])?$_POST["is_ems"]:0,
            		"SiteName"=>$_POST["SiteName"],
            		"CallEnable"=>isset($_POST["CallEnable"])?$_POST["CallEnable"]:0,
            		"CallPhoneNumber"=>$_POST["CallPhoneNumber"],
            	);
	$Set=$DB->Set("pintuan_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	$flag=$flag&&$Set;
	$Data=array(
		"Reply_Keywords"=>$_POST["Keywords"],
		"Reply_PatternMethod"=>isset($_POST["PatternMethod"])?$_POST["PatternMethod"]:0
	);
	$Set=$DB->Set("wechat_keyword_reply",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='pintuan' and Reply_TableID=0 and Reply_Display=0");
	$flag=$flag&&$Set;
	$Material=array(
		"Title"=>$_POST["Title"],
		"ImgPath"=>$_POST["ImgPath"],
		"TextContents"=>"",
		"Url"=>"/api/".$_SESSION["Users_ID"]."/pintuan/"
	);
	$Data=array(
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE)
	);
	$Set=$DB->Set("wechat_material",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='pintuan' and Material_TableID=0 and Material_Display=0");
	$flag=$flag&&$Set;
	if($flag)
	{
		mysql_query("commit");
                echo '<script language="javascript">alert("保存成功");window.location="config.php";</script>';
                exit;
	}else
	{
		mysql_query("roolback");
                 echo '<script language="javascript">alert("保存失败");window.location="config.php";</script>';
                 exit;
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
<script type="text/javascript" src="/third_party/uploadify/jquery.uploadify.min.js"></script>
<link href="/third_party/uploadify/uploadify.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <?php include 'top.php'; ?>
    <script language="javascript">
$(document).ready(function(){
	global_obj.config_form_init();
});
</script> 
    <div class="r_con_config r_con_wrap">
        <form id="" action="?" method="post">
        <input type="hidden" name="is_back" value="1"/>
        <input type="hidden" name="is_ems" value="1"/>
        <table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="50%" valign="top"><h1><span class="fc_red">*</span> <strong>拼团网名称</strong></h1>
              <input type="text" class="input" name="SiteName" value="<?php echo $rsConfig["SiteName"] ?>" maxlength="30" notnull /></td>
            <td width="50%" valign="top">
                <input type="hidden" name="CallEnable" value="1" />
              <input type="hidden" class="input" name="CallPhoneNumber" value="<?php echo empty($rsConfig["CallPhoneNumber"])?"":$rsConfig["CallPhoneNumber"]; ?>" />
            </td>
          </tr>
        </table>
        <!-- 
          <table align="center" border="0" cellpadding="0" cellspacing="0">
              <tr>
                  <td>
                      <h1><strong>拼团说明</strong></h1>
                      <div class="reply_msg">
                          <textarea name="info">
                               <?php echo empty($rsConfig["info"])?"":$rsConfig['info']; ?>
                          </textarea>
                      </div>
                  </td>
              </tr>
              
          </table> -->
        <table align="center" border="0" cellpadding="0" cellspacing="0" id="config_form">
          <tr>
            <td><h1><strong>触发信息设置</strong></h1>
              <div class="reply_msg">
                <div class="m_left"> <span class="fc_red">*</span> 触发关键词<br />
                  <input type="text" class="input" name="Keywords" value="<?php echo $rsKeyword["Reply_Keywords"] ?>" maxlength="100" notnull />
                  <br />
                  <br />
                  <br />
                  <span class="fc_red">*</span> 匹配模式<br />
                  <div class="input">
                    <label>
                      <input type="radio" name="PatternMethod" value="0"<?php echo empty($rsKeyword["Reply_PatternMethod"])?" checked":""; ?> />
                      精确匹配<span class="tips">（输入的文字和此关键词一样才触发）</span></label>
                  </div>
                  <div class="input">
                    <label>
                      <input type="radio" name="PatternMethod" value="1"<?php echo $rsKeyword["Reply_PatternMethod"]==1?" checked":""; ?> />
                      模糊匹配<span class="tips">（输入的文字包含此关键词就触发）</span></label>
                  </div>
                  <br />
                  <br />
                  <span class="fc_red">*</span> 图文消息标题<br />
                  <input type="text" class="input" name="Title" value="<?php echo $rsMaterial["Title"] ?>" maxlength="100" notnull />
                </div>
                <div class="m_right"> <span class="fc_red">*</span> 图文消息封面<span class="tips">（大图尺寸建议：640*360px，500KB以内，gif,jpg,jpeg,png格式）</span><br />
                  <div class="file">
                    <input id="ImgUpload" name="ImgUpload" type="file">
                  </div>
                  <br />
                  <div class="img" id="ImgDetail"><img src="<?php echo empty($rsMaterial["ImgPath"])?"/api/images/cover/pintuan.jpg":$rsMaterial["ImgPath"]; ?>" width="640" height="360"></div>
                </div>
                <div class="clear"></div>
              </div>
              <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo $rsMaterial["ImgPath"] ?>" /></td>
          </tr>
        </table>
        <div class="submit">
          <input type="submit" name="submit" value="提交保存" />
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>