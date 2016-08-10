<style>
*, *::before, *::after {
	box-sizing:content-box;
	.footer li{width:33%;}
}
</style>
<div class="footer_blank"></div>
<div style="bottom: 0px;" class="footer clearfix">
	<ul style="margin:0;padding:0;box-sizing:content-box;">	    
		<li class="f_home"><a href="<?=isset($_SESSION["Index_URI"])?$_SESSION["Index_URI"]:'' ?>"><i></i>云购</a></li>
		<li class="f_single" style="display:none;"><a href="<?php echo $cloud_url;?>lottery/"><i></i>最新揭晓</a></li>
		<li class="f_car"><a id="btnCart" href="<?php echo $cloud_url;?>cart/"><i>
		<?php 
			$car_num = 0;
			if(!empty($_SESSION[$UsersID."CloudCart"]) && $_SESSION[$UsersID."CloudCart"] != 'null'){
				$sessionCart = json_decode($_SESSION[$UsersID."CloudCart"],true);
				foreach($sessionCart as $k_ProductsID => $v){
					$car_num += $v[0]['Qty'];
				}
			}
		?>
		<b <?php if(empty($car_num)){?>style="display:none"<?php }?>><?php echo $car_num;?></b>
		</i>购物车</a></li>
		<li class="f_personal"><a href="/api/<?php echo $UsersID;?>/cloud/member/"><i></i>我的云购</a></li>
	</ul>
</div>