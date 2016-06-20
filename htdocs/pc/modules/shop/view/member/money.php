<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/member_money.js"></script>
<script>
	var ajax_url = '<?php echo url('member/money', array('type'=>$output['type']));?>';
	$(document).ready(function(){
		member_money_obj.member_money_init();
	});
</script>
<style>
.chongzhi label.Operator{padding:8px;border:solid 1px #cfcfcf;margin-right:8px;cursor:pointer;text-align:center;}
.chongzhi label.Operator input{display:none;}

.chongzhi label.cattsel{
    border: 2px solid #e9630a;
    position: relative;
}
.chongzhi label.cattsel i{
    background: rgba(0, 0, 0, 0) url(<?php echo $output['_site_url']?>/static/pc/shop/images/righbt.png) no-repeat scroll 0 0;
    padding:7px;
    position: absolute;
	bottom: -1px;
    right: -1px;
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
        <div class="body_center_pub all_dingdan">
			<div class="my_balance">
				<h3>我的余额：</h3>
				<span><?php echo $output['rsUser']['User_Money'];?></span>元
				<a href="javascript:;">充值</a>
			</div>
            <div class="balance_record">
			    <div class="balance_record_chose">
					<div class="brc_left">
						<ul>
							<li class="<?php if(empty($_GET['type']) || $_GET['type']=='charge_record'){?>brc_left_focus<?php }?>"><a href="<?php echo url('member/money', array('type'=>'charge_record'));?>">充值记录</a></li>
							<li class="<?php if(!empty($_GET['type']) && $_GET['type']=='money_record'){?>brc_left_focus<?php }?>"><a href="<?php echo url('member/money', array('type'=>'money_record'));?>">资金流水</a></li>
						</ul>
					</div>
				</div>
				<div class="balance_record_table">
				    <div class="brt_different">
						<span id="time">时间</span><span id="writesome">备注</span>
					</div>
					<div class="brt_see"><ul></ul></div>
				</div>
			</div>
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
<div class="box_dizhi_form chongzhi">
    <div class="zhezhao"></div>
    <div class="dizhi_form">
        <div class="title">
            <label>会员充值</label>
            <div class="cut"><a href="javascript:;"></a></div>
        </div>
        <div class="form" style=" margin-top:20px;">
			<form>
				<div style="margin-bottom:20px;"> <span class="form_span"><i>*</i>充值金额：</span>
					<input type="text" value="" name="Amount" style="width:200px;"/>
				</div>
				<div style="margin-bottom:20px;"><span class="form_span"><i>*</i>支付方式：</span>
					<label class="Operator cattsel"><input type="radio" value="2" name="Operator" checked="checked" /><i></i><strong>支付宝</strong></label>		
				</div>
				<div class="clear"></div>
                <input type="hidden" name="action" value="charge" />
				<a href="javascript:;" class="box_submit">确定</a>
			</form>
		</div>
    </div>
</div>


<div class="box_dizhi_form">
    <div class="zhezhao"></div>
    <div class="dizhi_form">
        <div class="title">
            <label></label>
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