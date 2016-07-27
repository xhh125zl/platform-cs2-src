<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$pinConfig = $DB->GetRs('pintuan_config', '*', "where Users_ID = '{$UsersID}'");
$rsConfig = array_merge($rsConfig, $pinConfig);

//获取幻灯片列表
if (! empty($rsConfig['banner_img'])) {
    $t = json_decode($rsConfig['banner_img'], true);
    if ($t && $t[0]) {
        $banner_img = array_filter(json_decode($rsConfig['banner_img'], true));
        $banner_url = array_filter(json_decode($rsConfig['banner_url'], true));
        $slider = array();
        for ($i = 0, $len = count($banner_img); $i < $len; $i ++) {
            $slider[$i]['img'] = $banner_img[$i];
            if (isset($banner_url[$i])) {
                $slider[$i]['url'] = $banner_url[$i];
            }
        }
    }
}
$rsBiz = $DB->GetRs("biz","Biz_Account,Biz_Name","WHERE Users_ID='{$UsersID}' AND Biz_ID={$BizID}");
if(empty($rsBiz)){
  sendAlert("商家店铺不存在");
}
$shopname = isset($rsBiz['Biz_Name']) && $rsBiz['Biz_Name']?$rsBiz['Biz_Name']:"";
$result = $DB->Get("pintuan_category", "cate_id", "WHERE Users_ID='{$UsersID}' AND istop=1");
$catelist = "";
while ($res = $DB->fetch_assoc()) {
    $catelist .= "'," . $res['cate_id'] . ",',";
}
$catelist = trim($catelist, ',');

$totalInfo = $DB->GetRs("pintuan_products","count(*) as total","WHERE Users_ID='{$UsersID}' AND Products_Category in ({$catelist})  AND Biz_ID={$BizID} ");
$pagesize = 5;
$totalPage = $totalInfo['total'] % $pagesize ==0?($totalInfo['total']/$pagesize):(intval($totalInfo['total']/$pagesize)+1);

