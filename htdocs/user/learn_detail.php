<?php
require_once "config.inc.php";
$articleid = isset($_GET['id'])?$_GET['id']:0;
if($articleid){
    $rsArticle = $DB->GetRS("shop_articles", "Article_ID,Article_Title,Article_CreateTime,Article_Content,Article_Editor,Article_ImgPath", "WHERE Article_ID='{$articleid}' AND Article_Status = 1");

}
?>


<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>学习中心</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l" href='javascript:history.back();'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>学习中心
    </div>
     <div class="learn_main">
    	<h3><?=$rsArticle['Article_Title'] ?></h3>
        <h5>2016-09-21</h5>
        <?php if($rsArticle['Article_ImgPath']){ ?>
        <div class="image_x"><img src="<?=$rsArticle['Article_ImgPath'] ?>"></div>
        <?php } ?>
        <p>
        	<?=$rsArticle['Article_Content']?htmlspecialchars_decode($rsArticle['Article_Content']):"" ?>
        </p>
    </div>
</div>
</body>
</html>
