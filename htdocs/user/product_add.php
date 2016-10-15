<?php
require_once "/config.inc.php";
//require_once(CMS_ROOT . '/include/api/product.class.php');
require_once(CMS_ROOT . '/include/api/b2cshopconfig.class.php');
//require_once(CMS_ROOT . '/include/api/product_category.class.php');

//检查用户是否登录
if(empty($BizAccount)){
    header("location:/user/login.php");
}
//检查用户是否已经交过费用
$users = b2cshopconfig::getConfig(array('Users_Account' => $BizAccount));
if ($users['errorCode'] == 0) {
    $bizData = $users['configData'];
    if ($bizData['expiresTime'] != 0 && $bizData['expiresTime'] < time()) {
        if ($bizData['need_charg'] == 1) {
            echo '<script language="javascript">alert("此项功能已到期,必须先交费才可以使用");history.back();</script>';
            exit();
        }
    }
} else {
    echo '<script language="javascript">alert("服务器网络异常,数据通信失败");history.back();</script>';
    exit;
}

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>发布产品</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<!-- <link href="../static/user/css/layer.css" type="text/css" rel="stylesheet"> -->
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<script  type="text/javascript"  src="../static/user/js/jquery.uploadView.js"></script>
<script  type="text/javascript"  src="../static/user/js/jquery.uploadView1.js"></script>
<script  type="text/javascript"  src="../static/user/js/product.js"></script>
<style type="text/css">
    .btn-upload {width: 43px;height: 43px;position: relative;float:left; border:1px #999 dashed; margin-left: 10px; }
    .btn-upload a {display: block;width: 43px;line-height: 43px;text-align: center;color: #4c4c4c;background: #fff;}
    .btn-upload input {width: 43px;height: 43px;position: absolute;left: 0px;top: 0px;z-index: -1;filter: alpha(opacity=0);-moz-opacity: 0;opacity: 0;cursor: pointer;}
    .deleted{cursor: pointer;width: 45px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
    .deleted1{cursor: pointer;width: 45px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
    .notNull { color: red; }
</style>
<script type="text/javascript">
    $(function(){
        mui.init({
            keyEventBind: {
                backbutton: true  //打开back按键监听
            }
        });
        var first=null;
        mui.back=function(){
            if(!first){
                first=new Date().getTime();
                mui.toast('再按一次退出系统!');

                setTimeout(function(){
                    first=null;
                },2000);
            }else{
                if(new Date().getTime()-first<2000){
                    plus.runtime.quit();
                }
            }
        };
    });
</script>
<body>
<div class="w">
    <div class="back_x">
        <a class="l" href="?act=store">&nbsp;取消</a><h3>发布产品</h3>
    </div>
    <input type="hidden" name="Products_Id" value="">
    <div class="name_pro">
        <input type="text" name="Products_Name" value="" placeholder="请输入商品名称">
        <div class="img_add">
            <div class="js_uploadBox">
                <div class="js_showBox"></div>
                <div class="btn-upload">
                    <a href="javascript:void(0);" id="add_img">+</a>
                    <input class="js_upFile" type="file" name="cover">
                </div>
                <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                <input type="hidden" id="image_files" value="">
                <input type="hidden" name="image_path" value=""/>
            </div>
        </div>
        <div class="note">商品封面（最少一张，最多三张）</div>
    </div>
    <div class="name_pro">
        <!-- <textarea name="BriefDescription" style="margin-left: 2%; width: 95%;height: 100px;line-height: 25px;border: none;" placeholder="请输入商品简介"></textarea> -->
        <textarea name="Description" style="margin-left: 2%; width: 95%;height: 100px;line-height: 20px;border: none;" placeholder="请输入商品详细介绍"></textarea>
        <div class="img_add">
            <div class="js_uploadBox1">
                <div class="js_showBox1"></div>
                <div class="btn-upload">
                    <a href="javascript:void(0);" id="add_img1">+</a>
                    <input class="js_upFile1" type="file" name="cover">
                </div>
                <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                <input type="hidden" id="image_files1" value="">
                <input type="hidden" name="image_path1" value=""/>
            </div>
        </div>
        <!--<div class="img_add">
            <!--这里写配图的一些代码--
        </div>
        <p>配图（最多6张）</p>-->
    </div>
    <div class="list_table">
        <table width="96%" class="table_x">
            <tr>
                <th><span class="notNull">*</span>原价(￥)：</th>
                <td><input type="number" name="PriceY" class="user_input" value="" placeholder="请输入商品原价"></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>现价(￥)：</th>
                <td><input type="number" name="PriceX" class="user_input" value="" placeholder="请输入商品现价"></td>
            </tr>
            <tr>
                <th>是否推荐　<br/>到批发商城：</th>
                <td><input class="toggle-switch" type="checkbox" name="is_Tj"></td>
            </tr>
            <tr class="is_Tj" style="display:none;">
                <th><span class="notNull">*</span>供货价(￥)：</th>
                <td><input type="number" name="PriceS" class="user_input" value="" placeholder="请输入商品供货价"></td>
            </tr>
            <tr class="is_Tj" style="display:none;">
                <th><span class="notNull">*</span>所属分类：</th>
                <td id="test1"><span id="b2c_category" firstCate="" secondCate=""></span><span class="right">选择分类&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>产品利润：</th>
                <td><input type="number" name="Products_Profit" class="user_input" value="" placeholder="佣金将按照产品利润发放" /></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>购买送积分：</th>
                <td><input type="number" name="Products_Integration" class="user_input" value="" placeholder="请输入送积分数" /></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>产品重量：</th>
                <td><input type="number" name="Products_Weight" class="user_input" value="" placeholder="产品重量,单位为kg" /> </td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>库存(件)：</th>
                <td><input type="number" name="count" class="user_input" value="" placeholder="请输入商品库存"></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>选择运费：</th>
                <td><input type="radio" value="1" name="freeshipping" checked="checked" />&nbsp;免运费 &nbsp;&nbsp;<input type="radio" value="0" name="freeshipping"/>&nbsp;运费模板 </td>
            </tr>
            <tr style="display: none;">
                <th>产品类型：</th>
                <td>
                    <select>
                        <option value="">01</option>
                        <option value="">02</option>
                    </select>
                </td>
            </tr>
            <tr style="display: none;">
                <th>产品属性：</th>
                <td></td>
            </tr>
            <tr>
                <th>佣金设置：</th>
                <td><span class="right" id="set_commission">设置佣金&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>所属分类：</th>
                <td id="test2"><span id="category" firstCate="" secondCate=""></span><span class="right">选择分类&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th>是否置顶：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsHot" checked=""></td>
            </tr>
            <tr>
                <th>是否推荐：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsRecommend" checked=""></td>
            </tr>
            <tr>
                <th>是否新品：</th>
                <td><input class="toggle-switch" type="checkbox" name="IsNew" checked=""></td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    <div class="kb"></div>
    <div class="bottom">
        <div class="pro_foot">
            <a>上架产品</a>
        </div>
    </div>
</div>
<?php
if ($bizData['seller_verify'] == 0) {
?>
<script language="javascript">
$(function(){
    $('input[name="is_Tj"]').change(function(){
        if ($('input[name="is_Tj"]:checked').val() == 'on') {
            var me = $(this);
            layer.open({
                content: "对不起,您尚未进行商家认证,请认证后再进行此项操作",
                btn: '我知道了',
                yes: function(){
                    me.prop("checked", false);
                    $('.is_Tj').attr('style', 'display:none;');
                    layer.closeAll();
                    return false;
                }
            });
        }
    })
})
</script>
<?php
}
?>
</body>
</html>
