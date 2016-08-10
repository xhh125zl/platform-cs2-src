function share_guide() {
    $("#mcover").css("display", "block"); // 分享给好友圈按钮触动函数
}

function weChat() {
    $("#mcover").css("display", "none"); // 点击弹出层，弹出层消失
}


var shop_obj = {
    page_init: function() {		
        $('#category .close, #cover_layer').click(function() {
            $('html, body').removeAttr('style');
            $('#cover_layer').hide();
            $('#category').animate({
                left: '-100%'
            }, 500);
            $('#shop_page_contents').animate({
                margin: '0'
            }, 500);
        });

        if ($('#support').size()) {
            $('#support').css('bottom', 0);
            $('#footer').css('bottom', 16);
            $('#footer_points').height(57);
        }

        $('footer .category a').click(function() {
            if ($('#category').height() > $(window).height()) {
                $('html, body, #cover_layer').css({
                    height: $('#category').height(),
                    width: $(window).width(),
                    overflow: 'hidden'
                });
            } else {
                $('#category, #cover_layer').css('height', $(window).height());
                $('html, body').css({
                    height: $(window).height(),
                    overflow: 'hidden'
                });
            }

            $('#cover_layer').show().html('');
            $('#category').animate({
                left: '0%'
            }, 500);
            $('#shop_page_contents').animate({
                margin: '0 -60% 0 60%'
            }, 500);
            window.scrollTo(0);

            return false;
        });
		
        /*确认收货*/
        $("#confirm_receive").click(function() {
            var param = {
                action: 'confirm_receive',
                Order_ID: $(this).attr('Order_ID')
            };


            $.post(base_url + 'api/' + UsersID + '/cloud/member/ajax/', param, function(data) {
				
				if(data.status == 1){
                	window.location.href = base_url + 'api/' + UsersID + '/cloud/member/status/4/';
				}else{
					alert(data.msg);
				}
			},'json');
        });


    },
	tao_detail_init:function(){
		  
		var window_height =  $(document).height();
		var window_half_height  =  $(window).height()/2.5;
		 
 
        //处理轮播图片
        $('#detail_images .touchslider').css('height',$(window).width()*0.8);
        $('#detail_images .touchslider .touchslider-viewport .touchslider-item').css('width',$(window).width());

        $('#detail_images .touchslider .img').css('height',$(window).width()+'px');
        if(proimg_count>0){
            (function(window, $, PhotoSwipe){

              	$('.touchslider-viewport .list a[rel]').photoSwipe({});

            }(window, window.jQuery, window.Code.PhotoSwipe));

            $('.touchslider').touchSlider({mouseTouch:true,autoplay:true,delay:2000 });
        }
  
        function show_footer_panel(){
			
			
			if($('footer').html().length >0 ){

				$('#option_content').html($("#footer").html());
			}
			
			var footer_panel_content = $("#footer_panel_content").html();
			 $('footer').html(footer_panel_content);       
        
		}  
		
		
	    var handler = function(e){
			
			if($(e.targent).attr('class') != 'property'){
					e.preventDefault();
			}

		}
		
		

        show_footer_panel();

        $("#option_select_link").click(function(){
            $("#buy-type").html("menu-addtocard-btn");
			$(window).scrollTop(0);
            var selector_content = $("#option_content").html();
			$("#option_content").html('');			
			$("footer").html(selector_content);
			var footer_height = window_half_height;
            $("footer").height(footer_height);
			var overlay_height  = $(window).height()-$("footer").height();
			global_obj.overlay(overlay_height);
        });
		
        //加入购物车
        $('footer').on('click','#menu-addtocard-btn"',function(){
			$("#buy-type").html('menu-addtocard-btn');
	        add_to_cart();
		});
		//立即购买
		$('footer').on('click','#menu-direct-btn"',function(){
			$("#buy-type").html('menu-direct-btn');
			add_to_cart();
		});
		function add_to_cart(){
			  var needcart = 1;
			  if($("#buy-type").html() == "menu-direct-btn"){
				  $("#needcart").attr("value", 0);
				  needcart = 0;
			  }else{
				  $("#needcart").attr("value", 1);
				  needcart = 1;
			  }
			  
			  $.post($('#addtocart_form').attr('action') + 'ajax/', $('#addtocart_form').serialize(), function(data) {				
                if (data.status == 1) {
					if($("#buy-type").html() == "menu-direct-btn"){
						if(is_virtual==1){
							window.location.href = "/api/"+UsersID+"/cloud/cart/checkout_virtual/";
						}else{
							window.location.href = "/api/"+UsersID+"/cloud/cart/checkout/"+needcart+"/";
						}
					}
					//购物车特效
					$('.icon-container span i').show();
					$('.icon-container span i').html(data.qty);
                   	new Toast({context:$('body'),message:'产品已成功加入购物车',top:$("#footer").offset().top-70}).show();    
                }else{
					new Toast({context:$('body'),message:data.msg,top:$("#footer").offset().top-70,time:4000}).show(); 
				}
            }, 'json');
		}
		
		/*收藏产品*/
		$("footer").on('click','#favorite,#favorited',function(){
			var productId = parseInt($(this).attr('productid'));
            var action = 'favourite';
            var isFavourite = parseInt($(this).attr('isFavourite'));
            var id = $(this).attr("id");
			
            if (isFavourite == parseInt(0)) {
                $.post($('#addtocart_form').attr('action') + 'ajax/', {
                        productId: productId,
                        action: action
                    },
                    function(data) {
                        if (data.status == 0) {
                            self.location.href = data.url
                        } else if (data.status == 1) {
                           
							$("#footer #"+id).attr("isFavourite", 1);
                            $("#footer #"+id).attr('id', "favorited");
                        }
                    },
                    'json');
					
            }else {
				
                $.post($('#addtocart_form').attr('action') + 'ajax/', {
                        productId: productId,
                        action: 'cancel_favourite'
                    },
					
                    function(data) {
                        if (data.status == 0) {
                            self.location.href = data.url
                        } else if (data.status == 1) {
                            $("#footer #"+id).attr("isFavourite", 0);
                            $("#footer #"+id).attr('id', "favorite");
                        }
                    },
                    'json');

            }

		});
		
		/*分销产品*/
        $("#share_product").click(function() {
			var url = "/api/"+UsersID+'/cloud/cart/ajax/';
			var is_distribute = $(this).attr("is_distribute");
			var productid = $(this).attr("productid");
			$.post(url,{is_distribute:is_distribute,productid:productid,action:'distribute_product'},function(data){
				if (data.status == 0) {
                       self.location.href = data.url
                } else if (data.status == 1) {
                  	//如果此用户为分销用户
                    if(data.Is_Distribute == 1){
					    window.location.href = "/api/"+UsersID+'/cloud/distribute/distribute_goods/'+productid+'/';
					}else{
					    window.location.href = "/api/"+UsersID+'/cloud/distribute/join/';
					}
                }
			},'json');
        });
	},
    detail_init: function() {
		$('#detail_images .touchslider').css('height',$(window).width()*0.8);
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

        $('#detail .desc #tag0').click(function() {
            $('#detail .desc span').removeClass();
            $(this).addClass('cur');
            $('#description').show();
            $('#commit').hide();
        });

        $('#detail .desc #tag1').click(function() {
            $('#description').hide();
            $('#commit').show();
            $('#detail .desc span').removeClass();
            $(this).addClass('cur');
        });


        /*分享产品*/
         $("#share_product").click(function() {

			var url = "/api/"+UsersID+'/cloud/cart/ajax/';
			var is_distribute = $(this).attr("is_distribute");
			var productid = $(this).attr("productid");
			$.post(url,{is_distribute:is_distribute,productid:productid,action:'distribute_product'},function(data){
				 if (data.status == 0) {
                       self.location.href = data.url
                  } else if (data.status == 1) {
                  		//如果此用户为分销用户
                       if(data.Is_Distribute == 1){
					   		window.location.href = "/api/"+UsersID+'/cloud/distribute/distribute_goods/'+productid+'/';
					   }else{
					   		window.location.href = "/api/"+UsersID+'/cloud/distribute/join/';
					   }
                   }
				
			},'json');
			
			
        });


        /*收藏产品*/
        $("#favorite,#favorited").click(function() {
            var productId = parseInt($(this).attr('productid'));
            var action = 'favourite';
            var isFavourite = parseInt($(this).attr('isFavourite'));
            var id = $(this).attr("id");

            if (isFavourite == parseInt(0)) {
                $.post($('#addtocart_form').attr('action') + 'ajax/', {
                        productId: productId,
                        action: action
                    },
                    function(data) {
                        if (data.status == 0) {
                            self.location.href = data.url
                        } else if (data.status == 1) {
                            $("#" + id).attr("isFavourite", 1);
                            $("#" + id).attr('id', "favorited");

                        }
                    },
                    'json');
            } else {
                $.post($('#addtocart_form').attr('action') + 'ajax/', {
                        productId: productId,
                        action: 'cancel_favourite'
                    },
                    function(data) {
                        if (data.status == 0) {
                            self.location.href = data.url
                        } else if (data.status == 1) {
                            $("#" + id).attr("isFavourite", 0);
                            $("#" + id).attr('id', "favorite");
                        }
                    },
                    'json');

            }

        });

        $('#detail a[name=minus]').click(function() {
            var qty = parseInt($('#detail input[name=Qty]').val()) - 1;
            if (qty < 1) {
                qty = 1;
            }
            $('#detail input[name=Qty]').val(qty);
        });

        $('#detail a[name=add]').click(function() {
            var qty = parseInt($('#detail input[name=Qty]').val()) + 1;
            var total_stock = parseInt($('#stock_val').html());
            if (qty > total_stock) {
                qty = total_stock;
            }
            $('#detail input[name=Qty]').val(qty);
        });

        $('#detail input[name=Qty]').keyup(function() {
            var qty = $(this).val();
            var total_stock = parseInt($('#stock_val').html());
            if (qty < 1) {
                qty = 1;
            }
            if (qty > total_stock) {
                qty = total_stock;
            }
            $('#detail input[name=Qty]').val(qty);
        });

        $('#detail .property span').click(function() {
            var PName = $(this).attr('PName');
            $('#detail .property span[PName=' + PName + ']').removeClass();
            $(this).addClass('cur');
            $('#detail #' + PName).val($(this).text());
        });

        $('#addtocart_tips .close').click(function() {
            $(this).parent().hide();
        });

        $('#addtocart_form').submit(function() {
            return false;
        });

        $('#addtocart_form .cart .add, #addtocart_form .cart .buy').click(function() {
            var this_btn = $(this);
            this_btn.attr('disabled', true);
			if (this_btn.attr('class') == 'buy') {
                $("#needcart").val(0);
            }else{
				$("#needcart").val(1);
			}
            $.post($('#addtocart_form').attr('action') + 'ajax/', $('#addtocart_form').serialize(), function(data) {
                this_btn.attr('disabled', false);
                if (data.status == 1) {
                    if (this_btn.attr('class') == 'buy') {
                        window.location = $('#addtocart_form').attr('action');
                    } else {
                        $('#addtocart_tips .qty').html(data.qty);
                        $('#addtocart_tips .total').html('￥' + data.total);
                        $('#addtocart_tips').css({
                            left: $(window).width() / 2 - 125,
                            top: $(window).height() / 2 - 60
                        }).show();
                    }
                }
            }, 'json');
        });
    },

    cart_init: function() {
        var price_detail = function() {
            var total_price = 0;
            $('#cart_form .sub_total span span').each(function() {
                var price = parseFloat($(this).parent().parent().siblings('.price').children('span').html().replace('￥', ''));
                var qty = parseInt($(this).parent().siblings('input[name=Qty\\[\\]]').val());
                isNaN(qty) && (qty = 1);
                var sub_total = price * qty;
                sub_total = sub_total.toFixed(2);
                $(this).html('￥' + sub_total);
                total_price += price * qty;
            });
            $('#cart_form .total span').html('￥' + total_price.toFixed(2));
        }

        price_detail();

        $('#cart_form input[name=Qty\\[\\]]').keyup(function() {
            //var qty=parseInt($(this).val().replace(/[^\d]/g, ''));
            var obj = $(this);
            var qty = $(this).val();
            qty >= 1000 && (qty = 999);
            $(this).val(qty);

            var _Qty = $(this).parent().children('input[name=Qty\\[\\]]').val();
            var _CartID = $(this).parent().children('input[name=CartID\\[\\]]').val();
            $.post($('#cart_form').attr('action') + 'ajax/', $('#cart_form').serialize() + '&_Qty=' + _Qty + '&_CartID=' + _CartID, function(data) {
                if (data.status == 1) {
                    obj.parent().siblings('.price').children('span').html('￥' + data.price);
                    price_detail();
                } else {
                    global_obj.win_alert('出现未知错误！');
                }
            }, 'json');
        });

        $('#cart_form .del div').click(function() {
            var obj = $(this);
            $.post($('#cart_form').attr('action') + 'ajax/', 'action=del&CartID=' + $(this).attr('CartID'), function(data) {
                
				if (data.status == 1) {
                    $('#cart_form .total span').html('￥' + data.total);
                    obj.parent().parent().remove();
                    if (data.total == 0) {
                        window.location = $('#cart_form').attr('action');
                    }
                }
				
            }, 'json');
        });

        $('#cart_form').submit(function() {
            return false;
        });
		
        $('#cart_form .checkout input').click(function() {
            $(this).attr('disabled', true);
            $('#cart_form input[name=action]').val('check');
            $.post($('#cart_form').attr('action') + 'ajax/', $('#cart_form').serialize(), function(data) {
                $('#cart_form .checkout input').attr('disabled', false);
                if (data.status == 1) {
					
					window.location = $('#cart_form').attr('action') + 'checkout/1/';
                } else {
                    window.location = $('#cart_form').attr('action');
                }
            }, 'json');
        });
    },

    checkout_init: function() {
        var address_display = function() {
            var AddressID = parseInt($('#checkout_form input[name=AddressID]:checked').val());
            if (AddressID == 0 || isNaN(AddressID)) {
                $('#checkout .address dl').css('display', 'block');
            } else {
                $('#checkout .address dl').css('display', 'none');
            }
        }

        var total_price_display = function() {

            var shipping_base_price = parseFloat($('#checkout_form input[name=Shipping\\[Express\\]]:checked').attr('Base_Price'));
            var shipping_continue_price = parseFloat($('#checkout_form input[name=Shipping\\[Express\\]]:checked').attr('Continue_Price'));

            var shipping_weight = parseInt($("#shipping_weight").html()) / 500;

            var shipping_price = 0;

            if(shipping_weight > 0 ){
              if(shipping_weight >= 1) {
                var shipping_price = shipping_base_price + shipping_continue_price * (shipping_weight - 1);
              }
              
              if(shipping_weight <1 ){
                var shipping_price = shipping_base_price;
              }
            }else{
                var shipping_price = 0;
            }


            $("#shipping_price").html(shipping_price.toFixed(2));

            isNaN(shipping_price) && (shipping_price = 0);
            var total_price = parseFloat($('#checkout_form input[name=total_price]').val());
            var coupon_price = parseFloat($('#checkout_form input[name=CouponID]:checked').attr('Price'));
            
			if (isNaN(coupon_price) == false && coupon_price > 0) {
                var usetype = parseInt($('#checkout_form input[name=CouponID]:checked').attr('UseType'));
                if (usetype == 1) {
                    total_price = total_price - coupon_price
                } else {
                    total_price = total_price * coupon_price
                }
            }
			
            var total_amount = parseFloat(total_price) + shipping_price;
            $('#checkout_form .total_price span').html('￥' + total_amount.toFixed(2));

        }

        $('#checkout_form input[name=AddressID]').click(address_display);
        $('#checkout_form input[name=Shipping\\[Express\\]]').click(total_price_display);
        $('#checkout_form input[name=CouponID]').click(total_price_display);
        address_display();
        total_price_display();

        $('#checkout_form').submit(function() {
            return false;
        });
        $('#checkout_form .checkout input').click(function() {
            var AddressID = parseInt($('#checkout_form input[name=AddressID]:checked').val());
            if (AddressID == 0 || isNaN(AddressID)) {
                if (global_obj.check_form($('*[notnull]'))) {
                    return false
                };
            }


            $(this).attr('disabled', true);

            $.post($('#checkout_form').attr('action') + 'ajax/', $('#checkout_form').serialize() + '&Shipping\[Price\]=' + parseFloat($("#shipping_price").html()), function(data) {
                if (data.status == 1) {
					
                    window.location = data.url;
                }
            }, 'json');
        });

      
    },
	select_payment_init:function(){
		
		/*同步数据库*/
		function diyong_sync(money,total_price){
				var Order_ID =  $("input[name='OrderID']").val();
				var Integral_Consumption = $("#Integral_Consumption").attr("value");
				var form_url = $("#payment_form").attr("action");
				var param = {'action':'diyong','Order_ID':Order_ID,'Integral_Consumption':Integral_Consumption,'Integral_Money':money};
				
				$.post(form_url+'ajax/',param,function(data){
					if(data.status == 1){
						
						$("#Order_TotalPrice").html("&yen;"+total_price);
						$("#btn-diyong").addClass("disabled");
					}
				},'json');
		}
		
		
		/*抵用操作*/
        $("#btn-diyong").click(function() {
            var diyong_rate = parseInt($("#diyong_rate").html());
            var user_integral = parseInt($("#user-integral").html());
            var can_diyong = parseInt($("#can-diyong").html());
	
            if (user_integral >= can_diyong) {
		
                var money = can_diyong / diyong_rate;
                var total_price = parseFloat($("input[name='total_price']").val());
             
				$("input[name='total_price']").attr("value", total_price - money);
                $(this).after("<span>&nbsp;&nbsp;&nbsp;&nbsp;抵用了" + money + "元</span>");
              
				$("#Integral_Consumption").attr("value",can_diyong);
                $("#User_Integral").attr("value", user_integral - can_diyong);
				$("#user-integral").html(user_integral - can_diyong);
				diyong_sync(money,total_price - money);
            } else {
				//如果用户积分小于可抵用积分，只能使用一部分积分
                var money = user_integral/diyong_rate;
                var total_price = parseFloat($("input[name='total_price']").val());
                
				$("input[name='total_price']").attr("value", total_price - money);
                $(this).after("<span>&nbsp;&nbsp;&nbsp;&nbsp;抵用了" + money + "元</span>");
            
				$("#Integral_Consumption").attr("value",user_integral);
                $("#User_Integral").attr("value", 0);
                $("#user-integral").html(0);
				diyong_sync(money,total_price - money);
            }


          

        });
	},
    payment_init: function() {
       // var PaymentMethod = $('#payment_form input[name=PaymentMethod]');
       	
		$("a.direct_pay").click(function(){
			
			var PaymentMethod = $(this).attr("data-value"); 
			var PaymentID = $(this).attr("id");
			$("#PaymentMethod_val").attr("value",PaymentMethod);
			
			shop_obj.submit_payment();
			
			
			
		});
		
		/*余额支付确认支付,与线下支付确认*/
		$("#btn-confirm").click(function(){
			shop_obj.submit_payment();
		});
	     
		 /*
	    if (PaymentMethod.size()) {
            var change_payment_method = function() {
                if (PaymentMethod.filter(':checked').val() == '线下支付') {
                    $('#payment_form .payment_info').show();
                    $('#payment_form .payment_password').hide();
                } else {
                    if (PaymentMethod.filter(':checked').val() == '余额支付') {
                        $('#payment_form .payment_password').show();
                        $('#payment_form .payment_info').hide();
                    } else {
                        $('#payment_form .payment_info').hide();
                        $('#payment_form .payment_password').hide();
                    }
                }
            }
            PaymentMethod.click(change_payment_method);
            PaymentMethod.filter('[value=' + $('#payment_form input[name=DefautlPaymentMethod]').val() + ']').click();
            change_payment_method();
        } else {
            $('#payment_form').hide();
        }
		*/

        $('#payment_form').submit(function() {
            return false;
        });
		
       
    },
 	submit_payment:function(){
		 $('#payment_form .payment input').attr('disabled', true);
            $.post($('#payment_form').attr('action') + 'ajax/', $('#payment_form').serialize(), function(data) {
                $('#payment_form .payment input').attr('disabled', false);
                if (data.status == 1) {
                    window.location = data.url
                } else {
                    global_obj.win_alert(data.msg);
                }
            }, 'json');
	},
    user_address_init: function() {
        $('#address_form .back').click(function() {
            window.location = './';
        });

        $('#address_form').submit(function() {
            return false;
        });
        $('#address_form .submit').click(function() {
            if (global_obj.check_form($('*[notnull]'))) {
                return false
            };

            $(this).attr('disabled', true);
            $.post($('#address_form').attr('action') + 'ajax/', $('#address_form').serialize(), function(data) {
                if (data.status == 1) {
                    window.location = $('#address_form').attr('action') + 'address/';
                }
            }, 'json');
        });
    },

    commit_init: function() {
        $('#commit_form .back').click(function() {
            history.back();
        });

        $('#commit_form').submit(function() {
            return false;
        });
        $('#commit_form .submit').click(function() {
            if (global_obj.check_form($('*[notnull]'))) {
                return false
            };

            $(this).attr('disabled', true);
            $.post($('#commit_form').attr('action') + 'ajax/', $('#commit_form').serialize(), function(data) {
                if (data.status == 1) {
                    global_obj.win_alert('评论成功!', function() {
                        window.location = $('#commit_form').attr('action') + 'status/4/';
                    });
                } else {
                    global_obj.win_alert(data.msg);
                }
            }, 'json');
        });
    },
    backup_init: function() {

        $("input.back_num").blur(function() {
            var qty = $(this).attr("qty");
            var value = $(this).val();
            if (value > qty) {
                $(this).after('<span id="num_tip" class="fc_red">您只购买了' + qty + '个产品</span>');
                $(this).attr("value", "")
            }
        });

        $("input.back_num").keydown(function() {
            $("#num_tip").remove();
        });

        $("input[name='Products_ID[]']").click(function() {
            var product_id = $(this).val();
            if ($(this).is(':checked')) {

                $("#backup_reason_" + product_id).css({
                    display: "block"
                });

            } else {
                $("#backup_reason_" + product_id).css({
                    display: "none"
                });

            }


        });

        /*提交*/
        $("#submit_shipping").click(function(e) {
            if (global_obj.check_form($('*[notnull]'))) {
                return false;
            };

            var url = $("form#backup_shipping_form").attr("action") + "ajax/";
            var params = $("form#backup_shipping_form").serialize();
            params = decodeURIComponent(params, true);


            $.post(url, params,
                function(data) {
                    if (data.status == 0) {
                        alert("信息提交错误");
                    } else if (data.status == 1) {
                        
						window.location = data.url;
                    }
                },
                'json');

            e.preventDefault();
        });

        /*申请退货*/
        $("#apply_backup").click(function(e) {

            var chk_value = []; //定义一个数组    
            $("input[name='Products_ID[]']:checked").each(function() { //遍历每一个名字为interest的复选框，其中选中的执行函数    

                chk_value.push($(this).val()); //将选中的值添加到数组chk_value中    
            });

            if (chk_value.length == 0) {

                alert("请勾选您要退货的商品");
            } else {
                var flag = true;
                $("input[name='Products_ID[]']:checked").each(function() {
                    var id = $(this).val();

                    if (global_obj.check_form($("#backup_reason_" + id).find('*[notnull]'))) {
                        flag = false;
                    };
                });

                if (!flag) {
                    return false;
                }

                var Order_ID = $("#Order_ID").val();
                var products = chk_value.join("_");
                var url = $("form#apply_form").attr("action") + "ajax/";
				
                var params = $("form#apply_form").serialize();
                params = decodeURIComponent(params, true);


                $.post(url, params,
                    function(data) {
                        if (data.status == 0) {
                           alert("信息提交错误");
                        } else if (data.status == 1) {
                           window.location = data.url;
                        }
                    },
                    'json');

            }


        });
    },

    distribute_init: function() {

        $('#bank_card').inputFormat('account');

    


        $("#edit-bankcard-btn").click(function() {
        
            if (global_obj.check_form($('input["name=bank_card"][notnull]'))) {
                return false
            };

            var url = $("#account_form").attr("action");
            var param = $("#account_form").serialize();

            $.post(url, param, function(data) {
                if (data.status == 1) {
                    global_obj.win_alert('银行卡账号修改成功', function() {
                        window.location.href = base_url + 'api/' + UsersID + '/cloud/distribute/';
                    });
                }
            }, 'json');

        });

        $("a.remove-msg").click(function() {

            $(this).parent().parent().remove();

            $.post(url, param, function(data) {
                if (data.status == 1) {
                    global_obj.win_alert('提现申请提交成功', function() {
                        window.location.reload();
                        //window.location.href = base_url+'api/'+UsersID+'/cloud/distribute/';
                    });
                }
            }, 'json');
        });


        /*提现申请*/
        $("#btn-withdraw").click(function() {

            if (global_obj.check_form($('input["name=money"][notnull]'))) {
                return false
            };
            var balance = parseInt($("#balance").val());
            var money = parseInt($("input[name='money']").val());
			
			if(money <= 0){
				   $(this).after("<label id=\"withdraw_tip\" class=\"fc_red\">必须大于0</label>");
                return false;
			}
            if (balance < money) {
                $("#withdraw-money").css({
                    border: '1px solid red'
                });
                $(this).after("<label id=\"withdraw_tip\" class=\"fc_red\">余额不足</label>");
                return false;
            }

            var url = $("#withdraw-form").attr("action");
            var param = $("#withdraw-form").serialize();

            $.post(url, param, function(data) {
                if (data.status == 1) {
                    global_obj.win_alert('提现申请提交成功', function() {
                        window.location.reload();
                        //window.location.href = base_url+'api/'+UsersID+'/cloud/distribute/';
                    });
                }
            }, 'json');

        })


        $("#withdraw-money").keydown(function() {
            $("#withdraw_tip").remove();
            $("#withdraw-money").css({
                border: '0px solid red'
            });
        });

        //展开提现记录
        $("a.record-title").click(function() {
            var status = $(this).attr("status");
            if (status == 'close') {
                $(this).find("span.icon").removeClass("icon-chevron-up").addClass("icon-chevron-down");
                $(this).parent().next().css({
                    display: 'block'
                });
                $(this).attr("status", "open");
            } else {
                $(this).find("span.icon").removeClass("icon-chevron-down").addClass("icon-chevron-up");
                $(this).parent().next().css({
                    display: 'none'
                });
                $(this).attr("status", "close");
            }


        });




    },
    join_distribute_init:function(){

    	$('#bank_card').inputFormat('account');
        
            /*表单验证*/
        var remote =  base_url+'/api/cloud/distribute/ajax.php?UsersID='+UsersID;
        real_name_remote = remote+'&action=check_exist&field=Real_Name';

        var id_card_remote = remote+"&action=check_exist&field=ID_Card";
        var email_remote =   remote+"&action=check_exist&field=Email";
      
      
        $("#join-distribute-form").validate({
            rules: {
                real_name: {required:true,remote:real_name_remote},
                email: {
                    required: true,
                    email: true,
                    remote:email_remote
                    
                },
                idcard: {
                    required: true,
                    number: true,
                   	remote:id_card_remote
                },
                alipay_account: 'required',
                Bank_Card: {
                    required: true
                },
				Bank_Name:{
					required:true
				}
            },
            
            messages:{
            	real_name:{remote:'此姓名已被占用'},
            	idcard:{ remote:'此身份证号已被占用'},
            	email:{remote:'此邮箱已被占用'}
            },

            onfocusout: function(element) {
                this.element(element);
            }
        });


        /*加入营销会员申请*/
        $("#join-distribute-btn").click(function() {

            var url = $("#join-distribute-form").attr("action");
            var param = $("#join-distribute-form").serialize();
          	if($("#join-distribute-form").valid()){

            	$.post(url, param, function(data) {
                	if (data.status == 1) {
                	global_obj.win_alert('您已经成为分销用户', function() {
                        window.location.href = base_url + 'api/' + UsersID + '/cloud/distribute/';
                    });
                 	}
            	 }, 'json');

           }else{
           	return false;
           }
           
        });
		
		/*弹出规则框*/
		$("#see_regulation").click(function(){
			var o=$(this);
			global_obj.div_mask();
			$('#seee_regulation_div').show();
			$('#cancel_view').off().click(function(){
				global_obj.div_mask(1);
				$('#seee_regulation_div').hide();
			});
			
		});
    },
	
	//虚拟物品购买页
	products_buy_init:function(){
		  
		var window_height =  $(document).height();
		var window_half_height  =  $(window).height()/2.5;
  
        function show_footer_panel(){
			
			
			if($('#footer').html().length >0 ){

				$('#option_content').html($("#footer").html());
			}
			
			var footer_panel_content = $("#footer_panel_content").html();
			 $('footer').html(footer_panel_content);       
        
		}  
		
		
	    var handler = function(e){
			
			if($(e.targent).attr('class') != 'property'){
					e.preventDefault();
			}

		}
		
		

        show_footer_panel();

        $("#option_select_link").click(function(){
            $("#buy-type").html("menu-addtocard-btn");
			$(window).scrollTop(0);
            var selector_content = $("#option_content").html();
			$("#option_content").html('');			
			$("footer").html(selector_content);
			var footer_height = window_half_height;
            $("footer").height(footer_height);
			//var overlay_height  = $(window).height()-$("footer").height();
			//global_obj.overlay(overlay_height);	
        });
		

        $('footer').on('click','#menu-addtocard-btn,#menu-direct-btn"',function(){
			
			$("#buy-type").html($(this).attr("id"));
			$(window).scrollTop(0);
            var selector_content = $("#option_content").html();     
			 
			$("#option_content").html('');	
			$("footer").html(selector_content);
			var footer_height = window_half_height;
            $("footer").height(footer_height);
			
			var overlay_height  = $(window).height()-$("footer").height();
			global_obj.overlay(overlay_height);
		});

		
        $('footer').on('click','#close-btn',function(){
			
			$("#option_content").html($("footer").html());
			$("footer").css("height","45px");
            show_footer_panel();
			global_obj.cancel_overlay();
			$(".option-val-list").html();
			
			
        });

        $('footer').on('click','#selector_confirm_btn',function(){
		   
			$("#option_content").html($("footer").html());
			$("footer").css("height","45px");
            add_to_cart();	
			show_footer_panel();
			global_obj.cancel_overlay();
			
		});
		
		
		/*产品qty改变*/
		$("footer").on("click","#qty_selector a[name=minus]",function(){
			
			var qty = parseInt($('#qty_selector input[name=Qty]').val()) - 1;
			var total_stock = parseInt($('#stock_val').html());
            
			if (qty < 1) {
                qty = 1;
            }
			
			var cur_price = parseFloat($("#cur_price").val());
			var float_qty =  parseFloat(qty+'.00').toFixed(2);
			$('#cur-price-txt').html(parseFloat(cur_price*float_qty).toFixed(2));
			
            $('#qty_selector input[name=Qty]').attr("value",qty);
		});
		
		$("footer").on("click","#qty_selector a[name=add]",function(){
			

			var qty = parseInt($('#qty_selector input[name=Qty]').val()) + 1;
			var total_stock = parseInt($('#stock_val').html());
            
			if (qty > total_stock) {
                qty = total_stock;
            }
			
			var cur_price = parseFloat($("#cur_price").val());
			var float_qty =  parseFloat(qty+'.00').toFixed(2);
			$('#cur-price-txt').html(parseFloat(cur_price*float_qty).toFixed(2));
			
            $('#qty_selector input[name=Qty]').attr("value",qty);
			
		});
		
		
		$("footer").on("change","#qty_selector input[name=Qty]",function(){
				
			var qty = parseInt($('#qty_selector input[name=Qty]').val());
            var total_stock = parseInt($('#stock_val').html());
		
			if (qty < 1) {
                qty = 1;
			}
         
			if (qty > total_stock) {
                qty = total_stock;
            }
			
			var cur_price = parseFloat($("#cur_price").val());
			var float_qty =  parseFloat(qty+'.00').toFixed(2);
			$('#cur-price-txt').html(parseFloat(cur_price*float_qty).toFixed(2));
			
            $('#qty_selector input[name=Qty]').attr("value",qty);
		});
		
		
		/*确定属性选择*/
	 
		
		function add_to_cart(){
			  var needcart = 1;
			  if($("#buy-type").html() == "menu-direct-btn"){
				  $("#needcart").attr("value",0);
				  needcart = 0;
			  }else{
				  $("#needcart").attr("value",1);
				  needcart = 1;
			  }
			  $.post($('#addtocart_form').attr('action') + 'ajax/', $('#addtocart_form').serialize(), function(data) {
               
                if (data.status == 1) {
					
					if($("#buy-type").html() == "menu-direct-btn"){
						if(is_virtual==1){
							window.location.href = "/api/"+UsersID+"/cloud/cart/checkout_virtual/";
						}else{
							window.location.href = "/api/"+UsersID+"/cloud/cart/checkout/"+needcart+"/";
						}
					}
					
                   	new Toast({context:$('body'),message:'产品已成功加入购物车',top:$("#footer").offset().top-70}).show();    
					
                }else{
						new Toast({context:$('body'),message:data.msg,top:$("#footer").offset().top-70,time:4000}).show(); 
				}
            }, 'json');
		}
		
		/*收藏产品*/
		$("footer").on('click','#favorite,#favorited',function(){
			
			var productId = parseInt($(this).attr('productid'));
            var action = 'favourite';
            var isFavourite = parseInt($(this).attr('isFavourite'));
            var id = $(this).attr("id");
			
            if (isFavourite == parseInt(0)) {
                $.post($('#addtocart_form').attr('action') + 'ajax/', {
                        productId: productId,
                        action: action
                    },
                    function(data) {
                        if (data.status == 0) {
                            self.location.href = data.url
                        } else if (data.status == 1) {
                           
							$("#footer #"+id).attr("isFavourite", 1);
                            $("#footer #"+id).attr('id', "favorited");
                        }
                    },
                    'json');
					
            }else {
				
                $.post($('#addtocart_form').attr('action') + 'ajax/', {
                        productId: productId,
                        action: 'cancel_favourite'
                    },
					
                    function(data) {
                        if (data.status == 0) {
                            self.location.href = data.url
                        } else if (data.status == 1) {
                            $("#footer #"+id).attr("isFavourite", 0);
                            $("#footer #"+id).attr('id', "favorite");
                        }
                    },
                    'json');

            }

		});
		
		/*分销产品*/
        $("#share_product").click(function() {
			var url = "/api/"+UsersID+'/cloud/cart/ajax/';
			var is_distribute = $(this).attr("is_distribute");
			var productid = $(this).attr("productid");
			$.post(url,{is_distribute:is_distribute,productid:productid,action:'distribute_product'},function(data){
				 if (data.status == 0) {
                       self.location.href = data.url
                  } else if (data.status == 1) {
                  		//如果此用户为分销用户
                       if(data.Is_Distribute == 1){
					   		window.location.href = "/api/"+UsersID+'/cloud/distribute/distribute_goods/'+productid+'/';
					   }else{
					   		window.location.href = "/api/"+UsersID+'/cloud/distribute/join/';
					   }
                   }
				
			},'json');
			
			
        });
    

	
      
	},

}
