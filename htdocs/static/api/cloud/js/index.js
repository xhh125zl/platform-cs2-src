var index_obj={
	index_init:function(){
		function load_page(type,page){
        var method = sessionStorage.getItem(UsersID+"CloudListMethod");
		
			$.ajax({
				type:'post',
				url:'/api/'+UsersID+'/cloud/ajax/',
				data:{action:type,p:page,BizID:BizID,ActiveID:ActiveID,UsersID:UsersID,method:method},
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
							$htmltmp += '<li><a href="'+cloud_url+'products/'+v['Products_ID']+'/" class="g-pic" style="display:block;width:150px;height:100px;overflow:hidden;margin:0 auto;">';
							 if(xiangou){
								    $htmltmp += '<div class="pTitle pPurchase">限购</div>';
								}
							    $htmltmp += '<img src="'+v['ImgPath']+'"></a>'+
											'<p class="g-name">(第'+v['qishu']+'期)'+v['Products_Name']+'</p>'+
											'<ins class="gray9">价值:￥'+v['Products_PriceY']+'</ins>'+
											'<div class="Progress-bar">'+
												'<p class="u-progress"><span class="pgbar" style="width: '+(v['canyurenshu']/v['zongrenci']*100)+'%;"><span class="pging"></span></span></p>'+
											'</div>';
								if(v['canyurenshu']/v['zongrenci'] == 1){
									$htmltmp += '<div class="btn-wrap" style="color:#F60;">'+
												'查看揭晓结果...'+
											'</div>';
								}else{
									$htmltmp += '<div class="btn-wrap"><a href="'+cloud_url+'products/'+v['Products_ID']+'/" class="buy-btn">立即'+v['Products_PriceX']+'元购</a>'+
												'<div class="gRate" IsShippingFree="'+v['Products_IsShippingFree']+'" ProductsID="'+v['Products_ID']+'" ProductsWeight="'+v['Products_Weight']+'"><a href="javascript:void(0);"><s></s></a></div>'+
											'</div>';
								}
								$htmltmp += '</li>';
						})

						$("#ulGoodsList").html($htmltmp);
						if(data['totalpage'] == $(".loading").attr('page')){
							$(".loading").hide();
						}
						if(data['totalpage'] > $(".loading").attr('page')){
							$(".loading").show();
						}
					}else {
						$("#ulGoodsList").html('<div class="noRecords colorbbb clearfix"><s></s>暂无数据<div class="z-use" style="color:#bbb">'+document.domain+'</div></div>');
					}
				},
				complete:function() {
					$("#load").remove();
				},
				dataType:'json',
			});
		}
		//默认加载
		load_page('IsRecommend', $(".loading").attr('page'));
		
		$('#ulOrder li').click(function(){
        method = sessionStorage.getItem(UsersID+"CloudListMethod");

        if(method=="asc"){
            method = "desc";
        }else{
            method = "asc";
        }
        $(this).attr("method", method);
				method = $(this).attr("method");
				sessionStorage.setItem(UsersID + "CloudListMethod", method);
		
		
			$('#ulOrder li').removeClass('current');
			$(this).addClass('current');
			var type = $(this).attr('order');
			load_page(type, $(".loading").attr('page'));
		});
		
		$(".loading").click(function(){
			var page = parseInt($(this).attr('page'))+1;
			$(this).attr('page', page);
			load_page($('#ulOrder .current').attr('order'), $(".loading").attr('page'));
		});
		
		$(document).click(function(){
			if($('.select-total').is(':visible')){
				$('.select-btn').removeClass('current');
				$('.select-total').hide();
			}
		});
		$('.select-btn').click(function(){
			if($('.select-total').is(':hidden')){
				$('.select-btn').addClass('current');
				$('.select-total').show();	
			}else{
				$('.select-btn').removeClass('current');
				$('.select-total').hide();
			}
			return false;
		});
		//加入购物车
		$('#ulGoodsList').on('click','.gRate',function(){
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