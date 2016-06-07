


//banner图切换代码begin
$(function() {
	var bannerSlider = new Slider($('#banner_tabs'), {
		time: 5000,
		delay: 400,
		event: 'hover',
		auto: true,
		mode: 'fade',
		controller: $('#bannerCtrl'),
		activeControllerCls: 'active'
	});
	$('#banner_tabs .flex-prev').click(function() {
		bannerSlider.prev()
	});
	$('#banner_tabs .flex-next').click(function() {
		bannerSlider.next()
	});
})
//banner图切换代码end




jQuery.fn.extend({luara:function(a){function s(){var a;switch(j){case"top":a=h;break;case"left":a=h*g;break;default:a=h}return a}function t(){var a=b.find("img").eq(0),c={};return c.width=a.width(),c.height=a.height(),c}function u(b){var b=b||a.speed||l/6;return b>l?b=l:l>b&&0>b&&(b=arguments.callee(-b)),b}function v(){q=setTimeout(function(){o++,e.eq(o-1).removeClass(n),o==g&&(o=0),r(),e.eq(o).addClass(n),v()},l)}var q,r,b=$(this).eq(0),c=$(this).find("ul").eq(0),d=c.find("li"),e=$(this).find("ol").eq(0).find("li"),f=b.find("img"),g=f.length,a=a||{},h=a.width||t().width,i=a.height||t().height,j=a.deriction||"",k="luara-"+j,l=(a.interval>0?a.interval:-a.interval)||3e3,m=u(),n=a.selected,o=0;b.width(h).height(i).addClass(k),c.width(s(j)).height(i),d.width(h).height(i),e.eq(0).addClass(n),function(){s=null,t=null,u=null}(),r=function(){switch(j){case"top":return function(){c.animate({top:-i*o+"px"},m)};case"left":return function(){c.animate({left:-h*o+"px"},m)};default:return function(){d.hide().eq(o).fadeIn(m)}}}(),e.mouseover(function(){e.eq(o).removeClass(n),o=e.index($(this)),$(this).addClass(n),r()}),b.mouseenter(function(){clearTimeout(q)}).mouseleave(function(){v()}),v()}});


//快速导航begin

$(function(){
		// @ 给窗口加滚动条事件
		var i=0;
		$(window).scroll(function(){
			// 获得窗口滚动上去的距离
			var ling = $(document).scrollTop();
			
			// 在标题栏显示滚动的距离
			//document.title = ling;
			// 如果滚动距离大于1534的时候让滚动框出来
			if(ling>582){
				$('#box').show();
			}
			if(582<ling && ling<1260){
				// 让第一层的数字隐藏，文字显示，让其他兄弟元素的li数字显示，文字隐藏
				$('#box ul li').eq(0).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(0).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
			}else if(ling<1764){
				$('#box ul li').eq(1).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(1).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i = 1;
			}else if(ling<2394){
				$('#box ul li').eq(2).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(2).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i = 2;
			}else if(ling<4170){
				$('#box ul li').eq(3).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(3).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i=3;
			}else if(ling<4885){
				$('#box ul li').eq(4).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(4).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i=4;
			}else if(ling<5460){
				$('#box ul li').eq(5).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(5).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i=5;
			}else if(ling<6035){
				$('#box ul li').eq(6).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(6).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i=6;
			}else if(ling<6645){
				$('#box ul li').eq(7).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(7).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i=7;
			}else if(ling<7360){
				$('#box ul li').eq(8).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(8).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i=8;
			}else if(ling<7905){
				$('#box ul li').eq(9).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(9).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i=9;
			}else if(ling<8790){
				$('#box ul li').eq(10).find('.num').hide().siblings('.word').css('display','block');
				$('#box ul li').eq(10).siblings('li').find('.num').css('display','block').siblings('.word').css('display','none');
				i=10;
			}
			if(ling>8800 || ling<582){
				// $('#box').css('display','none');  // @ 这一句和下一句效果一样。
				$('#box').hide();
			}
			
		});
		
		$('#box ul li').mouseover(function() {
			$(this).find("a").hide();
			$(this).find(".word").show();
        });
		$('#box ul li').mouseout(function() {
			var j=$(this).attr("ret");
			if(j == i){
				return;
			}else{
				$(this).find("a").hide();
				$(this).find(".num").show();
			}
        });

	})
//kuai



















