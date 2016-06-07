var BASE_RES_DIR='/static/api/games/images/';
var RES_DIR = "";
var APP_DEPLOYMENT = "WEB";
var USE_NATIVE_SOUND = !1;
var USE_NATIVE_SHARE = !1;
var IS_IOS = navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? !0 : !1;
var IS_ANDROID = -1 < navigator.userAgent.indexOf("Android");
var IS_NATIVE_ANDROID = IS_ANDROID && -1 < navigator.userAgent.indexOf("Version");
var IS_REFFER =true;
var SHOW_LLAMA = !0; 
var SHOW_COPYRIGHT = !1;
var IN_WEIXIN = !1;
var  IS_SUB = !1;
var  best = -10000;
score = 0; 
record_flag = !1; 
logFlag = !1;
keyStorage = "bestball";
function initBest() {
	best = gjStorage.get(keyStorage) || -10000;
}
function cacheBest(a) {
	a > best && (best = a, gjStorage.set(keyStorage, best));
}
function onNewScore(score) {
	cacheBest(score);
}
(function (a, b) {
	a.get = function (a) {
		if(localStorage){
			return localStorage.getItem(a);
		}
		return 0;
	};
	a.set = function (a, b) {
		if(localStorage){
			localStorage.setItem(a,b);
		}		
		return !0;
	};
})(window.gjStorage = window.gjStorage || {});

var stage, W = $(document).width()*2, H = $(document).height()*2, IS_TOUCH, SCREEN_SHOW_ALL = !1, g_androidsoundtimer = null, g_followAnim = null;
onload = function () {		
	stage=new createjs.Stage("stage");
	if (IS_TOUCH = createjs.Touch.isSupported()) {
		createjs.Touch.enable(stage, !0);
		var a = new createjs.Shape;
		a.graphics.f("white").r(0, 0, W, H);
		stage.addChild(a);
	}
	createjs.Ticker.setFPS(60);
	setTimeout(setCanvas, 100);
	createjs.Ticker.on("tick", stage);
	loadResource();
	initBest();	
};
onresize = setCanvas;
function setCanvas() {
	var a = stage.canvas, b = window.innerWidth, c = window.innerHeight - 3;
	if (SCREEN_SHOW_ALL) {
		var d = c;
		b / c > W / H ? b = W * c / H : c = H * b / W;
		a.style.marginTop = (d - c) / 2 + "px";
	} else {
		d = W * c / H, b >= d ? (b = d, stage.x = 0) : stage.x = (b - d) / 2;
	}
	a.width = W;
	a.height = H;
	a.style.width = b + "px";
	a.style.height = c + "px";
}
createjs.DisplayObject.prototype.do_cache = function () {
	var a = this.getBounds();
	this.cache(a.x, a.y, a.width, a.height);
};
function ProgressBar(a, b) {
	this.initialize();
	this.w = a;
	this.h = b;
	this.progress = new createjs.Shape;
	this.progress.graphics.s("black").r(0, 0, a, b).es();
	this.progress.graphics.lf(["red", "yellow", "blue"], [0, 0.5, 1], 0, 0, a, 0);
	this.progressText = new createjs.Text("资源加载中..", "bold 24px Arial", "black");
	this.progressText.x = a / 2;
	this.progressText.y = b / 2;
	this.progressText.textAlign = "center";
	this.progressText.textBaseline = "middle";
	this.addChild(this.progress);
	this.addChild(this.progressText);
}
ProgressBar.prototype = new createjs.Container;
ProgressBar.prototype.completeCallback = function (a) {
	this.parent.removeChild(this);
};
ProgressBar.prototype.progressCallback = function (a) {
	this.progress.graphics.r(0, 0, this.w * a.progress, this.h);
	this.progressText.text = "已加载: " + parseInt(100 * a.progress) + "%";
};
ProgressBar.prototype.forQueue = function (a) {
	this.errorList = [];
	a.on("complete", this.completeCallback, this, !0);
	a.on("progress", this.progressCallback, this);
	a.on("error", function (a) {
		global_obj.win_alert("资源加载出现错误!");
	}, null, !0);
	a.on("error", function (a) {
		this.errorList.push(a.item.src);
	}, this);
};
createjs.DisplayObject.prototype.setAnchorPoint = function (a, b) {
	var c = this.getBounds();
	this.regX = c.width * a;
	this.regY = c.height * b;
};
createjs.Container.prototype.addCenterChild = function (a) {
	a.setAnchorPoint(0.5, 0.5);
	var b = this.getBounds();
	a.x = b.x + 0.5 * b.width;
	a.y = b.y + 0.5 * b.height;
	this.addChild(a);
};

