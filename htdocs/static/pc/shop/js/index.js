var index_obj={
	index_init:function(){
		function load_hot(page){
			$.ajax({
				type:'post',
				url:'',
				data:{p:page,action:'getHot'},
				success:function(data){
					if(data['list'] != ''){
						var htmltmp = "";
						$.each(data['list'],function(i){
							v = data['list'][i];
							htmltmp +=  '<div class="fore">'+
											'<a href="'+v['link']+'">'+
												'<div style="width:180px;height:180px;overflow:hidden;" class="fore_img"> <img src="'+v['ImgPath']+'"> </div>'+
												'<div class="fore_word">'+
													'<p>'+v['products_name']+'</p>'+
													'<span>￥'+v['products_pricex']+'</span><i>￥'+v['products_pricey']+'</i>'+
												'</div>'+
											'</a>'+
										'</div>';
						});
						var preHtml = '';
						if(page > 1){
							preHtml = $('#hotBox').html();
						}
						if(page >= data.totalpage){
						    $(".agine").attr('page', 1);
						}
						$("#hotBox").html(htmltmp + preHtml);
					}else{
					   //$(".agine").attr('page', 1);
					}
				},
				dataType:'json',
			});
		}
		load_hot($(".agine").attr('page'));
		$(".agine").click(function(){
			var page = parseInt($(this).attr('page'))+1;
			$(this).attr('page', page);
			load_hot($(".agine").attr('page'));
		});
	},
}