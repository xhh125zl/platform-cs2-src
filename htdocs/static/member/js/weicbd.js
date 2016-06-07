var weicbd_obj={
	home_init:function(){
		//加载上传按钮
		global_obj.file_upload($('#HomeFileUpload'), $('#home_form input[name=ImgPath]'), $('#home .weicbd_skin_index_list').eq($('#home_form input[name=no]')).find('.img'));
		for(var i=0;i<5;i++){
			global_obj.file_upload($('#HomeFileUpload_'+i), $('#home_form input[name=ImgPathList\\[\\]]').eq(i), $('#home_form .b_r').eq(i));
		}
		$('.m_lefter a').attr('href', '#').css({'cursor':'default', 'text-decoration':'none'}).click(function(){
			$(this).blur();
			return false;
		});
		$('.m_lefter form').submit(function(){
			return false;
		});
		//加载版面内容
		for(i=0; i<weicbd_skin_data.length; i++){
			var obj=$("#weicbd_skin_index div").filter('[rel=edit-'+weicbd_skin_data[i]['Postion']+']');
			obj.attr('no', i);
			if(weicbd_skin_data[i]['ContentsType']==1){
				var dataObj=eval("("+weicbd_skin_data[i]['ImgPath']+")");
				if(dataObj[0].indexOf('http://')!=-1){
					var s='';
				}else if(dataObj[0].indexOf('/api/')!=-1){
					var s=domain.static;
				}else{
					var s='';
				}
				obj.find('.img').html('<img src="'+s+dataObj[0]+'" />');
			}else{
				if(weicbd_skin_data[i]['ImgPath'].indexOf('http://')!=-1){
					var s='';
				}else if(weicbd_skin_data[i]['ImgPath'].indexOf('/api/')!=-1){
					var s=domain.static;
				}else{
					var s='';
				}
				if(weicbd_skin_data[i]['NeedLink']==1){
					obj.find('.text').html('<a href="">'+weicbd_skin_data[i]['Title']+'</a>')
				}else{
					obj.find('.text').html(weicbd_skin_data[i]['Title'])
				}
				obj.find('.img').html('<img src="'+s+weicbd_skin_data[i]['ImgPath']+'" />');
			}
		}
		
		$('.weicbd_skin_index_list div').after('<div class="mod">&nbsp;</div>');	//追加编辑按钮
		$('#weicbd_skin_index .weicbd_skin_index_list').hover(function(){$(this).find('.mod').show();}, function(){$(this).find('.mod').hide();});
		
		//点击图标切换编辑内容
		$('#weicbd_skin_index .weicbd_skin_index_list .mod').click(function(){
			var parent=$(this).parent();
			var no=parent.attr('no');
		
			$('#SetHomeCurrentBox').remove();
			parent.append("<div id='SetHomeCurrentBox'></div>");
			$('#SetHomeCurrentBox').css({'height':parent.height()-10, 'width':parent.width()-10})
			$("#setbanner, #setimages").hide();
			$('.url_select').css('display', weicbd_skin_data[no]['NeedLink']==1?'block':'none');
			
			if(weicbd_skin_data[no]['ContentsType']==1){
				$("#setbanner").show();
				var dataImgPath=eval("("+weicbd_skin_data[no]['ImgPath']+")");
				var dataUrl=eval("("+weicbd_skin_data[no]['Url']+")");
				var dataTitle=eval("("+weicbd_skin_data[no]['Title']+")");
				$('#home_form #setbanner .tips label').html(weicbd_skin_data[no]['Width']+'*'+weicbd_skin_data[no]['Height']);
				for(var i=0; i<dataImgPath.length; i++){
					$('#home_form input[name=ImgPathList\\[\\]]').eq(i).val(dataImgPath[i]);
					$('#home_form input[name=UrlList\\[\\]]').eq(i).val(dataUrl[i]);
					$('#home_form input[name=TitleList\\[\\]]').eq(i).val(dataTitle[i]);
					
					if(dataImgPath[i].indexOf('http://')!=-1){
						var s='';
					}else if(dataImgPath[i].indexOf('/u_file/')!=-1){
						var s=domain.img;
						dataImgPath[i]=dataImgPath[i].replace('/u_file', '');
					}else if(dataImgPath[i].indexOf('/api/')!=-1){
						var s=domain.static;
					}else{
						var s='';
					}
					dataImgPath[i] && $("#home_form .b_r").eq(i).html('<a href="'+s+dataImgPath[i]+'" target="_blank"><img src="'+s+dataImgPath[i]+'" /></a>');
					if(dataUrl[i]){
						$("#home_form select[name=UrlList\\[\\]]").eq(i).find("option[value='"+dataUrl[i]+"']").attr("selected", true);
					}else{
						$("#home_form select[name=UrlList\\[\\]]").eq(i).find("option").eq(0).attr("selected", true);
					}
				}
			}else{
				if(parent.find('.text').length){
					$("#setimages div[value=title]").show();
				}else{
					$("#setimages div[value=title]").hide();
				}
				if(parent.find('.img').length){
					$("#setimages div[value=images]").show();
				}else{
					$("#setimages div[value=images]").hide();
				}
				$("#setimages").show();
				$('#home_form input').filter('[name=Title]').val(weicbd_skin_data[no]['Title'])
				.end().filter('[name=ImgPath]').val(weicbd_skin_data[no]['ImgPath'])
				.end().filter('[name=Title]').focus();
				$('#home_form #setimages .tips label').html(weicbd_skin_data[no]['Width']+'*'+weicbd_skin_data[no]['Height']);
				if(weicbd_skin_data[no]['Url']){
					$("#home_form select[name=Url] option[value='"+weicbd_skin_data[no]['Url']+"']").attr("selected", true);
				}else{
					$("#home_form select[name=Url] option").eq(0).attr("selected", true);
				}
			}	
					
			$('#home_form input').filter('[name=PId]').val(weicbd_skin_data[no]['PId'])
			.end().filter('[name=SId]').val(weicbd_skin_data[no]['SId'])
			.end().filter('[name=ContentsType]').val(weicbd_skin_data[no]['ContentsType'])
			.end().filter('[name=no]').val(no);
		});
		
		//加载默认内容
		$('#weicbd_skin_index .weicbd_skin_index_list .mod').eq(0).click();
		
		//ajax提交更新，返回
		$('#home_form').submit(function(){return false;});
		$('#home_form input:submit').click(function(){
			$(this).attr('disabled', true);
			$.post('?', $('#home_form').serialize()+'&do_action=shop.set_home_mod&ajax=1', function(data){
				$('#home_form input:submit').attr('disabled', false);
				if(data.status==1){
					$('#home_mod_tips .tips').html('首页设置成功！');
					$('#home_mod_tips').leanModal();
					
					var _no=$('#home_form input[name=no]').val();
					var _v=$("div[no="+_no+"]");
					weicbd_skin_data[_no]['ImgPath']=data.ImgPath;
					weicbd_skin_data[_no]['Title']=data.Title;
					weicbd_skin_data[_no]['Url']=data.Url;
					
					if(weicbd_skin_data[_no]['ContentsType']==1){
						var dataImgPath=eval("("+weicbd_skin_data[_no]['ImgPath']+")");
						if(dataImgPath[0].indexOf('http://')!=-1){
							var s='';
						}else if(dataImgPath[0].indexOf('/u_file/')!=-1){
							var s=domain.img;
							dataImgPath[0]=dataImgPath[0].replace('/u_file', '');
						}else if(dataImgPath[0].indexOf('/api/')!=-1){
							var s=domain.static;
						}else{
							var s='';
						}
						_v.find('.img').html('<img src="'+s+dataImgPath[0]+'" />');
					}else{
						if(weicbd_skin_data[_no]['ImgPath'].indexOf('http://')!=-1){
							var s='';
						}else if(weicbd_skin_data[_no]['ImgPath'].indexOf('/u_file/')!=-1){
							var s=domain.img;
							weicbd_skin_data[_no]['ImgPath']=weicbd_skin_data[_no]['ImgPath'].replace('/u_file', '');
						}else if(weicbd_skin_data[_no]['ImgPath'].indexOf('/api/')!=-1){
							var s=domain.static;
						}else{
							var s='';
						}
						_v.find('.text').html('<a href="">'+weicbd_skin_data[_no]['Title']+'</a>');
						_v.find('.img').html('<img src="'+s+weicbd_skin_data[_no]['ImgPath']+'" />');
					}
				}else{
					$('#home_mod_tips .tips').html('首页设置失败，请重试！');
					$('#home_mod_tips').leanModal();
				};
			}, 'json');
		});
		
		$('#home_form .item .rows .b_l a[href=#weicbd_home_img_del]').click(function(){
			var _no=$(this).attr('value');
			$('#home_form .b_r').eq(_no).html('');
			$('#home_form input[name=ImgPathList\\[\\]]').eq(_no).val('');
			this.blur();
			return false;
		});
	},
	
	config_init:function(){		
		$('#config_form').submit(function(){return false;});
		$('#config_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false;};
			
			$(this).attr('disabled', true);
			$.post('?', $('#config_form').serialize(), function(data){
				if(data.status==1){
					if(confirm(data.msg)){
						$('#config_form input:submit').attr('disabled', false);
					}else{
						$('#config_form input:submit').attr('disabled', false);
						window.location=data.url;
					}
				}else{
					alert(data.msg);
					$('#config_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	biz_list_init:function(){
		$('a[href=#search]').click(function(){
			$('form.search').slideDown();
			return false;
		});
	},
	
	biz_edit_init:function(){
		global_obj.map_init();
		$('#biz_edit').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#biz_edit input:submit').attr('disabled', true);
			return true;
		});
	},
	
	mulu_edit_init:function(){
		$('#mulu_edit').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#mulu_edit input:submit').attr('disabled', true);
			return true;
		});
	},
	
	products_init:function(){
		$("#products #BizID").change(function(){
			$("#mulu_html").css("display","none");
			$.ajax({
				type	: "POST",
				url		: "ajax.php",
				data	: "action=mulu&BizID="+$("#BizID").val()+"&MuluID="+$("#MuluID").val(),
				dataType: "json",
				async : false,
				success	: function(data){
					if(data.msg){
						$("#mulu_html").css("display","block");
						$("#mulu_html").html(data.msg);
					}
				}
			});
		});
		
		$("#products .font_btn").click(function(){
			var BizID = $("#BizID").val();
			var MuluID = $("#Mulu_ID").val();
			var ProductsID = $("#ProductsID").val();
			if(BizID>0 && MuluID>0){
				$.ajax({
					type	: "POST",
					url		: "ajax.php",
					data	: "action=property&BizID="+$("#BizID").val()+"&UsersID="+$("#UsersID").val()+"&MuluID="+$("#Mulu_ID").val()+"&ProductsID="+$("#ProductsID").val(),
					dataType: "json",
					async : false,
					success	: function(data){
						if(data.msg){
							$("#propertys").css("display","block");
							$("#propertys").html(data.msg);
						}else{
							alert("暂无属性！");
						}
					}
				});	
			} else {
				if(BizID==0){
					alert("请选择商家");
					$("#BizID").focus();
				}else{
					alert("请选择产品目录");
					$("#Category").focus();
				}
			}
		});
		
		$("#products .font_btn_clear").click(function(){
		    $("#propertys").css("display","none");
			$("#propertys").html("");
		});
													  
		
		$('#products_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#products_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	products_list_init:function(){
		$('a[href=#search]').click(function(){
			$('form.search').slideDown();
			return false;
		});
	},
	
	property_init:function(){
		$("#property_form #BizID").change(function(){
			$("#mulu_html").css("display","none");
			$.ajax({
				type	: "POST",
				url		: "ajax.php",
				data	: "action=mulu&BizID="+$("#BizID").val()+"&MuluID="+$("#MuluID").val(),
				dataType: "json",
				async : false,
				success	: function(data){
					if(data.msg){
						$("#mulu_html").css("display","block");
						$("#mulu_html").html(data.msg);
					}
				}
			});
		});
		
		$('#property_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#property_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	orders_init:function(){
		$('#search_form input:button').click(function(){
			window.location='./?'+$('#search_form').serialize()+'&do_action=shop.orders_export';
		});
		
		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});
		
		$('#orders .cp_title #cp_view, #orders .cp_title #cp_mod').click(function(){
			$('#orders .cp_title div').removeClass('cur');
			$(this).addClass('cur');
			
			if($(this).attr('id')=='cp_view'){
				$('#orders_mod_form .cp_item_view').show();
				$('#orders_mod_form .cp_item_mod').hide();
			}else{
				$('#orders_mod_form .cp_item_view').hide();
				$('#orders_mod_form .cp_item_mod').show();
			}
		});
		$('#orders_mod_form').submit(function(){$('#orders_mod_form input:submit').attr('disabled', true);});
		$('#orders_mod_form .cp_item_mod .back').click(function(){$('#orders .cp_title #cp_view').click();});
		
		var change_is_read=function(){
			$('#order_list tr[IsRead=0]').off().click(function(){
				var o=$(this);
				$.get('?', 'do_action=shop.orders_set_read&OrderId='+o.attr('OrderId'), function(data){
					if(data.ret==1){
						o.removeClass('is_not_read').off();
					}
				}, 'json');
			});
		};
		
		var refer_time=60;
		var refer_left_time=0;
		var refer_ing=false;
		var auto_refer=function(){
			if($('#auto_refer').is(':checked')){
				if(refer_left_time<refer_time){
					$('#search_form div label').html('<span><strong>'+(refer_time-refer_left_time)+'</strong></span>秒后自动刷新');
					refer_left_time++;
				}else if(refer_ing==false){
					refer_ing=true;
					$('#search_form div label').html('数据拉取中..');
					
					$.get('?', 'do_action=shop.orders_is_not_read', function(data){
						refer_ing=false;
						refer_left_time=0;
						if(data.ret==1){
							var have_new_order=false;
							var html='';
							for(var i=0; i<data.msg.length; i++){
								if($('#order_list tr[OrderId='+data.msg[i]['OrderId']+']').size()==0){	//订单号不在列表中
									have_new_order=true;
									html+='<tr class="is_not_read" IsRead="0" OrderId="'+data.msg[i]['OrderId']+'">';
										html+='<td nowrap="nowrap">新订单</td>';
										html+='<td nowrap="nowrap">'+data.msg[i]['OId']+'</td>';
										html+='<td>'+data.msg[i]['Name']+'</td>';
										html+='<td nowrap="nowrap">￥'+data.msg[i]['Price']+'</td>';
										NeedShipping && (html+='<td nowrap="nowrap">'+data.msg[i]['Shipping']+'</td>');
										html+='<td nowrap="nowrap">'+orders_status[data.msg[i]['OrderStatus']]+'</td>';
										html+='<td nowrap="nowrap">￥'+data.msg[i]['OrderTime']+'</td>';
										html+='<td nowrap="nowrap" class="last"><a href="?m=shop&a=orders&d=view&OrderId='+data.msg[i]['OrderId']+'"><img src="'+domain.static+'/member/images/ico/view.gif" align="absmiddle" alt="修改" /></a><a href="?m=shop&a=orders&do_action=shop.orders_del&OrderId='+data.msg[i]['OrderId']+'" title="删除" onClick="if(!confirm(\'删除后不可恢复，继续吗？\')){return false};"><img src="'+domain.static+'/member/images/ico/del.gif" align="absmiddle" /></a></td>';
									html+='</tr>';
								}
							}
							if(have_new_order){
								$('#search_form div label').html('<span>数据拉取成功</span>');
								$('#order_list tbody').prepend(html);
								change_is_read();
								$('body').prepend('<bgsound src="'+domain.static+'/member/images/shop/tips.mp3" autostart="true" loop="1">');
							}else{
								$('#search_form div label').html('<span>没有新的订单</span>');
							}
						}else{
							$('#search_form div label').html('<span>数据拉取失败</span>');
						}
					}, 'json');
				}
			}else{
				$('#search_form div label').html('自动刷新订单');
				refer_left_time=0;
				refer_ing=false;
			}
			setTimeout(auto_refer, 1000);
		};
		auto_refer();
		change_is_read();
		
		$('#orders a[name=print]').click(function(){
			var pos_l=($(document.body).width()-670)/2;
			var pos_t=($(window).height()-500)/2;
			$('#print_cont').css({'top':pos_t+'px','left':pos_l+'px'}).fadeIn();
			var OrderId=$(this).parent().parent().attr('OrderId');
			$('#get_data').attr('src','./?m=shop&a=print&OrderId='+OrderId+'&n='+Math.random());
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
	}
}