/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

vote_obj={
	vote_init:function(){
		$('.vi4, .voteBtn, .vote_btn').click(function(){
			if(window.PG.subscribe!=1){
				global_obj.attention_layer(0,window.attention.keyword);
				return;
			}
			var VId=$(this).attr('VId');
			var LId=$(this).attr('LId');
			$.get('./', 'do_action=vote.vote&VId='+VId+'&LId='+LId, function(data){
				if(data.ret==1){
					global_obj.win_alert(data.msg, function(){
						window.location=window.PG.url_pre_module+'result/'+VId+'/'+LId+'/';
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