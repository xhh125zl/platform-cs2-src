var member_coupon_obj={
	member_coupon_init:function(){
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
							
								htmltmp += '<div class="coupons_form">'+
												'<div class="top">'+
													'<div class="left">'+
														'<span class="couprice"><i>￥</i>130</span><div class="clear"></div>'+
														'<span>【消费满 239元 可用】</span>'+
														'<span>2015-11-04--2015-11-18</span>'+
													'</div>'+
													'<div class="right">';
								if(data['type'] == 0) {
								    htmltmp += 			'<a href="javascript:;">立即使用</a>';
								}else if(data['type'] == 2) {
									htmltmp += 			'<a href="javascript:;" style="color:#666">已经过期</a>';
								}else {
									htmltmp += 			'<a href="javascript:;">立即领取</a>';
								}			
								htmltmp +=		    '</div>'+
												'</div>'+
												'<div class="buttom">'+
													'<span>券 编 号： 2319138866</span><br>'+
													'<span>品类限制： 仅可购买惠普自营1111彩色喷墨打印机商品</span><br>'+
													'<span>平台限制： 全平台 </span><br>'+
												'</div>'+
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
						$('.coupons').html(htmltmp);
					 }else{
						$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
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
		$(document).on('click', '.delete', function(){
			var f_id = $(this).attr('f_id');
			$.post(shop_ajax_url, {action:'del_shoucang',del_id:f_id}, function(data){
				if(data.status == 1){
					alert(data.msg);
					location.reload();
				}else {
					alert(data.msg);
				}
			}, 'json');
		});
	},
}