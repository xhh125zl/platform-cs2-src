	    //根据AjAx获取内容
  function getContainer(url,page,sort,method)
	{
			if(page=="" || page==undefined)  page = 1;
			
			//Load 加载动画
			var loading = $("<div><img src='/static/api/pintuan/images/loading.gif' /></div>").css({'position':'absolute','z-index':'999','top':'100px'});
			$('#container').append(loading);
			$.post(url,{page:page,sort:sort,sortmethod:method},function(data){
				var strData = "";
				if(data.status==1){
					var list = data.data;
					for(var i=0;i<list.length;i++)
					{
						strData +='<div class="item">';
						strData +='	  <div class="flag">'+ list[i].buttonTitle +'</div>';
						strData +='	  <div class="time">活动时间：'+list[i].fromtime+' - '+ list[i].totime +'</div>';
						strData +='   <div class="title"><a href="/api/'+list[i].usersid+'/zhongchou/detail/'+list[i].itemid+'/">'+list[i].title+'</a></div>';
						strData +='   <div class="info"><a href="/api/' + list[i].usersid + '/zhongchou/detail/' +list[i].itemid+'/"><img src="' + list[i].thumb + '"></a></div>';
						strData +='   <div class="jindu">';
						strData +='       <p>目标<font style="font-family:\'Times New Roman\'; font-size:14px;">￥'+list[i].amount+'</font></p>';
						strData +='       <p>筹集<font style="font-family:\'Times New Roman\'; font-size:14px;color:#F60">￥'+list[i].curamount+'</font></p>';
						strData +='       <p class="nobg">支持<font style="font-family:\'Times New Roman\'; font-size:14px; color:#0dc05d">'+list[i].num+'</font>人</p>';
						strData +='       <div class="clear"></div>';
						strData +='   </div>';
						strData +='</div>';
					}
					loading.hide();
					$("#container").html(strData);
				}else{
					setTimeout(function(){
						loading.hide();
					},1000);
				}
			},"JSON");
		}