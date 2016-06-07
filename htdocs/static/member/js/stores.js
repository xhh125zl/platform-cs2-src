

var stores_obj={
	stores_init:function(){
		global_obj.map_init();
		global_obj.file_upload($('#ImgUpload'), $('form input[name=ImgPath]'), $('#ImgDetail'));
		$('#ImgDetail').html(global_obj.img_link($('form input[name=ImgPath]').val()));
		
		$('#stores_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#stores_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	user_init:function(){
		$('#stores_form').submit(function(){return false;});
		$('#stores_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false;};
			if($('input[name=Password]').val()!=$('input[name=ConfirmPassword]').val()){
				alert('登录密码与确认密码不匹配，请重新输入！');
				$('input[name=Password]').focus();
				return false;
			}
		
			$(this).attr('disabled', true);
			$.post('?', $('#stores_form').serialize(), function(data){
				$('#stores_form input:submit').attr('disabled', false);
				if(data.status==3){
					alert('对不起，用户名和密码的长度都必须为6位以上！');
				}else if(data.status==2){
					alert('对不起，此用户名已经被占用，请换一个用户名！');
				}else if(data.status==1){
					var StoresId=$('input[name=StoresId]').val();
					window.location='./?m=stores&a=stores&d=user_list&StoresId='+StoresId;
				}
			}, 'json');
		});
	}
}