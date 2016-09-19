<?
require_once CMS_ROOT . '/include/api/product_category.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';
if (isset($_GET['firstCateID']) && $_GET['firstCateID'] > 0) {
    $data = [] ;
    $data['Biz_Account'] = $BizAccount;
    $data['firstCateID'] = $_GET['firstCateID'];
    $result = product_category::getDev401SecondCate($data);
} else {
    $bizAccount = $BizAccount;
    $result = product_category::getDev401firstCate($bizAccount);
}
if (isset($result['cateData'])) {
    $cateArr = $result['cateData'];
} else {
    $cateArr = [];
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

<body>
<div class="w">
    <div style="text-align:center; line-height:30px; background:#fff; padding:5px 0">
        <span class="l"><a href="<?=strpos($_SERVER['REQUEST_URI'],'firstCateID') ? substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'&')) : ''?>"><img src="../static/user/images/gdx.png" width="30" height="30" style=""></a></span>商品管理
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
                    <p>已下架</p>
                </a></li>
            <li><a href="?act=my_cate">
                    <img src="../static/user/images/pro_3.png" width="40" height="40">
                    <p>我的分类</p>
                </a></li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="cate_list">
        <input type="hidden" name="firstCateID" value="<?=isset($_GET['firstCateID']) ? $_GET['firstCateID'] : 0?>"/>
        <ul>
            <?
                foreach($cateArr as $v) {
            ?>
            <li>
                <span class="left title_x"><h1><a href="<?=strpos($_SERVER['REQUEST_URI'], 'firstCateID') ? 'javascript:void(0);' :$_SERVER['REQUEST_URI'].'&firstCateID='.$v['Category_ID']?>" title="点击显示子分类"><?=$v['Category_Name']?></a></h1></span>
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
                <li><i class="fa fa-check-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;<?=$editFlag ? '<a href="'. substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'&')) .'">取消编辑</a>' : '<a href='.$_SERVER['REQUEST_URI'].'&do=edit>编辑分类</a>'?></li>
            </ul>
        </div>
    </div>
</div>
</body>
<?
    if (strpos($_SERVER['REQUEST_URI'], 'firstCateID')) {?>
        <script type="text/javascript" src="../static/user/js/mysecondCate.js"></script>
    <?} else {?>
        <script type="text/javascript" src="../static/user/js/myfirstCate.js"></script>
    <?}
?>
</html>
