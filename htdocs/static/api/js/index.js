/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var index_obj={
	index_init:function(){
		var musicPlayer;
		if(MusicPath!=''){
			$('#header li.music').show();
			$('#header li').css({width:100/($('#header li').size()-$('#header li:hidden').size())-0.001+'%'});
			
			musicPlayer = document.createElement('audio');
			musicPlayer.loop = false;
			musicPlayer.controls = false;
			musicPlayer.id = 'audio';
			musicPlayer.src = MusicPath;
			musicPlayer.style.display='none';
			musicPlayer.autoplay = true;
			musicPlayer.isLoadedmetadata = false;
			musicPlayer.touchstart = true;
			musicPlayer.audio = true;
			$('#header li.music a').append(musicPlayer);

			if(/i(Phone|P(o|a)d)/.test(navigator.userAgent)){
				musicPlayer.autoplay=false;
				//musicPlayer.load();
				$('#header li.music a').toggleClass("on");
			}
			
			/*$('*').click(function(){
				if(musicPlayer) {
					musicPlayer.play();
				}
			});*/

			$('#header li.music a').click(function(){
				$(this).toggleClass("on");
				if($(this).hasClass('on')){
					musicPlayer.play();
				}else{
					musicPlayer.pause();
				}
				return false;
			});
		}
		for(i=0; i<web_skin_data.length; i++){
			var obj=$("#web_skin_index div").filter('[rel=edit-'+web_skin_data[i]['Postion']+']');
			if(web_skin_data[i]['ContentsType']==1){
				var dataImg=eval("("+web_skin_data[i]['ImgPath']+")");
				var dataUrl=eval("("+web_skin_data[i]['Url']+")");
				var dataTitle=eval("("+web_skin_data[i]['Title']+")");
				var _banner='<div class="slider"><div class="flexslider"><ul class="slides">';
				for(var k=0; k<dataImg.length; k++){
					if(dataImg[k].indexOf('http://')!=-1){
						var s='';
					}else if(dataImg[k].indexOf('/u_file/')!=-1){
						var s=domain.img;
						dataImg[k]=dataImg[k].replace('/u_file', '');
					}else if(dataImg[k].indexOf('/api/')!=-1){
						var s=domain.static;
					}else{
						var s='';
					}
					
					if(web_skin_data[i]['NeedLink']==1){
						var h=(dataUrl[k].indexOf('/web/')==-1 || dataUrl[k].slice(-5)=='/web/')?'href':'ajax_url';
						_banner=_banner+'<li><a '+h+'="'+dataUrl[k]+'"><img src="'+s+dataImg[k]+'" alt="'+dataTitle[k]+'" /></a></li>';
					}else{
						_banner=_banner+'<li><img src="'+s+dataImg[k]+'" alt="'+dataTitle[k]+'" /></li>';
					}
				}
				var _banner=_banner+'</ul></div></div>';
				
				obj.find('.img').html(_banner);
				obj.find('.flexslider').flexslider({animation:"slide"});
				$('.flex-control-nav, .flex-direction-nav').remove();
			}else{
				var _Url='', h='', s='';
				if(web_skin_data[i]['NeedLink']==1){
					_Url=web_skin_data[i]['Url']?web_skin_data[i]['Url']:'';
					h=(_Url=='' || _Url.indexOf('/web/')==-1 || _Url.slice(-5)=='/web/')?'href':'ajax_url';
				}
				
				if(web_skin_data[i]['ImgPath'].indexOf('http://')!=-1){
					var s='';
				}else if(web_skin_data[i]['ImgPath'].indexOf('/u_file/')!=-1){
					var s=domain.img;
					web_skin_data[i]['ImgPath']=web_skin_data[i]['ImgPath'].replace('/u_file', '');
				}else if(web_skin_data[i]['ImgPath'].indexOf('/api/')!=-1){
					var s=domain.static;
				}else{
					var s='';
				}
				
				var _Img=_Url?'<a '+h+'="'+_Url+'"><img src="'+s+web_skin_data[i]['ImgPath']+'" /></a>':'<img src="'+s+web_skin_data[i]['ImgPath']+'" />';
				var _Title=_Url?'<a '+h+'="'+_Url+'">'+web_skin_data[i]['Title']+'</a>':web_skin_data[i]['Title'];
				obj.find('.img').html(_Img);
				obj.find('.text').html(_Title);
			}
		}
		web_obj.a_label('#web_skin_index');	//A连接处理
		
		if($.isFunction(skin_index_init)){
			skin_index_init();	//风格的首页如果有JS，需全部写入本函数，如果直接写在index.php文件，在后台管理首页广告图片时，会把不必要的JS也执行了
			if($('#header').css('display')=='none' && $('#header li.music').size()){
				$('body').append('<div id="MusicControl" class="on"></div>');
				if(/i(Phone|P(o|a)d)/.test(navigator.userAgent)){
					$('#MusicControl').toggleClass("on");
				}
				$('#MusicControl').click(function(){
					$(this).toggleClass("on");
					if($(this).hasClass('on')){
						musicPlayer.play();
					}else{
						musicPlayer.pause();
					}
					return false;
				});
			}
		}
	}
}