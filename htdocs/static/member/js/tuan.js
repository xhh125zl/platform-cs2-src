

var tuan_obj={
	products_init:function(){
		var date_str=new Date();
		$('#products_form input[name=Time]').daterangepicker({
			timePicker:true,
			//minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			format:'YYYY/MM/DD HH:mm:00'}
		);
		$('#PicDetail div span').on('click', function(){$(this).parent().remove();});
		
		var callback=function(imgpath){
			if($('#PicDetail div').size()>=5){
				alert('您上传的图片数量已经超过5张，不能再上传！');
				return;
			}
			$('#PicDetail').append('<div>'+global_obj.img_link(imgpath)+'<span>删除</span><input type="hidden" name="PicPath[]" value="'+imgpath+'" /></div>');
			$('#PicDetail div span').off('click').on('click', function(){$(this).parent().remove();});
		};
		
		$('#products_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#products_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	products_list_init:function(){
		$('a[href=#search]').click(function(){
			$('form.search').slideDown();
			return false;
		});
	},
	
	shopping_init:function(){
		$('#shopping .shipping .m_lefter dl').dragsort({
			dragSelector:'dd',
			dragEnd:function(){
				var data=$(this).parent().children('dd').map(function(){
					return $(this).attr('SId');
				}).get();
				$.get('?m=tuan&a=shopping', {do_action:'tuan.shopping_shipping_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'a',
			placeHolderTemplate:'<dd class="placeHolder"></dd>',
			scrollSpeed:5
		});
		
		$('#tuan_shipping_form').submit(function(){return false;});
		$('#tuan_shipping_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#tuan_shipping_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=tuan&a=shopping';
				}else{
					alert(data.msg);
					$('#tuan_shipping_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
		
		$('#shop_payment_form').submit(function(){return false;});
		$('#shop_payment_form .submit input').click(function(){
			$(this).attr('disabled', true);
			$.post('?', $('#shop_payment_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=tuan&a=shopping';
				}else{
					$('#shop_payment_form .submit input').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	orders_init:function(){
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
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
		$('#orders_mod_form .cp_item_mod .back').click(function(){$('#orders .cp_title #cp_view').click();});
	}
}