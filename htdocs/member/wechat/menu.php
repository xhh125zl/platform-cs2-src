<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del('wechat_menu',"Users_ID='".$_SESSION["Users_ID"]."' and Menu_ID=".$_GET["MenuID"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
	
	$err_config = include $_SERVER["DOCUMENT_ROOT"].'/include/library/err_config.php';
	$rsUsers=$DB->GetRs("users","Users_WechatAppId,Users_WechatAppSecret","where Users_ID='".$_SESSION["Users_ID"]."'");
	if(empty($rsUsers["Users_WechatAppId"]) || empty($rsUsers["Users_WechatAppSecret"])){
		echo '<script language="javascript">alert("您还未设置AppId和AppSecret，请先到【微信授权配置】中进行设置");history.back();</script>';
		exit;
	}else{
		include $_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_token.class.php';
		$weixin_token = new weixin_token($DB,$_SESSION["Users_ID"]);
		$ACCESS_TOKEN = $weixin_token->get_access_token();
		if(!$ACCESS_TOKEN){
			echo '<script language="javascript">alert("发布失败");history.back();</script>';
			exit;
		}
	}
	if($_GET["action"]=="delete"){
		$json = file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$ACCESS_TOKEN);
		if($json){
			$return = json_decode($json,true);
			if(empty($return["errcode"])){
				echo '<script language="javascript">alert("菜单删除成功");window.location="menu.php";</script>';
			}else{
				echo '<script language="javascript">alert("'.$err_config["err_".$return["errcode"]].'");history.back();</script>';
			}
		}else{
			echo '<script language="javascript">alert("系统异常");window.location="menu.php";</script>';
		}
		exit;
	}elseif($_GET["action"]=="publish"){
		$Menu=array();
		$DB->get("wechat_menu","*","where Users_ID='".$_SESSION["Users_ID"]."' and Menu_ParentID=0 order by Menu_Index asc");
		$ParentMenu=array();
		while($rsPmenu=$DB->fetch_assoc()){
			$ParentMenu[]=$rsPmenu;
		}
		foreach($ParentMenu as $value){
			$DB->get("wechat_menu","*","where Users_ID='".$_SESSION["Users_ID"]."' and Menu_ParentID=".$value["Menu_ID"]." order by Menu_Index asc");
			if($DB->num_rows()){
				$Data=array(
					"name"=>$value["Menu_Name"],
					"sub_button"=>array()
				);
				while($rsMenu=$DB->fetch_assoc()){
					
					if($rsMenu["Menu_MsgType"]==0){
						$Data["sub_button"][]=array(
							"type"=>"click",
							"name"=>$rsMenu["Menu_Name"],
							"key"=>strlen($rsMenu["Menu_TextContents"])>=120 ? "changwenben_".$rsMenu["Menu_ID"] : $rsMenu["Menu_TextContents"]
						);
					}elseif($rsMenu["Menu_MsgType"]==1){
						$Data["sub_button"][]=array(
							"type"=>"click",
							"name"=>$rsMenu["Menu_Name"],
							"key"=>"MaterialID_".$rsMenu["Menu_MaterialID"]
						);
					}elseif($rsMenu["Menu_MsgType"]==2){
						$Data["sub_button"][]=array(
							"type"=>"view",
							"name"=>$rsMenu["Menu_Name"],
							"url"=>$rsMenu["Menu_Url"]
						);
					}
				}
				$Menu["button"][]=$Data;
			}else{
				if($value["Menu_MsgType"]==0){
					$Data=array(
						"type"=>"click",
						"name"=>$value["Menu_Name"],
						"key"=>strlen($value["Menu_TextContents"])>=120 ? "changwenben_".$value["Menu_ID"] : $value["Menu_TextContents"]
					);
				}elseif($value["Menu_MsgType"]==1){
					$Data=array(
						"type"=>"click",
						"name"=>$value["Menu_Name"],
						"key"=>"MaterialID_".$value["Menu_MaterialID"]
					);
				}elseif($value["Menu_MsgType"]==2){
					$Data=array(
						"type"=>"view",
						"name"=>$value["Menu_Name"],
						"url"=>$value["Menu_Url"]
					);
				}
				$Menu["button"][]=$Data;
			}
		}
	
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACCESS_TOKEN);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($Menu,JSON_UNESCAPED_UNICODE));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$return = json_decode(curl_exec($ch),true);
		if(curl_errno($ch)){
			echo '<script language="javascript">alert("'.curl_error($ch).'");history.back();</script>';
		}else{
			if(empty($return["errcode"])){
				echo '<script language="javascript">alert("菜单发布成功");window.location="menu.php";</script>';
			}else{
				echo '<script language="javascript">alert("发布失败");history.back();</script>';
			}
		}
		curl_close($ch);
		exit;
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href="/static/style.css" rel="stylesheet" type="text/css" />
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
        <li class="cur"><a href="/member/wechat/menu.php">自定义菜单设置</a></li>
        <li class=""><a href="/member/wechat/keyword_reply.php">关键词回复</a></li>
        <li class=""><a href="/member/wechat/token_set.php">微信接口配置</a></li>
      </ul>
    </div>
    <div id="wechat_menu" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script> 
      <script language="javascript">$(document).ready(wechat_obj.menu_init);</script>
      <div class="m_menu">
        <div class="tips_info"> 1. 您的公众平台帐号类型必须为<span>服务号</span>或<span>已通过认证的订阅号</span>。<br />
          2. 在微信公众平台申请接口使用的<span>AppId</span>和<span>AppSecret</span>，然后在【<a href="auth_set.php?m=wechat&a=auth">微信授权配置</a>】中设置。<br />
          3. 最多创建<span>3</span>个一级菜单，每个一级菜单下最多可以创建<span>5</span>个二级菜单，菜单最多支持两层。<br />
          4. 对菜单重新排序后，只有"<span>发布菜单</span>"后才会生效，公众平台限制了每天的发布次数，请勿频繁操作。<br />
          5. 微信公众平台规定，<span>菜单发布24小时后生效</span>。您也可先取消关注，再重新关注即可马上看到菜单。<br />
          6. 点击"<span>删除菜单</span>"操作只删除微信公众平台上的菜单，并不是删除本系统已经设置好的菜单。 </div>
        <div class="control_btn"><a href="menu_add.php" class="btn_green btn_w_120">添加菜单</a> <a href="menu.php?action=publish" class="btn_green btn_w_120">发布菜单</a>
          <input type="button" class="btn_gray" name="del_btn" value="删除菜单" onClick="location.href='menu.php?action=delete'" />
        </div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
          <tr>
            <td width="50" align="center"><strong>排序</strong></td>
            <td width="100" align="center"><strong>消息类型</strong></td>
            <td align="center"><strong>菜单名称</strong></td>
            <td width="60" align="center"><strong>操作</strong></td>
          </tr>
          <?php
