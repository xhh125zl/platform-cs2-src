var zhongchou_obj = {
    detail_init: function() {
		$('.pay_items').hide();
		$('.detail_footer .footer_btns').click(function(){
			$('.pay_items').show();
		});
		$('.pay_items p').click(function(){
			$('.pay_items').hide();
		});
	},
	checkout_init:function(){
		var address_display=function(){
			var AddressID=parseInt($('#checkout_form input[name=AddressID]:checked').val());
			if(AddressID==0 || isNaN(AddressID)){
				$('#checkout .address dl').css('display', 'block');
			}else{
				$('#checkout .address dl').css('display', 'none');
			}
		}
		
		$('#checkout_form input[name=AddressID]').click(address_display);
		address_display();
		
		$('#checkout_form').submit(function(){return false;});
		$('#checkout_form .checkout input').click(function(){
			var AddressID=parseInt($('#checkout_form input[name=AddressID]:checked').val());
			if(AddressID==0 || isNaN(AddressID)){
				if(global_obj.check_form($('*[notnull]'))){return false};
			}
			
			$(this).attr('disabled', true);
			$.post($('#action').val(), $('#checkout_form').serialize(), function(data){
				if(data.status==1){
					window.location=data.url;
				}else{
					global_obj.win_alert(data.msg);
				}
			}, 'json');
		});
	},
	
	checkpay_init:function(){
		
		$('#checkout_form').submit(function(){return false;});
		$('#checkout_form .checkout input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post($('#action').val(), $('#checkout_form').serialize(), function(data){
				if(data.status==1){
					window.location=data.url;
				}else{
					global_obj.win_alert(data.msg);
				}
			}, 'json');
		});
	}
}