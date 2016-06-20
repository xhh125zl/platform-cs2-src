// JavaScript Document

var hotel_obj = {	 
	init:function(){
		$('.bar-nav a').click(function(){
			window.history.go(-1);
		});
	},
	detail_init:function(){
		$('#detail_images .touchslider .touchslider-viewport .touchslider-item').css('width',$(window).width());		
		$('#detail_images .touchslider .img').css('height',$(window).width()+'px');
		if(proimg_count>0){
			(function(window, $, PhotoSwipe){
				$('.touchslider-viewport .list a[rel]').photoSwipe({});
			}(window, window.jQuery, window.Code.PhotoSwipe));
			
			$('.touchslider').touchSlider({
				mouseTouch:true,
				autoplay:true,
				delay:2000
			});
		}
	},
	checkorder:function(){
		$('#order_form input[name=DateFrom]').datepicker({
			timePicker:true,
			minDate:new Date(),
			dateFormat:'yy-mm-dd'
		}).val((
			function(d){
				return [d.getFullYear(), d.getMonth()+1, d.getDate()].join('-');
			}
		)(new Date()));
		
		$('#order_form input[name=DateEnd]').datepicker({
			minDate:new Date(),
			dateFormat:'yy-mm-dd'
		}).val((
			function(d){
				var dd_time = d.setDate(d.getDate()+1);
				var dd_str = new Date(dd_time);
				return [d.getFullYear(), d.getMonth()+1, dd_str.getDate()].join('-');
			}
		)(new Date()));
		
		var gettime = function(){
			var datefrom = $('#order_form input[name=DateFrom]').val().replace(/-/g,"/")+' '+$('#order_form select[name=HourFrom]').val()+':'+$('#order_form select[name=MinuteFrom]').val();
			var dateend = $('#order_form input[name=DateEnd]').val().replace(/-/g,"/")+' '+$('#order_form select[name=HourEnd]').val()+':'+$('#order_form select[name=MinuteEnd]').val();
			var diff_time = new Date(dateend).getTime()-new Date(datefrom).getTime();
			var day = parseInt(diff_time/86400000);
			if(diff_time%86400000>0){
				day = day+1;
			}
			return day;
		}
		
		var price_detail = function() {
            var total_price = 0;
            var qty = parseInt($('#order_form input[name=Qty]').val());
			var price = parseFloat($('#order_form input[name=price]').val());
            isNaN(qty) && (qty = 1);
			var days = gettime();
            total_price = price * qty * days;
			total_price = total_price.toFixed(2);
			$('#order_form .total span').html('￥' + total_price);
        }
        price_detail();
		
		$('#order_form a[name=minus]').click(function() {
            var qty = parseInt($('#order_form input[name=Qty]').val()) - 1;
            if (qty < 1) {
                qty = 1
            }
            $('#order_form input[name=Qty]').val(qty);
			price_detail();
        });
        $('#order_form a[name=add]').click(function() {
            var qty = parseInt($('#order_form input[name=Qty]').val()) + 1;
            $('#order_form input[name=Qty]').val(qty);
			price_detail();
        });
		
		$('#order_form input[name=DateFrom]').change(function() {
			price_detail();
        });
		
		$('#order_form select[name=HourFrom]').change(function() {
			price_detail();
        });
		
		$('#order_form select[name=MinuteFrom]').change(function() {
			price_detail();
        });
		
		$('#order_form input[name=DateEnd]').change(function() {
			price_detail();
        });
		
		$('#order_form select[name=HourEnd]').change(function() {
			price_detail();
        });
		
		$('#order_form select[name=MinuteEnd]').change(function() {
			price_detail();
        });
		$('#order_form .submit').click(function() {
            //$(this).attr('disabled', true);
			if (global_obj.check_form($('*[notnull]'))) {
                    return false;
            }
            $.post($('#order_form').attr('action'), $('#order_form').serialize(),
            function(data) {
                $(this).attr('disabled', false);
                if (data.status == 1) {
                    window.location = data.url;
                } else {
                    window.location = $('#order_form').attr('action')
                }
            },
            'json')
        })
	},
	payment:function(){
		var PaymentMethod=$('#payment_form input[name=PaymentMethod]');
		if(PaymentMethod.size()){
			var change_payment_method=function(){
				if(PaymentMethod.filter(':checked').val()=='线下支付'){					
					$('#payment_form .payment_info').show();
					$('#payment_form .payment_password').hide();
				}if(PaymentMethod.filter(':checked').val()=='余额支付'){
					
					$('#payment_form .payment_password').show();
					$('#payment_form .payment_info').hide();
				}else{
					$('#payment_form .payment_info').hide();
					$('#payment_form .payment_password').hide();
				}
			}
			PaymentMethod.click(change_payment_method);
			PaymentMethod.filter('[value='+$('#payment_form input[name=DefautlPaymentMethod]').val()+']').click();
			change_payment_method();
		}else{
			$('#payment_form').hide();
		}
		
		$('#payment_form').submit(function(){return false;});
		$('#payment_form .payment input').click(function(){
			$(this).attr('disabled', true);
			$.post($('#payment_form').attr('action'), $('#payment_form').serialize(), function(data){
				$('#payment_form .payment input').attr('disabled', false);
				if(data.status==1){
					window.location=data.url
				}
			}, 'json');
		});
	}
}