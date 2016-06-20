// JavaScript Document
window.onload = function(){
	var oUl = document.getElementsByTagName('ul')[0];
	var oLi = oUl.getElementsByTagName('li');
	var arr = [];
	var newArr = [];
	var iZindex = 2;
	var map = 5;
	var timer=null;
	var t=0;
	var w=$(window).width();
	
	$('#return').click(function(){
		clearTimeout(timer);
		timedCount();
		oUl.innerHTML = '';
		arr = [];
		newArr = [];
		iZindex = 2;
		t=0;
		$('#time').html('时间：00:00');
		addLi();
	});
	
	clearTimeout(timer);
	addLi();
	timedCount();	
	
	function addLi(){   // 创建li地图
		$('#warp').height($(window).height());
		document.addEventListener('touchmove',function(event){event.preventDefault()});
		$(oUl).width(w).height(w);
		$(oUl).css({'margin-left':-w/2});
		for(var i=0; i<map*map; i++){
			var oLi = document.createElement('li');
			oUl.appendChild(oLi);			
		}
		addLiattr();
	}
	function timedCount () {
		t++;
		var _min = parseInt(t/60);
		var secs = t%60;
		$('#time').html( "时间："+(_min>9?_min:'0'+_min) + ':' + (secs>9?secs:'0'+secs) );//mm:ss
		timer=setTimeout(timedCount,1000);
	};
		
	function addLiattr(){    //给Li添加属性
		var img_num = Math.floor(Math.random()*5);
		var img=$('#img_path_'+img_num).val();
		$('#step img').attr('src',img);
		for(var i=0; i<oLi.length; i++){
			oLi[i].style.width = oUl.offsetWidth/map + 'px';
			oLi[i].style.height = oUl.offsetHeight/map + 'px';	
			oLi[i].style.backgroundImage = 'url('+img+')';
			oLi[i].style.backgroundSize = map*100+'%';
			arr.push([oLi[i].offsetLeft,oLi[i].offsetTop]); //布局转化
			oLi[i].style.backgroundPosition = -arr[i][0] + 'px' +' -'+ arr[i][1] + 'px';
		}
		getPosition();
	}
	
	function getPosition(){   // 给Li加绝对定位
		for(var i=0; i<oLi.length; i++){
			oLi[i].style.left = arr[i][0] + 'px';
			oLi[i].style.top = arr[i][1] + 'px'
			oLi[i].style.position = 'absolute';
			oLi[i].style.margin = 0;
		}
		for(var i=0; i<oLi.length; i++){
			oLi[i].index = i;
			drag(oLi[i]);
		}
		init();	
	}
	
	function drag(obj){    // 拖拽
		var disX = 0;
		var disY = 0;
		obj.ontouchstart = function(ev){
			var ev = ev || window.event;
			var touchs = ev.changedTouches[0];
			disX = touchs.pageX - obj.offsetLeft;
			disY = touchs.pageY - obj.offsetTop;
			obj.style.zIndex = iZindex++;
			document.ontouchmove = function(ev){
				var ev = ev || window.event;
				var touchs = ev.changedTouches[0];
				obj.style.left = touchs.pageX - disX + 'px';
				obj.style.top  = touchs.pageY - disY + 'px';
				for(var i=0;i<oLi.length;i++){
					oLi[i].style.border = '';
				}
				var nL = nearLi(obj);
				if(nL){nL.style.border = '1px solid #965600';}
			};
			document.ontouchend = function(){
				document.ontouchmove = null;
				document.ontouchend = null;
				var tmp = '';
				var nL = nearLi(obj);
				if(nL){
					startMove( nL , { left : arr[obj.index][0] , top : arr[obj.index][1] },function(){cheack()});
					startMove( obj , { left : arr[nL.index][0] , top : arr[nL.index][1] } );
					nL.style.border = '';
					tmp = obj.index;
					obj.index = nL.index;
					nL.index = tmp;
				}else{
					startMove( obj , { left : arr[obj.index][0] , top : arr[obj.index][1] },function(){cheack()});
				}
			};
			return false;
		};
	}
	function nearLi(obj){    //  距离最近的Li
		var value = 9999;
		var index = -1;
		for(var i=0;i<oLi.length;i++){
			if( pz(obj , oLi[i]) && obj!=oLi[i] ){
				var c = jl( obj , oLi[i] );
				if( c < value ){
					value = c;
					index = i;
				}
			}
		}
		if( index != -1 ){
			return oLi[index];
		}else{
			return false;
		}
	}
	function jl(obj1,obj2){  //最近距离
		var a = obj1.offsetLeft - obj2.offsetLeft;
		var b = obj1.offsetTop - obj2.offsetTop;
		return Math.sqrt(a*a + b*b);
	}
	function pz(obj1,obj2){   //  碰撞检测
		var L1 = obj1.offsetLeft;
		var R1 = obj1.offsetLeft + obj1.offsetWidth;
		var T1 = obj1.offsetTop;
		var B1 = obj1.offsetTop + obj1.offsetHeight;
		var L2 = obj2.offsetLeft;
		var R2 = obj2.offsetLeft + obj2.offsetWidth;
		var T2 = obj2.offsetTop;
		var B2 = obj2.offsetTop + obj2.offsetHeight;
		if( R1<L2 || L1>R2 || B1<T2 || T1>B2 ){
			return false;
		}else{
			return true;
		}
	}
	
	function init(){    //li随机
		var randomArr = [0,1,2,3];
		if(map == 3){
			randomArr = [0,1,2,3,4,5,6,7,8];
		}
		if(map == 4){
			randomArr = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
		}
		if(map == 5){
			randomArr = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];
		}
		if(map == 6){
			randomArr = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35];
		}
		if(map == 7){
			randomArr = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48];
		}
		var str = randomArr.toString();
		fn1()
		function fn1(){
			randomArr.sort(function(n,m){			
				return Math.random() - 0.5;				
			});
			if(str == randomArr.toString()){
				fn1()
			}
		}
		for(var i=0;i<oLi.length;i++){
			startMove( oLi[i] , { left : arr[randomArr[i]][0] , top : arr[randomArr[i]][1] });
			oLi[i].index = randomArr[i];
		}
	}
	
	function cheack(){  //判断Li定位是否匹配原图
		newArr = [];
		for(var i=0; i<oLi.length; i++){			
			newArr.push([oLi[i].offsetLeft,oLi[i].offsetTop]);			
		}
		if(arr.toString() == newArr.toString()){
			clearTimeout(timer);
			$('#return').hide();
			global_obj.win_alert("恭喜您，胜利通关",function(){
				$.post('', 'action=over&Result='+t, function(data){
					if(data.status==1){
						window.location=data.url;
					}
				}, 'json');
			});
		};
	}
}
function startMove(obj,json,endFn){	
	clearInterval(obj.timer);	
	obj.timer = setInterval(function(){		
		var bBtn = true;		
		for(var attr in json){			
			var iCur = 0;		
			if(attr == 'opacity'){
				if(Math.round(parseFloat(getStyle(obj,attr))*100)==0){
				iCur = Math.round(parseFloat(getStyle(obj,attr))*100);
				}
				else{
					iCur = Math.round(parseFloat(getStyle(obj,attr))*100) || 100;
				}	
			}
			else{
				iCur = parseInt(getStyle(obj,attr)) || 0;
			}
			var iSpeed = (json[attr] - iCur)/8;
			iSpeed = iSpeed >0 ? Math.ceil(iSpeed) : Math.floor(iSpeed);
			if(iCur!=json[attr]){
				bBtn = false;
			}
			if(attr == 'opacity'){
				obj.style.filter = 'alpha(opacity=' +(iCur + iSpeed)+ ')';
				obj.style.opacity = (iCur + iSpeed)/100;				
			}
			else{
				obj.style[attr] = iCur + iSpeed + 'px';
			}						
		}		
		if(bBtn){
			clearInterval(obj.timer);			
			if(endFn){
				endFn.call(obj);
			}
		}		
	},30);
}
function getStyle(obj,attr){
	if(obj.currentStyle){
		return obj.currentStyle[attr];
	}
	else{
		return getComputedStyle(obj,false)[attr];
	}
}

	
	