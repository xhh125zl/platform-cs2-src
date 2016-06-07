<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/sys_msg.js"></script>
<script>
    var ajax_url = '<?php echo url('member/sys_msg');?>';
	$(document).ready(function(){
		sys_msg_obj.sys_msg_init();
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
        <div class="body_center_pub all_dingdan mydizhi">
            <label>我的消息提醒</label>
			<div class="list"></div>
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