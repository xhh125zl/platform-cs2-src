<div id="iframe_page">
	<div class="iframe_content">
		<div class="r_nav">
			<ul>
				<li class=""><a href="<?php echo url('pc_diy/index_block');?>">首页设置</a></li>
				<li class=""><a href="<?php echo url('pc_diy/index_focus');?>">幻灯片管理</a></li>
				<li class=""><a href="<?php echo url('pc_setting/index');?>">基本设置</a></li>
				<li class=""><a href="<?php echo url('pc_setting/share_setting');?>">分享设置</a></li>
				<li class="cur"><a href="<?php echo url('pc_setting/menu_index');?>">导航管理</a></li>
			</ul>
		</div>
		<div class="r_con_config r_con_wrap">
		    <div class="control_btn">
			  <a href="<?php echo url('pc_setting/menu_add');?>" class="btn_green btn_w_120">添加导航</a>&nbsp;&nbsp;
			</div>
			<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
				<thead>
					<tr>
						<td width="10%" nowrap="nowrap">排序</td>
						<td width="20%" nowrap="nowrap">导航名称</td>
						<td width="35%" nowrap="nowrap">导航链接</td>
						<td width="15%" nowrap="nowrap">是否新窗口打开</td>
						<td width="20%" nowrap="nowrap" class="last">操作</td>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($output['users_menu']) && is_array($output['users_menu'])){ ?>
					<?php foreach($output['users_menu'] as $k => $v){ ?>
					<tr>
						<td nowrap="nowrap"><?php echo $v['menu_sort'];?></td>
						<td><?php echo $v['menu_name'];?></td>
						<td><?php echo $v['menu_link'];?></td>
						<td><?php echo $v['menu_target']==1 ? '是' : '否';?></td>
						<td class="last" nowrap="nowrap">
						    <a href="<?php echo url('pc_setting/menu_edit', array('id'=>$v['id']));?>">编辑</a> 
						    <a href="<?php echo url('pc_setting/menu_index', array('id'=>$v['id'],'action'=>'del'));?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};">删除</a>
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