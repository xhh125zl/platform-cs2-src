<div   style="visibility: visible; left: 142px; top: 333.25px;z-index:9;"   class="ks-dialog  dialog-areas hidden" id="area_dialog"><a tabindex="0" href="javascript:void(&quot;关闭&quot;)" role="button" style="z-index:9" class="ks-ext-close"><span class="ks-ext-close-x area-dialg-close">关闭</span></a><div class="ks-contentbox"><div id="ks-dialog-header672" class="ks-stdmod-header"><div class="title">选择区域</div></div><div class="ks-stdmod-body"><form method="post" id="area_form">	<ul id="J_CityList">
		<?php $i=0; ?>
		<?php foreach($region_list as $region_name=>$province_id_list):?>
        <li>
	<div class="dcity clearfix">
	
    <div class="ecity gcity">
		<span class="group-label"><input value="<?=implode(',',$province_id_list)?>" class="J_Group" id="J_Group_<?=$i?>" type="checkbox">
			<label for="J_Group_<?=$i?>"><?=$region_name?></label></span>
	</div>
	
    <div class="province-list">
		<?php foreach($province_id_list as $key=>$province_id):?>
        	<div class="ecity">
	<span class="gareas"><input value="<?=$province_id?>" id="J_Province_<?=$province_id?>" name="J_Province" class="J_Province" type="checkbox">
		<label for="J_Province_<?=$province_id?>"><?=$province_list[$province_id]?></label><span class="check_num"></span><img class="trigger" src="<?=$base_url?>static/member/images/shop/city_down_icon.gif"></span>
		<div class="citys">
		
        <?php foreach($area_array['0,'.$province_id] as $city_id=>$city_name):?>	
            <span class="areas"><input value="<?=$city_id?>" id="J_City_<?=$city_id?>" name="J_City[]" class="J_City" type="checkbox">
			<label for="J_City_<?=$city_id?>"><?=$city_name?></label></span>
       	<?php endforeach;?>	
     
		
	<p style="text-align:right;"><input value="关闭" class="close_button" type="button"></p>
	</div>
	</div>
		<?php endforeach;?>	
	</div>
	</div>
	</li>
        <?php $i++; ?>
		<?php endforeach;?>
	</ul>
    <input type="hidden" id="area_value_container" value=""/>
	<input type="hidden" id="area_type" value=""/>
    <div class="btns"><button type="button" id="J_Submit" class="J_Submit">确定</button><button type="button" class="J_Cancel area-dialg-close">取消</button></div></form></div><div class="ks-stdmod-footer"></div></div><div tabindex="0" style="position:absolute;"></div></div>