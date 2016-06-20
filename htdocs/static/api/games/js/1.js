$(window).load(function(){
	game_init();
});

var body, blockSize, GameLayer=[], GameLayerBG, touchArea=[], GameTimeLayer;
var _gameBBList=[], _gameBBListIndex=0, _gameOver=false, _gameStart=false, _gameTimer, _gameTimeStart, _gameTimeNum, _gameScore;
var transform, transitionDuration;
var _ttreg=/ t{1,2}(\d+)/, _clearttClsReg=/ t{1,2}\d+| bad/;

//var isDesktop=navigator['userAgent'].match(/(ipad|iphone|ipod|android|windows phone)/i)?false:true;
var isDesktop= true;
var fontunit=isDesktop?20:((window.innerWidth>window.innerHeight?window.innerHeight:window.innerWidth)/320)*10;
document.write('<style type="text/css">html,body{font-size:'+(fontunit<30?fontunit:'30')+'px;}'+(isDesktop?'#GameTimeLayer,#GameLayerBG{position: absolute;}':'#GameTimeLayer,#GameLayerBG{position:fixed;}')+'</style>');

function game_init(){
	body=document.getElementById('gameBody') || document.body;
	body.style.height=window.innerHeight+'px';
	transform=typeof(body.style.webkitTransform)!='undefined'?'webkitTransform':(typeof(body.style.msTransform)!='undefined'?'msTransform':'transform');
	transitionDuration=transform.replace(/ransform/g, 'ransitionDuration');
	
	var html='<div id="GameLayerBG">';
	for(var i=1; i<=2; i++){
		var id='GameLayer'+i;
		html+='<div id="'+id+'" class="GameLayer">';
		for(var j=0; j<10; j++){
			for(var k=0; k<4; k++){
				html+='<div id="'+id+'-'+(k+j*4)+'" num="'+(k+j*4)+'" class="block'+(k?' bl':'')+'"></div>';
			}
		}
		html+='</div>';
	}
	html+='</div>';
	html+='<div id="GameTimeLayer"></div>';
	$('body').append(html);

	GameTimeLayer=document.getElementById('GameTimeLayer');
	GameLayer.push(document.getElementById('GameLayer1'));
	GameLayer[0].children=GameLayer[0].querySelectorAll('div');
	GameLayer.push(document.getElementById('GameLayer2'));
	GameLayer[1].children=GameLayer[1].querySelectorAll('div');
	GameLayerBG=document.getElementById('GameLayerBG');
	GameLayerBG.ontouchstart=gameTapEvent;
	
	createjs.Sound.registerSound({src:'/static/api/games/media/err.mp3', id:'err'});
	createjs.Sound.registerSound({src:'/static/api/games/media/win.mp3', id:'end'});
	createjs.Sound.registerSound({src:'/static/api/games/media/tap.mp3', id:'tap'});
	
	_gameBBList=[];
	_gameBBListIndex=0;
	_gameScore=0;
	_gameOver=false;
	_gameStart=false;
	_gameTimeNum=parseInt(GameConfig.time)*1000;
	GameTimeLayer.innerHTML=creatTimeText(_gameTimeNum);
	countBlockSize();
	refreshGameLayer(GameLayer[0]);
	refreshGameLayer(GameLayer[1], 1);
	window.addEventListener('resize', refreshSize, false);
}

function countBlockSize(){
	blockSize=body.offsetWidth/4;
	body.style.height=window.innerHeight+'px';
	GameLayerBG.style.height=window.innerHeight+'px';
	touchArea[0]=window.innerHeight-blockSize*0;
	touchArea[1]=window.innerHeight-blockSize*3;
}

function refreshSize(){
	countBlockSize();
	for(var i=0; i<GameLayer.length; i++){
		var box=GameLayer[i];
		for(var j=0; j<box.children.length; j++){
			var r=box.children[j],rstyle=r.style;
			rstyle.left=(j%4)*blockSize+'px';
			rstyle.bottom=Math.floor(j/4)*blockSize+'px';
			rstyle.width=blockSize+'px';
			rstyle.height=blockSize+'px';
		}
	}
	var f, a;
	if(GameLayer[0].y>GameLayer[1].y){
		f=GameLayer[0];
		a=GameLayer[1];
	}else{
		f=GameLayer[1];
		a=GameLayer[0];
	}
	var y=((_gameBBListIndex)%10)*blockSize;
	f.y=y;
	f.style[transform]='translate3D(0,'+f.y+'px,0)';
	a.y=-blockSize*Math.floor(f.children.length/4)+y;
	a.style[transform]='translate3D(0,'+a.y+'px,0)';
}

