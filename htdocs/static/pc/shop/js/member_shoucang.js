var member_shoucang_obj={
	member_shoucang_init:function(){
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
							htmltmp += '<div class="fore">'+
											'<div class="delete" f_id="'+v['FAVOURITE_ID']+'">'+
												'<a href="javascript:;">删除</a>'+
											'</div>'+
											'<div class="bbimg">'+
												'<a href="'+v['P_URL']+'"><img src="'+v['ImgPath']+'"></a>'+
											'</div>'+
											'<div class="bbconformation">'+
												'<div class="sc_name">'+
													'<input type="checkbox" name="shop" id="forechoose" style="display: none;"><a href="'+v['P_URL']+'">'+v['Products_Name']+'</a>'+
												'</div>'+
												'<div class="sc_price">'+
													'<span>￥'+v['Products_PriceX']+'</span><i>￥'+v['Products_PriceY']+'</i>'+
												'</div>'+
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
						$('.myshoucang').html(htmltmp);
					 }else{
						$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('.myshoucang').html('<div class="nothing"><span class="icon"></span><span>收藏夹空空的。快去收藏更多的商品吧</span></div>');
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