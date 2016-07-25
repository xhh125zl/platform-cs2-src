<?php
$KfIco = '';
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1");
$KfIco = empty($kfConfig["KF_Icon"]) ? '' : $kfConfig["KF_Icon"];
?>
<?php if(!empty($kfConfig)){?>
<script language='javascript'>var KfIco='<?php echo $KfIco;?>'; var OpenId='<?php echo $_SESSION["OpenID"];?>'; var UsersID='<?php echo $UsersID;?>'; </script>
<script type='text/javascript' src='/kf/js/webchat.js?t=<?php echo time();?>'></script>
<?php }?>
<footer id="footer">
  <ul>
    <ul>
		<li class="home"><a href="<?=$shop_url?>"></a></li>
		<li class="category23"><a href="<?=$shop_url?>allcategory/0/" ></a></li>
		<li class="cart"><a href="<?=$shop_url?>cart/"></a></li>        
		<li class="member"><a href="<?=$shop_url?>member/"></a></li>
	</ul>
  </ul>
</footer>