var pagesshow_obj={
	url:'',
	tt:'',
	msk_init:function(status){
		var x_count=4, y_count=4;
		var obj=$('#PagesShow');
		if(status==1 && obj.is(':hidden')) return;
		$(obj)[0].addEventListener('touchmove', function(e){
   			e.preventDefault();
		});
		obj.height($(document).height());
		obj.show();
		var objWidth=$(window).width();
        var objHeight=$(window).height();
        var littleBoxWidth=Math.ceil(objWidth/x_count);
		var littleBoxHeight=Math.ceil(objHeight/y_count);                   
		var html='';
		var littleBoxLeft=littleBoxWidth*(-1), littleBoxTop=littleBoxHeight*(-1);
		for(var i=0; i<y_count; i++){//行
			littleBoxTop+=littleBoxHeight;
			for(var j=0; j<x_count; j++){//每一行中的单个span
				littleBoxLeft+=littleBoxWidth;
				html+="<span style='display:block;position:absolute;left:"+littleBoxLeft+"px;top:"+littleBoxTop+"px;width:"+littleBoxWidth+"px; height:"+littleBoxHeight+"px; background-image:url("+pagesshow_obj.url+");background-position:"+(littleBoxLeft)*(-1)+"px "+(littleBoxTop)*(-1)+"px; background-size:"+objWidth+"px auto'></span>";
			}
			littleBoxLeft=littleBoxWidth*(-1);
		}
		obj.html(html);
		var objItems=$('#PagesShow span');
		objItems.click(pagesshow_obj.msk_doit);
		pagesshow_obj.tt=setTimeout(pagesshow_obj.msk_doit,showtime);
	},
	msk_doit:function(){
		$('#PagesShow_blank').height(0);
		var obj=$('#PagesShow');
		var objItems=$('#PagesShow span');
		objItems.animate({height:0, width:0}, 1000,function(){
			obj.html('');
			obj.hide();
			$('#web_skin_index').show();
		});	
		clearTimeout(pagesshow_obj.tt);
	},
	fade_init:function(status){
		var obj=$('#PagesShow');
		if(status==1 && obj.is(':hidden')) return;
		$(obj)[0].addEventListener('touchmove', function(e){
   			e.preventDefault();
		});
		obj.height($(document).height());
		obj.show();
		pagesshow_obj.tt=setTimeout(function(){$('#PagesShow_blank').height(0);obj.fadeOut(1000);},showtime);
		obj.click(function(){
			$('#PagesShow_blank').height(0);
			clearTimeout(pagesshow_obj.tt);
			$(this).fadeOut(1000)
		});
	},
	door_init:function(status){
		var obj=$('#PagesShow');
		if(status==1 && obj.is(':hidden')) return;
		$(obj)[0].addEventListener('touchmove', function(e){
   			e.preventDefault();
		});
		var objWidth=$(window).width();
		var objWidth_50=objWidth/2;
       	var objHeight=$(window).height();
		obj.height($(document).height());
		var html='';
		html='<div class="lbar" style="position:absolute;width:50%;left:0px;top:0;height:100%;background-image:url('+pagesshow_obj.url+');background-size:'+objWidth+'px auto;background-position:0 top"></div><div class="rbar" style="position:absolute;width:50%;left:'+objWidth_50+'px;top:0;height:100%;background-image:url('+pagesshow_obj.url+');background-size:'+objWidth+'px auto;background-position:-'+objWidth_50+'px top;"></div>';
		obj.html(html).show();
		pagesshow_obj.tt=setTimeout(pagesshow_obj.door_doit,showtime);
		obj.find('div').click(pagesshow_obj.door_doit);
		
	},
	door_doit:function(){
		$('#PagesShow_blank').height(0);
		clearTimeout(pagesshow_obj.tt);
		var lbar=$('#PagesShow .lbar');
		var rbar=$('#PagesShow .rbar');
		var objWidth=$(window).width();
		var objWidth_50=objWidth/2;
		lbar.animate({'left':-objWidth_50+'px'},800);
		rbar.animate({'left':objWidth+'px'},800,function(){$('#PagesShow').hide()});
	}
}