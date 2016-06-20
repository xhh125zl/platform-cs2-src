(function(){
	$.fn.dragObj = function(options){
		dft		=	{
			obj		: this,					//当前拖动对象
			disX	: 0,					//获取鼠标第一次点击对象的x坐标
			disY	: 0,					//获取鼠标第一次点击对象的y坐标
			w		: $(window).width(),	//可以拖动的范围的宽度
			h		: $(window).height(),	//可以拖动的范围的高度
			isClone : false, 				//拖动时是否克隆对象
			hitPoint: {						
				hitType	: false,			//是否有碰撞元素
				hitObj	: ""				//碰撞的元素
			},
			package	: ""					//组件名称
		}

		var ops    = $.extend(dft,options);
		var ele    = $(ops.obj);
		zIndex     = 9999; 
		$(ops.obj).mousedown(function(event){
			ops.package = $(ops.obj).attr("packageName");
			var event = event || window.event;
			ops.disX =  event.clientX - $(ops.obj).position().left;
			ops.disY =  event.clientY - $(ops.obj).position().top;
			++zIndex;
			if(ops.isClone){
				var oTemp = $(ops.obj).clone(true);
				var $l    = $(ops.obj).position().left;
				var $t    = $(ops.obj).position().top;

				$(oTemp).attr("id","temp");
				$(oTemp).css({"left":$l,"top":$t,"zIndex":zIndex,"position":"absolute"});	
				$("body").append(oTemp);
			} else {
				$(ops.obj).css("zIndex",zIndex);
			}			
			$(document).mousemove(function(event){				
				var event 	= event || window.event;
				var iL 		= event.clientX - ops.disX;
				var iT		= event.clientY - ops.disY;
		
				if(ops.isClone){
					$(oTemp).css({"left":iL,"top":iT});
				} else {
					$(ops.obj).css({"left":iL,"top":iT});
				}
				oNear = findNearest(ops.isClone?oTemp:ops.obj,ops.hitPoint.hitObj);
				if(oNear)
				{
					$(".ipad .space").remove();
					$(ops.hitPoint.hitObj).removeClass("isNon").addClass("isHit");
					//找出在ipad层里面与拖动的元素发生碰撞的元素索引
					oNearIndex = findIpadNearestObj(oTemp);
					$(".ipad").children("div").eq(oNearIndex).css("background","#ccc").siblings("div").css("background","#fff");
					$(".ipad .p5").css({"background":$(".p5").attr("background0")});
					//$(".ipad .p5").css({"background":"#EAEAEA"});
					$(".ipad").children("div").eq(oNearIndex).after("<div class='space'></div>");
				} else {
					$(ops.hitPoint.hitObj).removeClass("isHit").addClass("isNon");
				}
				return false;
			});			
			$(document).mouseup(function(){
				$(document).unbind("mousemove");
				$(document).unbind("mouseup");
				if(ops.isClone){
					$(oTemp).remove();
				}
				if(oNear){ //碰撞以后相关操作
					appendElement(oNearIndex);
					$(oNear).removeClass("isHit").addClass("isNon");
				}
				oNear = null;
				ele[0].releaseCapture && ele[0].releaseCapture();
			});
			ele[0].setCapture && ele[0].setCapture();		
			return false;
			
			//--------- 静态方法 start ---------//
			function appendElement(nearIndex){ //oNearIndex要插入拖动元素的索引
				$("#ipadNotice").remove();
				var iDivNum = $(".ipad").children("div").size();
				var arr = [];
				$package = shop_obj[ops.package]["packageElement"](ops.package);
				if(iDivNum){
					$(".ipad").children("div").eq(nearIndex).after($package);			
				} else {
					$(".ipad").append($package);
					$(".ipad").children("div").eq(0).css("border","1px #f00 dashed").addClass("selectObj").siblings().css("border","1px #ccc dashed").removeClass("selectObj"); //里面没有 元素 赋第一个元素被选中
					$(".ipad").children("div").eq(0).find(".dragPart").orderDrag({"package":ops.package});	
				}
				//获取当前编辑对象
				$len = $("."+ops.package).size();
				$(".ipad").children("div").eq(nearIndex+1).css("border","1px #f00 dashed").addClass("selectObj").siblings().css("border","1px #ccc dashed").removeClass("selectObj");
				$(".ipad").children("div").eq(nearIndex+1).find(".dragPart").orderDrag({"package":ops.package});
				shop_obj.showproElement(ops.package);
				pName = ops.package;
				$(".ipad").children("div").css("background","#fff");
				$(".ipad .p5").css({"background":"#EAEAEA"});
				$(".ipad .space").remove();
			}
			
			function getDistance(obj1, obj2){ //找出两点的距离				
				var a = ($(obj1).position().left + $(obj1).outerWidth()  / 2)  - ($(obj2).position().left + $(obj2).outerWidth()  / 2);
				var b = ($(obj1).position().top  + $(obj1).outerHeight() / 2)  - ($(obj2).position().top  + $(obj2).outerHeight() / 2);
				return Math.sqrt(a * a + b * b)
			}
			
			function findNearest(obj,hitObj){  //找出相遇点最近元素
				var filterLi  = null;
				var aDistance = null;	
				hitObj != obj && (isButt(obj, hitObj) && (aDistance=getDistance(obj,hitObj), filterLi=hitObj));//判断两个对象是否一样			
				var minNum = Number.MAX_VALUE;
				var minLi = null;
				aDistance < minNum && (minNum = aDistance, minLi = filterLi);	
				return minLi
			}
			
			function isButt(obj1, obj2){
				var l1 = $(obj1).position().left;
				var t1 = $(obj1).position().top;
				var r1 = $(obj1).position().left + $(obj1).outerWidth();
				var b1 = $(obj1).position().top  + $(obj1).outerHeight();
				var l2 = $(obj2).position().left;
				var t2 = $(obj2).position().top;
				var r2 = $(obj2).position().left + $(obj2).outerWidth();
				var b2 = $(obj2).position().top  + $(obj2).outerHeight();

				return !(r1 < l2 || b1 < t2 || r2 < l1 || b2 < t1)
			}
			
			function findIpadNearestObj(oTemp){
				var arr1    = [];
				var arr2    = [];
				var nearObj = "";
				var oTempTop = $(oTemp).position().top - $(".ipad").position().top - 10;
				
				$(".ipad").children("div").each(function(index, element) {
					if($(".ipad").scrollTop() > 0)
					{
						var filterNum = oTempTop - (parseInt($(element).position().top) + $(".ipad").scrollTop()) + $(".ipad").scrollTop(); 
					} else {
						var filterNum = oTempTop - parseInt($(element).position().top) ; 
					}
					
					
					arr2.push(filterNum); //拖动元素 与 ipad div高度比较
				});
				nearObjIndex = findMin(arr2); //寻找最近距离的元素索引
				return nearObjIndex;
			}
			
			function findMin(arr){
				var $key  = 0;
				var $temp = Math.abs(arr[0]);
				for(i=0;i<arr.length;i++){
					if(Math.abs(arr[i]) < $temp){
						$key = i;
						$temp= Math.abs(arr[i]);
					}
				}
				return $key //返回最短距离的div索引
			}
			//--------- 静态方法 end ---------//
		}); 
	}
})(jQuery);