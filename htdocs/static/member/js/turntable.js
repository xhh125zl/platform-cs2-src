/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var turntable_obj={
	turntable_init:function(){
		turntable_obj.turntable_add();
		turntable_obj.turntable_del();
	},
	
	turntable_add:function(){
		$('a[href=#turntable_add]').each(function(){
			$(this).click(function(){
				$('#turntable_add').leanModal();
				var date_str=new Date();
				$('#turntable_add input[name=Time]').daterangepicker({
					timePicker:true,
					minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
					format:'YYYY/MM/DD HH:mm:00'}
				)
			});
		});
		
		$('#turntable_add_form').submit(function(){return false;});
		$('#turntable_add_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#turntable_add_form').serialize(), function(data){
				$('#turntable_add_form input:submit').attr('disabled', false);
				if(data.status==1){
					alert('添加欢乐大转盘成功！');
					window.self.location='turntable_mod.php?TurntableID='+data.TurntableID;
				}else if(data.status==2){
					alert('添加失败，该时段内有其他活动！');
				}else{
					alert('添加失败，出现未知错误！');
				};
			}, 'json');
		});
	},
	
	turntable_del:function(){
		$('#turntable td a[href=#stop]').click(function(){
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
			$.post('?', 'action=stop&TurntableID='+jsonData.TurntableID, function(data){
				if(data.status==1){
					window.location='index.php';
				}
			}, 'json');
		});
		
		$('#turntable td a[href=#del]').click(function(){
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
			$.post('?', 'action=del&TurntableID='+jsonData.TurntableID, function(data){
				if(data.status==1){
					window.location='index.php';
				}
			}, 'json');
		});
	},
	
	wheel_mod:function(){
		$('#wheel_form input[name=UsedIntegral]').bind('click', function(){
			if($(this).is(':checked')){
				$('#wheel_form .integral').show();
			}else{
				$('#wheel_form .integral').hide();
			}
		});
		
		$('#wheel_form input[name=If_Share]').bind('click', function(){
			if($(this).is(':checked')){
				$('#wheel_form .Share_num').show();
			}else{
				$('#wheel_form .Share_num').hide();
			}
		});
		
		var status=$('#wheel_form input[name=status]').val();
		if(status==1){
			$('#wheel_form input').attr('disabled', '');
			$('#wheel_form input:submit').remove();
		}else if(status==2){
			$('#wheel_form input:text').css({'background': '#ffffff'});
			$('#wheel_form input[name=Time], #wheel_form input[name=FirstPrize], #wheel_form input[name=SecondPrize], #wheel_form input[name=ThirdPrize], #wheel_form input[name=UsedIntegral],#wheel_form input[name=If_Share]').attr('disabled', '').css({'background': '#F5F5F5'});
		}else{
			var date_str=new Date();
			$('#wheel_form input[name=Time]').daterangepicker({
				timePicker:true,
				minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
				format:'YYYY/MM/DD HH:mm:00'}
			)
			$('#wheel_form input:text').css({'background': '#ffffff'});
		}
		
		$('#wheel_form').submit(function(){return false;});
		$('#wheel_form input:submit').click(function(){
			 if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#wheel_form').serialize()+'&Description='+encodeURIComponent(editor.html())+'&Turntable_More_Integral='+encodeURIComponent(editor2.html()), function(data){
				$('#wheel_form input:submit').attr('disabled', false);
				if(data.status==3){
					alert('活动已结束，不能修改！');
				}else if(data.status==2){
					alert('中奖概率总和不能大于100%');
				}else if(data.status==4){
					alert('请正确填写所需信息！');
				}else if(data.status==5){
					alert('调整失败，该时段内有其他活动！');
				}else if(data.status==1){
					window.location='index.php';
				}else{
					alert('调整失败，出现未知错误！');
				};
			}, 'json');
		});
	},
	
	sn_init:function(){
		$('#sncode td a[href=#used]').click(function(){
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
			$.post('?', 'action=used&TurntableID='+jsonData.TurntableID+'&SNID='+jsonData.SNID, function(data){
				if(data.status==1){
					window.location='turntable_sncode.php?TurntableID='+jsonData.TurntableID;
				}
			}, 'json');
		});
	}
}