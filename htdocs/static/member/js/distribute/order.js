var order_obj={
	orders_send:function(){
		$("input[name=refuse]").click(function(){
			var type = $(this).attr('value');
			if(type == 1){
			$("#refuseshow").hide();
			$('#order_send_form input:submit').val('通过审核');
			}else{
			$("#refuseshow").show();
			$('#order_send_form input:submit').val('提交');
			}
		});
		
		$('#order_send_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#order_send_form input:submit').attr('disabled', true);
			return true;
		});
	},
	orders_init:function(){		
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
	},
}