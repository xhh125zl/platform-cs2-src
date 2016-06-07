var main_obj={
	page_init:function(){
		if(self.location!=top.location){
			top.location=self.location;
		}
		$('body, html').css('overflow', 'hidden');
		
		$('a').click(function(){
			this.blur();
			main_obj.page_scroll_init();
		});
		$('#main .menu a').click(function(){
			$('#main .menu a').removeClass('current');
			$(this).addClass('current');
		});
		
		$('#main').height($(window).height()-$('#header').height()-$('#footer').height());
		w=925;
		$('#main .iframe').width(w);
		main_obj.page_scroll_init();
	},
	
	page_scroll_init:function(){
		$('#main .menu').jScrollPane();
	}
}