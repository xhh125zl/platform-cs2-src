// JavaScript Document

var distribute_obj = {
	
	init:function(){
		
		//点击提现按钮
		$("#withdraw_btn").click(function(){
			window.location.href = $(this).attr("link");
		});
		
        //展开分组列表
        $(".item_group_account_btn").click(function() {
			
            var status = $(this).attr("status");
          
		    if (status == 'close') {
              
                $(this).next().css({
                    display: 'block'
                });
                $(this).attr("status", "open");
            } else {
               
                $(this).next().css({
                    display: 'none'
                });
                $(this).attr("status", "close");
            }


        });
		
		$('a#mygroup').click(function(){
			var status = $(this).attr("status");
			if(status == "close" ){
				$("#firstchild").css({
					display: 'block'
				});
				$(this).attr("status", "open");
			}else{
				$("#firstchild").css({
					display: 'none'
				});
				$(this).attr("status", "close");
			}
		});
		
		//展开二维码
        $("#open_qrcode_title").click(function() {
			
            var status = $(this).attr("status");
            
			if (status == 'close') {
                $(this).find("span.fa").removeClass("fa-chevron-up").addClass("fa-chevron-down");
                $(this).next().css({
                    display: 'block'
                });
                $(this).attr("status", "open");
            } else {
                $(this).find("span.fa").removeClass("fa-chevron-down").addClass("fa-chevron-up");
                $(this).next().css({
                    display: 'none'
                });
                $(this).attr("status", "close");
            }


        });

	
	},
	
	withdraw_page:function(){
		
		//提现申请
        $("#btn-withdraw").click(function() {

           
			if($("input[name='money']").val().length == 0){
			   
			   $("#withdraw-money").after("<label id=\"withdraw_tip\" class=\"fc_red\">不可为空</label>");
                return false;
			}
			
            var balance = parseFloat($("#balance").val());
            var money = parseFloat($("input[name='money']").val());
			withdraw_limit = parseFloat(withdraw_limit);
			
			
			if(money <= 0){
				$("#withdraw-money").after("<label id=\"withdraw_tip\" class=\"fc_red\">必须大于0</label>");
                return false;
			}
			
			if(money < withdraw_limit){
				$("#withdraw-money").after("<label id=\"withdraw_tip\" class=\"fc_red\">最小为"+withdraw_limit+"元</label>");
                return false;
			}
			
            var url = $("#withdraw-form").attr("action");
            var param = $("#withdraw-form").serialize();

            $.post(url, param, function(data) {
                if (data.status == 1) {
                    global_obj.win_alert(data.msg, function() {
                        window.location.reload();
                        window.location.href = base_url+'api/'+UsersID+'/distribute/';
                    });
                }else{
					
					 global_obj.win_alert(data.msg, function() {
                      $("#withdraw-money").val('').focus();
                    });
					
				}
            }, 'json');

        });
		
		
	
		/*更改提现方法之后激发*/
		$("#User_Method_Name").change(function(){
		
		   var method_type = $(this).find("option:selected").attr("method_type");
		   $("#Method_Type").attr('value',method_type);
		   $("li.bank_card").hide();
		   if(method_type == 'bank_card'){
			  $("li.bank_card").show();
		   }else if(method_type == 'alipay'){
			  $("li.bank_card").show();
			  $("li#bank_position").hide();
		   }
		   
		});
		/*添加提现方法表单验证*/		
		$("#bank_card_form").validate({
				rules: {
					Account_Name: {required:true},
					Account_Val: {required:true},
				},
            
				onfocusout: function(element) {
					this.element(element);
				}
		});
		
		/*清除提示*/
	    $("#withdraw-money").keydown(function() {
            $("#withdraw_tip").remove();
            $("#withdraw-money").css({
                border: '0px solid red'
            });
        });
		
		/*显示添加提现方法*/
		$("#add-card").click(function(){
			$("#add_card_panel").css("display","block");	
		});
		
		$("#btn-addcard").click(function(){
			var url = $("#bank_card_form").attr("action");
            var param = $("#bank_card_form").serialize();
			var method_type1 = $("#bank_card_form #User_Method_Name").find("option:selected").attr("method_type");
			var flag = true;
			if(method_type1 != 'wx_hongbao' && method_type1 != 'wx_zhuanzhang'){
				flag = $("#bank_card_form").valid();
			}
          	if(flag){
            	$.post(url, param, function(data) {
                	if(data.status == 1) {
                	   global_obj.win_alert('添加提现方法成功', function() {
                       window.location.href = base_url + 'api/' + UsersID + '/distribute/withdraw/';
				
						});
                 	}
					
            	 }, 'json');

           }else{
           		return false;
           }
		
		});
	},
	
	apply_distribute:function(){
	
	   $("#join-distribute-form").validate({
            rules: {
                Real_Name: {required:true},
				User_Mobile:{required:true},
            },
            
            onfocusout: function(element) {
                this.element(element);
            }
        });
		
        /*成为分销商申请*/
        $("#submit-btn").click(function() {

            var url = $("#join-distribute-form").attr("action");
            var param = $("#join-distribute-form").serialize();
          	if($("#join-distribute-form").valid()){

            	$.post(url, param, function(data) {
                	if (data.status == 1) {
                	global_obj.win_alert('您已经成为分销商', function() {
                       window.location.href = base_url + 'api/' + UsersID + '/distribute/';
                    });
					
                 	}
            	 }, 'json');

           }else{
           	return false;
           }
           
        });
	},
	bank_card_manage:function(){
		$("a.remove-card").click(function(){
			var url = base_url+'api/'+UsersID+'/distribute/ajax/';
			var param = {action:'delete_user_withdraw_method',method_id:$(this).attr("data-method-id")};
			
			$.post(url, param, function(data) {
                	if (data.status == 1) {
                	global_obj.win_alert('您已成功删除此提现方法', function() {
                       window.location.href = base_url + 'api/' + UsersID + '/distribute/bankcards/';
                    });
                 	}
            }, 'json');
			
		});
	},
	withdraw_record_init:function(){
		//展开提现记录
        $("a.record-title").click(function() {
            var status = $(this).attr("status");
			
            if (status == 'close') {
                $(this).find("span.fa").removeClass("fa-chevron-up").addClass("fa-chevron-down");
                
				$(this).next().css({
                    display: 'block'
                });
				
                $(this).attr("status", "open");
            } else {
                $(this).find("span.fa").removeClass("fa-chevron-down").addClass("fa-chevron-up");
                $(this).next().css({
                    display: 'none'
                });
                $(this).attr("status", "close");
            }


        });		
		
		$("a.send_hongbao").click(function() {
			$(this).hide();
            var recordid = $(this).attr("ret");
			var url = base_url+'api/'+UsersID+'/distribute/ajax/';
            var param = {action:'send_hongbao',RecordID:recordid};
            
            $.post(url, param, function(data) {
                global_obj.win_alert(data.msg,function() {
					window.location.reload();
				});
            }, 'json');
        });
	},
    pro_file_init:function(){
        
       $("#get_ex_btn").click(function(){
            var url = base_url+'api/'+UsersID+'/distribute/ajax/';
            var param = {action:'get_ex_bonus'};
            
            $.post(url, param, function(data) {
                    if (data.status == 1) {
                    global_obj.win_alert('您已成功获奖金',function() {
                       window.location.href = base_url + 'api/' + UsersID + '/distribute/';
                    });
                    }
            }, 'json');
       });

    }


}