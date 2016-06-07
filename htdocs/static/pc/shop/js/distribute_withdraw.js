var distribute_withdraw_obj={
	distribute_withdraw_init:function(){
		function load_page(page) {
			$.ajax({
				type:'post',
				url:ajax_url,
				data:{p:page,status:status},
				success:function(data){
					if(data['list'] != '') {
						var htmltmp = '';
						$.each(data['list'],function(i) {
							v = data['list'][i];
							htmltmp += '<li><span class="time">'+v['Record_CreateTime']+'</span><span class="change">￥'+v['Record_Money']+'</span><span class="zhuangtai">'+v['Status']+'</span></li>';
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
						$('.center ul').html(htmltmp);
					}else{
						$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						//$('.see').hide();
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
		$('.top').on('click','.buttom',function(){
		    $('.tixian').show();	
		});
		$('.cut').click(function(){
			$('.tixian').hide();
		});
		//提交申请
		$('.box_submit').click(function(){
			var param = $("#withdraw-form").serialize();
			$.post(shop_ajax_url, param, function(data) {
				if (data.status == 1) {
				    alert('提现申请提交成功,预计2个工作日内申请通过');
					window.location.reload();
				} else {
				    alert(data.msg);
				}
			  }, 'json');
		});
	},
}