var shop_obj={
	biz_edit_init:function(){
		$('#biz_edit').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#biz_edit input:submit').attr('disabled', true);
			return true;
		});
	},
	
	products_list_init:function(){
		$("#search_form .output_btn").click(function(){
			window.location='/biz/output.php?'+$('#search_form').serialize()+'&type=product_gross_info';
		});
	},
	
	orders_init:function(){
		$("#search_form .output_btn").click(function(){
			window.location='/biz/output.php?'+$('#search_form').serialize()+'&type=order_detail_list';
		});
		
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
		
		$('#order_list a.send_print').click(function(){
			var orderid = $(this).attr("ret");
			$('#select_template #linkid').attr("value",orderid);
			$('#select_template').leanModal();
		});
		
		$('#submit_form #checkall').click(function(){
			if($(this).is(":checked")){
				$("#submit_form input[name=OrderID\\[\\]]").attr("checked","true");
			}else{
				$("#submit_form input[name=OrderID\\[\\]]").removeAttr("checked");
			}			
		});
		
		$("#submit_form input[name=OrderID\\[\\]]").click(function(){
			if($(this).is(":checked")){
				var i = 1;
				$("#submit_form input[name=OrderID\\[\\]]").each(function(index, element) {
                    if(!$(this).is(":checked")){
						i=0;
					}
                });
				if(i==1){
					$("#submit_form #checkall").attr("checked","true");
				}
			}else{
				$("#submit_form #checkall").removeAttr("checked");
			}
		});
		
		$("#submit_form label").click(function(){
			var j = 0;
			$("#submit_form input[name=OrderID\\[\\]]").each(function(index, element) {
                if($(this).is(":checked")){
					j=1;
				}
            });
			if(j==1){
				$('#select_template #linkid').attr("value",0);
				$('#select_template').leanModal();
			}else{
				alert("请选择订单");
				return false;
			}
			
		});
		
		$("#select_template label").click(function(){
			var templateid = $("#select_template #templates").val();
			if(templateid==0 || templateid == ""){
				alert("请选择运单模板");
				return false;
			}
			
			var linkid = $('#select_template #linkid').attr("value");
			if(linkid==0){
				$("#submit_form input[name=templateid]").attr("value",templateid);				
				$("#select_template,#lean_overlay").hide();
				window.open('/biz/orders/send_print.php?'+$('#submit_form').serialize());
			}else{
				$("#select_template,#lean_overlay").hide();
				window.open('/biz/orders/send_print.php?templateid='+templateid+'&OrderID='+linkid);
			}
			
		});
	},
	
	print_orders_init:function(){
		
		$('.r_nav, .ui-nav-tabs').hide();
		$('html,body').css('background','none');
		$('.iframe_content').removeClass('iframe_content');	
		$('.print_area input[name=print_close]').click(function(){
			$('#print_cont').fadeOut();
		});
		
		$('.print_area input[name=print_go]').click(function(){
			window.print();
		});
		$('.print_area input[name=print_close]').click(function(){
			$(window.parent.document).find('#print_cont').fadeOut();
		});
		
	},
	
	sales_init:function(){
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
		
		$("#search_form .output_btn").click(function(){
			window.location='/biz/output.php?'+$('#search_form').serialize()+'&type=sales_record_list';
		});
	},
	
	orders_send:function(){
		$('#order_send_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#order_send_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	backorder_edit:function(){
		$("#reject_btn").click(function(){
			$("#btns").hide();
			$("#reject").show();
		});
		
		$("#goback_reject").click(function(){
			$("#reject").hide();
			$("#btns").show();
		});
		
		$('#reject_form').submit(function(){
			if(global_obj.check_form($('#reject_form *[notnull]'))){return false};
			$('#reject_form input:submit').attr('disabled', true);
			return true;
		});
		
		$("#recieve_btn").click(function(){
			$("#btns").hide();
			$("#recieve").show();
		});
		
		$("#goback_recieve").click(function(){
			$("#recieve").hide();
			$("#btns").show();
		});
		
		$('#recieve_form').submit(function(){
			if(global_obj.check_form($('#recieve_form *[notnull]'))){return false};
			$('#recieve_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	category_init:function(){
		$('#category_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#category_form input:submit').attr('disabled', true);
			return true;
		});		
	},
	
	products_attr_edit_init:function(){
		shop_obj.products_attr_cu();
		var value  = parseInt($(".Attr_Input_Type").val());
		if(value == 0||value == 2){
			$("#Attr_Values").removeAttr('notnull');			
		}else{
			$("#Attr_Values").attr('notnull','');
		}
		$('#shop_attr_edit_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
		});
	
	},
	
	products_attr_cu:function(){
		//产品属性create 和  update 共用属性
		
		//如录入方式为手工录入和多行文本框，则禁用可选值textarea
		$(".Attr_Input_Type").click(function(){
			var value  = parseInt($(this).val());
			$("#Attr_Values").removeAttr('style');
			if(value == 0||value == 2){
				$("#Attr_Values").attr({"disabled":true});
				
			}else{
				$("#Attr_Values").removeAttr('disabled');
			}
		
		});
		
	},
	
	products_edit_init:function(){		
		$("#product_add_form").submit(function(){
				var data_array = $("#product_add_form").serialize();
				if(data_array.indexOf("&Category")==-1){
					alert("请选择分类！");
					return false;
				}
				if(data_array.indexOf("JSON")==-1){
					alert("请上传图片！");
					return false;
				}		
			if(global_obj.check_form($('*[notnull]'))){return false};
		});
		shop_obj.products_form_init();
	},
	
	products_form_init:function(){
		$('a[href=#select_category]').each(function(){
			$(this).click(function(){
				$('#select_category').leanModal();
			});
		});
		
		var category = function(object1){
			var c_k = true;
			object1.parent().find("input").each(function(){
				if(!$(this).attr("checked")){
					c_k = false;
				}
			});
			if(c_k){
				object1.parent().prev("dt").find("input").attr("checked",true);
			}else{
				object1.parent().prev("dt").find("input").removeAttr("checked"); 
			}
		};
		
		$("#select_category .catlist input:checkbox").click(function(){
			var flag = $(this).attr("rel");
			if(flag == 1){
				if($(this).is(':checked')){
					$(this).parent().next("dd").find("input").attr("checked",true);
				}else{
					$(this).parent().next("dd").find("input").attr("checked",false);
				}
			}else{
				category($(this).parent());
			}
		});
		
		$("#products #Type_ID").change(function(){
			
			var TypeID = $("#Type_ID").val();
			var ProductsID = 0;
			
			if(TypeID.length > 0){
				$.ajax({
					type	: "POST",
					url		: "ajax.php",
					data	: "action=get_attr&UsersID="+$("#UsersID").val()+"&TypeID="+$("#Type_ID").val()+"&ProductsID="+$("#ProductsID").val(),
					dataType: "json",
					async : false,
					success	: function(data){
						
						if(data.content){
							$("#attrs").css("display","block");
							$("#attrs").html(data.content);
						}else{
							alert("暂无属性！");
						}
					}
				});	
			} else {
				
			 $("#attrs").html('');
			   $("#Category").focus();
				
			}
		});
		
	},	
}