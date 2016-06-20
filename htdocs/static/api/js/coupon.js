var coupon_obj={
	coupon_init:function(){
		$('#coupon .contents .button').click(function(){
			var v=$(this).attr('v');
			if(v=='GetCoupon'){
				$(this).html('领取中...');
				$.post('?', 'action=GetCoupon', function(data){
					if(data.status==1){
						$('#coupon .contents .button').html('优惠券领取成功！');
						window.top.location.reload();
					}else{
						$('#coupon .contents .button').html('优惠券领取失败，请重新领取!');
					};
				}, 'json');
			}else if(v=='UseCoupon'){
				$('#coupon_form').slideDown(500);				
			}
		});
		
		$('#coupon_use_form input:button[class=back]').click(function(){
			$('#coupon_form').slideUp(500);
		});
		
		$('#coupon_use_form input:button[class=submit]').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#coupon_use_form').serialize()+'&q=1', function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){window.location.reload()});
				}else{
					global_obj.win_alert(data.msg, function(){
						$('form .submit').attr('disabled', false)
					});
				};
			}, 'json');
		});	
	}
}