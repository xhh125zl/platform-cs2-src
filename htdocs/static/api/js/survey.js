var survey_obj={
	survey_init:function(){
		$('#survey .start').click(function(){
			$('#survey .index').slideUp(500);
			$('#survey .vote .contents .questions').eq(0).show();
			$('#survey .vote').slideDown(500);
		});
		
		$('#survey .vote .contents .questions li .answer').click(function(){
			$(this).parent().parent().parent().find('.answer').removeClass('a_bg_1').addClass('a_bg_0');
			//$('#survey .vote .contents .questions li .answer').removeClass('a_bg_1').addClass('a_bg_0');
			$(this).addClass('a_bg_1');
		});
		
		$('#survey .pre').click(function(){
			var _no=$(this).parent().parent().index();
			$('#survey .vote .v_con .title span').eq(0).html(_no);
			//if(_no!=0){
				$('#survey .vote .contents .questions').eq(_no).slideUp(500);
				$('#survey .vote .contents .questions').eq(_no-1).slideDown(500);
			//}else{
				//$('#survey .index').slideDown(500);
				//$('#survey .vote').slideUp(500);
			//}
		});
		
		$('#survey .next').click(function(){
			var _no=$(this).parent().parent().index();
			var LId=$('#survey .vote .contents .questions').eq(_no).attr('LId');
			var name='q'+LId;
			var v=$('input[name='+name+']:checked').val();
			if(!v){
				global_obj.win_alert('请选择一个选项！');
				return false;
			}
			
			$('#survey .vote .v_con .title span').eq(0).html(_no+2);
			$('#survey .vote .contents .questions').eq(_no).slideUp(500);
			$('#survey .vote .contents .questions').eq(_no+1).slideDown(500);
		});
		
		$('#survey .submit').click(function(){
			var _no=$(this).parent().parent().index();
			var LId=$('#survey .vote .contents .questions').eq(_no).attr('LId');
			var name='q'+LId;
			var v=$('input[name='+name+']:checked').val();
			if(!v){
				global_obj.win_alert('请选择一个选项！');
				return false;
			}
			
			$(this).attr('disabled', true);
			$.post('?', $('form').serialize(), function(data){
				if(data.status==1){
					window.location=Url;
				}else{
					global_obj.win_alert(data.msg, function(){window.location.reload()});
				}
			}, 'json');
		});
	},
	
	survey_result:function(){
		$('#survey .tips input').click(function(){
			$('#survey .tips').slideUp(200);
			$('#survey .result').slideDown(500);
		});
	}
}