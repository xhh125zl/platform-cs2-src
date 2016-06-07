$(document).ready(function(){
	// 图片上下滚动
	var count = $("#imageMenu li").length - 4; /* 显示 6 个 li标签内容 */
	var interval = $("#imageMenu li:first").width();
	var curIndex = 0;
	
	$('.scrollbutton').click(function(){
		if( $(this).hasClass('disabled') ) return false;
		
		if ($(this).hasClass('smallImgUp')) --curIndex;
		else ++curIndex;
		
		$('.scrollbutton').removeClass('disabled');
		if (curIndex == 0) $('.smallImgUp').addClass('disabled');
		if (curIndex == count-1) $('.smallImgDown').addClass('disabled');
		
		$("#imageMenu ul").stop(false, true).animate({"marginLeft" : -curIndex*interval + "px"}, 600);
	});	
	// 解决 ie6 select框 问题
	$.fn.decorateIframe = function(options) {
        if ($.browser.msie && $.browser.version < 7) {
            var opts = $.extend({}, $.fn.decorateIframe.defaults, options);
            $(this).each(function() {
                var $myThis = $(this);
                //创建一个IFRAME
                var divIframe = $("<iframe />");
                divIframe.attr("id", opts.iframeId);
                divIframe.css("position", "absolute");
                divIframe.css("display", "none");
                divIframe.css("display", "block");
                divIframe.css("z-index", opts.iframeZIndex);
                divIframe.css("border");
                divIframe.css("top", "0");
                divIframe.css("left", "0");
                if (opts.width == 0) {
                    divIframe.css("width", $myThis.width() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                if (opts.height == 0) {
                    divIframe.css("height", $myThis.height() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                divIframe.css("filter", "mask(color=#fff)");
                $myThis.append(divIframe);
            });
        }
    }
    $.fn.decorateIframe.defaults = {
        iframeId: "decorateIframe1",
        iframeZIndex: -1,
        width: 0,
        height: 0
    }
    //放大镜视窗
    $("#bigView").decorateIframe();
    //点击到中图
    var midChangeHandler = null;
	
    $("#imageMenu li img").bind("click", function(){
		if ($(this).attr("id") != "onlickImg") {
			midChange($(this).attr("src"));
			$("#imageMenu li").removeAttr("id");
			$(this).parent().attr("id", "onlickImg");
		}
	})
    function midChange(src) {
        $("#midimg").attr("src", src).load(function() {
            changeViewImg();
        });
    }
    //大视窗看图
    function mouseover(e) {
        if ($("#winSelector").css("display") == "none") {
            $("#winSelector,#bigView").show();
        }
        $("#winSelector").css(fixedPosition(e));
        e.stopPropagation();
    }
    function mouseOut(e) {
        if ($("#winSelector").css("display") != "none") {
            $("#winSelector,#bigView").hide();
        }
        e.stopPropagation();
    }
    $("#midimg").mouseover(mouseover); //中图事件
    $("#midimg,#winSelector").mousemove(mouseover).mouseout(mouseOut); //选择器事件

    var $divWidth = $("#winSelector").width(); //选择器宽度
    var $divHeight = $("#winSelector").height(); //选择器高度
    var $imgWidth = $("#midimg").width(); //中图宽度
    var $imgHeight = $("#midimg").height(); //中图高度
    var $viewImgWidth = $viewImgHeight = $height = null; //IE加载后才能得到 大图宽度 大图高度 大图视窗高度

    function changeViewImg() {
        $("#bigView img").attr("src", $("#midimg").attr("src"));
    }
    changeViewImg();
    $("#bigView").scrollLeft(0).scrollTop(0);
    function fixedPosition(e) {
        if (e == null) {
            return;
        }
        var $imgLeft = $("#midimg").offset().left; //中图左边距
        var $imgTop = $("#midimg").offset().top; //中图上边距
        X = e.pageX - $imgLeft - $divWidth / 2; //selector顶点坐标 X
        Y = e.pageY - $imgTop - $divHeight / 2; //selector顶点坐标 Y
        X = X < 0 ? 0 : X;
        Y = Y < 0 ? 0 : Y;
        X = X + $divWidth > $imgWidth ? $imgWidth - $divWidth : X;
        Y = Y + $divHeight > $imgHeight ? $imgHeight - $divHeight : Y;

        if ($viewImgWidth == null) {
            $viewImgWidth = $("#bigView img").outerWidth();
            $viewImgHeight = $("#bigView img").height();
            if ($viewImgWidth < 200 || $viewImgHeight < 200) {
                $viewImgWidth = $viewImgHeight = 800;
            }
            $height = $divHeight * $viewImgHeight / $imgHeight;
            $("#bigView").width($divWidth * $viewImgWidth / $imgWidth);
            $("#bigView").height($height);
        }
        var scrollX = X * $viewImgWidth / $imgWidth;
        var scrollY = Y * $viewImgHeight / $imgHeight;
        $("#bigView img").css({ "left": scrollX * -1, "top": scrollY * -1 });
        $("#bigView").css({ "top": 245, "left": $(".preview").offset().left + $(".preview").width()+15  });

        return { left: X, top: Y };
    }
});

var goods_obj = {
	goods_init : function(){
		/*分销产品*/
        $("#share_product").click(function() {
            $.post(shop_ajax_url, {
                productid: Products_ID,
                action: 'distribute_product'
            },
            function(data) {
                if (data.status == 0) {
                    self.location.href = data.url
                } else if (data.status == 1) {
                    window.location.href = data.url;
                }
            }, 'json');
        });
		$('#add').click(function(e) {
			var amount = parseInt($('#amount').val());
			if(isNaN(amount) || amount == '')
				amount = 1
			amount = amount + 1;
			$('#amount').val(amount);
		});
		$('#minus').click(function(e) {
			var amount = parseInt($('#amount').val());
			if(isNaN(amount) || amount == '')
				amount = 1
			if(amount <= 1) {
				return;
				}else {
					amount = amount-1;
				}
			$('#amount').val(amount);
		});
		
		//详情、评价切换begin
		$('.control_right_ul li').click(function(e) {
			var i = $( this).index();
			var j = $('.control_right_all div').index();
            $( this).addClass('li_hover').siblings().removeClass('li_hover');
				$('.control_right_all div').eq(i).show().siblings().hide();
        });
		//商家同款
		$('.down').click(function(e) {
			$('.beibi_tui').stop().animate({top:'-476px'},function(){
				var first=$('.beibi_tui li').first();
				$('.beibi_tui ul').append(first);//先把最前面的li的移除最下面，再通过绝对定位
				$('.beibi_tui').css('top','0px');
				});
		});
		
		$('.up').click(function(e) {
			var penend = $('.beibi_tui li').last();
			$('.beibi_tui ul').prepend(penend);//把最后一个放到最前面
			$('.beibi_tui').css('top','-476px');
			$('.beibi_tui').stop().animate({top:'0px'});
		});
		
		$('.shoucang').click(function(e) {
			if($(this).hasClass('shoucang_hover')) {
				shoucang();
				$( this).removeClass('shoucang_hover');
				$('.sc_number').text(parseInt($('.sc_number').text())-1);
			}else {
				shoucang();
				$(this).addClass('shoucang_hover');
				$('.sc_number').text(parseInt($('.sc_number').text())+1);
			}
		});
		var shoucang = function(){
			$.post(shop_ajax_url, {action:'shoucang','productId':Products_ID}, function(data){
				if(data.status == 1) {
					//alert(data.info);
				}else {
					alert(data.info);
					window.location.href = data.url;
				}
			},'json');
		}
		//立即购买
		$('.b_submit').click(function(){
			if (is_virtual == 1) {
				add_to_cart('Virtual');    
            }else {
				add_to_cart('DirectBuy');
            }
		});
		//加入购物车
		$('.b_shopcar').click(function(){
			add_to_cart('CartList');
		});
		
		//加入购物车&&直接购买&&虚拟物品直接购买
        function add_to_cart(cart_key) {
            $.post(shop_ajax_url, {action:'add_to_cart',ProductsID:Products_ID,Qty:$('input[name=Qty]').val(),spec_list:$('input[name=spec_list]').val(),cart_key:cart_key},
            function(data) {
                if (data.status == 1) {
					if(cart_key == 'CartList'){
						//购物车特效
						$('.ci_shopcar b').show();
						$('.ci_shopcar b').html(data.qty).addClass('hize');
						$('.wzw-cart-popup #bold_num').html(data.qty);
						$('.wzw-cart-popup #bold_mly').html(data.total_price);
						$('.wzw-cart-popup').show();
					}else if(cart_key == 'Virtual') {
						window.location.href = shop_ajax_url.replace('ajax/index', 'buy/order_virtual');
					}else if(cart_key == 'DirectBuy') {
						window.location.href = shop_ajax_url.replace('ajax/index', 'buy/order_directbuy');
					}
                } else {
                    alert(data.msg);
                }
            },
            'json');
        }
	},
}