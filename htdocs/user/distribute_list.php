<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/distribute.class.php';
require_once CMS_ROOT . '/include/helper/page.class.php';

//分页初始化
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
//每页显示个数
$pageSize = 2;
$level = 1;  //分销商等级  1、2、3级

$transfer = ['Biz_Account' => $BizAccount, 'pageSize' => $pageSize, 'level' => $level];
$result = distribute::getDistribute($p, $transfer);

//print_r($result);die;
?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>分销管理</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/jquery.SuperSlide.2.1.1.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a href="?act=store" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>分销管理
    </div>
    <div class="slideTxtBox">
			<div class="hd distribute_x">
				<ul><li>一级分销商</li><li>二级分销商</li><li>三级分销商</li></ul>
			</div>
			<div class="bd">
				<ul>
					<li>
                    	<div class="user_ls">
                            <ul style="margin:0">
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                            </ul>
                        </div>
                    </li>
				</ul>
                <ul>
					<li>
                    	<div class="user_ls">
                            <ul style="margin:0">
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                            </ul>
                        </div>
                    </li>
				</ul>
                <ul>
					<li>
                    	<div class="user_ls">
                            <ul style="margin:0">
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                                <li><a>
                                    <span class="l"><img src="images/2p-5_03.png"></span>
                                    <span class="infor_x l" style="text-align:left">你是我的小苹果<p>手机号：12345678901</p></span>
                                    <span class="r"><i class="fa  fa-angle-right fa-2x" aria-hidden="true"></i></span>
                                    <div class="clear"></div>
                                </a></li>
                            </ul>
                        </div>
                    </li>
				</ul>
			</div>
		</div>
        <script type="text/javascript">jQuery(".slideTxtBox").slide();</script>
</div>
</body>
</html>