var g_queue, qp_a, qp_b = [], qp_c = 1000, qp_d = 500, qp_e = !0, GAME_READY = 1, GAME_PLAY = 2, GAME_FAIL = -2, GAME_OVER = -1, GAME_START = -10, GAMESTATUS = GAME_START;
function loadResource() {
	SCREEN_SHOW_ALL = !0;
	var a = new ProgressBar(0.8 * W, 40);
	a.regX = a.w / 2;
	a.regY = a.h / 2;
	a.x = W / 2;
	a.y = H / 2;
	stage.addChild(a);
	queue = new createjs.LoadQueue(!1);
	queue.setMaxConnections(30);
	queue.on("complete", function(){
		a=new Qp_g;
		stage.addChild(a);
		createjs.Ticker.on("tick", a.update, a);
		qp_a = new Qp_h;
		stage.addChild(qp_a);
		qp_a.startGame();
	}, null, !0);
	queue.loadManifest({path:BASE_RES_DIR, manifest:[{src:"cloud1.png", id:"cloud1"}, {src:"cloud2.png", id:"cloud2"}, {src:"cloud3.png", id:"cloud3"}, {src:"cloud4.png", id:"cloud4"}, {src:"score.png", id:"scorelabel"}, {src:"line.png", id:"line"}, {src:"green.png", id:"ballgreen"}, {src:"blue.png", id:"ballblue"}, {src:"red.png", id:"ballred"}]}, !1);
	a.forQueue(queue);
	queue.load();
}
function Qp_g() {
	this.initialize();
	var a = new createjs.Shape;
	a.graphics.lf(["#88c3d9", "#9ddcf2"], [0, 1], 0, 0, 0, H).r(0, 0, W, H);
	this.addChild(a);
	for (a = this.nextCloud = 0; 3 > a; a++) {
		this.update();
	}
}
Qp_g.prototype = new createjs.Container;
Qp_g.prototype.update = function (a) {
	a && (this.nextCloud -= a.delta / 1000);
	if (0 >= this.nextCloud) {
		var c = parseInt(4 * Math.random()) + 1, b = new createjs.Bitmap(queue.getResult("cloud" + c));
		b.setAnchorPoint(0.5, 0.5);
		b.y = Math.random() * H * 0.6;
		a ? (b.x = -b.image.width / 2, this.nextCloud += 5 * Math.random() + 5) : b.x = Math.random() * W;
		a = W + b.image.width / 2;
		createjs.Tween.get(b).to({x:a}, (a - b.x) * c * 10).call(function () {
			this.parent.removeChild(this);
		});
		this.addChild(b);
	}
};
function Qp_h() {
	this.initialize();
	this.gameplayinglayer = new Qp_m;
	this.gameplayinglayer.visible = !1;
	this.addChild(this.gameplayinglayer);
}
Qp_h.prototype = new createjs.Container;
Qp_h.prototype.gameover = function () {
	GAMESTATUS != GAME_OVER && (GAMESTATUS = GAME_OVER, createjs.Ticker.removeEventListener("tick", window.update), this.gameplayinglayer.visible = !1, onNewScore(score));
	$.post('', 'action=over&Result='+score, function(data){
		if(data.status==1){
			window.location=data.url;
		}
	}, 'json');
};
Qp_h.prototype.startGame = function () {
	GAMESTATUS = GAME_START;
	record_flag = !1;
	qp_b = [];
	score = 0;
	if (!1 == qp_e) {
		this.removeChild(a), this.gameplayinglayer.doReset(), this.gameplayinglayer.visible = !0, createjs.Ticker.addEventListener("tick", window.update);
	} else {
		qp_e = !1;
		var a = new createjs.Text("在线下面点猪宝宝", "bold 60px Arial", "#ff9d36");
		a.stroke = "white";
		a.textBaseline = "middle";
		a.regX = a.getBounds().width / 2;
		a.regY = a.getBounds().height / 2;
		a.scaleX = 2;
		a.scaleY = 2;
		createjs.Tween.get(a).to({scaleX:1, scaleY:1}, 200);
		createjs.Tween.get(a).to({alpha:0.6}, 200).to({alpha:1}, 200).to({alpha:0.6}, 200).to({alpha:1}, 200);
		a.x = 320;
		a.y = 720;
		this.addChild(a);
		setTimeout(function () {
			qp_a.removeChild(a);
			qp_a.gameplayinglayer.doReset();
			qp_a.gameplayinglayer.visible = !0;
			createjs.Ticker.addEventListener("tick", window.update);
		}, 1500);
	}
};
function Qp_m() {
	this.initialize();
	this.ballLayer = new createjs.Container;
	this.addChild(this.ballLayer);
	var a = new createjs.Bitmap(queue.getResult("scorelabel"));
	a.setAnchorPoint(0, 0.5);
	a.x = 30;
	a.y = 50;
	this.addChild(a);
	this.scoreText = new createjs.Text("0", "bold 48px Arial", "#ff9d36");
	this.scoreText.stroke = "white";
	this.scoreText.textBaseline = "middle";
	this.scoreText.x = 140;
	this.scoreText.y = 50;
	this.addChild(this.scoreText);
	a = new createjs.Bitmap(queue.getResult("line"));
	a.setAnchorPoint(0.5, 0.5);
	a.x = 320;
	a.y = qp_d;
	this.addChild(a);
}
Qp_m.prototype = new createjs.Container;
Qp_m.prototype.randomBalls = function () {
	var a = new Qp_q(queue, ["ballgreen", "ballred", "ballblue"][parseInt(3 * Math.random())], 506 * Math.random() + 67);
	qp_b.push(a);
	this.ballLayer.addChild(a);
};
Qp_m.prototype.doReset = function () {
	this.ballLayer.removeAllChildren();
	this.scoreText.text = "" + score;
};
function update(a) {
	5 > qp_b.length && score / 5 + 1 > qp_b.length && qp_a.gameplayinglayer.randomBalls();
	a = a.delta / 1300;
	for (var c in qp_b) {
		if (qp_b[c].refreshLoop(a)) {
			return qp_a.gameover();
		}
	}
}
createjs.DisplayObject.prototype.setAnchorPoint = function (a, c) {
	var b = this.getBounds();
	this.regX = b.width * a;
	this.regY = b.height * c;
};
createjs.Container.prototype.addCenterChild = function (a) {
	a.setAnchorPoint(0.5, 0.5);
	var c = this.getBounds();
	a.x = c.x + 0.5 * c.width;
	a.y = c.y + 0.5 * c.height;
	this.addChild(a);
};
function Qp_q(a, c, b) {
	this.initialize();
	this.ball = new createjs.Bitmap(a.getResult(c));
	this.ball.setAnchorPoint(0.5, 0.5);
	this.addChild(this.ball);
	this.doReset(b);
	this.on("mousedown", function (a) {
		IS_TOUCH && a.nativeEvent instanceof MouseEvent || this.touch(a.localX, a.localY);
	}, this);
}
Qp_q.prototype = new createjs.Container;
Qp_q.prototype.doReset = function (a) {
	this.y = -67;
	this.x = a;
	this.vx = this.vy = 0;
	this.ball.rotation = 0;
};
Qp_q.prototype.touch = function (a, c) {
	if (this.y > qp_d) {
		var b = this.x, d = Math.abs(Math.sqrt(2 * qp_c * this.y));
		if (320 <= b) {
			var e = (-d - Math.sqrt(4000 * qp_c)) / qp_c;
			this.vx = b / e;
		} else {
			e = (-d - Math.sqrt(4000 * qp_c)) / qp_c, this.vx = (b - 640) / e;
		}
		this.vy = -d;
		0 > this.vy && (score++, qp_a.gameplayinglayer.scoreText.text = score);
	}
};
Qp_q.prototype.refreshLoop = function (a) {
	this.vy += qp_c * a;
	this.y += this.vy * a;
	this.x += this.vx * a;
	this.mouseEnabled = 0 < this.vy;
	this.ball.rotation = (this.ball.rotation + 100 * a) % 360;
	0 >= this.x - 67 ? this.vx = Math.abs(this.vx) : this.x + 67 >= W && (this.vx = -Math.abs(this.vx));
	return this.y >= H;
};