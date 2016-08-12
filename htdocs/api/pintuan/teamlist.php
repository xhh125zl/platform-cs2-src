<?php 
   require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
    setcookie('url_referer', $_SERVER["REQUEST_URI"], time()+3600, '/', $_SERVER['HTTP_HOST']);
    //url_teamstatus 0全部，1拼团中，2拼团成功，3拼团失败
    //db_teamstatus 0拼团中，1拼团成功，2已中奖，3未中奖，4拼团失败, 5已退款
    $teamstatus = $_GET['teamstatus'];
    
    $sql = "select * from pintuan_teamdetail td left join pintuan_team t on td.teamid=t.id left join pintuan_products p on t.productid=p.products_id left join user_order o on o.order_id=td.order_id where td.userid='$UserID'";
    if (!empty($teamstatus)) {
        if ($teamstatus == '1') {
            $sql .= ' and t.teamstatus = 0';
        } elseif ($teamstatus == '2') {
            $sql .= ' and t.teamstatus in (1,2,3,5)';
        } elseif ($teamstatus == '3') {
            $sql .= ' and t.teamstatus in (4,5)';
        } else {
            
        }
    }
    $sql .= " order by t.addtime desc";

    $result = $DB->query($sql);
    $list = array();
    while ($res = $DB->fetch_assoc($result)) {
        $list[] = $res;
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>我的团</title>
    <link href="/static/api/pintuan/css/biaoqian.css" rel="stylesheet" type="text/css">
    <link href="/static/api/pintuan/css/zhezhao.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script>
</head>
<body>
    <div class="dingdan">
        <span class="fanhui l"><a href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
        <span class="querendd l">我的团</span>
        <div class="clear"></div>
    </div>
<div id="con">
    <ul id="tags">
        <li <?php echo $teamstatus == '0' ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/teamlist/0/"; ?>">全部</a></li>
        <li <?php echo $teamstatus == '1' ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/teamlist/1/"; ?>">拼团中</a></li>
        <li <?php echo $teamstatus == '2' ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/teamlist/2/"; ?>">拼团成功</a></li>
        <li <?php echo $teamstatus == '3' ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/teamlist/3/"; ?>">拼团失败</a></li>
    </ul>
  <div id="tagContent">
    <div id="tagContent0" class="tagContent selectTag">
        <?php if (!empty($list)) {
          foreach ($list as $item) {
            $pintuan_sp = json_decode(htmlspecialchars_decode($item["Order_CartList"]), true);
            $images = json_decode(htmlspecialchars_decode($item["Products_JSON"]), true)['ImgPath']['0'];
        ?>

        <div class="chanpin">
            <div class="chanpin1">
                <span class="l"><a href="<?php echo "/api/$UsersID/pintuan/teamdetail/{$item['id']}/"; ?>"><img style="width:100px;height: 100px;" src="<?php echo empty($images) ? '' : $images; ?>"></a></span>
                <span class="cp l" style="width:50%;">
                    <ul>
                        <li><strong><?php echo $pintuan_sp['ProductsName']; ?></strong></li>
                        <li><?php echo $res['Products_BriefDescription']; ?>
                        </li>
                    </ul>
                </span>
                <span class="jiage r"><?php echo $pintuan_sp['ProductsPriceT']; ?>/件</span>
                <div class="clear"></div>
            </div>
            <div class="t r">
                <span>合计：<?php echo $item["Order_TotalPrice"]; ?>(含物流费：¥<?php echo $pintuan_sp['IsShippingFree'] == 1 ? 0 : floatval($pintuan_sp['Shipping_fee']); ?>)</span>
                <div class="clear"></div>
            </div>
            <div class="futime2">
              <?php switch ($item['teamstatus']) {
                case 0:
                  echo '<span class="fuk r"><a>拼团中</a></span>';
                  break;
                case 1:
                  echo '<span class="fuk r"><a>拼团成功</a></span>';
                  break;
                case 2:
                    echo '<span class="fuk r"><a>已中奖</a></span>';
                    break;
                case 3:
                    echo '<span class="fuk r"><a>未中奖</a></span>';
                    break;
                case 4:
                    echo '<span class="fuk r"><a>拼团失败</a></span>';
                    break;
                case 5:
                    echo '<span class="fuk r"><a>已退款</a></span>';
                    break;
              }
              ?>
            </div>
        </div>

        <?php } } ?>
    </div>
  </div>
</div>
<?php include 'bottom.php';?>
</body>
</html>
