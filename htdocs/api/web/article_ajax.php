<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$UsersID=$_GET["UsersID"];
$ArticleID=$_GET["ArticleID"];
$rsArticle=$DB->GetRs("web_article","*","where Users_ID='".$UsersID."' and Article_ID=".$ArticleID);
$rsArticle["Article_Description"] = str_replace('&quot;','"',$rsArticle["Article_Description"]);
$rsArticle["Article_Description"] = str_replace("&quot;","'",$rsArticle["Article_Description"]);
$rsArticle["Article_Description"] = str_replace('&gt;','>',$rsArticle["Article_Description"]);
$rsArticle["Article_Description"] = str_replace('&lt;','<',$rsArticle["Article_Description"]);
if($rsArticle["Article_Link"]==1 && !empty($rsArticle["Article_LinkUrl"])){
	header("location:".$rsArticle["Article_LinkUrl"]);
}else{
	echo '<div class="wrap" id="column">
		<img src="" class="shareimg"/>
		<div class="contents">'.$rsArticle["Article_Description"].'</div>
		<div class="share"><span class="friend">发送给好友</span><span class="quan">分享到朋友圈</span></div>
		<div class="share_layer"><img src="/static/api/web/images/share.png" /></div>
		<input type="hidden" name="ShareTitle" value="'.$rsArticle["Article_Title"].'" />
	</div>';
}
?>