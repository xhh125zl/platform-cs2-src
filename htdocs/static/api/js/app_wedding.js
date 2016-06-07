var wedding_obj={
	wedding_init:function(){
		var musicPlayer;
		
		if(_wedding.MusicPath){
			musicPlayer = document.createElement('audio');
			musicPlayer.loop = true;
			musicPlayer.controls = false;
			musicPlayer.id = 'audio';
			musicPlayer.src = _wedding.MusicPath;
			
			musicPlayer.autoplay = true;
			musicPlayer.isLoadedmetadata = false;
			musicPlayer.touchstart = true;
			musicPlayer.audio = true;
			
			$('body').append(musicPlayer);
			$('#music_id').addClass('on');
			
			if(/i(Phone|P(o|a)d)/.test(navigator.userAgent)){
				musicPlayer.autoplay=false;
				musicPlayer.load();
				$('#music_id').removeClass('on');
				/*$(document).one('touchstart', function (e) {
					musicPlayer.touchstart = true;
					musicPlayer.play();
					musicPlayer.pause();
					return false;
				});*/
			}			
		}else{
			$('#music_id').hide();
			$('#music_id').remove();
		}
		
		$('#music_id').click(function(){
			if(musicPlayer) {
				$(this).toggleClass("on");
				if($(this).hasClass('on')){
					musicPlayer.play();
				}else{
					musicPlayer.pause();
				}
			}
		});
	},
	
	index_init:function(){
		if(_wedding.PhotoPath) {
			$('#wedding .banner').html('<img src="'+_wedding.PhotoPath+'" />');
		}else{
			$('#wedding .banner').css('height','12px');
		}
		
		if(_wedding.VideoPath) {
			/*videoPlayer = document.createElement('video');
			videoPlayer.loop = false;
			videoPlayer.id = 'video';
			videoPlayer.src = _wedding.VideoPath;*/
			if(_wedding.VideoPath.indexOf('.mp4')>-1){
				videoPlayer='<video id="video"><source src="'+_wedding.VideoPath+'" type="video/mp4" /></video>';
			}else{
				videoPlayer=_wedding.VideoPath.replace('height=498 width=510','height=auto width=100%');
			}
			$('#wedding .video').append(videoPlayer);
		}else{
			$('#wedding .video').remove();
		}
		
		/*$('#wedding video').click(function(){
			if($(this).paused){
				alert('11');
				$('#wedding audio').play();
				$('#music_id').addClass('on');
			}else{
				alert('22');
				$('#wedding audio').pause();
				$('#music_id').removeClass('on');
			}
		});*/
	},
	
	wish_init:function(){
		//加载更多祝福
		$('#wedding .wish .more a').click(function(){
			var WeddingID=$(this).attr('data-WeddingID');
			var page=$(this).attr('data-page');
			$('#wedding .loading').show();
			$('#wedding .more').hide();
			
			$.post('?', 'action=more&WeddingID='+WeddingID+'&page='+page, function(data){
				if(data.status==1){
					$('#wedding .wish .more a').attr('data-page', data.page);
					var have_new=false;
					var html='';
					for(var i=0; i<data.msg.length; i++){
						if($('#wedding .wish li[WishID='+data.msg[i]['WishID']+']').size()==0){	//回复不在列表中
							have_new=true;

							html+='<li WishID="'+data.msg[i]['WishID']+'">';
								html+='<div class="hd">';
									html+='<img alt="Male" src="/static/api/images/app_wedding/'+data.msg[i]['Gender']+'.jpg">';
									html+='<span>'+data.msg[i]['Name']+'</span>';
								html+='</div>';
								html+='<div class="bd">';
									html+='<p class="p-wall">'+data.msg[i]['Message']+'</p>';
									html+='<p class="p-time">发表于：'+data.msg[i]['CreateTime']+'</p>';
								html+='</div>';
							html+='</li>';
						}
					}
					if(have_new){
						$('#wedding .wish ul').append(html);
					}
					if(data.hide==1){
						$('#wedding .more, #wedding .loading').remove();
					}
				}else{
					$('#wedding .more, #wedding .loading').remove();
					global_obj.win_alert(data.msg);
				};
				$('#wedding .loading').hide();
				$('#wedding .more').show();
			}, 'json');
			return false;
		});
		
		
		//送上祝福
		$('form').submit(function(e) {return false;});
		$('form input:submit').click(function(){
			var Name=$('form input[Name=Name]');
			var Message=$('form textarea[Name=Message]');
			if(Name.val()==''){
				global_obj.win_alert('姓名不能为空！', function(){
					Name.focus();
				});
				return false;
			}
			if(Message.val()==''){
				global_obj.win_alert('祝福内容不能为空！', function(){
					Message.focus();
				});
				return false;
			}
			
			
			$(this).attr('disabled', true).val('提交祝福中...');
			$.post('?', $('form').serialize()+'&action=wish', function(data){
				if(data.status==1){
					var html='';
					html+='<li WishID="'+data.WishID+'">';
						html+='<div class="hd">';
							html+='<img alt="Male" src="/static/api/images/app_wedding/'+data.Gender+'.jpg">';
							html+='<span>'+data.Name+'</span>';
						html+='</div>';
						html+='<div class="bd">';
							html+='<p class="p-wall">'+data.Message+'</p>';
							html+='<p class="p-time">发表于：'+data.CreateTime+'</p>';
						html+='</div>';
					html+='</li>';
					
					$(html).prependTo('#wedding .wish ul');
					global_obj.win_alert('已成功送上您的祝福！', function(){
						$('form input[Name=Name], form textarea[Name=Message]').val('');
						$('form input:submit').attr('disabled', false).val('送上祝福');
					});
				}else{
					global_obj.win_alert('请填写姓名、祝福内容！', function(){
						$('form input:submit').attr('disabled', false).val('送上祝福');
					});
				};
			}, 'json');
		});
	},
	
	photo_init:function(){
		(function(window, $, PhotoSwipe){
			$('#wedding .photo a[rel]').photoSwipe({});
		}(window, window.jQuery, window.Code.PhotoSwipe));
	}
}