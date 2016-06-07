/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var main_obj={
	page_init:function(){
		if(self.location!=top.location){
			top.location=self.location;
		}
		$('body, html').css('overflow', 'hidden');
		
		$('a').click(function(){
			this.blur();
		});
		$('#header a').click(function(){
			$('#main .menu dt').removeClass('cur');
			$('#main .menu dd').hide();
			$('#main .menu div').removeClass('cur');
			main_obj.page_scroll_init();
		});
		$('#main .menu a').click(function(){
			$('#main .menu div').removeClass('cur');
			$(this).parent().addClass('cur');
		});
		
		$('#main').height($(window).height()-$('#header').height()-$('#footer').height());
		var w=$(window).width()-242;
		w=w<758?758:w;
		$('#main .iframe').width(w);
		main_obj.page_scroll_init();
		
		$('#main .menu dt').off().click(function(){
			$('#main .menu dt').removeClass('cur');
			$('#main .menu div').removeClass('cur');
			$('#main .menu dd').not($(this).next().filter('dd')).hide();
			var url=$(this).next().find('div:first').addClass('cur').find('a:first').attr('href');
			$(this).addClass('cur').next().filter('dd').slideDown(function(){
				main_obj.page_scroll_init();
				$('iframe').attr('src', url);
			});
		});
	},
	
	page_scroll_init:function(){
		$('#main .menu').jScrollPane();
	}
}