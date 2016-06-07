/**
 * 后台shop物流处理对象
 * @type 
 */
var shipping_obj = {
	 shop_shiping_init:function(){

        //弹出创建快递公司对话框
        $("#create_shipping_btn").click(function() {
			
            $('#mod_create_shipping').leanModal();
        });

        function valid_form(form_selector) {
            $(form_selector).validate({
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

        }

        valid_form('#create_shipping_form');


        //编辑快递公司信息
        $(".shipping_edit_btn").click(function() {

            var Shipping_ID = $(this).attr("shipping-id");
            var param = {
                'Shipping_ID': Shipping_ID,
                'action': 'get_shipping_company_edit_form'
            };

            $.get(base_url + 'member/shop/ajax.php', param, function(data) {
                if (data.status == 1) {
                    $("#shipping_company_edit_content").html(data.content);
                    $('#mod_edit_shipping').leanModal();
                }

            }, 'json');
        });



    },
    tpl_add_init: function() {

        /*全选,取消region内的所有城市*/
        $('input.J_Group').click(function() {
        	
            var checked = $(this).prop('checked');
            var targets = $(this).parent().parent().
            siblings('.province-list').
            find("input.J_Province");

            $(targets).each(function() {
                $(this).prop('checked', checked);
                shipping_obj.sync_check($(this), 'Province');
            });


        });

        /*全选，取消本省内所有城市*/
        $('input.J_Province').click(function() {

            shipping_obj.sync_check($(this), 'Province')
        });

        //城市选中，取消事件
        $('input.J_City').change(function() {
            shipping_obj.sync_check($(this), 'City')

        });

        //展开城市列表
        $("img.trigger").click(function() {
            $('div.ecity ').removeClass('showCityPop');
            $(this).parent().parent().addClass('showCityPop');
        });

        //关闭城市列表
        $("input.close_button").click(function() {
            $(this).parent().parent().parent().removeClass('showCityPop');
        });

        $(".Business_Type").live('click', function() {

            if ($(this).prop('checked')) {

                $(this).parent().siblings().css('display', 'block');
            } else {
                $(this).parent().siblings().css('display', 'none');
            }
            //同步包邮中的业务选择框*/
            shipping_obj.sync_j_service();

        });

        $("#Shipping_ID").live('change', function() {
            var Shipping_ID = $(this).val();
            var By_Method = $("input[name='By_Method']:checked").val();
            shipping_obj.get_deliver_content(Shipping_ID, By_Method)

        });

        $("input[name='By_Method']").live('click', function() {
            var Shipping_ID = $("#Shipping_ID").val();
            var By_Method = $(this).attr('value');
            shipping_obj.get_deliver_content(Shipping_ID, By_Method);

        });

        //添加指定区域表格
        $(".AddRule_Link").live('click', function() {
            shipping_obj.add_specify_section_setting($(this));
        });

        //删除指定区域tr
        $("a.delete_rule_link").live('click', function() {
            var tr_length = $(this).parent().parent().parent().find('tr').length;

            if (tr_length > 2) {
                shipping_obj.sync_j_service();
                $(this).parent().parent().remove();
            } else if (tr_length == 2) {
                $(this).parent().parent().parent().parent().parent().remove();
            }

        });

        //打开区域编辑框
        $('a.Edit_Area').live('click', function() {
            var top = $(this).offset().top + 20;
            var left = $(this).offset().left;

            $("#area_dialog").css({
                top: top,
                left: left
            }).removeClass('hidden');
            var area_value_container = $(this).attr('area_value_container');
            var area_type = $(this).attr('area_type');
			$('#area_value_container').attr('value', area_value_container);
			$('#area_type').attr('value',area_type);  
			//var container_obj = $("input:[name=" + area_value_container + "]");
            var container_obj = $("#" + area_value_container);
		  
			//勾选自己选定城市，禁用他人选定城市
		    shipping_obj.set_area(container_obj,area_type);
			
			if(area_type == 'deliver_business'){
				var overlay_top = $('#deliver_content').offset().top;
				var overlay_left = $('#deliver_content').offset().left;
				var width = $('#deliver_content').width();
				var height = $('#deliver_content').height();
            }else if(area_type == 'set_free'){
				var overlay_top = $('#deliver_free_content').offset().top;
				var overlay_left = $('#deliver_free_content').offset().left;
				var width = $('#deliver_free_content').width();
				var height = $('#deliver_free_content').height();
				
			
			}
			shipping_obj.overlay(overlay_top, overlay_left,width, height);

        });
		
		
		

        //关闭区域编辑框
        $('.area-dialg-close').live('click', function() {

            $("#area_dialog").addClass('hidden');
			shipping_obj.clear_area_dialog();
            shipping_obj.cancel_overlay();

        });

        /*勾选启用*/
        $("#J_SetFree").click(function() {
            shipping_obj.add_shipping_free_setting($(this));
        });

        /*添加一行免运费条件*/
        $(".J_AddItem").live('click', function() {
          
			var checked_business = shipping_obj.get_checked_business();
			var tr_index = $(this).parent().parent().siblings().length;
		
            var data = {
                checked_business: checked_business,tr_index:tr_index
            };
            var tr = $("#set_free_tr").tmpl(data);
            $(this).parent().parent().after(tr);

        });

        /*删除指定免运费条件*/
        $(".J_DelateItem ").live('click', function() {
            var tr_length = $(this).parent().parent().parent().find('tr').length;

            if (tr_length > 2) {
                $(this).parent().parent().remove();
            } else if (tr_length == 2) {

                $(this).parent().parent().parent().remove();

            }
        });

        /*J_ChageContion*/
        $(".J_ChageContion").live('change', function() {


        });

        //提交地区选择
        $('button#J_Submit').click(function() {
            var selected_city = $('#area_form .J_City').fieldValue();
            var checked_province = $('#area_form .J_Province').fieldValue();

            var area_value_container = $('#area_value_container').val();
		
            var container_obj = $("#" + area_value_container);
            $(container_obj).attr('value', selected_city);

            //获取地区描述文本
		
            if (checked_province.length > 0 || selected_city.length > 0) {
                var desc = shipping_obj.get_area_desc(checked_province, selected_city);
              
				$(container_obj).siblings('span').html(desc);
				$(container_obj).siblings('input.area_desc').attr('value',desc);
            } else {
                $(container_obj).siblings('span').html('未添加区域');
				$(container_obj).siblings('input.area_desc').attr('value','');
            }
			
            $("#area_dialog").addClass('hidden');
			shipping_obj.clear_area_dialog();
            shipping_obj.cancel_overlay();
        });

        //改变包邮条件
        $("Select.J_ChageContion").live('change', function() {
            var contion_index = $(this).val();
            var contion_html = $("#free-contion").tmpl({
                contion_index: contion_index
            }).html();
            $(this).next().html(contion_html);
        });

	    //提交运费模板
		
		
		$('#shipping_template_add_form input:submit').click(function() {
            
			var res = true;
			if (global_obj.check_form($('*[notnull]'))) {
				res = false;
            };
					
			var checked_business = shipping_obj.get_checked_business();
			
			$('input.express_areas').each(function(){
				
				if($(this).attr('value').length == 0){
					
					//$(this).parent().parent().css('border', '1px solid red');
					$(this).parent().css('border', '1px solid red');	
					var class_name = $(this).parent().parent().
					                 parent().parent().parent().attr("class");
					
					res = false;
				}else{
						$(this).parent().css('border', '0px solid red');
				}
			});
			
			return res;
			
		});
		

    },
    overlay: function(top, left, width, height) {
        $("#overlay")
            .height(height)
            .css({
                'display': 'block',
                'opacity': 0.2,
                'position': 'absolute',
                'top': top,
                'left': left,
                'background-color': '#000000',
                'width': width,
                'height': height,
                'z-index': 2
            });

    },
    cancel_overlay: function() {
        $("#overlay").css("display", "none");
    },
    get_deliver_content: function(Shipping_ID, By_Method) {

        var res = confirm('您之前的设置会丢失，您确定么？');

        if (res) {
            $("#J_SetFree").prop('checked', false);
            $("#deliver_free_content").html('');
            var url = base_url + 'member/shop/ajax.php';
            var param = {
                action: 'get_deliver_content',
                Shipping_ID: Shipping_ID,
                By_Method: By_Method
            };

            $.get(url, param, function(data) {
                if (data.status == 1) {
                    $("#deliver_content").html(data.content);
                } else {
                    alert('获取数据失败');
                }

            }, 'json');

        }
    },
    sync_check: function(obj, type) {
        //同步地域选择部分
        if (type == 'Province') {
            var targets = $(obj).parent().next().find('input.J_City');
            var checked = $(obj).prop('checked');
            var province_id = $(obj).attr('value');

            if (checked) {
                $(obj).next().next().html('(' + targets.length + ')');
            } else {
                $(obj).next().next().html('');
            }

        } else if (type == 'City') {
		
            var check_num_obj = $(obj).parent().parent().
            siblings('.gareas').
            find('span.check_num');
            var checked = $(obj).prop('checked');
            var html = check_num_obj.html();
			
            //如果是选中
            if (checked) {
                var city_length = $(obj).parent().siblings('.areas').length + 1;
				
                if (html.indexOf(')') == '-1') {
                    check_num_obj.html('(' + 1 + ')');
					var num = 1;
                } else {
                    var cur_num = parseInt(html.substring(1).substring(-1));
                    var num = cur_num + 1;
                    check_num_obj.html('(' + num + ')');
                }

                //如果所有城市全选
                if (num == city_length) {
                    var province_obj = $(check_num_obj).siblings("input.J_Province");
                    var province_id = $(province_obj).attr('value');
                    $(province_obj).prop('checked', true);
                }

            } else {
                //如果是取消
                var cur_num = parseInt(html.substring(1).substring(-1));
                var num = cur_num - 1;

                //某省下所有城市都被取消
                if (num == 0) {
                    check_num_obj.html('');
                } else {
                    check_num_obj.html('(' + num + ')');

                }
                //取消省全选勾
                var province_obj = $(check_num_obj).siblings("input.J_Province");
                var province_id = $(province_obj).attr('value');

                $(province_obj).prop('checked', false);
            }


        }

        //负责勾选城市的checkbox
        $(targets).each(function() {
            if (checked) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }


        });


    },
    add_specify_section_setting: function(obj) {
        //添加指定区域

        var business_alias = $(obj).attr('business_alias');
        var method_name = $("input[name='By_Method']:checked").attr('by_method_name');
        var unit = $("input[name='By_Method']:checked").attr('by_method_unit');

        var tpl_except = $(obj).parent().parent().find('.tpl_except').length;

        //如果还没有添加指定区域表格，则添加表格
        if (tpl_except == 0) {
            var table_data = {
                method_name: method_name,
                unit: unit,
                business_alias: business_alias,
                num: 1
            };

            var table = $("#tpl_except").tmpl(table_data);
            $(obj).parent().before(table);
        } else {
            //否则，在此表格中添加一个新的tr

            var table_tbody = $(obj).parent().parent().find('.tpl_except table tbody');

            var tr_num = $(table_tbody).find('tr').length;
            var tr_data = {
                method_name: method_name,
                unit: unit,
                business_alias: business_alias,
                num: tr_num
            };
            var tr = $("#tpl_except_tr").tmpl(tr_data);
            $(table_tbody).append(tr);

        }
    },
    add_shipping_free_setting: function(obj) {
        //添加免运费条件

        var checked = $(obj).prop('checked');
        
		var checked_business = shipping_obj.get_checked_business();
      
        var data = {
            checked_business: checked_business,tr_index:0
        };

        if (checked) {
            var free_table = $("#set_free_tpl").tmpl(data);
            $("#deliver_free_content").html(free_table);
        } else {
            var result = confirm('你确定要清空指定包邮条件么？！');

            if (result) {
                $("#deliver_free_content").html('');
            }
        }
    },
    get_area_desc: function(checked_province, selected_city) {
        /**
         *获取地区描述文本
         */

        var province_citys = new Array();

        if (checked_province.length > 0) {

            var area_location = new Location();

            for (key in checked_province) {

                var province_id = checked_province[key];
                var cur_privice_citys = area_location.getCityIds('0,' + province_id);

                for (key in cur_privice_citys) {
                    province_citys.push(cur_privice_citys[key]);
                }
            }
        }

		
        var except_citys = new Array();
        if(selected_city.length > 0 && province_citys.length > 0) {
            except_citys = Array.minus(selected_city, province_citys);
			
        }else if(selected_city.length > 0 && province_citys.length == 0){
			except_citys = selected_city;
		}

        var desc = '';
        var province_name_array = new Array();
        var city_name_array = new Array();

        //获取省份名
        if (checked_province.length > 0) {
            checked_province.each(function(province_id) {
                var selector = "label[for='J_Province_" + province_id + "']";
                var province_name = $(selector).html();
                province_name_array.push(province_name);
            });

        }

		desc += province_name_array.join(',');
		
        //获取城市名
	
        if (except_citys.length > 0) {
            except_citys.each(function(city_id) {
                var selector = "label[for='J_City_" + city_id + "']";
                var city_name = $(selector).html();
                city_name_array.push(city_name);
            });

        }
	

        desc += city_name_array.join(',');
	   
        return desc;
    },
	sync_j_service:function(){
		 var checked_business_objs = $('#shipping_template_add_form .Business_Type');
		 var checked_business = {};
  		   
		 var option_html = '';
		 $(checked_business_objs).each(function(){
			if($(this).prop('checked')){
				option_html += "<option value='"+$(this).attr('value')+"'>"+$(this).next().html()+"</option>";
			}
		 });
			
		$("select.J_Service").html(option_html);
	
	},
	set_area:function(obj,area_type){
		
		
	    var my_citys_str = $(obj).attr('value');
		if(my_citys_str.length > 0 ){
			var my_citys =  my_citys_str.split(',');
	    }else{
			var my_citys = new Array();
		}
		
		var class_name =  $(obj).attr('class');
		var all_area_objs = $('input.'+class_name);
		var all_citys_str = '';
		
		$(all_area_objs).each(function(){
			var item_citys = $(this).attr('value'); 
			if(item_citys.length > 0){
				all_citys_str += ','+item_citys; 	
			}
		});
		
		var all_citys = new Array();
		if(all_citys_str.length > 0){
			var str_length = all_citys_str.length;
			all_citys_str = all_citys_str.substr(1,str_length);	
			all_citys = all_citys_str.split(',');
		}
		
		
		var other_citys = new Array();
		if(all_citys.length >0 && my_citys.length >0 ){
			var other_citys = Array.minus(all_citys,my_citys);
			
		}else if( all_citys.length >0 && my_citys.length == 0){
			var other_citys = all_citys;
		}
	
		
		
		if(my_citys.length > 0 ){
			my_citys.each(function(city){
				if(typeof city != 'undefined'){
					var city_obj = $("#J_City_"+city);
					$(city_obj).prop('checked',true);
					shipping_obj.sync_check(city_obj,'City');
				}
		
			});
		}
		
        if(area_type == 'deliver_business'){
			if(other_citys.length >0 ){
				other_citys.each(function(city){
					if(typeof city != 'undefined'){
						shipping_obj.disable_city(city);
					}
			
				});
			}
		}
	},
	clear_area_dialog:function(){
		//清除表单
		$("#area_form input[disabled='disabled']:checkbox").removeAttr('disabled');
		$("#area_form input:checkbox").prop('checked',false);
		$("area_value_container").attr('value','');
		$('#area_form  label').removeClass('del-line');
		$(".check_num").html('');
		
	},
	get_checked_business:function(){
		var checked_business = {};
		checked_business['express'] = '快递';
		
		return checked_business;
	},
	disable_city:function(city_id){
		var city_obj = $("#J_City_"+city_id);
		
		$(city_obj).attr('disabled',true);
		$(city_obj).next().addClass('del-line');
		
		var city_length = $(city_obj).parent().siblings('.areas').length + 1;
	    var disable_city_length = $(city_obj).parent().parent().find("input[disabled='disabled']:checkbox").length;
		//某省下所有城市都被禁用
		if(city_length == disable_city_length){
			var province_obj = $(city_obj).parent().parent().prev().find("input.J_Province");			
			shipping_obj.disable_province(province_obj);
		}
		
	},
	disable_province:function(province_obj){
		
		province_obj.attr('disabled',true);
		$(province_obj).next().addClass('del-line');
	    var province_length = $(province_obj).parent().parent().siblings('.ecity').length+1;
		var disable_province_length = $(province_obj).parent().parent().
		parent().find("input.J_Province[disabled='disabled']:checkbox").length;
		console.log(disable_province_length);
		
		if(province_length == disable_province_length){
			var region_obj = $(province_obj).parent().parent().parent().siblings('.gcity').
			find('.J_Group');
			$(region_obj).attr('disabled',true);
			$(region_obj).next().addClass('del-line');
			
		}
	}

}
