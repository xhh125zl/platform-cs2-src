var scene_obj={
	w:$(window).width(),
	h:$(window).height(),
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
	},
	
	scroll_init:function(){
		$('#photo-list').height(this.h);
		$('#scroll-item').height(this.h-30);
		scene_obj.sideClickEvt(); 
	},
	sideClickEvt:function(){
		$('#div1 .icon1 a').click(function(){
			$('#nav-list').css({width:'0'});
			$('#photo-list').animate({width:'70%'},function(){
				var photo_myscroll=new iScroll("scroll-item",{hideScrollbar:true,fadeScrollbar:true});
			});
		});
		$('#photo-list .photo-title span').eq(0).click(function(){
			$('#photo-list').animate({width:'0'});
		});
	}
}