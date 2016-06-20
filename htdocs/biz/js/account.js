var account_obj={
	login_init:function(){
		$('#login').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#login input:submit').attr('disabled', true);
			return true;
		});
	}
}