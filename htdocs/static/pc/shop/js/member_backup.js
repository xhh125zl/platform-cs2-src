var member_backup_obj={
	member_backup_init:function(){
		function load_page(page) {
			$.ajax({
				type:'post',
				url:ajax_url,
				data:{p:page},
				success:function(data){
					if(data['list'] != '') {
						var htmltmp = '';
						$.each(data['list'],function(i) {
							v = data['list'][i];
							//操作按钮
							var paydo_html = '';
							if(v['Back_Status'] == 0) {
								paydo_html = '申请中..';
							}else if(v['Back_Status'] == 1) {
								paydo_html = '<a href="'+v['backup_send_url']+'">我要发货</a>';
							}else if(v['Back_Status'] == 2) {
								paydo_html = '我已发货';
							}else if(v['Back_Status'] == 3) {
								paydo_html = '卖家收货并确定退款价格';
							}else if(v['Back_Status'] == 4) {
								paydo_html = '完成';
							}
							
							htmltmp +=  '<div class="dingdan"><div class="dd_more"> <span class="name"><img title="'+v['CartList_back']['ProductsName']+'" src="'+v['CartList_back']['ImgPath']+'"><div><a href="'+v['P_URL']+'">'+v['CartList_back']['ProductsName']+'</a></div></span> <span class="price"><i>'+v['CartList_back']['ProductsPriceY']+'</i><br>'+v['CartList_back']['ProductsPriceX']+'</span> <span class="num">'+v['CartList_back']['Qty']+'</span><span class="truepay">￥'+v['Back_Amount']+'</span> <span class="on"><i>'+v['status_arr'][v['Back_Status']]+'</i><br><a href="'+v['detail_url']+'">订单详情</a></span> <span class="paydo">'+paydo_html+'</span> </div></div>';
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
            var param = {
                action: 'commit',
                Order_ID: order_id
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
		/*评论提交*/
		$(document).on('click', '.savecommit', function() {
			$('.box_dizhi_form').fadeIn(200);
            $(this).attr('disabled', true);
			$.post(shop_ajax_url, $('.box_dizhi_form form').serialize(),
            function(data) {
                if (data.status == 1) {
                    alert('评论成功!');
                    window.location = data.url;
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