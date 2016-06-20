/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/
var shop_obj={
	skin_init:function(){
		$('#skin li .item').click(function(){
			if(!confirm('您确定要选择此风格吗？')){return false};
			$.post('?', "do_action=shop.skin_mod&SId="+$(this).attr('SId'), function(data){
				if(data.status==1){
					window.location.reload();
				}
			}, 'json');
		});
	},
	
	home_init:function(){
		//加载上传按钮
		global_obj.file_upload($('#HomeFileUpload'), $('#home_form input[name=ImgPath]'), $('#home .shop_skin_index_list').eq($('#home_form input[name=no]')).find('.img'));
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
		for(i=0; i<shop_skin_data.length; i++){
			var obj=$("#shop_skin_index div").filter('[rel=edit-'+shop_skin_data[i]['Postion']+']');
			obj.attr('no', i);
			if(shop_skin_data[i]['ContentsType']==1){
				var dataObj=eval("("+shop_skin_data[i]['ImgPath']+")");
				if(dataObj[0].indexOf('http://')!=-1){
					var s='';
				}else if(dataObj[0].indexOf('/u_file/')!=-1){
					var s=domain.img;
					dataObj[0]=dataObj[0].replace('/u_file', '');
				}else if(dataObj[0].indexOf('/api/')!=-1){
					var s=domain.static;
				}else{
					var s='';
				}
				obj.find('.img').html('<img src="'+s+dataObj[0]+'" />');
			}else{
				if(shop_skin_data[i]['ImgPath'].indexOf('http://')!=-1){
					var s='';
				}else if(shop_skin_data[i]['ImgPath'].indexOf('/u_file/')!=-1){
					var s=domain.img;
					shop_skin_data[i]['ImgPath']=shop_skin_data[i]['ImgPath'].replace('/u_file', '');
				}else if(shop_skin_data[i]['ImgPath'].indexOf('/api/')!=-1){
					var s=domain.static;
				}else{
					var s='';
				}
				if(shop_skin_data[i]['NeedLink']==1){
					obj.find('.text').html('<a href="">'+shop_skin_data[i]['Title']+'</a>')
				}else{
					obj.find('.text').html(shop_skin_data[i]['Title'])
				}
				obj.find('.img').html('<img src="'+s+shop_skin_data[i]['ImgPath']+'" />');
			}
		}
		
		$('.shop_skin_index_list div').after('<div class="mod">&nbsp;</div>');	//追加编辑按钮
		$('#shop_skin_index .shop_skin_index_list').hover(function(){$(this).find('.mod').show();}, function(){$(this).find('.mod').hide();});
		
		//点击图标切换编辑内容
		$('#shop_skin_index .shop_skin_index_list .mod').click(function(){
			var parent=$(this).parent();
			var no=parent.attr('no');
		
			$('#SetHomeCurrentBox').remove();
			parent.append("<div id='SetHomeCurrentBox'></div>");
			$('#SetHomeCurrentBox').css({'height':parent.height()-10, 'width':parent.width()-10})
			$("#setbanner, #setimages").hide();
			$('.url_select').css('display', shop_skin_data[no]['NeedLink']==1?'block':'none');
			
			if(shop_skin_data[no]['ContentsType']==1){
				$("#setbanner").show();
				var dataImgPath=eval("("+shop_skin_data[no]['ImgPath']+")");
				var dataUrl=eval("("+shop_skin_data[no]['Url']+")");
				var dataTitle=eval("("+shop_skin_data[no]['Title']+")");
				$('#home_form #setbanner .tips label').html(shop_skin_data[no]['Width']+'*'+shop_skin_data[no]['Height']);
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
				$('#home_form input').filter('[name=Title]').val(shop_skin_data[no]['Title'])
				.end().filter('[name=ImgPath]').val(shop_skin_data[no]['ImgPath'])
				.end().filter('[name=Title]').focus();
				$('#home_form #setimages .tips label').html(shop_skin_data[no]['Width']+'*'+shop_skin_data[no]['Height']);
				if(shop_skin_data[no]['Url']){
					$("#home_form select[name=Url] option[value='"+shop_skin_data[no]['Url']+"']").attr("selected", true);
				}else{
					$("#home_form select[name=Url] option").eq(0).attr("selected", true);
				}
			}	
					
			$('#home_form input').filter('[name=PId]').val(shop_skin_data[no]['PId'])
			.end().filter('[name=SId]').val(shop_skin_data[no]['SId'])
			.end().filter('[name=ContentsType]').val(shop_skin_data[no]['ContentsType'])
			.end().filter('[name=no]').val(no);
		});
		
		//加载默认内容
		$('#shop_skin_index .shop_skin_index_list .mod').eq(0).click();
		
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
					shop_skin_data[_no]['ImgPath']=data.ImgPath;
					shop_skin_data[_no]['Title']=data.Title;
					shop_skin_data[_no]['Url']=data.Url;
					
					if(shop_skin_data[_no]['ContentsType']==1){
						var dataImgPath=eval("("+shop_skin_data[_no]['ImgPath']+")");
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
						if(shop_skin_data[_no]['ImgPath'].indexOf('http://')!=-1){
							var s='';
						}else if(shop_skin_data[_no]['ImgPath'].indexOf('/u_file/')!=-1){
							var s=domain.img;
							shop_skin_data[_no]['ImgPath']=shop_skin_data[_no]['ImgPath'].replace('/u_file', '');
						}else if(shop_skin_data[_no]['ImgPath'].indexOf('/api/')!=-1){
							var s=domain.static;
						}else{
							var s='';
						}
						_v.find('.text').html('<a href="">'+shop_skin_data[_no]['Title']+'</a>');
						_v.find('.img').html('<img src="'+s+shop_skin_data[_no]['ImgPath']+'" />');
					}
				}else{
					$('#home_mod_tips .tips').html('首页设置失败，请重试！');
					$('#home_mod_tips').leanModal();
				};
			}, 'json');
		});
		
		$('#home_form .item .rows .b_l a[href=#shop_home_img_del]').click(function(){
			var _no=$(this).attr('value');
			$('#home_form .b_r').eq(_no).html('');
			$('#home_form input[name=ImgPathList\\[\\]]').eq(_no).val('');
			this.blur();
			return false;
		});
	
	},
	
	products_init:function(){
		$('#products_form').submit(function() {

			if (global_obj.check_form($('*[notnull]'))) {

				return false

			};

			$('#products_form .submit').attr('disabled', true);

			return true;

		});

        var date_str=new Date();
		$('#products_form input[name=Time]').daterangepicker({
			timePicker:true,
			//minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			format:'YYYY/MM/DD HH:mm:00'}
		);
		$('#PicDetail div span').on('click', function(){$(this).parent().remove();});
		var pic_count=parseInt($('#pic_count').html());
		
		$('#BannerDetail div span').on('click', function(){$(this).parent().remove();});
		var banner_count=parseInt($('#banner_count').html());
		
		if(!$('#stock_cont').size()){
			$('#products_form td.stock').remove();
			$('#products_form td.title').attr('colspan', 2);
			$('#property_ext thead td').width('50%');
		}
		
		$('#products_form .property input[type=checkbox]').click(function(){
			var dataObj=eval("("+$(this).attr('data')+")");
			if($(this).is(':checked')){
				if($('#PEPId_'+dataObj.PId).length==0){
					var html_t=$('#property_tmp .column').html();
					html_t=html_t.replace('XXX',dataObj.PId).replace('Column',dataObj.Column);
					$('#property_ext').append(html_t);
				}
				if($('#PELId_'+dataObj.LId).length==0){
					var html_c=$('#property_tmp .contents').html();
					html_c=html_c.replace(/XXX/g,dataObj.LId).replace('Name',dataObj.Name);
					$('#PEPId_'+dataObj.PId).append(html_c);	
				}
			}else{
				if($('#PELId_'+dataObj.LId).length>0){
					$('#PELId_'+dataObj.LId).remove();
				}
				if($('#PEPId_'+dataObj.PId+' tr').length==1){
					$('#PEPId_'+dataObj.PId).remove();
				}
			}
			if($('#products_form .property input:checked').size()){
				$('#stock_cont').hide();
				$('#property_ext_cont').show();
			}else{
				$('#stock_cont').show();
				$('#property_ext_cont').hide();
			}
		});
		
		$('a[href=#add_wholesale]').click(function(){
			var newrow=document.getElementById('wholesale_price_list').insertRow(-1);
			newcell=newrow.insertCell(-1);
			newcell.innerHTML='数量：<input type="text" name="Qty[]" value="" class="form_input" size="5" maxlength="3" /> 价格：￥<input type="text" name="Price[]" value="" class="form_input" size="5" maxlength="10" /> <a href="javascript:;" onclick="document.getElementById(\'wholesale_price_list\').deleteRow(this.parentNode.parentNode.rowIndex);"><img src="'+domain.static+'/member/images/ico/del.gif" hspace="5" /></a>';
		});
		
		var callback=function(imgpath){
			if($('#PicDetail div').size()>=pic_count){
				alert('您上传的图片数量已经超过5张，不能再上传！');
				return;
			}
			$('#PicDetail').append('<div>'+global_obj.img_link(imgpath)+'<span>删除</span><input type="hidden" name="PicPath[]" value="'+imgpath+'" /></div>');
			$('#PicDetail div span').off('click').on('click', function(){$(this).parent().remove();});
		};
		
		var banner_callback=function(imgpath){
			if($('#BannerDetail div').size()>=banner_count){
				alert('您上传的图片数量已经超过'+banner_count+'张，不能再上传！');
				return;
			}
			$('#BannerDetail').append('<div>'+global_obj.img_link(imgpath)+'<span>删除</span><input type="hidden" name="Banner[]" value="'+imgpath+'" /></div>');
			$('#BannerDetail div span').off('click').on('click', function(){$(this).parent().remove();});
		};
		
		global_obj.file_upload($('#PicUpload'), '', '', 'shop_products', true, pic_count, callback);
		global_obj.file_upload($('#BannerUpload'), '', '', 'shop_products_banner', true, banner_count, banner_callback);
		$('#products_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#products_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	products_list_init:function(){
		$('a[href=#search]').click(function(){
			$('form.search').slideDown();
			return false;
		});
	},
	
	products_category_init:function(){
		global_obj.file_upload($('#HomeFileUpload'), $('#shop_category_form input[name=ImgPath]'), $('#look'));
		$('#products .category .m_lefter dl').dragsort({
			dragSelector:'dd',
			dragEnd:function(){
				var data=$(this).parent().children('dd').map(function(){
					return $(this).attr('CateId');
				}).get();
				$.get('?m=shop&a=products', {do_action:'shop.products_category_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'ul, a',
			placeHolderTemplate:'<dd class="placeHolder"></dd>',
			scrollSpeed:5
		});
		
		$('#products .category .m_lefter ul').dragsort({
			dragSelector:'li',
			dragEnd:function(){
				var data=$(this).parent().children('li').map(function(){
					return $(this).attr('CateId');
				}).get();
				$.get('?m=shop&a=products', {do_action:'shop.products_category_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'a',
			placeHolderTemplate:'<li class="placeHolder"></li>',
			scrollSpeed:5
		});
		
		$('#products .category .m_lefter ul li').hover(function(){
			$(this).children('.opt').show();
		}, function(){
			$(this).children('.opt').hide();
		});
		
		$('#pro-list-type .item').removeClass('item_on').each(function(){
			$(this).click(function(){
				$('#pro-list-type .item').removeClass('item_on');
				$(this).addClass('item_on');
				$('#shop_category_form input[name=ListTypeId]').val($(this).attr('ListTypeId'));
			});
		}).filter('[ListTypeId='+$('#shop_category_form input[name=ListTypeId]').val()+']').addClass('item_on');
		
		$('#shop_category_form').submit(function(){return false;});
		$('#shop_category_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#shop_category_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=shop&a=products&d=category';
				}else{
					alert(data.msg);
					$('#shop_category_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	products_property_init:function(){
		var ul=$('#products_property_form ul');
		var add_btn=ul.find('img[src*=add]');
		var add_fun=function(){
			add_btn.click(function(){
				ul.append(ul.children('li:last').clone(true));
				ul.children('li').eq(-2).children('img[src*=add]').remove();
				ul.find('li:last input').val('');
			});
		};
		add_fun();
		ul.find('img[src*=del]').click(function(){
			if(ul.children('li').size()>1){
				$(this).parent().remove();
				
				if(ul.find('img[src*=add]').size()==0){
					ul.children('li:last').append(add_btn);
					add_fun();
				}
			}
		});
		
		$('#products .property .m_lefter ul li').hover(function(){
			$(this).children('.opt').show();
		}, function(){
			$(this).children('.opt').hide();
		});
		
		$('#products_property_form').submit(function(){return false;});
		$('#products_property_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#products_property_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=shop&a=products&d=property';
				}else{
					alert(data.msg);
					$('#products_property_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	orders_init:function(){
		$('#search_form input:button').click(function(){
			window.location='./?'+$('#search_form').serialize()+'&do_action=shop.orders_export';
		});
		
		$("#search_form .output_btn").click(function(){
			window.location='./output.php?'+$('#search_form').serialize()+'&type=order_detail_list';
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
	},
	confirm_form_init:function(){
		$("#add_man").click(function(){
			var li_item = '<li class="item">满：￥ <input name="man_reach[]" value="" class="form_input" size="3" maxlength="10" type="text"> 送：￥ <input name="man_award[]" value="" class="form_input" size="3" maxlength="10" type="text"> <a><img src="/static/member/images/ico/del.gif" hspace="5"></a></li>';
			$("ul#man_panel").append(li_item);
		});
		
	
		
		$("#man_panel li.item a").live('click',function(){
				$(this).parent().remove();
		});
		
		$("#add_integral_law").click(function(){
			var li_item = '<li class="item">满：￥ <input name="Integral_Man[]" value="" class="form_input" size="3" maxlength="10" type="text"> 可用<input name="Integral_Use[]" value="" class="form_input" size="3" maxlength="10" type="text">个<a><img src="/static/member/images/ico/del.gif" hspace="5"></a></li>';
			$("ul#integral_panel").append(li_item);
		});
		
		$("#integral_panel li.item a").live('click',function(){
				$(this).parent().remove();
		});

		//添加分销规则
		$("#add_distribute").click(function(){
		  var li_item = '<li class=\"item\"><input name=\"distribute_from[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> 到 <input name=\"distribute_to[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> (含) &nbsp;&nbsp; <input name=\"distribute_rate[]\" value=\"\" class=\"form_input\" size=\"3\" maxlength=\"10\" type=\"text\"> <span>%</span> <a><img src=\"/static/member/images/ico/del.gif\" hspace=\"5\"></a></li>';
			$("ul#distribute_panel").append(li_item);

		});

		$("#distribute_panel li.item a").live('click',function(){
				$(this).parent().remove();
		});
		
		
	},
	distribute_init:function(){
		

		//编辑银行卡号
		/*$('a.mod-card').each(function(){
			
			$(this).click(function(){
			$('#Bank_Card').inputFormat('account');	
			$('#mod_account_card .h span').html(' ('+$(this).parent().parent().children('td[field=1]').html()+')');
			$('#mod_account_card form input[name=Bank_Card]').val('');
			$('#mod_account_card form input[name=UserID]').val($(this).parent().parent().attr('UserID'));
			$('#mod_account_card form').show();
			$('#mod_account_card .tips').hide();
			$('#mod_account_card').leanModal();
			
			});
		});
		*/
		
		$('a.mod-card').click(function(){
			$('#Bank_Card').inputFormat('account');	
			$('#Bank_Card').inputFormat('account');	
			$('#mod_account_card .h span').html(' ('+$(this).parent().parent().children('td[field=1]').html()+')');
			$('#mod_account_card form input[name=Bank_Card]').val('');
			$('#mod_account_card form input[name=UserID]').val($(this).parent().parent().attr('UserID'));
			$('#mod_account_card form').show();
			$('#mod_account_card .tips').hide();
			$('#mod_account_card').leanModal();
		});

		$('#mod_account_card form').submit(function(){return false;});
		$('#mod_account_card form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('/member/shop/ajax.php?', $('#mod_account_card form').serialize(), function(data){
				$('#mod_account_card form input:submit').attr('disabled', false);
				
				if(data.status == 1){
					$('#mod_account_card .tips').html('修改银行账号成功！').show();
				}else{
					$('#mod_account_card .tips').html('修改银行账号失败，出现未知错误！').show();
				};
				
				$('#mod_account_card form').hide();
				$('#mod_account_card').leanModal();
			}, 'json');
		});	

		var date_str=new Date();
		$('#search_form input[name=AccTime_S], #search_form input[name=AccTime_E]').omCalendar({
			date:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			maxDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
			showTime:true
		});	


	},
}