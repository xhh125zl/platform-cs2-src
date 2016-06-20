var prints_obj={
	config_init:function(){
		$('#config_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#config_form input:submit').attr('disabled', true);
			return true;
		});
	}
}