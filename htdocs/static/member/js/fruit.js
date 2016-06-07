/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var fruit_obj={
	fruit_init:function(){
		fruit_obj.fruit_add();
		fruit_obj.fruit_del();
	},
	
	fruit_add:function(){
		$('a[href=#fruit_add]').each(function(){
			$(this).click(function(){
				$('#fruit_add').leanModal();
				var date_str=new Date();
				$('#fruit_add input[name=Time]').daterangepicker({
					timePicker:true,
					minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
					format:'YYYY/MM/DD HH:mm:00'}
				)
			});
		});
		
		$('#fruit_add_form').submit(function(){return false;});
		$('#fruit_add_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#fruit_add_form').serialize(), function(data){
				$('#fruit_add_form input:submit').attr('disabled', false);
				if(data.status==1){
					alert('添加水果达人成功！');
					window.self.location='fruit_mod.php?FruitID='+data.FruitID;
				}else if(data.status==2){
					alert('添加失败，该时段内有其他活动！');
				}else{
					alert('添加失败，出现未知错误！');
				};
			}, 'json');
		});
	},
	
	fruit_del:function(){
		$('#fruit td a[href=#stop]').click(function(){
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
			$.post('?', 'action=stop&FruitID='+jsonData.FruitID, function(data){
				if(data.status==1){
					window.location='index.php';
				}
			}, 'json');
		});
		
		$('#fruit td a[href=#del]').click(function(){
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
			$.post('?', 'action=del&FruitID='+jsonData.FruitID, function(data){
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
		
		var status=$('#wheel_form input[name=status]').val();
		if(status==1){
			$('#wheel_form input').attr('disabled', '');
			$('#wheel_form input:submit').remove();
		}else if(status==2){
			$('#wheel_form input:text').css({'background': '#ffffff'});
			$('#wheel_form input[name=Time], #wheel_form input[name=FirstPrize], #wheel_form input[name=SecondPrize], #wheel_form input[name=ThirdPrize], #wheel_form input[name=UsedIntegral]').attr('disabled', '').css({'background': '#F5F5F5'});
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
			$.post('?', $('#wheel_form').serialize(), function(data){
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
			$.post('?', 'action=used&FruitID='+jsonData.FruitID+'&SNID='+jsonData.SNID, function(data){
				if(data.status==1){
					window.location='fruit_sncode.php?FruitID='+jsonData.FruitID;
				}
			}, 'json');
		});
	}
}