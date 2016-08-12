    
    //根据AjAx获取内容
    function getContainer(url,page,sort,method)
		{
			if(page=="" || page==undefined)  page = 1;
			$.post(url,{page:page,sort:sort,sortmethod:method},function(data){
				var strData = "";
				if(data.status==1){
					var list = data.data;
					for(var i=0;i<list.length;i++)
					{
						strData +='<div class="chanpin">';
						strData +='	  <div class="biaoqian"><img src="/static/api/pintuan/images/xp_'+list[i].Tie+'.png" width="55px" height="55px"></div>';
						strData +='   <div class="tt"></div>';
						strData +='   <div class="tp l"><a href="/api/' + list[i].Users_ID + '/pintuan/xiangqing/' +list[i].Products_ID+'/"><img src="' + list[i].imgpath + '"></a></div>';
						strData +='   <div class="jianjie1 l">';
						strData +='       <div><span class="ct l"><a href="/api/' + list[i].Users_ID + '/pintuan/xiangqing/' + list[i].Products_ID + '/">' + list[i].Products_Name + '</a></span>';
						if(list[i].Draw==1){
                			strData +='       <span class="cj_choujiang r">抽奖</span>';
						}
						strData +='       </div>';
						strData +='       <div class="clear"></div>';
						strData +='       <div class="t1">销量:'+list[i].Products_Sales+'</div>';
						strData +='       <div class="t10 l"></div>';
						strData +='       <div class="shou r"></div>';
						strData +='       <div class="clear"></div>';
						strData +='       <div class="tuan">';
						strData +='          <div class="t9 l">￥' +list[i].Products_PriceT + '</div>';
						strData +='          <div class="t8 l"><del>￥' +list[i].Products_PriceD + '</del></div>';
						strData +='          <div class="t9 r">' +list[i].people_num + '人团</div>';
						strData +='       </div>';
						if(list[i].buttonTitle.status==0){
                		strData +='       <div class="tuan1"><a href="">即将开团 倒计时'+list[i].buttonTitle.data.hour+'时'+list[i].buttonTitle.data.minute+'分</a></div>';
						}else if(list[i].buttonTitle.status==1){
                		strData +='       <div class="tuan1"><a href="">拼团已结束</a></div>';
						}else{
                		strData +='       <div class="tuan1"><a href="/api/'+list[i].Users_ID+'/pintuan/xiangqing/' +list[i].Products_ID+'/">立即开团</a></div>';
						}
						strData +='    </div>';
						strData +='</div>';
						strData +='<div class="clear"></div>';
					}
					$("#container").html(strData);
				}
			},"JSON");
		}