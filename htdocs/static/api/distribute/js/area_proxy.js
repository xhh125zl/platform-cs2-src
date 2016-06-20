var area_proxy_obj = {	
	area_proxy_init:function(){
		$('.payaction').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			applyPrice = parseInt($(this).attr('data-value'));

			if(applyPrice<=0){
				return false;
			}

			$(this).attr('disabled', true);
			$('.pay_select_bg').css('height',$(window).height()).show();
			$('.pay_select_list').css('height',$(window).height()).show();
			$('.pay_select_list').attr('id', parseInt($(this).attr('data-id')));
			$('.pay_select_list h3 span').html('&yen;'+applyPrice);

		});

		$('.pay_select_list h2 span').click(function(){
			$('.pay_select_list, .money_info').hide();
		});

		$('.pay_select_list a#zfb,.pay_select_list a#wzf').click(function(){
			var orderid = parseInt($(this).parent().attr('id'));
			if(orderid<=0){
				global_obj.win_alert('请先提交订单', function() {});
				return false;
			}
			$.post(ajax_url,'action=get_proxy_pay_method&OrderID='+orderid+'&method='+$(this).attr('data-value'),function(data){
				if(data.status==1){
                    window.location.href=data.url;
				}else{
					global_obj.win_alert(data.msg, function() {});
				}
			},'json');
		});

		$('.pay_select_list a#money').click(function(){
			$('.pay_select_list .money_info').show();
		});

		$('.pay_select_list button').click(function(){
			var password = $(this).prev().val();
			var orderid = parseInt($(this).parent().parent().attr('id'));

			if(password==''){
				global_obj.win_alert('请输入支付密码', function() {});
				return false;
			}
			
			if(orderid<=0){
				global_obj.win_alert('请先提交订单', function() {});
				return false;
			}
			//$(this).attr('disabled', true);
			$.post(ajax_url,'action=deal_proxy_order_pay&OrderID='+orderid+'&password='+password,function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function() {
                        window.location.href=data.url;
                    });
				}else{
					global_obj.win_alert(data.msg, function() {});
				}
			},'json');
		});

	},
}