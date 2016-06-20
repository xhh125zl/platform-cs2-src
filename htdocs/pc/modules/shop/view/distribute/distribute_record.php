<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src='<?php echo $output['_site_url'];?>/static/pc/shop/js/distribute_record.js'></script>
<script>
    var filter = '<?php echo $output['filter'];?>';
	var ajax_url = '<?php echo url('distribute/distribute_record');?>';
	$(document).ready(distribute_record_obj.distribute_record_init);
</script>
<div class="comtent">
	<?php include(dirname(__DIR__) . '/home/_menu.php');?>
</div>
<div class="my_con">
    <div class="body">
		<?php include(dirname(__DIR__) . '/member/_left.php');?>
        <div class="body_center_pub all_dingdan fenxiao_jilu">
        <label>分销明细记录</label>
        <div class="jilubiao">
        	<div class="choose">
            	<div class="left">
                	<a <?php if($output['filter'] == 'all'){?>class="focus"<?php }?> href="<?php echo url('distribute/distribute_record',array('filter'=>'all'));?>">全部<span>(<?php echo $output['all_distribute_count'];?>)</span></a><a <?php if($output['filter'] == 'self'){?>class="focus"<?php }?> href="<?php echo url('distribute/distribute_record',array('filter'=>'self'));?>">自销<span>(<?php echo $output['self_distribute_count'];?>)</span></a><a <?php if($output['filter'] == 'down'){?>class="focus"<?php }?> href="<?php echo url('distribute/distribute_record',array('filter'=>'down'));?>">下级分销商分销<span>(<?php echo $output['posterity_distribute_count'];?>)</span></a>
                </div>
                <div class="right" style="display:none;">
                	<input type="text"><input type="submit" value="搜索">
                </div>
            </div>
            <div class="clear"></div>
            <div class="neirong">
            	<div class="top">
                	<div class="time">时间</div>
                    <div class="fenxiaostyle">分销类型</div>
                    <div class="fenxiaochanpin"><span>分销产品</span></div><div class="jiangjin"><span>获得佣金</span></div>
                    <div class="zhuangtai">状态</div>
                </div>
                <div class="list"></div>
            </div>
            <div class="fanye">
			    <input type="hidden" name="page" value="1" />
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
        </div>
        </div>
    </div>
</div>