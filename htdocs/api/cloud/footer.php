<?php require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/substribe.php');?>
<?php 
	// echo '<pre>';
	// print_R($rsConfig);
	// exit;
    $flag = false;
	//if($rsConfig['Distribute_Type'] > 0){//sunchenglong
	if(isset($rsConfig['Distribute_Type']) && $rsConfig['Distribute_Type'] > 0){
		if(!empty($_SESSION[$UsersID.'User_ID'])){ 
			
			$User_ID = empty($_SESSION[$UsersID.'User_ID']) ? 0 : $_SESSION[$UsersID.'User_ID'];
			
			$rsUser=$DB->GetRs("user","Is_Distribute","where Users_ID='".$UsersID."' and User_ID=".$User_ID);
			if($rsUser['Is_Distribute'] == 1){
				$flag = true;
			}
		
		}
	}else{
		$flag = true;
	}
?>
<div class="footer_blank"></div>
<div style="bottom: 0px;" class="footer clearfix">
	<ul style="margin:0;padding:0;">
	<style>
	    .footer li{width:24%;}
	</style>
	  <li class="f_announced" ><a href="<?php echo shop_url();?>"><i></i>商城</a></li>
		<li class="f_home"><a href="<?=isset($_SESSION["Index_URI"])?$_SESSION["Index_URI"]:'' ?>"><i></i>云购</a></li>
		<li class="f_single" style="display:none;"><a href="<?php echo base_url().'api/'.$UsersID.'/cloud/';?>lottery/"><i></i>最新揭晓</a></li>
		<li class="f_car"><a id="btnCart" href="<?php echo base_url().'api/'.$UsersID.'/cloud/';?>cart/"><i>
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
<?php
$KfIco = '';
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1 and KF_Code<>''");
$kfConfig["KF_Code"] = htmlspecialchars_decode($kfConfig["KF_Code"],ENT_QUOTES);
?>

<?php if(!empty($kfConfig)){?>
<?php echo $kfConfig["KF_Code"];?>
<?php }?>
<?php if(!empty($share_flag) && $share_flag==1 && $signature<>""){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_user["Users_WechatAppId"];?>",		   
		   timestamp:<?php echo $timestamp;?>,
		   nonceStr:"<?php echo $noncestr?>",
		   url:"<?php echo $url?>",
		   signature:"<?php echo $signature;?>",
		   title:"<?php echo $share_title;?>",
		   desc:"<?php echo $share_desc;?>",
		   img_url:"<?php echo $share_img;?>",
		   link:"<?php echo $share_link;?>"
		};
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>