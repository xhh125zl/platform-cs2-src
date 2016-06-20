var hongbao_obj={
	config_form_init:function(){
		var date_str=new Date();
		$('#config_form input[name=Time]').daterangepicker({
			timePicker:true,
			format:'YYYY/MM/DD HH:mm:00'}
		)
		
		global_obj.file_upload($('#ImgUpload'), $('#config_form input[name=ImgPath]'), $('#ImgDetail'));
		$('#ImgDetail').html(global_obj.img_link($('#config_form input[name=ImgPath]').val()));
		
		$('#config_form').submit(function(){return false;});
		$('#config_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false;};
			
			$(this).attr('disabled', true);
			$.post('?', $('#config_form').serialize(), function(data){
				if(data.status==1){
					if(confirm(data.msg)){
						$('#config_form input:submit').attr('disabled', false);
					}else{
						$('#config_form input:submit').attr('disabled', false);
						window.location=data.url;
					}
				}else{
					alert(data.msg);
					$('#config_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	form_submit:function(){
		$('#form_submit').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#form_submit input:submit').attr('disabled', true);
			return true;
		});
	},
	
	message_init:function(){
		$('#user_message_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#user_message_form input:submit').attr('disabled', true);
			return true;
		});
	}
}