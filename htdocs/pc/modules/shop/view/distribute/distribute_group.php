<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<div class="comtent">
	<?php include(dirname(__DIR__) . '/home/_menu.php');?>
</div>
<div class="my_con">
    <div class="body">
		<?php include(dirname(__DIR__) . '/member/_left.php');?>
        <div class="body_center_pub all_dingdan my_yongjin">
        	<div class="top">
            	<div>我的团队人数：<span class="money"><em><?php echo $output['rsAccount']['Group_Num'];?></em>人</span></div>
            </div>
            <div class="biaoge">
            	<label>队员列表</label>
                <div class="see">
                	<div class="top">
                    	<span class="time">创建时间</span>
                        <span class="change">店铺</span>
						<span class="zhuangtai">级别</span>
                    </div>
                    <div class="center">
                    	<ul>
						<?php foreach($output['posterity_list'] as $k => $v) {
                                                        if(!empty($v['level'])){?>
                                                            <li><span class="time"><?php echo $v['Account_CreateTime']?></span><span class="change"><?php echo $v['Shop_Name']?:'暂无'?></span><span class="zhuangtai"><?php echo $output['level_name_list'][$v['level']];?></span></li>
						<?php  }
                                                    }?>
						</ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>