var account_obj={
	
	home_init:function(){
		$("#level-up-btn").click(function(){
		  	$('#confirm_renewal form').show();
			$('#confirm_renewal .tips').hide();
			$('#confirm_renewal').leanModal();
		});
		
		
		$(".qty_filter").click(function(){
			$(this).siblings().removeClass("cur");
			$(this).addClass("cur");
			var this_id = $(this).attr("id");
			var renewal_qty = this_id.split("_")[0];
			var renewal_price = parseFloat(this_id.split("_")[1]);
			var renewal_sheng = one_year_price*renewal_qty-renewal_price;
			$("#renewal_price").html(renewal_price);		
			$("#renewal_sheng").html(renewal_sheng);
			$("#qty_val").attr("value",renewal_qty);
			$("#money_val").attr("value",renewal_price);
		});
		
		
		//bootstrap分页
		var input_page = $('#input_record_pagination');
		var output_page =  $('#output_record_pagination');
		
		account_obj.twbsPagination_init('input',input_total_pages);
		account_obj.twbsPagination_init('output',output_total_pages);
		
		$("#input-record-search").click(function(){
			var range_txt = $("#reportrange-input").attr('value').trim();
	
			if (range_txt.length > 0) {
				var Begin_Time = range_txt.split('-')[0];
				var End_Time = range_txt.split('-')[1];
				account_obj.count_record(Begin_Time,End_Time);
				account_obj.ajax_pagination(1,'input');
				account_obj.ajax_pagination(1,'output');
			}
		});
		
		//初始化时间间隔插件
		$("#reportrange").daterangepicker({
			ranges: ranges,
			startDate: moment(),
			endDate: moment()
			}, function(startDate, endDate) {
				var range = startDate.format('YYYY/MM/DD') + "-" + endDate.format('YYYY/MM/DD');
				$("#reportrange #reportrange-inner").html(range);
				$("#reportrange #reportrange-input").attr('value', range);
		});
		
		//销售曲线表
		$('.chart').height(347).highcharts({
		chart: {
			height: 347,
		},
		title: {
			text: ''
		},
		tooltip: {
			shared: true,
			valueSuffix: global_obj.chart_par.valueSuffix
		},
		xAxis: {
			categories: chart_data.date
		},
		yAxis: [{
			title: {
				text: '单位(元)'
			},
			min: 0,
			max:max_val
		}],
		legend: global_obj.chart_par.legend,
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true
				},
				enableMouseTracking: false
			},
			bar: {
				dataLabels: {
					enabled: true
				}
			}
		},
		series: chart_data.count,
		exporting: {
			enabled: false
		}
	});
		
	},
	login_init:function(){
		if(window!=top){
			top.location.href=window.location.href;
		}
		
		var p_left = ($(window).width()/2 - $('.login_box').width()/2)+'px';
		var p_top = ($(window).height()/2 - $('.login_box').height()/2-70)+'px';
		var s_left = ($('.login_box').width()/2 - $('.tab_box').width()/2)+'px';
		var s_top = ($('.login_box').height()/2 - $('.tab_box').height()/2)+'px';
		
		$('.login_box').css({'left':p_left,'top':p_top});
		$('.tab_box').css({'left':s_left,'top':s_top});
		$('form').submit(function(){return false;});
		
		$('#verifyimg').click(function(){
			account_obj.verifycode_init();
		});
		
		$('input:submit').click(function(){
			var flag=false;
			$('#Account, #Password, #VerifyCode').each(function(){
				if($(this).val()==''){
					$(this).focus();
					flag=true;
					return false;
				}
			});
			if(flag){return;}
			
			$('.login_msg').show().html('身份验证中...');
			$(this).attr('disabled', true);
			
			$.post('?', $('form').serialize(), function(data){
				$('input:submit').attr('disabled', false);
				if(data.status==1){
					window.top.location='./';
				}else if(data.status==3){
					$('.login_msg').show().html('登录失败，错误的用户名或密码！');
				}else if(data.status==0){
					$('.login_msg').show().html('您的帐号已被禁用，无法登录！');
				}else if(data.status==2){
					$('.login_msg').show().html('您的帐号已经到期，无法登录！');
				}else if(data.status==4){
					$('.login_msg').show().html('验证码不正确');
				};
			}, 'json');
		});
	},
	
	index_init:function(){
		$('a[group]').click(function(){
			var group=$(this).attr('group');
			if(group=='#'){
				parent.$('#main .menu dt').removeClass('cur');
				parent.$('#main .menu dd').hide();
			}else{
				parent.$('#main .menu dt').removeClass('cur');
				parent.$('#main .menu dt[group='+group+']').addClass('cur').next().filter('dd').show();
			}
			parent.$('#main .menu div').removeClass('cur');
			if($(this).attr('url')){
				parent.$('#main .menu a[href="'+$(this).attr('url')+'"]').parent().addClass('cur');
			}else{
				parent.$('#main .menu a[href="'+$(this).attr('href')+'"]').parent().addClass('cur');
			}
			parent.main_obj.page_scroll_init();
		});
			
		$("#level-up-btn").click(function(){
		  	$('#confirm_renewal form').show();
			$('#confirm_renewal .tips').hide();
			$('#confirm_renewal').leanModal();
		});	
	
		
		$(".qty_filter").click(function(){
			$(this).siblings().removeClass("cur");
			$(this).addClass("cur");
			var this_id = $(this).attr("id");
			var renewal_qty = this_id.split("_")[0];
			var renewal_price = parseFloat(this_id.split("_")[1]);
			var renewal_sheng = one_year_price*renewal_qty-renewal_price;
			$("#renewal_price").html(renewal_price);		
			$("#renewal_sheng").html(renewal_sheng);
			$("#qty_val").attr("value",renewal_qty);
			$("#money_val").attr("value",renewal_price);
		});
		
		
		global_obj.chart_par.height='347';
		global_obj.chart_par.legend={
			layout: 'horizontal',
            align: 'center',
            x: 10,
            verticalAlign: 'bottom',
            y: 0,
            floating: false,
            backgroundColor: '#FFFFFF',
			itemMarginBottom: 0,
			itemStyle:{
				color: '#000000',
				fontWeight: 'normal'
            }
		};
		global_obj.chart();
		
	},
	
	profile_init:function(){
		$('#profile_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#profile_form input:submit').attr('disabled', true);
			return true;
		});
	},
	reg_init:function(){
		
		var p_left = ($(window).width()/2 - $('.login_box').width()/2)+'px';
		var p_top = ($(window).height()/2 - $('.login_box').height()/2-70)+'px';
		var s_left = ($('.login_box').width()/2 - $('.tab_box').width()/2)+'px';
		var s_top = ($('.login_box').height()/2 - $('.tab_box').height()/2)+'px';
		
		$('.login_box').css({'left':p_left,'top':p_top});
		$('.tab_box').css({'left':s_left,'top':s_top});
		
		$('#trade_0').change(function(){
			var data = {action:'trade',id:this.value};
			$.get('?', data, function(data){
				if(data.status == 1){
					$('#trade_1').empty();
					$.each(data.html,function(index,html){
						var option = $("<option>").text(html.name).val(html.id)
						$('#trade_1').append(option);
                    });
				}
			},"json");
		});
		
		$('form').submit(function(){return false;});		
		$('input:submit').click(function(){
			$(this).attr('disabled', true);
			$.post('?', $('form').serialize(), function(data){
				$('input:submit').attr('disabled', false);
				if(data.status==1){
					alert("注册成功");
					window.location.href='/member/login.php';
				}else{
					alert(data.msg);
				}
			}, 'json');
		});
		
		$('#sendsms').click(function(){
			if($('#mobile').val()==''){
				alert('请输入手机号码');
				return false;
			}
			$(this).attr('disabled', true);
			$('#sendsms').html('发送中......');
			var data = {action:'smscode',mobile:$('#mobile').val()};
			$.get('?', data, function(data){
				if(data.status == 1){
					alert(data.msg);
					$('#sendsms').html('发送成功');
				}else{
					alert(data.msg);
				}
			},"json");
		});
	},
	
	findpwd_init:function(){
		
		var p_left = ($(window).width()/2 - $('.login_box').width()/2)+'px';
		var p_top = ($(window).height()/2 - $('.login_box').height()/2-70)+'px';
		var s_left = ($('.login_box').width()/2 - $('.tab_box').width()/2)+'px';
		var s_top = ($('.login_box').height()/2 - $('.tab_box').height()/2)+'px';
		
		$('.login_box').css({'left':p_left,'top':p_top});
		$('.tab_box').css({'left':s_left,'top':s_top});
		
		$('form').submit(function(){return false;});		
		$('input:submit').click(function(){
			$(this).attr('disabled', true);
			$.post('?', $('form').serialize(), function(data){
				$('input:submit').attr('disabled', false);
				if(data.status==1){
					account_obj.win_alert(data.msg, function(){
						window.location=data.url;		
					});					
				}else{
					account_obj.win_alert(data.msg, function(){
						history.back();
					});
				}
			}, 'json');
		});
		
		$('#sendsms').click(function(){
			if($('#mobile').val()==''){
				alert('请输入手机号码');
				return false;
			}
			$(this).attr('disabled', true);
			$('#sendsms').html('发送中......');
			var data = {action:'smscode',mobile:$('#mobile').val()};
			$.get('?', data, function(data){
				if(data.status == 1){
					alert(data.msg);
					$('#sendsms').html('发送成功');
				}else{
					alert(data.msg);
				}
			},"json");
		});
	},
	
	win_alert:function(tips, handle){
		$('body').prepend('<div id="global_win_alert"><div>'+tips+'</div><h1>好</h1></div>');
		$('#global_win_alert').css({
			position:'fixed',
			left:$(window).width()/2-125,
			top:'30%',
			background:'#fff',
			border:'1px solid #ccc',
			opacity:0.95,
			width:250,
			'z-index':100000,
			'border-radius':'8px'
		}).children('div').css({
			'text-align':'center',
			padding:'30px 10px',
			'font-size':16
		}).siblings('h1').css({
			height:40,
			'line-height':'40px',
			'text-align':'center',
			'border-top':'1px solid #ddd',
			'font-weight':'bold',
			'font-size':20
		});
		$('#global_win_alert h1').click(function(){
			$('#global_win_alert').remove();
		});
		if($.isFunction(handle)){
			$('#global_win_alert h1').click(handle);
		}
	},
	
	verifycode_init:function(){
		$('.verifyimg').attr("src","?action=verifycode&t="+Math.random());
	},
	twbsPagination_init:function(type,totalPages){
		
		var page_obj = $("#"+type+"_record_pagination");
		if(page_obj.children().length >0 ){
			page_obj.twbsPagination('destroy');
		}
		if(parseInt(totalPages)>0){	
		
			page_obj.twbsPagination({
				totalPages:parseInt(totalPages),
				visiblePages: 10,
				href: 'javascript:void(0)',
				onPageClick: function(event, page) {
					account_obj.ajax_pagination(page,type);
				}
			});
		}
	},
	count_record:function(Begin_Time,End_Time){
			var url = base_url+'member/shop/ajax.php';

			var param = {
					Begin_Time: Begin_Time,
					End_Time: End_Time,
					action: 'count_record',
			};
			
			
			$.get(url, param, function(data){	
			   if(data.status == 1){
				 
					
				$('#input-record-begin').html(data.Begin_Time);
				$('#input-record-end').html(data.End_Time);
				$('#input-record-sum').html(data.input_sum);
				
				$('#output-record-begin').html(data.Begin_Time);
				$('#output-record-end').html(data.End_Time);
				$('#output-record-sum').html(data.output_sum);
				
				
						
				account_obj.twbsPagination_init('input',parseInt(data.input_total_pages));
				account_obj.twbsPagination_init('output',parseInt(data.output_total_pages));
					
			   }
			},'json');
	},
	ajax_pagination:function(page,type){
		var range_txt = $("#reportrange-input").attr('value').trim();
	
		if (range_txt.length > 0) {
			var Begin_Time = range_txt.split('-')[0];
			var End_Time = range_txt.split('-')[1];
			var url = base_url + 'member/shop/ajax.php';
			var param = {
				Begin_Time: Begin_Time,
				End_Time: End_Time,
				action:'get_'+type+'_record',
				page: page
			};
			
			$.get(url, param, function(data) {
				$("#"+type+"_record_table").html(data);
			});
		}
	}
}