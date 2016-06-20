var stores_obj={
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
				$($('#stores .store').get(nearest_index)).addClass('nearest').prependTo($('#stores'));
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
		
		/*if(navigator.geolocation){
			navigator.geolocation.getCurrentPosition(function(pos){
				showNearest(pos['coords']['latitude'], pos['coords']['longitude']);
			}, function(error){
				switch(error.code){
					case error.TIMEOUT:
						global_obj.win_alert('浏览器获取地理位置超时！');
						break;
					case error.PERMISSION_DENIED:
						global_obj.win_alert('您未允许浏览器提供地理位置服务，无法导航！');
						break;
					case error.POSITION_UNAVAILABLE:
						global_obj.win_alert('浏览器获取地理位置服务不可用！');
						break;
					default:
						break;
				}
			}, {enableHighAccuracy:true, maximumAge:1000, timeout:5000});
		}*/
	}
}