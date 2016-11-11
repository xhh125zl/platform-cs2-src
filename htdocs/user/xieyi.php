<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";

$rsBiz = $DB->GetRs("biz", 'is_agree', "WHERE Biz_ID=" . $BizID);

//同意注册协议
if (isset($_POST['inajax']) && $_POST['inajax'] == 1) {
    $data = ['is_agree' => 1];
    $DB->Set('biz', $data);

    $return = [
        'status' => 1,
    ];
    echo json_encode($return);
    exit();
}

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>商家入驻</title>
</head>
<link rel="stylesheet" href="/static/user/css/font-awesome.min.css" type="text/css">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>

<style>
/* CSS Document */
*{margin:0px; padding:0px;}
ul, li, dt, dd, ol, dl{list-style-type:none;}
a{color:#717171; text-decoration:none;}
input, textarea, button, a{ -webkit-tap-highlight-color:rgba(0,0,0,0); }
.clear{clear:both;}
img{border:none;vertical-align: middle;}
body{background-color:#f8f8f8;-webkit-tap-highlight-color:transparent;font-size:14px; font-family:"微软雅黑"}
.w{width:100%; margin:0 auto;}
input[type=button], input[type=submit], input[type=file], button { cursor: pointer; -webkit-appearance: none;vertical-align:middle; outline:none} 
.vbox{ width:100%; margin:0 auto;position:relative}
.vbox header{ border-bottom:1px #e4e4e4 solid; background:#fff; color:#717171; line-height:22px;padding: 8px 10px;}
.vbox header p{display: inline-block;font-weight: bold;}
.wrapper{ width:98%;padding:1%;overflow-x: hidden;position: absolute;}
.wrapper section.panel{ background:#fff;border: 1px solid #f4f4f4;}
.panel-body{padding:10px;}
/*  ==  == */
.icon-step-list{
	display: inline-block;
	vertical-align: middle;
	overflow: hidden;
	background:#ff6600;
	width:20px;
	height:20px;
	border-radius:50%;
}
.step-list{padding-left:25px;}
.step-list .icon-step-list{width:23px;height:23px;line-height:23px;background-position:0 -292px;}
.step-list .step-item h4{font-size:14px;font-weight:normal;color:#717171; line-height:25px}
.step-list .step-inner{*zoom:1;border-top:1px solid #e6e7ec;padding:15px 0 20px;}
.step-list .step-inner:after{content:"\200B";display:block;height:0;clear:both;}
.step-list .step-inner:first-child{border-top:0;}
.step-list .step-content{overflow:hidden;}
.step-list .step-content h4 i{ color:#ff6600}
.step-list .step-desc{font-size:13px;color:#7b7b7b;}
.step-list .step-list-opr{margin-top:0.5em;margin-left:1em;float:right;}
.step-list .step-list-opr a,.step-list .step-list-opr span{display:block;text-align:right;}
.step-list .step-list-opr a{ color:#ff6600}
.step-list .step-list-opr span i{ color:#ff6600}
.step-list .step-list-opr span{color:#7b7b7b;}
.step-list .step-list-opr span.warn{color:#e15f63;}
.step-list .step-list-opr .ico18-msg{margin-right:6px;}
.step-list .step-list-opr .btn{padding:5px 8px;}
.btn-primary {color:#fff !important;background-color:#ff6600;border-color:#ff6600;border-radius: 2px;}
.step-list .step-list-opr .btn-disabled{background:none;background-color:#d4d4d4;border-color:transparent;}
.step-list .step-box{position:relative;margin-top:20px;margin-bottom:20px;padding:0 5px;min-height:56px;border:1px solid #e6e7ec;}
.step-list .step-box .arrow-main-box{position:absolute;left:-7px;top:20px;}
.step-list .step-box .arrow-out{display:inline-block;width:0;height:0;border-width:7px;border-style:dashed;border-color:transparent;border-left-width:0;border-right-color:#e6e7ec;border-right-style:solid;position:absolute;left:-1px;}
.step-list .step-box .arrow-in{display:inline-block;width:0;height:0;border-width:7px;border-style:dashed;border-color:transparent;border-left-width:0;border-right-color:#ffffff;border-right-style:solid;position:absolute;}
.step-list .icon-step-main-box{position:absolute;left:-34px;top:13px;}
.step-list .icon-step-list{text-align:center;font-weight:400;color:#ffffff;font-size:16px;}
.step-list .icon-step-line{position:absolute;background-color:#e6e7ec;width:2px;left:-23px;}
.step-list .icon-step-line-up{height:30px;top:-17px;}
.step-list .no-extra-up .icon-step-line-up{display:none;}
.step-list .icon-step-line-down{height:100%;top:35px;}
.step-list .no-extra-down .icon-step-line-down{display:none;}
.step-list .step-list-opr span i.text-warning{ color:#1fb4f8;color:#1fbba6}
.btn-ipt{clear:both; text-align:center; margin-top:20px; margin-bottom:10px}
.btn-ipt input{background: #1fb4f8;padding: 6px 15px;border: none;color: #fff;outline: none;border-radius: 5px;}
</style>
<body>
<div class="w">
	<section class="vbox">
    <header class="header">
        <p>注册协议</p>
    </header>
    <section class="wrapper">
        <section class="panel">
            <form>
                <div class="panel-body">
                    <div class="step-list-wrp">
<?php
$rsBizConfig = $DB->GetRs("biz_config", 'BaoZhengJin');                        
echo $rsBizConfig['BaoZhengJin'];
?>
                    </div>
<?php
if ($rsBiz['is_agree'] == 0) {
?>                    
                    <div class="btn-ipt"><input type="button" value="我已阅读协议并同意"></div>
<?php
}
?>                    
                </div>
            </form>
        </section>
    </section>
</section>
</div>
<?php
if ($rsBiz['is_agree'] == 0) {
?> 
<script type="text/javascript">
$(function(){
    $(".btn-ipt input").click(function() {
        $.post('?act=xieyi',{inajax:1, do:'agree'}, function(json){
            $(".btn-ipt").remove();
        },'json')
    })
    
})
</script>
<?php
}
?>  
</body>
</html>
