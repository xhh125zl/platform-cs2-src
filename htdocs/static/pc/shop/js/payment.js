var payment_obj = {
	payment_init : function(){
		$('.wzw-payment-list > li').on('click',function(){
			$('.wzw-payment-list > li').removeClass('using');
			$(this).addClass('using');
			var PaymentMethod = $(this).attr('data-value');
            $('#PaymentMethod_val').attr('value', PaymentMethod);
		});
		$('#pay_button').on('click',function(){
			if ($('#PaymentMethod_val').val() == '') {
				alert('请选择支付方式');return false;
			}
			if ($('#PaymentMethod_val').val() == '余额支付') {
				window.location.href = shop_ajax_url.replace('ajax/index', 'payment/complete_pay/OrderID/' + $('input[name=OrderID]').val() + '/Paymethod/money');
				return;
			}
			if ($('#PaymentMethod_val').val() == '线下支付') {
				window.location.href = shop_ajax_url.replace('ajax/index', 'payment/complete_pay/OrderID/' + $('input[name=OrderID]').val() + '/Paymethod/huodao');
				return;
			}
			submit_pay();
		});
		//余额支付
		$('#pay_money_button').on('click',function(){
			submit_pay();
		});
		var submit_pay = function(){
			$.ajax({
				type:'post',
				url:shop_ajax_url,
				data:$('#buy_form').serialize(),
				beforeSend:function() {
					//$("body").append("<div id='load'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div>");
				},
				success:function(data) {
					$('#payment_form .payment input').attr('disabled', false);
					if (data.status == 1) {
						window.location = data.url
					} else {
						alert(data.msg);
					}
				},
				complete:function() {
					//$("#load").remove();
				},
				dataType:'json',
			});
		}
        /*同步数据库*/
        var diyong_sync = function (){
            var Order_ID = $('input[name=OrderID]').val();
            var param = {
                'action': 'diyong',
                'Order_ID': Order_ID,
            };

            $.post(shop_ajax_url, param, function(data) {
                if (data.status == 1) {
                    $('#Order_TotalPrice').html('&yen;' + data.total_price);
					alert(data.msg);
					$('.jifen_box').remove();
                }else{
				    alert(data.msg);
				    location.reload();
				}
            }, 'json');
        }
		/*抵用操作*/
        $('#btn-diyong').click(function() {
		    diyong_sync();
        });
	},
}