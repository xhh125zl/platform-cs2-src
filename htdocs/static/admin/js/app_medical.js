/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var app_medical_obj={
	reserve_edit_init:function(){
		global_obj.map_init();
		app_medical_obj.reserve_form_init();
		
		$('#reserve_form').submit(function(){return false;});
		$('#reserve_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#reserve_form').serialize(), function(data){
				if(data.status==1){
					window.location='index.php';
				}else{
					alert(data.msg);
					$('#reserve_form input:submit').attr('disabled', false);
				}
			}, 'json');
		})
	},
	reserve_list_init:function(){
		$('#search_form input:button').click(function(){
			window.location='./?'+$('#search_form').serialize()+'&do_action=app_medical.reserve_export';
		});
		$('#search_form input[name=Time]').daterangepicker();	
	},
	reserve_form_init:function(){
		$('.reverve_field_table .input_add').click(function(){
			$('.reverve_field_table tr[FieldType=text]:hidden').eq(0).show();
			if(!$('.reverve_field_table tr[FieldType=text]:hidden').size()){
				$(this).hide();
			}
		});
		$('.reverve_field_table .input_del').click(function(){
			$('.reverve_field_table .input_add').show();
			$(this).parent().parent().hide().find('input').val('');
		});
		$('.reverve_field_table .select_add').click(function(){
			$('.reverve_field_table tr[FieldType=select]:hidden').eq(0).show();
			if(!$('.reverve_field_table tr[FieldType=select]:hidden').size()){
				$(this).hide();
			}
		});
		$('.reverve_field_table .select_del').click(function(){
			$('.reverve_field_table .select_add').show();
			$(this).parent().parent().hide().find('input').val('');
		});
	},
}