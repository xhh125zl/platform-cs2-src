<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $BizID?'店铺':''.$rsConfig["name"];?></title>
<link href='/static/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/zhongchou/css/zhongchou.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/zhongchou/js/zhongchou.js?t=<?php echo time();?>'></script>
<script src="/static/api/zhongchou/js/dropload.min.js"></script>
<script src="/static/api/zhongchou/js/common.js"></script>
<style>
  .sort { margin-top:5px; }
  .sort ul li {
    font-size: 13px;
    width: 23.5%;
    float:left;
    margin-bottom: 10px;
    background: #0dc05d;
    height: 30px;
    text-align:center;
    line-height: 30px;
    color: #fff;
    cursor:pointer;
    font-family: '微软雅黑'; 
  }
  .sort ul li:nth-child(2){
      float:left;
      margin-right:5px;
  }
  .sort ul li:nth-child(3){
      float:left;
      margin-right:5px;
  }
  .sort ul li:nth-child(1){
      float:left;
      margin-right:5px;
  }
  .sort ul li:nth-child(4){
      float:left;
  }
  .sort ul li span { margin-left:8px;font-size::15px;}
  .dropload-refresh,.dropload-update,.dropload-load{ text-align:center;color:#777;width:100%;}
</style>
</head>
<body>
    <div class="header">
     <?php echo $BizID?'店铺':''.$rsConfig["name"];?>
     <a href="/api/<?php echo $UsersID;?>/zhongchou/orders/" id="user"></a>
    </div>
    <div class="sort">
    	<ul>
    		<li sort="1" method="asc">时间<span>↑↓</span></li>
    		<li sort="2" method="asc">销量<span>↑↓</span></li>
    		<li sort="3" method="asc">价格<span>↑↓</span></li>
    		<li sort="4" method="asc">综合<span>↑↓</span></li>
    	</ul>
    </div>
    <div class="clear"></div>
    <div id="container" class="main"></div>
	<script>
		$(function(){
			var url = "<?=$_SERVER['REQUEST_URI']?>";
			var page = 1;
			var sort = 0;
			var method="asc";
			var marrow = "↑";
			var totalPage = <?=$totalPage?>;
			sessionStorage.setItem("<?=$UsersID ?>ListSort", sort);
			sessionStorage.setItem("<?=$UsersID ?>ListMethod", method);
			sessionStorage.setItem("<?=$UsersID ?>currentPage", page);
			getContainer(url,page,sort,method);
			$(".sort ul li").click(function(){
                method = sessionStorage.getItem("<?=$UsersID ?>ListMethod");
                if(method=="asc"){
                    method = "desc";
                    marrow = "↓↑";
                }else{
                    method = "asc";
                    marrow = "↑↓";
                }
        		$(this).attr("method",method);
        		$(this).find("span").text(marrow);
				sort = $(this).attr("sort");
				method = $(this).attr("method");
				sessionStorage.setItem("<?=$UsersID ?>ListSort", sort);
				sessionStorage.setItem("<?=$UsersID ?>currentPage", page);
				sessionStorage.setItem("<?=$UsersID ?>ListMethod", method);
				getContainer(url,page,sort,method);
			});
			
			$("#loader").dropload({
                domUp : {
                	domClass   : 'dropload-up',
                	domRefresh : '<div class="dropload-refresh">下拉刷新</div>',
                	domUpdate  : '<div class="dropload-update">释放更新</div>',
                	domLoad    : '<div class="dropload-load"></div>'
                },
                domDown : {
                    domClass   : 'dropload-down',
                    domRefresh : '<div class="dropload-refresh">上拉加载更多</div>',
                    domUpdate  : '<div class="dropload-update">释放加载</div>',
                    domLoad    : '<div class="dropload-load">test</div>'
                },
                loadUpFn : function(me){
                    page = sessionStorage.getItem("<?=$UsersID ?>currentPage")?sessionStorage.getItem("<?=$UsersID ?>currentPage"):page;
                    sort = sessionStorage.getItem("<?=$UsersID ?>ListSort")?sessionStorage.getItem("<?=$UsersID ?>ListSort"):1;
                    method = sessionStorage.getItem("<?=$UsersID ?>ListMethod")?sessionStorage.getItem("<?=$UsersID ?>ListMethod"):'asc';
                    if(page>1){
                      page--;
                      sessionStorage.setItem("<?=$UsersID ?>ListSort", sort);
                      sessionStorage.setItem("<?=$UsersID ?>currentPage", page);
                      getContainer(url,page,sort,method); 
                    }
                    me.resetload();
                },
                loadDownFn : function(me){
                    page = sessionStorage.getItem("<?=$UsersID ?>currentPage")?sessionStorage.getItem("<?=$UsersID ?>currentPage"):page;
                    sort = sessionStorage.getItem("<?=$UsersID ?>ListSort")?sessionStorage.getItem("<?=$UsersID ?>ListSort"):1;
                    method = sessionStorage.getItem("<?=$UsersID ?>ListMethod")?sessionStorage.getItem("<?=$UsersID ?>ListMethod"):'asc';
                    if(page<totalPage){
                      page++;
                      sessionStorage.setItem("<?=$UsersID ?>ListSort", sort);
                      sessionStorage.setItem("<?=$UsersID ?>currentPage", page);
                      getContainer(url,page,sort,method);
                    } 
                    me.resetload();
                }
    		});	
		});
	</script>
<?php if($share_flag==1 && $signature<>""){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_user["Users_WechatAppId"];?>",		   
		   timestamp:<?php echo $timestamp;?>,
		   nonceStr:"<?php echo $noncestr?>",
		   url:"<?php echo $url?>",
		   signature:"<?php echo $signature;?>",
		   title:'<?php echo $rsConfig["name"];?>',
		   desc:'投资你关注的项目，有机会获得丰厚的回报',
		   img_url:'http://<?php echo $_SERVER["HTTP_HOST"];?>/static/api/images/cover_img/zhongchou.jpg',
		   link:''
		};
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
<?php ad($UsersID,2,2);//第一个数字参数代表广告位置：1顶部2底部；第二个数字参数代表广告位的编号，从后台查看?>
</body>
</html>