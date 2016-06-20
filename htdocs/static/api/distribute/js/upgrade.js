var upgrade_obj = {	
	upgrade_init:function(){
		$('#upgrade_buy .level_list ul li').click(function(){
			$('#upgrade_buy .level_list ul li').removeClass('cur');
			$(this).addClass('cur');
			var lid=$(this).attr('lid');			
			$.post(ajax_url,'action=get_level_info_upgrade&LevelID='+lid,function(data){
				if(data.status==1){//补差价
					$('#upgrade_buy input[name=LevelID]').attr('value',lid);
					$('#upgrade_buy .level_total span').html('&yen; '+data.price);
					$('#upgrade_buy .level_total,#upgrade_buy .level_submit').show();
				}else if(data.status==2){//购买指定商品
					$('#upgrade_buy .level_total,#upgrade_buy .level_submit').hide();
					$('#upgrade_buy input[name=LevelID]').attr('value','');
					$('#upgrade_buy .level_total span').html('');
					
					$('.body_bg').css('height',$(window).height()).show();
					$('.products_list_tobuy').css('height',$(window).height()).show();
					$('.products_list_content').html(data.msg);
				}else{
					global_obj.win_alert(data.msg, function() {
                        window.location.reload();
                    });
				}
			},'json');
		});
		
		$('.products_list_tobuy h2 span').click(function(){
			$('.body_bg').hide();
			$('.products_list_tobuy').hide();
		});
		
		$('#upgrade_buy').submit(function() {
            return false;
        });
		
		$('#upgrade_buy input[name=submit]').click(function(){
			
			if(global_obj.check_form($('*[notnull]'))){return false};
			var lid=parseInt($('#upgrade_buy input[name=LevelID]').attr('value'));			
			if(lid<=0){
				return false;
			}
			
			$(this).attr('disabled', true);
			$.post(ajax_url,$('#upgrade_buy').serialize(),function(data){
				if(data.status==1){
					 
					$('.pay_select_bg').css('height',$(window).height()).show();
					$('.pay_select_list').css('height',$(window).height()).show();
					$('.pay_select_list').attr('id',data.orderid);
					$('.pay_select_list h3 span').html('&yen;'+data.money);
				}else{
					global_obj.win_alert(data.msg, function() {
                        window.location.reload();
                    });
				}
			},'json');
		});	
		
		$('#upgrade_buy').on('click', '#close-btn',
        function() {
           $('.pay_select_list').css('height',$(window).height()).hide();
		   window.location.reload();

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