<?php

$DB->showErr=false;
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$rsConfig=$DB->GetRs("kf_config","*","where Users_ID='".$_SESSION["Users_ID"]."'");

if($_POST){
	
	$Flag=$DB->Set("kf_config",array("Wx_keyword"=>$_POST["Keywords"]),"where Users_ID='".$_SESSION["Users_ID"]."'");
	if($Flag){
		echo '<script language="javascript">alert("设置成功");window.location="weixin_config.php";</script>';
	}else{
		echo '<script language="javascript">alert("设置失败");history.back();</script>';
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
<!--/*edit在线客服20160419--start--*/-->
<style>
    .r_con_table td{
        text-align: left;
    }
    .kftable td{
        border-bottom: 0px solid; 
    }
    .kftable  td{
        border-right: 0px solid;
     }
</style>
<!--/*edit在线客服20160419--end--*/-->
</head>

<body>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/kf.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/kf.js'></script>
    <script language="javascript">$(document).ready(kf_obj.web_init);</script>
    <div class="r_nav">
      <ul>
        <li><a href="config.php">客服设置</a></li>
		<li class="cur" ><a href="weixin_config.php">微信客服设置</a></li>
      </ul>
    </div>
    <div class="r_con_config r_con_wrap">
      <form action="weixin_config.php" method="post" id="config_form">
        <table align="center" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><h1><strong>触发信息设置</strong></h1>
              <div class="reply_msg">
                <div class="m_left"> <span class="fc_red">*</span> 触发关键词<span class="tips_key">（有多个关键词请用 <font style="color:red">"|"</font> 隔开）</span><br />
                  <input type="text" class="input" name="Keywords" value="<?php echo !empty($rsConfig['Wx_keyword']) ? $rsConfig['Wx_keyword'] : '';?>" maxlength="100" notnull />
                  <br />
                </div>
                <div class="clear"></div>
              </div>
              <input type="hidden" id="ImgPath" name="ImgPath" value="" /></td>
          </tr>
        </table>
        <div class="submit">
          <input type="submit" name="submit_button" value="提交保存" />
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>