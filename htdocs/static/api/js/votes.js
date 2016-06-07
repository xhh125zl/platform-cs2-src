votes_obj={	
	votes_init:function(){		
		$('.vi4, .voteBtn, .vote_btn').click(function(){
			if(subcribe==0){
				global_obj.attention_layer(0,keyword);
				return;
			}
			
			var VId=$(this).attr('VId');
			var LId=$(this).attr('LId');
			
			$.get('/api/'+UsersID+'/votes/ajax/'+VId+'/'+LId+'/', '', function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						window.location='/api/'+UsersID+'/votes/result/'+VId+'/'+LId+'/';
					});
				}else{
					global_obj.win_alert(data.msg);
				}
			}, 'json');
		});
		$('.list .sprite .photo .photo_item').height($('.list .sprite .photo .photo_item img').width());
		var item_h=$('.list .sprite .photo .photo_item').height();
		$(window).load(function(){
			$('.list .sprite .photo .photo_item img').each(function(index, element) {
				if($(element).height() > $(element).width()){
					var percent=item_h/$(element).height();
					$(element).css({width:percent*$(element).width(),height:'100%'});
				}
			});
		});
	}
}