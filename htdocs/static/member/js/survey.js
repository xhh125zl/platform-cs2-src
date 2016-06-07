var survey_obj={
	survey_edit_init:function(){
		var date_str=new Date();
		$('#survey_form input[name=Time]').click(function(){
			var date_str=new Date();
			$('#survey_form input[name=Time]').daterangepicker({
				timePicker:true,
				minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
				format:'YYYY/MM/DD HH:mm:00'}
			)
		});
		
		$('#survey_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			return true;
		});
	},
	
	survey_set_init:function(){
		
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
				.end().filter('[name=QuestionID]').val(jsonData.LId);
				if(jsonData.ImgPath!=''){
					$('#ImgPathDetailMod').html("<a href='"+jsonData.ImgPath+"' target='_blank'><img src='"+jsonData.ImgPath+"' /></a>");
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
				$.post('?', "action=delquestion&QuestionID="+jsonData.LId, function(data){
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
			$('#survey_answer_add_form input').filter('[name=QuestionID]').val(jsonData.LId);
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
				$('#survey_answer_mod_form #SurveyListTitleMod').html(jsonData.QTitle);
				$('#survey_answer_mod_form input').filter('[name=Answer]').val(jsonData.Title)
				.end().filter('[name=ImgPath]').val(jsonData.ImgPath)
				.end().filter('[name=AnswerID]').val(jsonData.AId);
				if(jsonData.ImgPath!=''){
					$('#AnswerModImgPathDetail').html("<a href='"+jsonData.ImgPath+"' target='_blank'><img src='"+jsonData.ImgPath+"' /></a>");
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
				$.post('?', "action=delanswer&AnswerID="+jsonData.AId, function(data){
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