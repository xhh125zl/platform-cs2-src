var distribute_qrcodehb_obj={
	distribute_qrcodehb_init:function(){
		$('.qrcode').hover(function(){
		    $('.mask').css({opacity:'.2',display:'block'});
			$(this).css('opacity','.5');
			$('.guide').animate({ 
				marginLeft: '153px', 
				opacity: 1
			}, 800 );        
		},function(){
			$('.mask').css({opacity:'0',display:'none'});
			$(this).css('opacity','1');
			$('.guide').animate({ 
				marginLeft: '-120px', 
				opacity: 0
			}, 500 );  
		});
	},
}