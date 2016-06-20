var cart_obj = {
	cart_init : function(){
		$('.add-substract-key').click(function() { //更新购物车
            var obj = $(this);
            var qty = parseInt(obj.parent().children('input').attr('value'));
            var BizID = obj.parents('.shop-list').attr("BizID");
            var ProductsID = obj.parents('.shop-list').attr("ProductsID");
            var CartID = obj.parents('.shop-list').attr("CartID");
            var type = obj.attr('rel');
            if (type == 'qty_less') {
                if (qty <= 1) {
                    alert('最小购买数量为1！');
                    return false;
                }
            }
            $.post(shop_ajax_url, {action:'cart_update',BizID:BizID,ProductsID:ProductsID,CartID:CartID,Type:type,Qty:qty},
            function(data) {
                if (data.status == 2) { //更新购物车成功                    
                    obj.parent().children('input').attr('value', data.qty);
					obj.parents('.shop-list').find('em[wzw_type=eachGoodsTotal]').html(data.xiaoji);
					obj.parents('tbody').find('em[wzw_type=eachStoreTotal]').html(data.heji);
					$('#cartTotal').html(data.total);
                } else if (data.status == 1) {
                    alert(data.msg)
                    obj.parent().children('input').attr('value', data.qty);
					obj.parents('.shop-list').find('em[wzw_type=eachGoodsTotal]').html(data.xiaoji);
					obj.parents('tbody').find('em[wzw_type=eachStoreTotal]').html(data.heji);
					$('#cartTotal').html(data.total);
                } else {
                    alert(data.msg);
                   // obj.parent().parent().parent().parent().remove();
                }
            }, 'json');
        });

        $('input[rel=qty_input]').keyup(function() {
            var obj = $(this);
            var qty = parseInt(obj.attr("value"));
			var BizID = obj.parents('.shop-list').attr("BizID");
            var ProductsID = obj.parents('.shop-list').attr("ProductsID");
            var CartID = obj.parents('.shop-list').attr("CartID");

            $.post(shop_ajax_url, {action:'cart_update',BizID:BizID,ProductsID:ProductsID,CartID:CartID,Type:'qty_input',Qty:qty},
            function(data) {
                if (data.status == 2) { //更新购物车成功                    
                    obj.parent().children('input').attr('value', data.qty);
					obj.parents('.shop-list').find('em[wzw_type=eachGoodsTotal]').html(data.xiaoji);
					obj.parents('tbody').find('em[wzw_type=eachStoreTotal]').html(data.heji);
					$('#cartTotal').html(data.total);
                } else if (data.status == 1) {
                    alert(data.msg);
                    obj.attr('value', data.qty);
					obj.parents('.shop-list').find('em[wzw_type=eachGoodsTotal]').html(data.xiaoji);
					obj.parents('tbody').find('em[wzw_type=eachStoreTotal]').html(data.heji);
					$('#cartTotal').html(data.total);
                } else {
                    alert(data.msg);
                    //obj.parent().parent().parent().parent().remove();
                }
            }, 'json');
        });
		 //从购物车中删除产品
        $('.delCart').click(function() {
			var obj = $(this);
            var BizID = obj.parents('.shop-list').attr("BizID");
            var ProductsID = obj.parents('.shop-list').attr("ProductsID");
            var CartID = obj.parents('.shop-list').attr("CartID");
            $.post(shop_ajax_url, {action:'cart_del',BizID:BizID,ProductsID:ProductsID,CartID:CartID},
            function(data) {
                if (data.status == 1) { //删除本产品成功
                    obj.parents('tbody').find('em[wzw_type=eachStoreTotal]').html(data.heji);
					$('#cartTotal').html(data.total);
					obj.parents('.shop-list').remove();
                } else if (data.status == 2) { //该商家的产品都已删除
					$('#cartTotal').html(data.total);
                    obj.parents('tbody').remove();
                } else {
                    location.reload();
                }
            }, 'json');
        });
		//下一步“填写核对购物信息”跳转
		$('#next_submit').click(function() {
            $.post(shop_ajax_url, {action:'cart_check'},
            function(data) {
                if (data.status == 1) { //更新购物车成功                    
                    window.location.href = shop_ajax_url.replace('ajax/index', 'buy/order_real');
                } else {
                    alert(data.msg);
                }
            },
            'json');
        });
	},
}