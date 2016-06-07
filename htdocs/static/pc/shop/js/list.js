function encode(s){
  return s.replace(/&/g,"&").replace(/</g,'<').replace(/>/g,'>').replace(/([\\\.\*\[\]\(\)\$\^])/g,'\\$1');
}
function decode(s){
  return s.replace(/\\([\\\.\*\[\]\(\)\$\^])/g,'$1').replace(/>/g,'>').replace(/</g,'<').replace(/&/g,'&');
}
function highlight(s){
  if (s.length==0){
    alert('搜索关键词未填写！');
    return false;
  }
  s=encode(s);
  var obj=document.getElementById('listBox');
  var t=obj.innerHTML.replace(/<span\s+class=.?highlight.?>([^<>]*)<\/span>/gi,'$1');
  obj.innerHTML=t;
  var cnt=loopSearch(s,obj);
  t=obj.innerHTML
  var r=/{searchHL}(({(?!\/searchHL})|[^{])*){\/searchHL}/g
  t=t.replace(r,'<span class="highlight">$1</span>');
  obj.innerHTML=t;
 // alert('搜索到关键词'+cnt+'处')
}
function loopSearch(s,obj){
  var cnt=0;
  if (obj.nodeType==3){
    cnt=replace(s,obj);
    return cnt;
  }
  for (var i=0,c;c=obj.childNodes[i];i++){
    if (!c.className||c.className!='highlight')
      cnt+=loopSearch(s,c);
  }
  return cnt;
}
function replace(s,dest){
  var r=new RegExp(s,'g');
  var tm=null;
  var t=dest.nodeValue;
  var cnt=0;
  if (tm=t.match(r)){
    cnt=tm.length;
    t=t.replace(r,"{searchHL}"+decode(s)+"{/searchHL}")
    dest.nodeValue=t;
  }
  return cnt;
}

$( document).ready(function(e) {
 var width=$('.beibi_tui li').width();
	var i=$('.beibi_tui li').index()+1;
	$('.beibi_tui').css({'width':width*i+60});
	$('.down').click(function(e) {
        $('.beibi_tui').stop().animate({left:'-239px'},function(){
			var first=$('.beibi_tui li').first();
			$('.beibi_tui ul').append(first);//先把最前面的li的移除最下面，再通过绝对定位
			$('.beibi_tui').css('left','0px');
			});
    });
	
	$('.up').click(function(e) {
        var penend = $('.beibi_tui li').last();
		$('.beibi_tui ul').prepend(penend);//把最后一个放到最前面
		$('.beibi_tui').css('left','-239px');
		$('.beibi_tui').stop().animate({left:'0px'});
    });
});

var list_obj={
	list_init:function(){
		function load_page(page,sort,where) {
			$.ajax({
				type:'post',
				url:ajax_url,
				data:{p:page,sort:sort,where:where},
				success:function(data){
					if(data['list'] != '') {
						var htmltmp = '';
						$.each(data['list'],function(i) {
							v = data['list'][i];
							htmltmp +=  '<div class="list">'+
											'<div class="list_img"><a href="'+v['link']+'"><img src="'+v['ImgPath']+'" title="'+v['products_name']+'" /></a></div>'+
											'<div class="list_word">'+
												'<div class="bb_proce"><span>￥'+v['products_pricex']+'</span><i>￥'+v['products_pricey']+'</i></div>'+
												'<div class="bb_name"><a href="'+v['link']+'" title="'+v['products_name']+'">'+v['products_name']+'</a></div>'+
												'<div class="bb_else"><span class="yishou">已售<i>'+v['products_sales']+'</i>件</span><span class="pingjia"><i>'+v['products_commit']+'</i>人已评价</span></div>'+
											'</div>'+
										'</div>';
						});
						if($('.fu_next').attr('page') == 1) {
							$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						}else {
							$('#up').attr('flag','true').css({'color':'#333','cursor':'pointer'});
						}
						if(data.totalpage == $('.fu_next').attr('page')) {
							$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						}else {
							$('#down').attr('flag','true').css({'color':'#333','cursor':'pointer'});
						}
						
						$('.fu_next i').eq(0).html(data.count);
						$('.fu_next i').eq(1).html($('.fu_next').attr('page'));
						$('.fu_next em').html(data.totalpage);
						$('#listBox').html(htmltmp);
						if(highlight_flag){
						    highlight($('input[name=Keyword]').val());
						}
					}else{
						$('#up').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#down').attr('flag','false').css({'color':'#999','cursor':'auto'});
						$('#listBox').html('<p style="line-height:30px; text-align:center;color:#999;">暂无数据！</p>');
					}
				},
				dataType:'json',
			});
		}
        load_page($('.fu_next').attr('page'),$('.fu_mune .fu_mune_lihover').attr('rel'),$('input[name=shaixuan]').val());
		$('.fu_next').on('click','#up[flag=true]',function(){//上一页
			var page = parseInt($('.fu_next').attr('page'))-1;
			$('.fu_next').attr('page', page);
			load_page($('.fu_next').attr('page'),$('.fu_mune .fu_mune_lihover').attr('rel'),$('input[name=shaixuan]').val());
		});
		$('.fu_next').on('click','#down[flag=true]',function(){//下一页
			var page = parseInt($('.fu_next').attr('page'))+1;
			$('.fu_next').attr('page', page);
			load_page($('.fu_next').attr('page'),$('.fu_mune .fu_mune_lihover').attr('rel'),$('input[name=shaixuan]').val());
		});
		
		
		//排序
		$('.fu_mune li').click(function(){
			$('.fu_mune li').removeClass('fu_mune_lihover');
			$(this).addClass('fu_mune_lihover');
			var sort = $(this).attr('rel');
			load_page($('.fu_next').attr('page'),sort,$('input[name=shaixuan]').val());
		});
		//筛选
		$('.filt_down a').click(function(){
			var value = '';
			var this1 = this;
			if($('i',this1).hasClass('aaa')){
				$('i',this1).removeClass('aaa');
			}else{
				$('i',this1).addClass('aaa');
			}
			
			$('.filt_down i').each(function(){
				var this2 = this;
				if($(this2).hasClass('aaa')){
					value += $(this2).parent().attr('rel') + ',';
				}
				$('input[name=shaixuan]').val(value);//组装
			});
			load_page($('.fu_next').attr('page'),$('.fu_mune .fu_mune_lihover').attr('rel'),$('input[name=shaixuan]').val());
		});
		//搜索
		$('#list_search').click(function(){
			var value = '';
			$('.filt_down i').each(function(){
				var this2 = this;
				if($(this2).hasClass('aaa')){
					value += $(this2).parent().attr('rel') + ',';
				}
				$('input[name=shaixuan]').val(value + 'search' + $('input[name=k]').val());//组装
			});
			load_page($('.fu_next').attr('page'),$('.fu_mune .fu_mune_lihover').attr('rel'),$('input[name=shaixuan]').val());
		});
	},
}