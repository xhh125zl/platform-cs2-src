(function($){
	$.fn.snow = function(options){
			$('body').css({
				width:'100%',
				overflow:'hidden'
			});
			
			var $flake 			= $('<div id="snowbox" />').css({'position': 'absolute', 'top': '-50px', 'z-index':10000}).html('&#10052;'),
				documentHeight 	= $(document).height(),
				documentWidth	= $(document).width(),
				defaults		= {
									minSize		: 10,		//雪花的最小尺寸
									maxSize		: 20,		//雪花的最大尺寸
									newOn		: 1000,		//雪花出现的频率
									flakeColor	: "#FFFFFF"
								},
				options			= $.extend({}, defaults, options);
			var interval		= setInterval( function(){
				var startPositionLeft 	= Math.random() * documentWidth - 100,
				 	startOpacity		= 0.5 + Math.random(),
					sizeFlake			= options.minSize + Math.random() * options.maxSize,
					endPositionTop		= Math.random() * $(document).height()-sizeFlake,
					endPositionLeft		= Math.random() * $(window).width()-sizeFlake,
					durationFall		= documentHeight * 5 + Math.random() * 5000;
				$flake.clone().appendTo('body').css({
							left: startPositionLeft,
							opacity: startOpacity,
							'font-size': sizeFlake,
							color: options.flakeColor
						}).animate({
							top: endPositionTop,
							left: endPositionLeft,
							opacity: 0.2
						},durationFall,'linear',function(){
							$(this).remove()
						}
					);
			}, options.newOn);
	};
})(jQuery);

$(function(){
	$.fn.snow({ 
		minSize: 5,		//雪花的最小尺寸
		maxSize: 35, 	//雪花的最大尺寸
		newOn: 300		//雪花出现的频率 这个数值越小雪花越多
	});
});