var zhuli_obj={
	config_form_init:function(){
		var date_str=new Date();
		$('#config_form input[name=Time]').daterangepicker({
			timePicker:true,
			//minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
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
	
	prize_init:function(){
		for(i=0;i<5;i++){
			var PicContents=$('#ImgPath_'+i).val()?global_obj.img_link($('#ImgPath_'+i).val())+'<br /><a href="javascript:;" id="'+i+'">删除</a>':'';
			$('#ImgDetail_'+i).html(PicContents);
		}
		$('#prize .pic a').click(function(){
			$(this).parent().html('100*100');
			$('#ImgPath_'+$(this).attr('id')).val('');
		});
		
		$('.level_table .input_add').click(function(){
			$('.level_table tr[FieldType=text]:hidden').eq(0).show();
			if(!$('.level_table tr[FieldType=text]:hidden').size()){
				$(this).hide();
			}
		});
		$('.level_table .input_del').click(function(){
			$('.level_table .input_add').show();
			$(this).parent().parent().hide().find('input').val('').parent().parent().find('span.pic').html('100*100');
		});
		$('#prize_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#prize_form input:submit').attr('disabled', true);
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