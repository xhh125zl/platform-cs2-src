// JavaScript Document

jQuery.validator.addMethod("isMobile", function(value, element) {       
    	var length = value.length;   
    	var mobile = /^(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;   
     return this.optional(element) || (length == 11 && mobile.test(value));       
}, "格式错"); 

var mobile_bind_obj = {
	
	init:function(){
		
		$('#bind_mobile_form').validate({
				rules:{
					User_Mobile:{remote:{
						url:base_url+'api/user/ajax.php?action=check_mobile_exist&UsersID='+Users_ID,
						type:"get"
					}}
				},
				messages:{
					User_Mobile:{remote:'已被占用!'},					
					Password_Confirm:{equalTo:'两次输入不一致'}
				},				
                onfocusout: function(element) {
                    this.element(element);
                },
                errorPlacement: function(error, element) {
                    if (element.is(":checkbox")) {
                        error.appendTo(element.parent());
                    } else {
                        error.appendTo(element.parent());
                    }

                } 
            });
			
			var validCode=true;
			var time = 180;
			var tip_template = '<div class="alert alert-tip_type" id="bind-mobile-alert"><a href="#" class="close" data-dismiss="alert">&times;</a>tip_content</div>'
		
			$("#send-rancode").click(function(){
		
				if(!$("#User_Mobile").valid()){
					return false;
				}
				
				var url = user_url+'ajax/'

				var param = {action:'send_short_msg',mobile:$("#User_Mobile").val()};
				$.post(url,param,function(data){
					if(data.status==1){
						var code=$('#send-rancode');
						
						//重发倒数
						if (validCode) {
							validCode=false;
							code.addClass("disabled");
							var t=setInterval(function  () {
							time--;
							code.html(time+"秒后重新发");
							if (time==0) {
								clearInterval(t);
								code.removeClass('disabled');	
								code.html("重新发送验证码");
								validCode=true;
								}
							},1000)
						}	
					
						
					}else if(data.status == 0){
					
						var tip = tip_template.replace(/tip_type/,'danger').replace(/tip_content/,data.msg);
						
						$("#bind_mobile_panel").before(tip);
					}

				},'json');
				
			
			});
	
	}
}