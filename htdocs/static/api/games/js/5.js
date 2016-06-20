$(function(){
	$(window).load(function(){
		setTimeout(function(){
			game=new Game($('#box').get(0));
			game.init();
		},400);
	});
});
function Game(oBox){
	this.oBox=oBox; //容器
	this.windowWidth=$(window).width()>640?640:$(window).width(); //窗口宽度
	this.windowHiehgt=$(window).height();//窗口高度
	this.itemCurrentWidth=0.16;
	this.itemWidth= this.itemCurrentWidth * $(this.oBox).width();
	this.itemHeight=this.itemCurrentWidth * $(this.oBox).width();
	this.row=4;
	this.cols=5;
	this.gap=($(this.oBox).width()-this.itemWidth*this.row)/(this.row+1);
	this.postion=[]; //记录item x y
	this.numAry=[];	 //图片序号
	this.cardAry=[]; //保存图片
	this.closeCard=0;
	this.source=0;	//记录成绩
	this.timer={timeEvt:null,second:60}; //游戏时间
	this.btn1={ //保存当前点击第一次
		openType:false, //当前状态关闭
		num:null,		//保存当前数字
		index:null		//索引
	}; 
	this.btn2={ //保存当前点击第二次
		openType:false, //当前状态关闭
		num:null,		//保存当前数字
		index:null		//索引
	}; 
}
Game.prototype.init = function(){
	this.setPostion(); //布局转换
	this.setRandomAry(); //生成随机数组
	this.setEvent(); //点击事件
	this.getCard(); //获取图片组
	this.countTime(); //开始计算游戏时间
}
Game.prototype.setPostion=function(){
	var self=this;
	$(this.oBox).height(this.itemWidth*this.cols+this.gap*(this.cols+1));
	$(this.oBox).find('.item').width(this.itemWidth).height(this.itemHeight);
	$('.item').css({'margin-left':this.gap,'margin-top':this.gap});
	$('.item').each(function(index, element) {
		self.postion.push({x:$(element).get(0).offsetLeft,y:$(element).get(0).offsetTop});
    });
	$('.item').each(function(index, element) {
		$(element).css({'position':'absolute','margin':'0','left':self.postion[index].x,'top':self.postion[index].y});
    });
}
Game.prototype.setRandomAry=function(){
	var i=0;
	var str='';
	while(i<this.row*this.cols){
		var rand = Math.floor(Math.random()*(this.cols));
		if(this.checkAry(this.numAry,rand)<this.row){
			this.numAry.push(rand);
			i++;
		}
	}
	/*
	for(var i=0;i<this.numAry.length;i++){
		if(i%4==3){
			str += this.numAry[i]+',<br>';
		} else {
			str += this.numAry[i]+',';
		}
	}
	$('#notice').html(str);
	*/
}
Game.prototype.setEvent=function(){
	var self=this;
	$('.item').each(function(index, element) {
		$(element).bind('touchstart',function(){
			var num=self.numAry[index];
			var img=self.cardAry[num];
			$(this).css({'background':'url('+img+')','background-size':'100% 100%'});
			if(self.btn1.openType && self.btn2.openType){
				if(self.btn1.index!=index && self.btn2.index!=index){
					$('.item').eq(self.btn1.index).css('background-image','url('+$('#bg').val()+')');
					$('.item').eq(self.btn2.index).css('background-image','url('+$('#bg').val()+')');
					self.btn1.openType=true;
					self.btn1.num=num;
					self.btn1.index=index;
					self.btn2.openType=false;
					self.btn2.num=null;
					self.btn2.index=null;
				}
			} else {
				if(!self.btn1.openType){
					self.btn1.openType=true;
					self.btn1.num=num;
					self.btn1.index=index;
				} else if(self.btn1.index != index) {
					self.btn2.openType=true;
					self.btn2.num=num;
					self.btn2.index=index;
				}
				if(self.btn1.num == self.btn2.num){
					self.source++;
					self.closeCard+=2;
					$('#source .source_title2').eq(0).html(self.source);
					setTimeout(function(){
						$('.item').eq(self.btn1.index).hide();
						$('.item').eq(self.btn2.index).hide();
					},100);
				}
			}
			if(self.closeCard == $('.item').size()){ //再run一次
				setTimeout(function(){
					var str='';
					$('.item').show();
					$('.item').css('background-image','url('+$('#bg').val()+')');
					self.btn1.openType=false;
					self.btn1.num=null;
					self.btn1.index=null;
					self.btn2.openType=false;
					self.btn2.num=null;
					self.btn2.index=null;
					self.closeCard=0;
					self.numAry=self.randArray(self.numAry).concat();
					/*
					for(var i=0;i<self.numAry.length;i++){
						if(i%4==3){
							str += self.numAry[i]+',<br>';
						} else {
							str += self.numAry[i]+',';
						}
					}
					$('#notice').html(str);
					*/
				},400);
			}
		});
    });
}
Game.prototype.getCard=function(){
	for(var i=0;i<5;i++){
		this.cardAry.push($('#img_'+i).val());
	}
}
Game.prototype.countTime=function(){
	var self=this;
	this.timer.timeEvt=setInterval(function(){
		self.timer.second--;
		var time=self.timer.second.toString();
		var showTime=null;
		switch(time.length){
			case 1:
				showTime='00:0'+time;
			break;
			case 2:
				showTime='00:'+time;
			break;
		}
		$('#source .source_title2').eq(1).html(showTime);
		if(self.timer.second<1){
			clearInterval(self.timer.timeEvt);
			self.gameover();
		}
	},1000);
}
Game.prototype.gameover=function(){
	var self=this;
	$('#box').html('');
	global_obj.win_alert("游戏结束",function(){
		$.post('', 'action=over&Result='+self.source, function(data){
			if(data.status==1){
				window.location=data.url;
			}
		}, 'json');
	});
}
Game.prototype.checkAry=function(ary,num){
	var j=0;
	for(var i=0;i<ary.length;i++){
		if(ary[i]==num){
			j++;
		}
	}
	return j;
}
Game.prototype.randArray=function(data){
    var arrlen = data.length;
    var try1 = new Array();
    for(var i = 0;i < arrlen; i++){
        try1[i] = i;
    }
    var try2 = new Array();
    for(var i = 0;i < arrlen; i++){
        try2[i] = try1.splice(Math.floor(Math.random() * try1.length),1);
    }
    var try3 = new Array();
    for(var i = 0; i < arrlen; i++){
        try3[i] = data[try2[i]];
    }
    return try3;
}