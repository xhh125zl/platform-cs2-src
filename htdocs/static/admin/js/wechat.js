

var wechat_obj={
	attention_init:function(){
		var display_row=function(){
			if($('select[name=ReplyMsgType]').val()==0){
				$('#text_msg_row').show();
				$('#img_msg_row').hide();
			}else{
				$('#text_msg_row').hide();
				$('#img_msg_row').show();
			}
		}
		
		display_row();
		$('select[name=ReplyMsgType]').on('change blur', display_row);
		$('#attention_reply_form').submit(function(){
			$('#attention_reply_form input:submit').attr('disabled', true);
		});
	},
	
	reply_keyword_init:function(){
		var display_row=function(){
			if($('select[name=ReplyMsgType]').val()==0){
				$('#text_msg_row').show();
				$('#img_msg_row').hide();
			}else{
				$('#text_msg_row').hide();
				$('#img_msg_row').show();
			}
		}
		
		display_row();
		$('select[name=ReplyMsgType]').on('change blur', display_row);
		$('#keyword_reply_form').submit(function(){return false;});
		$('#keyword_reply_form input:submit').click(function(){
			if($('select[name=ReplyMsgType]').val()==0){			
				if(global_obj.check_form($('*[notnull], textarea[name=TextContents]'))){return false};
			}else{
				if(global_obj.check_form($('*[notnull]'))){return false};
			}
			
			$(this).attr('disabled', true);
			$.post('?', $('form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=wechat&a=reply_keyword';
				}else{
					alert(data.msg);
					$('#keyword_reply_form input:submit').attr('disabled', false);
				}
			}, 'json');
		})
	},
	
	set_token_init:function(){
		$('#set_token_form').submit(function(){return false;});
		$('#set_token_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			var btn_value=$('#set_token_form input:submit').val();
			$('.set_token_msg').css({display:'none'}).html('');
			$(this).val('对接中，请耐心等待...').attr('disabled', true);
			
			$.post('?', $('form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=wechat&a=set_token';
				}else{
					$('.set_token_msg').css({display:'block'}).html(data.msg);
					$('#set_token_form input:submit').val(btn_value).attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	menu_init:function(){
		$('#wechat_menu .m_lefter dl').dragsort({
			dragSelector:'dd',
			dragEnd:function(){
				var data=$(this).parent().children('dd').map(function(){
					return $(this).attr('MId');
				}).get();
				$.get('?m=wechat&a=menu', {do_action:'wechat.menu_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'ul, a',
			placeHolderTemplate:'<dd class="placeHolder"></dd>',
			scrollSpeed:5
		});
		
		$('#wechat_menu .m_lefter ul').dragsort({
			dragSelector:'li',
			dragEnd:function(){
				var data=$(this).parent().children('li').map(function(){
					return $(this).attr('MId');
				}).get();
				$.get('?m=wechat&a=menu', {do_action:'wechat.menu_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'a',
			placeHolderTemplate:'<li class="placeHolder"></li>',
			scrollSpeed:5
		});
		
		$('#wechat_menu .m_lefter ul li').hover(function(){
			$(this).children('.opt').show();
		}, function(){
			$(this).children('.opt').hide();
		});
		
		var display_row=function(){
			var v=$('#wechat_menu_form select[name=MsgType]').val();
			if(v==0){
				$('#img_msg_row, #url_msg_row').hide();
				$('#text_msg_row').show();
			}else if(v==1){
				$('#text_msg_row, #url_msg_row').hide();
				$('#img_msg_row').show();
			}else{
				$('#text_msg_row, #img_msg_row').hide();
				$('#url_msg_row').show();
			}
		}
		
		display_row();
		$('#wechat_menu_form select[name=MsgType]').on('change blur', display_row);
		$('#wechat_menu_form').submit(function(){return false;});
		$('#wechat_menu_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=wechat&a=menu';
				}else{
					alert(data.msg);
					$('#wechat_menu_form input:submit').attr('disabled', false);
				}
			}, 'json');
		})
		
		$('#wechat_menu .publish .btn_green').click(function(){
			var btn_value=$(this).val();
			$(this).val('发布中，请耐心等待...').attr('disabled', true);
			$.get('?do_action=wechat.menu_publish', '', function(data){
				$('#wechat_menu .publish .btn_green').val(btn_value).attr('disabled', false);
				if(data.status==1){
					alert('菜单发布成功，24小时后可看到效果，或取消关注再重新关注可即时看到效果！');
				}else{
					alert(data.msg);
				}
			}, 'json');
		});
		
		$('#wechat_menu .publish .btn_gray').click(function(){
			var btn_value=$(this).val();
			$(this).val('删除中...').attr('disabled', true);
			$.get('?do_action=wechat.menu_wx_del', '', function(data){
				$('#wechat_menu .publish .btn_gray').val(btn_value).attr('disabled', false);
				if(data.status==1){
					alert('菜单删除成功，24小时后可看到效果，或取消关注再重新关注可即时看到效果！');
				}else{
					alert(data.msg);
				}
			}, 'json');
		});
	},
	
	auth_init:function(){
		$('#wechat_info_form').submit(function(){return false;});
		$('#wechat_info_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#wechat_info_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=wechat&a=auth';
				}else{
					alert('设置失败，出现未知错误！');
				}
			}, 'json');
		});
	},
	
	spread_init:function(){
		var spread_type=function(){
			if($('#spread_form input[name=SpreadType]:checked').val()==0){
				$('#spread_form .pcas').show();
				$('#spread_form .url').hide();
			}else{
				$('#spread_form .pcas').hide();
				$('#spread_form .url').show();
			}
		}
		$('#spread_form input[name=SpreadType]').click(function(){
			spread_type();
		});
		spread_type();
		
		$('#spread_form').submit(function(){return false;});
		$('#spread_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#spread_form').serialize(), function(data){
				if(data.ret==1){
					window.location='?m=wechat&a=spread';
				}else{
					alert('设置失败，出现未知错误！');
				}
			}, 'json');
		});
	}
	
}