<?php
if (!defined('USER_PATH')) exit();
require_once "config.inc.php";
require_once CMS_ROOT . '/include/api/shopconfig.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';

$inajax = isset($_GET['inajax']) ? (int)$_GET['inajax'] : 0;

if ($inajax == 1) {
    $do = isset($_GET['do']) ? $_GET['do'] : '';

    if ($do == 'wechat') {
        $Users_WechatAccount = isset($_POST['Users_WechatAccount']) ? $_POST['Users_WechatAccount'] : 0;

        $data = [
            'Biz_Account' => $BizAccount,
            'usersData' => [
                'Users_WechatAccount' => $Users_WechatAccount,
            ],
        ];
        
        $result = shopconfig::updatecolumn($data);

        echo json_encode($result);
    }

    exit();
}else if (isset($_POST['do']) && $_POST['do'] == 'uploadFile') {
    $imagepath = trim($_POST['data']);
    $url = IMG_SERVER."user/lib/upload.php";
	$result = curlInterFace($url,"post",[
        'data' => $imagepath,
        'act' => 'uploadFile',
        'Users_Account' => $BizAccount,
        'filepath' => '../../uploadfiles',
    ]);

    if($result['errorCode']===0){
        $data = [
            'Biz_Account' => $BizAccount,
            'configData' => [
                'ShareLogo' =>$result['msg']
            ]
        ];

        $result = shopconfig::updatecolumn($data);
        echo json_encode($result);
        exit;
    }
    
    echo json_encode($result);exit;
}else if (isset($_POST['do']) && $_POST['do'] == 'save') {
    $data = [
            'Biz_Account' => $BizAccount,
            'configData' => [
                'ShareIntro' => addslashes($_POST['ShareIntro'])
            ]
        ];
        
    $result = shopconfig::updatecolumn($data);
    echo json_encode($result);
    exit;
}


//获取配置信息
$data = [
    'Biz_Account' => $BizAccount,
];

$result = shopconfig::getConfig($data);
$config = $result['data'];

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>店铺分享</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../static/user/js/jquery.uploadView.js"></script>
<script type="text/javascript" src="../static/js/plugin/layer_mobile/layer.js"></script>
<style type="text/css">
    .btn-upload {width: 43px;height: 43px;position: relative; float: left; border:1px #999 dashed; margin-left: 10px; }
    .btn-upload a {display: block;width: 43px;line-height: 43px;text-align: center;color: #4c4c4c;background: #fff;}
    .btn-upload input {width: 43px;height: 43px;position: absolute;left: 0px;top: 0px;z-index: 1;filter: alpha(opacity=0);-moz-opacity: 0;opacity: 0;cursor: pointer;}
    .deleted{cursor: pointer;width: 4px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
</style>
<body>
<div class="w">
	<div class="back_x">
    	<a href="?act=setting" class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>店铺分享
    </div>
    <div class="blank10"></div>
	<div class="pic_add">
        <form method="post" action="?act=setting_share" id="settingShare">
    	<div class="ccc js_uploadBox">
        	<img src="<?=$config['ShareLogo']?IMG_SERVER.$config['ShareLogo']:'' ?>" class="showimg">
            <span>修改图片</span>

            <input type="file" class="js_upFile" style="position:absolute; top:180px; left:0; height:34px; filter:alpha(opacity:0);opacity: 0;width:100%; cursor:pointer;" name="upthumb" />
            <input type="hidden" id="image_files" name="image_files" value="">
            <input type="hidden" name="ShareLogo" value="">
            <input type="hidden" id="Users_WechatAccount" name="Users_WechatAccount" value="">
        </div>
		<div class="shop_share">
        	<textarea name="ShareIntro" rows="3"><?=$config['ShareIntro']?$config['ShareIntro']:'请输入店铺分享语' ?></textarea>
        </div>
        <div class="sub_setting">
            <input  type="button" name="save" class="" value="保存">
        </div>
        <input type="hidden" name="do" value="save"/>
        </form> 
	</div>
</div>
<script type="text/javascript">
$(function(){
    $(".btnsubmit").click(function(){
        var Users_WechatAccount = $("#Users_WechatAccount").val();
        $.post("?act=setting_wechat&inajax=1&do=wechat", {Users_WechatAccount:Users_WechatAccount}, function(json){
            if(json.errorCode == '0') {
                layer.open({content:json.msg, time:2, end:function() {
                     location.href="admin.php?act=setting";
                }});
            }
        },'json')
    });
    $("input[name='save']").click(function(){
        $.ajax({
            type:"POST",
                url:$("#settingShare").attr("action"),
                data:$("#settingShare").serialize(),
                dataType:"json",
                success:function(data){
                    if (data.errorCode == 0) {
                         layer.open({
                            content: data.msg
                            ,btn: '我知道了'
                        });
                    } else {
                        layer.open({
                            content: data.msg
                            ,btn: '我知道了'
                        });
                    }
                }
        });
    });

    $(".js_upFile").uploadView({
            uploadBox: '.js_uploadBox',//设置上传框容器
            showBox : '.js_showBox',//设置显示预览图片的容器
            width : 220, //预览图片的宽度，单位px
            height : 120, //预览图片的高度，单位px
            allowType: ["gif", "jpeg", "jpg", "bmp", "png"], //允许上传图片的类型
            maxSize :10, //允许上传图片的最大尺寸，单位M
            success:function(e){
                $(".showimg").attr("src",$("#image_files").val());
                $.ajax({
                    type:"POST",
                    url:"?act=setting_share",
                    data:{"do":"uploadFile", "data":$("#image_files").val()},
                    dataType:"json",
                    success:function(data){
                        if (data.errorCode == 0) {
                             $("input[name=ShareLogo]").val(data.msg);
                        } else {
                            layer.open({
                                content: data.msg
                                ,btn: '我知道了'
                            });
                        }
                    }
                });
            }
        });
})
</script>

</body>
</html>
