<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src='<?php echo $output['_site_url'];?>/static/pc/shop/js/vip_privilege.js'></script>
<script>
	var ajax_url = '<?php echo url('member/vip_privilege');?>';
	$(document).ready(vip_privilege_obj.vip_privilege_init);
</script>
<div class="comtent">
	<?php include(dirname(__DIR__) . '/home/_menu.php');?>
</div>
<div class="my_con">
    <div class="body">
		<?php include(dirname(__DIR__) . '/member/_left.php');?>
        <div class="body_center_pub all_dingdan fenxiao_jilu">
        <label>会员权利说明</label>
        <div class="jilubiao">
            <div class="neirong">
            	<div class="top">
                	<div class="time">内容标题</div>
                    <div class="fenxiaostyle">会员等级</div>
                    <div class="fenxiaochanpin">显示时间</div>
                    <div class="zhuangtai">状态</div>
					<div class="jiangjin">操作</div>
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