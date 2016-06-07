<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/address.js"></script>
<link href="<?php echo $output['_site_url'];?>/static/css/select2.css" rel="stylesheet" />
<script type='text/javascript' src="<?php echo $output['_site_url'];?>/static/js/select2.js"></script>
<script type="text/javascript" src="<?php echo $output['_site_url'];?>/static/js/location.js"></script>
<script type="text/javascript" src="<?php echo $output['_site_url'];?>/static/js/area.js"></script>
<script>
	$(document).ready(function(){
		address_obj.address_init();
	});
</script>
<style>
.select2-container, .select2-drop, .select2-search, .select2-search input{
	width:140px;
}
</style>
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
        <div class="body_center_pub all_dingdan mydizhi">
            <label>我的收货地址</label>
			<?php if(!empty($output['address_list'])){?>
			<?php foreach($output['address_list'] as $key => $address){?>
			<?php
				$Province = $output['province_list'][$address['Address_Province']];
				$City = $output['area_array']['0,'.$address['Address_Province']][$address['Address_City']];
				$Area = $output['area_array']['0,'.$address['Address_Province'].','.$address['Address_City']][$address['Address_Area']];
			?>
            <div class="dizhi" addr_id="<?php echo $address['Address_ID']?>">
                <div class="cut"><a href="javascript:;"></a></div>
                <div>
                    <ul>
                        <li><span>收货人：</span><i><?php echo $address['Address_Name']?></i></li>
                        <li><span>所在地区：</span>
						    <i><?php echo $Province . $City . $Area?></i>
						</li>
                        <li>
						    <span>地址：</span>
							<i><?php echo $address['Address_Detailed']?></i>
						</li>
                        <li><span>手机：</span><i><?php echo substr_replace($address['Address_Mobile'], '****', 3, 4)?></i></li>
                    </ul>
                </div>
                <div class="dosomething"> 
				<?php if(empty($address['Address_Is_Default'])) {?>
					<a href="javascript:;" fxy_type="set_address">设为默认</a>
			    <?php }else {?>
				<span class="moren">默认地址</span>
				<?php }?>
					<a href="javascript:;" fxy_type="edit_address">编辑</a> 
				</div>
            </div>
			<?php }?>
			<?php }else{?>
			<div class="dizhi" style="color:#666;text-align:center">暂无数据！</div>
			<?php }?>
            <a href="javascript:;" class="add_dizhi">新增收货地址</a> 
		</div>
    </div>
</div>
<div class="box_dizhi_form">
    <div class="zhezhao"></div>
    <div class="dizhi_form">
        <div class="title">
            <label>新增收货地址</label>
            <div class="cut"><a href="javascript:;"></a></div>
        </div>
        <div class="form" style=" margin-top:20px;">
			<form>
				<div class="dizhibieming" style="margin-bottom:20px;"> <span class="form_span"><i>*</i>收货人：</span>
					<input type="text" name="name"/>
				</div>
				<div class="diqu" style="margin-bottom:20px;"> <span class="form_span" style="float:left; line-height:30px;"><i>*</i>地区：</span>
					<div class="info" style="margin-left:106px;">
						<div>
							<select id="loc_province" name="province">
							</select>
							<select id="loc_city" name="city" >
							</select>
							<select id="loc_town" name="area">
							</select>
							<script>
							    showLocation(0,0,0);
							</script>
						</div>
						<div id="show"></div>
					</div>
				</div>
				<div class="xiangxidizhi" style="margin-bottom:20px;"> <span class="form_span"><i>*</i>详细地址：</span>
					<input type="text" style="width:500px;" name="detailed" />
				</div>
				<div class="phone" style=" overflow:hidden;margin-bottom:20px;">
					<div class="milble" style=" float:left;"> <span class="form_span"><i>*</i>手机：</span>
						<input type="text" name="mobile"/>
					</div>
				</div>
				<div class="clear"></div>
				<a href="javascript:;" class="saveaddress">保存收货地址</a>
				<input type="hidden" name="addr_id"  value=""/>
			</form>
		</div>
    </div>
</div>