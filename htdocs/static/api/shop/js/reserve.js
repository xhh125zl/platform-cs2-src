var reserve_obj={
	
	reserve_init:function(){
		var typeid=0;
		$("#reserve input[name=Is_Union]").click(function(){
			if(typeid!=$(this).attr("value")){
				typeid = $(this).attr("value");
				$('#trade_0').empty();
				$('#trade_1').empty();
				$.get('/api/'+UsersID+'/shop/sjrz/'+this.value+'/0/', '', function(data){
					if(data.status == 1){
						$.each(data.html,function(index,html){
							var option = $("<option>").text(html.Category_Name).val(html.Category_ID)
							$('#trade_0').append(option);
						});
					}
				},"json");
			}
		});
		
		$('#trade_0').change(function(){
			$.get('/api/'+UsersID+'/shop/sjrz/'+typeid+'/'+this.value+'/', '', function(data){
				$('#trade_1').empty();
				if(data.status == 1){
					$.each(data.html,function(index,html){
						var option = $("<option>").text(html.Category_Name).val(html.Category_ID)
						$('#trade_1').append(option);
                    });
				}
			},"json");
		});
		$('.submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true).val('提交中...');
			$.post('/api/'+UsersID+'/shop/sjrz/', $('form').serialize(), function(data){
				if(data.status==1){
					$('input, select, textarea').attr('disabled', true);
					$('.submit').val('提交成功');
					$('#reserve_success').show().animate({
						bottom:150,
						opacity:'0.7'
					}, 1500).animate({
						opacity:0
					}, 4000);
				}else{
					global_obj.win_alert(data.msg);
					$('.submit').attr('disabled', false).val('提 交');
				};
			}, 'json');
		});
	}
}