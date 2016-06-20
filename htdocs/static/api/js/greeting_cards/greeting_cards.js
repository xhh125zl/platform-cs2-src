
var greeting_cards_obj={
	greeting_cards_init:function(){
		$('#greeting_cards, form').css({
			background:'url('+$('input[name=BgImgPath]').val()+')',
			backgroundSize:'100% 100%'
		});
		
		$('form .header img').click(function(){
			$('#greeting_cards').show();
			$('form').hide();
		});
		
		$('.send_card span').click(function(){
			$('#greeting_cards').hide();
			$('form').show();
		});
		$('.send_btn').click(function(){
			$('#greeting_cards').hide();
			$('form').show();
		});
		
		if(!$('#global_support').size()){
			$('#greeting_cards .send_btn').css('bottom', 8);
			$('#greeting_cards .contents').css('bottom', 50);
		}
		
		musicPlayer=document.createElement('audio');
		musicPlayer.loop=true;
		musicPlayer.controls=false;
		musicPlayer.id='audio';
		musicPlayer.src=$('input[name=MusicPath]').val();
		musicPlayer.style.display='';
		musicPlayer.autoplay=true;
		musicPlayer.isLoadedmetadata=false;
		musicPlayer.touchstart=true;
		musicPlayer.audio=true;
		$('body').append(musicPlayer);
		
		if(/i(Phone|P(o|a)d)/.test(navigator.userAgent)){
			musicPlayer.autoplay=false;
		}
		$('*').click(function(){
			musicPlayer.play();
		});
		
		var ToName=$('.contents .txt h1').html();
		var Contents=$('.contents .txt h2').html();
		var SendName=$('.contents .txt h3').html();
		var cur_str=0;
		var cur_obj=0;
		
		$('.contents .txt h1, .contents .txt h2, .contents .txt h3').html('').show();
		var show_text=function(obj, str){
			obj.html(str.slice(0, cur_str++));
            if(cur_str>str.length){
				cur_str=0;
				if(cur_obj==0){
					cur_obj++;
					show_text($('.contents .txt h2'), Contents);
				}else if(cur_obj==1){
					cur_obj++;
					show_text($('.contents .txt h3'), SendName);
				}
            }else{
				setTimeout(function(){show_text(obj, str)}, 150);
			}
        }
		show_text($('.contents .txt h1'), ToName);
		
		global_obj.share_init({
			'img_url':'http://'+document.domain+$('input[name=BgImgPath]').val(),
			'img_width':100,
			'img_height':100,
			'link':window.location.href,
			'desc':Contents,
			'title':'收到一张来自“'+SendName+'”的贺卡'
		});
		$('.share_layer').click(function(){
			$(this).hide();
		});
		$('form').submit(function(){return false;});
		$('form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$('.share_layer').css('height', $(document).height()).show();
			return false;
			global_obj.share_init({
				'img_url':'http://'+document.domain+$('input[name=BgImgPath]').val(),
				'img_width':100,
				'img_height':100,
				'link':'http://'+document.domain,
				'desc':Contents,
				'title':'收到一张来自“'+SendName+'”的贺卡'
			});
		});	
	}
}