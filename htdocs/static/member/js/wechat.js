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
		$('#menu_form #msgtype').change(function(){
			var va = $(this).val();
			for(var i=0; i<=2; i++){
				if(i==va){
					$("#menu"+va).show();
				}else{
					$("#menu"+i).hide();
				}
			}			
		});
		
		$('#menu_form #btn_select_url').click(function(){
			global_obj.create_layer('选择链接', '/member/material/sysurl.php?dialog=1&input=menu',1000,500);
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
	},
	
	message_init:function(){
		var msg_type;
		var msg_type_o=$('#message_form select[name=ModelID]');
		var add_btn_o=$('#message_form .input_add');
		var data_o=$('#message_form .data');
		var change_msg_type=function(){
			msg_type=msg_type_o.find('option:selected').attr('rel');
			var html='';
			$.each(msg_field[msg_type], function(index, value){
				html+='<option value="'+index+'">'+value+'</option>';
			});
			html+='<option value="">--自定义内容--</option>';
			$('#message_form .data').find('select').empty().append(html);
		}
		var custom_cancel=function(){
			data_o.find('.custom div').off().click(function(){
				var o=$(this).parent().parent();
				o.find('select').show();
				o.find('select').parent().next().hide();
				o.find('option:first').attr('selected', true);
			});
		}
		data_o.find('select').change(function(){
			if($(this).val()==''){
				$(this).hide();
				$(this).parent().next().show();
			}
		});
		change_msg_type();
		custom_cancel();
		msg_type_o.change(change_msg_type);
		data_o.find('input:first').attr('placeholder', '字段名称');
		data_o.find('input:last').attr('placeholder', '自定义内容');
		add_btn_o.click(function(){
			data_o.parent('.input').append($('#message_form .data:last').clone(true));
			$('#message_form .data:last input').val('');
			$('#message_form .data:last select').show().find('option:first').attr('selected', true);;
			$('#message_form .data:last .custom').hide();
			custom_cancel();
		});
		
		data_o.find('select[rel]').each(function(){
			$(this).find('option[value='+$(this).attr('rel')+']').attr('selected', true);
			if($(this).val()==''){
				$(this).hide();
				$(this).parent().next().show();
			}
		});
		
		$('#message_form').submit(function(){return false;});
		$('#message_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};			
			$(this).attr('disabled', true);
			$.post('?', $('form').serialize(), function(data){
				if(data.status==1){
					if(confirm(data.msg)){						
						window.location='message.php';
					}else{
						window.location='message.php';
					}
				}else{
					alert(data.msg);
					$('#message_form input:submit').attr('disabled', false);
				}
			}, 'json');
		})	
	}
}