$MsgType=array(0=>"文字消息",1=>"图文消息",2=>"连接网址");
$DB->get("wechat_menu","*","where Users_ID='".$_SESSION["Users_ID"]."' and Menu_ParentID=0 order by Menu_Index asc");
$ParentMenu=array();
$i=1;
while($rsPmenu=$DB->fetch_assoc()){
	$ParentMenu[$i]=$rsPmenu;
	$i++;
}
foreach($ParentMenu as $key=>$value){
	$DB->get("wechat_menu","*","where Users_ID='".$_SESSION["Users_ID"]."' and Menu_ParentID=".$value["Menu_ID"]." order by Menu_Index asc");?>
          <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';" onDblClick="location.href='menu_edit.php?MenuID=<?php echo $value["Menu_ID"]; ?>'">
            <td align="center"><?php echo $key; ?>&nbsp;&nbsp;</td>
            <td align="center"><?php echo ($value["Menu_TextContents"]=='myqrcode' && $value["Menu_MsgType"]==0) ? '我的二维码' : $MsgType[$value["Menu_MsgType"]]; ?></td>
            <td><?php echo $value["Menu_Name"]; ?></td>
            <td align="center"><a href="menu_edit.php?MenuID=<?php echo $value["Menu_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="menu.php?action=del&MenuID=<?php echo $value["Menu_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php
	$i=1;
	while($rsMenu=$DB->fetch_assoc()){
?>
          <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';" onDblClick="location.href='menu_edit.php?MenuID=<?php echo $rsMenu["Menu_ID"]; ?>'">
            <td align="center"><?php echo $key.'.'.$i; ?></td>
            <td align="center"><?php echo $MsgType[$rsMenu["Menu_MsgType"]]; ?></td>
            <td>——<?php echo $rsMenu["Menu_Name"]; ?></td>
            <td align="center"><a href="menu_edit.php?MenuID=<?php echo $rsMenu["Menu_ID"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="menu.php?action=del&MenuID=<?php echo $rsMenu["Menu_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php $i++;
	}
}?>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>