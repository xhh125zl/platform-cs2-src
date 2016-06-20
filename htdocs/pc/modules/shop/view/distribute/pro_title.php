<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<style>
    .biaoge .see .top{width:auto;}
    .biaoge .see .top span{width:16%;text-align:center;}
	.center ul li span{width:16%;text-align:center;}
</style>
<div class="comtent">
	<?php include(dirname(__DIR__) . '/home/_menu.php');?>
</div>
<div class="my_con">
    <div class="body">
		<?php include(dirname(__DIR__) . '/member/_left.php');?>
        <div class="body_center_pub all_dingdan my_yongjin">
        	<div class="top">
            	<div>
					<span class="money">
						<em style="font-size: 30px;">
						<?php if(!empty($output['rsDisAccount']['Professional_Title']) && !empty($output['front_title'][$output['rsDisAccount']['Professional_Title']])){?>
							<?php if(!empty($output['front_title'][$output['rsDisAccount']['Professional_Title']]['ImgPath'])){?><img src="<?=$output['front_title'][$output['rsDisAccount']['Professional_Title']]['ImgPath']?>" /><?php }?> <?=$output['front_title'][$rsAccount['Professional_Title']]['Name']?>
						<?php }else {?>
						   暂无爵位
						<?php }?>
						</em>
					</span>
					<span class="hostory"><b>消费额：</b><em><?=$output['total_sales'];?></em>元</span>&nbsp;&nbsp;
					<span class="hostory"><b>直销人数：</b><em><?=$output['user_count'];?></em>人</span>&nbsp;&nbsp;
					<span class="hostory"><b>团队人数：</b><em><?=$output['rsDisAccount']['Group_Num'];?></em>人</span>&nbsp;&nbsp;
					<?php if($output['ex_bonus']['total']){?>
					<p style="line-height:25px;"><span class="hostory"><b>总奖金：</b><em><?=$output['ex_bonus']['total'];?></em>元</span></p>
					<p style="line-height:25px;"><span class="hostory">已有 <em><?=$output['ex_bonus']['payed']?></em> 元发放到您的可提现佣金中</span></p>
					<?php }else{?>
					<span class="hostory" style="color:#d30015;">目前无奖金!!!</span>&nbsp;&nbsp;
					<?php }?>
				</div>
            </div>
            <div class="biaoge">
                <div class="see">
                	<div class="top">
					    <span>#</span>
                    	<span>爵位</span>
                        <span>消费额</span>
                        <span>直销人数</span>
						<span>团队人数</span>
                        <span>奖励百分比</span>
                    </div>
                    <div class="center">
                    	<ul>
						    <?php if(!empty($output['front_title'])){?>
						    <?php foreach($output['front_title'] as $key => $item) {?>
						    <li>
								<span><?=$key?></span>
								<span><?=$item['Name']?></span>
								<span><?=$item['Consume']?>元</span>
								<span><?=$item['Saleroom']?></span>
								<span><?=$item['Group_Num']?></span>
								<span><?=$item['Bonus']?>%</span>
							</li>
							<?php }?>
							<?php }?>
						</ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>