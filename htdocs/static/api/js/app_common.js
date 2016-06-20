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
			autoplay:50000000,
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
	},
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
	
	reserve_init:function(){
		$('#reserve input[name=ReserveDate]').datepicker({
			minDate:new Date(),
			dateFormat:'yy-mm-dd'
		}).val((
			function(d){
				return [d.getFullYear(), d.getMonth()+1, d.getDate()].join('-');
			}
		)(new Date()));
		
		$('.submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true).val('提交中...');
			$.post(linkurl, $('form').serialize(), function(data){
				if(data.status==1){
					$('input, select, textarea').attr('disabled', true);
					$('.submit').val('提交成功');
					$('#reserve_success').show().animate({
						bottom:150,
						opacity:'0.7'
					}, 1500).animate({
						opacity:0
					}, 4000);
				}else{
					global_obj.win_alert(data.msg);
					$('.submit').attr('disabled', false).val('提 交');
				};
			}, 'json');
		});
	}
}