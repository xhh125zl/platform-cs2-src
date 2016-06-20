

var wedding_obj={
	wedding_init:function(){
		global_obj.map_init();
		
		var date_str=new Date();
		$('#wedding_form input[name=Time]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
		
		$('#wedding_form').submit(function(){return false;});
		$('#wedding_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#wedding_form').serialize(), function(data){
				if(data.status==1){
					window.location='wedding.php';
				}else{
					$('#wedding_form input:submit').attr('disabled', false);
					alert(data.msg);
				};
			}, 'json');
		});
	},
	
	wedding_photo_init:function(){
		$('#wedding .photo_list').dragsort({
			dragSelector:'dd.item',
			dragEnd:function(){
				var data=$('#wedding .photo_list ul a[PhotoID]').map(function(){
					return $(this).attr('PhotoID');
				}).get();
				$.get('?', {action:'photo_order', sort_order:data.join('_')});
			},
			dragSelectorExclude:'a',
			placeHolderTemplate:'<dd class="placeHolder"></ul>',
			scrollSpeed:5
		});
		
		$('#wedding .photo_list a[PhotoID]').click(function(){
			var obj=$(this);
			$.get('?action=del', 'PhotoID='+obj.attr('PhotoID'), function(data){
				if(data.status==1){
					obj.parent().parent().parent().remove();
				}
			}, 'json');
			return false;
		});
	},
	
	wedding_photo_upload_init:function(){
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
		
		global_obj.file_upload($('#PhotoUpload'), '', '', 'app_wedding_albums_photo', true, 20, callback);
		$('#wedding_photo_upload_form').submit(function(){
			$('#wedding_photo_upload_form input:submit').attr('disabled', true);
		});
	}
}