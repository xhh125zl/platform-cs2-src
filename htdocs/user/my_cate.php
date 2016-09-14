<?
global $DB;
//查询分类下有几个商品
$cateArr = $DB->GetAssoc('shop_dist_category', '*', "where Users_ID = '". $UsersID ."' and User_ID = ". $UserID);
foreach($cateArr as $k =>$v) {
    $cateArr[$k]['countPro'] = count($DB->GetAssoc('shop_dist_product_db', 'id', "where Cate_ID = " .$v['Category_ID']));
}
//编辑分类
$editFlag = false;
if (isset($_GET['do']) && $_GET['do'] == 'edit') {
    $editFlag = true;
}
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>我的分类</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/layer.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<!--逻辑处理JS myCate-->
<script type="text/javascript" src="../static/user/js/myCate.js"></script>
<body>
<div class="w">
    <div style="text-align:center; line-height:30px; background:#fff; padding:5px 0">
        <span class="l"><a><img src="../static/user/images/gdx.png" width="30" height="30" style=""></a></span>商品管理
    </div>
    <div class="clear"></div>
    <div class="header">
        <ul>
            <li><a href="?act=products">
                    <img src="../static/user/images/pro_1.png" width="40" height="40">
                    <p>出售中</p>
                </a></li>
            <li><a href="?act=products&state=1">
                    <img src="../static/user/images/pro_2.png" width="40" height="40">
                    <p>>已下架</p>
                </a></li>
            <li><a href="?act=my_cate">
                    <img src="../static/user/images/pro_3.png" width="40" height="40">
                    <p>我的分类</p>
                </a></li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="cate_list">
        <ul>
            <?
                foreach($cateArr as $v) {
            ?>
            <li>
                <span class="left title_x"><h1><?=$v['Category_Name']?></h1><p>共<?=$v['countPro']?>件商品</p></span>
                <span class="right edit_x" style="<?=$editFlag ? '' : 'display: none;'?>">
                	<i class="fa fa-trash-o fa-x" aria-hidden="true" del_id="<?=$v['Category_ID']?>"></i>
                    <i class="fa fa-check-square-o fa-x" aria-hidden="true" cateName="<?=$v['Category_Name']?>" cateID="<?=$v['Category_ID']?>"></i>
                </span>
                <div class="clear"></div>
            </li>
            <?}?>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="kb"></div>
    <div class="bottom_x">
        <div class="foot_nav1">
            <ul>
                <li id="addCate"><i class="fa fa-plus-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;添加分类</li>
                <li><i class="fa fa-check-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;<?=$editFlag ? '<a href="?act=my_cate">取消编辑</a>' : '<a href="?act=my_cate&do=edit">编辑分类</a>'?></li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
