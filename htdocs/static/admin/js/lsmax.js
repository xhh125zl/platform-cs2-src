/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var lsmax_obj={
	lsmax_prize_edit:function(){
		global_obj.file_upload($('#PicUpload'), $('#lsmax_form input[name=PicPath]'), $('#PicDetail'));
		$('#PicDetail').html(global_obj.img_link($('#lsmax_form input[name=PicPath]').val()));	
		
		$('#lsmax_form').submit(function(){return false;});
		$('#lsmax_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true);
			$.post('?', $('#lsmax_form').serialize(), function(data){
				if(data.status==1){
					window.location='?m=lsmax&a=prize';
				}else{
					$('#lsmax_form input:submit').attr('disabled', false);
					alert(data.msg);
				};
			}, 'json');
		});
	},
	
	lsmax_edit:function(){
		var date_str=new Date();
		$('#lsmax_form input[name=Time]').daterangepicker({
		timePicker:true,
		minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
		format:'YYYY/MM/DD HH:mm:00'});
		
		$('.ext_checkbox input[type="checkbox"]').click(function(){
			var self=$(this);
			var v=self.attr('value');
			var dis='none';
			if(self.is(':checked'))
			{
				dis='block';
			}
			$('#ext_'+v).css('display',dis);												  
		});
		
		$('.awards_add').click(function(){
			lsmax_obj.row_add('awards',0);
		});
		
		$('.wheel_add').click(function(){
			lsmax_obj.row_add('wheel',0);
		});
		
		$('.vote_add').click(function(){
			lsmax_obj.row_add('vote',1);
		});
		
		$('#awardsbox').find('.items_del').click(function(){		   
			var self=$(this);	//alert('ok');
			var AId=self.parent().attr('AId');
			
			$.get('?', 'ajax=1&do_action=lsmax.lsmax_awards_del&AId='+AId, function(data){
				if(data.status==1){
					self.parent().parent().remove();
				}else{
					alert('出现未知错误！');
				}
			}, 'json');
		});
		
		$('#wheelbox').find('.items_del').click(function(){		   
			var self=$(this);	//alert('ok');
			var WId=self.parent().attr('WId');
			
			$.get('?', 'ajax=1&do_action=lsmax.lsmax_wheel_del&WId='+WId, function(data){
				///alert(data);
				if(data.status==1){
					self.parent().parent().remove();
				}else{
					alert('出现未知错误！');
				}
			}, 'json');		   
		});

		$('#votebox').find('.items_del').click(function(){
			var self=$(this);	//alert('ok');
			var VId=self.parent().attr('VId');
			
			$.get('?', 'ajax=1&do_action=lsmax.lsmax_vote_del&VId='+VId, function(data){
				if(data.status==1){
					self.parent().remove();
				}else{
					alert('出现未知错误！');
				}
			}, 'json');	   
		});
		
		global_obj.file_upload($('#BgUpload'), $('#lsmax_form input[name=BgPath]'), $('#BgDetail'));
		$('#BgDetail').html(global_obj.img_link($('#lsmax_form input[name=BgPath]').val()));
		global_obj.file_upload($('#TDcodeUpload'), $('#lsmax_form input[name=TDcodePath]'), $('#TDcodeDetail'));
		$('#TDcodeDetail').html(global_obj.img_link($('#lsmax_form input[name=TDcodePath]').val()));
		
		var callback=function(imgpath){
			if($('#PicDetail div').size()>=20){
				alert('您上传的图片数量已经超过20张，不能再上传！');
				return;
			}
			
			$('#upload_img').append('<li>'+$('#for_copy').html()+'</li>');
			$('#upload_img li:last').find('.imgpath a').attr('href', imgpath).end().find('.imgpath img').attr('src', imgpath).end().find('.del').click(function(){
				$(this).parent().parent().remove();
			}).end().find('input[name=ImgPath\\[\\]]').val(imgpath);
		};
		
		global_obj.file_upload($('#PhotoUpload'), '', '', 'lsmax_photo', true, 20, callback);
		
		$('#upload_img').find('.del').click(function(){
			var PId=$(this).parent().attr('PId');
			var self=$(this);
			$.get('?', 'ajax=1&do_action=lsmax.lsmax_photo_del&PId='+PId, function(data){
				if(data.status==1){
					self.parent().parent().remove();
				}else{
					alert('出现未知错误！');
				}
			}, 'json');
		});
		
		$('#lsmax_form').submit(function(){return false;});
		$('#lsmax_form input:submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('?', $('#lsmax_form').serialize(), function(data){
			//alert(data);
				if(data.status==1){
					window.location='?m=lsmax';
				}else{
					$('#lsmax_form input:submit').attr('disabled', false);
					alert(data.msg);
				};
			}, 'json');
		});
	},
	
	row_add:function(id,c){
		var add_cont=$('#for_'+id).html();
		$('#'+id+'box').append(add_cont).end();
		$('#'+id+'box').find('.items_del').click(function(){
			if(c==1){
				$(this).parent().remove();			
			}else{
				$(this).parent().parent().remove();			
			}									  
		});
	},
	
	sn_init:function(){
		$("#sncode td a[href=#used]").click(function(){
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
			$.post('?', 'ajax=1&do_action=lsmax.sncode_used&MId='+jsonData.MId+'&SId='+jsonData.SId+'&Tb='+jsonData.Tb, function(data){
				if(data.status==1){
					//window.self.location.reload();
					window.self.location='?m=lsmax&a=sncode&MId='+jsonData.MId+'&type='+jsonData.type;
				}else{
					alert('调整失败，出现未知错误！');
				};
			}, 'json');
		});
	},
	
	lsmax_user:function()
	{
		$("#lsmax td a[href=#lock]").click(function(){
			
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
		
			$.post('?', 'ajax=1&do_action=lsmax.user_lock&UserId='+jsonData.UserId, function(data){
				//alert(data);
				if(data.status==1){
					//window.self.location.reload();
					window.self.location='?m=lsmax&a=user&MId='+jsonData.MId+'&page='+jsonData.page;
				}else{
					alert('调整失败，出现未知错误！');
				};
			}, 'json');
		});	
		
		
		$("#lsmax td a[href=#nolock]").click(function(){
			
			var jsonData=eval('('+$(this).parent().attr('Data')+')');
		
			$.post('?', 'ajax=1&do_action=lsmax.user_nolock&UserId='+jsonData.UserId, function(data){
				//alert(data);
				if(data.status==1){
					//window.self.location.reload();
					window.self.location='?m=lsmax&a=user&MId='+jsonData.MId+'&page='+jsonData.page;
				}else{
					alert('调整失败，出现未知错误！');
				};
			}, 'json');
		});
	}
}