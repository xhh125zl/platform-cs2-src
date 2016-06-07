<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$rsReply=$DB->GetRs("wechat_attention_reply","*","where Users_ID='".$_SESSION["Users_ID"]."'");
if($_POST)
{	
	if($rsReply)
	{
		$Data=array(
			"Reply_MsgType"=>$_POST["ReplyMsgType"],
			"Reply_TextContents"=>isset($_POST["ReplyTextContents"])?$_POST["ReplyTextContents"]:"",
			"Reply_MaterialID"=>empty($_POST["ReplyMaterialID"])?0:$_POST["ReplyMaterialID"],
			"Reply_Subscribe"=>isset($_POST["ReplySubscribe"])?1:0,
			"Reply_MemberNotice"=>isset($_POST["MemberNotice"])?1:0
		);
		$Flag=$DB->Set("wechat_attention_reply",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	}else
	{
		$Data=array(
			"Reply_MsgType"=>$_POST["ReplyMsgType"],
			"Reply_TextContents"=>isset($_POST["ReplyTextContents"])?$_POST["ReplyTextContents"]:"",
			"Reply_MaterialID"=>empty($_POST["ReplyMaterialID"])?0:$_POST["ReplyMaterialID"],
			"Reply_Subscribe"=>isset($_POST["ReplySubscribe"])?1:0,
			"Users_ID"=>$_SESSION["Users_ID"],
			"Reply_MemberNotice"=>isset($_POST["MemberNotice"])?1:0
			
		);
		$Flag=$DB->Add("wechat_attention_reply",$Data);
	}
	if($Flag)
	{
		echo '<script language="javascript">alert("保存成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
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
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/wechat.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="/member/wechat/attention_reply.php">首次关注设置</a></li>
        <li class=""><a href="/member/wechat/menu.php">自定义菜单设置</a></li>
        <li class=""><a href="/member/wechat/keyword_reply.php">关键词回复</a></li>
        <li class=""><a href="/member/wechat/token_set.php">微信接口配置</a></li>
      </ul>
    </div>
    <script language="javascript">$(document).ready(wechat_obj.attention_init);</script>
    <div id="attention" class="r_con_wrap">
      <form id="attention_reply_form" class="r_con_form" method="post" action="attention_reply.php">
        <div class="rows">
          <label>回复类型</label>
          <span class="input">
          <select name="ReplyMsgType">
            <option value="0"<?php echo $rsReply["Reply_MsgType"]?"":" selected"; ?>>文字消息</option>
            <option value="1"<?php echo $rsReply["Reply_MsgType"]?" selected":""; ?>>图文消息</option>
          </select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="text_msg_row">
          <label>回复内容</label>
          <span class="input">
          <textarea name="ReplyTextContents"><?php echo $rsReply["Reply_TextContents"]; ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="img_msg_row">
          <label>回复内容</label>
          <span class="input">
          <select name='ReplyMaterialID'>
            <option value=''>--请选择--</option>
            <optgroup label='---------------系统业务模块---------------'></optgroup>
            <?php $DB->Get("wechat_material","Material_ID,Material_Table,Material_Json","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table<>'0' and Material_TableID=0 and Material_Display=0 order by Material_ID desc");
				while($rsMaterial=$DB->fetch_assoc()){
					$json=json_decode($rsMaterial['Material_Json'],true);
					echo '<option value="'.$rsMaterial['Material_ID'].'"'.($rsReply["Reply_MaterialID"]==$rsMaterial['Material_ID']?" selected":"").'>'.$json['Title'].'</option>';
				}?>
            <optgroup label="---------------自定义图文消息---------------"></optgroup>
            <?php $DB->Get("wechat_material","Material_ID,Material_Table,Material_Type,Material_Json","where Users_ID='".$_SESSION["Users_ID"]."' and Material_Table='0' and Material_TableID=0 and Material_Display=1 order by Material_ID desc");
				while($rsMaterial=$DB->fetch_assoc()){
					$json=json_decode($rsMaterial['Material_Json'],true);
					$json=$rsMaterial['Material_Type']?$json[0]:$json;
					echo '<option value="'.$rsMaterial['Material_ID'].'"'.($rsReply["Reply_MaterialID"]==$rsMaterial['Material_ID']?" selected":"").'>'.($rsMaterial['Material_Type']?'【多图文】':'【单图文】').$json['Title'].'</option>';
				}?>
          </select>
          <a href="/member/material/index.php" class="material">素材管理</a></span>
          <div class="clear"></div>
        </div>
		<div class="rows" id="img_msg_row">
          <label>成为会员提醒</label>
          <span class="input">
          <input type="checkbox" value="1" name="MemberNotice"<?php echo $rsReply["Reply_MemberNotice"]?" checked":""; ?> />
          <span class="tips">开启（开启后，用户关注公众收到的消息中会包含会员信息，例如：您好**，您已成为第***位会员。此设置仅对“文字消息”有效）</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="img_msg_row">
          <label>任意关键词</label>
          <span class="input">
          <input type="checkbox" value="1" name="ReplySubscribe"<?php echo $rsReply["Reply_Subscribe"]?" checked":""; ?> />
          <span class="tips">开启（开启后，当输入的关键字无相关的匹配内容时，则使用本设置回复）</span></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          </span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>