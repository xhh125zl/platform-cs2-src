<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/product.class.php';
require_once CMS_ROOT . '/include/api/count.class.php';
require_once CMS_ROOT . '/include/api/shopconfig.class.php';
require_once CMS_ROOT . '/include/api/message.class.php';
require_once CMS_ROOT . '/include/api/users.class.php';
require_once CMS_ROOT . '/include/api/distribute.class.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;
if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';
    if ($do == 'count') {
        $counter = count::countIncome(['Biz_Account' => $BizAccount]);

        echo json_encode($counter);

    } else if ($do == 'countPeople') {
        $countPeople = count::countPeople(['Biz_Account' => $BizAccount]);

        echo json_encode($countPeople);

    } else if ($do == 'getcash') {
        $cash = distribute::getcash(['Biz_Account' => $BizAccount]);

        if (isset($cash['errorCode']) && $cash['errorCode'] == 0) {
            $totalCash = 0;
            if (isset($cash['yijiGateWayBalance'])) {
                $totalCash += $cash['yijiBalance'] + $cash['yijiGateWayBalance']['availableBalance'];
            } else {
                $totalCash += $cash['yijiBalance'];
            }
            $data = [
                'errorCode' => 0,
                'msg' => '我的钱包金额获取成功',
                'totalCash' => number_format($totalCash, 2)
            ];
        } else {
            $data = [
                'errorCode' => 1,
                'msg' => '我的钱包金额获取失败'
            ];
        }
        echo json_encode($data);

    } else if ($do == 'msgUnreadCount') {
        //获取消息页未读条数
        //系统消息未读条数
        //获取商家注册时间，以确认显示信息
        $biz_info = $DB->GetRs('biz', 'Biz_CreateTime', 'where `Biz_ID` = '.$BizID);
        $DB->Get("announce","announce.*,announce_record.Record_ID","left join `announce_record` on announce.Announce_ID = announce_record.Announce_ID and announce_record.Biz_ID = ".$BizID." where announce.Announce_Status = 1 and Announce_CreateTime > ".$biz_info['Biz_CreateTime']." order by announce_record.Record_ID,announce.Announce_CreateTime desc");
        $unread_system_nums = 0;
        while ($r=$DB->fetch_assoc()) {
            if (!(isset($r['Record_ID']) && $r['Record_ID'] > 0)) {
                $unread_system_nums++;
            }
        }
        //订单消息未读条数
        $result = message::getMsgOrder(['Biz_Account' => $BizAccount]);
        if ($result['errorCode'] == 0) {
            $unread_order_nums = $result['data']['unReadCount'];
        } else {
            $unread_order_nums = 0;
        }
        //分销消息未读条数
        $result = message::getMsgDistribute(['Biz_Account' => $BizAccount]);
        if ($result['errorCode'] == 0) {
            $unread_distribute_nums = $result['data']['unReadCount'];
        } else {
            $unread_distribute_nums = 0;
        }
        //提现消息未读条数
        $result = message::getMsgWithdraw(['Biz_Account' => $BizAccount]);
        if ($result['errorCode'] == 0) {
            $unread_withdraw_nums = $result['data']['unReadCount'];
        } else {
            $unread_withdraw_nums = 0;
        }
        //计算总未读条数
        $total_unread_nums = $unread_system_nums + $unread_order_nums + $unread_distribute_nums + $unread_withdraw_nums;
        echo json_encode(array('errorCode' => 0, 'data' => $total_unread_nums));
    }
    exit();
}

//获取配置信息
$result = shopconfig::getConfig(['Biz_Account' => $BizAccount]);
if ($result['errorCode'] != 0) {
    die($result['msg']);
}
$config = $result['data'];

