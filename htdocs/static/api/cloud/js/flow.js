// 商品购买流程js操作
var flow_obj = {
    checkout_init: function() {
        //提交订单页面js操作 
        $('.qty_selector a[name=minus]').click(function() {
            var qty_input_obj = $(this).next();
            var qty = $(qty_input_obj).attr('value') - 1;
            var qty_id = $(qty_input_obj).attr('id');
            var cart_id = flow_obj.getCartID(qty_id);

            if (qty < 1) {
                qty = 1;
                return false;
            }
            flow_obj.update_checkout_qty(qty, cart_id);
        });

        $('.qty_selector a[name=add]').click(function() {
            var qty_input_obj = $(this).prev();
            var qty = parseInt($(qty_input_obj).attr('value')) + 1;
            var qty_id = $(qty_input_obj).attr('id');
            var cart_id = flow_obj.getCartID(qty_id);

            flow_obj.update_checkout_qty(qty, cart_id);
        });

        $('.qty_selector input').change(function() {
            var qty_input_obj = $(this);
            var qty = parseInt($(qty_input_obj).attr('value'));
            var qty_id = $(qty_input_obj).attr('id');
            var cart_id = flow_obj.getCartID(qty_id);

            if (qty < 1) {
                qty = 1;
                $(qty_input_obj).attr('value', 1);
                return false;
            }

            flow_obj.update_checkout_qty(qty, cart_id);
        });

        $("#submit-btn").removeAttr('disabled');

        $('#checkout_form').submit(function() {
            return false;
        });

        $('#checkout_form #submit-btn').click(function() {
            //$(this).attr('disabled', true);
            var param = $('#checkout_form').serialize();
            var url = $('#checkout_form').attr('action') + 'ajax/';


            $.post(url, param, function(data) {
            
				if (data.status == 1) {
					
				   window.location = data.url;
                }else{
					if(data.status == 0 && data.msg){
						global_obj.win_alert(data.msg);
					}
				}

            }, 'json');
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
	
    update_checkout_qty: function(qty, cart_id) {
        var Products_ID = cart_id.split('_')[0];
		var virtual = $("#virtual").attr('value');
        var needcart = parseInt($("#needcart").attr('value'));

        var param = {
            _Qty : qty,
            _CartID : cart_id,
            action : 'checkout_update',
			virtual : virtual,
            needcart : needcart,
        };

        var url = base_url + 'api/' + Users_ID + '/cloud/cart/ajax/';
        $.post(url, param, function(data) {

            if (data.status == 1) {
                $('#subtotal_price_' + cart_id).html('&yen' + data.Sub_Total);
                $('#subtotal_qty_' + cart_id).html(data.Sub_Qty);
                $('#qty_' + cart_id).attr('value', data.Sub_Qty);

                //更新订单合计信息
                var total_price = parseFloat(data.total);
                $("#total_price_txt").html('&yen' + total_price);
                $("#total_price").attr('value', total_price);
                var integral = data.integral;
                if (integral > 0) {
                    $("#total_integral").html(integral);
                }
            }else{
				if(data.msg){
					global_obj.win_alert(data.msg);
				}
			}
        }, 'json');
    },
    getCartID: function(qty_id) {
  
		var pics = qty_id.split('_')	
        var cart_id = pics[1]+'_'+pics[2];
        return cart_id;
		
    }

}
