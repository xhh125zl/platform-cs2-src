var app_hotels_obj={	
	goods_init:function(){
		 var cur_date=new Date();
		 var tomo_date=new Date(cur_date.setDate(cur_date.getDate()+1)); 
		$('#app_hotels input[name=ReserveDate]').datepicker({
			minDate:new Date(),
			dateFormat:'yy-mm-dd'
		}).val((
			function(d){
				return [d.getFullYear(), d.getMonth()+1, d.getDate()].join('-');
			}
		)(new Date()));
		
		$('#app_hotels input[name=CheckOutDate]').datepicker({
			minDate:tomo_date,
			dateFormat:'yy-mm-dd'
		}).val((
			function(d){
				return [d.getFullYear(), d.getMonth()+1, d.getDate()].join('-');
			}
		)(tomo_date));
		
		$('.submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			
			$(this).attr('disabled', true).val('提交中...');
			$.post('/api/'+$('#UsersID').val()+'/app_hotels/ajax/', $('form').serialize(), function(data){
				if(data.status==1){
					$('input, select').attr('disabled', true);
					$('.submit').val('提交成功');
					$('#reserve_success').show().animate({
						bottom:150,
						opacity:'0.7'
					}, 1500).animate({
						opacity:0
					}, 4000);
				}else{
					global_obj.win_alert(data.msg);
					$('.submit').attr('disabled', false).val('提交订单');
				};
			}, 'json');
		});
	},
	detail_init:function(){
		$('.touchslider').touchSlider({
			mouseTouch:true,
			autoplay:true,
			delay:2000
		});
	}
}