var login_obj={
	login_init:function(){
		if(window!=top){
			top.location.href=window.location.href;
		}
		
		$('form').submit(function(){return false;});
		$('input:submit').click(function(){
			var flag=false;
			$('#Account, #Password').each(function(){
				if($(this).val()==''){
					$(this).focus();
					flag=true;
					return false;
				}
			});
			if(flag){return;}
			
			$('.login_msg').show().html('身份验证中...');
			$(this).attr('disabled', true);
			
			$.post('?', $('form').serialize(), function(data){
				$('input:submit').attr('disabled', false);
				if(data.status==1){
					window.top.location='./';
				}else if(data.status==2){
					$('.login_msg').show().html('登录失败，错误的用户名或密码！');
				}else if(data.status==3){
					$('.login_msg').show().html('您的帐号已被锁定，无法登录！');
				}else if(data.status==4){
					$('.login_msg').show().html('您的帐号已经到期，无法登录！');
				};
			}, 'json');
		});
		
		$('form input').each(function(){
			$(this).focus(function(){
				$(this).siblings('label').css({display:'none'});
			});
			$(this).blur(function(){
				if($(this).val()==''){
					$(this).siblings('label').css({display:'block'});
				}
			});
		});
	}
}