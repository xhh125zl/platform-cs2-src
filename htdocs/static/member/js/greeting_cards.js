/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var greeting_cards_obj={
	greeting_cards_edit_init:function(){
		
		global_obj.file_upload($('#ReplyImgUpload'), $('#greeting_cards_form input[name=ReplyImgPath]'), $('#ReplyImgDetail'));
		
		global_obj.file_upload($('#BgImgUpload'), $('#greeting_cards_form input[name=BgImgPath]'), $('#BgImgDetail'));
		
		global_obj.file_upload($('#MusicUpload'), '', '', '', false, 1, function(filename,filepath){
			$('#greeting_cards_form input[name=MusicPath]').val(filepath);
		}, '*.mp3', '500KB');
		
		
		
		var set_bg_img=function(){
			if($('#greeting_cards_form input[name=SetToDefaultBgImg]:checked').size()){
				$('.up_bg_img').hide();
			}else{
				$('.up_bg_img').show();
			};
		}
		set_bg_img();
		$('#greeting_cards_form input[name=SetToDefaultBgImg]').click(function(){
			set_bg_img();
		});
		
		$('#greeting_cards_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#greeting_cards_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	greeting_cards_diy_edit_init:function(){
		frame_obj.file_upload($('#MusicUpload'), '', '', '', false, 1, function(filepath){
			$('#greeting_cards_form input[name=MusicPath]').val(filepath);
		}, '*.mp3', 500);
		
		frame_obj.file_upload($('#ReplyImgUpload'), $('#greeting_cards_form input[name=ReplyImgPath]'), $('#ReplyImgDetail'));
		$('#ReplyImgDetail').html(frame_obj.upload_img_detail($('#greeting_cards_form input[name=ReplyImgPath]').val()));
		frame_obj.file_upload($('#BgImgUpload'), $('#greeting_cards_form input[name=BgImgPath]'), $('#BgImgDetail'));
		$('#BgImgDetail').html(frame_obj.upload_img_detail($('#greeting_cards_form input[name=BgImgPath]').val()));
		frame_obj.file_upload($('#GifImgUpload'), $('#greeting_cards_form input[name=GifImgPath]'), $('#GifImgDetail'));
		$('#GifImgDetail').html(frame_obj.upload_img_detail($('#greeting_cards_form input[name=GifImgPath]').val()));
		
		$('#greeting_cards_form').submit(function(){return false;});
		$('#greeting_cards_form input:submit').click(function(){
			if(system_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#greeting_cards_form').serialize(), function(data){
				if(data.ret==1){
					window.location='?m=greeting_cards&a=diy';
				}else{
					$('#greeting_cards_form input:submit').attr('disabled', false);
					alert(data.msg);
				};
			}, 'json');
		});
	}
}