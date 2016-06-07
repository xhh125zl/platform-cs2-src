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
    <div class='contents' id="description" style="background:#FFF"><?php echo $rsArticle["Article_Description"] ?></div>
    <div class='share'><span class='friend'>发送给好友</span><span class='quan'>分享到朋友圈</span></div>
    <div class='share_layer'><img src='/static/api/web/images/share.png' /></div>
    <input type="hidden" name="ShareTitle" value="<?php echo $rsArticle["Article_Title"] ?>" />
  </div>
</div>
<div id="footer_points"></div>
<footer id="footer">
  <ul>
	<?php
		$i=0;
		$DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc limit 0,4");
		while($rsColumn=$DB->fetch_assoc()){
			$i++;
			$html = $i==1 ? '<li class="first">' : '<li>';
			echo $html.'<a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><img src="/static/api/web/skin/default/images/nav-dot.png" />'.$rsColumn["Column_Name"].'</a></li>';
	}?>
  </ul>
</footer>
<script language="javascript">$(document).ready(function(){$('#support').css('bottom', 50);});</script>
<?php require_once('footer.php');?>