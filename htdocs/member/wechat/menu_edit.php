<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/support/sysurl_helpers.php');
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
$MenuID=empty($_REQUEST['MenuID'])?0:$_REQUEST['MenuID'];
$rsMenu=$DB->GetRs("wechat_menu","*","where Users_ID='".$_SESSION["Users_ID"]."' and Menu_ID=".$MenuID);
$rsMenu["Menu_MsgType"] = ($rsMenu["Menu_MsgType"]==0 && $rsMenu["Menu_TextContents"]=="myqrcode") ? 3 : $rsMenu["Menu_MsgType"];
$rsMenu["Menu_TextContents"] = $rsMenu["Menu_TextContents"]=="myqrcode" ? '' : $rsMenu["Menu_TextContents"];
if($_POST)
{
	$Data=array(
		"Menu_Index"=>$_POST['Index'],
		"Menu_Name"=>$_POST["Name"],
		"Menu_ParentID"=>$_POST["ParentID"],
		"Menu_MsgType"=>$_POST["MsgType"]==3 ? 0 : $_POST["MsgType"],
		"Menu_TextContents"=>$_POST["MsgType"]==3 ? 'myqrcode' : $_POST['TextContents'],
		"Menu_MaterialID"=>empty($_POST['MaterialID'])?0:$_POST['MaterialID'],
		"Menu_Url"=>$_POST['Url'],
	);
	$Flag=$DB->Set("wechat_menu",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Menu_ID=".$MenuID);
	if($Flag)
	{
		echo '<script language="javascript">alert("修改成功");window.location="menu.php";</script>';
	}else
	{
		echo '<script language="javascript">alert("修改失败");history.back();</script>';
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
      <script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
      <script language="javascript">$(document).ready(wechat_obj.menu_init);</script>
      <div class="m_menu">
        <div class="m_righter">
          <form action="menu_edit.php" method="post" id="menu_form">
            <h1>添加菜单</h1>
            <div class="opt_item">
              <label>菜单排序：</label>
              <span class="input">
              <select name="Index">
                <option value="1"<?php echo $rsMenu["Menu_Index"]==1?" selected":""; ?>>1</option>
                <option value="2"<?php echo $rsMenu["Menu_Index"]==2?" selected":""; ?>>2</option>
                <option value="3"<?php echo $rsMenu["Menu_Index"]==3?" selected":""; ?>>3</option>
                <option value="4"<?php echo $rsMenu["Menu_Index"]==4?" selected":""; ?>>4</option>
                <option value="5"<?php echo $rsMenu["Menu_Index"]==5?" selected":""; ?>>5</option>
              </select>
              <font class="fc_red">*</font></span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>菜单名称：</label>
              <span class="input">
              <input name="MenuID" type="hidden" value="<?php echo $rsMenu["Menu_ID"] ?>">
              <input type="text" name="Name" value="<?php echo $rsMenu["Menu_Name"] ?>" class="form_input" size="15" maxlength="15" notnull />
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
	echo '<option value="'.$rsPmenu["Menu_ID"].'"'.($rsPmenu["Menu_ID"]==$rsMenu["Menu_ParentID"]?" selected":"").'>'.$rsPmenu["Menu_Name"].'</option>';
}?>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label>消息类型：</label>
              <span class="input">
              <select name="MsgType" id="msgtype">
                <option value="0"<?php echo $rsMenu["Menu_MsgType"]==0?" selected":""; ?>>文字消息</option>
                <option value="1"<?php echo $rsMenu["Menu_MsgType"]==1?" selected":""; ?>>图文消息</option>
                <option value="2"<?php echo $rsMenu["Menu_MsgType"]==2?" selected":""; ?>>连接网址</option>
				<option value="3"<?php echo $rsMenu["Menu_MsgType"]==3?" selected":""; ?>>我的二维码</option>
              </select>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item" id="menu0"<?php echo $rsMenu["Menu_MsgType"]==0?'':' style="display:none;"'; ?>>
              <label>文字内容：</label>
              <span class="input">
              <textarea name="TextContents"><?php echo $rsMenu["Menu_TextContents"] ?></textarea>
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item" id="menu1"<?php echo $rsMenu["Menu_MsgType"]==1?'':' style="display:none;"'; ?>>
              <label>图文内容：</label>
              <span class="input">
              <select name='MaterialID'>
                <option value=''>--请选择--</option>
                <optgroup label='---------------系统业务模块---------------'></optgroup>
                <?php
                    foreach($sys_material as $value){
                        echo '<option value="'.$value['Material_ID'].'"'.($rsMenu["Menu_MaterialID"]==$value['Material_ID']?" selected":"").'>'.$value['Title'].'</option>';
                    }
                ?>
                <optgroup label="---------------自定义图文消息---------------"></optgroup>
                <?php
                    foreach($diy_material as $value){
                        echo '<option value="'.$value['Material_ID'].'"'.($rsMenu["Menu_MaterialID"]==$value['Material_ID']?" selected":"").'>'.($value['Material_Type']?'【多图文】':'【单图文】').$value['Title'].'</option>';
                    }
                ?>
              </select>
              <a href="../material/index.php">素材管理</a></span>
              <div class="clear"></div>
            </div>
            <div class="opt_item" id="menu2"<?php echo $rsMenu["Menu_MsgType"]==2?'':' style="display:none;"'; ?>>
              <label>链接网址：</label>
              <span class="input">
              <input type="text" name="Url" value="<?php echo $rsMenu["Menu_Url"] ?>" class="form_input" size="30" maxlength="200" id="menu_url" /><img src="/static/member/images/ico/search.png" style="width:22px; height:22px; margin:0px 0px 0px 5px; vertical-align:middle; cursor:pointer" id="btn_select_url" />
              </span>
              <div class="clear"></div>
            </div>
            <div class="opt_item">
              <label></label>
              <span class="input">
              <input type="submit" class="btn_green btn_w_120" name="submit_button" value="修改菜单" />
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