<?php
require_once CMS_ROOT . '/include/api/product_category.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';

$mark = false;  //标识是否为二级分类页面
if (isset($_GET['firstCateID']) && $_GET['firstCateID'] > 0) {
    $mark = true;
    $data = [
        'Biz_Account' => $BizAccount,
        'firstCateID' => $_GET['firstCateID']
    ];
    $result = product_category::getDev401SecondCate($data);
} else {
    $result = product_category::getDev401firstCate($BizAccount);
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
<?php if ($mark) { ?>
    <script type="text/javascript" src="../static/user/js/mysecondCate.js?t=<?php echo time(); ?>"></script>
<?php } else { ?>
    <script type="text/javascript" src="../static/user/js/myfirstCate.js?t=<?php echo time(); ?>"></script>
<?php } ?>
<body>
<div class="w">
    <div style="text-align:center; line-height:30px; background:#fff; padding:5px 0">
        <span class="l"><a href="<?=strpos($_SERVER['REQUEST_URI'],'firstCateID') ? substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'&')) : 'javascript:history.back();' ?>"><img src="../static/user/images/gdx.png" width="30" height="30" style=""></a></span>分类管理
        <!-- <a class="r" id="addCate"><i class="fa fa-plus-square fa-2x" aria-hidden="true" style="color:#ff5500"></i></a> -->
    </div>
    <div class="clear"></div>
    <div class="cate_list">
        <input type="hidden" name="firstCateID" value="<?=isset($_GET['firstCateID']) ? $_GET['firstCateID'] : 0?>"/>
        <ul>
            <?
                foreach($cateArr as $v) {
            ?>
            <li>
                <span class="left title_x"><h1><a href="<?php echo $mark ? 'javascript:void(0);' : $_SERVER['REQUEST_URI'].'&firstCateID='.$v['Category_ID']; ?>" title="点击显示子分类"><?=$v['Category_Name']?></a></h1></span>
                <span class="right edit_x" style="display: none;">
                    <i class="fa fa-pencil fa-x" aria-hidden="true" cateName="<?=$v['Category_Name']?>" cateID="<?=$v['Category_ID']?>"></i>
                	<i class="fa fa-trash-o fa-x" aria-hidden="true" del_id="<?=$v['Category_ID']?>"></i>
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
                <li><i class="fa fa-check-square fa-x" aria-hidden="true" style="font-size:16px; color:#ff5500"></i>&nbsp;<a href="javascript:;" id="editCate" value="0">编辑分类</a></li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    $(function(){
        $('#editCate').click(function(){
            var value = $(this).attr('value');
            if (value == 0) {
                $('.edit_x').removeAttr('style');
                $(this).attr('value', '1');
                $(this).html('取消编辑');
            } else {
                $('.edit_x').attr('style', 'display:none;');
                $(this).attr('value', '0');
                $(this).html('编辑分类');
            }
        });
    });
</script>