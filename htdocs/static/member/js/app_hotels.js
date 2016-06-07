
var app_hotels_obj={
	config_init:function(){
		global_obj.map_init();
		$('#hotels_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#hotels_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	//酒店房间管理
	products_init:function(){
		$('#PicDetail div span').on('click', function(){$(this).parent().remove();});
		var pic_count=parseInt($('#pic_count').html());
		
		var callback=function(imgpath){
			if($('#PicDetail div').size()>=pic_count){
				alert('您上传的图片数量已经超过5张，不能再上传！');
				return;
			}
			$('#PicDetail').append('<div>'+frame_obj.upload_img_detail(imgpath)+'<span>删除</span><input type="hidden" name="PicPath[]" value="'+imgpath+'" /></div>');
			$('#PicDetail div span').off('click').on('click', function(){$(this).parent().remove();});
		};
		
		frame_obj.file_upload($('#PicUpload'), '', '', 'app_hotels_products', true, pic_count, callback);
		$('#products_form').submit(function(){
			if(system_obj.check_form($('*[notnull]'))){return false};
			$('#products_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	products_list_init:function(){
		$('a[href=#search]').click(function(){
			$('form.r_con_search_form').slideDown();
			return false;
		});
	},
	
	reserve_edit_init:function(){
		frame_obj.reserve_form_init();
		
		$('#reserve_form').submit(function(){return false;});
		$('#reserve_form input:submit').click(function(){
			if(system_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#reserve_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=app_hotels&a=reserve';
				}else{
					alert(data.msg);
					$('#reserve_form input:submit').attr('disabled', false);
				}
			}, 'json');
		})
	},
	
	reserve_list_init:function(){
		$('#search_form input:button').click(function(){
			window.location='./?'+$('#search_form').serialize()+'&do_action=app_hotels.reserve_export';
		});
		$('#search_form input[name=Time]').daterangepicker();	
	}
}