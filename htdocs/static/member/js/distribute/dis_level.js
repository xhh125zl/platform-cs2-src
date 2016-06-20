var dis_level={
	level_init:function(){
	    $('a#close_layer').click(function(){
			$.get('ajax.php','action=get_dis_level&type='+$("#dis_type").attr('value'),function(data){
				$('#level_intro', parent.document).html(data.html);
				var index = parent.layer.getFrameIndex(window.name);
				parent.layer.close(index);
			},'json');				
		});
		
		if($('input[name=count]').attr('value')>0){
			$('#level_has', parent.document).val('1');
		}
	},
	
	level_edit:function(){		
		$("input[name=Come_Type]").click(function(){
			if($(this).attr("value") == 3){
				$("#type_1").hide();
			}else{
				$("#type_1").show();
			}
		});
		//升级设置选择按钮
		$("input[name=Update_Type]").click(function(){
			if($(this).attr("value") == 0){
				$("#update_div_1").hide();
				$("#update_div_0").show();
			}else{
				$("#update_div_0").hide();
				$("#update_div_1").show();
			}
		});
		//商品选择模块
		$("input[name=Fanwei]").click(function(){
			if($(this).attr("value") == 0){
				$(this).parent().children(".products_option").hide();
			}else{
				$(this).parent().children(".products_option").show();
			}
		});
		
		$(".products_option .search_div .button_search").click(function(){
			var object = $(this).parent();
			var catid = object.children("select").val()
			var keyword = object.children("input").val();
			
			var param = {cate_id:catid,keyword:keyword,action:'get_product'};
			$.get('?',param,function(data){
				object.parent().children(".select_items").children(".select_product0").html(data);
			});
		});
		
		$(".products_option .select_items .button_add").click(function(){
			var text = $(this).parent().children(".select_product0").find("option:selected").text();
			var value = $(this).parent().children(".select_product0").find("option:selected").val();
			if($(this).parent().children(".select_product1").find("option:contains("+text+")").length == 0 && typeof(value)!='undefined'){
				$(this).parent().children(".select_product1").append("<option value='"+value+"'>"+text+"</option>");
			}
			
			var strids = $(this).parent().children("input").val();
			if(typeof(value)!='undefined'){
				if(strids == ''){
					$(this).parent().children("input").val(','+value+',');
				}else{
					strids = strids.replace(','+value+',',",");
					$(this).parent().children("input").val(strids+value+',');
				}
			}
		});
		
		$(".products_option .options_buttons .button_remove").click(function(){//移除选项		
			var select_obj = $(this).parent().parent().children(".select_items").children(".select_product1").find("option:selected");
			var input_obj = $(this).parent().parent().children(".select_items").children("input");
			var strids = input_obj.val();
			select_obj.each(function(){
				$(this).remove();
				strids = strids.replace(','+$(this).val()+',',",");
			});
			if(strids==','){
				strids = '';
			}
			input_obj.val(strids);
		});
		
		$(".products_option .options_buttons .button_empty").click(function(){//清空选项
			 $(this).parent().parent().children(".select_items").children(".select_product1").empty();
			 $(this).parent().parent().children(".select_items").children("input").val('');
		});
		
		//商品选择模块end
		
	    $('#level_form').submit(function(){
			if(global_obj.check_form($('#level_form *[notnull]'))){
				return false;
			};
			$('#level_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	card_init:function(){
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
	},
	
	card_edit:function(){
		$('#card_form input:submit').click(function(){
			if(global_obj.check_form($('#card_form *[notnull]'))){
				return false;
			};
		});
	}
}