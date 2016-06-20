var apply_obj = {	
	apply_init:function(){
		$('.pay_select_bg').height($(window).height());
		$('.pay_select_list').height($(window).height());
		$('.products_list_bg').height($(window).height());
		$('.products_list_tobuy').height($(window).height());
		$('a.products_buy').click(function(){
			var lid=$(this).attr('lid');
			$.post(ajax_url,'action=get_level_products&LevelID='+lid,function(data){
				if(data.status==1){					
					$('.products_list_bg').show();
					$('.products_list_tobuy').show();
					$('.products_list_content').html(data.msg);
				}else{
					global_obj.win_alert(data.msg, function() {
                        window.location.reload();
                    });
				}
			},'json');
		});
		
		$('.products_list_tobuy h2 span').click(function(){
			$('.products_list_bg').hide();
			$('.products_list_tobuy').hide();
		});
		
		$('#apply_buy .level_list ul li').click(function(){
			$('#apply_buy .level_list ul li').removeClass('cur');
			$(this).addClass('cur');
			var lid=$(this).attr('lid');			
			$.post(ajax_url,'action=get_level_price&LevelID='+lid,function(data){
				if(data.status==1){
					$('#apply_buy input[name=LevelID]').attr('value',lid);
					$('#apply_buy .level_total span').html('&yen; '+data.price);
				}else{
					global_obj.win_alert(data.msg, function() {
                        window.location.reload();
                    });
				}
			},'json');
		});
		
		$('#apply_buy').submit(function() {
            return false;
        });
		
		$('#apply_buy div.submit_btn').click(function(){
			if(global_obj.check_form($('#apply_buy *[notnull]'))){return false};
			var lid=parseInt($('#apply_buy input[name=LevelID]').attr('value'));			
			if(lid<=0){
				return false;
			}
			
			if($('input[name=agree]').attr("value")==1){
				if(!$('input[name=agreement]').attr("checked")){
					alert('你还没同意协议');
					return false;
				}
			}
			$(this).attr('disabled', true);
			$.post(ajax_url,$('#apply_buy').serialize(),function(data){
				if(data.status==1){
					$('.pay_select_bg').show();
					$('.pay_select_list').show();
					$('.pay_select_list').attr('id',data.orderid);
					$('.pay_select_list h3 span').html('&yen;'+data.money);
				}else{
					global_obj.win_alert(data.msg, function() {
                        window.location.reload();
                    });
				}
			},'json');
		});		
		
		$('.pay_select_list a#zfb,.pay_select_list a#wzf').click(function(){
			var orderid = parseInt($(this).parent().attr('id'));
			if(orderid<=0){
				global_obj.win_alert('请先提交订单', function() {});
				return false;
			}
			$.post(ajax_url,'action=get_distribute_pay_method&OrderID='+orderid+'&method='+$(this).attr('data-value'),function(data){
				if(data.status==1){
                    window.location.href=data.url;
				}else{
					global_obj.win_alert(data.msg, function() {});
				}
			},'json');
		});
		
		$('.pay_select_list h2 span').click(function(){
			$('.pay_select_bg').hide();
			$('.pay_select_list').hide();
			$('#apply_buy input[name=submit]').attr('disabled', false)
		});
		
		$('.pay_select_list a#money').click(function(){
			$('.pay_select_list .money_info').show();
		});
		
		$('.pay_select_list button').click(function(){
			var password = $(this).parent().children('input').attr('value');
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
			$.post(ajax_url,'action=deal_level_order_pay&OrderID='+orderid+'&password='+password,function(data){
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