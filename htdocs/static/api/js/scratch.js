/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var scratch_obj={
	scratch_init:function(){
		//global_obj.hide_opt_menu();
		if(start==true){
			var status='enable';
			var useragent=window.navigator.userAgent.toLowerCase();
			$('#scratchpad').wScratchPad({
				width:160,
				height:45,
				color:'#a9a9a7',
				scratchMove:function(e, percent){
					if(isMove){
						if(useragent.indexOf('android 4')>0){
							if($('#scratchpad').css('color').indexOf('51')>0){
								$('#scratchpad').css('color', 'rgb(50,50,50)');
							}else if($('#scratchpad').css('color').indexOf('50')>0){
								$('#scratchpad').css('color', 'rgb(51,51,51)');
							}
						}
						if(percent>30 && status=='enable'){
							status='disable';
							if($('#SnNumber').html()){
								$('#get_prize').show();
							}
						}
					}else{
						isMove=true;
						$.post('','action=move', function(data){
							if(data.status==1){
								$('#prize').text(data.msg);
								$('#PrizeClass').html(data.msg);
								$('#SnNumber').html(data.sn);
								$('#get_prize').show();
							}else{
								$('#prize').text(data.msg);
							};
						}, 'json');
					}
				}
			});
		}
		
		$('#get_prize input').click(function(){
			$('#WinPrize').slideDown(500);
			$('#WinPrize input[name=MobilePhone]').focus();
			$('#get_prize').hide();
		});
		
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
					global_obj.win_alert('提交成功！',function(){
						$('#GetPrize input:submit').attr('disabled', true).val('提交成功！');
						window.top.location.reload();
					});
				}else if(data.status==0){
					global_obj.win_alert(data.msg);
					$('#GetPrize input:submit').attr('disabled', false).val('提交');
				}else{
					$('#GetPrize input:submit').attr('disabled', false).val('提交');
				};
			}, 'json');
		});
	},
	
	use_sn_init:function(){
		//global_obj.hide_opt_menu();
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
$(document).ready(function(){
	
	//积分赚取抽奖次数
    $('#jifen').click(function(){
	      $.post('', 'action_do=jifen', function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){window.top.location.reload();});
				}else{
					global_obj.win_alert(data.msg);
				};
	      }, 'json');
      });
	  
	  /*邀请帮助*/
		$('#share').click(function(){
			$('.share_layer').css('height', $(document).height()).show();
			return false;
		});
		$('.share_layer').click(function(){
			$(this).hide();
		});
		$('#more_jf').click(function(){
			$msg = $("#more_jf_txt").html();
		    global_obj.win_alert($msg);
		})
})

//WeixinJSBridge.call('showOptionMenu');