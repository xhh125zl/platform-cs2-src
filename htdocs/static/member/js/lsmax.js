var lsmax_obj={
	lsmax_prize_edit:function(){
		global_obj.file_upload($('#ImgUpload'), $('#lsmax_form input[name=ImgPath]'), $('#ImgDetail'));
		$('#lsmax_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#lsmax_form .submit').attr('disabled', true);
			return true;
		});
	},
	
	lsmax_edit:function(){
		
		$('#lsmax_form input[name=Time]').click(function(){
			var date_str=new Date();
			$('#lsmax_form input[name=Time]').daterangepicker({
				timePicker:true,
				minDate:new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate()),
				format:'YYYY/MM/DD HH:mm:00'}
			)
		});
		
		$('.ext_checkbox input[name=AlbumsDisplay]').click(function(){
			var self=$(this);
			var dis='none';
			if(self.is(':checked'))
			{
				dis='block';
			}
			$('#ext_1').css('display',dis);												  
		});
		
		$('.ext_checkbox input[name=VotesDisplay]').click(function(){
			
			var self=$(this);
			var dis='none';
			if(self.is(':checked'))
			{
				dis='block';
			}
			$('#ext_2').css('display',dis);												  
		});

		$('.vote_add').click(function(){
			lsmax_obj.row_add('vote',1);
		});
		
		$('.ext_checkbox input[name=PriceDisplay]').click(function(){
			var self=$(this);
			var dis='none';
			if(self.is(':checked'))
			{
				dis='block';
			}
			$('#ext_3').css('display',dis);												  
		});

		$('.awards_add').click(function(){
			lsmax_obj.row_add('awards',0);
		});
		
		$('.ext_checkbox input[name=TurntableDisplay]').click(function(){
			var self=$(this);
			var dis='none';
			if(self.is(':checked'))
			{
				dis='block';
			}
			$('#ext_4').css('display',dis);												  
		});
		
		$('.ext_checkbox input[name=ShakeDisplay]').click(function(){
			var self=$(this);
			var dis='none';
			if(self.is(':checked'))
			{
				dis='block';
			}
			$('#ext_5').css('display',dis);												  
		});

		$('.wheel_add').click(function(){
			lsmax_obj.row_add('wheel',0);
		});
		
		global_obj.file_upload($('#BgUpload'), $('#lsmax_form input[name=BgPath]'), $('#BgDetail'));
		
		global_obj.file_upload($('#LogoUpload'), $('#lsmax_form input[name=LogoPath]'), $('#LogoDetail'));
		
		global_obj.file_upload($('#TDcodeUpload'), $('#lsmax_form input[name=TDcodePath]'), $('#TDcodeDetail'));
		
		$('#lsmax_form').submit(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$('#lsmax_form .submit').attr('disabled', true);
			return true;
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
	},
	
	votes_chart_init:function(){ //输出统计图
		$('.chart').height(500).highcharts({
			title:{text:''},
            credits:{enabled:false},
			chart: {
               	backgroundColor: 'rgba(0,0,0,0)',
				plotBackgroundColor: null,
				plotBorderWidth:0
            },
			tooltip:{
				pointFormat:'{series.name}: <b>{point.percentage:.2f}%</b>'
			},
			plotOptions:{
				pie:{
					allowPointSelect:true,
					cursor:'pointer',
					dataLabels:{
						enabled:true,
						color:'#000000',
						connectorColor:'#000000',
						format:'<b>{point.name}</b>: {point.percentage:.2f} %'
					}
				}
			},
			series:[{
				type:'pie',
				name:'百分比',
				data:pie_data
			}]
		});
	},
}