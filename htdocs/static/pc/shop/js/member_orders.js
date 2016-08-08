var member_orders_obj={
	member_orders_init:function(){
		function load_page(page) {
			$.ajax({
				type:'post',
				url:ajax_url,
				data:{p:page,Status:Status},
				success:function(data){
					if(data['list'] != '') {
						var htmltmp = '';
						$.each(data['list'],function(i) {
							v = data['list'][i];
							//操作按钮
							var paydo_html = '';
							if(v['Order_Status'] == 0) {
								paydo_html = '<a href="'+v['del_url']+'">取消</a>';
							}else if(v['Order_Status'] == 1) {
								paydo_html = '<a href="'+v['del_url']+'">取消</a>&nbsp;&nbsp;<a href="'+v['pay_url']+'">付款</a>';
							}else if(v['Order_Status'] == 2) {
								paydo_html = '等待发货..';
							}else if(v['Order_Status'] == 3) {
                                                            if (v['product_num'] > 1) {
                                                                paydo_html = '<a href="javascript:;" class="confirm_receive" Order_ID="'+v['Order_ID']+'">确认收货</a>&nbsp;&nbsp;<a href="'+v['detail_url']+'">申请退款</a>';
                                                            } else {
                                                                paydo_html = '<a href="javascript:;" class="confirm_receive" Order_ID="'+v['Order_ID']+'">确认收货</a>&nbsp;&nbsp;<a href="'+v['backup_url']+'">申请退款</a>';
                                                            }
								
							}else if(v['Order_Status'] == 4) {
								paydo_html = '<a href="javascript:;" class="commit" Order_ID="'+v['Order_ID']+'">评论</a>';
							}
							htmltmp +=  '<div class="dingdan">'+
											'<div class="dd_nt"> <span>订单编号:<i>'+v['order_sn']+'</i></span><span>下单时间：<i>'+v['Order_CreateTime']+'</i></span><span><a href="'+v['shipping_trace']+'">'+v['Express_Name']+'</a><a href="'+v['shipping_trace']+'">快递跟踪</a></span></div>'+
											'<div class="dd_more"> <span class="name"><img src="'+v['products_img']+'"title="'+v['ProductsName']+'"  />'+
												'<div><a href="'+v['products_url']+'">'+v['ProductsName']+'</a>';
							if(v['Property']) {
								$.each(v['Property'], function(j) {
									htmltmp +=	'<span>'+v['Property'][j]['Name']+' ：'+v['Property'][j]['Value']+'</span><br />';
								});
							}
							htmltmp +=  '</div>'+
												'</span> <span class="price"><i>'+v['ProductsPriceY']+'</i><br />'+
												''+v['ProductsPriceX']+'</span> <span class="num">'+v['ProductsQty']+'</span><span class="truepay">￥'+v['Order_TotalPrice']+'</span> <span class="on"><i>'+v['status_arr'][v['Order_Status']]+'</i><br />'+
												'<a href="'+v['detail_url']+'">订单详情</a></span> <span class="paydo">'+paydo_html+'</span> </div>'+
										'</div>';
						});
						if($('input[name=page]').val() == 1) {
							$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						}else {
							$('#up').attr('flag','true').css({'color':'#333','cursor':'pointer'});
						}
						if(data.totalpage == $('input[name=page]').val()) {
							$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						}else {
							$('#down').attr('flag','true').css({'color':'#333','cursor':'pointer'});
						}
						$('#cur_page').html($('input[name=page]').val());
						$('#total_page').html(data.totalpage);
						$('.manydingdan').html(htmltmp);
					}else{
						$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('.manydingdan').html('<p style="line-height:30px; text-align:center;color:#999;">暂无订单！</p>');
					}
				},
				dataType:'json',
			});
		}
        load_page($('input[name=page]').val());
		$('.fanye').on('click','#up[flag=true]',function(){//上一页
			var page = parseInt($('input[name=page]').val()) - 1;
			$('input[name=page]').val(page);
			load_page($('input[name=page]').val());
		});
		$('.fanye').on('click','#down[flag=true]',function(){//下一页
			var page = parseInt($('input[name=page]').val()) + 1;
			$('input[name=page]').val(page);
			load_page($('input[name=page]').val());
		});
		$('.fanye').on('click','#submit',function(){//跳转
		    if($('#text').val() != ''){
				var page = parseInt($('#text').val());
				$('input[name=page]').val(page);
				load_page($('input[name=page]').val());
			}	
		});
		/*确认收货*/
        $(document).on('click', '.confirm_receive', function() {
			$(this).attr('disabled', true);
            var param = {
                action: 'confirm_receive',
                Order_ID: $(this).attr('Order_ID'),
            };

            $.post(shop_ajax_url, param,
            function(data) {

                if (data.status == 1) {
					if(data.url){
					    window.location.href = data.url;	
					}
                } else {
                    alert(data.msg);
                }
            },
            'json');
        });
		/*评论弹窗*/
        $(document).on('click', '.commit', function() {
			$('.box_dizhi_form').fadeIn(200);
			var order_id = $(this).attr('Order_ID');
			$('input[name=Order_ID]').val(order_id);
        });
		/*评论提交*/
		$(document).on('click', '.savecommit', function() {
			$('.box_dizhi_form').fadeIn(200);
            $(this).attr('disabled', true);
			$.post(shop_ajax_url, $('.box_dizhi_form form').serialize(),
            function(data) {
                if (data.status == 1) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            }, 'json');
        });
		$(document).on('click', '.box_dizhi_form .cut', function() {
			$('.box_dizhi_form').fadeOut(200);
        });
	},
}