var config_obj={
	base_config:function(){
		$.get('ajax.php','action=get_dis_level&type='+$("select[name=Distribute_Type]").attr('value'),function(data){
			$('#level_intro').html(data.html);
		},'json');
		
		$("select[name=Dis_Level]").change(function(){
			//手机端级别联动
			for(var i=1;i<=$(this).attr('value');i++){
				if(i==1){
					$("select[name=Dis_Mobile_Level]").html("<option value='"+i+"'>"+i+"级</option>");
				}else{
					$("select[name=Dis_Mobile_Level]").append("<option value='"+i+"'>"+i+"级</option>");
				}
			}
			
			//分销商级别设置弹框
			var level_has = $('#level_has').attr('value');
			var level = $('#level').attr('value');
			var type = $('select[name=Distribute_Type]').attr('value');
			if(level_has>0 && level!=$(this).attr('value')){
				global_obj.create_layer('分销商级别设置', 'level.php?level='+$(this).attr('value')+'&type='+type,1000,500,1,1);	
			}
		});
		
		$("select[name=Distribute_Type]").change(function(){
			//分销商级别设置弹框
			var level = $("select[name=Dis_Level]").attr('value');
			var type = $(this).attr('value');
			global_obj.create_layer('分销商级别设置', 'level.php?level='+level+'&type='+type,1000,500,1,1);
		});
		
		$("a.setting_level_btn").click(function(){
			//分销商级别设置弹框
			var level = $("select[name=Dis_Level]").attr('value');
			var type = $('select[name=Distribute_Type]').attr('value');
			global_obj.create_layer('分销商级别设置', 'level.php?level='+level+'&type='+type,1000,500,1,1);
		});
	},
	
	withdraw_config:function(){
		$('select[name=Type]').change(function(){
			for(var i=1; i<4; i++){
				$('#type_'+i).hide();
			}
			$('#type_'+this.value).show();
		});
		
		config_obj.products_select();
		
		$('#distribute_config_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#distribute_config_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	other_config:function(){
		//返本开关		
		$("input[name=Fanben]").click(function(){
			if($(this).attr("value") == 0){
				$(this).parent().children("div").hide();
				$('#fanben_limit').hide();
			}else{
				$(this).parent().children("div").show();
				$('#fanben_limit').show();
			}
		});
		
		$('select[name=Type]').change(function(){
			for(var i=1; i<2; i++){
				$('#type_'+i).hide();
			}
			$('#type_'+this.value).show();
		});
		
		//复销开关		
		$("input[name=Fuxiao]").click(function(){
			if($(this).attr("value") == 0){
				$(this).parent().children("div").hide();
			}else{
				$(this).parent().children("div").show();
			}
		});
		
		config_obj.products_select();
		/*edit in 20160409*/
		$("input[name=Dis_Agent_Type]").click(function(){
			var commo_agetopen = "<input type=\"radio\" id=\"s_0\" name=\"Agent_Rate[Agentenable]\" value=\"1\" checked><label for=\"s_0\">开启</label>&nbsp;&nbsp;<input type=\"radio\" id=\"s_1\" name=\"Agent_Rate[Agentenable]\" value=\"0\"><label for=\"s_1\">关闭</label>";
			var nameshow_open = "<input type=\"radio\" id=\"d_0\" name=\"Agent_Rate[Nameshow]\" value=\"1\" checked><label for=\"d_0\">显示</label>&nbsp;&nbsp;<input type=\"radio\" id=\"d_1\" name=\"Agent_Rate[Nameshow]\" value=\"0\"><label for=\"d_1\">隐藏</label>";
			var regwhere_proaget ='';
			var regwhere_citaget ='';
			var regwhere_couaget ='';
			var type = $(this).attr('value');
			if(type == 0){				
				$("#Agent_Rate_Row").hide();
				$("#Agent_Rata_Row").hide();
				$("#Agent_Ratb_Row").hide();
				$("#Agent_Ratc_Row").hide();
				$("#Agent_Ratd_Row").hide();
				$("#Agent_Rate_Input").html('');
				$("#Agent_Rata_Input").html('');
				$("#Agent_Ratb_Input").html('');
				$("#Agent_Ratc_Input").html('');
				$("#Agent_Ratd_Input").html('');
			}else if(type == 1){
				//省				
				regwhere_proaget += "利润率%<input type=\"text\" name=\"Agent_Rate[pro][Province]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>&nbsp;&nbsp;&nbsp;代理价格<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[pro][Provincepro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull >认证条件：";
				if(level != null){
				regwhere_proaget += "等级<select name=\"Agent_Rate[pro][Level]\"><option value=\"0\" selected>---选择等级---</option>";
				$.each(level,function(key,val){			
				regwhere_proaget += "<option value=\""+val['Level_ID']+"\">"+val['Level_Name']+"</option>";			
		});		
				regwhere_proaget += "</select>";
				}
				if(title != null){
				regwhere_proaget += "&nbsp;&nbsp;&nbsp;爵位<select name=\"Agent_Rate[pro][Protitle]\"><option value=\"0\" selected>---选择爵位---</option>";
				$.each(title,function(key,val){			
				regwhere_proaget += "<option value=\""+key+"\">"+val['Name']+"</option>";
		});
				regwhere_proaget += "</select>";
				}
				regwhere_proaget += "&nbsp;&nbsp;&nbsp;自费金额<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[pro][Selfpro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>&nbsp;&nbsp;&nbsp;团队销售额<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[pro][Teampro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>";

			//市			
				regwhere_citaget += "利润率%<input type=\"text\" name=\"Agent_Rate[cit][Province]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>&nbsp;&nbsp;&nbsp;代理价格<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[cit][Provincepro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull >认证条件：";
				if(level != null){
				regwhere_citaget += "等级<select name=\"Agent_Rate[cit][Level]\"><option value=\"0\" selected>---选择等级---</option>";				
				$.each(level,function(key,val){			
				regwhere_citaget += "<option value=\""+val['Level_ID']+"\">"+val['Level_Name']+"</option>";			
		});		
				regwhere_citaget += "</select>";
				}
				if(title != null){
				regwhere_citaget += "&nbsp;&nbsp;&nbsp;爵位<select name=\"Agent_Rate[cit][Protitle]\"><option value=\"0\" selected>---选择爵位---</option>";
				$.each(title,function(key,val){			
				regwhere_citaget += "<option value=\""+key+"\">"+val['Name']+"</option>";
		});
				regwhere_citaget += "</select>";
				}
				regwhere_citaget += "&nbsp;&nbsp;&nbsp;自费金额<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[cit][Selfpro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>&nbsp;&nbsp;&nbsp;团队销售额<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[cit][Teampro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>";
				
				//县			
				regwhere_couaget += "利润率%<input type=\"text\" name=\"Agent_Rate[cou][Province]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>&nbsp;&nbsp;&nbsp;代理价格<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[cou][Provincepro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull >认证条件：";
				if(level != null){
				regwhere_couaget += "等级<select name=\"Agent_Rate[cou][Level]\"><option value=\"0\" selected>---选择等级---</option>";
				$.each(level,function(key,val){			
				regwhere_couaget += "<option value=\""+val['Level_ID']+"\">"+val['Level_Name']+"</option>";			
		});		
				regwhere_couaget += "</select>";
				}
				if(title != null){
				regwhere_couaget += "&nbsp;&nbsp;&nbsp;爵位<select name=\"Agent_Rate[cou][Protitle]\"><option value=\"0\" selected>---选择爵位---</option>";
				$.each(title,function(key,val){			
				regwhere_couaget += "<option value=\""+key+"\">"+val['Name']+"</option>";
		});
				regwhere_couaget += "</select>";
				}
				regwhere_couaget += "&nbsp;&nbsp;&nbsp;自费金额<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[cou][Selfpro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>&nbsp;&nbsp;&nbsp;团队销售额<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Agent_Rate[cou][Teampro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>";
				
				$("#Agent_Rate_Row").show();
				$("#Agent_Rata_Row").show();
				$("#Agent_Ratb_Row").show();
				$("#Agent_Ratc_Row").show();	
				$("#Agent_Ratd_Row").show();
				$("#Agent_Rate_Input").html(regwhere_proaget);
				$("#Agent_Rata_Input").html(regwhere_citaget);
				$("#Agent_Ratc_Input").html(regwhere_couaget);
				$("#Agent_Ratb_Input").html(commo_agetopen);
				$("#Agent_Ratd_Input").html(nameshow_open);				
			}
		});
		
		$("input[name=Sha_Agent_Type]").click(function(){
			var shaenable_open = "<input type=\"radio\" id=\"q_0\" name=\"Sha_Rate[Shaenable]\" value=\"1\" checked><label for=\"q_0\">开启</label>&nbsp;&nbsp;<input type=\"radio\" id=\"q_1\" name=\"Sha_Rate[Shaenable]\" value=\"0\"><label for=\"q_1\">关闭</label>";
			var regwhere_shaaget ='';			
			var type = $(this).attr('value');		
			if(type == 0){				
				$("#Agent_Ratf_Row").hide();
				$("#Agent_Ratg_Row").hide();				
				$("#Agent_Ratf_Input").html('');
				$("#Agent_Ratg_Input").html('');				
			}else if(type == 1){
				if(level != null){
				regwhere_shaaget += "等级<select name=\"Sha_Rate[sha][Level]\"><option value=\"0\" selected>---选择等级---</option>";
				$.each(level,function(key,val){			
				regwhere_shaaget += "<option value=\""+val['Level_ID']+"\">"+val['Level_Name']+"</option>";			
		});		
				regwhere_shaaget += "</select>";
				}
				if(title != null){
				regwhere_shaaget += "&nbsp;&nbsp;&nbsp;爵位<select name=\"Sha_Rate[sha][Protitle]\"><option value=\"0\" selected>---选择爵位---</option>";
				$.each(title,function(key,val){			
				regwhere_shaaget += "<option value=\""+key+"\">"+val['Name']+"</option>";
		});
				regwhere_shaaget += "</select>";
				}
				regwhere_shaaget += "&nbsp;&nbsp;&nbsp;自费金额<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Sha_Rate[sha][Selfpro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>&nbsp;&nbsp;&nbsp;团队销售额<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Sha_Rate[sha][Teampro]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull>&nbsp;&nbsp;&nbsp;申请价格<strong class=\"red\">（元）</strong><input type=\"text\" name=\"Sha_Rate[sha][price]\" value=\"0\" class=\"form_input\" size=\"3\" maxlength=\"10\" notnull />";
				
				$("#Agent_Ratf_Row").show();
				$("#Agent_Ratg_Row").show();				
				$("#Agent_Ratf_Input").html(regwhere_shaaget);
				$("#Agent_Ratg_Input").html(shaenable_open);
			}
		});
		
		$('#distribute_config_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#distribute_config_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	protitle_config:function(){
		$('#distribute_config_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			var obj = $(".bonus");
			for(i=0;i<obj.length;i++){
				if(i>0 && obj[i].value!=''){
					if((obj[i].value-obj[i-1].value)<0){
						alert('奖励额度应为递增！');
						return false;
					}
				}
			}
			
			$('#distribute_config_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	products_select:function(){
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
	},
	
}