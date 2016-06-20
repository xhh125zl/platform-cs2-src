var card_obj={
	card_init:function(){
		$('#sign_submit').click(function(){
			$(this).html('领取中...');
			$.post('ajax/', 'action=sign',function(data){
				if(data.status==1){
					$('#sign_submit').html('积分领取成功！');
					$('#Integral').html(data.integral);
					$('#sign_submit').off();
				}else{
					$('#sign_submit').html('积分领取失败，请重新领取');
				};
			}, 'json');
		});
		
		$('.cp>dl>dt').click(function(){
			$(this).parent().find('dd').hide();
			$(this).toggleClass("on");
			if($(this).hasClass('on')){
				$(this).next().show();
			}else{
				$(this).next().hide();
			}
		});
		$('.cp>h3').click(function(){
			$(this).parent().children('div').hide();
			$(this).toggleClass("on");
			if($(this).hasClass('on')){
				$(this).next().show();
			}else{
				$(this).next().hide();
			}
		});
		
		$('#card .article .btn input:button[name=GetIntegral], #card .article .btn input:button[name=UseIntegral]').click(function(){
			$('#card_form h2').html($(this).val());
			$('#card_integral_form input[name=RecordType]').val($(this).attr('v'));
			$('#card .article .btn').slideUp(200);
			$('#card_form').slideDown(500);
		});
		
		$('#card_integral_form input:button[class=back]').click(function(){
			$('#card_form').slideUp(500);
			$('#card .article .btn').slideDown(500);
		});
		
		$('#card_integral_form input:button[class=submit]').click(function(){
			$(this).attr('disabled', true);
			$.post('ajax/', $('#card_integral_form').serialize()+'&action=record', function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						$('form .submit').attr('disabled', false)
						window.location.reload()
					});
				}else{
					global_obj.win_alert(data.msg, function(){
						$('form .submit').attr('disabled', false)
					});
				};
			}, 'json');
		});	
	}
}