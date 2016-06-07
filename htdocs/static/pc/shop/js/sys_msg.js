var sys_msg_obj={
	sys_msg_init:function(){
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
							htmltmp += '<div class="dizhi" id="'+v['id']+'">'+
											'<div class="cut"><a href="javascript:;"></a></div>'+
											'<div>'+
												'<ul>'+
													'<li><span>通知时间：</span><i>'+v['time']+'</i></li>'+
													'<li><span>内容：</span>'+
														'<i>'+v['content']+'</i>'+
													'</li>'+
												'</ul>'+
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
						$('.list').html(htmltmp);
					}else{
						$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('.list').html('<div class="dizhi" style="color:#666;text-align:center">暂无提醒！</div>');
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
		//删除地址
		$('.body_center_pub').on('click', '.dizhi .cut', function(e){
			var that = this;
			$.post(shop_ajax_url, {action:'del_sys_msg',id:$(that).parent('.dizhi').attr('id')}, function(data){
				if(data.status == 1) {
					$(that).parent('.dizhi').remove();
				}
			}, 'json');
		});
	},
}