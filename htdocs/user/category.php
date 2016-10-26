<?php
if (!defined('USER_PATH')) exit();

require_once CMS_ROOT . '/include/api/product_category.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

$category = product_category::get_all_category();

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes"> 
<title>产品分类</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<script type="text/javascript" src="../static/js/template.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l" href="javascript:history.back();"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>产品分类
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
                    <p>分类管理</p>
                </a></li>
        </ul>
    </div>
    <div class="clear"></div>
<?php
foreach ($category as $cate) {
    if (!isset($cate['child'])) continue;   
?>    
    <div class="primary_x1">
        <p><span class="l"><?php echo $cate['Category_Name'];?></span><span class="r"><a href="?act=search&fid=<?php echo $cate['Category_ID'];?>">查看全部>></a></span></p>
        <div class="clear"></div>
        <ul>
<?php
    foreach ($cate['child'] as $vcate) {
?>        
        	<li>
            	<span class="primary_pr l"><a href="?act=search&fid=<?php echo $cate['Category_ID'];?>&sid=<?php echo $vcate['Category_ID'];?>"><?php echo $vcate['Category_Name'];?></a><p><?php echo $vcate['Category_Name'];?></p></span>
                <span class="r">
                    <?
                        if ($vcate['Category_Img']) {
                            ?>
                                <img src="<?php echo B2C_URL . $vcate['Category_Img']; ?>">
                            <?
                        }
                    ?>
                </span>
                <div class="clear"></div>
            </li>
<?php
    }
?>            
      </ul>
    </div>
<?php
}
?>    
   
</div>
</body>
</html>
