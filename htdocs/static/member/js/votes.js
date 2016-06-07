var votes_obj={
	votes_init:function(){
		var date_str=new Date();
		$('#votes_form input[name=Time]').daterangepicker({
				timePicker:true,
				minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
				format:'YYYY/MM/DD HH:mm:00'}										  
		);
		$('input[name=BgColor]').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el){
				$(el).val("#"+hex);
				$(el).ColorPickerHide();
			}
		});
		
		$('input[name=Pattern]').click(function(){
			if($(this).val()!=0){
				$('#photo').hide();
			}else{
				$('#photo').show();
			}
		});
		
		$('#votes_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#votes_form input:submit').attr('disabled', true);
			return true;
		});
	}
}