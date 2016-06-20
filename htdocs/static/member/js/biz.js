var biz_obj={
	group_edit:function(){
		$('#group_edit').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#group_edit input:submit').attr('disabled', true);
			return true;
		});
	},
	
}