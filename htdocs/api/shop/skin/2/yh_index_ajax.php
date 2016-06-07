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
						$htmltmp = '<a href="'+v['link']+'">'+
										'<div class="item">'+
											'<div class="img">'+((!v['JSON']["ImgPath"]) ? '暂无图片' : '<img data-url="'+v['JSON']["ImgPath"][0]+'" src="/static/js/plugin/lazyload/grey.gif" />')+'</div>'+
											'<div class="info">'+
												'<h1>'+v["Products_Name"]+'</h1>'+
												'<h2>￥'+v["Products_PriceX"]+'</h2>'+
												'<h3>'+v["Products_BriefDescription"]+'</h3>'+
											'</div>'+
											'<div class="detail"><span></span></div>'+
										'</div>'+
									'</a>'+
									(j%2==0 ? '<div class="clear"></div>' : '');
									
						$(".list-0").append($htmltmp);
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
