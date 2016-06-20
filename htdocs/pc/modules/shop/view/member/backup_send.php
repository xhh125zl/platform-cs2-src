<link href="<?php echo $output['_site_url'];?>/static/pc/shop/css/vipcenter.css" rel="stylesheet" type="text/css" />
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
			<div class="ddxq_top"> <span><em>退款编号：</em><?php echo $output['rsBackup']['Back_Sn']; ?></span> <span><em>退款状态：</em><?php echo $output['_STATUS'][$output['rsBackup']['Back_Status']] ?></span><br>
				<span><em>退款时间：</em><?php echo date('Y-m-d H:i:s', $output['rsBackup']['Back_CreateTime']) ?></span> <span> <em>退款数量：</em><?php echo $output['rsBackup']['Back_Qty'] ?></span> <span><em>退款总价：</em>￥<?php echo $output['rsBackup']['Back_Amount'] ?></span>
				<span><em>账号：</em><?php echo $output['rsBackup']['Back_Account'] ?></span>
				<span><em>商家收货地址：</em><?php echo $output['Province'] . $output['City'] . $output['Area'] . '【' . $output['rsBiz']['Biz_RecieveAddress'] . ' ， ' . $output['rsBiz']['Biz_RecieveName'] . '，' . $output['rsBiz']['Biz_RecieveMobile'] . '】';?></span>
			</div>
			<div class="ddxq_botton">
			    <div class="sqtk">
				    <div class="sqform">
						<form id="send_form">
							<input type="hidden" name="action" value="backup_send" />
							<input type="hidden" name="BackID" value="<?php echo $output['rsBackup']['Back_ID'];?>" />
							<div class="howtk">
								<div class="tkyy">
								  <label for="tkzh"><span><i>*</i>物流方式：</span></label>
								  <input type="text" id="tkzh" name="shipping" placeholder="请输入物流方式" value="" />
								</div>
								<div class="tkyy">
								  <label for="tkyy"><span><i>*</i>物流单号：</span></label>
								  <input type="text" id="tkyy" name="shippingID" placeholder="请输入物流单号" value="" />
								</div>
								<a id="tjsq" href="javascript:;">确定发货</a> 
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<script>
    $('#tjsq').click(function(){
	    $.post(shop_ajax_url, $('#send_form').serialize(),function(data){
		    if(data.status == 1){
			    location.href = data.url;
			}else{
			    alert(data.msg);
			}
		},'json');
	});
</script>