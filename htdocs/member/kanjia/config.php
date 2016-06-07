<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
require_once('vertify.php');
$json=$DB->GetRs("wechat_material","*","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='kanjia' and Material_TableID=0 and Material_Display=0");

if(empty($json)){
	$Material=array(
		"Title"=>"微砍价",
		"ImgPath"=>"/static/api/images/cover_img/kanjia.jpg",
		"TextContents"=>"",
		"Url"=>"/api/".$_SESSION["Users_ID"]."/kanjia/"
	);
	
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Material_Table"=>"kanjia",
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

$rsKeyword=$DB->GetRs("wechat_keyword_reply","*","where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='kanjia' and Reply_TableID=0 and Reply_Display=0");
if(empty($rsKeyword)){
	$MaterialID=empty($json['Material_Json'])?$MaterialID:$json['Material_Json'];
	$Data=array(
		"Users_ID"=>$_SESSION["Users_ID"],
		"Reply_Table"=>"kanjia",
		"Reply_TableID"=>0,
		"Reply_Display"=>0,
		"Reply_Keywords"=>"微砍价",
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
	
	$flag=$flag;
	
	$Data=array(
		"Reply_Keywords"=>$_POST["Keywords"],
		"Reply_PatternMethod"=>isset($_POST["PatternMethod"])?$_POST["PatternMethod"]:0
	);
	$Set=$DB->Set("wechat_keyword_reply",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Reply_Table='kanjia' and Reply_TableID=0 and Reply_Display=0");
	$flag=$flag&&$Set;
	
	$Material=array(
		"Title"=>$_POST["Title"],
		"ImgPath"=>$_POST["ImgPath"],
		"TextContents"=>"",
		"Url"=>"/api/".$_SESSION["Users_ID"]."/kanjia/"
	);
	$Data=array(
		"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE)
	);
	$Set=$DB->Set("wechat_material",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='kanjia' and Material_TableID=0 and Material_Display=0");
	$flag=$flag&&$Set;
	if($flag){
		mysql_query("commit");
		$Data=array(
			"status"=>1,
			"url"=>$_SERVER['HTTP_REFERER'].'?t='.time(),
			"msg"=>"保存成功，继续修改？"
		);
	}else{
		mysql_query("roolback");
		$Data=array(
			"status"=>0,
			"msg"=>"保存失败"
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
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
    <link href='/static/member/css/kanjia.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/kanjia.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="config.php">基本设置</a></li>
        <li ><a href="activity_list.php">活动管理</a></li>
        <li ><a href="orders.php">订单管理</a></li>
        <li ><a href="commit.php">评论管理</a></li>
        
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">
	$(document).ready(function(){
		global_obj.config_form_init();
		kanjia_obj.kanjia_config_init();
	});
	</script>
    <div id="albums_config" class="r_con_config r_con_wrap">
      <form action="config.php" method="post" id="config_form">
        <table border="0" cellpadding="0" cellspacing="0">
          
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0">
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
                    <input type="radio" name="PatternMethod" value="0"<?php echo empty($rsKeyword["Reply_PatternMethod"])?" checked":""; ?> />
                    精确匹配<span class="tips">（输入的文字和此关键词一样才触发）</span></div>
                  <div class="input">
                    <input type="radio" name="PatternMethod" value="1"<?php echo $rsKeyword["Reply_PatternMethod"]==1?" checked":""; ?> />
                    模糊匹配<span class="tips">（输入的文字包含此关键词就触发）</span></div>
                  <br />
                  <br />
                  <span class="fc_red">*</span> 图文消息标题<br />
                  <input type="text" class="input" name="Title" value="<?php echo $rsMaterial["Title"] ?>" maxlength="100" notnull />
                </div>
                <div class="m_right"> <span class="fc_red">*</span> 图文消息封面<span class="tips">（大图尺寸建议：640*360px）</span><br />
                  <div class="file">
                    <input id="ImgUpload" name="ImgUpload" type="file">
                  </div>
                  <br />
                  <div class="img" id="ImgDetail"><img src="<?php echo empty($rsMaterial["ImgPath"])?"/api/images/cover/tuan.jpg":$rsMaterial["ImgPath"]; ?>" width="640" height="360"></div>
                </div>
                <div class="clear"></div>
              </div>
              <input type="hidden" id="ImgPath" name="ImgPath" value="<?php echo $rsMaterial["ImgPath"] ?>" /></td>
          </tr>
        </table>
        <div class="submit">
          <input type="submit" name="submit_button" value="提交保存" />
        </div>
        <input type="hidden" name="action" value="config">
      </form>
    </div>
  </div>
</div>
</body>
</html>