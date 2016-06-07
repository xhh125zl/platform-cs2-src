var list_obj={
	list_init:function(){
		function load_page(page) {
			var search = $('input[name=search]').val();
			$.ajax({
				type:'get',
				url:ajax_url,
				data:{p:page,search:search},
				success:function(data){
					if(data['list'] != '') {
						var htmltmp = '';
						$.each(data['list'],function(i) {
							v = data['list'][i];
							htmltmp += '<li>'+
											'<dl>'+
												'<dt>'+
													'<a class="goods-thumb" target="_blank" href="'+v['link']+'">'+
														'<img alt="'+v['products_name']+'" src="'+v['ImgPath']+'">'+
													'</a>'+
												'</dt>'+
												'<dd class="goods-name">'+
													'<a target="_blank" title="'+v['products_name']+'" href="'+v['link']+'">'+v['products_name']+'</a>'+
												'</dd>'+
												'<dd class="goods-info">'+
													'<span class="price"><i>¥</i> '+v['products_pricex']+' </span>'+
													'<span class="goods-sold">已售：<strong>'+v['products_sales']+'</strong> 件</span>'+
												'</dd>'+
											'</dl>'+
										'</li>';
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
						$('#listBox').html(htmltmp);
					}else{
						$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#listBox').html('<p style="line-height:30px; text-align:center;color:#999;font-size:12px;">暂无数据！</p>');
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
		$('#searchShop .ncs-btn').click(function(){
			load_page(1);
		});
	},
}