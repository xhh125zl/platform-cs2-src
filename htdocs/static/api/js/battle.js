/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

battle_obj={
	mseTime:null,
	Account:null,
	currentTime:'',
	getExam:function($examId){
		$.ajax({
			type 	: 'POST',
			url  	: $('#hidUrl').val(),
			data 	: 'ExamID='+$examId+'&date='+new Date(),
			dataType: 'json',
			success	: function(msg){
				$('.q2_content').html(msg['question']);
				$('.source-title').html(msg['source']);
			}
		});
	},
	remainTime:function(iTime){	
		var $str=iTime.toString();
		var $fisrtTimeLen=$str.length;
		if($fisrtTimeLen==2){
			$('.square').eq(0).html($str[0]);
			$('.square').eq(1).html($str[1]);
		}else{
			$('.square').eq(0).html(0);
			$('.square').eq(1).html($str[0]);
		}
		
		battle_obj.currentTime=new Date().getTime().toString().substr(0,10); //当前加载时间
		setTimeout(function(){
			battle_obj.mSecond(10);
		}, 1000);
		battle_obj.Account=setInterval(function(){
			var nowTime=new Date().getTime().toString().substr(0,10);
			_time=parseInt(nowTime) - parseInt(battle_obj.currentTime);
			sTime=iTime - _time; 
			//赋值
			sTime=sTime.toString();
			$len =sTime.length;
			if($len==2){
				$('.square').eq(0).html(sTime[0]);
				$('.square').eq(1).html(sTime[1]);
			}else{
				$('.square').eq(0).html(0);
				$('.square').eq(1).html(sTime[0]);
			}
			
			if(_time>=10){
				if($('#hidisSound').val()==1){
					$('#music').get(0).play();
					$(document).bind("touchstart",function(){
						$('#music').get(0).play();
					})
				}
			}
			//判断停止
			if(_time>=iTime){
				battle_obj.showDelateNotice();//停止倒数
			}
		},1000);
	},
	cut:function(){ //停止倒数及关闭声音
		clearTimeout(battle_obj.Account);
		clearInterval(battle_obj.mseTime);
		$('.square').html(0);
		$('#hidisSound').val()==1?$('#music').get(0).pause():''; // 1有开启音效，操作声音
	},
	showDelateNotice: function(){
		$('#notice-item').show();
		global_obj.div_mask();
		battle_obj.cut();
	},
	showWrongNotice: function(){
		$('#wrong-item').show();
		global_obj.div_mask();
		battle_obj.cut();
	},
	showRihtNoeice:function(){
		$('#right-item').show();
		global_obj.div_mask();
		battle_obj.cut();
	},
	mSecond:function(iTime){
		battle_obj.mseTime=setInterval(function(){
			iTime--;
			tStr=iTime.toString();
			if(iTime==2){
				$('.square').eq(2).html(0);
				$('.square').eq(3).html(tStr[0]);
			}else{
				$('.square').eq(2).html(0);
				$('.square').eq(3).html(tStr[0]);
			}
			if(iTime<=0){
				iTime=10;
			}
		},100);
	},
	selectAnswer: function(){ //选择题目
		$('.questionList').live('click',function(){
			var $answer=$(this).index();
			var $ExamID=$(this).attr('currentId');
			$(this).addClass('bg1');
			
			$.ajax({
				type 	: 'POST',
				url  	: $('#hidUrl').val(),
				data 	: 'ExamID='+$ExamID+'&answer='+$answer+'&type=judge&date='+new Date().getTime(),
				dataType: 'json',
				success	: function(msg){
					if(msg['stu']==2){ //答错了
						battle_obj.showWrongNotice(); 
						battle_obj.cut();
					}else{
						battle_obj.cut(); //题目答对 跳到下一题
						battle_obj.showRihtNoeice();
					}
				}
			});
		});
	},
	nextQuestion:function(){
		$('.next-btn').click(function(){
			battle_obj.nextQuestionCtrl();
		});
	},
	nextQuestionCtrl:function(){
		var $currentNum=parseInt($('.currentNum').html());       //当前题号
		var $allQueNum=parseInt($('#hidAllQuestionNum').val()); //总题目数

		$.ajax({
			type 	: 'POST',
			url  	: $('#hidUrl').val(),
			data 	: 'type=nextQuestion&notInId='+$('#hidExamID').val()+'&currentExamID='+$('#hidCurrentExamID').val()+'&date='+new Date().getTime(),
			dataType: 'json',
			success	: function(msg){
				battle_obj.cut();
				if($currentNum < $allQueNum){ //继续答题
					$('.q2_content').html(msg['question']);
					//$('.source-title').html(msg['source']);
					global_obj.div_mask(1);
					$('#notice-item , #wrong-item ,#right-item').hide();
					battle_obj.remainTime($('#hidLimitTime').val());
					$('#hidExamID').val(msg['notInId']);
					$('#hidCurrentExamID').val(msg['currentExamID']);
				}else{ //已经答完题目了
					$('#wrong-item').show();
					$('#right-item , #notice-item').hide();
					global_obj.div_mask();
					$('.wrongImg img').attr('src','/static/api/images/battle/win1.png');
					$('.wrongNotice').html('题目已经答完了');
					$('#wrong-item .next-btn').html('查看成绩');
					$.post('','Act_End_Time=Act_End_Time', function(data){},'json')
					$('#wrong-item .next-btn').click(function(){
						window.location.href='/api/'+UsersID+'_'+ActID+'/battle/result/'+$('#hidBAId').val()+'/?wxref=mp.weixin.qq.com';
					});
				}
			}
		});
	},
	page_init:function(){
		global_obj.win_alert($('#hidNotice').val());
	},
    user_init:function(){
	 /*接受PK*/
	 $("#do_action").click(function(){
			var data = {action:"PK",actid:ActID};
			$.post(document.URL+'/api/battle/index.php?UsersID='+UsersID,data,function(data){
				global_obj.win_alert(data.msg, function(){
					if(data.url){
					   window.location=data.url;
					}
				});		
			},'json')	
	 });
	 $('.share_layer').click(function(){
		    window.location.reload();
			$(this).hide();
		});
     $('.btns p').click(function(){
			var id=$(this).attr('rel');
			$('.btns p').removeClass();
			$(this).addClass('cur');
			$('.stores div').hide();
			$('.stores #rank_list_'+id).show();
	 })
	 $("#send_friends").click(function(){
	        global_obj.win_prompt("输入你要押的积分数量：");
			$('#global_win_prompt h1#ok').click(function(){
				var Integral = $('#global_win_input').val();
				$('#global_win_prompt').remove();
				if(Integral){			
					if(!/^[0-9]*$/.test(Integral)){  
					   global_obj.win_alert("请输入数字!");
					   return false;
					} 
					$.post('','Send_Integral='+Integral, function(data){
					   if(data.status==1){
						   global_obj.win_alert(data.msg, function(){
							   $('.share_layer').css('height', $(document).height()).show();
						   });		   
						   if(share_flag==1 && signature!=""){
							   global_obj.show_opt_menu();
						   }
					   }else{
						  global_obj.win_alert(data.msg);
					   }
					},'json')
				}				
			});			
	 })
}
}