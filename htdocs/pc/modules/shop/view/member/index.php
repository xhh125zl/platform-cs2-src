<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<?php if($output['rsUser']['User_HeadImg']){?>
<style>
    .head_img a{
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
	
</style>
<?php }?>
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
		<div class="body_center_pub my_dingdan">
			<div class="my_con_top">
				<div class="mct_left">
					<div class="head_img"> <a href="<?php echo url('member/personal_information');?>"></a> </div>
					<div class="vip_word">
						<div class="v_name">
							<h3><?php echo $output['rsUser']['User_NickName']?:'匿名'?>&nbsp;<a style="font-size:12px;" href="<?php echo url('member/sys_msg');?>">提醒消息</a></h3>
						</div>
						<div class="v_level"> <span><?php echo $output['rsUser']['LevelName']?></span><a href="<?php echo url('member/vip_privilege');?>">查看等级特权&gt;&gt;</a> </div>
					</div>
				</div>
				<div class="mct_center">
					<div class="mct_cen_public wait_pay"> <span>未付款</span>
						<div><a href="<?php echo url('member/status', array('Status'=>1));?>"><?php echo $output['order_num'][0];?></a></div>
					</div>
					<div class="mct_cen_public over_pay"> <span>已付款</span>
						<div><a href="<?php echo url('member/status', array('Status'=>2));?>"><?php echo $output['order_num'][1];?></a></div>
					</div>
					<br />
					<div class="mct_cen_public wait_get"> <span>待收货</span>
						<div><a href="<?php echo url('member/status', array('Status'=>3));?>"><?php echo $output['order_num'][2];?></a></div>
					</div>
					<div class="mct_cen_public finish"> <span>已完成</span>
						<div><a href="<?php echo url('member/status', array('Status'=>4));?>"><?php echo $output['order_num'][3];?></a></div>
					</div>
				</div>
				<div class="mct_right">
					<div class="red">
						<ul>
							<li>我的余额：<a href="javascript:;"><?php echo $output['rsUser']['User_Money'];?></a>元<span><a href="<?php echo url('member/money');?>">充值</a></span></li>
							<li>我的积分：<a href="javascript:;"><?php echo $output['rsUser']['User_Integral'];?></a>分</li>
							<li>我的佣金：<a href="javascript:;"><?php echo $output['total_income'];?></a>元<span><a href="<?php echo url('distribute/distribute_withdraw');?>">提现</a></span></li>
						</ul>
					</div>
					<div class="blue"></div>
				</div>
			</div>
			<span class="more"><a style="color:#999999" href="<?php echo url('member/status');?>">查看全部订单&gt;&gt;</a></span>
			<label>我的订单</label>
			<div class="masthead"> <span class="name">商品</span> <span class="price">单价</span> <span class="num">数量</span> <span class="truepay">实付款</span> <span class="on">交易状态</span> <span class="paydo">交易操作</span> </div>
			<?php if($output['my_order']){?>
			<?php foreach($output['my_order'] as $k => $v){?>
			<div class="dingdan">
				<div class="dd_nt"> <span>订单编号:<i><?php echo $v['order_sn'];?></i></span><span>成交时间：<i><?php echo $v['Order_CreateTime'];?></i></span> </div>
				<div class="dd_more"> 
				    <span class="name"><img src="<?php echo $v['products_img'];?>">
						<div>
							<a href="<?php echo $v['products_url']?>"><?php echo $v['ProductsName']?></a>
							<?php if($v['Property']){?>
							<?php foreach($v['Property'] as $v2){?>
							<span><?php echo $v2['Name']?> ：<?php echo $v2['Value']?></span><br>
							<?php }?>
							<?php }?>
						</div>
					</span> 
					<span class="price"><i><?php echo $v['ProductsPriceY']?></i><br><?php echo $v['ProductsPriceX']?></span> 
					<span class="num"><?php echo $v['ProductsQty']?></span> 
					<span class="truepay"><?php echo $v['Order_TotalPrice'];?></span> 
					<span class="on"><i><?php echo $v['status_arr'][$v['Order_Status']]?></i><br><a href="<?php echo $v['detail_url'];?>">订单详情</a></span> 
					<span class="paydo"><?php echo $v['paydo_html']?></span> 
				</div>
			</div>
			<?php }?>
			<?php }?>
			<div class="my_shoucang">
				<div class="msctitle"> 我<br>
					的<br>
					收<br>
					藏 </div>
				<ul>
					<?php if($output['shoucang']){?>
					<?php foreach($output['shoucang'] as $v){?>
					<li>
						<div class="sc_img"> <a href="<?php echo $v['P_URL'];?>"><img src="<?php echo $v['ImgPath'];?>"></a> </div>
						<div class="sc_word">
							<div class="sc_number"><a href="<?php echo $v['P_URL'];?>"><?php echo $v['Products_Name'];?></a></div>
							<div class="andmore"><span>￥<?php echo $v['Products_PriceX']?></span><i>￥<?php echo $v['Products_PriceY']?></i></div>
						</div>
					</li>
					<?php }?>
					<?php }?>
					<li>
						<div class="shoucang_more"> <a href="<?php echo url('member/shoucang');?>"> <span class="star"></span> 查看全部收藏 </a> </div>
					</li>
				</ul>
			</div>
		</div>
    </div>
</div>