var catering_obj={
	stores_init:function(){
		global_obj.map_init();		
		$('#stores_form').submit(function(){return false;});
		$('#stores_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#stores_form').serialize(), function(data){
				if(data.status==1){
					window.location='store.php';
				}else{
					$('#stores_form input:submit').attr('disabled', false);
					alert(data.msg);
				};
			}, 'json');
		});
	},
	
	products_category_init:function(){
		$('#category_form').submit(function(){return false;});
		$('#category_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#category_form').serialize(), function(data){
				if(data.status==1){
					window.location='category.php';
				}else{
					alert(data.msg);
					$('#category_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	reserve_edit_init:function(){
		global_obj.file_upload($('#HeaderImgUpload'), $('form input[name=HeaderImgPath]'), $('#HeaderImgDetail'));
		$('#HeaderImgDetail').html(global_obj.img_link($('form input[name=HeaderImgPath]').val()));
		global_obj.reserve_form_init();
		
		$('#reserve_form').submit(function(){return false;});
		$('#reserve_form input:submit').click(function(){
			if(system_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#reserve_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=app_catering&a=reserve';
				}else{
					alert(data.msg);
					$('#reserve_form input:submit').attr('disabled', false);
				}
			}, 'json');
		})
	},
	
	products_init:function(){
		$('#products_form').submit(function(){return false;});
		$('#products_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#products_form').serialize(), function(data){
				if(data.status==1){
					window.location='products.php';
				}else{
					$('#products_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	products_list_init:function(){
		$('a[href=#search]').click(function(){
			$('form.r_con_search_form').slideDown();
			return false;
		});
	},
	
	
	
	orders_init:function(){
		$('#search_form input:button').click(function(){
			window.location='./?'+$('#search_form').serialize()+'&do_action=app_catering.orders_export';
		});
		
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate())
		});
		
		$('#orders .cp_title #cp_view, #orders .cp_title #cp_mod').click(function(){
			$('#orders .cp_title div').removeClass('cur');
			$(this).addClass('cur');
			
			if($(this).attr('id')=='cp_view'){
				$('#orders_mod_form .cp_item_view').show();
				$('#orders_mod_form .cp_item_mod').hide();
			}else{
				$('#orders_mod_form .cp_item_view').hide();
				$('#orders_mod_form .cp_item_mod').show();
			}
		});
		$('#orders_mod_form').submit(function(){$('#orders_mod_form .submit .sub').attr('disabled', true);});
		$('#orders_mod_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#orders_mod_form').serialize(), function(data){
				if(data.status==1){
					window.location.reload();
				}else{
					$('#orders_mod_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	print_page_init:function(){
		$('.r_nav').hide();
		$('html,body').css('background','none');
		$('.iframe_content').removeClass('iframe_content');	
		$('.pos_print input[name=print_go]').click(function(){
			window.print();
		});
		$('.pos_print input[name=print_close]').click(function(){
			$(window.parent.document).find('#pos_cont').fadeOut();
		});
	},
	reserve_list_init:function(){
		$('#search_form input:button').click(function(){
			window.location='./?'+$('#search_form').serialize()+'&do_action=app_catering.reserve_export';
		});
		
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});	
	}
}