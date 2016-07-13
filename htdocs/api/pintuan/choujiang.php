<?php 
    require_once('comm/global.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');

    if (isset($_GET["UsersID"])) {
        $UsersID = $_GET["UsersID"];
    } else {
        echo '缺少必要的参数';
        exit;
    }

    if (empty($_SESSION)) {
        header("location:/api/".$UsersID."/pintuan/");
    }

    $UserID = $_SESSION[$UsersID."User_ID"];
    
    //url_teamstatus 0全部，1抽奖成功，2抽奖失败
    //db_teamstatus 0拼团中，1拼团成功，2已中奖，3未中奖，4拼团失败, 5已退款
    $teamstatus = $_GET['teamstatus'];

    $sql = "select * from pintuan_teamdetail td left join pintuan_team t on td.teamid=t.id left join pintuan_products p on t.productid=p.products_id left join user_order o on o.order_id=td.order_id where td.userid='$UserID'";
    switch ($teamstatus) {
        case '1':
            $sql .= ' and t.teamstatus = 2';
            break;
        case '2':
            $sql .= ' and t.teamstatus in (3,5)';
            break;
        default:
            $sql .= ' and t.teamstatus in (2,3,5)';
            break;
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
	<title>我的抽奖</title>
    <link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
    <link href="/static/api/pintuan/css/biaoqian.css" rel="stylesheet" type="text/css">
	<link href="/static/api/pintuan/css/shoucang.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="/static/api/pintuan/js/jquery.1.4.2-min.js"></script>
	<script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script>
</head>
<body>
	<div class="dingdan">
		<span class="fanhui l"><a href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
		<span class="querendd l">我的抽奖</span>
		<div class="clear"></div>
	</div>
    <ul id="tags">
        <li style="width:33%; border-width:0px;" <?php echo $teamstatus == '0' ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/choujiang/0/"; ?>">全部</a></li>
        <li style="width:33%; border-width:0px;" <?php echo $teamstatus == '1' ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/choujiang/1/"; ?>">抽奖成功</a></li>
        <li style="width:33%; border-width:0px;" <?php echo $teamstatus == '2' ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/choujiang/2/"; ?>">抽奖失败</a></li>
    </ul>
	<?php foreach ($list as $item) {
        $pintuan_sp = json_decode(htmlspecialchars_decode($item["Order_CartList"]), true);
	?>
	<div class="chanpin">
        <div class="chanpin1">
            <span class="chanpin3 l"><img style="width:50px;height:50px;" src="<?php echo $pintuan_sp['ImgPath']; ?>"></span>
            <span class="cp1 l"><?php echo $item['Products_Name']; ?></span>
            <div class="clear"></div>
        </div>
        <div class="jiaj">实付：¥<?php echo $item['Order_TotalPrice']; ?>（<?php echo $pintuan_sp['IsShippingFree'] == 1 ? '免运费' : '含运费：¥'.floatval($pintuan_sp['Shipping_fee']); ?>）</div>
    	<div class="futime2">
          <?php switch ($item['teamstatus']) {
            case '2':
              echo '<span class="fuk r"><a>已中奖</a></span>';
              break;
            case '3':
              echo '<span class="fuk r"><a>退款中</a></span>';
              break;
            case '5':
              if (intval($item['Is_Draw']) == 0) {
                echo '<span class="fuk r"><a>已退款</a></span>';
                break;
              }
          } ?>
        </div>
    </div>
    <div class="clear"></div>
    <?php } ?>
<div style="height:70px;"></div>
<div class="cotrs">
  <a id="f1" href="<?php echo "/api/$UsersID/pintuan/"; ?>"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br/>首页</a>
  <a id="f2" href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"><img src="/static/api/pintuan/images/002-2.png" width="22px" height="22px" style="margin-top:3px;"/><br/>搜索</a>
  <a id="f3" href="<?php echo "/api/$UsersID/pintuan/user/"; ?>" class="thisclass"><img src="/static/api/pintuan/images/002-3.png" width="22px" height="22px" style="margin-top:3px;"/><br/>我的</a>
</div> 
</body>
</html>