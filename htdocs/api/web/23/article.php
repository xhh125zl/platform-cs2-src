<?php require_once('header.php');?>
<style>
#description img{
	width:100%;
	height:auto;
}
</style>

<div id="web_page_contents">
  <div class="wrap" id="column">
    <img src=""  class="shareimg"/>
    <div class='contents' id="description"><?php echo $rsArticle["Article_Description"] ?></div>
    <div class='share'><span class='friend'>发送给好友</span><span class='quan'>分享到朋友圈</span></div>
    <div class='share_layer'><img src='/static/api/web/images/share.png' /></div>
    <input type="hidden" name="ShareTitle" value="<?php echo $rsArticle["Article_Title"] ?>" />
  </div>
</div>
<div id="menu"><a href="#"></a></div>
<div id="menu_cover"></div>
<div id="menu_list">
   <ul>
     <?php
				$DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc");
				while($rsColumn=$DB->fetch_assoc()){
					echo '<li><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><img src="'.$rsColumn["Column_ImgPath"].'" /></a><br />'.$rsColumn["Column_Name"].'</li>';
			}?>
   </ul>
</div>
<script type="text/javascript">
    $('#header ul li').eq(0).removeClass('first');
    $('<li class="first">&nbsp;</li>').prependTo('#header ul');
	$('#menu a').click(function(){
		if($('#menu_cover').css('display')=='none'||$('#menu_list').css('display')=='none'){
			$('#menu_cover, #menu_list').slideDown(500);
		}else{
			$('#menu_cover, #menu_list').slideUp(500);
		}
		return false;
	});
	$('#menu_list, #menu_list li, #menu_list li a').click(function(){
		$('#menu_cover, #menu_list').slideUp(500);
	});
</script>
<script language="javascript">$(document).ready(function(){$('#support').css('bottom', 50);});</script>
<?php require_once('footer.php');?>