<style>
<?php if($output['rsUser']['User_HeadImg']){?>
    #head_img{
	    background-image:url(<?php echo $output['rsUser']['User_HeadImg'];?>);
		-moz-background-size: 100% 100%;
		-o-background-size: 100% 100%;
		-webkit-background-size: 100% 100%;
		background-size: 100% 100%;
		-moz-border-image: url(<?php echo $output['rsUser']['User_HeadImg'];?>) 0;
		background-repeat:no-repeat\9;
		background-image:none\9;
		filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $output['rsUser']['User_HeadImg'];?>', sizingMethod='scale')\9;
	}
<?php }?>
</style>
<div class="body_menu">
<?php if($output['_controller'] != 'member' && $output['_action'] != 'index'){?>
    <div class="my_little">
        <div id="head_img"> <a href="<?php echo url('member/index');?>" title="个人中心"></a> </div>
        <div id="vip_word"> <a href="<?php echo url('member/personal_information');?>"><?php echo $output['rsUser']['User_NickName']?:'匿名'?></a><br />
            <span><?php echo $output['rsUser']['LevelName']?></span> 
		</div>
    </div>
<?php }?>
    <div class="bm_public">
        <label>订单中心</label>
        <ul>
            <li><a href="<?php echo url('member/status');?>">全部订单</a></li>
            <li><a href="<?php echo url('member/shoucang');?>">我的收藏</a></li>
            <li><a href="<?php echo url('member/backup');?>">我的退货单</a></li>
        </ul>
    </div>
    <div class="bm_public">
        <label>我的资产</label>
        <ul>
            <li><a href="<?php echo url('member/money');?>">我的余额</a></li>
            <li style="display:none;"><a href="<?php echo url('member/coupon');?>">我的优惠券(0)</a></li>
            <li style="display:none;"><a href="#">我的积分</a></li>
        </ul>
    </div>
    <div class="bm_public" style="display:none;">
        <label>积分兑换</label>
        <ul>
            <li><a href="#">全部商品</a></li>
            <li><a href="#">我的兑换记录</a></li>
            <li><a href="#">积分兑换礼品</a></li>
        </ul>
    </div>
    <div class="bm_public">
        <label>设置</label>
        <ul>
            <li><a href="<?php echo url('member/personal_information');?>">个人信息</a></li>
            <li><a href="<?php echo url('member/address');?>">收货地址</a></li>
        </ul>
    </div>
    <div class="bm_public">
        <label>分销中心</label>
        <ul>
            <li><a href="<?php echo url('distribute/distribute_group');?>">我的团队</a></li>
            <li><a href="<?php echo url('distribute/distribute_withdraw');?>">我的佣金</a></li>
            <li><a href="<?php echo url('distribute/distribute_withdraw_method');?>">我的提现方式</a></li>
            <li><a href="<?php echo url('distribute/distribute_record');?>">分销记录</a></li>

<!--            <li><a href="<?php //echo url('distribute/distribute_qrcodehb');?>" target="_blank">我的二维码</a></li>-->
<!--			<li><a href="<?php //echo url('distribute/pro_title');?>">爵位晋级</a></li>-->

        </ul>
    </div>
</div>