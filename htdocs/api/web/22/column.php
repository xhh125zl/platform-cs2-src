<?php require_once('header.php');?>
<div id="web_page_contents">
  <div class="wrap" id="column">
    <?php
	  $column_arr = array();
	  $column_arr[] = $ColumnID;
      $DB->Get("web_column","Column_ID","where Users_ID='".$UsersID."' and Column_ParentID=".$ColumnID);
	  while($r=$DB->fetch_assoc()){
		$column_arr[] = $r["Column_ID"];
	  }
	  $columnids = implode(",",$column_arr);
    ?>
    <?php
	$DB->Get("web_article","*","where Users_ID='".$UsersID."' and Column_ID in(".$columnids.") order by Article_Index asc,Article_CreateTime desc");
if($DB->num_rows()){	
	if($rsColumn["Column_ListTypeID"]==0){
		echo '<div class="list-type-0">';
		while($rsArticle=$DB->fetch_assoc()){
			echo '<a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
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
			echo '<a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
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
			echo '<a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
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
	echo '<img src=""  class="shareimg"/><div class="contents">'.$rsColumn["Column_Description"].'</div><div class="share"><span class="friend">发送给好友</span><span class="quan">分享到朋友圈</span></div><div class="share_layer"><img src="/static/api/web/images/share.png" /></div>
<input type="hidden" name="ShareTitle" value="'.$rsColumn["Column_Name"].'" />';
}
?>
  </div>
</div>
<div id="plug_menu">
	<div>
        <div class="menu"><img src="/static/api/web/skin/22/menu.png" /></div>
        <ul>
        <?php $DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc");
		while($rsColumn=$DB->fetch_assoc()){
			echo '<li><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><div class="img"><img src="'.$rsColumn["Column_ImgPath"].'" /></div><label>'.$rsColumn["Column_Name"].'</label></a></li>';
		}
	?>
     </ul>
    </div>
</div>
<div id="cover"></div>
<script type="text/javascript">
$(function(){
	$('#plug_menu .menu img').click(function(){
	    if($("#plug_menu ul").css('display')=='none'){
            $("#plug_menu ul, #cover").show();
	    }else{
            $("#plug_menu ul, #cover").hide();
	    }
	});
	
	$('#plug_menu ul li a, #cover').click(function(){
		$("#plug_menu ul, #cover").hide();
	});
});
</script>

<div class="blank15"></div>
<script language="javascript">$(document).ready(function(){$('#support').css('bottom', 0);});</script>
<?php require_once('footer.php');?>