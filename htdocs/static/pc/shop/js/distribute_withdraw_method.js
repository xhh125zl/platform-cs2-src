var distribute_withdraw_method_obj={
	distribute_withdraw_method_init:function(){
		//关闭弹窗
		$('.cut').click(function(e) {
			$('.box_method_form').fadeOut(200);
		});
		//添加弹窗
		$('.add_method').click(function(e) {
			$('.box_method_form').fadeIn(200);
		});
		/*更改提现方法之后激发*/
		$('#Method_Name').change(function(){
           
		   var method_type = $(this).find('option:selected').attr('method_type');
		   $('#Method_Type').attr('value', method_type);
		   if(method_type == 'bank_card'){
			    $('div.bank_card').show();
                $('div.alipay').hide();
                $('div.alipay').find('input').val('');
		   }else if(method_type == 'alipay'){
			    $("div.alipay").show();
			    $("div.bank_card").hide();
                $('div.bank_card').find('input').val(''); 
		   }else{
                $("div.alipay").hide();
                $("div.bank_card").hide();
				$('div.bank_card').find('input').val(''); 
				$('div.alipay').find('input').val('');
           }
		});
		//删除
		$('.method .cut').click(function(e){
			var that = this;
			$.post(shop_ajax_url, {action:'del_withdraw_method',id:$(that).parent('.method').attr('id')}, function(data){
				if(data.status == 1) {
					$(that).parent('.method').remove();
				}
			}, 'json');
		});
		//保存（新增）
		$('.savemethod').click(function(){
			var that = this;
			var post_date = $('.box_method_form form').serialize()+'&action=save_withdraw_method';
			$.post(shop_ajax_url, post_date, function(data){
				if(data.status == 1) {
					$('.box_method_form').fadeOut(200);
					location.reload();
				}else {
					alert(data.msg);
				}
			}, 'json');
		});
        
	},
}