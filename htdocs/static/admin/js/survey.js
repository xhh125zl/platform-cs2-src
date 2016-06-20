var survey_obj={
	survey_edit_init:function(){
		global_obj.file_upload($('#ImgUpload'), $('#survey_form input[name=ImgPath]'), $('#ImgDetail'));
		$('#ImgDetail').html(global_obj.img_link($('#survey_form input[name=ImgPath]').val()));
		
		//var date_str=new Date();
		$('#survey_form input[name=Time]').daterangepicker({
			timePicker:true,
			//minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			format:'YYYY/MM/DD HH:mm:00'
		})
		
		$('#survey_form').submit(function(){return false;});
		$('#survey_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#survey_form').serialize()+'&Description='+encodeURIComponent(CKEDITOR.instances.survey_Description.getData()), function(data){
				if(data.ret==1){
					window.location='?m=survey&a=survey';
				}else{
					$('#survey_form input:submit').attr('disabled', false);
					alert(data.msg);
				};
			}, 'json');
		});
	},
	
	survey_set_init:function(){
		global_obj.file_upload($('#ImgPathFileUpload'), $('#survey_add_form input[name=ImgPath]'), $('#ImgPathDetail'));
		global_obj.file_upload($('#ImgPathFileUploadMod'), $('#survey_mod_form input[name=ImgPath]'), $('#ImgPathDetailMod'));
		global_obj.file_upload($('#AnswerImgPathFileUpload'), $('#survey_answer_add_form input[name=ImgPath]'), $('#AnswerImgPathDetail'));
		global_obj.file_upload($('#AnswerModImgPathFileUpload'), $('#survey_answer_mod_form input[name=ImgPath]'), $('#AnswerModImgPathDetail'));
		
		//鼠标移上答案效果
		$('#survey_list .topic .answer .list').hover(function(){
			$(this).children('.opt').show();
		}, function(){
			$(this).children('.opt').hide();
		});
		
		//新增问题
		$('#survey_list a[href=#add_topic]').click(function(){
			$('#survey_list_add').leanModal();
		});
		
		$('#survey_add_form').submit(function(){return false;});
		$('#survey_add_form input:submit').click(function(){
			if(global_obj.check_form($('#survey_add_form *[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#survey_add_form').serialize(), function(data){
				if(data.status==1){
					window.location.reload();
				}else{
					$('#survey_add_form .submit').attr('disabled', false);
					alert('出现未知错误！');
				};
			}, 'json');
		});
		
		//修改问题
		$('#survey_list a[href=#survey_mod_list]').each(function(){
			$(this).click(function(){
				$('#survey_list_mod').leanModal();
				
				var jsonData=eval('('+$(this).parent().attr('Data')+')');
				$('#survey_mod_form input').filter('[name=Title]').val(jsonData.Title)
				.end().filter('[name=ImgPath]').val(jsonData.ImgPath)
				.end().filter('[name=SId]').val(jsonData.SId)
				.end().filter('[name=LId]').val(jsonData.LId);
				if(jsonData.ImgPath!=''){
					$('#ImgPathDetailMod').html("<a href='../../member/js/"+jsonData.ImgPath+"' target='_blank'><img src='../../member/js/"+jsonData.ImgPath+"' /></a>");
				}else{
					$('#ImgPathDetailMod').html("");
				}
			});
		});
		
		$('#survey_mod_form').submit(function(){return false;});
		$('#survey_mod_form input:submit').click(function(){
			if(global_obj.check_form($('#survey_mod_form *[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#survey_mod_form').serialize(), function(data){
				if(data.status==1){
					window.location.reload();
				}else{
					$('#survey_mod_form .submit').attr('disabled', false);
					alert('出现未知错误！');
				};
			}, 'json');
		});
		
		//删除问题
		$('#survey_list a[href=#survey_del_list]').each(function(){
			$(this).click(function(){
				if(!confirm('删除后不可恢复，继续吗？')){
					return false;
				}
				var jsonData=eval('('+$(this).parent().attr('Data')+')');
				$.post('?', "ajax=1&do_action=survey.survey_list_del&SId="+jsonData.SId+"&LId="+jsonData.LId, function(data){
					if(data.status==1){
						window.location.reload();
					}else{
						alert('出现未知错误！');
					};
				}, 'json');
			});
		});
		
		//新增问题答案
		$('#survey_list a[href=#survey_add_answer]').click(function(){
			$('#survey_answer_add').leanModal();
			
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
			$('#survey_answer_add_form #SurveyListTitle').html(jsonData.Title);
			$('#survey_answer_add_form input').filter('[name=SId]').val(jsonData.SId)
			.end().filter('[name=LId]').val(jsonData.LId);
		});
		
		$('#survey_answer_add_form').submit(function(){return false;});
		$('#survey_answer_add_form input:submit').click(function(){
			if(global_obj.check_form($('#survey_answer_add_form *[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#survey_answer_add_form').serialize(), function(data){
				$('#survey_answer_add_form input:submit').attr('disabled', false);
				if(data.status==1){
					window.location.reload();
				}else{
					alert('添加答案失败，出现未知错误！');
				};
			}, 'json');
		});
		
		//修改问题答案
		$('#survey_list a[href=#survey_mod_answer]').each(function(){
			$(this).click(function(){
				$('#survey_answer_mod').leanModal();
				
				var jsonData=eval('('+$(this).parent().parent().attr('DataAnswer')+')');
				$('#survey_answer_mod_form #SurveyListTitleMod').html(jsonData.Title);
				$('#survey_answer_mod_form input').filter('[name=Answer]').val(jsonData.Answer)
				.end().filter('[name=ImgPath]').val(jsonData.ImgPath)
				.end().filter('[name=SId]').val(jsonData.SId)
				.end().filter('[name=LId]').val(jsonData.LId)
				.end().filter('[name=AId]').val(jsonData.AId);
				if(jsonData.ImgPath!=''){
					$('#AnswerModImgPathDetail').html("<a href='../../member/js/"+jsonData.ImgPath+"' target='_blank'><img src='../../member/js/"+jsonData.ImgPath+"' /></a>");
				}else{
					$('#AnswerModImgPathDetail').html("");
				}
			});
		});
		
		$('#survey_answer_mod_form').submit(function(){return false;});
		$('#survey_answer_mod_form input:submit').click(function(){
			if(global_obj.check_form($('#survey_answer_mod_form *[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#survey_answer_mod_form').serialize(), function(data){
				if(data.status==1){
					window.location.reload();
				}else{
					$('#survey_answer_mod_form .submit').attr('disabled', false);
					alert('出现未知错误！');
				};
			}, 'json');
		});
		
		//删除问题答案
		$('#survey_list a[href=#survey_del_answer]').each(function(){
			$(this).click(function(){
				if(!confirm('删除后不可恢复，继续吗？')){
					return false;
				}
				var jsonData=eval('('+$(this).parent().parent().attr('DataAnswer')+')');
				$.post('?', "ajax=1&do_action=survey.survey_answer_del&SId="+jsonData.SId+"&LId="+jsonData.LId+"&AId="+jsonData.AId, function(data){
					if(data.status==1){
						window.location.reload();
					}else{
						alert('出现未知错误！');
					};
				}, 'json');
			});
		});
	}
}