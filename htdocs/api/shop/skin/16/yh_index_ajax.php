<script>
    function load_page(page){
		$.ajax({
			type:'post',
			url:'?',
			data:{p:page},
			beforeSend:function(){
				$(".get_more").addClass('loading');
			},
			success:function(data){
				if(data['list'] != ''){
					var j = 0;
					$.each(data['list'],function(i){
						j++;
						v = data['list'][i];
						$htmltmp = '<div class="items">'+
								   '<div class="products_info">'+
									'<a href="'+v['link']+'"><img data-url="'+v['ImgPath']+'" src="/static/js/plugin/lazyload/grey.gif"/></a>'+
									'<p>'+v['Products_Name']+'</p>'+
								   '</div>'+
								   '<div class="products_price">&yen;'+v['Products_PriceX']+'<br /><span>已售'+v['Products_Sales']+'笔</span></p></div>'+
								   '<div class="spit"></div>'+
								  '</div>';
									
						$(".index_products").append($htmltmp);
						loaded();
					})
					if(data['totalpage'] == $(".get_more").attr('page')){
						$(".get_more").hide();
					}
				}else{
					$(".get_more").hide();
				}
			},
			complete:function(){
				$(".get_more").removeClass('loading');
			},
			dataType:'json',
		});
	}
	//加载第一页
	load_page($('.pullUp').attr('page'));
	$(".pullUp").click(function(){
		var page = parseInt($(this).attr('page'))+1;
		$(this).attr('page', page);
	    load_page(page);
	});
</script>
<!--懒加载--> 
<script type='text/javascript' src='/static/js/plugin/lazyload/jquery.scrollLoading.js'></script> 
<script language="javascript">
function loaded(){
	$("img").scrollLoading();
}
$(document).ready(function(){
	document.addEventListener('touchmove', function (e) { $("img").scrollLoading(); }, false);
	document.addEventListener('DOMContentLoaded', loaded, false); 
})
</script> 
