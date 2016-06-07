DialogManager.close = function(id) {
	__DIALOG_WRAPPER__[id].hide();
	ScreenLocker.unlock();
}
DialogManager.show = function(id) {
	if (__DIALOG_WRAPPER__[id]) {
		__DIALOG_WRAPPER__[id].show();
		ScreenLocker.lock();
		return true;
	}
	return false;
}
function show_dialog(id, title, callback) {
	if(DialogManager.show(id)) return;
	var d = DialogManager.create(id);//不存在时初始化(执行一次)
	$("#"+id+"_dialog").remove();
	d.setTitle(title);
	d.setContents('<div id="'+id+'_dialog" class="'+id+'_dialog"></div>');
	d.setWidth(640);
	d.show('center', 1);
	if(typeof callback == 'function')
		callback();
}
var order_obj = {
	order_init : function() {
		//组装价格
		var make_total_price = function(total){
			if($('.wzw-all-account em').length > 0){//订单页的总价格
			    var shipping_total = 0;
			    $('.Shipping_fee_value').each(function(){
					shipping_total = parseFloat(shipping_total) + parseFloat($(this).val());
				});
				$('.wzw-all-account em').html(parseFloat(total + shipping_total));
			}
			if($('#cartTotal').length > 0){ //购物车总价格
				$('#cartTotal').html(total);
			}
		}
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
					make_total_price(data.total);
                } else if (data.status == 1) {
                    alert(data.msg)
                    obj.parent().children('input').attr('value', data.qty);
					obj.parents('.shop-list').find('em[wzw_type=eachGoodsTotal]').html(data.xiaoji);
					obj.parents('tbody').find('em[wzw_type=eachStoreTotal]').html(data.heji);
					make_total_price(data.total);
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
					make_total_price(data.total);
                } else if (data.status == 1) {
                    alert(data.msg);
                    obj.attr('value', data.qty);
					obj.parents('.shop-list').find('em[wzw_type=eachGoodsTotal]').html(data.xiaoji);
					obj.parents('tbody').find('em[wzw_type=eachStoreTotal]').html(data.heji);
					make_total_price(data.total);
                } else {
                    alert(data.msg);
                    //obj.parent().parent().parent().parent().remove();
                }
            }, 'json');
        });
		 //从购物车中删除产品  //注意：删除代码顺序不能乱
        $('.delCart').click(function() {
			var obj = $(this);
            var BizID = obj.parents('.shop-list').attr("BizID");
            var ProductsID = obj.parents('.shop-list').attr("ProductsID");
            var CartID = obj.parents('.shop-list').attr("CartID");
            $.post(shop_ajax_url, {action:'cart_del',BizID:BizID,ProductsID:ProductsID,CartID:CartID},
            function(data) {
                if (data.status == 1) { //删除本产品成功
                    obj.parents('tbody').find('em[wzw_type=eachStoreTotal]').html(data.heji);
					obj.parents('.shop-list').remove();
					make_total_price(data.total);
                } else if (data.status == 2) { //该商家的产品都已删除
					if(obj.parents('.wzw-receipt-info').length > 0){//订单页删除商品 第一步
						obj.parents('.wzw-receipt-info').next('.wzw-receipt-info').next('.wzw-receipt-info').remove();
						obj.parents('.wzw-receipt-info').next('.wzw-receipt-info').remove();
						obj.parents('.wzw-receipt-info').remove();
					}
					if(obj.parents('tbody').length > 0){
						obj.parents('tbody').remove();//购物车删除商品
					}
					make_total_price(data.total); //第二步
                } else {
                    location.reload();
                }
            }, 'json');
        });
		//获取地址列表
		$('#edit_reciver').click(function() {
			var select_addr_id = $('#addr_list').attr('add_id');
			$.post(shop_ajax_url,{action:'address_list'},function(data){
				if(data.status == 1) {
					var htmlTmp = '';
					$.each(data['list'],function(i) {
						var v = data['list'][i];
						   htmlTmp += '<label>'+
									'<div class="pull-left">'+
										v['Province_name']+v['City_name']+v['Area_name']+v['Address_Detailed']+
									'</div>'+
									'<div class="pull-right">'+
										'<input type="radio" '+((select_addr_id == v['Address_ID']) ? 'checked' : '')+' name="Address_ID" value="'+v['Address_ID']+'" />'+
									'</div>'+
								'</label>';
					});
					var button = '<div id="address_ok">确定</div>';
					show_dialog('address_list', '选择收货地址', function() {
				        $('#address_list_dialog').html(htmlTmp+button);
						$('#address_list_dialog').on('click','#address_ok',function(){
							$.post(shop_ajax_url,{action:'change_address',AddressID:$('input[name=Address_ID]:checked').val()},function(result){
								if(result.status == 1) {
									$('#addr_list .true-name').html(result.address.Address_Name);
									$('#addr_list .phone').html(result.address.Address_Mobile);
									$('#addr_list .address').html(result.address.Province_name+''+result.address.City_name+''+result.address.Area_name);
									$('#addr_list .detailed').html(result.address.Address_Detailed);
									$('#addr_list #City_Code').html(result.address.Address_City);
								}
							}, 'json');
							DialogManager.close('address_list');
						});
			        });
				};
			}, 'json');
		});
		//修改发票
		$('.edit_invoice').click(function(){
			$(this).hide();
			$(this).parents('.wzw-receipt-info').find('.invoice_list1').hide();
			$(this).parents('.wzw-receipt-info').find('.invoice_list2').show();
			$(this).parents('.wzw-receipt-info').addClass('current_box');
		});
		//取消发票
		$('.cancel_invoice').click(function(){
			$(this).parents('.wzw-receipt-info').find('.edit_invoice').show();
			$(this).parents('.wzw-receipt-info').find('.invoice_list1').show();
			$(this).parents('.wzw-receipt-info').find('.invoice_list2').hide();
			$(this).parents('.wzw-receipt-info').find('.vat_invoice_panel input').val('');
			$(this).parents('.wzw-receipt-info').find('.invoice_list1 li').html('不需要发票');
			$(this).parents('.wzw-receipt-info').find('.Order_NeedInvoice').val('0');
			$(this).parents('.wzw-receipt-info').removeClass('current_box');
		});
		//保存发票
		$('.hide_invoice_list').click(function(){
			if($(this).parents('.wzw-receipt-info').find('.vat_invoice_panel input.Order_InvoiceInfo').val()) {
				$(this).parents('.wzw-receipt-info').find('.invoice_list1 li').html($(this).parents('.wzw-receipt-info').find('.vat_invoice_panel input.Order_InvoiceInfo').val());
				$(this).parents('.wzw-receipt-info').find('.edit_invoice').show();
				$(this).parents('.wzw-receipt-info').find('.invoice_list1').show();
				$(this).parents('.wzw-receipt-info').find('.invoice_list2').hide();
				$(this).parents('.wzw-receipt-info').find('.Order_NeedInvoice').val('1');
				$(this).parents('.wzw-receipt-info').removeClass('current_box');
			}else {
				alert('请填写发票信息！');
			}
		});
		//选择配送方式
		$(".shipping_method").click(function(){
			var BizID = $(this).attr("Biz_ID");
			
			$.post(shop_ajax_url,{action:'get_shipping_list',BizID:BizID},function(data){
				if(data.status == 1) {
					var htmlTmp = '';
					$.each(data['biz_company_dropdown'],function(i) {
						var v = data['biz_company_dropdown'][i];
						   htmlTmp += '<label>'+
									'<div class="pull-left shipping-company-name">'+
										v+
									'</div>'+
									'<div class="pull-right">'+
										'<input type="radio" '+((data['Default_Shipping'] == i) ? 'checked' : '')+' class="Shiping_ID_Val" name="Shiping_ID_'+data['Biz_ID']+'" Biz_ID="'+data['Biz_ID']+'" value="'+i+'" />'+
									'</div>'+
									'<div class="clearfix"></div>'+
								'</label>';
						});
						var button = '<div id="shipping_ok">确定</div>';
						show_dialog('shipping' + BizID, '配送方式', function() {
				            $('#shipping' + BizID + '_dialog').html(htmlTmp+button);
							$('#shipping' + BizID + '_dialog').on('click','#shipping_ok',function(){
								order_obj.change_shipping_method(BizID);
								DialogManager.close('shipping' + BizID);
							});
			            });
					};
				}, 'json');
		});
		//提交订单
		$('#order_submit').click(function() {
            var AddressID = parseInt($('#addr_list').attr('add_id'));
            $(this).attr('disabled', true);
            var param = $('#order_form').serialize();

            $.post(shop_ajax_url, param + '&AddressID='+AddressID, function(data) {
                if (data.status == 1) {
                    window.location = data.url;
                }else {
					if(typeof data.msg != 'undefined'){
						alert(data.msg);
					}   
				}
            }, 'json');
        });
	},
	change_shipping_method : function(Biz_ID){
		var Shipping_ID = order_obj.getShippingID();
		var City_Code = $("#City_Code").attr('value');
		var cart_key = $("#cart_key").attr('value');
		var action = 'change_shipping_method';
		var param = {
			Biz_ID:Biz_ID,
			Shipping_ID:Shipping_ID,
			City_Code:City_Code,
			cart_key:cart_key,
			action:action
		};
			
		$.post(shop_ajax_url, param, function(data) {
			if(data.status == 1) {
				var total_price = data.total + data.total_shipping_fee;
				if (parseFloat(data.biz_shipping_fee) == 0) {
                    $('#biz_shipping_fee_txt_' + Biz_ID).html('免运费');
                } else {
                    $('#biz_shipping_fee_txt_' + Biz_ID).html(data.biz_shipping_fee + '元');
                }
				$('#Shipping_ID_' + Biz_ID).val(data.biz_shipping_fee);
                $(".wzw-all-account em").html(total_price);
			}
		}, 'json');
	},
	getShippingID : function() {
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
	},
}