<?php require_once('header.php');?>
<link href='/static/api/web/skin/<?php echo $rsConfig['Skin_ID'];?>/style.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
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
<div id="footer_points"></div>
<footer id="footer">
  <ul>
<?php
				$DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc limit 0,4");
				while($rsColumn=$DB->fetch_assoc()){
					echo '<li><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><img src="'.$rsColumn["Column_ImgPath"].'" /><br />'.$rsColumn["Column_Name"].'</a></li>';
			}?>
  </ul>
</footer>
<script language="javascript">$(document).ready(function(){$('#support').css('bottom', 50);});</script>
<?php require_once('footer.php');?>