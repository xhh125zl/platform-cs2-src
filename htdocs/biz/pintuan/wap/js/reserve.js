var reserve_obj={
	reserve_init:function(){
		$('#trade_0').change(function(){
			var data = {action:'trade',id:this.value};
			$.get('?', data, function(data){
				if(data.status == 1){
					$('#trade_1').empty();
					$.each(data.html,function(index,html){
						var option = $("<option>").text(html.name).val(html.id)
						$('#trade_1').append(option);
                    });
				}
			},"json");
		});
		$('.submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true).val('提交中...');
			$.post('?', $('form').serialize(), function(data){
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