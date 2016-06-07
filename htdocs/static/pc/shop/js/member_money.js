var member_money_obj={
	member_money_init:function(){
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
							htmltmp += '<li><span class="time">'+v['time']+'</span><span class="writesome">'+v['Note']+'</span></li>';
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
						$('.brt_see ul').html(htmltmp);
					}else{
						$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('.brt_see ul').html('<p style="line-height:30px; text-align:center;color:#999;">暂无订单！</p>');
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
		/*充值弹窗*/
        $(document).on('click', '.my_balance a', function() {
			$('.chongzhi').show();	
        });
		$(document).on('click', '.chongzhi .Operator', function() {
			if($(this).hasClass('cattsel')) {
				$(this).removeClass('cattsel');
				$(this).find('input[name=Operator]').removeAttr('checked');
			}else {
				$(this).find('input[name=Operator]').attr('checked', 'checked');
				$(this).addClass('cattsel');
			}
			return false;
		});
		/*充值提交*/
		$(document).on('click', '.chongzhi .box_submit', function() {
            $(this).attr('disabled', true);
			$.post(shop_ajax_url, $('.chongzhi form').serialize(),
            function(data) {
                if (data.status == 1) {
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