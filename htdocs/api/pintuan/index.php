<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$time = time();
$result = $DB->Get("active","Users_ID,Active_ID,Type_ID,Active_Name,MaxGoodsCount,IndexShowGoodsCount,MaxBizCount","WHERE Users_ID='{$UsersID}' AND starttime<={$time} AND stoptime>{$time} AND Status = 1");
$activelist = $DB->toArray($result);
$goodslist = [];
foreach ($activelist as $k => $v)
{
    $goodslist[$k]['Users_ID'] = $v['Users_ID'];
    $goodslist[$k]['Active_ID'] = $v['Active_ID'];
    $goodslist[$k]['Type_ID'] = $v['Type_ID'];
    $goodslist[$k]['Active_Name'] = $v['Active_Name'];
    $sql = "SELECT IndexConfig FROM biz_active WHERE Users_ID='{$v['Users_ID']}' AND Active_ID={$v['Active_ID']} AND Status=2 ORDER BY addtime ASC Limit 0,{$v['MaxBizCount']} ";
    $res = $DB->query($sql);
    $plist = $DB->toArray($res);
    $listGoods = "";
    foreach($plist as $key => $value)
    {
        $listGoods .=$value['IndexConfig'].',';
    }
    $listGoods = trim($listGoods,',');
    if($listGoods){
        $fields = "starttime,Products_JSON,products_IsNew,products_IsRecommend,products_IsHot,Is_Draw,Products_ID,Products_Name,stoptime,Products_Sales,Products_PriceT,Products_PriceD,people_num";
        $sql = "SELECT {$fields} FROM `pintuan_products` WHERE Users_ID='{$UsersID}' AND Products_ID IN ({$listGoods}) LIMIT 0,{$v['IndexShowGoodsCount']}";
        $result = $DB->query($sql);
        $list = $DB->toArray($result);
        $goodslist[$k]['goods']=$list;
    }
}

$pinConfig = $DB->GetRs('pintuan_config', '*', "where Users_ID = '{$UsersID}'");
$rsConfig = array_merge($rsConfig, $pinConfig);
$rsUserAccount = $DB->GetRs("users","Users_Account","WHERE Users_ID='{$UsersID}'");
$shopname = isset($rsUserAccount['Users_Account']) && $rsUserAccount['Users_Account']?$rsUserAccount['Users_Account']:"";
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?="{$shopname}的拼团商城" ?></title>
<link href="/static/api/pintuan/css/css.css" rel="stylesheet"
	type="text/css">
<link href="/static/api/pintuan/css/style11.css" rel="stylesheet"
	type="text/css">
<script src="/static/api/pintuan/js/jquery.min.js"></script>
<script src="/static/api/pintuan/js/responsiveslides.min.js"></script>
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
            						<a href="<?php echo $ress['url'];?>"><img src="<?php echo $ress['img'];?>"></a>
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
            e.preventDefault()
            mySwiper.swipePrev()
          })
          $('.arrow-right').on('click', function(e){
            e.preventDefault()
            mySwiper.swipeNext()
          })
          </script>
		<!-- 代码部分end -->
		<!-- 代码 结束 -->
		<div class="clear"></div>
        <?php
        $time = time();
        if(!empty($goodslist)){
            foreach($goodslist as $k => $v)
            {
                $lianjie = '/api/' . $UsersID . '/pintuan/list/' . $v['Active_ID'] . '/';
                if(!empty($v['goods'])){
                    foreach ($v['goods'] as $key=>$value)
                    {
                        $image = json_decode($value['Products_JSON'], true);
                        $path = $image['ImgPath']['0'];
                        // 设置 贴图 和 抽奖标签
                        if ($value['products_IsNew'] == 1) {
                            // 新品
                            $tie = "/static/api/pintuan/images/xp_1.png";
                        } elseif ($value['products_IsRecommend'] == 1) {
                            // 促销
                            $tie = "/static/api/pintuan/images/xp_6.png";
                        } elseif ($value['products_IsHot'] == 1) {
                            // 热销
                            $tie = "/static/api/pintuan/images/xp_2.png";
                        }
                        
                        if ($value['Is_Draw'] == 0) {
                            $choujiang = '<span class="cj_choujiang r">抽奖</span>';
                        } elseif ($value['Is_Draw'] == 1) {
                            $choujiang = '';
                        }
                       
                        // 获取开始和关闭时间设置倒计时
                        $starttime = $value['starttime'];
                        $endtime = $value['stoptime'];

                        echo '<div class="chanpin">
                          <div class="biaoqian"><img src="' . $tie . '" width="55px" height="55px"></div>
                          <div class="tt"></div>
                          <div class="tp l"><img src="' . $path . '"></div>
                          <div class="jianjie1 l">
                          <div ><span class="ct l">' . sub_str($value['Products_Name'], 16, false) . '</span>
                          ' . $choujiang . '
                          </div>
                          <div class="clear"></div>
                          <div class="t1">销量:' . $value['Products_Sales'] . '</div>
                          <div class="t10 l"></div>
                          <div class="shou r"></div>
                          <div class="clear"></div>
                          <div class="tuan">
                          <div class="t9 l">¥' . $value['Products_PriceT'] . ' </div>
                          <div class="t8 l"><del>¥' . $value['Products_PriceD'] . '</del></div>
                          <div class="t9 r">' . $value['people_num'] . '人团</div>
                          </div>';
                        if ($starttime>$time) {
                            $lasttime = $starttime - time();
                            $hour = intval($lasttime/3600)>0?intval($lasttime/3600):0;
                            $minute = intval(($lasttime-360*$hour)/60)?intval(($lasttime-360*$hour)/60):0;
                            $minute = intval($minute/60);
                            echo '<div class="tuan1"><a href="">即将开团 倒计时'.$hour.'时'.$minute.'分</a></div>';
                        } else  if ($endtime<$time) {
                            echo '<div class="tuan1"><a href="">拼团已结束</a></div>';
                        } else {
                            echo '<div class="tuan1"><a href="' . $lianjie . '">立即开团</a></div>';
                        }
                        echo '</div>
                          <input name="starttime" type="hidden" value="' . $starttime . '">
                          <input name="endtime" type="hidden" value="' . $endtime . '">
                          </div>
                          <div class="clear"></div>';
                    }
                }
            }
        }
        ?>
		<?php include 'bottom.php';?>
	</div>
	</div>
</body>
</html>