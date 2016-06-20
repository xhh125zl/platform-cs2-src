var material_obj={
	material_init:function(){
		$('#material>.list').masonry({itemSelector:'.item', columnWidth:367});
	},
	
	material_one_init:function(){
		$('#material .button').css('padding-bottom', 70);
		global_obj.file_upload($('#ImgUpload'), $('#material_form input[name=ImgPath]'), $('#ImgDetail'));
		$('#ImgDetail').html(global_obj.img_link($('#material_form input[name=ImgPath]').val()));
		
		$('#material_form input[name=Title]').on('keyup paste blur', function(){
			$('#material_form .title').html($(this).val());
		});
		$('#material_form textarea').on('keyup paste blur', function(){
			$('#material_form .txt').html($(this).val().replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2'));
		});
		
		$('#material_form #btn_select_url').click(function(){
			global_obj.create_layer('选择链接', '/member/material/sysurl.php?dialog=1&input=tuwen',1000,500);
		});
		
		$('#material_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#material_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	material_multi_init:function(){
		var material_multi_list_even=function(){
			$('.multi .first, .multi .list').each(function(){
				var children=$(this).children('.control');
				$(this).mouseover(function(){children.css({display:'block'});});
				$(this).mouseout(function(){children.css({display:'none'});});
				
				children.children('a[href*=#del]').click(function(){
					if($('.multi .list').size()<=1){
						alert('无法删除，多条图文至少需要2条消息！');
						return false;
					}
					if(confirm('删除后不可恢复，继续吗？')){
						$(this).parent().parent().remove();
						$('.multi .first a[href*=#mod]').click();
						$('.mod_form').css({top:37});
					}
				});
				
				children.children('a[href*=#mod]').click(function(){
					var position=$(this).parent().offset();
					var material_form_position=$('#material_form').offset();
					var cur_id='#'+$(this).parent().parent().attr('id');
					$('.mod_form').css({top:position.top-material_form_position.top});
					$('.mod_form input[name=inputUrl]').attr("id",'url_'+$(this).parent().parent().attr('id'));
					$('.mod_form input[name=inputUrl]').val($(cur_id+' input[name=Url\\[\\]]').val());
					$('.mod_form input[name=inputTitle]').val($(cur_id+' input[name=Title\\[\\]]').val());
					$('.big_img_size_tips').html(cur_id=='#multi_msg_0'?'640*360px':'300*300px');
					$('.multi').data('cur_id', cur_id);
					global_obj.file_upload($('#ImgUpload'), $(cur_id+' input[name=ImgPath\\[\\]]'), $(cur_id+' .img'));
				});
				//$('.mod_form select[name=inputUrl]').find("option[value='"+$('input[name=Url\\[\\]]').val()+"']").attr("selected", true);
			});
		}
		
		global_obj.file_upload($('#ImgUpload'), $('.multi .first input[name=ImgPath\\[\\]]'), $('.first .img'));
		$('.multi').data('cur_id', '#'+$('.multi .first').attr('id'));
		
		$('.mod_form input').filter('[name=inputTitle]').on('keyup paste blur', function(){
			var cur_id=$('.multi').data('cur_id');
			$(cur_id+' input[name=Title\\[\\]]').val($(this).val());
			$(cur_id+' .title').html($(this).val());
		})
		
		$('.mod_form img').filter('.btn_select_url').on('click', function(){
			var id=$('.multi').data('cur_id').replace("#multi_msg_","");
			global_obj.create_layer('选择链接', '/member/material/sysurl.php?dialog=1&input=multituwen_'+id,1000,500);
		})
		
		material_multi_list_even();
		$('a[href=#add]').click(function(){
			$(this).blur();
			if($('.multi .list').size()>=7){
				alert('你最多只可以加入8条图文消息！');
				return false;
			}
			$('.multi .list, a[href*=#mod], a[href*=#del]').off();
			$('<div class="list" id="multi_msg_'+Math.floor(Math.random()*1000000)+'">'+$('.multi .list:last').html()+'</div>').insertAfter($('.multi .list:last'));
			$('.multi .list:last').children('.info').children('.title').html('标题').siblings('.img').html('缩略图');
			$('.multi .list:last input').filter('[name=Title\\[\\]]').val('').end().filter('[name=Url\\[\\]]').val('').end().filter('[name=ImgPath\\[\\]]').val('');
			material_multi_list_even();
		});
		
		$('#material_form').submit(function(){
			var pre = '#'+$('.mod_form input[name=inputUrl]').attr("id").replace('url_','');
			if(pre !== 'undefined'){
				$(pre+' input[name=Url\\[\\]]').val($('.mod_form input[name=inputUrl]').val());
			}
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#material_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	url_init:function(){
		$('#add_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#add_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	url_select:function(){
		
		$('#url table tfoot a.url_add').click(function(){
			var url_name = $("#url table tfoot #Url_Name").attr("value");
			if($.trim(url_name)==''){
				alert("标题不能为空");
				return false;
			}
			var url_link = $("#url table tfoot #Url_Link").attr("value");
			if($.trim(url_link)==''){
				alert("链接不能为空");
				return false;
			}
			
			$(this).removeClass("url_add");
			
			$(this).html("加载中");
			
			$.post('sysurl.php',{Url_Name:url_name,Url_Link:url_link},function(data){
				if(data.status==1){
					$("#url table tfoot #Url_Name").attr("value","");
					$("#url table tfoot #Url_Link").attr("value","");
					window.location.reload();
				}else{
					alert(data.msg);
				}
			},'json');
		});
		
		$('#url table tbody tr').css('cursor','pointer');
		$('#url table tbody tr').click(function(){
			var selected = $(this).children('td.last').html();
			if(parent_input.indexOf("multituwen_")!=-1){//多图文
				var id = parent_input.replace("multituwen_","");
				$('#url_multi_msg_'+id, parent.document).val($.trim(selected));
				$('#multi_msg_'+id+' input[name=Url\\[\\]]', parent.document).val($.trim(selected));
			}else if(parent_input.indexOf("distribute_url_")!=-1){//分销设置自定义菜单
				var id = parent_input.replace("distribute_url_","");
				$('#menubox #distribute_url_'+id, parent.document).val($.trim(selected));
			}else if(parent_input.indexOf("shop_home_url_")!=-1){//商城首页设置
				var id = parent_input.replace("shop_home_url_","");
				$('.item .url_select #shop_home_url_'+id, parent.document).val($.trim(selected));
			}else{
				switch(parent_input){
					case 'menu'://自定义菜单选择链接
						$('#menu_url', parent.document).val($.trim(selected));
					break;
					case 'tuwen'://单图文
						$('#tuwen_url', parent.document).val($.trim(selected));
					break;
					case 'web_common'://微官网公用
						$('#web_common_url', parent.document).val($.trim(selected));
					break;
				}
			}
			$('.layui-layer-shade',parent.document).remove();
			$('.layui-layer-iframe',parent.document).remove();
		});
	},
	
}