if(IS_AJAX){
    $time = time();
    $page = isset($_POST['page']) && $_POST['page']?$_POST['page']:1;
    $sort = isset($_POST['sort']) && $_POST['sort']?$_POST['sort']:1;
    $offset = ($page-1)*$pagesize;
    $order = ["Products_ID ASC","Products_CreateTime DESC","Products_Sales DESC","Products_PriceT DESC","Products_Index DESC"];
    $fields = "starttime,Users_ID,Products_JSON,products_IsNew,products_IsRecommend,products_IsHot,Is_Draw,Products_ID,Products_Name,stoptime,Products_Sales,Products_PriceT,Products_PriceD,people_num";
    $sql = "SELECT {$fields} FROM `pintuan_products` WHERE Users_ID='{$UsersID}' AND Products_Category in ({$catelist})  AND Biz_ID={$BizID} ORDER BY {$order[$sort]} LIMIT {$offset},{$pagesize}";
    $result = $DB->query($sql);
    $list = [];
    if($result){
        $list = $DB->toArray($result);
        if(!empty($list))
        {
            foreach($list as $k => $v)
            {
                $image = json_decode($v['Products_JSON'], true);
                $path = $image['ImgPath']['0'];
                $list[$k]['imgpath'] = $path;
                if ($v['products_IsNew'] == 1) {
                    // 新品
                    $list[$k]['Tie'] = "1";
                } elseif ($v['products_IsRecommend'] == 1) {
                    // 促销
                    $list[$k]['Tie'] = "6";
                } elseif ($v['products_IsHot'] == 1) {
                    // 热销
                    $list[$k]['Tie'] = "2";
                }
                if ($v['Is_Draw'] == 0) {
                    $list[$k]['Draw'] = 1;
                } elseif ($v['Is_Draw'] == 1) {
                    $list[$k]['Draw'] = 0;
                }
                $list[$k]['Products_Name'] = sub_str($v['Products_Name'], 16, false);
                if($v['starttime']>$time){
                    $lasttime = $v['starttime'] - time();
                    $hour = intval($lasttime/3600)>0?intval($lasttime/3600):0;
                    $minute = intval(($lasttime-360*$hour)/60)?intval(($lasttime-360*$hour)/60):0;
                    $minute = intval($minute/60);
                    $list[$k]['buttonTitle'] = json_encode(['status'=>0, 'data'=>['hour'=>$hour,'minute'=>$minute]]);
                }else if ($v['stoptime']<$time){
                    $list[$k]['buttonTitle'] = json_encode(['status' =>-1 ]);
                }else{
                    $list[$k]['buttonTitle'] = json_encode(['status' =>1 ]);
                }
            }
            die(json_encode([ 'status'=>1 ,'data' => $list ], JSON_UNESCAPED_UNICODE));
        }
    }
    die(json_encode([ 'status'=>0 ,'data' => $list ], JSON_UNESCAPED_UNICODE));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?="{$shopname}的拼团商城" ?>
</title>
<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
<link href="/static/api/pintuan/css/style11.css" rel="stylesheet" type="text/css">
<script src="/static/api/pintuan/js/jquery.min.js"></script>
<script src="/static/api/pintuan/js/responsiveslides.min.js"></script>
<script src="/static/api/pintuan/js/common.js"></script>
<style>
.sort ul li {     font-size: 16px;
    width: 45%;
    margin-bottom: 10px;
    background: #f61d4b;
    height: 30px;
    line-height: 30px;
    color: #fff;
    cursor:pointer;
    font-family: '微软雅黑'; }
.sort ul li:nth-child(2n-1){
    float:left;
    margin-left: 5px;
    padding-left: 10px;
    margin-right:3px;
}
.sort ul li:nth-child(2n){
    float:right;
    padding-left:10px;
}
</style>
</head>
<body>
	<div class="w">
		<!-- 代码 开始 -->
		<div class="device">
			<a class="arrow-left" href="#"></a> <a class="arrow-right" href="#"></a>
			<div class="swiper-container">
				<div class="swiper-wrapper">
                  <?php
                  if (! empty($slider)) {
                    foreach ($slider as $ks => $ress) {
                        if (isset($ress['url']) && $ress['url']) {
                  ?>        
                	<div class="swiper-slide">
            			<a href="<?php echo $ress['url'];?>"><img src="<?php echo $ress['img'];?>"> </a>
            		</div>
                    <?php
                        } else {
                    ?>
                    <div class="swiper-slide">
            			<img src="<?php echo $ress['img'];?>">
            		</div>
                    <?php
                        }
                    }
                  }
                  ?>
                  </div>
			</div>
			<div class="pagination"></div>
		</div>
		<script src="/static/api/pintuan/js/idangerous.swiper.min.js"></script>
		<script>
          var mySwiper = new Swiper('.swiper-container',{
            pagination: '.pagination',
            loop:true,
            grabCursor: true,
            paginationClickable: true
          })
          $('.arrow-left').on('click', function(e){
            e.preventDefault();
            mySwiper.swipePrev();
          })
          $('.arrow-right').on('click', function(e){
            e.preventDefault();
            mySwiper.swipeNext();
          })
        </script>
		<!-- 代码部分end -->
		<!-- 代码 结束 -->
		<div class="sort">
			<ul>
				<li sort="1">按发布时间</li>
				<li sort="2">按销量</li>
				<li sort="3">按价格</li>
				<li sort="4">按手动</li>
			</ul>
		</div>
		<div class="clear"></div>
		<script src="/static/api/pintuan/js/dropload.min.js"></script>
    <div id="container"></div>
    <script>
		$(function(){
          var url = "/api/<?=$UsersID ?>/pintuan/biz/<?=$BizID ?>/";
          var page = 1;
          var sort = 1;
          var totalPage = <?=$totalPage?>;
          sessionStorage.setItem("<?=$UsersID ?>BizListSort", sort);
          sessionStorage.setItem("<?=$UsersID ?>BizcurrentPage", page);
          getContainer(url,page,1);
          $(".sort ul li").click(function(){
            var sort = $(this).attr("sort");
            sessionStorage.setItem("<?=$UsersID ?>BizListSort", sort);
            sessionStorage.setItem("<?=$UsersID ?>BizcurrentPage", page);
            $("#container").empty();
            getContainer(url,page,sort);
          });
          $("#container").dropload({
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
                  domLoad    : '<div class="dropload-load"></div>'
              },
              loadUpFn : function(me){
                  page = sessionStorage.getItem("<?=$UsersID ?>BizcurrentPage")?sessionStorage.getItem("<?=$UsersID ?>BizcurrentPage"):page;
                  sort = sessionStorage.getItem("<?=$UsersID ?>BizListSort")?sessionStorage.getItem("<?=$UsersID ?>BizListSort"):1;
                  if(page>1){
                    page--;
                    sessionStorage.setItem("<?=$UsersID ?>BizListSort", sort);
                    sessionStorage.setItem("<?=$UsersID ?>BizcurrentPage", page);
                    getContainer(url,page,sort); 
                  }
                  me.resetload();
              },
              loadDownFn : function(me){
                  page = sessionStorage.getItem("<?=$UsersID ?>BizcurrentPage")?sessionStorage.getItem("<?=$UsersID ?>BizcurrentPage"):page;
                  sort = sessionStorage.getItem("<?=$UsersID ?>BizListSort")?sessionStorage.getItem("<?=$UsersID ?>BizListSort"):1;
                  if(page<=totalPage){
                    page++;
                    sessionStorage.setItem("<?=$UsersID ?>BizListSort", sort);
                    sessionStorage.setItem("<?=$UsersID ?>BizcurrentPage", page);
                    getContainer(url,page,sort);
                  } 
                  me.resetload();
              }
        });
		});
    </script>
		<?php include 'bottom.php';?>
	</div>
</body>
</html>