function gameTapEvent(e){
	if(_gameOver){return false;}
	var tar=e.target;
	var y=e.clientY || e.targetTouches[0].clientY,
		x=(e.clientX || e.targetTouches[0].clientX)-body.offsetLeft,
		p=_gameBBList[_gameBBListIndex];
	if(y>touchArea[0] || y<touchArea[1]){return false;}
	if((p.id==tar.id&&tar.notEmpty) || (p.cell==0&&x<blockSize) || (p.cell==1&&x>blockSize&&x<2*blockSize) || (p.cell==2&&x>2*blockSize&&x<3*blockSize) || (p.cell==3&&x>3*blockSize)){
		if(!_gameStart){gameStart();}
		createjs.Sound.play('tap');
		tar=document.getElementById(p.id);
		tar.className=tar.className.replace(_ttreg, ' tt$1');
		_gameBBListIndex++;
		_gameScore++; 
		gameLayerMoveNextRow();
	}else if(_gameStart && !tar.notEmpty){
		createjs.Sound.play('err');
		GameLayerBG.className+=' flash';
		gameOver();
		tar.className+=' bad';
	}
	return false;
}

function gameStart(){
	_gameTimeStart=new Date().getTime().toString();
	_gameStart=true;
	_gameTimer=setInterval(gameTime, 10);
}

function gameOver(){
	_gameOver=true;
	clearInterval(_gameTimer);
	$.post('', 'action=over&Result='+_gameScore, function(data){
		if(data.status==1){
			setTimeout(function(){
				window.location=data.url;
			}, 2000);
		}
	}, 'json');
}

function gameTime(){
	var over_time=new Date().getTime().toString()-_gameTimeStart;
	if(over_time>=_gameTimeNum){
		GameTimeLayer.innerHTML='时间到';
		gameOver();
		GameLayerBG.className+=' flash';
		createjs.Sound.play('end');
	}else{
		GameTimeLayer.innerHTML=creatTimeText(_gameTimeNum-over_time);
	}
}

function creatTimeText(n){
	s=''+n;
	text=''+Math.floor(n/1000)+"'"+s.substr(-3, 2)+"''"
	return text;
}

function refreshGameLayer(box, loop, offset){
	var i=Math.floor(Math.random()*1000)%4+(loop?0:4);
	for(var j=0; j<box.children.length; j++){
		var r=box.children[j],rstyle=r.style;
		rstyle.left=(j%4)*blockSize+'px';
		rstyle.bottom=Math.floor(j/4)*blockSize+'px';
		rstyle.width=blockSize+'px';
		rstyle.height=blockSize+'px';
		r.className=r.className.replace(_clearttClsReg, '');
		if(i==j){
			_gameBBList.push({cell:i%4, id:r.id});
			r.className+=' t'+(Math.floor(Math.random()*1000)%5+1);
			r.notEmpty=true;
			i=(Math.floor(j/4)+1)*4+Math.floor(Math.random()*1000)%4;
		}else{
			r.notEmpty=false;
		}
	}
	if(loop){
		box.style.webkitTransitionDuration='0ms';
		box.style.display='none';
		box.y=-blockSize*(Math.floor(box.children.length/4)+(offset||0))*loop;
		setTimeout(function(){
			box.style[transform]='translate3D(0,'+box.y+'px,0)';
			setTimeout(function(){
				box.style.display='block';
			}, 100);
		}, 200);
	}else{
		box.y=0;
		box.style[transform]='translate3D(0,'+box.y+'px,0)';
	}
	box.style[transitionDuration]='150ms';
}

function gameLayerMoveNextRow(){
	for(var i=0; i<GameLayer.length; i++){
		var g=GameLayer[i];
		g.y+=blockSize;
		if(g.y>blockSize*(Math.floor(g.children.length/4))){
			refreshGameLayer(g, 1, -1);
		}else{
			g.style[transform]='translate3D(0,'+g.y+'px,0)';
		}
	}
}