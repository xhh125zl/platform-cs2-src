var shipping_obj = {
    shipping_init: function() {
		$('#gift .item a.concel').click(function(){
			var ordersid = $(this).attr("ret");
			$.post(ajax_url, {action:'concel',ordersid:ordersid}, function(data) {
                if (data.status == 1) {
					global_obj.win_alert('取消成功!', function(){
						window.location.href=data.url;
					});		
                } else {
                    global_obj.win_alert(data.msg);
                }
            }, 'json');
		});
		
		$('#gift .item a.recieve').click(function(){
			var ordersid = $(this).attr("ret");
			$.post(ajax_url, {action:'recieve',ordersid:ordersid}, function(data) {
                if (data.status == 1) {
					global_obj.win_alert('操作成功!', function(){
						window.location.href=data.url;
					});		
                } else {
                    global_obj.win_alert(data.msg);
                }
            }, 'json');
		});
    },
	shipping_checkout_init:function(){
		$("#shipping_method").click(function(){
			var top = $(window).height()/2;
			$("#shipping-modal").css('top',top-80);
			$("#shipping-modal").modal('show');
			
		});
		
		$("#confirm_shipping_btn").live('click',function(){
			$("#shipping-modal").modal('hide');
			shipping_obj.change_shipping_method();
		});
		
		$("#cancel_shipping_btn").live('click',function(){
		    $("#shipping-modal").modal('hide');
		});
		
		$('#checkout_form #submit-btn').click(function() {
			var isshipping = parseInt($('#checkout_form input[name=isshipping]').val());
			if(isshipping==0){
				if(global_obj.check_form($('*[notnull]'))){return false};
			}else{
            	var AddressID = parseInt($('#checkout_form input[name=AddressID]').val());
				if (AddressID == 0 || isNaN(AddressID)) {
					alert("请选择收货地址");
					return false;
				}
			}

            $(this).attr('disabled', true);

            var param = $('#checkout_form').serialize();
            var url = $('#checkout_form').attr('action');

            $.post(url, param, function(data) {
				if (data.status == 1) {
				   window.location = data.url;
                }else{
					global_obj.win_alert(data.msg, function(){
						window.location.href=data.url;
					});
				}

            }, 'json');
        });
	},
	change_shipping_method:function(){
		var Shipping_ID = parseInt($("input[name='Shiping_ID']:checked").attr('value'));
		var Shipping_Name  = $("input[name='Shiping_ID']:checked").attr('shipping_name');
		if(FreeShipping==1){
			$("#shipping_name").html(Shipping_Name);
			$("#Order_Shipping_Express").attr('value',Shipping_Name);
			$("#total_price").attr('value', 0);
			$('#total_shipping_fee_txt').html('免运费');
		}else{		
			var City_Code = $("#City_Code").attr('value');
			var DetailID = $("input[name=DetailID]").attr('value');
			var action = 'change_shipping_method';
			var url = base_url + 'api/' + Users_ID + '/cloud/member/products/ajax/';
			var param = {
				DetailID:DetailID,
				Shipping_ID:Shipping_ID,
				City_Code:City_Code,
				action:action
			};
				
			$.post(url, param, function(data) {
				if(data.status == 1){
					var total_price = parseFloat(data.total_shipping_fee);
					$("#shipping_name").html(Shipping_Name); 
					$("#Order_Shipping_Express").attr('value',Shipping_Name);
					$("#total_price_txt").html('&yen' + total_price);
					$("#total_price").attr('value', total_price);
					
					if (parseFloat(data.total_shipping_fee) == 0) {
						$('#total_shipping_fee_txt').html('免运费');
					} else {
						$('#total_shipping_fee_txt').html(data.total_shipping_fee + '元');
					}
				
				}
				
			},'json');
		}
	},
	shipping_payment_init: function() {
		$("a.direct_pay").click(function(){
			var PaymentMethod = $(this).attr("data-value"); 
			var PaymentID = $(this).attr("id");
			$("#PaymentMethod_val").attr("value",PaymentMethod);
			shipping_obj.submit_payment();
		});
		
		/*余额支付确认支付,与线下支付确认*/
		$("#btn-confirm").click(function(){
			shipping_obj.submit_payment();
		});

        $('#payment_form').submit(function() {
            return false;
        });
    },
	submit_payment:function(){
		 $('#payment_form .payment input').attr('disabled', true);
            $.post($('#payment_form').attr('action'), $('#payment_form').serialize(), function(data) {
                $('#payment_form .payment input').attr('disabled', false);
                if (data.status == 1) {
                    window.location = data.url
                } else {
                    global_obj.win_alert(data.msg);
                }
            }, 'json');
	},
}