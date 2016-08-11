<style>
.cart{position:relative;}
.cart b{background:red; border-radius: 50%;display: block;height: 15px;position: absolute;right: 15px;top: 5px;width: 15px;font-size:12px;text-align:center;line-height:15px;color:#ffffff;}
</style>
<?php require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/substribe.php');?>
<div id="footer_points"></div>
<?php
$DefaultMenu = array(
    'menu' => array(
        array(
            'menu_name' => '首页',
            'isDefault' => basename($_SERVER['REQUEST_URI']) == "shop" ? 1 : 0,
            'login_menu_name' => '',
            'icon' => '/static/api/distribute/images/' . (basename($_SERVER['REQUEST_URI']) == "shop" ? 'home_cur.png' : 'home.png'),
            'menu_href' => '/api/' . $UsersID . '/shop/',
            'login_menu_href' => '/api/' . $UsersID . '/shop/',
            'bind_action_attr' => 0,
            'menu_order' => '1'
        ),
        array(
            'menu_name' => '我要分销',
            'isDefault' => basename($_SERVER['REQUEST_URI']) == "distribute" ? 1 : 0,
            'login_menu_name' => '分销中心',
            'icon' => '/static/api/distribute/images/' . (basename($_SERVER['REQUEST_URI']) == "distribute" ? 'sitemap_cur.png' : 'sitemap.png'),
            'menu_href' => '/api/' . $UsersID . '/distribute/join/',
            'login_menu_href' => '/api/' . $UsersID . '/distribute/',
            'bind_action_attr' => 1,
            'menu_order' => '2'
        ),
        array(
            'menu_name' => '购物车',
            'isDefault' => basename($_SERVER['REQUEST_URI']) == "cart" ? 1 : 0,
            'login_menu_name' => '',
            'icon' => '/static/api/distribute/images/' . (basename($_SERVER['REQUEST_URI']) == "cart" ? 'cart_cur.png' : 'cart.png'),
            'menu_href' => '/api/' . $UsersID . '/shop/cart/',
            'login_menu_href' => '/api/' . $UsersID . '/shop/cart/',
            'bind_action_attr' => 2,
            'menu_order' => '3'
        ),
        array(
            'menu_name' => '个人中心',
            'isDefault' => basename($_SERVER['REQUEST_URI']) == "member" ? 1 : 0,
            'login_menu_name' => '',
            'icon' => '/static/api/distribute/images/' . (basename($_SERVER['REQUEST_URI']) == "member" ? 'user_cur.png' : 'user.png'),
            'menu_href' => '/api/' . $UsersID . '/shop/member/',
            'login_menu_href' => '/api/' . $UsersID . '/shop/member/',
            'bind_action_attr' => 0,
            'menu_order' => '4'
        )
    )
);

$rsMenuConfig = $DB->GetRs('shop_config', 'ShopMenuJson', ' WHERE  Users_ID="' . $UsersID . '"');

// $ShopMenu = empty(json_decode($rsMenuConfig['ShopMenuJson'], TRUE)) ? $DefaultMenu : json_decode($rsMenuConfig['ShopMenuJson'], TRUE);
$ShopMenuJsons = json_decode($rsMenuConfig['ShopMenuJson'], TRUE);
if (empty($ShopMenuJsons)) {
    $ShopMenu = $DefaultMenu;
} else {
    $ShopMenu = $ShopMenuJsons;
    foreach ($ShopMenu['menu'] as $k => $v) {
        $ShopMenu['menu']['isDefault'] = basename($_SERVER['REQUEST_URI']) == basename($v['login_menu_href']) ? 1 : 0;
    }
}
?>
<footer id="footer">  
  <ul class="list-group" id="footer-nav">
		<?php foreach ($ShopMenu['menu'] as $k => $v) : ?>
		<li>
			<?php if($v['bind_action_attr'] == 1 && $distribute_flag):?>
				<a href="<?php echo $v['login_menu_href']; ?>" style="background:url(<?php echo $v['icon']; ?>) center center no-repeat;<?=isset($v['isDefault']) && $v['isDefault'] ?"color:#F36767":"" ?>"><?php echo $v['login_menu_name']; ?></a>
			<?php else: ?>
				<a href="<?php echo $v['menu_href']; ?>" style="background:url(<?php echo $v['icon']; ?>) center center no-repeat;<?=isset($v['isDefault']) && $v['isDefault'] ?"color:#F36767":"" ?>"><?php echo $v['menu_name']; ?></a>
			<?php endif; ?>

			<?php if($v['bind_action_attr'] == 2): ?>
				<?php 
					$car_num = 0;
					if(!empty($_SESSION[$UsersID.'CartList'])) {
						$sessionCart = json_decode($_SESSION[$UsersID.'CartList'],true);
						foreach($sessionCart as $key_first => $value_first) {
							foreach($value_first as $key_second => $value_second) {
								foreach($value_second as $key_third => $value_third) {
									$car_num += $value_third['Qty'];
								}
							}
						}
					}
				?>
				<b <?php if(empty($car_num)){?>style="display:none"<?php }?>><?php echo $car_num;?></b>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
  </ul>
</footer>
 
<?php
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1 and KF_Code<>''");
if($kfConfig){
	echo htmlspecialchars_decode($kfConfig["KF_Code"],ENT_QUOTES);
}
?>

<?php if($rsConfig["CallEnable"] && $rsConfig["CallPhoneNumber"]){?>
<script language='javascript'>var shop_tel='<?php echo $rsConfig["CallPhoneNumber"];?>';</script>
<script type='text/javascript' src='/static/api/shop/js/tel.js?t=<?php echo time();?>'></script>
<?php }?>

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
<div class='conver_favourite'><img src="/static/api/images/global/share/favourite.png" /></div>