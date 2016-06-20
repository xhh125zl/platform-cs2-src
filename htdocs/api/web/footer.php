<div id="global_support_point"></div><div id="global_support"><div class="bg"></div><?php echo $Copyright;?></div>
<?php
if(!empty($kfConfig["KF_Code"])){
$kfConfig["KF_Code"] = htmlspecialchars_decode($kfConfig["KF_Code"],ENT_QUOTES);
?>
<?php echo $kfConfig["KF_Code"];?>
<?php }?>

<img src='/static/api/images/cover_img/web.jpg' class='shareimg'/>
<?php if(!empty($share_config)){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_config["appId"];?>",   
		   timestamp:<?php echo $share_config["timestamp"];?>,
		   nonceStr:"<?php echo $share_config["noncestr"];?>",
		   url:"<?php echo $share_config["url"];?>",
		   signature:"<?php echo $share_config["signature"];?>",
		   title:"<?php echo $share_config["title"];?>",
		   desc:"<?php echo str_replace(array("\r\n", "\r", "\n"), "", $share_config["desc"]);?>",
		   img_url:"<?php echo $share_config["img"];?>",
		   link:"<?php echo $share_config["link"];?>"
		};
		
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
</body>
</html>