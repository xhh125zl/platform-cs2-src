var turntable_obj={
	turntable_init:function(){
		turntable_obj.share_init();
		$("#startbtn").rotate({
			bind:{
				click:function(){
					if(start==false){
						return;
					}
					$.post('','action=move', function(data){
						$("#turntable #WheelEvent .fs i").text($("#turntable #WheelEvent .fs i").text()-1);
						$('.off_layer').css({'height':$(document).height(),'width':$(document).width(),'position':'absolute', 'z-index':1000}).show();
						if(data.status==1){
							$('#startbtn').rotate({
								duration:6000,
								angle: data.rand, 
								animateTo:5400+data.rand,
								easing:$.easing.easeOutSine,
								callback:function(){
									if(data.prize){
										global_obj.win_alert(data.msg+'SN码为：'+data.sn, function(){
											//window.location.reload();
											//$('.off_layer').hide();
											$('#GetPrize').css({'position':'relative', 'z-index':10000})
											$('#WinPrize').slideDown(500);
											$('#PrizeClass').html(data.prizemsg);
											$('#SnNumber').html(data.sn);
											$('#GetPrize input[name=MobilePhone]').focus();
											start=true;
										});								
									}else{										
										global_obj.win_alert(data.msg, function(){
											start=true;
											$('.off_layer').hide();
											window.location.reload();
										});
									}
								}
							});		
						}else if(data.status==-2){
							window.location='tips/';
						}else{
							if(data.url){
								global_obj.win_alert(data.msg,function(){
								   window.location.reload();
								});
							}else{
							    global_obj.win_alert(data.msg);
							}
						}
					}, 'json');
				}
			}
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
		turntable_obj.share_init();
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
	},
	
	share_init:function(){
		global_obj.share_init({
			'img_url':'/api/images/turntable/share_img.jpg',
			'img_width':100,
			'img_height':100,
			'link':window.location.href+'&Share=1',
			'desc':'点击参与抽奖',
			'title':document.title
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
		$('#share_btn').click(function(){
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