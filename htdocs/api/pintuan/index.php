<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/url.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/tools.php');

!isset($_GET["UsersID"]) && die("缺少必要的参数e");
$UsersID = $_GET["UsersID"];
$rsConfig = shop_config($UsersID);
empty($rsConfig) && die("商城没有配置");

$BizID = isset($_GET['BizID']) && $_GET['BizID']?$_GET['BizID']:0;
// 分销相关设置
$dis_config = dis_config($UsersID);
if (empty($dis_config)) {
    $dis_config = [];
}
// 合并参数
$rsConfig = array_merge($rsConfig, $dis_config);
$is_login = 1;
$owner = get_owner($rsConfig, $UsersID);
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php');
$user_id = $_SESSION[$UsersID . 'User_ID'];

// banner图
$DB->get('pintuan_config', '*', "where Users_ID = '" . $UsersID . "'");
$rsConfig = $DB->fetch_assoc();

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
// 获得所有拼团活动
$pintuan = $DB->query("SELECT * FROM `pintuan_products` where Users_ID='" . $UsersID . "'".($BizID?" AND Biz_ID ={$BizID}":""));
// 获取时间 处理时间状态
$time = time();
while ($chuli = $DB->fetch_assoc($pintuan)) {
    if ($chuli['starttime'] <= $time || $chuli['stoptime'] >= $time) {
        // 拼团活动开始状态为0 关闭为1
        $DB->Set('pintuan_products', "pintuan_type='0'", 'WHERE  Products_ID=' . $chuli['Products_ID'] . '');
    } else {
        // 已结束 关闭
        $DB->Set('pintuan_products', "pintuan_type='1'", 'WHERE  Products_ID=' . $chuli['Products_ID'] . '');
    }
}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>
<?php
$DB->query('select Users_Account from users where Users_ID="' . $UsersID . '"');
$Config = $DB->fetch_assoc();
// 商城名
$shopname = $Config['Users_Account'];
echo "" . $shopname . "的拼团商城";
?>
</title>
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
            						<a href="<?php echo $ress['url'];?>"><img
            							src="<?php echo $ress['img'];?>"> </a>
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
        // 产品
        $UsersID = $_GET['UsersID'];
        $result = $DB->Get("pintuan_category", "cate_id", "where Users_ID='{$UsersID}' and istop=1");
        $catelist = "";
        while ($res = $DB->fetch_assoc()) {
            $catelist .= "'," . $res['cate_id'] . ",',";
        }
        $catelist = trim($catelist, ',');
        if (empty($catelist)) {
            echo '您的拼团后台没有数据，请添加商品数据并将分类设置为在首页显示';
            exit();
        }
        $DB->query("SELECT * FROM `pintuan_products` where pintuan_type=0 AND Products_Status=1 and Users_ID='" . $UsersID . "'".($BizID?" AND Biz_ID ={$BizID}":"")." and Products_Category in ({$catelist}) order by Products_ID desc limit 0,6 ");

        $i = 0;
        $time = time();
        while ($res = $DB->fetch_assoc()) {
            if (empty($res)) {
                echo '您的后台没有数据  请添加商品数据';
                exit();
            }
            $image = json_decode($res['Products_JSON'], true);
            $path = $image['ImgPath']['0'];
            // 设置 贴图 和 抽奖标签
            if ($res['products_IsNew'] == 1) {
                // 新品
                $tie = "/static/api/pintuan/images/xp_1.png";
            } elseif ($res['products_IsRecommend'] == 1) {
                // 促销
                $tie = "/static/api/pintuan/images/xp_6.png";
            } elseif ($res['products_IsHot'] == 1) {
                // 热销
                $tie = "/static/api/pintuan/images/xp_2.png";
            }
            
            if ($res['Is_Draw'] == 0) {
                $choujiang = '<span class="cj_choujiang r">抽奖</span>';
            } elseif ($res['Is_Draw'] == 1) {
                $choujiang = '';
            }
            $lianjie = '/api/' . $UsersID . '/pintuan/xiangqing/' . $res['Products_ID'] . '/';
            // 获取开始和关闭时间设置倒计时
            $starttime = $res['starttime'];
            $endtime = $res['stoptime'];
            $pintuan_stop=date('Y-m-d',$res['stoptime']);
            $pintuan_start=date('Y-m-d',$res['starttime']);
            $time=date("Y-m-d",time());
            echo '<div class="chanpin">
                  <div class="biaoqian"><img src="' . $tie . '" width="55px" height="55px"></div>
                  <div class="tt"></div>
                  <div class="tp l"><img src="' . $path . '"></div>
                  <div class="jianjie1 l">
                  <div ><span class="ct l">' . sub_str($res['Products_Name'], 16, false) . '</span>
                  ' . $choujiang . '
                  </div>
                  <div class="clear"></div>
                  <div class="t1">销量:' . $res['Products_Sales'] . '</div>
                  <div class="t10 l"></div>
                  <div class="shou r"></div>
                  <div class="clear"></div>
                  <div class="tuan">
                  <div class="t9 l">¥' . $res['Products_PriceT'] . ' </div>
                  <div class="t8 l"><del>¥' . $res['Products_PriceD'] . '</del></div>
                  <div class="t9 r">' . $res['people_num'] . '人团</div>
                  </div>';
                if ($pintuan_start>$time) {
                    $lasttime = $starttime - time();
                    $hour = intval($lasttime/3600)>0?intval($lasttime/3600):0;
                    $minute = intval(($lasttime-360*$hour)/60)?intval(($lasttime-360*$hour)/60):0;
                    $minute = intval($minute/60);
                    echo '<div class="tuan1"><a href="">即将开团 倒计时'.$hour.'时'.$minute.'分</a></div>';
                } else  if ($pintuan_stop<$time) {
                        echo '<div class="tuan1"><a href="">拼团已结束</a></div>';
                } else {
                        echo '<div class="tuan1"><a href="' . $lianjie . '">立即开团</a></div>';
                }
                echo '</div>
                  <input name="starttime" type="hidden" value="' . $starttime . '">
                  <input name="endtime" type="hidden" value="' . $endtime . '">
                  </div>
                  <div class="clear"></div>';
            $i ++;
        }
        ?>
		<div class="kb"></div>
		<div class="clear"></div>
		<div class="cotrs">
			<a href="<?php echo "/api/$UsersID/pintuan/"; ?>" class="thisclass"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br />首页</a>
			<a href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"><img src="/static/api/pintuan/images/002-2.png" width="22px" height="22px" style="margin-top: 3px;" /><br />搜索</a>
			<a href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/002-3.png" width="22px" height="22px" style="margin-top: 3px;" /><br />我的</a>
		</div>
	</div>
	</div>
</body>
</html>