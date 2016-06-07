var account_obj={
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
	},
	
	index_init:function(){
		$('a[group]').click(function(){
			var group=$(this).attr('group');
			if(group=='#'){
				parent.$('#main .menu dt').removeClass('cur');
				parent.$('#main .menu dd').hide();
			}else{
				parent.$('#main .menu dt').removeClass('cur');
				parent.$('#main .menu dt[group='+group+']').addClass('cur').next().filter('dd').show();
			}
			parent.$('#main .menu div').removeClass('cur');
			if($(this).attr('url')){
				parent.$('#main .menu a[href="'+$(this).attr('url')+'"]').parent().addClass('cur');
			}else{
				parent.$('#main .menu a[href="'+$(this).attr('href')+'"]').parent().addClass('cur');
			}
			parent.main_obj.page_scroll_init();
		});
		
		global_obj.chart_par.height='347';
		global_obj.chart_par.legend={
			layout: 'horizontal',
            align: 'center',
            x: 10,
            verticalAlign: 'bottom',
            y: 0,
            floating: false,
            backgroundColor: '#FFFFFF',
			itemMarginBottom: 0,
			itemStyle:{
				color: '#000000',
				fontWeight: 'normal'
            }
		};
		global_obj.chart();
		
	},
	
	profile_init:function(){
		$('#profile_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#profile_form input:submit').attr('disabled', true);
			return true;
		});
	}
}