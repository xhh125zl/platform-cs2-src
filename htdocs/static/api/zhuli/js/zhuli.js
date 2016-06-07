
var zhuli_obj = {
    zhuli_init: function() {
		$('.stores div').hide();
		$('.stores #rank_list_0').show();
		var s_h = $(window).width()*175/640;
		var r_h = $(window).width()*402/640;
		$('.my_score').animate({top:s_h}, 500);
		$('.my_rank').animate({top:r_h}, 500);
		$('.btns p').click(function(){
			var id=$(this).attr('rel');
			if(id==3){
				var data = {action:"join"};			
				$.post('/api/zhuli/ajax.php?UsersID='+UsersID,data,function(data){			
					if(data.status == 1){
						global_obj.win_alert(data.msg, function(){
							window.location=data.url;
						});
					}else{
						global_obj.win_alert(data.msg);
					}
				},"json");
			}else{
				$('.btns p').removeClass();
				$(this).addClass('cur');
				$('.stores div').hide();
				$('.stores #rank_list_'+id).show();
			}
		})
		
		/*参加助力*/
		$("#join_zhuli").click(function(){
			
			var data = {action:"join"};
			
			$.post('/api/zhuli/ajax.php?UsersID='+UsersID,data,function(data){			
				if(data.status == 1){
					global_obj.win_alert(data.msg, function(){
						window.location=data.url;
					});
				}else{
					global_obj.win_alert(data.msg);
				}				
			},"json");	
		});
		/*帮助朋友*/
		$("#help_zhuli").click(function(){
			
			var data = {action:"do_zhuli",actid:ActID};
			
			$.post('/api/zhuli/ajax.php?UsersID='+UsersID,data,function(data){			
				global_obj.win_alert(data.msg, function(){
					location.reload();
				});		
			},"json");	
		});
		/*邀请帮助*/
		$('#share_zhuli').click(function(){
			$('.share_layer').css('height', $(document).height()).show();
			return false;
		});
		$('.share_layer').click(function(){
			$(this).hide();
		});
	}
}