<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
if(isset($_GET['OpenId'])){
	$_SESSION[$UsersID.'OpenID']=$_GET['OpenId'];
}else{
	echo '缺少必要的参数';
	exit;
}

$r = $DB->GetRs("kf_message","*","where Open_ID='".$_SESSION[$UsersID.'OpenID']."' and Users_ID='".$UsersID."'");
//判断是否有记录
if($r){//有
	$UserId = $r["Message_ID"];
	$rsKF=$DB->GetRs("kf_account","*","where Account_Name='".$r["KF_Account"]."' and Users_ID='".$UsersID."'");
	if(!$rsKF){//找不到相关客服人员，重新分配
		$rsKF=$DB->GetRs("kf_account","*","where Users_ID='".$UsersID."' order by Account_Chat ASC");
		if(!$rsKF){
			echo '暂无设置客服人员！';
			exit;
		}
	}
	$KfId = $rsKF["Account_ID"];
}else{//没有记录，创建聊天记录
	$rsKF=$DB->GetRs("kf_account","*","where Users_ID='".$UsersID."' order by Account_Chat ASC");
	if(!$rsKF){
		echo '暂无设置客服人员！';
		exit;
	}else{
		$Data = array(
			"Open_ID"=>$_SESSION[$UsersID.'OpenID'],
			"Users_ID"=>$UsersID,
			"KF_Account"=>$rsKF["Account_Name"],
			"Message_CreateTime"=>time()
		);
		$Flag = $DB->Add("kf_message",$Data);
		$UserId = $DB->insert_id();
		$KfId = $rsKF["Account_ID"];
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="/kf/css/chat.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/kf/js/chat.js'></script>
<script type="text/javascript">$(function(){chat.init();});</script>

</head>
<body>
<div class="list_frame">
<!-- 留言列表信息 start -->
<!-- 留言列表信息 end -->
</div>
<div class="reply_footer">
    <table width="100%" cellpadding="0" cellspacing="0">
       <tr>
        <td width="40" align="center">
         <div class="msg_icon" id="face_icon"><img src="/kf/images/main/010.png" /></div>
        </td>
        <td width="40" align="center">
         <div class="msg_icon" id="photo_icon">
          <iframe name="UploadThumb" style="display:none;" src=""></iframe>
		  <form action="/kf/upmobile.php?TableField=chat&Users_ID=<?php echo 'userchat'.$UserId;?>" method="post" enctype="multipart/form-data" target="UploadThumb">
          	<input type="hidden" name="KfId" id="KfId" value="<?php echo $KfId;?>" />
			<input type="hidden" name="UserId" id="UserId" value="<?php echo $UserId;?>" />
            <input type="hidden" name="forward" value="/kf/web/chat.php?OpenId=<?php echo $_SESSION[$UsersID.'OpenID'];?>&UsersID=<?php echo $UsersID;?>" />
            <div class="filebtn">&nbsp;</div>
            <input type="file" class="upthumb" name="upthumb" onchange="this.form.submit();" />
          </form>
		 </div>
        </td>
        <td>
         <div class="reply_frame" contenteditable="true"></div>
        </td>
        <td width="65" align="center">
         <div class="reply_btn_frame"><input class="reply_btn" type="button" value="发送" /></div>
        </td>
       </tr>
    </table>
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
    </div>
</div>
<input type="hidden" name="KfId" id="KfId" value="<?php echo $KfId;?>" />
<input type="hidden" name="UsersID" id="UsersID" value="<?php echo $UsersID;?>" />
<input type="hidden" name="UserId" id="UserId" value="<?php echo $UserId;?>" />
<input type="hidden" name="SendTyp" id="SendTyp" value="0" /><!-- 编辑状态0文字1图片 -->
</body>
</html>