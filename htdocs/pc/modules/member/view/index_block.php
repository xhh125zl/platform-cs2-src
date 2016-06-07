<div id="iframe_page">
	<div class="iframe_content">
		<div class="r_nav">
			<ul>
				<li class="cur"><a href="<?php echo url('pc_diy/index_block');?>">首页设置</a></li>
				<li class=""><a href="<?php echo url('pc_diy/index_focus');?>">幻灯片管理</a></li>
				<li class=""><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<div class="r_con_config r_con_wrap">
		    <div class="control_btn">
			  <a href="<?php echo url('pc_diy/block_add');?>" class="btn_green btn_w_120">添加板块</a>&nbsp;&nbsp;
			  <a href="<?php echo url('pc_diy/update_index');?>" class="btn_green btn_w_120">更新首页</a>
			</div>
			<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
				<thead>
					<tr>
						<td width="10%" nowrap="nowrap">排序</td>
						<td width="20%" nowrap="nowrap">板块名称</td>
						<td width="15%" nowrap="nowrap">色彩风格</td>
						<td width="20%" nowrap="nowrap">更新时间</td>
						<td width="15%" nowrap="nowrap">显示</td>
						<td width="20%" nowrap="nowrap" class="last">操作</td>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($output['web_list']) && is_array($output['web_list'])){ ?>
					<?php foreach($output['web_list'] as $k => $v){ ?>
					<tr>
						<td nowrap="nowrap"><?php echo $v['web_sort'];?></td>
						<td><?php echo $v['web_name'];?></td>
						<td><div style="width:30px;height:30px;margin:0 auto;background:<?php echo $v['style_name'];?>"></div></td>
						<td><?php echo date('Y-m-d H:i:s',$v['update_time']);?></td>
						<td><?php echo $v['web_show']==1 ? '是' : '否';?></td>
						<td class="last" nowrap="nowrap">
						    <a href="<?php echo url('pc_diy/block_edit', array('web_id'=>$v['web_id']));?>">编辑</a> 
						    <a href="<?php echo url('pc_diy/code_edit', array('web_id'=>$v['web_id']));?>">板块编辑</a>
						</td>
					</tr>
					<?php } ?>
					<?php }else { ?>
						<tr>
							<td colspan="15" style="color:#999">暂无记录</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>