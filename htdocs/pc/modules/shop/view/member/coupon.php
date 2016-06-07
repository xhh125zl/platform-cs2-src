<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/member_coupon.js"></script>
<script>
	var ajax_url = '<?php echo url('member/coupon');?>';
	$(document).ready(function(){
		member_coupon_obj.member_coupon_init();
	});
</script>
<div class="comtent">
    <?php include(dirname(__DIR__) . '/home/_menu.php');?>
</div>
<div class="breadcrumb">
    <?php if(!empty($output['Bread'])){?>
    <?php foreach($output['Bread'] as $link => $name){?>
    <span><a href="<?php echo $link;?>"><?php echo $name;?></a></span> &nbsp;>&nbsp;
    <?php }?>
    <?php }?>
</div>
<div class="my_con">
    <div class="body">
        <?php include(__DIR__ . '/_left.php');?>
        <div class="body_center_pub all_dingdan my_coupons">
		    <div class="my_coupons_many">
            	<ul>
                	<li <?php if($output['TypeID'] == 0 || $output['TypeID'] == 2){?>class="my_coupons_manyfocus"<?php }?>><a href="<?php echo url('member/coupon');?>">我的优惠券</a></li>
                    <li <?php if($output['TypeID'] == 1){?>class="my_coupons_manyfocus"<?php }?>><a href="<?php echo url('member/coupon', array('TypeID'=>1));?>">领取优惠券</a></li>
                </ul>
            </div>
		    <div class="my_coupons_form">
			<?php if($output['TypeID'] != 1){?>
            	<div class="my_coupons_choose">
                	<a href="<?php echo url('member/coupon', array('TypeID'=>0));?>" <?php if($output['TypeID'] == 0){?>class="ccur"<?php }?>>可使用</a><a href="<?php echo url('member/coupon', array('TypeID'=>2));?>" <?php if($output['TypeID'] == 2){?>class="ccur"<?php }?>>已过期</a>
                </div>
			<?php }?>
                <div class="coupons"></div>
            </div>
			<input type="hidden" name="page" value="1" />
            <div class="fanye fanye_myshoucang">
                <div class="fy1"> 
				    <a href="javascript:;" title="上一页" id="up"><</a> 
				    <span><i id="cur_page">0</i>/<b id="total_page">0</b></span> 
					<a href="javascript:;" title="下一页" id="down">></a>
					<i>到</i>
                    <input type="text" id="text" maxlength="2" />
                    <i>页</i>
					<a id="submit" href="javascript:;">跳转</a> 
				</div>
            </div>
			<div class="clear"></div>
        </div>
    </div>
</div>