<?
    require_once "lib/user.php";
?>

<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>我的会员</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/jquery.SuperSlide.2.1.1.js"></script>
<body>
<div class="w">
    <div class="back_x">
        <a href="javascript:history.back();" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>我的会员
    </div>
    <div class="user_ls">
        <ul>
            <?
                foreach ($userList['data'] as $k => $v) {
            ?>
            <li>
                <a href="?act=user_detail&User_ID=<?=$v['User_ID']?>">
                    <span class="l"><img src="<?=strlen($v['User_HeadImg']) > 0 ? $v['User_HeadImg'] : '/static/api/images/user/face.jpg'?>"></span>
                    <span class="infor_x l"><?=strlen($v['User_NickName']) > 0 ? $v['User_NickName'] : '暂无昵称'?><p>手机号：<?=strlen($v['User_Mobile']) > 0 ? $v['User_Mobile'] : '暂无手机号'?></p></span>
                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                    <div class="clear"></div>
                </a>
            </li>
            <?}?>
        </ul>
    </div>
</div>
</body>
</html>
