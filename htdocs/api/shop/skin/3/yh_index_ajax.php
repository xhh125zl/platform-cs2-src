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
					$.each(data['list'],function(i){
						v = data['list'][i];
						$htmltmp = '<div class="item">'+
										'<a href="'+v['link']+'">'+
											'<div class="img">'+(!(v['JSON']["ImgPath"]) ? '暂无图片' : '<img data-url="'+v['JSON']["ImgPath"][0]+'" src="/static/js/plugin/lazyload/grey.gif"/>')+'</div>'+
											'<strong>'+v["Products_Name"]+'</strong>'+
											'<span>￥'+v["Products_PriceX"]+'</span>'+
										'</a>'+
									'</div>';
									
						$("#more").append($htmltmp);
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
	$(".pullUp").each(function(){
	    load_page($(this).attr('page'));
	});
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
