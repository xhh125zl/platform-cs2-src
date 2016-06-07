var distribute_record_obj={
	distribute_record_init:function(){
		function load_page(page) {
			$.ajax({
				type:'post',
				url:ajax_url,
				data:{p:page,filter:filter},
				success:function(data){
					if(data['list'] != '') {
						var htmltmp = '';
						$.each(data['list'],function(i) {
							v = data['list'][i];
							htmltmp += '<div class="bottom">'+
											'<div class="time"><span>'+v['Record_CreateTime']+'</span></div>'+
											'<div class="fenxiaostyle"><span>'+v['Type']+'</span></div>'+
											'<div class="fenxiaochanpin">'+
												'<div class="img"><a href="'+v['P_URL']+'"><img src="'+v['ImgPath']+'"></a></div>'+
												'<div class="word">'+
													'<a class="name" style="width:247px;" href="'+v['P_URL']+'">'+v['Products_Name']+'</a><br>'+
													'<span class="price">￥'+v['Products_PriceX']+'</span>'+
												'</div>'+
											'</div>'+
											'<div class="jiangjin"><span>￥'+v['Record_Money']+'</span></div>'+
											'<div class="zhuangtai"><span>'+v['Status']+'</span></div>'+
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
						$('.list').html('<p style="line-height:30px; text-align:center;color:#999;">暂无记录！</p>');
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
	},
}