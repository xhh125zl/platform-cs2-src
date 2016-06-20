var panoramic_obj={
	edit_init:function(){
		$('#panoramic_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#panoramic_form input:submit').attr('disabled', true);
			return true;
		});
	},
	myfun:function(str1,str2){
		var id=str1.split('.')[0].split('_')[2];
		$('#ImgPath_'+id).val(str2);
		$('#ImgDetail_'+id).html(global_obj.img_link(str2));
	},
	
	category_init:function(){
		$('#category_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#category_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	panoramic_init:function(){
		$('#panoramic dl').dragsort({
			dragSelector:'dd.item',
			dragEnd:function(){
				var data=$('#panoramic dl dd.item').map(function(){
					return $(this).attr('CateId');
				}).get();
				$.get('?m=panoramic&a=panoramic', {do_action:'panoramic.category_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'ul, a',
			placeHolderTemplate:'<dd class="item placeHolder"></dd>',
			scrollSpeed:5
		});
		
		$('#panoramic ul').dragsort({
			dragSelector:'li',
			dragEnd:function(){
				var data=$('#panoramic ul li').map(function(){
					return $(this).attr('PId');
				}).get();
				$.get('?m=panoramic&a=panoramic', {do_action:'panoramic.order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'a',
			placeHolderTemplate:'<li class="placeHolder"></li>',
			scrollSpeed:5
		});
		
		$('#panoramic .item ul li').hover(function(){
			$(this).children('.opt').show();
		}, function(){
			$(this).children('.opt').hide();
		});
	}
}