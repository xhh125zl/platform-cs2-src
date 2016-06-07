<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$UsersID=$_GET["UsersID"];
$ColumnID=$_GET["ColumnID"];
$rsConfig=$DB->GetRs("web_config","*","where Users_ID='".$UsersID."'");
$rsColumn=$DB->GetRs("web_column","*","where Users_ID='".$UsersID."' and Column_ID=".$ColumnID);
$rsColumn["Column_Description"] = str_replace('&quot;','"',$rsColumn["Column_Description"]);
$rsColumn["Column_Description"] = str_replace("&quot;","'",$rsColumn["Column_Description"]);
$rsColumn["Column_Description"] = str_replace('&gt;','>',$rsColumn["Column_Description"]);
$rsColumn["Column_Description"] = str_replace('&lt;','<',$rsColumn["Column_Description"]);
echo '<div class="wrap" id="column">';
$DB->Get("web_article","*","where Users_ID='".$UsersID."' and Column_ID=".$ColumnID." order by Article_CreateTime desc");
if($DB->num_rows()){	
	if($rsColumn["Column_ListTypeID"]==0){
		echo '<div class="list-type-0">';
		while($rsArticle=$DB->fetch_assoc()){
			echo '<a ajax_url="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
				<div class="item">
					<div class="img"><img src="'.$rsArticle["Article_ImgPath"].'"></div>
					<div class="info">
						<h1>'.$rsArticle["Article_Title"].'</h1>
						<h2>'.$rsArticle["Article_BriefDescription"].'</h2>
					</div>
					<div class="detail">
						<span></span>
					</div>
				</div>
			</a>';
		}
		echo '</div>';
	}elseif($rsColumn["Column_ListTypeID"]==1){
		echo '<div class="list-type-1">
			<div class="list">';
		while($rsArticle=$DB->fetch_assoc()){
			echo '<a ajax_url="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
				<div class="item">
					<div>
						<ul>
							<li class="img"><img src="'.$rsArticle["Article_ImgPath"].'"></li>
							<li class="title">'.$rsArticle["Article_Title"].'</li>
						</ul>
					</div>
				</div>
			</a>';
		}
		echo '<div class="clear"></div>
			</div>
		</div>';
	}elseif($rsColumn["Column_ListTypeID"]==2){
		echo '<div class="list-type-2">';
		while($rsArticle=$DB->fetch_assoc()){
			echo '<a ajax_url="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
				<div class="item">
					<div class="info">
						<h1>'.$rsArticle["Article_Title"].'</h1>
						<h2>'.$rsArticle["Article_BriefDescription"].'</h2>
					</div>
					<div class="detail">
						<span></span>
					</div>
				</div>
			</a>';
		}
		echo '</div>';
	}
}else{
	echo '<img src="" class="shareimg"/><div class="contents">'.$rsColumn["Column_Description"].'</div><div class="share"><span class="friend">发送给好友</span><span class="quan">分享到朋友圈</span></div><div class="share_layer"><img src="/static/api/web/images/share.png" /></div>
<input type="hidden" name="ShareTitle" value="'.$rsColumn["Column_Name"].'" />';
}
echo '<input type="hidden" name="ShareTitle" value="'.$rsColumn["Column_Name"].'" />
</div>';
?>