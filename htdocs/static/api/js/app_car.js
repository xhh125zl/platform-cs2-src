/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var app_car_obj={
	app_car_header_swipe_init:function(){
		var hedaerBoxWidth = $("#headerbox").width();
		
		var navWidth = $(".nav-div").width();
		var iLong = 0;
		$(".nav-div a").each(function(index, element) {
			iLong += $(element).width();
		});
		if($("#hid").val() == 41){
			alert(hedaerBoxWidth +" "+navWidth);
		}
		if(hedaerBoxWidth <= navWidth){
			$(".nav-div").css({"width":iLong});
			var oDrag = $(".nav-div").get(0);
			oDrag.ontouchstart = function (event){
				var touch = event.targetTouches[0]; 
				disX = touch.pageX - this.offsetLeft;
				disY = touch.pageY - this.offsetTop;
				document.ontouchmove = function (event){
					var touch = event.targetTouches[0];
					var iL = touch.pageX - disX;
					var iT = touch.pageY - disY;
					oDrag.style.left = iL + "px";
					return false;
				};
				oDrag.ontouchend = function (){
					var l = parseInt($(".nav-div").css("left"));
					var last = iLong - hedaerBoxWidth;
					if(l > 0){
						$(".nav-div").animate({"left":0},30);
					} else if (l<-last){
						$(".nav-div").animate({"left":-last},30);
					}
					document.ontouchmove = null;
					document.ontouchend = null;
					oDrag.releaseCapture && oDrag.releaseCapture()
				};
			}
		}
	},
	
	app_car_banner_swipe_init:function(){
		new Swipe(document.getElementById('banner_box'), {
			speed:500,
			auto:3000,
			callback: function(){
				var lis = $(this.element).next("ol").children();
				lis.removeClass("on").eq(this.index).addClass("on");
			}
		});
	},
	
	app_car_footer_init:function(){
		$(function(){
			$("#mask").css({"width":$(window).width(),"height":$(window).height()*1.2});
			$(".plug-menu").click(function(){
				var span = $(this).find("span");		
				if(span.attr("class") == "open")
				{
					span.removeClass("open");
					span.addClass("close");
					$("#mask").hide();
					$(".footer_list").hide();
				} else {
					span.removeClass("close");
					span.addClass("open");
					$("#mask").show();
					$(".footer_list").show();
				}
			});
		});
	},
	
	app_car_tab:function(){
		$(function(){
			$(".ct_title").each(function(index, element) {
				$(element).click(function(){
					$(this).css({"color":"#3E3E3E","background":"#ccc"}).siblings(".ct_title").css({"color":"#fff","background":"#777"});
					$(".contact_content").eq(index).show().siblings(".contact_content").hide();
				});
			});
		});
	},
	
	reserve_init:function(){
		$(function(){
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
				$.post('/api/'+$('#UsersID').val()+'/app_car/ajax/'+$('#type').val()+'/', $('form').serialize(), function(data){
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
		});
	}
}