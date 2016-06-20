<div id="iframe_page">
	<div class="iframe_content">
		<div class="r_nav">
			<ul>
				<li><a href="<?php echo url('pc_diy/index_block');?>">首页设置</a></li>
				<li class="cur"><a href="<?php echo url('pc_diy/index_focus');?>">幻灯片管理</a></li>
				<li class=""><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<div class="r_con_config r_con_wrap">
		    <div class="control_btn">
			  <a href="<?php echo url('pc_diy/focus_add');?>" class="btn_green btn_w_120">添加幻灯</a>
			</div>
			<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
				<thead>
					<tr>
						<td width="10%" nowrap="nowrap">编号</td>
						<td width="20%" nowrap="nowrap">标题</td>
						<td width="20%" nowrap="nowrap">图片</td>
						<td width="20%" nowrap="nowrap">排序</td>
						<td width="10%" nowrap="nowrap">显示</td>
						<td width="20%" nowrap="nowrap" class="last">操作</td>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($output['focus_list']) && is_array($output['focus_list'])){ ?>
					<?php foreach($output['focus_list'] as $k => $v){ ?>
					<tr>
						<td nowrap="nowrap"><?php echo $k+1;?></td>
						<td><?php echo $v['title'];?></td>
						<td><img src="<?php echo $v['pic'];?>" width="100px"/></td>
						<td><?php echo $v['sort'];?></td>
						<td><?php echo $v['is_show']==1 ? '是' : '否';?></td>
						<td class="last" nowrap="nowrap">
						    <a href="<?php echo url('pc_diy/focus_edit', array('id'=>$v['id']));?>">编辑</a> 
						    <a href="<?php echo url('pc_diy/focus_del', array('id'=>$v['id']));?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};">删除</a>
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