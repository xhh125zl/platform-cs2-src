<?php
require_once CMS_ROOT . '/include/api/product_category.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';

$mark = false;  //标识是否为二级分类页面
/*if (isset($_GET['firstCateID']) && $_GET['firstCateID'] > 0) {
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
}*/

//获取商家自己的分类
$res = product_category::getDev401firstCate($BizAccount);
$Category = [];
if (isset($res['errorCode']) && $res['errorCode'] == 0) {
    foreach ($res['cateData'] as $k => $v) {
        $result = product_category::getDev401SecondCate(['Biz_Account' => $BizAccount, 'firstCateID' => $v['Category_ID']]);
        if (isset($result['errorCode']) && $result['errorCode'] == 0 && isset($result['cateData']) && count($result['cateData']) > 0) {
            $res['cateData'][$k]['child'] = $result['cateData'];
        }
    }
    $Category = $res['cateData'];
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
        <span class="l"><a href="javascript:history.back();"><img src="../static/user/images/gdx.png" width="30" height="30" style=""></a></span>分类管理
        <a class="r" id="addCate"><i class="fa fa-plus-square fa-2x" aria-hidden="true" style="color:#ff5500"></i></a>
    </div>
    <div class="clear"></div>
    <div class="cate_list">
        <ul>
            <?php if (!empty($Category)) { foreach($Category as $k => $v) { ?>
            <li>
                <span class="left title_x firstCate" firstCateId="<?php echo $v['Category_ID']; ?>" value="0">
                    <h1>
                        <?php if (isset($v['child']) && count($v['child']) > 0) {echo '<i class="fa fa-plus-square fa-x" aria-hidden="true"></i>';} ?>
                        <a href="javascript:;"><?php echo $v['Category_Name']; ?></a>
                    </h1>
                </span>
                <span class="right edit_x">
                    <i class="fa fa-plus-square fa-x" aria-hidden="true" del_id="<?=$v['Category_ID']?>"></i>
                    <i class="fa fa-pencil fa-x" aria-hidden="true" cateName="<?=$v['Category_Name']?>" cateID="<?=$v['Category_ID']?>"></i>
                	<i class="fa fa-trash-o fa-x" aria-hidden="true" del_id="<?=$v['Category_ID']?>"></i>
                </span>
                <div class="clear"></div>
            </li>
                <?php if (isset($v['child']) && !empty($v['child'])) { foreach ($v['child'] as $kk => $vv) { ?>
                <li class="secondCate secondCate_<?php echo $v['Category_ID']; ?>" style="display:none;">
                    <span class="left title_x">
                        <h1 style="margin-left: 30px;"><a href="javascript:;"><?php echo $vv['Category_Name']; ?></a></h1>
                    </span>
                    <span class="right edit_x">
                        <i class="fa fa-pencil fa-x" aria-hidden="true" cateName="<?php echo $vv['Category_Name']; ?>" cateID="<?php echo $vv['Category_ID']; ?>"></i>
                        <i class="fa fa-trash-o fa-x" aria-hidden="true" del_id="<?php echo $v['Category_ID']; ?>"></i>
                    </span>
                    <div class="clear"></div>
                </li>
                <?php }} ?>
            <?php }} ?>
        </ul>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>
<script type="text/javascript">
    $(function(){
        //编辑分类
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
        //显示子分类
        $(document).on('click', '.firstCate' ,function(){
            var firstCateId = $(this).attr('firstCateId');
            var secondCate_style = $(this).attr('value');
            if (secondCate_style == 0) {
                $('.secondCate').slideUp(300, function(){   //收起
                    $(this).attr('value', '0');
                });
                $('.secondCate_'+firstCateId).slideDown(300, function(){    //展开
                    $(this).attr('value', '1');
                });
            }
        });
    });
</script>