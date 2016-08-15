var category_obj={
	category_init:function(){
		function load_page(type, cid, page){
			$.ajax({
				type:'post',
				url:'/api/'+UsersID+'/cloud/ajax/',
				data:{action:type,p:page,cid:cid,BizID:BizID},
				beforeSend:function() {
					$("body").append("<div id='load'><div class='double-bounce1'></div><div class='double-bounce2'></div></div>");
				},
				success:function(data) {
					if(data['list'] != '') {
						var j = 0;
						var $htmltmp = '';
						
						$.each(data['list'], function(i){
							var xiangou = false;
							j++;
							v = data['list'][i];
							if(v['Products_xiangoutimes'] > 0){
								xiangou = true;
							}
							$htmltmp += '<ul>'+
											'<li><span class="gList_l fl" onclick="location.href=\''+cloud_url+'products/'+v['Products_ID']+'/\'"><img src="'+v['ImgPath']+'">';
											if(xiangou){
											    $htmltmp += '<div class="pTitle pPurchase">&nbsp;&nbsp;限购</div>';
											}
							$htmltmp += 	'</span>'+
												'<div class="gList_r">'+
													'<h3 class="gray6">(第'+v['qishu']+'期)'+v['Products_Name']+'</h3>'+
													'<em class="gray9">价值：￥'+v['Products_PriceY']+'</em>'+
													'<div class="gRate">'+
														'<div class="Progress-bar">'+
															'<p class="u-progress"><span style="width: '+(v['canyurenshu']/v['zongrenci']*100)+'%;" class="pgbar"><span class="pging"></span></span></p>'+
															'<ul class="Pro-bar-li">'+
																'<li class="P-bar01"><em>'+v['canyurenshu']+'</em>已参与</li>'+
																'<li class="P-bar02"><em>'+v['zongrenci']+'</em>总需人次</li>'+
																'<li class="P-bar03"><em>'+(v['zongrenci']-v['canyurenshu'])+'</em>剩余</li>'+
															'</ul>'+
														'</div>'+
														'<a href="javascript:" IsShippingFree="'+v['Products_IsShippingFree']+'" ProductsID="'+v['Products_ID']+'" ProductsWeight="'+v['Products_Weight']+'"><s></s></a></div>'+
												'</div>'+
											'</li>'+
										'</ul>';
						})
						$(".goodList").html($htmltmp);
						if(data['totalpage'] == $(".loading").attr('page')){
							$(".loading").hide();
						}
						if(data['totalpage'] > $(".loading").attr('page')){
							$(".loading").show();
						}
					}else {
						$(".goodList").html('<p style="line-height:30px;text-align:center;font-size:15px;color:#666;">暂无数据</p>');
					}
				},
				complete:function() {
					$("#load").remove();
				},
				dataType:'json',
			});
		};
		//默认加载最新揭晓
		load_page('category', cid, $(".loading").attr('page'));
		
		$(".loading").click(function(){
			var page = parseInt($(this).attr('page'))+1;
			$(this).attr('page', page);
			load_page('category', cid, $(".loading").attr('page'));
		});
		
		//加入购物车
		$('.goodList').on('click','.gRate a',function(){
			var needcart = 1;
			var IsShippingFree = $(this).attr('IsShippingFree');
			var ProductsID = $(this).attr('ProductsID');
			var ProductsWeight = $(this).attr('ProductsWeight');
			var formData = {IsShippingFree:IsShippingFree,OwnerID:OwnerID,ProductsID:ProductsID,ProductsWeight:ProductsWeight,Qty:1,needcart:1,spec_list:''};
			$.post('/api/'+UsersID+'/cloud/cart/ajax/', formData, function(data) {				
                if (data.status == 1) {
					//购物车特效
					$('#btnCart i b').show();
					$('#btnCart i b').html(data.qty);
                   	new Toast({context:$('body'),message:'产品已成功加入购物车',top:$(".footer").offset().top-70}).show();    
                }else{
					new Toast({context:$('body'),message:data.msg,top:$(".footer").offset().top-70,time:4000}).show(); 
				}
            }, 'json');
		});
	},
}