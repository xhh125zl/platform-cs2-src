var microbar_obj={	
	microbar_publication:function(){
		$('#publication_form').submit(function(e) {return false;});
		$('#publication_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true).val('提交中...');
			$.post('?', $('form').serialize(), function(data){
				if(data.status==1){
					window.location='../topic/'+data.mid+'/';
				}else{
					global_obj.win_alert('添加失败', function(){$('form input:submit').attr('disabled', false).val('发布话题');});
				};
			}, 'json');
		});
	},
	
	microbar_topic:function(){
		$('#topic .topic_box .btn input:button').click(function(){
			$('#topic .publication').slideDown(500);
			$('#topic textarea').focus();
		});
		$('#publication_form input:button').click(function(){
			$('#topic .publication').slideUp(500);
		});
		
		//回复主题
		$('#publication_form').submit(function(e) {return false;});
		$('#publication_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true).val('提交中...');
			$.post('?', $('form').serialize()+'&action=reply', function(data){
				if(data.status==1){
					window.location.reload();
				}else{
					global_obj.win_alert('回复失败！', function(){$('form input:submit').attr('disabled', false).val('回复话题');});
				};
			}, 'json');
		});
		
		//主题点赞
		$('#topic .topic_box .info a').click(function(){
			$.post('?', 'action=tclick&v='+$(this).attr('v'), function(data){
				if(data.status==1){
					window.location.reload();
				}else{
					global_obj.win_alert(data.msg);
					window.location.reload();
				}
			}, 'json');
		});
		
		//回复点赞
		$('#topic .topic_list li .comment img').click(function(){
			var r=$(this).parent().attr('r');
			$.post('?', 'action=rclick&ReplyID='+r+'&v='+$(this).attr('class'), function(data){
				if(data.status==1){
					window.location.reload();
				}else{
					global_obj.win_alert(data.msg);
					window.location.reload();
				}
			}, 'json');
		});
	}
}