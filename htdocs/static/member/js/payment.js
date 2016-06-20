var payment={
	orders_init:function(){
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
		
		$("#search_form .output_btn").click(function(){
			window.location='./output.php?'+$('#search_form').serialize()+'&type=sales_record_list';
		});
	},
	payment_edit_init:function(){
		var date_str=new Date();
		$('#payment_form input[name=Time]').daterangepicker({
			timePicker:true,
			format:'YYYY/MM/DD HH:mm:00'}
		)
		$('#payment_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#payment_form input:submit').attr('disabled', true);
			return true;
		});
	}
}