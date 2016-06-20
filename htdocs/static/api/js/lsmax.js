
var lsmax_obj={
    PageHeaderHeight : 102, //页面头部的高度
    PageFooterHeight : 84,  //页面脚注的高度
    GameTimer:null, //比赛计时
    GameSecond:0,//比赛用时
	GameWinner:{}, //保存摇一摇胜利后选手的信息
    setPageHeight : function(){ //设置lsmax-content高度自定响应浏览器 
        lsmax_obj.setPageHeightCtrl();
        $(window).resize(function(){
            lsmax_obj.setPageHeightCtrl();
        });
    },
	login_check:function(){
		$("#myform").submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
		});
	},
    setPageHeightCtrl:function(){
        //---- public start -----//
        var lsmaxContentHeight = $(window).height() - lsmax_obj.PageHeaderHeight - lsmax_obj.PageFooterHeight; //计算得到lsmax-content高度
        var _h = parseInt(lsmaxContentHeight/3);
        $("#lsmax-content").height(lsmaxContentHeight);
        //---- public end -----//
        
        //---- index start -----//
        $("#lsmax-content .lsmax-list .list-item .img-item").width(_h-50).height(_h-50); //图片框高度
        //$("#lsmax-content .lsmax-list .list-item .info-item").height(_h-10);
        //---- index end -----//
        
        //---- photo start -----//
        var photoLstHeight = parseInt((lsmaxContentHeight)/3);
        $("#lsmax-content .photo-detail").height(lsmaxContentHeight-20);
        $("#lsmax-content .slide-bar").height(lsmaxContentHeight-10);
        $("#lsmax-content .slide-bar .img-list").height(photoLstHeight);
        $("#lsmax-content .slide-bar .img-list .img-item").height(photoLstHeight-20);
        
        //---- photo end -----//
        
        //---- lottery start -----//
        $("#lsmax-content .member-list").height(lsmaxContentHeight-$(".lottery-title").height()-$(".btn-item").height()); //标题高度 按钮框高度
        $("#lsmax-content .section").height(lsmaxContentHeight-20);
        //---- lottery end -----//
        
        //---- shake start -----//
        var listHeight = lsmaxContentHeight-$(".shake-title").height()-$(".btn-item").height();
        $("#lsmax-content .shake-list").height(listHeight); //标题高度 按钮框高度
        $("#lsmax-content .shake-item").height(listHeight/2);
        $("#lsmax-content .shake-img").height(listHeight/2-60);
        //---- shake end -----//
        
        //---- vote start -----//
        $("#lsmax-content .chart").css("margin-top",(lsmaxContentHeight-500)/2); //统计图里底部的高度
        //---- vote end -----//
    },
    index_init : function(){ //index 微信墙
        lsmax_obj.setFullScreen(); //设置全屏
        lsmax_obj.setPageHeight(); //设置lsmax-content高度自定响应浏览器 
        lsmax_obj.showBox(); //输出二维码
        lsmax_obj.setBg(); //设置页面背景
		$(window).load(function(){
			lsmax_obj.scrollPage(); //列表滚动
		});
		$('#lsmax-footer a span').removeClass('active');
		$('#lsmax-footer .fi1').addClass('active');
    },
    photo_init : function(){ //photo 相册
        lsmax_obj.setPageHeight();//设置lsmax-content高度自定响应浏览器 
        lsmax_obj.setFullScreen();//设置全屏
        lsmax_obj.showBox(); //输出二维码
        lsmax_obj.setBg(); //设置页面背景
        lsmax_obj.imgScrollEvent(); //相册滚动效果
		$('#lsmax-footer a span').removeClass('active');
		$('#lsmax-footer .fi2').addClass('active');
    },
	votes_init : function(){ //index 微信墙
        lsmax_obj.setFullScreen(); //设置全屏
        lsmax_obj.setPageHeight(); //设置lsmax-content高度自定响应浏览器 
        lsmax_obj.showBox(); //输出二维码
        lsmax_obj.setBg(); //设置页面背景
		$('#lsmax-footer a span').removeClass('active');
		$('#lsmax-footer .fi5').addClass('active');
    },
	votes_chart_init:function(){ //输出统计图
		$('.chart').height(500).highcharts({
			title:{text:''},
            credits:{enabled:false},
			chart: {
               	backgroundColor: 'rgba(0,0,0,0)',
				plotBackgroundColor: null,
				plotBorderWidth:0
            },
			tooltip:{
				pointFormat:'{series.name}: <b>{point.percentage:.2f}%</b>'
			},
			plotOptions:{
				pie:{
					allowPointSelect:true,
					cursor:'pointer',
					dataLabels:{
						enabled:true,
						color:'#ffffff',
						connectorColor:'#ffffff',
						format:'<b>{point.name}</b>: {point.percentage:.2f} %'
					}
				}
			},
			series:[{
				type:'pie',
				name:'百分比',
				data:pie_data
			}]
		});
	},
	
    media_init : function(){ //media 视频
        lsmax_obj.setPageHeight();//设置lsmax-content高度自定响应浏览器 
        lsmax_obj.setFullScreen();//设置全屏
        lsmax_obj.showBox(); //输出二维码
        lsmax_obj.setBg(); //设置页面背景
    },
    lottery_init : function(){ //lottery 抽奖
        lsmax_obj.setPageHeight();//设置lsmax-content高度自定响应浏览器 
        lsmax_obj.setFullScreen();//设置全屏
        lsmax_obj.showBox(); //输出二维码
        lsmax_obj.setBg(); //设置页面背景
        lsmax_obj.selectWinnerLottery(); //选出中奖名单
		$('#lsmax-footer a span').removeClass('active');
		$('#lsmax-footer .fi4').addClass('active');
    },
    wheel_init : function(){ //lottery 抽奖
        lsmax_obj.setPageHeight();//设置lsmax-content高度自定响应浏览器 
        lsmax_obj.setFullScreen();//设置全屏
        lsmax_obj.showBox(); //输出二维码
        lsmax_obj.setBg(); //设置页面背景
        lsmax_obj.selectWinnerBigwheel(); //选出中奖名单
		$('#lsmax-footer a span').removeClass('active');
		$('#lsmax-footer .fi9').addClass('active');
    },
    shake_init:function(){ //摇一摇pc
        lsmax_obj.setPageHeight();//设置lsmax-content高度自定响应浏览器 
        lsmax_obj.setFullScreen();//设置全屏
        lsmax_obj.showBox(); //输出二维码
        lsmax_obj.setBg(); //设置页面背景
        lsmax_obj.searchPlayerPC(); //每1500毫秒搜索参加摇一摇的选手(电脑端)
        lsmax_obj.delPlayer();//剔除参赛选手
        lsmax_obj.beginSignUp();//点击开始报名按钮
        lsmax_obj.joinGame(); //主持点击“开始”按钮加入游戏
    },
    phone_init:function(){ //摇一摇mobile
		$("#shake-main").css({"min-height":$(window).height()});  
        lsmax_obj.searchPlayerPhone(); //每1500毫秒搜索参加摇一摇的选手(手机端)
        lsmax_obj.changePlayerStatus(); //修改选手状态            
    },
    game_init:function(){ //比赛页面
        $("body").css({"background":"url('"+bg+"') center top no-repeat"});//设置页面背景
        lsmax_obj.startGame();
        $(".group-btn").eq(0).click(function(){
            var link = $(this).attr("link");
            if(!$("#addSourceId").val()){
                if(confirm("您还没有保存比赛成绩，您需要保存吗？")){
                     saveResult();   
                } else {
                    window.location.href = link;
                }
            } else {
                window.location.href = link;
            }
        });
        $(".group-btn").eq(1).click(function(){//保存比赛结果
            saveResult();
			//console.log(lsmax_obj.GameWinner);
        });
        $(".group-btn").eq(2).click(function(){//查看比赛结果
            $("#result-item").show();
            $.ajax({
                type: "POST",
                url: lsmax_link,
                data: "MId="+MId+"&type=show_result",
                dataType:"json",
                success: function(msg){
                   $("#result-item").html(msg['html'])
                }
            });
        });
        $(".result-close").live("click",function(){
            $("#result-item").hide(); 
        });
        $(".game-event").live("click",function(){
            var GId=$(this).attr("GId");
            $("#result-item .game-event").css("background","#0c7ecf");
            $(this).css("background","#50b400");
            $("#result-item .wrapResult").hide()
            $("#gid"+GId).show();
        });
        function saveResult(){
			var GroupPId="";
			var GroupShakeNum="";
			console.log(lsmax_obj.GameWinner);
			$.each(lsmax_obj.GameWinner,function(index,element){
				GroupPId += element["PId"]+"-";
				GroupShakeNum += element["ShakeNum"]+"-";
			});
			if(!$("#addSourceId").val()){
                $.ajax({
                    type: "POST",
                    url: lsmax_link,
                    data: "MId="+MId+"&GroupPId="+GroupPId+"&GroupShakeNum="+GroupShakeNum+"&type=add_source",
                    dataType:"json",
                    success: function(msg){
                        if(msg['GId']){
                            $("#addSourceId").val(msg['GId']);
                            alert('成绩保存成功');
                        }
                    }
                });
            } else {
                alert('成绩已经保存');
            }
        }
    },
    game_phone_init:function(){ //手机摇动版面
        $("body").css({"background": "#292D2E"});
        lsmax_obj.checkGameStatus(); //检测摇一摇活动 status是否开始
        lsmax_obj.shakePhone(); //摇到手机记录数据
    },
    imgScrollEvent:function(){//相册滚动效果
        var num=0;
        var len=$(".img-list").size();
        $(".icon-item .lbtn").click(turnLeft);
        $(".icon-item .rbtn").click(turnRight);
        setInterval(turnRight,7000);
        function turnLeft(){
            num--;
            num=num<0?(len-1):num;
            changeIcon(num);
        }
        function turnRight(){
            num++;
            num = num>(len-1)?0:num;
            changeIcon(num);
        }
        function changeIcon(num){
            var imgUrl = "/static";
            var img1=imgUrl+"/api/images/lsmax/banner_icon1.png";
            var img2=imgUrl+"/api/images/lsmax/banner_icon1_1.png";
            var h=$(".slide-bar ul li").eq(0).height();
            $(".slide-bar ul").animate({top:-num*h},function(){
                $(".icon-section img").eq(num).attr("src",img2).siblings("img").attr("src",img1);
                $(".l-img").attr("src",$(".slide-bar ul li img").eq(num).attr("src"));
            });
        }
    },
    shakePhone:function(){
        var SHAKE_THRESHOLD = 3000;
        var last_update = 0;
        var num=0;
        var total=0;
        var x = y = z = last_x = last_y = last_z = 0;
        var openid=$("#openid").val();
        if (window.DeviceMotionEvent) {
            window.addEventListener('devicemotion', deviceMotionHandler, false);
        } else {
            alert('本设备不支持devicemotion事件');
        }
        function deviceMotionHandler(eventData) {
            var acceleration = eventData.accelerationIncludingGravity;
            var curTime = new Date().getTime();
            if ((curTime - last_update) > 100) {
                var diffTime = curTime - last_update;
                last_update = curTime;
                x = acceleration.x;
                y = acceleration.y;
                z = acceleration.z;
                var speed = parseInt(Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000);
                if (speed > SHAKE_THRESHOLD && $("#IsShake").val()==1) {
                    num++;
                    total += speed;
                    var percent = parseInt((total / 1000000) * 100);
                    percent = percent>100?100:percent;
                    if(num%5==0){
						var data = {UsersID:UsersID,WallID:WallID,YID:YID,OpenID:openid,total:parseInt(total),action:"add_shake_num"};		
						$.post('/api/wall/ajax.php',data,function(data){
						},"json");
                    }
                    $(".shake-notice").html(" 您的目标："+percent+"%");
                }
                last_x = x;
                last_y = y;
                last_z = z;
            }
        } 
    },
    checkGameStatus:function(){ //检测游戏状态0初始状态 1开始 2结束
        var checkTimer=null;
        var openid = $("#openid").val();
        var isShowNotice=true;
        checkTimer = setInterval(function(){
			var data = {UsersID:UsersID,WallID:WallID,YID:YID,OpenID:openid,action:"get_game_status"};			
			$.post('/api/wall/ajax.php',data,function(data){
				if(data.status==1){
                    if(isShowNotice){
                        $(".shake-notice").html("摇动你们的手机吧!<br>摇啊摇啊摇啊^_^");
                        $("#IsShake").val(1);
                        isShowNotice=false;
                    }
                }
                //游戏结束跳去结束页面
                if(data.status==2){
                    window.location.href='/api/'+UsersID+'/wall/shake_result/'+WallID+'/'+openid+'/';
                }
                //$playerRow['Status']==0 跳回等待页面 
                if(data.status==99){
                    window.location.href='/api/'+UsersID+'/wall/shake_phone/'+WallID+'/'+openid+'/';
                }
			},"json");
        },1500);
    },
    startGame:function(){ 
        var startGameTimer=null;
        var i=0;
        var num = parseInt($("#reciprocal-num").text());
        $(".game-start").click(function(){
            $(this).hide();
            $("#reciprocal-num").show();
            //开始到计时
            startGameTimer = setInterval(function(){
                i++;
                $("#reciprocal-num").text(num-i);
                if(i==6){
                    //倒数完毕
                    clearInterval(startGameTimer);
                    $(".game-notice").text("摇动你们的手机吧!摇啊摇啊摇啊^_^");
                    $("#reciprocal-num").hide();
                    var data = {UsersID:UsersID,WallID:WallID,action:"start_game_status"};			
					$.post('/api/wall/ajax.php',data,function(data){
						if(data.status==0){
							global.win_alert("发生错误");
							return false;
						}
					},"json");
                    lsmax_obj.playerMove(); //选手开始移动
                    lsmax_obj.timing(); //开始计时
                }
            },1000);
        });
    },
    timing:function(){
        var m=0;//分钟
        var s=0;//秒钟
        lsmax_obj.GameTimer = setInterval(function(){
            lsmax_obj.GameSecond++;
            m=parseInt(lsmax_obj.GameSecond/60);
            s=lsmax_obj.GameSecond%60;
            $(".game-time").html("时间："+m+" 分 "+s+" 秒");
        },1000);
    },
    playerMove:function(){
        var numArr=[];
        var playMoveTimer=null;
        var MId=$("#MId").val();
        var lsmax_link = $("#lsmax_link").val();
        var TotalNum=1000000;//摇动的总数值
        var S = 822//路程
        playMoveTimer=setInterval(function(){
			var data = {UsersID:UsersID,WallID:WallID,action:"player_move"};			
			$.post('/api/wall/ajax.php',data,function(data){
				if(data.status==1){				
					var len=data.msg.size;
					for(var i=0;i<len;i++){
						var PId=data.msg[i]["PId"];
						var ShakeNum=Math.abs(parseInt(data.msg[i]["ShakeNum"]));
						var percent=ShakeNum/TotalNum;
						var move=Math.abs(percent*S);
						var winNum=ShakeNum>TotalNum?data.msg[i]["PId"]:"";
							
						if(ShakeNum > TotalNum){ 
							clearInterval(playMoveTimer);//比赛停止
							clearInterval(lsmax_obj.GameTimer);//计时停止
							//最后一次移动选手位置
							for(var j=0;j<len;j++){
								var percentLast=data.msg[j]["ShakeNum"]/TotalNum;
								var moveLast=percentLast*S;
								$("#"+data.msg[j]["PId"]).animate({"left":Math.abs(moveLast)});
							}
							$("#"+PId).css({"left":S});
							lsmax_obj.showWinTable(data.msg); //显示胜利信息
						}else{
							$("#"+PId).animate({"left":move});
						}
					}
				}
			},"json");
        },1000);
    },
    showWinTable:function(playerInfo){ //获取前三名的成绩
        var GroupPId="";
        var arr=["source-frist-item","source-second-item","source-third-item"];
		var data = {UsersID:UsersID,WallID:WallID,action:"end_game_status"};			
		$.post('/api/wall/ajax.php',data,function(data){
			if(data.status==0){
				global.win_alert("发生错误");
				return false;
			}
		},"json");
		//输出前三名成绩
		$("#source-item").show();
		$.each(playerInfo,function(index,element){
			$("#source-item ."+arr[index]+" .player-img").html("<img src="+element["Face"]+" />");
            $("#source-item ."+arr[index]+" .player-name").html(element["Nickname"]);
			GroupPId += element["PId"]+"-";
		});
		lsmax_obj.GameWinner = $.extend(true, {}, playerInfo);//保存选手信息(对象深度复制)
    },
    beginSignUp:function(){        
        $(".begin-sign-up").click(function(){
            var data = {UsersID:UsersID,WallID:WallID,action:"begin_sign_up"};			
			$.post('/api/wall/ajax.php',data,function(data){
				if(data.status==1){
					$(".begin-sign-up").hide();
					$(".start-btn").css("display","inline-block");
				}
			},"json");
		});
	},
    joinGame:function(){
        $(".start-btn").click(function(){
            var flag = true;
            var len=$(".shake-list .shake-item").size();
            if(len){
				var data = {UsersID:UsersID,WallID:WallID,action:"start_game"};
				$.post('/api/wall/ajax.php',data,function(data){
					if(data.status==1){
						window.location.href=data.url;
					}
				},"json");                
            }else{
                alert('等待参赛选手');
            }
        });
    },
    delPlayer:function(){
        $(".shake-close").live("click",function(){
            var openid = $(this).parent().parent().attr("id");
            if(confirm("您确定剔除该选手？")){
				var data = {UsersID:UsersID,WallID:WallID,OpenID:openid,action:"del_player"};			
				$.post('/api/wall/ajax.php',data,function(data){
					if(data.status==1){
                        $("#"+data.openid).remove();
					}else{
						global_obj.win_alert(data.msg);
					}
				},"json");				
            }
        });    
    },
    changePlayerStatus:function(){
        var openid = $("#openid").val();
        $(".phone-btn").click(function(){
			var data = {UsersID:UsersID,WallID:WallID,OpenID:openid,action:"player_begin_sign_up"};			
			$.post('/api/wall/ajax.php',data,function(data){
				if(data.status==1){
					window.location.href=data.url; //直接跳去准备摇一摇
				}else{
					global_obj.win_alert(data.msg);
				}
			},"json");
        });
    },
    searchPlayerPC:function(){
        var timer = null;
        timer = setInterval(function(){
			var data = {UsersID:UsersID,WallID:WallID,action:"get_player_list_pc"};			
			$.post('/api/wall/ajax.php',data,function(data){
				$(".shake-list").html(data.html);
                $(".shake-list .shake-item .shake-img").height($(".shake-list .shake-img").width()); 
			},"json");            
        },1500);
    },
    searchPlayerPhone:function(){
        var timer = null;
        timer = setInterval(function(){
            //---- 搜索选手参加 start ----//
			var data = {UsersID:UsersID,WallID:WallID,action:"get_player_list_phone"};			
			$.post('/api/wall/ajax.php',data,function(data){
				$(".phone-content").html(data.html);
                $(".phone-content .list-item .phone-img").height($(".phone-content .list-item").width()); //设置图片正方
				if(data.IsBeginSignUp==1){
                    $(".phone-title .title-str1").html('请点击"报名"按钮进入摇一摇活动');
                } else {
                    $(".phone-title .title-str1").html('请等待主持人点击"开始报名"按钮');
                }
                if(data.IsGame==1){
                    $("#shake-main .Gameing").show();
                    $("#shake-main .SignUp").hide();
                } else {
                    $("#shake-main .Gameing").hide();
                    $("#shake-main .SignUp").show();
                }
			},"json");
            //---- 搜索选手参加 end ----//
        },1500);
    },
    selectWinnerLottery:function(){		
        var lsmax_link = "/api/wall/ajax.php";
        var scrollTimer='';
        var imgMum=-1;
        $('.lucky-btn').click(function(){
            var winnerNum=$(".winner-item").size();
            //抽中的人数大于中奖名额 或者 抽中的人数大于等于参赛用户
            if(winnerNum>=lottery_obj.WinningNum || winnerNum >= lottery_obj.playerNum){
				alert('本奖品所有名额已中出！');
				return;
			}
            imgMum=-1;
			var data = {UsersID:UsersID,WallID:WallID,PrizeID:lottery_obj.AId,MuAward:lottery_obj.MuAward,action:"get_user_list"};			
			$.post('/api/wall/ajax.php',data,function(data){
				if(data.status==1){
					$('.items_list').html(data.html);
					$('.lucky-btn').hide();
					scrollTimer=setInterval(function(){
						imgMum++;
						$(".items").eq(imgMum).hide();
						if(imgMum > $(".items").size()-2){
							imgMum=-1;
							$(".items").show();
						}
					},60);
					
					setTimeout(function(){
						$('.stop-btn').show();
					},500);
				}				
			},"json");			
		});
		
		$('.stop-btn').click(function(){
            var str="";
            $(this).css('display','none');
            clearInterval(scrollTimer);
            var userNum = imgMum+1; //获取幸运儿的序号
            var len=$(".winner-item").size();
            var openid = $(".items").eq(userNum).attr("UserId");
			var data = {UsersID:UsersID,WallID:WallID,PrizeID:lottery_obj.AId,openid:openid,action:"winner"};
            $.post('/api/wall/ajax.php',data,function(data){
                str +='<div class="winner-item">';
                str +=  '<div class="winner-num">'+(len+1)+'</div>';
                str +=  '<div class="winner-img"><img src="'+data.face+'" /></div>';
                str +=  '<div class="winner-name">'+data.nickname+'</div>';
                str +='</div>';
                $('.lottery-section3').append(str);
                $('.lucky-btn').css('display','inline');
            },'json');
		});
    },
    selectWinnerBigwheel:function(){
        var lsmax_link = $("#lsmax_link").val();
        var scrollTimer='';
        var imgMum=-1;
        $('.lucky-btn').click(function(){
            var winnerNum=$(".winner-item").size();
            if(winnerNum>=bigwheel_obj.WinningNum || winnerNum >= bigwheel_obj.playerNum){
				alert('本奖品所有名额已中出！');
				return;
			}
            imgMum=-1;
            var data = {UsersID:UsersID,WallID:WallID,PrizeID:bigwheel_obj.AId,MuAward:bigwheel_obj.MuAward,action:"get_wuser_list"};			
			$.post('/api/wall/ajax.php',data,function(data){
				if(data.status==1){
					$('.items_list').html(data.html);
					$('.lucky-btn').hide();
					scrollTimer=setInterval(function(){
						imgMum++;
						$(".items").eq(imgMum).hide();
						if(imgMum > $(".items").size()-2){
							imgMum=-1;
							$(".items").show();
						}
					},60);
					
					setTimeout(function(){
						$('.stop-btn').show();
					},500);
				}				
			},"json");
		});
		
		$('.stop-btn').click(function(){
            var str="";
			$(this).css('display','none');
			$('.lucky-btn').css('display','inline');
            clearInterval(scrollTimer);
            var userNum = imgMum+1; //获取幸运儿的序号
            var len=$(".winner-item").size();
            var openid = $(".items").eq(userNum).attr("UserId");
			var data = {UsersID:UsersID,WallID:WallID,PrizeID:bigwheel_obj.AId,openid:openid,action:"wwinner"};
            $.post('/api/wall/ajax.php',data,function(data){
                str +='<div class="winner-item">';
                str +=  '<div class="winner-num">'+(len+1)+'</div>';
                str +=  '<div class="winner-img"><img src="'+data.face+'" /></div>';
                str +=  '<div class="winner-name">'+data.nickname+'</div>';
                str +='</div>';
                $('.lottery-section3').append(str);
                $('.lucky-btn').css('display','inline');
            },'json');
		});
    },
    getMemberList:function(){
        var tt = null;
        var data = {UsersID:UsersID,WallID:WallID,action:"getMemberList"};	
        tt = setInterval(function(){
			$.post('/api/wall/ajax.php',data,function(data){
				$(".member-list").html(data.html);
			},"json");
        },2000);
    },
    setBg:function(){
        $("body").css({"background":"url('"+bg+"') center top no-repeat","background-size":"cover"});
    },
    vote_results:function(){ //输出统计图
		$('.chart').height(500).highcharts({
			title:{text:''},
            credits:{enabled:false},
			chart: {
               	backgroundColor: 'rgba(0,0,0,0)',
				plotBackgroundColor: null,
				plotBorderWidth:0
            },
			tooltip:{
				pointFormat:'{series.name}: <b>{point.percentage:.2f}%</b>'
			},
			plotOptions:{
				pie:{
					allowPointSelect:true,
					cursor:'pointer',
					dataLabels:{
						enabled:true,
						color:'#ffffff',
						connectorColor:'#ffffff',
						format:'<b>{point.name}</b>: {point.percentage:.2f} %'
					}
				}
			},
			series:[{
				type:'pie',
				name:'百分比',
				data:vote_results_data
			}]
		});
	},
    showBox:function(){
        $('.tdcode').click(function(){	
            var wid = 370;
			var l=($(document).width()-wid)/2;
            $('#showbox').css({'left':l+'px','width':wid+'px'}).show();				
		}); 
        $('#showbox .close').click(function(){						
			$("#showbox").hide();		
		});
    },
    scrollPage :function(){
        var key=0;
		var isScroll = true;
		
		setInterval(function(){
			//大于3条信息以后信息复制
			
			if($("#lsmax-content ul li").size()>2 && isScroll){    
				$("#lsmax-content ul").append($("#lsmax-content ul").html());
				isScroll=false;
			}
			//大于3条后信息开始滚动
			if($("#lsmax-content ul li").size()>2){
				var iLiHiehgt = $("#lsmax-content ul li").eq(key).height()+10;
				$("#lsmax-content ul").animate({"top":"-="+iLiHiehgt},1500,function(){
					if($("#lsmax-content ul li").size()/2 == key){
						$("#lsmax-content ul").css("top",0);
						key=0;
					}
				});
				key++;
			}
            //获取最新的消息
            var len=$("#lsmax-content ul li").size()>2?$("#lsmax-content ul li").size()/2:$("#lsmax-content ul li").size();
            var MsgIdArr=[];
            for(var i=0;i<len;i++){
                MsgIdArr.push($("#lsmax-content ul li").eq(i).attr("MSId"));
            }
            MsgIdArr.sort(function(a,b){return a<b?1:-1});//从大到小排序
            var maxMSId = MsgIdArr[0] ? MsgIdArr[0] : 0;
			var data = {UsersID:UsersID,WallID:WallID,maxid:maxMSId,action:"get_new_msg"};			
			$.post('/api/wall/ajax.php',data,function(data){
				if(data.num>0){
					$.each(data.html,function(MSId,html){
                        $("#lsmax-content ul li").eq(len).before(html);
                        $("#lsmax-content ul").append(html);
                        lsmax_obj.setPageHeight();
                    });
				}
			},"json");
        },3500);
    },
    setFullScreen : function(){
        $('.fullshow').click(function(){    //全屏						
            if($.browser.msie){ //IE兼容处理
                window.open(window.location.href+'&full=1','','fullscreen=1,left=0,top=0,menubar=0, scrollbars=no, resizable=0,titlebar=0, location=no,toolbar=0,channelmode=1,depended=1,status=no,width='+screen.availWidth+',height='+screen.availHeight);
            } else { //非IE浏览器
                if(document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if(document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if(document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen();
                } else if(element.msRequestFullscreen) {
                    document.documentElement.msRequestFullscreen();
                }     			
            }
		});
		$('.fullhid').click(function(){   //取消全屏			
			window.close();				
		});
		$('.tdcode').click(function(){$('#showbox').css({'left':($(document).width()-370)/2,'width':370}).show();});
        $('#showbox .close').click(function(){$("#showbox").hide();});
    }
}