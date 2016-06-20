var games_obj={
	result_init:function(){
		$('#result .link .stat').click(function(){
			system_obj.div_mask();
			$('#submit_name').show();
			return false;
		});
		
		$('#result .link .share').click(function(){
			$('.share_layer').css('height', $(document).height()).show();
			return false;
		});
		$('.share_layer').click(function(){
			$(this).hide();
		});
		if($('input[name=ShareTitle]').size()){$('title').html($('input[name=ShareTitle]').val());}
		global_obj.share_init({
			'img_url':'http://'+document.domain+$('.shareimg').eq(0).attr('src'),
			'img_width':100,
			'img_height':100,
			'link':url,
			'desc':$('input[name=ShareTitle]').size()?$('input[name=ShareTitle]').val():$('title').html(),
			'title':$('input[name=ShareTitle]').size()?$('input[name=ShareTitle]').val():$('title').html()
		});
		
		$('#submit_form').submit(function(){return false});
		$('#submit_form .sub_button').click(function(){
			if(system_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('./', $('#submit_form').serialize(), function(data){
				if(data.ret==1){
					window.location.reload();
				};
			}, 'json');
		});
		$('#submit_name .cancel').click(function(){
			system_obj.div_mask(1);
			$('#submit_name').hide();
		});
		if($('#result .link li').size()%2==1){
			$('#result .link li:last').remove();
		}
	},
	
	detail_init:function(GId){
		$('#detail .start').click(function(){
			window.location=$('#detail .start').attr('rel');
		});
	}
}