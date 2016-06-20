<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/support/sysurl_helpers.php');
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$ReplyID=empty($_REQUEST['ReplyID'])?0:$_REQUEST['ReplyID'];
$rsReply=$DB->GetRs("wechat_keyword_reply","*","where Reply_ID=".$ReplyID);
if($_POST){	
	if($rsReply){
		$Data=array(
			"Reply_Keywords"=>$_POST["Keywords"],
			"Reply_PatternMethod"=>$_POST["PatternMethod"],
			"Reply_MsgType"=>$_POST["ReplyMsgType"],
			"Reply_TextContents"=>isset($_POST['TextContents'])?$_POST['TextContents']:"",
			"Reply_MaterialID"=>empty($_POST['MaterialID'])?0:$_POST['MaterialID']
		);
		$Flag=$DB->Set("wechat_keyword_reply",$Data,"where Reply_ID=".$ReplyID);
	}else{
		$Data=array(
			"Reply_Table"=>0,
			"Reply_TableID"=>0,
			"Reply_Display"=>1,
			"Reply_Keywords"=>$_POST["Keywords"],
			"Reply_PatternMethod"=>$_POST["PatternMethod"],
			"Reply_MsgType"=>$_POST["ReplyMsgType"],
			"Reply_TextContents"=>isset($_POST['TextContents'])?$_POST['TextContents']:"",
			"Reply_MaterialID"=>empty($_POST['MaterialID'])?0:$_POST['MaterialID'],
			"Users_ID"=>$_SESSION["Users_ID"]
			
		);
		$Flag=$DB->Add("wechat_keyword_reply",$Data);
	}
	if($Flag){
		echo '<script language="javascript">alert("保存成功");window.location="keyword_reply.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}

$sys_material = get_sys_material($DB,$_SESSION["Users_ID"],1);//系统图文
$diy_material = get_sys_material($DB,$_SESSION["Users_ID"],0);//自定义图文
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
        <li class=""><a href="/member/wechat/attention_reply.php">首次关注设置</a></li>
        <li class=""><a href="/member/wechat/menu.php">自定义菜单设置</a></li>
        <li class="cur"><a href="/member/wechat/keyword_reply.php">关键词回复</a></li>
        <li class=""><a href="/member/wechat/token_set.php">微信接口配置</a></li>
      </ul>
    </div>
    <div id="reply_keyword" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(wechat_obj.reply_keyword_init);</script>
      <form action="keyword_edit.php" method="post" class="r_con_form">
        <div class="rows">
          <label>关键词</label>
          <span class="input">
		  <input type="text" class="form_input" size="60" name="Keywords" value="<?php echo $rsReply["Reply_Keywords"] ?>" maxlength="100" notnull />
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>匹配模式</label>
          <span class="input">
          <input type="radio" name="PatternMethod" value="0"<?php echo $rsReply["Reply_PatternMethod"]?"":" checked"; ?> />
          精确匹配<span class="tips">（用户输入的文字和此关键词一样才会触发,一般用于一个关键词.）</span><br />
          <input type="radio" name="PatternMethod" value="1"<?php echo $rsReply["Reply_PatternMethod"]?" checked":""; ?> />
          模糊匹配<span class="tips">（只要用户输入的文字包含此关键词就触）</span><br />
          </span>
          <div class="clear"></div>
        </div>
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
          <textarea name='TextContents'><?php echo $rsReply["Reply_TextContents"] ?></textarea>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="img_msg_row">
          <label>回复内容</label>
          <span class="input">
          <select name='MaterialID'>
            <option value=''>--请选择--</option>
            <optgroup label='---------------系统业务模块---------------'></optgroup>
            <?php
				foreach($sys_material as $value){
					echo '<option value="'.$value['Material_ID'].'"'.($rsReply["Reply_MaterialID"]==$value['Material_ID']?" selected":"").'>'.$value['Title'].'</option>';
				}
			?>
            <optgroup label="---------------自定义图文消息---------------"></optgroup>
            <?php
				foreach($diy_material as $value){
					echo '<option value="'.$value['Material_ID'].'"'.($rsReply["Reply_MaterialID"]==$value['Material_ID']?" selected":"").'>'.($value['Material_Type']?'【多图文】':'【单图文】').$value['Title'].'</option>';
				}
			?>
          </select>
          <a href="/member/material/index.php" class="material">图文消息管理</a></span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          <a href="keyword_reply.php" class="btn_gray">返回</a></span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="ReplyID" value="<?php echo $rsReply["Reply_ID"] ?>">
      </form>
    </div>
  </div>
</div>
</body>
</html>