<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $output['_site_url'];?>/static/pc/shop/js/distribute_withdraw_method.js"></script>
<script>
	$(document).ready(function(){
		distribute_withdraw_method_obj.distribute_withdraw_method_init();
	});
</script>
<style>
.box_method_form div.alipay,.box_method_form div.bank_card{
    display:none;
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
		<?php include(dirname(__DIR__) . '/member/_left.php');?>
        <div class="body_center_pub all_dingdan mydizhi">
            <label>我的提现方法管理</label>
			<a href="javascript:;" class="add_dizhi add_method">新增提现方式</a> 
			<?php if(!empty($output['method_list'])){?>
			<?php foreach($output['method_list'] as $key => $item){?>
            <div class="dizhi method" id="<?=$item['User_Method_ID']?>">
                <div class="cut"><a href="javascript:;"></a></div>
                <div>
                    <ul>
                        <li><span>方法名称：</span><i><?=$item['Method_Name']?></i></li>
                        <?php if($item['Method_Type'] == 'bank_card'){?>
                        <li><span>户名：</span>
						    <i><?=$item['Account_Name']?></i>
						</li>
                        <li>
						    <span>账号：</span>
							<i><?=$item['Account_Val']?></i>
						</li>
                        <li><span>开户行：</span><i><?=$item['Bank_Position']?></i></li>
                        <?php }else if($item['Method_Type'] == 'alipay'){?>
                         <li><span>户名：</span>
						    <i><?=$item['Account_Name']?></i>
						</li>
                        <li>
						    <span>账号：</span>
							<i><?=$item['Account_Val']?></i>
						</li>
                        <?php }?> 
                    </ul>
                </div>
            </div>
			<?php }?>
			<?php }else{?>
			<div class="dizhi" style="color:#666;text-align:center">暂无数据！</div>
			<?php }?>
		</div>
    </div>
</div>
<div class="box_dizhi_form box_method_form">
    <div class="zhezhao"></div>
    <div class="dizhi_form method_form">
        <div class="title">
            <label>新增提现方式</label>
            <div class="cut"><a href="javascript:;"></a></div>
        </div>
        <div class="form" style=" margin-top:20px;">
			<form>
                
				<div class="dizhibieming" style="margin-bottom:20px;"> <span class="form_span"><i>*</i>提现方式：</span>
					<select id="Method_Name" name="Method_Name" style="background: #ffffff none repeat scroll 0 0;border: 1px solid #dcdcdc;height: 30px;">
					    <option value="">请选择</option>
                        <?php foreach($output['enabled_method_list'] as $key => $item) {?>
                        <option vlaue="<?=$item['Method_Name']?>" method_type="<?=$item['Method_Type']?>"><?=$item['Method_Name']?></option> 
                        <?php }?>
		            </select>
				</div>
                <!--银行卡-->
				<div class="xiangxidizhi bank_card" style="margin-bottom:20px;"> <span class="form_span"><i>*</i>户&nbsp;&nbsp;名：</span>
					<input type="text" style="width:200px;" name="Account_Name" />
				</div>
                <div class="xiangxidizhi bank_card" style="margin-bottom:20px;"> <span class="form_span"><i>*</i>帐&nbsp;&nbsp;号 ：</span>
					<input type="text" style="width:200px;" name="Account_Val" />
				</div>
				<div class="phone bank_card" style=" overflow:hidden;margin-bottom:20px;">
					<div class="milble" style=" float:left;"> <span class="form_span"><i>*</i>开户行：</span>
						<input type="text" style="width:200px;" name="Bank_Position"/>
					</div>
				</div>
                <!--支付宝-->
                <div class="xiangxidizhi alipay" style="margin-bottom:20px;"> <span class="form_span"><i>*</i>户&nbsp;&nbsp;名：</span>
					<input type="text" style="width:200px;" name="Account_Name2" />
				</div>
                <div class="xiangxidizhi alipay" style="margin-bottom:20px;"> <span class="form_span"><i>*</i>帐&nbsp;&nbsp;号 ：</span>
					<input type="text" style="width:200px;" name="Account_Val2" />
				</div>
                
				<div class="clear"></div>
				<a href="javascript:;" class="saveaddress savemethod">提交保存</a>
				<input type="hidden" name="id"  value=""/>
                <input type="hidden" name="Method_Type" id="Method_Type" value=""/> 
			</form>
		</div>
    </div>
</div>