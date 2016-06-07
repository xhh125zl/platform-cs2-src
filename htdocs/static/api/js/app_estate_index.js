
var index_obj={
	index_init:function(){
		
		var resize=function(){
			$('#web_skin_index').css({
				height:$(window).height(),
				overflow:'hidden'
			});
		};
		
		setInterval(resize, 50);
		$('#web_skin_index .banner *').not('img').height($(window).height());
		
		new Swiper('.swiper-container', {
			loop:true,
			autoplayDisableOnInteraction:false,
			autoplay:3000,
		});
		$(window).load(function(){
			$('#skin_index_control .toolbar-item').on('click', function(){
				if($(this).hasClass('left')){
					scroll_fun(1);
				}else if($(this).hasClass('right')){
					scroll_fun(0);
				}
			});
			
			var scroll_fun=function(flg){
				var box=$('#skin_index_control .item-box');
				var list=$('#skin_index_control .list-box');
				var flgScrolling=false;
	
				if(flgScrolling){return false;}
				var left=parseInt(box.css('left'));
	
				if(flg==0){
					if(left>=(-box.width()) && (box.width()+left)>list.width()){
						flgScrolling=true;
						box.animate({
							left:'-='+list.width()
						}, 500, function(){
							flgScrolling=false;
						});
					}
				}else{
					if(left!=0){
						flgScrolling=true;
						box.animate({
							left:'+='+list.width()
						}, 500, function(){
							flgScrolling=false;
						});
					}
				}
			}
		});
	}
}