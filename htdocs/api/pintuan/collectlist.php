<?php 
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

    $sql = "select * from pintuan_collet a left join pintuan_products b on a.productid = b.products_id where a.users_ID='$UsersID' and a.userid='$UserID';";
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
<title>我的收藏</title>
</head>
<link href="/static/api/pintuan/css/shoucang.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script>
<body>
    <div class="dingdan">
        <span class="fanhui l"><a onclick="history.go(-1)"><img src="/static/api/pintuan/images/fanhui.png"></a></span>
        <span class="querendd l">我的收藏</span>
        <div class="clear"></div>
    </div>
    <?php 
        if (!empty($list)) {
            foreach ($list as $item) {
                $images = json_decode(htmlspecialchars_decode($item["Products_JSON"]), true)['ImgPath']['0'];
     ?>
    <div class="chanpin">
        <div class="chanpin1">
             <span class="chanpin3 l"><img src="<?php echo $images; ?>"></span>
             <span class="cp l"><?php echo $item['Products_Name']; ?></span>
             <span class="sc l"><img src="/static/api/pintuan/images/index-1_18_1_18.png" width="14" height="16"></span>
             <div class="clear"></div>
        </div>
        <?php if ($item['pintuan_type'] == 0) { ?>
        <div class="futime2">
            <span class="fuk1 r"><a href="#">立即开团</a></span>
            <span class="fuk r"><a><?php echo $item['people_num']; ?>人团</a></span>
        </div>
        <?php } else { ?>
        <div class="futime3">活动已结束</div>
        <?php } ?>
    </div>
    <div class="clear"></div>
    <?php } } ?>
</body>
</html>