/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var app_estate_obj={
	config_init:function(){
		$('#config_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#config_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	intro_init:function(){
		global_obj.map_init();
		$('#config_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#config_form input:submit').attr('disabled', true);
			return true;
		});
	},

	article_init:function(){
		frame_obj.file_upload($('#ImgPathFileUpload'), $('#article_form input[name=ImgPath]'), $('#ImgPathDetail'));
		$('#ImgPathDetail').html(frame_obj.upload_img_detail($('#article_form input[name=ImgPath]').val()));
		frame_obj.map_init();
		
		$('#article_form').submit(function(){
			if(system_obj.check_form($('*[notnull]'))){return false};
			$('#article_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	//楼盘相册
	albums_init:function(){
		$('#albums dl').dragsort({
			dragSelector:'dd.item',
			dragEnd:function(){
				var data=$('#albums dl dd.item').map(function(){
					return $(this).attr('CId');
				}).get();
				$.get('?m=app_estate&a=albums', {do_action:'app_estate.albums_category_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'ul, a',
			placeHolderTemplate:'<dd class="item placeHolder"></dd>',
			scrollSpeed:5
		});
	},

	//楼盘相册首页展示方式设置
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
	},
	
	//贴吧
	microbar_init:function(){
		frame_obj.file_upload($('#MicroBarImgUpload'), $('#microbar_set input[name=MicroBarImgPath]'), $('#MicroBarImgDetail'));
	},
	
	reserve_edit_init:function(){
		global_obj.map_init();
		$('#reserve_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#reserve_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	//户型全景
	household_set:function(){
		$('#household').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#household .submit').attr('disabled', true);
			return true;
		});
	},
	
	household_init:function(){
		$('#household').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#household .submit').attr('disabled', true);
			return true;
		});
	},
	
	//新闻动态
	
	news_init:function(){
		$('#news_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#news_form .submit').attr('disabled', true);
			return true;
		});
	},
}