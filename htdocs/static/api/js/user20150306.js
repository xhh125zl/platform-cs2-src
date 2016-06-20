var user_obj={
	user_login_init:function(){
		$('#user_form .submit input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#user_form').serialize(), function(data){
				if(data.status==1){
					window.location=data.jump_url;
				}else{
					global_obj.win_alert('错误的用户名或密码，请重新登录！', function(){
						$('#user_form .submit input').attr('disabled', false)
						$('#user_form input[name=Password]').val('');
					});
				};
			}, 'json');
		});
	},
	
	user_create_init:function(){
		$('#user_form .submit input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			if($('#user_form input[name=Password]').size()){	//微信认证号没有密码这一项
				if($('#user_form input[name=Password]').val()!=$('#user_form input[name=ConfirmPassword]').val()){
					global_obj.win_alert('两次输入的密码不一致，请重新输入登录密码！', function(){
						$('#user_form input[name=Password]').val('').focus();
						$('#user_form input[name=ConfirmPassword]').val('');
					});
					return false;
				}
			}
			
			var Mobile=$('#user_form input[name=Mobile]').val();
			if(Mobile=='' || Mobile.length!=11){
				global_obj.win_alert('请正确填写手机号码！', function(){
					$('input[name=Mobile]').focus();					
				});
				return false;
			}
			
			$(this).attr('disabled', true);
			$.post('?', $('#user_form').serialize(), function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						window.location=data.url;		
					});					
				}else{
					global_obj.win_alert(data.msg, function(){
						history.back();
					});
				}
			}, 'json');
		});
		
		$('#user_form .sms_button').click(function(){
			var Mobile=$('input[name=Mobile]').val();
			if(Mobile=='' || Mobile.length!=11){
				global_obj.win_alert('请正确填写手机号码！', function(){
					$('input[name=Mobile]').focus();
				});
			}else{
				$(this).attr('disabled', true);
				var time=0;
				time_obj=function(){
					if(time>=30){
						$('#user_form .sms_button').val('获取验证码').attr('disabled', false);
						time=0;
						clearInterval(timer);
					}else{
						$('#user_form .sms_button').val('重新获取('+(30-time)+')');
						time++;
					}
				}
				var timer=setInterval('time_obj()', 1000);
				$.get('?d=get_sms&Mobile='+Mobile);
			}
		});
	},
	
	user_profile_init:function(){
		$('#user_form .submit input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('../../ajax/', $('#user_form').serialize(), function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						window.location='../';
					});
				}else{
					global_obj.win_alert(data.msg, function(){
						$('#user_form .submit input').attr('disabled', false);
					});
				}
			}, 'json');
		});
	},
	
	user_payword_init:function(){
		$('#user_form .submit input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			if($('#user_form input[name=PayPassword]').val()!=$('#user_form input[name=QPayPassword]').val()){
				global_obj.win_alert('两次输入的支付密码不一致，请重新输入支付密码！', function(){
					$('#user_form input[name=PayPassword]').val('').focus();
					$('#user_form input[name=QPayPassword]').val('');
				});
				return false;
			}
			
			$(this).attr('disabled', true);
			$.post('../ajax/', $('#user_form').serialize(), function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						window.location='../';
					});
				}else{
					global_obj.win_alert(data.msg, function(){
						$('#user_form .submit input').attr('disabled', false);
					});
				}
			}, 'json');
		});
	},
	
	user_paymoney_init:function(){
		$('#user_form .submit input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};			
			$(this).attr('disabled', true);
			$.post('../ajax/', $('#user_form').serialize(), function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						window.location='../';
					});
				}else{
					global_obj.win_alert(data.msg, function(){
						$('#user_form .submit input').attr('disabled', false);
					});
				}
			}, 'json');
		});
	},
	
	user_charge_init:function(){
		$('#user_form .submit input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};			
			$(this).attr('disabled', true);
			$.post('../ajax/', $('#user_form').serialize(), function(data){
				if(data.status==1){
					window.location=data.url;
				}else{
					global_obj.win_alert(data.msg, function(){
						$('#user_form .submit input').attr('disabled', false);
					});
				}
			}, 'json');
		});
	},
	
	user_complete_init:function(){
		$('#complete_form .submit input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			var Mobile=$('#complete_form input[name=Mobile]').val();
			if(Mobile=='' || Mobile.length!=11){
				global_obj.win_alert('请正确填写手机号码！', function(){
					$('input[name=Mobile]').focus();					
				});
				return false;
			}
			
			$(this).attr('disabled', true);
			$.post('../ajax/', $('#complete_form').serialize(), function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						window.location=$('#httphref').val();
					});
				}else{
					global_obj.win_alert(data.msg, function(){
						$('#complete_form .submit input').attr('disabled', false);
					});
				}
			}, 'json');
		});
	},
	
	card_init:function(){
		$('#card .sign').click(function(){
			$(this).html('签到中...');
			$.post('../ajax/','action=sign', function(data){
				if(data.status==1){
					$('#card .sign').html('今天已签到');
					$('#card .sign').off();
					$('#card .intergral').html('我的积分：'+data.integral);
				}else{
					$('#card .sign').html('签到失败');
				};
			}, 'json');
		});	
		
		$('#card .benefits_btn').click(function(){
			$('#card .benefits').slideToggle();
			$('#card .benefits_btn span:last').removeClass().addClass($('#card .benefits').is(':hidden')?'jt_up':'jt_down');
		});
	},
	
	my_init:function(){
		$('.modify_password').click(function(){
			var o=$(this);
			global_obj.div_mask();
			$('#modify_password_div').show();
			$('#modify_password_div .cancel').off().click(function(){
				global_obj.div_mask(1);
				$('#modify_password_div').hide();
			});
			
			$('#modify_password_form .submit').off().click(function(){
				if(global_obj.check_form($('#modify_password_form input[notnull]'))){return false};
				if($('#modify_password_form input[name=Password]').val()!=$('#modify_password_form input[name=ConfirmPassword]').val()){
					global_obj.win_alert('登录密码与确认密码不匹配，请重新输入！', function(){
						$('#modify_password_form input[name=Password]').focus();
					});
					return false;
				}
				
				$(this).attr('disabled', true);
				$.post('../ajax/', $('#modify_password_form').serialize()+'&action=modify_password', function(data){
					if(data.status==1){
						global_obj.win_alert(data.msg, function(){
							global_obj.div_mask(1);
							$('#modify_password_div').hide();
							$('#modify_password_form .submit').attr('disabled', false);
							$('#modify_password_form input[name=YPassword], #modify_password_form input[name=Password], #modify_password_form input[name=ConfirmPassword]').val('');
						});
					}else{
						global_obj.win_alert(data.msg, function(){
							$('#modify_password_form .submit').attr('disabled', false);
						});
					};
				}, 'json');
			});
		});
		
		$('.modify_mobile').click(function(){
			var o=$(this);
			global_obj.div_mask();
			$('#modify_mobile_div').show();
			$('#modify_mobile_div .cancel').off().click(function(){
				global_obj.div_mask(1);
				$('#modify_mobile_div').hide();
			});
			$('#modify_mobile_form .submit').off().click(function(){
				if(global_obj.check_form($('#modify_mobile_form input[notnull]'))){return false};
				$(this).attr('disabled', true);
				$.post('../ajax/', $('#modify_mobile_form').serialize()+'&action=modify_mobile', function(data){
					if(data.status==1){
						global_obj.win_alert(data.msg, function(){
							global_obj.div_mask(1);
							$('#modify_mobile_div').hide();
							$('#modify_mobile_form .submit').attr('disabled', false);
							$('#modify_mobile_form input[name=MobileCheck]').val($('#modify_mobile_form input[name=Mobile]').val());
						});
					}else{
						global_obj.win_alert(data.msg, function(){
							$('#modify_mobile_form .submit').attr('disabled', false);
						});
					};
				}, 'json');
			});
			
			$('#modify_mobile_form .sms_button').off().click(function(){
				var Mobile=$('input[name=Mobile]').val();
				if(Mobile=='' || Mobile.length!=11){
					global_obj.win_alert('请正确填写手机号码！', function(){
						$('input[name=Mobile]').focus();
					});
				}else{
					$(this).attr('disabled', true);
					var time=0;
					time_obj=function(){
						if(time>=30){
							$('#modify_mobile_form .sms_button').val('获取验证码').attr('disabled', false);
							time=0;
							clearInterval(timer);
						}else{
							$('#modify_mobile_form .sms_button').val('重新获取('+(30-time)+')');
							time++;
						}
					}
					var timer=setInterval('time_obj()', 1000);
					$.get('?d=get_sms&Mobile='+Mobile);
				}
			});
		});
	},
	
	my_address_init:function(){
		$('#user_form .submit_btn').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post($('#user_form').attr('action')+'ajax/', $('#user_form').serialize(), function(data){
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						window.location=$('#user_form').attr('action')+'my/address/';
					});
				}else{
					global_obj.win_alert(data.msg, function(){
						$('#user_form .submit input').attr('disabled', false);
					});
				}
			}, 'json');
		});
	},
	
	integral_init:function(){
		$('#integral_header .sign').click(function(){
			$(this).html('签到中');
			$.post('../ajax/','action=sign', function(data){
				if(data.status==1){
					$('#integral_header .sign').html('已签到').off().removeClass().addClass('sign_ok');
					$('#integral_header .l span').html(data.integral);
					$('#integral_header .r span').html(parseInt($('#integral_header .r span').html())+1);
				}else{
					$('#integral_header .sign').html('签到失败');
				};
			}, 'json');
		});	
		
		$('#integral_get_use div').click(function(){
			var o=$(this);
			global_obj.div_mask();
			$('.pop_form').show();
			$('.pop_form .cancel').off().click(function(){
				global_obj.div_mask(1);
				$('.pop_form').hide();
			});
			$('.pop_form h1').html(o.html());
			$('.pop_form input:text').attr('placeholder', o.html());
			$('.pop_form input[name=RecordType]').val(o.html());
			
			$('#integral_form .submit').off().click(function(){
				if(global_obj.check_form($('*[notnull]'))){return false};
				$(this).attr('disabled', true);
				$.post('../ajax/', $('#integral_form').serialize(), function(data){
					if(data.status==1){
						global_obj.win_alert(data.msg, function(){
							$('#integral_form .submit').attr('disabled', false);
							window.location.reload();
						});
					}else{
						global_obj.win_alert(data.msg, function(){
							$('#integral_form .submit').attr('disabled', false);
						});
					};
				}, 'json');
			});
		});
	},
	
	message_init:function(){
		$('#message .list').click(function(){
			var o=$(this);
			if(o.attr('Display')==0){
				o.attr('Display', 1);
				$.post('../ajax/','action=get_message_contents&MessageID='+o.attr('MessageID'), function(data){
					if(data.status==1){
						o.after('<div class="contents">'+data.msg+'</div>');
						o.removeClass().addClass('list is_read').find('div').addClass('up').html('');
						o.next().slideToggle();
						var not_read=$('#message .not_read').size();
						if(not_read<=0){
							$('#footer_user font').remove();
						}else{
							$('#footer_user font').html(not_read);
						}
					}else{
						global_obj.win_alert(data.msg, function(){
							o.attr('Display', 0);
						});
					};
				}, 'json');
			}else{
				$(this).attr('Display', 0);
				o.next().slideToggle(function(){
					o.next().remove();
					o.find('div').removeClass();
				});
			}
		});
	},
	
	gift_init:function(){
		$('#gift .item img').click(function(){
			$(this).parent().parent().find('h3').slideToggle();
		});
		
		var address_display=function(o){
			if(o.attr('Shipping')==1){
				$('#gift_form .address').show();
				$('#gift_form .btn').css('padding-top', 0);
				var AddressID=parseInt($('#gift_form input[name=AddressID]:checked').val());
				if(AddressID==0 || isNaN(AddressID)){
					$('#gift_form .address dl').show();
					$('.pop_form').css('top', 0);
				}else{
					$('#gift_form .address dl').hide();
					$('.pop_form').css('top', 50);
				}
			}else{
				$('#gift_form .address').hide();
				$('#gift_form .btn').css('padding-top', 6);
				$('.pop_form').css('top', 50);
			}
		}
		
		$('#gift .get').click(function(){
			var o=$(this);
			global_obj.div_mask();
			$('.pop_form').show();
			$('.pop_form .cancel').off().click(function(){
				global_obj.div_mask(1);
				$('.pop_form').hide();
			});
			$('#gift_form .integral span').html(o.attr('Integral'));
			$('#gift_form input[name=AddressID]').off().click(function(){address_display(o);});
			address_display(o);
			
			$('#gift_form .submit').off().click(function(){
				if(o.attr('Shipping')==1){
					var AddressID=parseInt($('#gift_form input[name=AddressID]:checked').val());
					if(AddressID==0 || isNaN(AddressID)){
						if(global_obj.check_form($('*[notnull]'))){return false};
					}
				}
				$(this).attr('disabled', true);
				$.post('../../ajax/', $('#gift_form').serialize()+'&action=gift_change&GiftID='+o.attr('GiftID'), function(data){
					if(data.status==1){
						global_obj.win_alert(data.msg, function(){
							window.location='../../gift/';
						});
					}else{
						global_obj.win_alert(data.msg, function(){
							$('#gift_form .submit').attr('disabled', false);
						});
					};
				}, 'json');
			});
		});
	},
	
	coupon_init:function(){
		$('#coupon .use').click(function(){
			var o=$(this);
			global_obj.div_mask();
			$('.pop_form').show();
			$('.pop_form .cancel').off().click(function(){
				global_obj.div_mask(1);
				$('.pop_form').hide();
			});
			
			$('#coupon_use_form .submit').off().click(function(){
				if(global_obj.check_form($('*[notnull]'))){return false};
				$(this).attr('disabled', true);
				$.post('../ajax/', $('#coupon_use_form').serialize()+'&action=use_coupon&CouponID='+o.attr('CouponID'), function(data){
					if(data.status==1){
						global_obj.win_alert(data.msg, function(){
							window.location=$('#coupon .t_list a:first').attr('href');
						});
					}else{
						global_obj.win_alert(data.msg, function(){
							$('#coupon_use_form .submit').attr('disabled', false);
						});
					};
				}, 'json');
			});
		});
		
		$('#coupon .p img').click(function(){
			$(this).parent().parent().find('h3').slideToggle();
		});
		
		$('#coupon .get').click(function(){
			var o=$(this);
			o.html('领取中...');
			$.post('../../ajax/', 'action=get_coupon&CouponID='+o.attr('CouponID'), function(data){
				o.html('领取');
				if(data.status==1){
					global_obj.win_alert(data.msg, function(){
						window.location=$('#coupon .t_list a:first').attr('href');
					});
					
				}else{
					global_obj.win_alert(data.msg);
				};
			}, 'json');
		});
	}
}