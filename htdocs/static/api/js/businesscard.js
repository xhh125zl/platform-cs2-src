var businesscard_obj={
	businesscard_init:function(){
		$('a[href=#share]').click(function(){
			$('#share').css('height', $(document).height()).show();
			return false;
		});
		$('#share').click(function(){
			$(this).hide();
		});

		global_obj.share_init({
			'img_url':'http://'+document.domain+$('#businesscard .logo img').attr('src'),
			'img_width':100,
			'img_height':100,
			'link':window.location.href,
			'desc':'点击查看详细',
			'title':$('title').html()
		});
	}
	
	dragBg:function(){
		var oDiv = document.getElementById('businesscard_skin_4');
		var oUl = document.getElementById('ul1');
		var aLi = oUl.getElementsByTagName('li');
		var w = $(window).width();
		var h = $(window).height();
		$(oDiv).css({"width":w,"height":h});
		$(oUl).find("li").width(w).height(h);
		oUl.style.width = aLi.length * w + 'px';
		document.ontouchmove = function(ev){ev.preventDefault();};
		var downX = 0;
		var downLeft = 0;
		var iNow = 0;
		var downTime = 0;
		oUl.ontouchstart = function(ev){
			var touchs = ev.changedTouches[0];
			var bBtn = true;
			downX = touchs.pageX;
			downLeft = this.offsetLeft;
			downTime = Date.now();
			oUl.ontouchmove = function(ev){
				var touchs = ev.changedTouches[0];
				if( this.offsetLeft >= 0 ){
					if(bBtn){
						bBtn = false;
						downX = touchs.pageX;
					}
					this.style.left = (touchs.pageX - downX)/3 + 'px';
				}
				else if( this.offsetLeft <= oDiv.offsetWidth - oUl.offsetWidth ){
					if(bBtn){
						bBtn = false;
						downX = touchs.pageX;
					}
					this.style.left = (touchs.pageX - downX)/3 + ( oDiv.offsetWidth - oUl.offsetWidth ) + 'px';
				}
				else{
					this.style.left = touchs.pageX - downX + downLeft + 'px';
				}
			};
			oUl.ontouchend = function(ev){
				var touchs = ev.changedTouches[0];
				if( touchs.pageX < downX ){   //←
					
					if(iNow != aLi.length-1){
						if( downX - touchs.pageX > aLi[0].offsetWidth/2 || (Date.now() - downTime < 600 && downX - touchs.pageX > 30 ) ){
							iNow++;
						}
					}				
					startMove( oUl , { left : - iNow * w } , 200 , 'easeOut');
				}
				else{    //→
					if(iNow != 0){
						if( touchs.pageX - downX > aLi[0].offsetWidth/2 || (Date.now() - downTime < 600 && touchs.pageX - downX > 30 ) ){
							iNow--;
						}
					}
					startMove( oUl , { left : - iNow * w } , 200 , 'easeOut');
				}	
				this.ontouchmove = null;
				this.ontouchend = null;
			};	
		};
		function startMove(obj,json,times,fx,fn,fnMove){
			var iCur = {};
			for(var attr in json){
				if(attr == 'opacity'){
					iCur[attr] = Math.round(getStyle(obj,attr)*100);
				}
				else{
					iCur[attr] = parseInt(getStyle(obj,attr));
				}
			}			
			var startTime = now();			
			clearInterval(obj.timer);
			obj.timer = setInterval(function(){				
				var changeTime = now();				
				var scale = 1 - Math.max(0,(startTime - changeTime + times)/times);				
				if(fnMove){
					fnMove(scale);
				}				
				for(var attr in json){			
					var value = Tween[fx](scale*times, iCur[attr] , json[attr] - iCur[attr] , times );
					if(attr == 'opacity'){
						obj.style.filter = 'alpha(opacity='+ value +')';
						obj.style.opacity = value/100;
					}
					else{
						obj.style[attr] = value + 'px';
					}
					if(scale==1){
						clearInterval(obj.timer);
						if(fn){
							fn.call(obj);
						}
					}
				}
			},13);
			function now(){
				return Date.now();
			}
		}
		function getStyle(obj,attr){
			return getComputedStyle(obj,false)[attr];
		}
		
		var Tween = {
			linear: function (t, b, c, d){
				return c*t/d + b;
			},
			easeIn: function(t, b, c, d){
				return c*(t/=d)*t + b;
			},
			easeOut: function(t, b, c, d){
				return -c *(t/=d)*(t-2) + b;
			},
			easeBoth: function(t, b, c, d){
				if ((t/=d/2) < 1) {
					return c/2*t*t + b;
				}
				return -c/2 * ((--t)*(t-2) - 1) + b;
			},
			easeInStrong: function(t, b, c, d){
				return c*(t/=d)*t*t*t + b;
			},
			easeOutStrong: function(t, b, c, d){
				return -c * ((t=t/d-1)*t*t*t - 1) + b;
			},
			easeBothStrong: function(t, b, c, d){
				if ((t/=d/2) < 1) {
					return c/2*t*t*t*t + b;
				}
				return -c/2 * ((t-=2)*t*t*t - 2) + b;
			}
		}
	}
}