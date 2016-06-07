<?php require_once('header.php');?>
<div id="web_page_contents">
  <div class="wrap" id="column">
    <img src=""  class="shareimg"/>
    <div class="contents" id="description"><?php echo $rsArticle["Article_Description"] ?></div>
    <div class="share"><span class="friend">发送给好友</span><span class="quan">分享到朋友圈</span></div>
    <div class="share_layer"><img src='/static/api/web/images/share.png' /></div>
    <input type="hidden" name="ShareTitle" value="<?php echo $rsArticle["Article_Title"] ?>" />
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