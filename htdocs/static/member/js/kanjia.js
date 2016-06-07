// 后台砍价js

$(document).ready(function(){
	$("#search").click(function(){
		var param = {cate_id:$("#Category").val(),keyword:$("#keyword").val(),action:'get_product'};
		$.post(base_url+'/member/kanjia/ajax.php',param,function(data){
			
			$("#select_product").html(data);
		});
		
	});
	
	
	//选择您所要选的产品
	$("#select_product").change(function(){
		$("#Products_ID").attr("value",$(this).val());
		$("#Products_Name").attr("value",$(this).find("option:selected").text().split('---')[0]);
		var price_part = $(this).find("option:selected").text().split('---')[1];
	    var length = price_part.length;
		var price = parseFloat(price_part.substring(1,length-1)).toFixed(2);
		$("#Products_Price").attr("value",price);
		$("#Products_Price_Txt").html(price);
	});
	
	//时间区间选择js初始化
	   var date_str = new Date();
		
		$('#add_form input[name=AccTime_S], #add_form input[name=AccTime_E]').omCalendar({
			date: new Date(date_str.getFullYear(), date_str.getMonth(), date_str.getDate(), 00, 00, 00),
			
			showTime: true
		});

		$("#begin_num,#end_num").change(function(){
			var num = parseInt($(this).attr('value'));
			
			if(!isNaN(num)){
				$(this).attr('value',parseInt($(this).attr('value')));
			}else{
				$(this).attr('value','');
			}
		});
	
		$('#add_form').submit(function() {

			if (global_obj.check_form($('*[notnull]'))) {
				return false
			};
			
			var Products_Price = Number($("#Products_Price").attr("value"));
			var Bottom_Price =  Number($("#Bottom_Price").attr("value"));
		
			var begin_num = Number($("#begin_num").attr('value'));
			var end_num = Number($("#end_num").attr('value'));

			if(Bottom_Price <0 || end_num < 0 || begin_num<0){
				alert('任何价格都不能是负数...');
				return false;
			}
		
			
			if(Bottom_Price >= Products_Price){
				alert('底价必须小于产品原价');
				return false;
			}
			
			if(end_num == begin_num){
				alert('起价与终价不能相等');
				return false;
			}
			
			if(end_num < begin_num){
				alert('终价必须大于起价');
				return false;
			}
			
			var max_num = Products_Price-Bottom_Price;
		
			if(end_num > max_num){
				alert('随机砍价区间必须在0到'+max_num+'之间');
				return false;
			}
			
			return true;

		});

	
});