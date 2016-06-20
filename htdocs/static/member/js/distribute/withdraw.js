var withdraw_obj = {
	withdraw_init:function(){
		//弹出驳回用户申请框
		$("a.reject_btn").click(function(){
			$('#reject_withdraw form input[name=Bank_Card]').val('');
			$('#reject_withdraw form input[name=Record_ID]').val($(this).parent().parent().attr('Record_ID'));
			$('#reject_withdraw form').show();
			$('#reject_withdraw .tips').hide();
			$('#reject_withdraw').leanModal();
		});

		//提交驳回用户申请
		$('#reject_withdraw form').submit(function(){return false;});
		$('#reject_withdraw form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};

			$(this).attr('disabled', true);
			$.post('ajax.php', $('#reject_withdraw form').serialize(), function(data){
				$('#reject_withdraw form input:submit').attr('disabled', false);
				alert(data.msg);
				window.location.reload();
			}, 'json');
		});	

		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
	},
	
	method_edit:function(){
		$('select[name=Type]').change(function(){
			if($(this).attr('value')=='bank_card'){
				$('#type_0').show();
			}else{
				$('#type_0').hide();
			}
		});
		
		$('#method_form input:submit').click(function(){
			$(this).css('disabled', true);
			return true;
		});
	}
}