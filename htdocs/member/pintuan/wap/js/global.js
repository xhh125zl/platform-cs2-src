$(document).ready(function(){global_obj.page_init()});

var global_obj={
	hide_opt_menu:function(){
		if(typeof window.WeixinJSBridge=='undefined'){
			document.addEventListener('WeixinJSBridgeReady', function onBridgeReady(){
				WeixinJSBridge.call('hideOptionMenu');
			});
		}else{
			WeixinJSBridge.call('hideOptionMenu');
		}
	},
	
	share_init:function(share_info){
		share_info.link=global_obj.url_filter(share_info.link, 'OpenId');
		if(typeof window.WeixinJSBridge=='undefined'){
			document.addEventListener('WeixinJSBridgeReady', function onBridgeReady(){
				WeixinJSBridge.on('menu:share:appmessage', function(){WeixinJSBridge.invoke('sendAppMessage', share_info)});
				WeixinJSBridge.on('menu:share:timeline', function(){WeixinJSBridge.invoke('shareTimeline', share_info)});
			});
		}else{
			WeixinJSBridge.on('menu:share:appmessage', function(){WeixinJSBridge.invoke('sendAppMessage', share_info)});
			WeixinJSBridge.on('menu:share:timeline', function(){WeixinJSBridge.invoke('shareTimeline', share_info)});
		}
	},
	
	page_init:function(){
		$('a').each(function(){
			var url=$(this).attr('href');
			var rel=$(this).attr('rel');
			if(url && url.indexOf('tel:')==-1 && url.indexOf('javascript:')==-1 && url.indexOf('wxref=mp.weixin.qq.com')==-1 && url.indexOf('#')==-1 && !rel){
				if(url.charAt(url.length-1)=='/'){
					$(this).attr('href', url+'?wxref=mp.weixin.qq.com');
				}else if(url.indexOf('?')==-1){
					$(this).attr('href', url+'?wxref=mp.weixin.qq.com');
				}else{
					$(this).attr('href', url+'&wxref=mp.weixin.qq.com');
				}
			}
		});
	},
	
	div_mask:function(remove){
		if(remove==1){
			$('#div_mask').remove();
		}else{
			$('body').prepend('<div id="div_mask"></div>');
			$('#div_mask').css({
				width:'100%',
				height:$(document).height(),
				overflow:'hidden',
				position:'fixed',
				top:0,
				left:0,
				background:'#000',
				opacity:0.6,
				'z-index':10000
			});
		}
	},
	
	win_alert:function(tips, handle){
		$('body').prepend('<div id="global_win_alert"><div>'+tips+'</div><h1>好</h1></div>');
		$('#global_win_alert').css({
			position:'fixed',
			left:$(window).width()/2-125,
			top:'30%',
			background:'#fff',
			border:'1px solid #ccc',
			opacity:0.95,
			width:250,
			'z-index':100000,
			'border-radius':'8px'
		}).children('div').css({
			'text-align':'center',
			padding:'30px 10px',
			'font-size':16
		}).siblings('h1').css({
			height:40,
			'line-height':'40px',
			'text-align':'center',
			'border-top':'1px solid #ddd',
			'font-weight':'bold',
			'font-size':20
		});
		$('#global_win_alert h1').click(function(){
			$('#global_win_alert').remove();
		});
		if($.isFunction(handle)){
			$('#global_win_alert h1').click(handle);
		}
	},
	
	check_form:function(obj){
		var flag=false;
		obj.each(function(){
			if($(this).val()==''){
				$(this).css('border', '1px solid red');
				flag==false && ($(this).focus());
				flag=true;
			}else{
				$(this).removeAttr('style');
			}
		});
		return flag;
	},
	
	url_filter:function(url){
		var aParams=url.substr(url.indexOf('?')+1).split('&');
		var url=url.substr(0, url.indexOf('?'));
		var reqstr='';
		var argumentslen=arguments.length;
		var argumentstr='&';
		if(argumentslen>1){
			for(var i=1; i<argumentslen; i++){
				argumentstr+=arguments[i].toString()+'&';
			}
		}
		if(aParams.length>0){
			for(i=0; i<aParams.length; i++){
				var aParam=aParams[i].split('=');
				if(aParam[0]!='' && argumentstr.indexOf('&'+aParam[0]+'&')<0){
					reqstr+=aParam[0]+'='+aParam[1]+'&';
				}
			}
		}
		url=(reqstr.lastIndexOf('&')>0)?url+'?'+reqstr.substring(0, reqstr.length-1):url;
		return url;
	}
}