//商家审核记录
$bizRow = $DB->GetRs("Biz", 'is_auth', "WHERE Biz_Account='" . $BizAccount . "'");
$auth_status = get_auth_statusText($bizRow['is_auth']);

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>店铺管理</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<body>
<div class="w">
	<div class="head_bg">
        <!-- message -->
    	<div class="right">
            <span class="commenting">
            	<a href="?act=msg_system" class="msg"></a>
                <i class="fa  fa-commenting-o fa-x" aria-hidden="true"></i>
            	<p style="display:none;" id="total_unread_nums"><a>0</a></p>
            </span>
        </div><!--//message -->
        <div class="clear"></div>
        <span class="head_pho l"><a href="?act=setting"><img src="<?php echo IMG_SERVER . getImageUrl($config['ShopLogo'], 2);?>"></a></span>
        <span class="head_name l">
        	<a><?php echo $config['ShopName'];?></a>
            <p><span><i>V</i></span><span style=" background:#0292d4; padding:0px 5px; border-top-right-radius:3px;border-bottom-right-radius:3px;"><?php echo $auth_status;?></span></p>
        </span>
        <span class="head_pho l" style=" padding-left:30px"><a id="previewShop" style="color:#fff"><i class="fa  fa-eye fa-x" aria-hidden="true"></i><br>预览</a></span>       
    </div>

    <div  class="clear"></div>
    <div class="income_x">
        <ul class="income_t">
            <li style="border-right:1px #fff solid;">
                <a>本月收入（元）<p class="price_t" id="monthIncome">0</p></a>
            </li>
            <li>
                <a>累计收入（元）<p class="price_t" id="totalIncome">0</p></a>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="income_today">
    	<span class="l">
        	<p class="in">我的钱包（元）</p>
            <p class="price_in" id="cash">0</p>
        </span>
        <span class="r" style="margin-top:21px;"><a href="<?php echo B2C_URL; ?>pay/yijipay/wallet.php" style="padding:4px 15px; color:#fff; background:#ff6600; border-radius:3px">提现</a></span>
    </div>
    <div class="clear"></div>
    <div class="daily_x">
        <ul>
            <li>
            	<p>今日会员</p>
            	<p><strong id="userToday">0</strong></p>
            </li>
            <li>
            	<p>今日分销商</p>
            	<p><strong id="disToday">0</strong></p>
            </li>
            <li>
            	<p>今日代销人数</p>
            	<p><strong id="shareToday">0</strong></p>
            </li>
            <li>
            	<p>今日订单</p>
            	<p><strong id="dayOrderCount">0</strong></p>
            </li>
            <li>
            	<p>今日交易额</p>
            	<p><strong id="dayTradeVolume">0</strong></p>
            </li>
            <li>
            	<p style="color:#ff5500">今日收入</p>
            	<p><strong style="color:#ff5500" id="dayIncome">0</strong></p>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="product_x">
        <ul>
            <li style="border-right:1px #eee solid;">
                <a id="pro_x"><i class="fa  fa-plus-square fa-x" aria-hidden="true"></i>&nbsp;产品发布</a>
            </li>
            <li style="border-right:1px #eee solid;">
                <a href='?act=products'><i class="fa  fa-check-square fa-x" aria-hidden="true"></i>&nbsp;产品管理</a>
            </li>
            <li>
                <a href='?act=my_cate'><i class="fa  fa-check-square fa-x" aria-hidden="true"></i>&nbsp;分类管理</a>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="plate_x">
        <ul class="list_x">
            <li>
                <a href='?act=order_list'><img src="../static/user/images/ico_07.png" width="25" height="25"><p>订单管理</p></a>
            </li>
            <li>
                <a href='?act=user_list'><img src="../static/user/images/ico_02.png" width="25" height="25"><p>我的会员</p></a>
            </li>
            <li>
                <a href='?act=setting'><img src="../static/user/images/ico_04.png" width="25" height="25"><p>店铺配置</p></a>
            </li>
            <li>
                <a href='?act=distribute_list'><img src="../static/user/images/ico_01.png" width="25" height="25"><p>分销管理</p></a>
            </li>
            <li>
                <a href='?act=data_statistics'><img src="../static/user/images/ico_06.png" width="25" height="25"><p>数据统计</p></a>
            </li>
            <li>
                <a href='?act=financial_analysis'><img src="../static/user/images/ico_03.png" width="25" height="25"><p>财务分析</p></a>
            </li>
            <li>
                <a href='?act=learn'><img src="../static/user/images/ico_08.png" width="25" height="25"><p>学习中心</p></a>
            </li>
            <li>
                <a href='?act=web'><img src="../static/user/images/ico_05.png" width="25" height="25"><p>网页版</p></a>
            </li>
        </ul>
    </div>
    <div class="kb"></div>
    <!-- footer nav -->
