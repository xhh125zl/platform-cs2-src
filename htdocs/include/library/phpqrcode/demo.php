<?php
//引入phpqrcode库文件
include('phpqrcode.php'); 

// 二维码数据 

$data = 'http://sale.jd.com/m/act/N1maiIC8E6.html?resourceType=home_floor&resourceValue=15687&client=m&sid=099cab963858e03de1d043e00f17b6fb'; 

// 生成的文件名 

$filename = 'baidu.png'; 

// 纠错级别：L、M、Q、H 

$errorCorrectionLevel = 'H';  

// 点的大小：1到10 

$matrixPointSize = 10;  

//创建一个二维码文件 

QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

//输入二维码到浏览器

QRcode::png($data); 