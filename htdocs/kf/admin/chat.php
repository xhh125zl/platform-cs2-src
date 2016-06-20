<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["KFA_ID"]))
{
	header("location:login.php");
}
$UserId = empty($_REQUEST["UserId"]) ? '' : $_REQUEST["UserId"];
$rsKF=$DB->GetRs("kf_account","*","where Account_ID='".$_SESSION["KFA_ID"]."'");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href="/kf/css/style.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/kf/js/webchat_inner.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">$(function(){webchat_inner.init();webchat_inner.commonLang();});</script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/kf/upload_json.php?TableField=chat&Users_ID=<?php echo 'userchat'.$UserId;?>',
		fileManagerJson : '/kf/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('.input-file').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				clickFn : function(url, title, width, height, border, align){
					K('.reply_frame').html('<img src="'+url+'" width="130" />');
					K('#SendTyp').val(1);
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
  	<div class="r_nav"><div class="r_title">对话框(<font style="color:#ff0000; font-size:12px;">用户<?php echo $UserId;?></font>)</div></div>
        <div class="r_con_config r_con_wrap">
        	<div class="chat_left">
                <div class="list_frame">
                	<!-- 留言列表信息 start -->
                    <!-- 留言列表信息 end -->
               	</div>
                <div class="select_type">
                	<div class="face_frame">
                    	<ul>
                          <?php
                           for($i=1; $i<=105; $i++){
						  ?>
                            <li num="<?php echo $i;?>" onMouseOver="this.style.background='#ccc'" onMouseOut="this.style.background= ''">
                            	<img src="/kf/images/expression/<?php echo $i;?>.gif" />
                            </li>
                          <?php }?>
                        </ul>
                    </div>
                    <div class="msg_icon" id="face_icon"><img src="/kf/images/main/bicon5.jpg" /></div>
                    <div class="msg_icon" id="photo_icon">
                    <?php if($UserId){?>
                 		<span class="input-file">
                        <img src="/kf/images/main/bicon2.jpg" /></span>
                 	<?php }else{?>
                    	<img src="/kf/images/main/bicon2.jpg" />
                    <?php }?>
                    </div>
                    <div class="msg_icon3">消息记录</div>
                </div>
            <div class="reply_frame" contenteditable="true"></div>
                <div class="reply_btn_frame">
                 <?php if($UserId){?>
                 	<div class="reply_cancel" onClick="webchat_inner.cancelSendImg()">取消</div>	
                    <input class="reply_btn" type="button" value="发送">
                	<div class="reply_notice">快捷键发送Ctrl+Enter</div>
                 <?php }?>
                </div>
            </div>
            <div class="chat_right">
            	<div class="cr_bar">
                	<div class="cr_lbtn"><img src="/kf/images/main/lbtn.png" /></div>
                    <div class="cr_frame">
                    	<ul>
                        	<li class="active">常用语</li>
                            <li>聊天记录</li>
                        </ul>
                    </div>
                    <div class="cr_rbtn"><img src="/kf/images/main/rbtn.png" /></div>
                </div>
                <div class="info_item" id="item1">
                	<!-- 常用语 start -->
              <div id="info_item_1" class="info_item_frame">
                    	<div class="info_list">
                        	<table cellpadding="0" cellspacing="0" border="0" width="92%" id="info_tb">
                            	<tr id="line1" bgcolor="#F1F1F1">
                                	<td width="67%" height="25" class="t1"><strong>常用语</strong></td>
                                    <td width="25%" align="center"><strong>操作</strong></td>
                                </tr>
                                <?php
                                $DB->Get("kf_language","*","where Users_ID='".$rsKF["Users_ID"]."' and KF_Account='".$rsKF["Account_Name"]."' order by Lan_ID desc");
								while($r=$DB->fetch_assoc()){
								?>
                                <tr>
                                	<td height="28" class="t1">
                                    	<span  class="tbAddFrame hand fz_12px" onClick="webchat_inner.copyLang(this)"><?php echo $r["Lan_Content"];?></span>
                                    </td>
                                    <td align="center">
                                    	<span class="tbEdit hand" id="ed<?php echo $r["Lan_ID"];?>" onClick="webchat_inner.ctrlLangEdit(this,<?php echo $r["Lan_ID"];?>)">
                                        	<img src="/kf/images/main/mod.gif" />
                                        </span>
                                        <span class="tbDel hand" id="delbtn<?php echo $r["Lan_ID"];?>" onClick="webchat_inner.ctrlLangDel(this,<?php echo $r["Lan_ID"];?>)">
                                        	<img src="/kf/images/main/del.gif" />
                                        </span>
                                    </td>
                                </tr>
                                <?php }?>
                            </table>
                        </div>
                        <div class="ctrl_info_list">
                        	<table cellpadding="0" cellspacing="0" border="0" width="98%">
                            	<tr>
                                	<td width="15%" height="36"><span class="fz_12px">常用语：</span></td>
                                    <td width="53%"><input type="text" id="usuallyMsg" value="" /></td>
                                    <td width="15%" align="center"><input type="button" id="addUsuallyBtn" value=" 添加 " /></td>
                                    <td width="15%" align="center">
                                    	<input type="button" id="cancelUsuallyBtn" value=" 取消 " onClick="webchat_inner.cancelLang(this)" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div id="info_item_2" class="info_item_frame"></div>                    
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<input type="hidden" name="KfId" id="KfId" value="<?php echo $_SESSION["KFA_ID"];?>" />
<input type="hidden" name="UsersID" id="UsersID" value="<?php echo $rsKF["Users_ID"];?>" />
<input type="hidden" name="UserId" id="UserId" value="<?php echo $UserId;?>" />
<input type="hidden" name="SendTyp" id="SendTyp" value="0" /><!-- 编辑状态0文字1图片 -->
</body>
</html>