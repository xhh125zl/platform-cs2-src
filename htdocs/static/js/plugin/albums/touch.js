drag={iNow:0} //当前图片滚动的index
$(function(){
   setInterval(function(){
		$('#arrow').animate({bottom:20,opacity:0.3},function(){
			$('#arrow').animate({bottom:10,opacity:1});
		});	
	},1000);
	var oMc=$('#mc');		
	var isMusic=true;
	var playing=true;
	$(document).bind('touchstart',function(){
		if(oMc.length>0){
			if(isMusic){
				oMc.get(0).play();
				$('#music span').eq(1).fadeIn().html('关闭');
				isMusic=false;
			}
			setTimeout(function(){$('#music span').eq(1).fadeOut();},2500);
		}
	});
	$('#music span').eq(0).click(function(){
		if(playing){
			oMc.get(0).pause();
			$('#music span').eq(1).fadeIn().html('开启');
			playing=false;
			setTimeout(function(){$('#music span').eq(1).fadeOut();},2500);
		}else{
			oMc.get(0).play();
			$('#music span').eq(1).fadeIn().html('关闭');
			playing=true;
			setTimeout(function(){$('#music span').eq(1).fadeOut();},2500);
		}
	});
	//////////////////
    var oDiv = document.getElementById('div1');
	var oUl = document.getElementById('ul1');
	var aLi = oUl.getElementsByTagName('li');
	var w = $(window).width();
    var h = $(window).height();
    $(oDiv).css({"width":w,"height":h});
    $(oUl).find("li").width(w).height(h);
	oUl.style.height = aLi.length * h + 'px';
	document.ontouchmove = function(ev){ev.preventDefault();};
	var downY = 0;
	var downTop = 0;
	var downTime = 0;
	
	/////////////////////
	oUl.ontouchstart = function(ev){
		var touchs = ev.changedTouches[0];
        var bBtn = true;
		downY = touchs.pageY;
		downTop = this.offsetTop;
		downTime = Date.now();
		oUl.ontouchmove = function(ev){
			var touchs = ev.changedTouches[0];
            //----- 手指拖动相邻图片比例减少 start ------//
            if(touchs.pageY-downY < 0){ //下往上泼
                var s = Math.abs(touchs.pageY-downY); //手指拖动的距离
                s=s>$(window).height()?$(window).height():s;
                var percent = 1 - Math.abs(s*0.2/$(window).height());
				var move=(100-100*percent)*20;
                if(drag.iNow != $(oUl).find("li").size()-1){ //最后一个不需要缩放效果
                	$(oUl).find("li").eq(drag.iNow).find("img").css({'-webkit-transform':'translate(0px,'+move+'px) scale('+percent+')','-webkit-transform-origin':'bottom'});
				}
            }
			
            if(touchs.pageY-downY > 0){ //上往下泼
                var s = Math.abs(touchs.pageY-downY); //手指拖动的距离
                s=s>$(window).height()?$(window).height():s;
                var percent = 1 - Math.abs(s*0.1/$(window).height());
				var move=-(100-100*percent)*20;
                if(drag.iNow != 0){ //最后一个不需要缩放效果
                    $(oUl).find("li").eq(drag.iNow).find("img").css({'-webkit-transform':'translate(0px,'+move+'px) scale('+percent+')','-webkit-transform-origin':'top'});
                }
            }
            //----- 手指拖动相邻图片比例减少 end ------//
			
            if( this.offsetTop >= 0 ){
				if(bBtn){
					bBtn = false;
					downY = touchs.pageY;
				}
				this.style.top = (touchs.pageY - downY)/3 + 'px';
			}else if( this.offsetTop <= oDiv.offsetHeight - oUl.offsetHeight ){
				if(bBtn){
					bBtn = false;
					downY = touchs.pageY;
				}
				this.style.top = (touchs.pageY - downY)/3 + ( oDiv.offsetHeight - oUl.offsetHeight ) + 'px';
			}else{
				this.style.top = touchs.pageY - downY + downTop + 'px';
			}
		};
		oUl.ontouchend = function(ev){
			var touchs = ev.changedTouches[0];
			if( touchs.pageY < downY ){   //←
				if(drag.iNow != aLi.length-1){
					if( downY - touchs.pageY > aLi[0].offsetHeight/2 || (Date.now() - downTime < 1000 && downY - touchs.pageY > 30 ) ){
						drag.iNow++;
					}
				}				
				startMove( oUl , { top : - drag.iNow * h } , 200 , 'easeOut' ,function(){
                    $(oUl).find("li").find("img").css('-webkit-transform','scale(1)');
                });
			}
			else{    //→
				if(drag.iNow != 0){
					if( touchs.pageY - downY > aLi[0].offsetHeight/2 || (Date.now() - downTime < 1000 && touchs.pageY - downY > 30 ) ){
						drag.iNow--;
					}
				}
				startMove( oUl , { top : - drag.iNow * h } , 200 , 'easeOut' ,function(){
                    $(oUl).find("li").find("img").css('-webkit-transform','scale(1)');
                });
			}	
			this.ontouchmove = null;
			this.ontouchend = null;
		};
			
	};
});
