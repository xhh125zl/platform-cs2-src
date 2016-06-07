var hongbao_obj = {
    index_init: function() {
		var n_h = $(window).width()*170/640;
		var o_h = $(window).width()*4/640;
		var m_h = o_h+44;
		var q_h = m_h+$(window).width()*70/640+10;
		$('.act_name').animate({top:n_h}, 500);
		$('.main_other').animate({bottom:o_h}, 500);
		$('.myhong_div').animate({bottom:m_h}, 500);	
		$('.qiang_div').animate({bottom:q_h}, 500);
		$('.qiang_time').animate({bottom:q_h}, 500);
		if(start==1){
			$('.qiang_div').show();
			$('.qiang_time').hide();
		}else{
			var sysj = function(){
				var html = '距下次开抢还剩<br />';  
				time_diff=time_diff-1;
				if(time_diff>0){
					var s = Math.floor(time_diff/3600);
					if(s>0){
						html = html + " " + s +" 小时";
					}
					s = Math.floor(time_diff%3600);
					var m = Math.floor(s/60);
					html = html + " " + m +" 分钟";
					s = Math.floor(s%60);
					html = html + " " + s +" 秒";
					$('.qiang_time p').html(html);
				}else{					
					$('.qiang_div').show();
					$('.qiang_time').hide();
					clearInterval(sysj);
				}
				
			}
			setInterval(sysj, 1000); 
			$('.qiang_div').hide();
			$('.qiang_time').show();
		}
		$('.qiang_div img').click(function(){
			var data = {action:"qiang"};			
			$.post('/api/hongbao/ajax.php?UsersID='+UsersID,data,function(data){			
				if(data.status == 1){
					global_obj.win_alert(data.msg, function(){
						window.location=data.url;
					});
				}else{
					global_obj.win_alert(data.msg);
				}
			},"json");
		});	
	},
	
	detail_init: function() {
		var n_h = $(window).width()*170/640;
		var o_h = $(window).width()*4/640;
		var m_h = o_h+44;
		var q_h = m_h+$(window).width()*70/640+10;
		$('.act_name').animate({top:n_h}, 500);
		$('.invite').animate({bottom:o_h}, 500);
		$('.myhong_div').animate({bottom:m_h}, 500);	
		$('.chai_div').animate({bottom:q_h}, 500);
		$('.syhy').animate({bottom:q_h}, 500);
		if(chai==1){
			$('.chai_div').show();
			$('.syhy').hide();
		}else{ 
			$('.chai_div').hide();
			$('.syhy').show();
		}
		var chai_flag = function(){
			var data = {action:"chai_flag"};			
			$.post('/api/hongbao/ajax.php?UsersID='+UsersID+'&actid='+actid,data,function(data){			
				if(data.status == 1){
					$('.invite').hide();
					$('.syhy').hide();
					$('.chai_div').show();
					clearInterval(chai_flag);		
				}else{
					$('.chai_div').hide();
					$('.invite').show();
					$('.syhy').show();
					$('.syhy p').html('需 '+data.count+' 位好友拆开后到微信钱包');					
				}
			},"json");
		}
		setInterval(chai_flag, 1000);
		
	},
	help_init: function() {
		var n_h = $(window).width()*170/640;
		var o_h = $(window).width()*4/640;
		var m_h = o_h+44;
		var q_h = m_h+$(window).width()*70/640+10;
		$('.act_name').animate({top:n_h}, 500);
		$('.main_other').animate({bottom:o_h}, 500);
		$('.myhong_div').animate({bottom:m_h}, 500);	
		$('.help_div').animate({bottom:q_h}, 500);
		$('#help').click(function(){
			var data = {action:"help"};			
			$.post('/api/hongbao/ajax.php?UsersID='+UsersID+'&actid='+actid,data,function(data){			
				if(data.status == 1){
					global_obj.win_alert(data.msg, function(){
						window.location=data.url;
					});		
				}else{
					global_obj.win_alert(data.msg);				
				}
			},"json");
		});
	}
}