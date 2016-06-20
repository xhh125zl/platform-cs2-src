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
<div id="footer_points"></div>
<footer id="footer">
	<ul>
	 <li class="footer01"><a href="/api/<?php echo $UsersID;?>/reserve/">在线预约</a></li><li class="footer02"><a href="tel:<?php echo $rsConfig['CallPhoneNumber'];?>">客服热线</a></li><li class="footer03"><a href="/api/<?php echo $UsersID ?>/web/lbs/">导航</a></li><div class="clear"></div>
	</ul>
</footer>
<script language="javascript">$(document).ready(function(){$('#support').css('bottom', 50);});</script>
<?php require_once('footer.php');?>