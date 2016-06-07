
var account_obj={
	account_init:function(){
		$('.upd_select').dblclick(function(){
			var o=$(this).children('.upd_txt');
			if(o.children('select').size()){return false;}
			
			var s_html='<select>';
			for(i=0;i<level_ary.length;i++){
				var selected=o.html()==level_ary[i]?'selected':'';
				s_html+='<option value="'+i+'" '+selected+'>'+level_ary[i]+'</option>';
			}
			s_html+='</select>';
			o.data('text', o.html()).html(s_html);
			o.children('select').focus();
			
			o.children('select').bind('change blur', function(){
				var value=parseInt($(this).val());
				if(value>=level_ary.length){
					value=0;
				}
				
				if(level_ary[value]==o.data('text')){
					o.html(o.data('text'));
					return false;
				}
				$('#update_post_tips').html('数据提交中...').css({left:$(window).width()/2-100}).show();
				
				$.post('ajax.php', "action=protitle&AccountID="+o.parent().parent().attr('AccountID')+'&Value='+value, function(data){
					if(data.status==1){
						var msg='修改成功！';
						o.html(level_ary[value]);
					}else if(data.msg!=''){
						var msg=data.msg;
						o.html(o.data('text'));
					}else{
						var msg='修改失败，出现未知错误！';
						o.html(o.data('text'));
					}
					$('#update_post_tips').html(msg).fadeOut(3000);
				}, 'json');
			});
		});
        //弹出代理信息对话框
		$(".agent_info").click(function(){
			var account_id = $(this).attr('agent-id');
			$("#agent-info-modal").modal('show');
			var param = {account_id:account_id,action:'get_dis_agent_form'};
			$.get('ajax.php',param,function(data){
				
				if(data.status == 1){					
					$("#agent-info-modal").find('div.modal-body').html(data.content);	
					 //展开城市列表
					$("img.trigger").click(function() {
					$('div.ecity ').removeClass('showCityPop');
					$(this).parent().parent().addClass('showCityPop');
					});

					//关闭城市列表
					$("input.close_button").click(function() {
						$(this).parent().parent().parent().removeClass('showCityPop');
					});
		
				}
			},'json');
		});
		
		//选中大区反应
		$(".J_Group").live('click',function(){
			var province_ids = $(this).attr('value');
			var checked = $(this).prop('checked');
			
			if(checked){
				province_ids.split(',').each(function(province_id){
			    	if(!$("#J_Province_"+province_id).prop('disabled')){
						$("#J_Province_"+province_id).prop('checked',true);
					}
				});
			}else{
				province_ids.split(',').each(function(province_id){
					if(!$("#J_Province_"+province_id).prop('disabled')){
						$("#J_Province_"+province_id).prop('checked',false);
					}
				});
			}
		});
		
		$("#confirm_dis_area_agent_btn").click(function(){
			var JProvinces = $("#dis_agent_form input[name='J_Province']").fieldValue(); 
		    var KCitys = $("#dis_agent_form input[name='K_City']").fieldValue();
			var KCountys = $("#dis_agent_form input[name='K_County']").fieldValue();
			var account_id = $("#account_id").attr('value');
			var param = {JProvinces:JProvinces,
						 KCitys:KCitys,	
						 KCountys:KCountys,
			             account_id:account_id,
						 action:'save_dis_agent_area'};
			$.post('ajax.php',param,function(data){
				if(data.status == 1){
					$("#agent-info-modal").modal('hide');	
				}
			},'json');
		});
	}
}