var shop_obj = {  
    payment_init: function() {
        // var PaymentMethod = $('#payment_form input[name=PaymentMethod]');
        $("a.direct_pay").click(function() {

            var PaymentMethod = $(this).attr("data-value");
            var PaymentID = $(this).attr("id");
            $("#PaymentMethod_val").attr("value", PaymentMethod);

            shop_obj.submit_payment();

        });

        /*余额支付确认支付,与线下支付确认*/
        $("#btn-confirm").click(function() {
            shop_obj.submit_payment();
        });

        $('#payment_form').submit(function() {
            return false;
        });

    },
    submit_payment: function() {
        $('#payment_form .payment input').attr('disabled', true);
		$.ajax({
			type:'post',
			url:$('#payment_form').attr('action') + 'ajax/',
			data:$('#payment_form').serialize(),
			beforeSend:function() {
				$("body").append("<div id='load'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div>");
			},
			success:function(data) {
				// url Redirect
				$('#payment_form .payment input').attr('disabled', false);
				if (data.status == 1) {
					layer.msg(data.msg,{icon:1,time:1500},function(){
						window.location = data.url;
                    });
					
				} else {
					layer.msg(data.msg,{icon:1,time:3000});
				}
			},
			complete:function() {
				//$("#load").remove();
			},
			dataType:'json',
		});
    }

}