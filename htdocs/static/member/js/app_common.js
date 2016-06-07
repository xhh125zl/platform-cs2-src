/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var app_common_obj={
	config_init:function(){
		$('#config_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#config_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	stores_init:function(){
		global_obj.map_init();
		$('#stores_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#stores_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	albums_init:function(){
		$('#albums dl').dragsort({
			dragSelector:'dd.item',
			dragEnd:function(){
				var data=$('#albums dl dd.item').map(function(){
					return $(this).attr('CId');
				}).get();
				$.get('?m=app_common&a=albums', {do_action:'app_common.albums_category_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'ul, a',
			placeHolderTemplate:'<dd class="item placeHolder"></dd>',
			scrollSpeed:5
		});
	},
	
	albums_set_init:function(){
		$('#albums-list-type .item').removeClass('item_on').each(function(){
			$(this).click(function(){
				$('#albums-list-type .item').removeClass('item_on');
				$(this).addClass('item_on');
				$('#albums_set_form input[name=DisplayType]').val($(this).attr('DisplayType'));
			});
		}).filter('[DisplayType='+$('#albums_set_form input[name=DisplayType]').val()+']').addClass('item_on');
	},
	
	albums_category_init:function(){
		global_obj.file_upload($('#ImgUpload'), $('#albums_category_form input[name=ImgPath]'), $('#ImgDetail'));
		$('#ImgDetail').html(global_obj.img_link($('#albums_category_form input[name=ImgPath]').val()));
		
		$('#albums-list-type .item').removeClass('item_on').each(function(){
			$(this).click(function(){
				$('#albums-list-type .item').removeClass('item_on');
				$(this).addClass('item_on');
				$('#albums_category_form input[name=DisplayType]').val($(this).attr('DisplayType'));
			});
		}).filter('[DisplayType='+$('#albums_category_form input[name=DisplayType]').val()+']').addClass('item_on');
		
		$('#albums_category_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#albums_category_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	albums_photo_init:function(){
		$('#albums .albums_info .r span').html($('#albums .photo_list ul').size());
		$('#albums .photo_list').dragsort({
			dragSelector:'dd.item',
			dragEnd:function(){
				var data=$('#albums .photo_list ul a[PhotoID]').map(function(){
					return $(this).attr('PhotoID');
				}).get();
				$.get('?action=photo_order', {sort_order:data.join('_')});
			},
			dragSelectorExclude:'a',
			placeHolderTemplate:'<dd class="placeHolder"></ul>',
			scrollSpeed:5
		});
		
		$('#albums .photo_list a[PhotoID]').click(function(){
			var obj=$(this);
			$.get('?action=del', 'PhotoID='+obj.attr('PhotoID'), function(data){
				if(data.status==1){
					obj.parent().parent().parent().remove();
					$('#albums .albums_info .r span').html($('#albums .photo_list ul').size());
				}
			}, 'json');
			return false;
		});
	},
	
	albums_photo_upload_init:function(){
		var callback=function(imgpath){
			if($('#PicDetail div').size()>=20){
				alert('您上传的图片数量已经超过20张，不能再上传！');
				return;
			}
			
			$('#upload_img').append('<li>'+$('#for_copy').html()+'</li>');
			$('#upload_img li:last').find('.imgpath a').attr('href', imgpath).end().find('.imgpath img').attr('src', imgpath).end().find('.del').click(function(){
				$(this).parent().parent().remove();
			}).end().find('input[name=ImgPath\\[\\]]').val(imgpath);
		};
		
		$('#albums_photo_upload_form').submit(function(){
			$('#albums_photo_upload_form input:submit').attr('disabled', true);
		});
	},
	
	albums_photo_mod_init:function(){
		$('#albums_photo_mod_form').submit(function(){
			$('#albums_photo_mod_form input:submit').attr('disabled', true);
		});
	}
}