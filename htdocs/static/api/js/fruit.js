/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

(function () {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function (callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function () { callback(currTime + timeToCall); },
              timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function (id) {
            clearTimeout(id);
        };
}());

(function () {
    window.GameTimer = function (fn, timeout) {
        this.__fn = fn;
        this.__timeout = timeout;
        this.__running = false;
        this.__lastTime = Date.now();
        this.__stopcallback = null;
    };

    window.GameTimer.prototype.__runer = function () {
        if (Date.now() - this.__lastTime >= this.__timeout) {
            this.__lastTime = Date.now();
            this.__fn.call(this);
        }
        if (this.__running) {
            window.requestAnimationFrame(this.__runer.bind(this));
        }
        else {
            if (typeof this.__stopcallback === 'function') {
                window.setTimeout(this.__stopcallback,100);
            }
        }
    };

    window.GameTimer.prototype.start = function () {
        this.__running = true;
        this.__runer();
    };
    window.GameTimer.prototype.stop = function (callback) {
        this.__running = false;
        this.__stopcallback = callback;
    };

})();

var fruit_obj={
	fruit_init:function(){
		global_obj.hide_opt_menu();
		$('#WheelEvent').show();
		if(start==false){return;}
		
		var itemPositions = [
			0, //苹果
			100,//芒果
			200,//布林
			300,//香蕉
			400,//草莓
			500,//梨
			600,//桔子
			700,//青苹果
			800//樱桃
		];
		
		//游戏开始
		var gameStart = function () {
			lightFlicker.stop();
			lightRandom.stop();
			lightCycle.start();
			
			//游戏开始，指定用户的结果，从左到右，水果编码，从0开始。
			//随机给个用于测试
			//boxCycle.start(Math.round(Math.random() * 8), Math.round(Math.random() * 8), Math.round(Math.random() * 8));
	
			//先后台抽奖，生成获奖纪录，然后再调用
			$.post('','action=move', function(result){
				if(result.result==-1){
					window.location='tips/';
				}else{
					boxCycle.start(result.data);
				}
			}, 'json');
		};
		
		//游戏结束
		var gameOver = function (resultData) {
			lightFlicker.stop();
			//lightRandom.start();
			lightRandom.stop();
			lightCycle.stop();
			
			//alert('你获得的水果编码从左到右：' + left + ',' + middle + ',' + right);
			//var resultData=eval('('+resultData+')');
			if(resultData.type==0){
				global_obj.win_alert(resultData.msg, function(){$('.machine .gamebutton').removeClass('disabled')});
			}else{
				global_obj.win_alert(resultData.msg+'SN码为：'+resultData.sn, function(){
					$('#WinPrize').slideDown(500);
					$('#PrizeClass').html(resultData.prize);
					$('#SnNumber').html(resultData.sn);
					$('#GetPrize input[name=MobilePhone]').focus();
				});
			}
		};
		
		var $machine = $('.machine');
		var $slotBox = $('.tigerslot .box');
		var light_html = '';
		for (var i = 0; i < 21; i++) {
			light_html += '<div class="light l'+ i +'"></div>';
		}
		var $lights = $(light_html).appendTo($machine);

		var $gameButton = $('.machine .gamebutton').tap(function () {
			var $this = $(this);
			if (!$this.hasClass('disabled')) {
				$this.addClass('disabled');
				$this.toggleClass(function (index, classname) {
					if (classname.indexOf('stop') > -1) {
						boxCycle.stop(function (resultData) {
							gameOver(resultData);
						});
					} else {
						gameStart();
						window.setTimeout(function () {
							$this.removeClass('disabled');
						},1000);
					}
					return 'stop';
				});
			}
		});
		
		var lightCycle = new function () {
			var currIndex = 0, maxIndex = $lights.length - 1;
			$('.l0').addClass('on');
			var tmr = new GameTimer(function () {
				$lights.each(function(){
					var $this = $(this);
					if($this.hasClass('on')){
						currIndex++;
						if (currIndex > maxIndex) {
							currIndex = 0;
						}
						$this.removeClass('on');
						$('.l' + currIndex).addClass('on');
						return false;
					}
				});
			}, 100);
			this.start = function () {
				tmr.start();
			};
			this.stop = function () {
				tmr.stop();
			};
		};
		var lightRandom = new function () {
			var tmr = new GameTimer(function () {
				$lights.each(function () {
					var r = Math.random() * 1000;
					if (r < 400) {
						$(this).addClass('on');
					} else {
						$(this).removeClass('on');
					}
				});
			}, 100);
			this.start = function () {
				tmr.start();
			};
			this.stop = function () {
				tmr.stop();
			};
		};	
		var lightFlicker = new function () {
			$lights.each(function (index) {
				if ((index >> 1) == index / 2) {
					$(this).addClass('on');
				} else {
					$(this).removeClass('on');
				}
			});
			var tmr = new GameTimer(function () {
				$lights.toggleClass('on');
			}, 100);
			this.start = function () {
				tmr.start();
			};
			this.stop = function () {
				tmr.stop();
			};
		};
		
		var boxCycle = new function () {
			var speed_left = 0, speed_middle = 0, speed_right = 0, maxSpeed = 25;
			var running = false, toStop = false, toStopCount = 0;
			var boxPos_left = 0, boxPos_middle = 0, boxPos_right = 0;
			var toLeftIndex = 0, toMiddleIndex = 0, toRightIndex = 0;
			var resultData;
			
			var $box = $('.tigerslot .box'), $box_left = $('.tigerslot .strip.left .box'), $box_middle = $('.tigerslot .strip.middle .box'), $box_right = $('.tigerslot .strip.right .box');
	
			var fn_stop_callback = null;
	
			var tmr = new GameTimer(function () {
				if (toStop) {
					toStopCount--;
					speed_left = 0;
					boxPos_left = -itemPositions[toLeftIndex];
					if (toStopCount < 25) {
						speed_middle = 0;
						boxPos_middle = -itemPositions[toMiddleIndex];
					}
					if (toStopCount < 0) {
						speed_right = 0;
						boxPos_right = -itemPositions[toRightIndex];
					}
				} else {
					speed_left += 1;
					speed_middle += 1;
					speed_right += 1;
					if (speed_left > maxSpeed) {
						speed_left = maxSpeed;
					}
					if (speed_middle > maxSpeed) {
						speed_middle = maxSpeed;
					}
					if (speed_right > maxSpeed) {
						speed_right = maxSpeed;
					}
				}
	
				boxPos_left += speed_left;
				boxPos_middle += speed_middle;
				boxPos_right += speed_right;
	
				$box_left.css('background-position', '0 ' + boxPos_left + 'px')
				$box_middle.css('background-position', '0 ' + boxPos_middle + 'px')
				$box_right.css('background-position', '0 ' + boxPos_right + 'px')
	
				if (speed_left == 0 && speed_middle == 0 && speed_right == 0) {
					tmr.stop(fn_stop_callback.bind(this, resultData));
				}
				
			}, 33);
	
			this.start = function (data) {
				var data=eval('('+data+')');
				toLeftIndex = data.left; toMiddleIndex = data.middle; toRightIndex = data.right;
				running = true; toStop = false;
				resultData = data;
				tmr.start();
			};
	
			this.stop = function (fn) {
				fn_stop_callback = fn;
				toStop = true;
				toStopCount = 50;
			};
	
	
			this.reset = function () {
				$box_left.css('background-position', '0 ' + itemPositions[0] + 'px');
				$box_middle.css('background-position', '0 ' + itemPositions[0] + 'px');
				$box_right.css('background-position', '0 ' + itemPositions[0] + 'px');
			};
			this.reset();
		};
		
		//初始给点欢乐
		lightFlicker.start();
		window.setTimeout(function () {
			lightFlicker.stop();
		}, 2000);
		
		$('#GetPrize').submit(function(){return false;});
		$('#GetPrize input:submit').click(function(){
			var Phone=$('input[name=MobilePhone]');
			if(Phone.val()==''){
				global_obj.win_alert('请填写手机号码！', function(){Phone.focus()});
				return false;
			}
			if(!(/^13\d{9}$/g.test(Phone.val()) || /^14[57]\d{8}$/g.test(Phone.val()) || /^15[0-35-9]\d{8}$/g.test(Phone.val()) || /^18\d{9}$/g.test(Phone.val()))){
				global_obj.win_alert("'"+Phone.val()+"'不是一个有效的手机号码！", function(){Phone.focus()});
				return false;
			}
			
			$(this).attr('disabled', true).val('提交中...');
			$.post('', $('#GetPrize').serialize()+'&action=mobile', function(data){
				if(data.status==1){
					global_obj.win_alert('提交成功！', function(){
						window.location.reload();
					});
				}else if(data.status==-1){
					global_obj.win_alert(data.msg);
					$('#GetPrize input:submit').attr('disabled', false).val('提交');
				}else{
					$('#GetPrize input:submit').attr('disabled', false).val('提交');
				};
			}, 'json');
		});
	},
	
	use_sn_init:function(){
		global_obj.hide_opt_menu();
		$('#PrizeTips .prize_list li span a[href=#usesn]').click(function(){
			var sn=$(this).parent().attr('sn');
			var snid=$(this).parent().attr('snid');
			$('#UsedSn').slideDown(200);
			$('#UsedSn #UsedSnNumber').text(sn);
			$('#UsedSn form input[name=SNID]').val(snid);
			$('#UsedPrize input[name=bp]').focus();
			return false;
		});
		
		$('#UsedPrize').submit(function(){return false;});
		$('#UsedPrize input:submit').click(function(){
			if($('#UsedPrize input[name=bp]').val()==''){
				global_obj.win_alert('请输入商家密码！', function(){$('#UsedPrize input[name=bp]').focus()});
				return false;
			}
			
			$(this).attr('disabled', true).val('提交中...');
			$.post('', $('#UsedPrize').serialize()+'&action=used', function(data){
				if(data.status==1){
					global_obj.win_alert('兑奖成功！', function(){window.top.location.reload();});
				}else{
					global_obj.win_alert(data.msg);
				};
				$('#UsedSn').slideUp(200);
				$('#UsedPrize input[name=bp]').val('');
				$('#UsedPrize input:submit').attr('disabled', false).val('提交');
			}, 'json');
		});
		
		$('#UsedPrize .close').click(function(){
			$('#UsedSn').slideUp(200);
		});
	}
}