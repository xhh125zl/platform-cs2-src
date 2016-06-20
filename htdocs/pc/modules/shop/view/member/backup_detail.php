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
			<?php if($output['rsBackup']['Back_Status'] == 1){?>
			    <div><a href="<?php echo url('member/backup_send', array('BackID'=>$output['rsBackup']['Back_ID']));?>" style="display:block; width:100px; height:30px; line-height:28px; color:#FFF; background:#F60; border-radius:8px; text-align:center; font-size:12px; font-weight:normal; margin:3px auto">我要发货</a></div>
			<?php }?>
			<div class="ddxq_botton">
				<div class="ddxq_more">
				<?php if($output['user_back_order_detail']){?>
					<?php foreach($output['user_back_order_detail'] as $r){?>
					<div class="dingdan">
						<div class="dd_more"> 
							<span id="ddxq_num"><?php echo date('Y-m-d H:i:s', $r['createtime']);?></span> 
							<span id="ddxq_truepay" style="color:#777"><?php echo $r['detail'];?></span>
						</div>
					</div>
					<?php }?>
					<?php }?>
				</div>
			</div>
		</div>

    </div>
</div>