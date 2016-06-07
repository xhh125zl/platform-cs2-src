var app_car_obj={
	config_init:function(){
		$('#config_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#config_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	products_list_init:function(){
		$('a[href=#search]').click(function(){
			$('form.r_con_search_form').slideDown();
			return false;
		});
	},
	
	products_init:function(){
		$('#products_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#products_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	products_category_init:function(){
		$('#app_car_category_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#app_car_category_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	reserve_edit_init:function(){
		frame_obj.file_upload($('#ReplyImgUpload'), $('form input[name=ReplyImgPath]'), $('#ReplyImgDetail'));
		$('#ReplyImgDetail').html(frame_obj.upload_img_detail($('form input[name=ReplyImgPath]').val()));
		frame_obj.file_upload($('#HeaderImgUpload'), $('form input[name=HeaderImgPath]'), $('#HeaderImgDetail'));
		$('#HeaderImgDetail').html(frame_obj.upload_img_detail($('form input[name=HeaderImgPath]').val()));
		frame_obj.map_init();
		frame_obj.reserve_form_init();
		
		$('#reserve_form').submit(function(){return false;});
		$('#reserve_form input:submit').click(function(){
			if(system_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#reserve_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=app_car&a=reserve';
				}else{
					alert(data.msg);
					$('#reserve_form input:submit').attr('disabled', false);
				}
			}, 'json');
		})
	},
	
	news_init:function(){
		$('#news_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#news_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	plugin_init:function(){
		$('#plugin img[plugin]').click(function(){
			var img_obj=$(this);
			$.get('?', 'plugin='+img_obj.attr('plugin')+'&Status='+img_obj.attr('Status'), function(data){
				if(data.ret==1){
					var img=img_obj.attr('Status')==0?'on':'off';
					img_obj.attr('src', '/static/member/images/ico/'+img+'.gif');
					img_obj.attr('Status', img_obj.attr('Status')==0?1:0);
				}else{
					alert('设置失败，出现未知错误！');
				}
			}, 'json');
		});
	},
	
	reserve_list_init:function(){
		$('#search_form input:button').click(function(){
			window.location='./?'+$('#search_form').serialize()+'&do_action=app_car.reserve_export';
		});
		$('#search_form input[name=Time]').daterangepicker();	
	}
}