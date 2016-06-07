// 商品购买流程js操作
var flow_obj = {
    checkout_init: function() {
        //提交订单页面js操作 
		
		$('.Invoice_btn').click(function(){//发票
			var rel = $(this).attr("rel");
			if(!$(this).attr("checked")){
				$("#Invoice_info_"+rel).hide();
			}else{
				$("#Invoice_info_"+rel).show();
			}
		});
		
        $('.qty_selector a[name=minus]').click(function() {

            var qty_input_obj = $(this).next();
            var qty = $(qty_input_obj).attr('value') - 1;
            var cart_id = $(qty_input_obj).attr('id');
            if (qty < 1) {
                qty = 1;
                return false;
            }

            flow_obj.update_checkout_qty(qty, cart_id);

        });

        $('.qty_selector a[name=add]').click(function() {
            var qty_input_obj = $(this).prev();
            var qty = parseInt($(qty_input_obj).attr('value')) + 1;
            var cart_id = $(qty_input_obj).attr('id');
            var Products_ID = cart_id.split('_')[1];
            var products_Count = parseInt($("#Products_Count_" + Products_ID).attr('value'));

            if (qty > products_Count) {
                qty = products_Count;
                return false;
            }

            flow_obj.update_checkout_qty(qty, cart_id);


        });

        $('.qty_selector input').change(function() {
            var qty_input_obj = $(this);
            var qty = parseInt($(qty_input_obj).attr('value'));
            var cart_id = $(qty_input_obj).attr('id');
            var Products_ID = cart_id.split('_')[1];
            var products_Count = parseInt($("#Products_Count_" + Products_ID).attr('value'));

            if (qty < 1) {
                qty = 1;
                $(qty_input_obj).attr('value', 1);
                return false;
            }

            if (qty > products_Count) {
                qty = products_Count;
                $(qty_input_obj).attr('value', products_Count);
                return false;
            }

            flow_obj.update_checkout_qty(qty, cart_id);
        });

        $("#submit-btn").removeAttr('disabled');

        $('#checkout_form').submit(function() {
            return false;
        });

        $('#checkout_form #submit-btn').click(function() {
            var AddressID = parseInt($('#checkout_form input[name=AddressID]').val());

            if (AddressID == 0 || isNaN(AddressID)) {
                if (global_obj.check_form($('*[notnull]'))) {
                    return false
                };
            }

            $(this).attr('disabled', true);

            var param = $('#checkout_form').serialize();
            var url = $('#checkout_form').attr('action') + 'ajax/';

            $.post(url, param, function(data) {
                if (data.status == 1) {
                    window.location = data.url;
                }else {
					if(typeof data.msg != 'undefined'){
						global_obj.win_alert(data.msg);
					}   
				}
            }, 'json');
        });

		$("input.coupon").live('click',function(e){
				var pre_coupon_value = parseInt($("#coupon_value").attr('value'));
				var total_price = parseInt($("#total_price").attr('value'));
				if(pre_coupon_value > 0){
					total_price = total_price + pre_coupon_value;
				}
				
				var coupon_price = parseInt($(this).attr('price'));
				total_price -= coupon_price;
			
				$("#total_price_txt").html('&yen' + total_price);
				$("#total_price").attr('value', total_price);	
				$("#coupon_value").attr('value',coupon_price);
		});
		
		$(".shipping_method").click(function(){
			var BizID = $(this).attr("Biz_ID");
			var top = $(window).height()/2;
			$("#shipping-modal-"+BizID).css('top',top-80);
			$("#shipping-modal-"+BizID).modal('show');
			
		});
		
		$("#confirm_shipping_btn").live('click',function(){
			var Biz_ID = $(this).attr('biz_id');
			$("#shipping-modal-"+Biz_ID).modal('hide');
		
			flow_obj.change_shipping_method(Biz_ID);
		});
		
		$("#cancel_shipping_btn").live('click',function(){
			var Biz_ID = $(this).attr('biz_id');
		    $("#shipping-modal-"+Biz_ID).modal('hide');
		});
		
		
		
		
        /**
         * json对象转字符串形式
         */
        function json2str(o) {
            var arr = [];
            var fmt = function(s) {
                if (typeof s == 'object' && s != null) return json2str(s);
                return /^(string|number)$/.test(typeof s) ? "'" + s + "'" : s;
            }
            for (var i in o) arr.push("'" + i + "':" + fmt(o[i]));
            return '{' + arr.join(',') + '}';
        }
    },
	coupon_price:function(){
		
			
	},
    update_checkout_qty: function(qty, cart_id) {
        var City_Code = $("#City_Code").attr('value');
		var Biz_ID = cart_id.split('_')[0];
        var Products_ID = cart_id.split('_')[1];
        var Shipping_ID = flow_obj.getShippingID();
        var Business = $("#Business_" + Products_ID).attr('value');
        var IsShippingFree = parseInt($("#IsShippingFree_" + Products_ID).attr('value'));
        var cart_key = $("#cart_key").attr('value');
        var param = {
            Shipping_ID:Shipping_ID,
            Business: Business,
            City_Code: City_Code,
            _Qty: qty,
            _CartID: cart_id,
            IsShippingFree: IsShippingFree,
			cart_key: cart_key,
            action: 'checkout_update'
        };

        var url = base_url + 'api/' + Users_ID + '/shop/cart/ajax/';
        var Cart_ID = cart_id;
        $.post(url, param, function(data) {
            if (data.status == 1) {
                if (parseInt(data.biz_shipping_fee) == 0) {
                    $('#biz_shipping_fee_txt_' + Biz_ID).html('免运费');
                } else {
                    $('#biz_shipping_fee_txt_' + Biz_ID).html(data.biz_shipping_fee + '元');
                }

                $('#subtotal_price_' + Cart_ID).html('&yen' + data.Sub_Total);
                $('#subtotal_qty_' + Cart_ID).html(data.Sub_Qty);
                $('#' + Cart_ID).attr('value', data.Sub_Qty);
				$('#biz_shipping_'+Biz_ID).html(data.biz_shipping_name);
                //更新订单合计信息
                var total_price = data.total + data.total_shipping_fee;
                $("#total_price_txt").html('&yen' + total_price);
                $("#total_price").attr('value', total_price);
                $("#total_shipping_fee").attr('value', data.total_shipping_fee);
				$("#coupon_value").attr('value',0);
                

                var integral = parseInt(data.integral);

                if (integral > 0) {
                    $("#total_integral").html(integral);
                }
				//优惠券
                //if (data.coupon_html.length > 0) {
                //    $("#coupon-list-"+Biz_ID).html(data.coupon_html);
                //} else {
                //    $("#coupon-list-"+Biz_ID).html('');
                //}

            }
        }, 'json');
    },
	change_shipping_method:function(Biz_ID){
		
		var Shipping_ID = flow_obj.getShippingID();
		var City_Code = $("#City_Code").attr('value');
		var cart_key = $("#cart_key").attr('value');
		var action = 'change_shipping_method';
		var url = base_url + 'api/' + Users_ID + '/shop/cart/ajax/';
		var param = {
			Biz_ID:Biz_ID,
			Shipping_ID:Shipping_ID,
			City_Code:City_Code,
			cart_key:cart_key,
			action:action
			};
			
		$.post(url, param, function(data) {
			
			if(data.status == 1){
				var total_price = data.total + data.total_shipping_fee;
				if (parseFloat(data.biz_shipping_fee) == 0) {
                    $('#biz_shipping_fee_txt_' + Biz_ID).html('免运费');
                } else {
                    $('#biz_shipping_fee_txt_' + Biz_ID).html(data.biz_shipping_fee + '元');
                }

				$('#biz_shipping_'+Biz_ID).html(data.biz_shipping_name);
				$("#total_price_txt").html('&yen' + total_price);
                $("#total_price").attr('value', total_price);
                $("#total_shipping_fee").attr('value', data.total_shipping_fee);
				$("#total_shipping_fee_txt").html('&yen'+data.total_shipping_fee+'元');
				$('#Shipping_ID_' + Biz_ID).attr("value",data.biz_shipping_fee);
			}
			
		},'json');
		
	},
	getShippingID:function(){
		
		var Shiping_IDS = [];
		$("input.Shiping_ID_Val:checked").each(function(){
			var Biz_ID = $(this).attr('Biz_ID');
			var Shipping_ID = $(this).val();
            var obj =  new Object();
			obj.Biz_ID = Biz_ID;
			obj.Shipping_ID = Shipping_ID;
			Shiping_IDS.push(obj);
     	});
	   return Shiping_IDS;
		
	}

}
