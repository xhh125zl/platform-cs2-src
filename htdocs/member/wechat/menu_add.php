<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/support/sysurl_helpers.php');
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if($_POST){
	$Data=array(
		"Menu_Index"=>$_POST['Index'],
		"Menu_Name"=>$_POST["Name"],
		"Menu_ParentID"=>$_POST["ParentID"],
		"Menu_MsgType"=>$_POST["MsgType"]==3 ? 0 : $_POST["MsgType"],
		"Menu_TextContents"=>$_POST["MsgType"]==3 ? 'myqrcode' : $_POST['TextContents'],		
		"Menu_MaterialID"=>empty($_POST['MaterialID'])?0:$_POST['MaterialID'],
		"Menu_Url"=>$_POST['Url'],
		"Users_ID"=>$_SESSION["Users_ID"]
		
	);
	$Flag=$DB->Add("wechat_menu",$Data);
	if($Flag){
		echo '<script language="javascript">alert("添加成功");window.location="menu.php";</script>';
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
    <script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
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
      <script language="javascript">$(document).ready(wechat_obj.menu_init);</script>
      <div class="m_menu">
        <div class="m_righter">
          <form action="menu_add.php" method="post" id="menu_form">
            <h1>添加菜单</h1>
             <div class="opt_item">
              <label>菜单排序：</label>
              <span class="input">
              <select name="Index">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>菜单名称：</label>
              <span class="input">
              <input type="text" name="Name" value="" class="form_input" size="15" maxlength="15" notnull />
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>添加到：</label>
              <span class="input">
              <select name="ParentID">
                <option value="0">一级菜单</option>
<?php $DB->get("wechat_menu","*","where Users_ID='".$_SESSION["Users_ID"]."' and Menu_ParentID=0 order by Menu_Index asc");
while($rsPmenu=$DB->fetch_assoc()){
	echo '<option value="'.$rsPmenu["Menu_ID"].'">'.$rsPmenu["Menu_Name"].'</option>';
}?>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>消息类型：</label>
              <span class="input">
              <select name="MsgType" id="msgtype">
                <option value="0" selected>文字消息</option>
                <option value="1" >图文消息</option>
                <option value="2" >连接网址</option>
				<option value="3">我的二维码</option>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item" id="menu0">
              <label>文字内容：</label>
              <span class="input">
              <textarea name="TextContents"></textarea>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item" id="menu1" style="display:none;">
              <label>图文内容：</label>
              <span class="input">
              <select name='MaterialID'>
                <option value=''>--请选择--</option>
                <optgroup label='---------------系统业务模块---------------'></optgroup>
                <?php
                    foreach($sys_material as $value){
                        echo '<option value="'.$value['Material_ID'].'">'.$value['Title'].'</option>';
                    }
                ?>
                <optgroup label="---------------自定义图文消息---------------"></optgroup>
                <?php
                    foreach($diy_material as $value){
                        echo '<option value="'.$value['Material_ID'].'">'.($value['Material_Type']?'【多图文】':'【单图文】').$value['Title'].'</option>';
                    }
                ?>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item" id="menu2" style="display:none;">
              <label>链接网址：</label>
              <span class="input">
              <input type="text" name="Url" value="" class="form_input" size="30" maxlength="200" id="menu_url" /><img src="/static/member/images/ico/search.png" style="width:22px; height:22px; margin:0px 0px 0px 5px; vertical-align:middle; cursor:pointer" id="btn_select_url" />
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="添加菜单" />
              </span>
              <div class="clear"></div>
            </div>
          </form>
        </div>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>