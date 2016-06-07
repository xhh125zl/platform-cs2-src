<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/member_backup.js"></script>
<script>
	var ajax_url = '<?php echo url('member/backup');?>';
	$(document).ready(function(){
		member_backup_obj.member_backup_init();
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
        <div class="body_center_pub all_dingdan">
            <div class="df">
                <ul>
				<li class="my_return">
                    <a>我的退款单</a>
				</li>
                </ul>
            </div>
            <div class="masthead">
				<span class="name">商品</span>
				<span class="price">单价</span>
				<span class="num">数量</span>
				<span class="truepay">退款金额</span>
				<span class="on">退款状态</span>
				<span class="paydo">退款操作</span>
			</div>
            <div class="manydingdan"> </div>
			<input type="hidden" name="page" value="1" />
            <div class="fanye">
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
<div class="box_dizhi_form">
    <div class="zhezhao"></div>
    <div class="dizhi_form">
        <div class="title">
            <label>我要评论</label>
            <div class="cut"><a href="javascript:;"></a></div>
        </div>
        <div class="form" style=" margin-top:20px;">
			<form>
				<div style="margin-bottom:20px;"> <span class="form_span" style="float:left; line-height:30px;"><i>*</i>为卖家打分：</span>
					<div class="info" style="margin-left:106px;">
						<div>
							<select name="Score">
							   <option value="5">非常满意</option>
							   <option value="4">满意</option>
							   <option value="3">一般</option>
							   <option value="2">差</option>
							   <option value="1">非常差</option>
							</select>
						</div>
						<div id="show"></div>
					</div>
				</div>
				<div style="margin-bottom:20px;"> <span class="form_span"><i>*</i>评论内容：</span>
					<textarea name="Note" value="" style="width:500px"></textarea>
				</div>
				<div class="clear"></div>
				<input type="hidden" name="Order_ID" value="" />
                <input type="hidden" name="action" value="commit" />
				<a href="javascript:;" class="savecommit">提交保存</a>
			</form>
		</div>
    </div>
</div>