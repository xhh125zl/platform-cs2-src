//document.write("<script language=\"javascript\" src=\"\/static\/api\/js\/jweixin-1.0.0.js\" ><\/script>");
document.write("<script language=\"javascript\" src=\"http:\/\/res.wx.qq.com\/open\/js\/jweixin-1.0.0.js\" ><\/script>");
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
	
	show_opt_menu:function(){
		if(typeof window.WeixinJSBridge=='undefined'){
			document.addEventListener('WeixinJSBridgeReady', function onBridgeReady(){
				WeixinJSBridge.call('showOptionMenu');
			});
		}else{
			WeixinJSBridge.call('showOptionMenu');
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
	
	share_init_config:function(){
		if(typeof(share_config)!='undefined'){
			document.title=share_config.title
			$(window).load(function(){
				wx.config({
					appId:share_config.appId,
					timestamp:share_config.timestamp,
					nonceStr:share_config.nonceStr,
					signature:share_config.signature,
					jsApiList:['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'startRecord', 'stopRecord', 'onVoiceRecordEnd', 'playVoice', 'pauseVoice', 'stopVoice', 'onVoicePlayEnd', 'uploadVoice', 'downloadVoice', 'chooseImage', 'previewImage', 'uploadImage', 'downloadImage', 'translateVoice', 'getNetworkType', 'openLocation', 'getLocation', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems', 'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'closeWindow', 'scanQRCode']
				});
				wx.ready(function(){
					global_obj.share_init(share_config);	
				});
			});
		}
	},
	
	share_init:function(share){
		
		(typeof(share.link)=='undefined' || !share.link) && (share.link=window.location.href);
		(typeof(share.title)=='undefined' || !share.title) && (share.title=document.title);
		(typeof(share.desc)=='undefined' || !share.desc) && (share.desc=share.title);
		(typeof(share.trans)=='undefined' || !share.trans) && (share.trans=1);

		var url_filter=function(url){
			if(url.indexOf('?')!=-1){
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
				for(i=0; i<aParams.length; i++){
					var aParam=aParams[i].split('=');
					if(aParam[0]!='' && argumentstr.indexOf('&'+aParam[0]+'&')<0){
						reqstr+=aParam[0]+'='+aParam[1]+'&';
					}
				}
				url=url+'?';
				url=reqstr.lastIndexOf('&')>0?url+reqstr.substring(0, reqstr.length-1):url;
				return url;
			}else{
				return url;
			}
		}
		
		share.link=url_filter(share.link);
		var appmessage_share={
			imgUrl:share.img_url,
			link:share.link,
			title:share.title,
			desc:share.desc,
			success: function (res) {
				$.post('','action_do=share', function(data){
				  if(data.status==1){
				     if(data.url){
					    window.location=data.url;
					 }
			      }else{
					 if(data.msg){
				         global_obj.win_alert(data.msg);
				      }
				  }
			   },'json')
			}
		}		
		
		var timeline_share={
			imgUrl:share.img_url,
			link:share.link,
			title:share.trans?share.desc:share.title,
			desc:share.trans?share.title:share.desc,
			success:function(){
			   $.post('','action_do=share', function(data){
				  if(data.status==1){
				     if(data.url){
					    window.location=data.url;
					 }
			      }else{
					 if(data.msg){
				         global_obj.win_alert(data.msg);
				      }
				  }
			   },'json')
			}
		}
		wx.onMenuShareTimeline(timeline_share);
		wx.onMenuShareAppMessage(appmessage_share);
	},
	
	attention_layer:function(remove,keyword){//提示用户关注我们
		if(keyword.indexOf('|')>-1){
			var re = new RegExp("|","g");
			var arr = keyword.match(re);
			for(var i=0;i<arr.length;i++){
				keyword=keyword.replace('|','&nbsp;');
			}
		}
		if(remove==1){
			$('#global_share_layer').remove();
			return;
		}
		$('body').prepend('<div id="global_share_layer"><div></div></div>');
		$('#global_share_layer').css({
			width:'100%',
			height:'100%',
			overflow:'hidden',
			position:'fixed',
			top:0,
			left:0,
			background:'#000',
			opacity:0.8,
			'z-index':100000
		}).children('div').css({
			width:'100%',
			height:'100%',
			background:'url(/static/api/images/global/share/attention.png) left top no-repeat',
			'background-size':'100% auto',
			position:'relative',
			left:0,
			color:'#fff',
			top:0
		});
		$('#global_share_layer div').html('<div style="position:absolute; bottom:20%; width:100%; text-align:center; height:20px; font-size:18px; line-height:20px;">发送关键词<span style="color:#f00;">'+keyword+'</span>可以参加本活动</div>');
		$('#global_share_layer').click(function(){
			$('#global_share_layer').remove();
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
	
	win_prompt:function(tips){
		$('body').prepend('<div id="global_win_prompt"><div>'+tips+'</div><input type="input" id="global_win_input" pattern="[0-9]*" value="" /><h1 id="ok">确定</h1><h1 id="no">取消</h1></div>');
		$('#global_win_prompt').css({
			left:$(window).width()/2-125,
		})
		$('#global_win_prompt h1#no').click(function(){
			$('#global_win_prompt').remove();
		});
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