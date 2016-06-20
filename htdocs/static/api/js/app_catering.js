var catering_obj={
	stores_init:function(){
		var getRad=function(d){
			return d*(Math.PI)/180.0;
		}
		
		var getDistance=function(lat1, lng1, lat2, lng2){
			lat1=lat1*1;
			lng1=lng1*1;
			lat2=lat2*1;
			lng2=lng2*1;
			var f=getRad((lat1+lat2)/2);
			var g=getRad((lat1-lat2)/2);
			var l=getRad((lng1-lng2)/2);
			
			var sf=Math.sin(f);
			var sg=Math.sin(g);
			var sl=Math.sin(l);
			
			var s, c, w, r, d, h1, h2;
			var fl=1/298.257;
			
			sg=sg*sg;
			sl=sl*sl;
			sf=sf*sf;
			
			s=sg*(1-sl)+(1-sf)*sl;
			c=(1-sg)*(1-sl)+sf*sl;
			
			w=Math.atan(Math.sqrt(s/c));
			r=Math.sqrt(s*c)/w;
			d=2*w*6378137.0;
			h1=(3*r-1)/2/c;
			h2=(3*r+1)/2/s;
			
			return d*(1+fl*(h1*sf*(1-sg)-h2*(1-sf)*sg));
		};
		
		var getFriendDistance=function(lat1, lng1, lat2, lng2){
			var dis=0;
			if(arguments.length==1){
				dis=lat1;
			}else{
				dis=getDistance(lat1, lng1, lat2, lng2);
			}
			if(dis<10000){
				return '约'+(dis>>0)+'m';
			}else{
				return '约'+((dis/1000)>>0)+'km';
			}
		};
		
    	//计算距离，显示最近的
		var showNearest=function(lat1, lng1){
			var nearest_index=-1, nearest_dis=-1;
			$('#stores .store').each(function(index){
				var lat=$(this).attr('lat'), lng=$(this).attr('lng');
				if(lat && lng){
					var dis=getDistance(lat, lng, lat1, lng1);
					var dis_f=getFriendDistance(dis);
					if(nearest_dis<0 || dis<nearest_dis){
						nearest_dis=dis;
						nearest_index=index;
					}
					$('.dis', $(this)).html(dis_f).show();
				}
			});
			
			if(nearest_index>-1){//把最近得显示在前面
				var o=$('#stores .store').get(nearest_index);
				o=$(o).addClass('nearest').prependTo($('#stores'));
				o.find('.dis').html(o.find('.dis').html()+'<span>【离你最近】</span>');
			}
		};
		
		renderReverse=function(response){
			var addr=response.result.formatted_address;
			if(addr){
				$('#your_address').show();
				$('#your_address dd').html(addr);
			}
		}
		
		var geolocation=new BMap.Geolocation();
		geolocation.getCurrentPosition(function(pos){
			showNearest(pos.point.lat, pos.point.lng);
			var url="http://api.map.baidu.com/geocoder/v2/?ak="+baidu_map_ak+"&callback=renderReverse&location="+pos.point.lat+","+pos.point.lng+"&output=json&pois=0";
			var script=document.createElement('script');
			script.type='text/javascript';
			script.src=url;
			document.body.appendChild(script);
		});
	},
	
	reserve_init:function(){
		$('#reserve input[name=ReserveDate]').datepicker({
			minDate:new Date(),
			dateFormat:'yy-mm-dd'
		}).val((
			function(d){
				return [d.getFullYear(), d.getMonth()+1, d.getDate()].join('-');
			}
		)(new Date()));
		
		$('.submit').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true).val('提交中...');
			$.post('/api/'+$('#UsersID').val()+'/app_catering/cart/ajax/reserve/'+$('#SId').val()+'/', $('form').serialize(), function(data){
				if(data.status==1){
					$('input, select, textarea').attr('disabled', true);
					$('.submit').val('提交成功');
					$('#reserve_success').show().animate({
						bottom:150,
						opacity:'0.7'
					}, 1500).animate({
						opacity:0
					}, 4000);
				}else{
					global_obj.win_alert(data.msg);
					$('.submit').attr('disabled', false).val('提 交');
				};
			}, 'json');
		});
	},
	
	food_init:function(){
		$('#left_menu').css('height', $(window).height())
		$('#page_right_contents').css({
			height:$(window).height(),
			width:$(window).width()-85
		});
		
		if($('#cart_products_count').html()!=0){	//未购物，隐藏底部的购物车条
			$('#shopping_cart').show();
		}
		
		$('#shop_products .item').each(function(){
			if($(this).attr('IsBuy')==1){
				$(this).find('.inkcom_bt .btn').hide();
				$(this).find('.inkcom_bt .bts').show();
			}
			
			$(this).click(function(){
				var obj=$(this);
				var IsBuy=$(this).attr('IsBuy');
				var d=IsBuy==0?'add':'del';	
				$.post('/api/'+$('#UsersID').val()+'/app_catering/cart/ajax/'+d+'/'+$(this).attr('ProId')+'/', '', function(data){
					if(data.status==1){
						if(data.qty==0){
							$('#shopping_cart').hide();
						}else{
							$('#shopping_cart').show();
							$('#cart_products_count').html(data.qty);
							$('#cart_products_price').html('￥'+data.total);
						}
						
						if(IsBuy==1){
							obj.find('.inkcom_bt .btn').show();
							obj.find('.inkcom_bt .bts').hide();
							obj.attr('IsBuy', 0);
						}else{
							obj.find('.inkcom_bt .btn').hide();
							obj.find('.inkcom_bt .bts').show();
							obj.attr('IsBuy', 1);
						}
					}
				}, 'json');
			});
		});
	},
	
	cart_init:function(){
		$('#left_menu').css('height', $(window).height())
		$('#page_right_contents').css({
			height:$(window).height(),
			width:$(window).width()-85
		});
		var price_detail=function(){
			var total_price=0;
			$('#cart_form .item .inkcom .price').each(function(){
				var price=parseFloat($(this).html().replace('￥', ''));
				var qty=parseInt($(this).parent().parent().siblings('select[name=Qty\\[\\]]').val());
				isNaN(qty) && (qty=1);
				total_price+=price*qty;
			});
			if(total_price==0){
				$('#no_goods').show();
				$('#checkout').hide();
			}else{
				$('#no_goods').hide();
				$('#checkout').show();
				$('#cart_form .total_price span').html('￥'+total_price.toFixed(2));
			}
		}
		price_detail();
		$('#cart_form select[name=Qty\\[\\]]').change(function(){
			var obj=$(this);
			var qty=$(this).val();
			qty>=1000 && (qty=999);
			$(this).val(qty);
			var _Qty=$(this).parent().children('select[name=Qty\\[\\]]').val();
			var _CartID=$(this).parent().parent().children('input[name=CartID\\[\\]]').val();
			$.post('/api/'+$('#UsersID').val()+'/app_catering/cart/ajax/update/'+_CartID+'_'+_Qty+'/', '', function(data){
				if(data.status==1){
					$('#cart_form .total_price span').html('￥'+data.total.toFixed(2));
				}else{
					global_obj.win_alert('出现未知错误！');
				}
			}, 'json');
		});
		
		$('#cart_form .del').click(function(){
			var obj=$(this);
			var cartid = $(this).parent().parent().children('input[name=CartID\\[\\]]').val();
			$.post('/api/'+$('#UsersID').val()+'/app_catering/cart/ajax/del/'+cartid+'/', '', function(data){
				if(data.status==1){
					if(data.total>0){
						$('#cart_form .total_price span').html('￥'+data.total);
						obj.parent().parent().remove();
					}else{
						$('#no_goods').show();
						$('#checkout').hide();
					}
				}
			}, 'json');
		});
		
		$('#cart_form').submit(function(){return false;});
		$('#cart_form .checkout input').click(function(){
			if(global_obj.check_form($('*[notnull]'))){return false};
			$(this).attr('disabled', true);
			$.post('/api/'+$('#UsersID').val()+'/app_catering/cart/ajax/checkout/', $('#cart_form').serialize(), function(data){
				if(data.status==1){
					global_obj.win_alert('订单提交成功！');
					window.location.reload();
				}else{
					$('#cart_form input:submit').attr('disabled', false);
					alert(data.msg);
				};
			}, 'json');
		});
	},
}