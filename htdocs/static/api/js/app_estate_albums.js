/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var albums_obj={
	masonry_init:function(){
		var masonry_fun=function(){
			$('#albums .list_type_0').masonry({itemSelector:'.item', columnWidth:$(window).width()/2});
			$('#photo_list a[rel] img').attr('src', $(this).parent().attr('rel'));
		};
		setInterval(masonry_fun, 50);
	},
	
	detail_init:function(){
		(function(window, $, PhotoSwipe){
			$('#photo_list a[rel]').photoSwipe({});
		}(window, window.jQuery, window.Code.PhotoSwipe));
	}
}