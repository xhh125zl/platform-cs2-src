(function(){
	$.fn.orderDrag = function(options){
		dft		=	{
			obj		: this,					//当前拖动对象
			disX	: 0,					//获取鼠标第一次点击对象的x坐标
			disY	: 0,					//获取鼠标第一次点击对象的y坐标
			package	: ""					//组件名称
		}
		var ops    = $.extend(dft,options);
		var ele    = $(ops.obj);
		zIndex     = 9999; 
		$(ops.obj).mousedown(function(event){
			var objIndex   = $(".dragPart").index(this); //拖动div的索引
			var oNearIndex = objIndex; //保持在原索引不会移动
			var event = event || window.event;
			ops.disX =  5;
			ops.disY =  event.clientY - $(ops.obj).parent("."+ops.package).position().top - $(".ipad").scrollTop(); //父级div的高度
			++zIndex;
			var oTemp = $(ops.obj).clone(true);
			var $l    = 5
			var $t    = $(ops.obj).parent("."+ops.package).position().top + $(".ipad").scrollTop();			
			$(oTemp).attr("id","temp");
			$(oTemp).css({"left":$l,"top":$t,"zIndex":zIndex,"position":"absolute","opacity":0.5});	
			$(".ipad").append(oTemp);
			$(ops.obj).css("visibility","hidden");
			//mouseDown选中元素
			shop_obj.selectElement(ops.obj,ops.package);
			
			$(document).mousemove(function(event){				
				var event 	= event || window.event;
				var iT		= event.clientY - ops.disY;
				$(oTemp).css({"top":iT});
				oNearIndex = findNearestObj(oTemp); //离拖动div最近的元素索引
				$(".ipad").children("div").eq(oNearIndex).css("background","#ccc").siblings("div").css("background","#fff");
				$(".ipad .p5").css({"background":"#EAEAEA"});
				return false;
			});			
			
			$(document).mouseup(function(){
				$(document).unbind("mousemove");
				$(document).unbind("mouseup");
				$(ops.obj).css("visibility","visible");
				$(".ipad").children("div").css("background","#fff");
				$(".ipad .p5").css({"background":$(".p5").attr("background0")});
				if(objIndex > oNearIndex){ //使用 before 或 after 元素插入 索引相同则跳过不操作
					$(".ipad").children("div").eq(oNearIndex).before($(".ipad").children("div").eq(objIndex));
				} else if(objIndex < oNearIndex) {
					$(".ipad").children("div").eq(oNearIndex).after($(".ipad").children("div").eq(objIndex));
				}
				$(oTemp).remove();
				oNear = null;
				ele[0].releaseCapture && ele[0].releaseCapture();
			});
			ele[0].setCapture && ele[0].setCapture();		
			return false;
			
			//--------- 静态方法 start ---------//
			function findNearestObj(oTemp){
				var arr1    = [];
				var arr2    = [];
				var nearObj = "";
				
				$(".ipad").children("div").each(function(index, element) {
					var filterNum = $(oTemp).position().top - $(element).position().top; 
					arr2.push(filterNum); //拖动元素 与 ipad div高度比较
				});
				
				arr2.pop();
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