<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src='<?php echo $output['_site_url'];?>/static/pc/shop/js/distribute_withdraw.js'></script>
<script>
    var status = '<?php echo $output['status'];?>';
	var ajax_url = '<?php echo url('distribute/distribute_withdraw');?>';
	$(document).ready(distribute_withdraw_obj.distribute_withdraw_init);
</script>
<div class="comtent">
	<?php include(dirname(__DIR__) . '/home/_menu.php');?>
</div>
<div class="my_con">
    <div class="body">
		<?php include(dirname(__DIR__) . '/member/_left.php');?>
        <div class="body_center_pub all_dingdan my_yongjin">
        	<div class="top">

            	<div>
<!--                    我的佣金：<span class="money"><em><?php //echo $output['total_income']?></em>元</span><a class="buttom" href="javascript:;">立即提现</a><span class="hostory">可提现佣金：<em><?php //echo round_pad_zero($output['rsDisAccount']['balance'], 2);?></em>元</span>-->
                    我的佣金：<span class="money"><em><?php echo round_pad_zero($output['rsDisAccount']['balance'], 2);?></em>元</span><a class="buttom" href="javascript:;">立即提现</a><span class="hostory">
                </div>

            </div>
            <div class="biaoge">
            	<label>提现记录</label>
                <div class="see">
                	<div class="top">
                    	<span class="time"><div class="time_menu"><div class="time_text">时间</div></div></span>
                        <span class="change">佣金变动</span>
                        <span class="zhuangtai">
                            <div class="zhuangtai_menu">
                                <div class="zhungtai_text"><a href="javascript:;">状态<i></i></a></div>
                                <div class="zhuangtai_list">
                                    <ul>
                                        <li><a href="<?php echo url('distribute/distribute_withdraw',array('status'=>'all'));?>">全部状态</a></li>
                                        <li><a href="<?php echo url('distribute/distribute_withdraw',array('status'=>'0'));?>">申请中</a></li>
                                        <li><a href="<?php echo url('distribute/distribute_withdraw',array('status'=>'1'));?>">已执行</a></li>
										<li><a href="<?php echo url('distribute/distribute_withdraw',array('status'=>'2'));?>">已驳回</a></li>
                                    </ul>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="center">
                    	<ul></ul>
                    </div>
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
<div class="box_dizhi_form tixian">
    <div class="zhezhao"></div>
    <div class="dizhi_form">
        <div class="title">
            <label>申请提现</label>
            <div class="cut"><a href="javascript:;"></a></div>
        </div>
        <div class="form" style=" margin-top:20px;">
		    <div class="tishi">可提现余额<span>¥<?php echo empty($output['rsDisAccount']['balance']) ? '0.00' : $output['rsDisAccount']['balance'];?></span></div>
			<form id="withdraw-form">
			    <div style="margin-bottom:20px;color:#999;text-indent: 30px;">
					提示：申请提现后，您提现的金额<?php echo $output['rsConfig']['balance_ratio']?:'0.00';?>%转入您的会员余额，<?php echo (100-$output['rsConfig']['balance_ratio']);?>%店主会手动将钱打入您的账号 
				</div>
				<div style="margin-bottom:20px;"> <span class="form_span"><i>*</i>提现金额：</span>
					<input type="text" value="" name="money" style="width:100px;"/>
				</div>
				<div style="margin-bottom:20px;"><span class="form_span"><i>*</i>账&nbsp;&nbsp;号：</span>
					<?php if(!empty($output['user_method_list'])){?>
					<select name="User_Method_ID" style="background: #ffffff none repeat scroll 0 0;border: 1px solid #dcdcdc;height: 30px;">
					    <?php foreach($output['user_method_list'] as $key => $item){?>
						<option value="<?=$item['User_Method_ID']?>"><?=$item['Method_Name']?>&nbsp;&nbsp;<?=$item['Account_Val']?></option>
					    <?php }?>
					</select>
					<?php }else{?>
					<span style="color:#F60;">添加提现方法后才可体现</span>
					<?php }?>
				</div>
				<div class="clear"></div>
                <input type="hidden" name="action" value="withdraw_appy" />
				<a href="javascript:;" class="box_submit">确定</a>
			</form>
		</div>
    </div>
</div>