var web_obj={
	web_config_init:function(){
		global_obj.file_upload($('#MusicUpload'), '', '', '', false, 1, function(filename,filepath){
			$('#config_form input[name=MusicPath]').val(filepath);
		}, '*.mp3', '500KB');
	},
	
	skin_init:function(){
		$('#skin li .item').click(function(){
			if(!confirm('您确定要选择此风格吗？')){return false};
			$.post('?', "do_action=web.skin_mod&SId="+$(this).attr('SId')+'&TradeId='+$(this).attr('TradeId'), function(data){
				if(data.status==1){
					window.location.reload();
				}
			}, 'json');
		});
	},
	
	home_init:function(){
		//加载上传按钮
		global_obj.file_upload($('#HomeFileUpload'), $('#home_form input[name=ImgPath]'), $('#home .web_skin_index_list').eq($('#home_form input[name=no]')).find('.img'));
		for(var i=0;i<5;i++){
			global_obj.file_upload($('#HomeFileUpload_'+i), $('#home_form input[name=ImgPathList\\[\\]]').eq(i), $('#home_form .b_r').eq(i));
		}
		
		$('.m_lefter a').attr('href', '#').css({'cursor':'default', 'text-decoration':'none'}).click(function(){
			$(this).blur();
			return false;
		});
		//加载版面内容
		for(i=0; i<web_skin_data.length; i++){
			var obj=$("#web_skin_index div").filter('[rel=edit-'+web_skin_data[i]['Postion']+']');
			obj.attr('no', i);
			if(web_skin_data[i]['ContentsType']==1){
				var dataObj=eval("("+web_skin_data[i]['ImgPath']+")");
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
				if(web_skin_data[i]['ImgPath'].indexOf('http://')!=-1){
					var s='';
				}else if(web_skin_data[i]['ImgPath'].indexOf('/u_file/')!=-1){
					var s=domain.img;
					web_skin_data[i]['ImgPath']=web_skin_data[i]['ImgPath'].replace('/u_file', '');
				}else if(web_skin_data[i]['ImgPath'].indexOf('/api/')!=-1){
					var s=domain.static;
				}else{
					var s='';
				}
				if(web_skin_data[i]['NeedLink']==1){
					obj.find('.text').html('<a href="">'+web_skin_data[i]['Title']+'</a>');
				}else{
					obj.find('.text').html(web_skin_data[i]['Title']);
				}
				obj.find('.img').html('<img src="'+s+web_skin_data[i]['ImgPath']+'" />');
			}
		}
		
		$('.web_skin_index_list div').after('<div class="mod">&nbsp;</div>');	//追加编辑按钮
		$('#web_skin_index .web_skin_index_list').hover(function(){$(this).find('.mod').show();}, function(){$(this).find('.mod').hide();});
		
		//点击图标切换编辑内容
		//$('#web_skin_index .web_skin_index_list .mod').html('&nbsp;<img src="static/images/ico/mod.gif" action="mod" />&nbsp;');
		//$('img[action=mod]').click(function(){
		$('#web_skin_index .web_skin_index_list .mod').click(function(){
			var parent=$(this).parent();
			var no=parent.attr('no');
		
			$('#SetHomeCurrentBox').remove();
			parent.append("<div id='SetHomeCurrentBox'></div>");
			$('#SetHomeCurrentBox').css({'height':parent.height()-10, 'width':parent.width()-10})
			$("#setbanner, #setimages").hide();
			$('.url_select').css('display', web_skin_data[no]['NeedLink']==1?'block':'none');
			
			if(web_skin_data[no]['ContentsType']==1){
				$("#setbanner").show();
				var dataImgPath=eval("("+web_skin_data[no]['ImgPath']+")");
				var dataUrl=eval("("+web_skin_data[no]['Url']+")");
				var dataTitle=eval("("+web_skin_data[no]['Title']+")");
				$('#home_form #setbanner .tips label').html(web_skin_data[no]['Width']+'*'+web_skin_data[no]['Height']);
				for(var i=0; i<dataImgPath.length; i++){
					$('#home_form input[name=ImgPathList\\[\\]]').eq(i).val(dataImgPath[i])
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
				$('#home_form input').filter('[name=Title]').val(web_skin_data[no]['Title'])
				.end().filter('[name=ImgPath]').val(web_skin_data[no]['ImgPath'])
				.end().filter('[name=Title]').focus();
				$('#home_form #setimages .tips label').html(web_skin_data[no]['Width']+'*'+web_skin_data[no]['Height']);
				if(web_skin_data[no]['Url']){
					$("#home_form select[name=Url] option[value='"+web_skin_data[no]['Url']+"']").attr("selected", true);
				}else{
					$("#home_form select[name=Url] option").eq(0).attr("selected", true);
				}
			}	
					
			$('#home_form input').filter('[name=ContentsType]').val(web_skin_data[no]['ContentsType'])
			.end().filter('[name=no]').val(no);
		});
		
		//加载默认内容
		//$('img[action=mod]').eq(0).click();
		$('#web_skin_index .web_skin_index_list .mod').eq(0).click();
		
		//ajax提交更新，返回
		$('#home_form').submit(function(){return false;});
		$('#home_form input:submit').click(function(){
			$(this).attr('disabled', true);
			$.post('?', $('#home_form').serialize()+'&do_action=web.set_home_mod&ajax=1', function(data){
				$('#home_form input:submit').attr('disabled', false);
				if(data.status==1){
					$('#home_mod_tips .tips').html('首页设置成功！');
					$('#home_mod_tips').leanModal();
					
					var _no=$('#home_form input[name=no]').val();
					var _v=$("div[no="+_no+"]");
					web_skin_data[_no]['ImgPath']=data.ImgPath;
					web_skin_data[_no]['Title']=data.Title;
					web_skin_data[_no]['Url']=data.Url;
					
					if(web_skin_data[_no]['ContentsType']==1){
						var dataImgPath=eval("("+web_skin_data[_no]['ImgPath']+")");
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
						if(web_skin_data[_no]['ImgPath'].indexOf('http://')!=-1){
							var s='';
						}else if(web_skin_data[_no]['ImgPath'].indexOf('/u_file/')!=-1){
							var s=domain.img;
							web_skin_data[_no]['ImgPath']=web_skin_data[_no]['ImgPath'].replace('/u_file', '');
						}else if(web_skin_data[_no]['ImgPath'].indexOf('/api/')!=-1){
							var s=domain.static;
						}else{
							var s='';
						}
						_v.find('.text').html('<a href="">'+web_skin_data[_no]['Title']+'</a>');
						_v.find('.img').html('<img src="'+s+web_skin_data[_no]['ImgPath']+'" />');
					}
				}else{
					$('#home_mod_tips .tips').html('首页设置失败，请重试！');
					$('#home_mod_tips').leanModal();
				};
			}, 'json');
		});
		
		$('#home_form .item .rows .b_l a[href=#web_home_img_del]').click(function(){
			var _no=$(this).attr('value');
			$('#home_form .b_r').eq(_no).html('');
			$('#home_form input[name=ImgPathList\\[\\]]').eq(_no).val('');
			this.blur();
			return false;
		});
	},
	
	column_init:function(){
		$('#column dl').dragsort({
			dragSelector:'dd.item',
			dragEnd:function(){
				var data=$('#column dl dd.item').map(function(){
					return $(this).attr('CId');
				}).get();
				$.get('?m=web&a=column', {do_action:'web.column_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'ul, a',
			placeHolderTemplate:'<dd class="item placeHolder"></dd>',
			scrollSpeed:5
		});
		$('#column ul').dragsort({
			dragSelector:'li',
			dragEnd:function(){
				var data=$('#column ul li').map(function(){
					return $(this).attr('AId');
				}).get();
				$.get('?m=web&a=column', {do_action:'web.column_article_order', sort_order:data.join('|')});
			},
			dragSelectorExclude:'a',
			placeHolderTemplate:'<li class="placeHolder"></li>',
			scrollSpeed:5
		});
		
		$('#column .item ul li').hover(function(){
			$(this).children('.opt').show();
		}, function(){
			$(this).children('.opt').hide();
		});
	},
	
	column_edit_init:function(){
		if($('#ColumnFileUpload').size()){
			global_obj.file_upload($('#ColumnFileUpload'), $('#column_form input[name=ImgPath]'), $('#ColumnImgDetail'), 'web_column');
			$('#ColumnImgDetail').html(global_obj.img_link($('#column_form input[name=ImgPath]').val()));
		}
		
		var ext_link_fun=function(){
			if($('#column_form input[name=ExtLink]:checked').size()){
				$('#Description_rows, #ListType_rows').hide();
				$('#LinkUrl_span').show();
			}else{
				$('#Description_rows, #ListType_rows').show();
				$('#LinkUrl_span').hide();
				form_init_fun();
			}
		}
		var form_init_fun=function(){
			if($('#column_form input[Name=CId]').val()!=0){	//修改栏目
				if($('#column_form input[name=ArticleCount]').val()==0){
					$('#Option_rows .pop_sub_menu').hide();
					$('#Description_rows').show();
					$('#ListType_rows').css({visibility:'hidden', display:'none'});
				}else{
					$('#Option_rows .pop_sub_menu').show();
					$('#Description_rows').hide();
					$('#ListType_rows').css({visibility:'visible', display:'block'});
				}
			}
			$('#column-article-list-type .item').removeClass('item_on').each(function(){
				$(this).click(function(){
					$('#column-article-list-type .item').removeClass('item_on');
					$(this).addClass('item_on');
					$('#column_form input[name=ListTypeId]').val($(this).attr('ListTypeId'));
				});
			}).filter('[ListTypeId='+$('#column_form input[name=ListTypeId]').val()+']').addClass('item_on');
		}
		
		ext_link_fun();
		$('#column_form input[name=ExtLink]').click(ext_link_fun);
		
		$('#column_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#column_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	column_article_init:function(){
		if($('#ColumnAtricleFileUpload').size()){
			global_obj.file_upload($('#ColumnAtricleFileUpload'), $('#column_article_form input[name=ImgPath]'), $('#ColumnAtricleImgDetail'), 'web_column_article');
			$('#ColumnAtricleImgDetail').html(global_obj.img_link($('#column_article_form input[name=ImgPath]').val()));
		}
		
		var ext_link_fun=function(){
			if($('#column_article_form input[name=ExtLink]:checked').size()){
				$('#BriefDescription_rows, #Description_rows').hide();
				$('#LinkUrl_span').show();
			}else{
				$('#BriefDescription_rows, #Description_rows').show();
				$('#LinkUrl_span').hide();
			}
		}
		ext_link_fun();
		$('#column_article_form input[name=ExtLink]').click(ext_link_fun);
		
		$('#column_article_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#column_article_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	products_init:function(){
		$('#PicDetail div span').on('click', function(){$(this).parent().remove();});
		var pic_count=parseInt($('#pic_count').html());
		
		var callback=function(imgpath){
			if($('#PicDetail div').size()>=pic_count){
				alert('您上传的图片数量已经超过5张，不能再上传！');
				return;
			}
			$('#PicDetail').append('<div>'+global_obj.img_link(imgpath)+'<span>删除</span><input type="hidden" name="PicPath[]" value="'+imgpath+'" /></div>');
			$('#PicDetail div span').off('click').on('click', function(){$(this).parent().remove();});
		};
		
		global_obj.file_upload($('#PicUpload'), '', '', 'web_products', true, pic_count, callback);
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
		if($('#CategoryFileUpload').size()){
			global_obj.file_upload($('#CategoryFileUpload'), $('#web_category_form input[name=PicPath]'), $('#CategoryImgDetail'), 'web_category');
			$('#CategoryImgDetail').html(global_obj.img_link($('#web_category_form input[name=PicPath]').val()));
		}
		
		$('#products .category .m_lefter dl').dragsort({
			dragSelector:'dd',
			dragEnd:function(){
				var data=$(this).parent().children('dd').map(function(){
					return $(this).attr('CateId');
				}).get();
				$.get('?m=web&a=products', {do_action:'web.products_category_order', sort_order:data.join('|')});
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
				$.get('?m=web&a=products', {do_action:'web.products_category_order', sort_order:data.join('|')});
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
		
		$('#web_category_form').submit(function(){return false;});
		$('#web_category_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#web_category_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=web&a=products&d=category';
				}else{
					alert(data.msg);
					$('#web_category_form input:submit').attr('disabled', false);
				}
			}, 'json');
		});
	},
	
	lbs_init:function(){
		global_obj.file_upload($('#ImgUpload'), $('#lbs_form input[name=ImgPath]'), $('#ImgDetail'));
		$('#ImgDetail').html(global_obj.img_link($('#lbs_form input[name=ImgPath]').val()));
		global_obj.map_init();
		
		var LbsLinkToStores=function(){
			if($('#lbs_form input[name=LbsLinkToStores]').is(':checked')){
				$('#lbs_form .not_lbs').hide();
				$('#lbs_form #Address').removeAttr('notnull');
			}else{
				$('#lbs_form .not_lbs').show();
				$('#lbs_form #Address').attr('notnull', '');
			}
		};
		$('#lbs_form input[name=LbsLinkToStores]').click(LbsLinkToStores);
		LbsLinkToStores();
		
		$('#lbs_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#lbs_form input:submit').attr('disabled', true);
			return true;
		});
	},
	
	//---------- diy页面 ----------//
	diy_init:function(){
		web_obj.firstrproperty = $(".ps2").html(); //初始化信息
		$(".square_sprite").each(function(index, element) {
			$(element).dragObj({	
				"isClone" 	: true,	
				"hitPoint"	: {	
					"hitType"	: true,	
					"hitObj"	: $(".ipad")	
				}	
			}); 	
		});
		$(".ipad .sprite1").each(function(index, element) {
			var packagename = $(element).attr("packagename");
			$(element).find(".dragPart").orderDrag({"package":packagename});  
        });
		//------ 超链接地址选择 ------//
		
		//------ 编辑器 ------//	
//debug		myeditor = CKEDITOR.replace( 'content' , {
//			width	 : 340,	
//			height	 : 150,	
//			toolbar :	[	
//				//['TextColor' ,'FontSize' ,'Bold' ,'Italic' ,'Underline', 'JustifyLeft','JustifyCenter','JustifyRight']
//				['Source', 'Image', 'Bold', 'Link','Unlink'], ['JustifyLeft','JustifyCenter','JustifyRight', 'TextColor', 'BGColor', 'FontSize']
//			]	
//		});
		//------ 组件：一行三列p0上传 -------//
		global_obj.file_upload($("#upfile_p0_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p0ImgFrame").eq(0).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p0_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p0ImgFrame").eq(1).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		global_obj.file_upload($("#upfile_p0_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p0ImgFrame").eq(2).find(".imgObj").html('<img src="'+filepath+'" width="95" />');
			}
		});
		//------ 组件：一行两列p1上传 -------//
		global_obj.file_upload($("#upfile_p1_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .imgObj").eq(0).html('<img src="'+filepath+'" width="146" />');
			}
		});
		global_obj.file_upload($("#upfile_p1_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .imgObj").eq(1).html('<img src="'+filepath+'" width="146" />');
			}
		});
		//------ 组件：图片p3上传 -------//
		global_obj.file_upload($("#upfile"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .imgObj").html('<img src="'+filepath+'" width="292" />');
			}
		});
		//------ 组件：图片p4上传 -------//
		global_obj.file_upload($("#upfile_p4_0"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(0).attr("src",filepath).show().siblings().hide();
				$("#p4LookDetail0").html('<img src="'+filepath+'" width="50" />');
			}
		});
		global_obj.file_upload($("#upfile_p4_1"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(1).attr("src",filepath);
				$("#p4LookDetail1").html('<img src="'+filepath+'" width="50" />');
			}
		});
		global_obj.file_upload($("#upfile_p4_2"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(2).attr("src",filepath);
				$("#p4LookDetail2").html('<img src="'+filepath+'" width="50" />');
			}
		});
		global_obj.file_upload($("#upfile_p4_3"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(3).attr("src",filepath);
				$("#p4LookDetail3").html('<img src="'+filepath+'" width="50" />');
			}
		});
		global_obj.file_upload($("#upfile_p4_4"),'','',1,true,1,function(filename,filepath){
			if($(".selectObj").hasClass(pName)){
				$(".selectObj .dragPart .p4ImgFrame img").eq(4).attr("src",filepath);
				$("#p4LookDetail4").html('<img src="'+filepath+'" width="50" />');
			}
		});
	},
	//组件元素操作
	"Evt1"	 : "",
	"Evt2"	 : "onmouseover='this.style.border=\"1px #3270AD solid\"' onmouseout = 'this.style.border = \"1px #F0F0EE solid\"' ",
	"Evt3"	 : "onmouseover='this.src=\""+domain.static+"/member/images/web/diy/select_1.png\"' onmouseout = 'this.src=\""+domain.static+"/member/images/web/diy/select.png\"'",
	"cloIco" : domain.static+"/member/images/web/diy/del.png",
	"firstrproperty" : "", 
	//------------ 组件0start ------------//
	"p0"	: { //一行三列
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p0 sprite1' packageName='"+packageName+"' link0='' link1='' link2=''>";	
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p0ImgFrame'>";
			$str +=				"<div class='imgObj'>图片区域1</div>";
			$str +=				"<div class='wordObj'>文字区域1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p0ImgFrame'>";
			$str +=				"<div class='imgObj'>图片区域2</div>";
			$str +=				"<div class='wordObj'>文字区域2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p0ImgFrame'>";
			$str +=				"<div class='imgObj'>图片区域3</div>";
			$str +=				"<div class='wordObj'>文字区域3</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='web_obj.delObjEvt(this,\"p0\");'><img "+web_obj.Evt1+" src='"+web_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<3;i++)
			{
				var $value = $(".ps2_frmae_p0 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			web_obj.btnCtrl();
		}
	},
	//------------ 组件0end ------------//
	//------------ 组件1start ------------//
	"p1"	: { //一行两列
		"packageElement"	: function(packageName){
			$str = "";
			$str += "<div class='p1 sprite1' packageName='"+packageName+"' link0='' link1=''>";	
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p1ImgFrame'>";
			$str +=				"<div class='imgObj'>图片区域1</div>";
			$str +=				"<div class='wordObj'>文字区域1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=  		"<div class='p1ImgFrame'>";
			$str +=				"<div class='imgObj'>图片区域2</div>";
			$str +=				"<div class='wordObj'>文字区域2</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='web_obj.delObjEvt(this,\"p1\");'><img "+web_obj.Evt1+" src='"+web_obj.cloIco+"' /></div>";
			$str +=  	"<div class='clean'></div>";
			$str += "</div>";
			return $str;
		},
		"insertWord"	: function(packageName){
			for(var i=0;i<2;i++)
			{
				var $value = $(".ps2_frmae_p1 .img_name").eq(i).find("input").val();
				if($(".selectObj").hasClass(packageName)){
					if($value){
						$(".selectObj .wordObj").eq(i).show().text($value);
					} else {
						$(".selectObj .wordObj").eq(i).hide();
						$(".selectObj .wordObj").eq(i).html("");
					}
				}
			}
			web_obj.btnCtrl();
		}
	},
	//------------ 组件1end ------------//
	//------------ 组件2start ------------//
	"p2"	: { //文字组件
		"packageElement"	: function(packageName){
			$str  = "";
			$str += "<div class='p2 sprite1' packageName='"+packageName+"' link0=''>";
			$str += 	"<div class='dragPart'>文字组件：请编写文字</div>";
			$str +=		"<div class='delObj hand' onclick='web_obj.delObjEvt(this,\"p2\");'><img "+web_obj.Evt1+" src='"+web_obj.cloIco+"' /></div>";
			$str +=	"</div>";
			return $str;
		},
		"insertHtml"	: function(packageName){
			var $html = CKEDITOR.instances.content.getData()
			if($(".selectObj").hasClass(packageName)){
				$(".selectObj .dragPart").html($html);
			}
			web_obj.btnCtrl();
		}
	},
	//------------ 组件2end ------------//
	//------------ 组件3start ------------//
	"p3"	: { //图片组件
		"packageElement"	: function(packageName){
			$str  = "";
			$str += "<div class='p3 sprite1' packageName='"+packageName+"' link0=''>";
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p3ImgFrame'>";
			$str +=				"<div class='imgObj'>图片区域1</div>";
			$str +=				"<div class='wordObj'>文字区域1</div>";
			$str +=				"<div class='clean'></div>";
			$str +=			"</div>";
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='web_obj.delObjEvt(this,\"p3\");'><img "+web_obj.Evt1+" src='"+web_obj.cloIco+"' /></div>";
			$str +=		"<div class='clean'></div>";
			$str +=	"</div>";
			return $str;
		},
		"insertWord"	: function(packageName){ //文字编辑
			var $value = $(".ps2_frmae_p3 .img_name input").val();
			if($(".selectObj").hasClass(packageName)){
				if($value){
					$(".selectObj .wordObj").show().text($value);
				} else {
					$(".selectObj .wordObj").hide();
					$(".selectObj .wordObj").html("");
				}
			}
			web_obj.btnCtrl();
		}
	},
	//------------ 组件3end ------------//
	//------------ 组件4start ------------//
	"p4"	: { //幻灯片组件
		"packageElement"	: function(packageName){
			$str  = "";
			$str += "<div class='p4 sprite1' packageName='"+packageName+"' link0='' link1='' link2='' link3='' link4=''>";
			$str += 	"<div class='dragPart'>";
			$str +=  		"<div class='p4ImgFrame'>";
			$str +=				"<span>幻灯片区域</span>";
			$str += 			"<img width='292' style='display:none' /><img width='292' style='display:none' /><img width='292' style='display:none' /><img width='292' style='display:none' /><img width='292' style='display:none' />";
			$str +=			"</div>"
			$str +=		"</div>";
			$str +=		"<div class='delObj hand' onclick='web_obj.delObjEvt(this,\"p4\");'><img "+web_obj.Evt1+" src='"+web_obj.cloIco+"' /></div>";
			$str +=	"</div>";
			return $str;
		},
		"insertWord"	: function(){
			web_obj.btnCtrl();
		}
	},
	//------------ 组件4end ------------//
	"p5"	: { //电话
		"packageElement"	: function(packageName){
			$str  = "";
			$str += "<div class='p5 sprite1' packageName='"+packageName+"' onclick='web_obj.selectElement(this,\"p5\")'>";
			$str += 	"<div class='dragPart'></div>";
			$str +=		"<div class='delObj hand' onclick='web_obj.delObjEvt(this,\"p5\");'><img "+web_obj.Evt1+" src='"+web_obj.cloIco+"' /></div>";
			$str +=	"</div>";
			return $str;
		},
		"insertHtml"	: function(packageName){
			var $html = $(".phoneSprite textarea").val();
			$html = $html.replace(/\n/g, "<br />");
			if($(".selectObj").hasClass(packageName)){
				$(".selectObj .dragPart").html($html);
			}
			web_obj.btnCtrl();
		}
	},
	//------------ 公用函数start ------------//
	"delObjEvt" : function(e,packageName){	//清除元素 
		if(pName == packageName){
			pName = "";
		}
		if(confirm("您确定删除该组件吗?")){
			$(e).parents("."+packageName).remove();
		}
	},
	"selectElement" : function(e,packageName){
		var obj = $(e).parent("."+packageName);
		pName = packageName;
		$(obj).css("border","1px #f00 dashed").addClass("selectObj").siblings().css("border","1px #ccc dashed").removeClass("selectObj");
		web_obj.showproElement(packageName);
	},
	"showproElement": function(packageName){ //显示属性编辑面板
		switch(packageName)
		{
			case "p0":
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $color2 = $(".ipad .selectObj").attr("color2");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					var $bg2    = $(".ipad .selectObj").attr("background2");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$color2 = $color2?$color2:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					$bg2    = $bg2?$bg2:"#4C4C4C";
					
					$("#colorSelectorWordp0_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp0_1").val($color1).css("background",$color1);
					$("#colorSelectorWordp0_2").val($color2).css("background",$color2);
					$("#colorSelectorBgp0_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp0_1").val($bg1).css("background",$bg1);
					$("#colorSelectorBgp0_2").val($bg2).css("background",$bg2);
					$(".ps2_frmae_p0 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p0 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p0 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p0")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
					
                });
			break;
			case "p1":
				$(".ipad .selectObj").find(".wordObj").each(function(index, element) {
                    var $html = $(element).text();
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $color0 = $(".ipad .selectObj").attr("color0");
					var $color1 = $(".ipad .selectObj").attr("color1");
					var $bg0    = $(".ipad .selectObj").attr("background0");
					var $bg1    = $(".ipad .selectObj").attr("background1");
					$color0 = $color0?$color0:"#ffffff";
					$color1 = $color1?$color1:"#ffffff";
					$bg0    = $bg0?$bg0:"#4C4C4C";
					$bg1    = $bg1?$bg1:"#4C4C4C";
					
					$("#colorSelectorWordp1_0").val($color0).css("background",$color0);
					$("#colorSelectorWordp1_1").val($color1).css("background",$color1);
					$("#colorSelectorBgp1_0").val($bg0).css("background",$bg0);
					$("#colorSelectorBgp1_1").val($bg1).css("background",$bg1);

					$(".ps2_frmae_p1 .img_name input").eq(index).val($html);
					$(".ps2_frmae_p1 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p1 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p1")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
                });
			break;
			case "p2":	
				var $html = $(".ipad .selectObj").find(".dragPart").html();
				var $link = $(".ipad .selectObj").attr("link0");
				myeditor.setData($html);
				$(".ps2_frmae_p2 .selectLink select").val($link);
				$(".ps2_frmae_p2 .selectLink select").change(function(){
					var $value = $(this).val(); //选择当前链接
					if($(".selectObj").hasClass("p2")){
						$(".selectObj").attr("link0",$value);
					}
				});
			break;
			case "p3":
				var $html = $(".ipad .selectObj").find(".wordObj").text();
				var $link = $(".ipad .selectObj").attr("link0");
				var $color0 = $(".ipad .selectObj").attr("color0");
				var $bg0    = $(".ipad .selectObj").attr("background0");
				$color0 = $color0?$color0:"#ffffff";
				$bg0    = $bg0?$bg0:"#4C4C4C";
				
				$("#colorSelectorWordp3_0").val($color0).css("background",$color0);
				$("#colorSelectorBgp3_0").val($bg0).css("background",$bg0);
				
				$(".ps2_frmae_p3 .img_name input").val($html);
				$(".ps2_frmae_p3 .selectLink select").val($link);
				$(".ps2_frmae_p3 .selectLink select").change(function(){
					var $value = $(this).val(); //选择当前链接
					if($(".selectObj").hasClass("p3")){
						$(".selectObj").attr("link0",$value);
					}
				});
			break;
			case "p4":
				$(".ps2_frmae_p4 .lookDetail").html("");
				$(".ipad .selectObj .p4ImgFrame").find("img").each(function(index, element) {
					var $link = $(".ipad .selectObj").attr("link"+index);
					var $src  = $(this).attr("src");
					if($src){
						$(".ps2_frmae_p4 .lookDetail").eq(index).html("<img src='"+$src+"' width='50' />");
					}
					$(".ps2_frmae_p4 .selectLink select").eq(index).val($link);
					$(".ps2_frmae_p4 .selectLink select").eq(index).change(function(){
						var $value = $(this).val();
						if($(".selectObj").hasClass("p4")){
							$(".selectObj").attr("link"+index,$value);
						}
					});
                });
			break;
			case "p5":
				var $html = $(".ipad .selectObj").find(".dragPart").html();
				var $color= $(".ipad .selectObj").attr("color0");
				var $bg   = $(".ipad .selectObj").attr("background0");
				var $fz   = $(".ipad .selectObj").attr("fontsize0");
				$color = ($color=="" || $color=="undefined")?"#ffffff":$color;
				$bg    = ($bg=="" || $bg=="undefined")?"#4C4C4C":$bg;
				$fz    = ($fz=="" || $fz=="undefined")?"14":$fz;
				
				
				$("#colorSelectorWordp5").css("background",$color).val($color);
				$("#colorSelectorBgp5").css("background",$bg).val($bg);
				$(".fontSize_p5").val($fz);
				
				$html = $html.replace(/<br \/>/g, "<br>");
				$html = $html.replace(/<BR \/>/g, "<br>");
				$html = $html.replace(/<BR>/g, "<br>");
				$html = $html.replace(/<br>/g, "\n");
				$(".phoneSprite textarea").val($html);
			break;
		}
		$(".ps2_frmae_"+packageName).show().siblings().hide();
	},
	"colorPicker": function(){
		//p0 一行三列
		$('#colorSelectorWordp0_0,#colorSelectorWordp0_1,#colorSelectorWordp0_2').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp0_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp0_0,#colorSelectorBgp0_1,#colorSelectorBgp0_2').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp0_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		//p1 一行两列
		$('#colorSelectorWordp1_0,#colorSelectorWordp1_1').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp1_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp1_0,#colorSelectorBgp1_1').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp1_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		//p3 图片文字
		$('#colorSelectorWordp3_0').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorWordp3_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("color","#"+hex);
				$(".selectObj").attr("color"+$num,"#"+hex);
			}
		});
		$('#colorSelectorBgp3_0').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				var $id   = $(el).attr("id");
				$id = $id.toString();
				var $num  = $id.replace("colorSelectorBgp3_","");
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .wordObj").eq($num).css("background","#"+hex);
				$(".selectObj").attr("background"+$num,"#"+hex);
			}
		});
		//p5 电话号码
		$('#colorSelectorWordp5').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj .dragPart").css("color","#"+hex);
				$(".selectObj").attr("color0","#"+hex);
			}
		});
		$('#colorSelectorBgp5').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val("#"+hex).css("background","#"+hex);
				$(el).ColorPickerHide();
				$(".selectObj").css("background","#"+hex);
				$(".selectObj").attr("background0","#"+hex);
			}
		});
		$(".fontSize_p5").change(function(){ //选择字号
			var $value = $(this).val();
			$(".selectObj .dragPart").css("font-size",$value+"px");
			$(".selectObj").attr("fontsize0",$value);
		})
		
	},
	"btnCtrl"	: function(){
		var str 		 = "";
		//json构造
		var $html        = $(".ipad .sprite1").html();
		$(".ipad .sprite1").each(function(index, element) {
			var $packageName = $(this).attr("packagename");
			switch($packageName){
				case "p0": //一行三列
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $color2		 = $(this).attr("color2");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $bg2		 = $(this).attr("background2");
					var $img0		 = $(this).find(".p0ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p0ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $img2		 = $(this).find(".p0ImgFrame").eq(2).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p0ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p0ImgFrame").eq(1).find(".wordObj").text();
					var $txt2		 = $(this).find(".p0ImgFrame").eq(2).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p0",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+"|"+$txt2+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+"|"+$color2+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+"|"+$bg2+'"';
						str += "},";
					
				break;
				case "p1": //一行两列
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $color0		 = $(this).attr("color0");
					var $color1		 = $(this).attr("color1");
					var $bg0		 = $(this).attr("background0");
					var $bg1		 = $(this).attr("background1");
					var $img0		 = $(this).find(".p1ImgFrame").eq(0).find(".imgObj img").attr("src");
					var $img1		 = $(this).find(".p1ImgFrame").eq(1).find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p1ImgFrame").eq(0).find(".wordObj").text();
					var $txt1		 = $(this).find(".p1ImgFrame").eq(1).find(".wordObj").text();
						str += "{";
						str += 		'"type" : "p1",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+'",';
						str +=		'"txt"  : "'+$txt0+"|"+$txt1+'",';
						str +=		'"txtColor": "'+$color0+"|"+$color1+'",';
						str +=		'"bgColor" : "'+$bg0+"|"+$bg1+'"';
						str += "},";
				break;
				case "p2": //文字
					var $link0       = $(this).attr("link0");
					var $txt0		 = $(this).find(".dragPart").html();
					$txt0 = $txt0.replace(/\"/g,"'");
					$txt0 = $txt0.replace(/\n/g,"");
					$txt0 = $txt0.replace(/\t/g,"");
					
						str += "{";
						str += 		'"type" : "p2",';
						str +=  	'"url"  : "'+$link0+'",';
						str +=		'"pic"  : "",';
						str +=		'"txt"  : "'+$txt0+'"';
						str += "},";
				break;
				case "p3": //图片
					var $link0       = $(this).attr("link0");
					var $img0		 = $(this).find(".p3ImgFrame").find(".imgObj img").attr("src");
					var $txt0		 = $(this).find(".p3ImgFrame").find(".wordObj").text();
					var $color0		 = $(this).attr("color0");
					var $bg0		 = $(this).attr("background0");
						str += "{";
						str += 		'"type" : "p3",';
						str +=  	'"url"  : "'+$link0+'",';
						str +=		'"pic"  : "'+$img0+'",';
						str +=		'"txt"  : "'+$txt0+'",';
						str +=		'"txtColor": "'+$color0+'",';
						str +=		'"bgColor" : "'+$bg0+'"';
						str += "},";
				break;
				case "p4": //幻灯片
					var $link0       = $(this).attr("link0");
					var $link1       = $(this).attr("link1");
					var $link2       = $(this).attr("link2");
					var $link3       = $(this).attr("link3");
					var $link4       = $(this).attr("link4");
					var $img0		 = $(this).find(".p4ImgFrame img").eq(0).attr("src");
					var $img1		 = $(this).find(".p4ImgFrame img").eq(1).attr("src");
					var $img2		 = $(this).find(".p4ImgFrame img").eq(2).attr("src");
					var $img3		 = $(this).find(".p4ImgFrame img").eq(3).attr("src");
					var $img4		 = $(this).find(".p4ImgFrame img").eq(4).attr("src");
						str += "{";
						str += 		'"type" : "p4",';
						str +=  	'"url"  : "'+$link0+"|"+$link1+"|"+$link2+"|"+$link3+"|"+$link4+'",';
						str +=		'"pic"  : "'+$img0+"|"+$img1+"|"+$img2+"|"+$img3+"|"+$img4+'",';
						str +=		'"txt"  : ""';
						str += "},";
				break;
				case "p5": //电话
					var $txt0		 = $(this).find(".dragPart").html();
					var $color0		 = $(this).attr("color0");
					var $bg0		 = $(this).attr("background0");
					var $fz0		 = $(this).attr("fontsize0");
						str += "{";
						str += 		'"type" : "p5",';
						str +=  	'"url"  : "",';
						str +=		'"pic"  : "",';
						str +=		'"txtColor": "'+$color0+'",';
						str +=		'"bgColor" : "'+$bg0+'",';
						str +=		'"fontSize" : "'+$fz0+'",';
						str +=		'"txt"  : "'+$txt0+'"';
						str += "},";
				break;
			}
		});
		//json构造
		str = "["+str.substr(0,str.length-1)+"]"; //json end

		$.ajax({
			type    : "POST",
			url     : "?",
			data    : "gruopPackage="+encodeURIComponent(str)+"&do_action=web.home_diy&date="+new Date(),
			dataType:"json",
			success: function(data){	
				if(data.status == 1){
					alert("页面保存成功！");
				}
			}
		});
	}
	//------------ 公用函数end ------------//
}