var distribute_join_obj={
	distribute_join_init:function(){
		$('.submit').click(function(){
			$.post(shop_ajax_url, $('#personal_form').serialize(), function(data){
				if(data.status == 1){
					location.href = data.url;
				}else{
					alert(data.msg);
				}
			}, 'json');
		});
			
	},
}