<?php
$homeUrl = '/api/' . $UsersID . '/shop/';
$cartUrl = $homeUrl . 'allcategory/';
$ucenter = $homeUrl . 'member/';
?>    
<!--
    <div class="bottom" >
        <div class="footer">
            <ul style="margin-top: 5px;">
                <li><a href="<?php echo $homeUrl;?>">
                        <i class="fa  fa-home fa-2x" aria-hidden="true"></i><br> 首页
                    </a></li>
                <li><a href="/user/admin.php?act=store" style="color:#ff3600">
                        <i class="fa fa-gift fa-2x" aria-hidden="true"></i><br>开店
                    </a></li>
                <li><a href="<?php echo $cartUrl;?>">
                        <i class="fa  fa-shopping-cart fa-2x" aria-hidden="true"></i><br> 购物
                    </a></li>
                <li><a href="<?php echo $ucenter;?>">
                        <i class="fa  fa-user fa-2x" aria-hidden="true"></i><br> 我的
                    </a></li>
            </ul>
        </div>
    </div>
    -->
    <!--//footer nav -->
</div>
<script type="text/javascript">
    $("#pro_x").click(function(){
        layer.open({
        content: '<div class="sto_bg"><ul><li><h3>产品库挑选</h3><p>没有产品，想开分销商城的用户，不能发布自己的产品，只能代销。</p><span class="r"><input type="button"value="立即进入" onclick="location.href=\'?act=search\'"></span><div class="clear"></div></li><li><h3>开通官方分销商城</h3><p>有产品，可绑定自己的微信公众号，独立运营，同时还可以把产品推荐到产品库，同其他代销。</p><span class="r"><input type="button"value="立即进入" onclick="location.href=\'?act=product_add\'"></span><div class="clear"></div></li><li><h3>供货商，只供货</h3><p>只向平台提供货源，不做自己的独立的店铺。</p><span class="r"><input type="button"value="立即进入" onclick="location.href=\'?act=product_supply\'"></span><div class="clear"></div></li></ul></div>'
        });	
    });
    
    $(function(){
        $.get('?act=store&inajax=1&do=count', {}, function(json) {
            if (json.errorCode == '0') {
                var counter = json.count;
                $("#totalIncome").html(counter.totalCount.Amount);
                $("#monthIncome").html(counter.monthCount.Amount);
                 
                $('#dayOrderCount').html(counter.dayCount.orderCount);     //今日订单
                $('#dayTradeVolume').html(counter.dayAllCount.Amount);     //今日交易额
                $("#dayIncome").html(counter.dayCount.Amount);             //今日收入
            } else {
                layer.open({content:'用户统计数据获取失败，请刷新此页面重试'});
            }
        }, 'json');
        //获取今日会员、今日分销商、今日代销人数
        $.get('?act=store&inajax=1&do=countPeople', {}, function(json) {
            if (json.errorCode == '0') {
                $('#userToday').html(json.data.userToday);     //今日会员
                $('#disToday').html(json.data.disToday);     //今日分销商
                $("#shareToday").html(json.data.shareToday);             //今日代销人数
            } else {
                layer.open({content:'用户统计数据获取失败，请刷新此页面重试'});
            }
        }, 'json');
        //获取可提现金额
        $.get('?act=store&inajax=1&do=getcash', {}, function(json) {
            if (json.errorCode == '0') {
                $('#cash').html(json.totalCash);     //获取可提现金额
            } else {
                layer.open({content:'用户统计数据获取失败，请刷新此页面重试'});
            }
        }, 'json');
        //获取未读信息条数
        $.get('?act=store&inajax=1&do=msgUnreadCount', {}, function(data) {
            if (data.errorCode == 0) {
                if (data.data > 99) {
                    $('#total_unread_nums').removeAttr('style');
                    $('#total_unread_nums a').html('···');
                } else if (data.data > 0) {
                    $('#total_unread_nums').removeAttr('style');
                    $('#total_unread_nums a').html(data.data);
                }
            } else {
                layer.open({content:'用户统计数据获取失败，请刷新此页面重试'});
            }
        }, 'json');

        //店铺预览
        <?php
            $UsersID = Users::findUsersIDByAccount($BizAccount);
            $ShopUrl = SHOP_URL . 'api/' . $UsersID . '/shop/?preview=1';
        ?>
        $("#previewShop").click(function(){
            var shopurl = '<?php echo $ShopUrl ?>';
            location.href = shopurl;
        });
    });
</script>
</body>
</html>
