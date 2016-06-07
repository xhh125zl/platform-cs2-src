var statistics_obj={
	stat_init:function(){	
		global_obj.chart_par.themes='column';
		
		global_obj.chart();
		
		$('.tab_bar').click(function(){
			switch(global_obj.chart_par.themes){
				case 'line':
					global_obj.chart_par.themes='column';
				break;
				case 'column':
					global_obj.chart_par.themes='line';
				break;
			}
			
			global_obj.chart();
			
			$(this).find('span').html(global_obj.chart_par.themes=='line'?'柱状图':'曲线图');
		});
	}
}