<?php
require_once "/config.inc.php";
//require_once(CMS_ROOT . '/include/api/product.class.php');
require_once(CMS_ROOT . '/include/api/b2cshopconfig.class.php');
//require_once(CMS_ROOT . '/include/api/product_category.class.php');

//检查用户是否登录
if(empty($BizAccount)){
    header("location:/biz/login.php");
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
}else{
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
<script  type="text/javascript"  src="../static/user/js/product.js"></script>
<style type="text/css">
    .btn-upload {width: 43px;height: 43px;position: relative; float: left; border:1px #999 dashed; margin-left: 10px; }
    .btn-upload a {display: block;width: 43px;line-height: 43px;text-align: center;color: #4c4c4c;background: #fff;}
    .btn-upload input {width: 43px;height: 43px;position: absolute;left: 0px;top: 0px;z-index: -1;filter: alpha(opacity=0);-moz-opacity: 0;opacity: 0;cursor: pointer;}
    .deleted{cursor: pointer;width: 45px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
    .notNull { color: red; }
</style>
<script type="text/javascript">
    $(function(){
        //上架商品  提交数据 并验证
        $('.pro_foot').on('click',function(){
            var productData = {
                'Products_Name' : $.trim($('input[name="Products_Name"]').val()),      //商品名称
                'Products_JSON' : $.trim($('input[name="image_path"]').val()),      //商品封面图片的路径集
                'Products_BriefDescription' : $.trim($('textarea[name="BriefDescription"]').val()),     //商品描述
                'Products_PriceY' : $.trim($('input[name="PriceY"]').val()),      //商品原价
                'Products_PriceX' : $.trim($('input[name="PriceX"]').val()),      //商品现价
                'is_Tj' : $('input[name="is_Tj"]:checked').val() == 'on' ? 1 : 0,    //是否推荐到批发商城
                'Products_PriceS' : $.trim($('input[name="PriceS"]').val()),      //商品供货价   推荐到批发商城后
                //'b2c_firstCate' : $('#b2c_category').attr('firstCate'),      //批发商城商品所属分类  一级
                'B2CProducts_Category' : $('#b2c_category').attr('secondCate'),      //批发商城商品所属分类  二级
                'Products_Profit' : $.trim($('input[name="Products_Profit"]').val()),  //商品的利润
                'Products_Integration' : $.trim($('input[name="Products_Integration"]').val()),  //商品送积分
                'Products_Weight' : $.trim($('input[name="Products_Weight"]').val()),  //商品重量
                'Products_Count' : $.trim($('input[name="count"]').val()),      //商品库存
                'Products_IsShippingFree' : $('input[name="freeshipping"]:checked').val(),    //运费选择
                'firstCate' : $('#category').attr('firstCate'),      //商品所属分类  一级
                'secondCate' : $('#category').attr('secondCate'),      //商品所属分类  二级
                'Products_IsHot' : $('input[name="IsHot"]:checked').val() == 'on' ? 1 : 0,    //是否置顶
                'Products_IsRecommend' : $('input[name="IsRecommend"]:checked').val() == 'on' ? 1 : 0,    //是否推荐
                'Products_IsNew' : $('input[name="IsNew"]:checked').val() == 'on' ? 1 : 0    //是否为新品
            };
            //商品名称非空判断
            if (!check_null($('input[name="Products_Name"]'))) {
                return false;
            }
            //判断是否有图片
            if(($('.js_showBox div').length < 1) || (productData.Products_JSON == '')) {
                $('#add_img').attr('style', 'border: 1px solid red;');
                return false;
            } else {
                $('#add_img').removeAttr('style');
            }
            //商品描述非空判断
            if (!check_null($('textarea[name="BriefDescription"]'))) {
                return  false;
            }
            //原价
            if (!check_null($('input[name="PriceY"]')) || !check_number($('input[name="PriceY"]'))) {
                return false;
            }
            //现价
            if (!check_null($('input[name="PriceX"]')) || !check_number($('input[name="PriceX"]'))) {
                return false;
            }
            //选择推荐  判断填写供货价
            var PriceS = $('input[name="PriceS"]');
            if (productData.is_Tj == 1) {
                if (!check_null($('input[name="PriceS"]')) || !check_number($('input[name="PriceS"]'))) {
                    return false;
                }
                //判断是否选择b2c平台分类
                if (productData.B2CProducts_Category == '') {
                    $('#test1').attr('style', 'border:1px solid red;');
                    return false;
                } else {
                    $('#test1').removeAttr('style');
                }
            } else {
                PriceS.attr('style', '');
                productData.Products_PriceS = 0;
            }
            //产品利润
            if (!check_null($('input[name="Products_Profit"]')) || !check_number($('input[name="Products_Profit"]'))) {
                return false;
            }
            //产品送积分
            if (!check_null($('input[name="Products_Integration"]')) || !check_number($('input[name="Products_Integration"]'), 1)) {
                return false;
            }
            //产品重量
            if (!check_null($('input[name="Products_Weight"]')) || !check_number($('input[name="Products_Weight"]'))) {
                return false;
            }
            //库存
            if (!check_null($('input[name="count"]')) || !check_number($('input[name="count"]'), 1)) {
                return false;
            }
            //判断是否选择分类
            if(productData.firstCate == '' || productData.secondCate == '') {
                $('#test2').attr('style', 'border:1px solid red;');
                return false;
            } else {
                $('#test2').removeAttr('style');
            }
            //数据提交
            $.ajax({
                type:"POST",
                url:"lib/upload.php",
                data:{"act":"addProduct", "productData":productData},
                dataType:"json",
                success:function(data) {
                    if (data.errorCode == 0) {
                        layer.open({
                           content:data.msg,
                        });
                    } else {
                        layer.open({
                           content:data.msg,
                        });
                    }
                }
            });
        });
        $(document).keydown(function(e){
            if (e.keyCode == 13) {
                $('.pro_foot').click();
            }
        });
    });
</script>
<body>
<div class="w">
    <div class="back_x">
        <a class="l">&nbsp;取消</a><h3>发布产品</h3>
    </div>
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
        <p>商品封面（最少一张，最多三张）</p>
    </div>
    <div class="name_pro">
        <textarea name="BriefDescription" style="width: 100%;height: 100px;line-height: 25px;border: none;" placeholder="请输入商品描述信息"></textarea>
        <!--<div class="img_add">
            <!--这里写配图的一些代码--
        </div>
        <p>配图（最多6张）</p>-->
    </div>
    <div class="list_table">
        <table width="96%" class="table_x">
            <tr>
                <th><span class="notNull">*</span>原价（￥）：</th>
                <td><input type="number" name="PriceY" class="user_input" value="" placeholder="请输入商品原价"></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>现价（￥）：</th>
                <td><input type="number" name="PriceX" class="user_input" value="" placeholder="请输入商品现价"></td>
            </tr>
            <tr>
                <th>是否推荐&nbsp;&nbsp;&nbsp;<br/>到批发商城：</th>
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
                <th><span class="notNull">*</span>库存（件）：</th>
                <td><input type="number" name="count" class="user_input" value="" placeholder="请输入商品库存"></td>
            </tr>
            <tr>
                <th><span class="notNull">*</span>选择运费：</th>
                <td><input type="radio" value="1" name="freeshipping" checked="checked" />&nbsp;&nbsp;免运费 &nbsp;&nbsp;<input type="radio" value="2" name="freeshipping"/>&nbsp;&nbsp;运费模板 </td>
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
<script>
    $(function(){
        $("#set_commission").click(function(){
            layer.open({
                type:0,
                title:"提示信息",
                content:"请到PC端操作!手机端不支持设置佣金,将按照默认佣金比例!",
                btn:['确认'],
            })
        });
        //点击推荐到批发商城选择分类进行的操作
        $("#test1").click(function(){
            var me = $(this);
            layer.open({
                type:1,
                content:"<div class=\"select_containers\">请选择一级分类:<select name=\"b2c_firstCate\" class=\"select\" id=\"b2c_firstCate\"><option value=\"0\">请选择顶级分类</option></select><br/>请选择二级分类:</div>",
                title:[
                    '<span style="float:left">请选择要添加到的分类</span><span style="float: right"><a href="javascript:void(0);">新增分类</a></span>',
                    'background-color:#f0f0f0;font-weight:bold;'
                ],
                style: 'width:100%;position:fixed;bottom:0;left:0;border-radius:8px;',
                btn:['确定','重选'],
                shadeClose:false,
                yes:function(index){
                    if ($("#b2c_secondCate").length > 0) {
                        var b2c_firstCate = $("#b2c_firstCate").val();
                        var b2c_secondCate = $("#b2c_secondCate").val();
                        var b2c_firstCate_name = $("#b2c_firstCate").find('option:selected').text();
                        var b2c_secondCate_name = $("#b2c_secondCate").find('option:selected').text();
                        $('#b2c_category').attr('firstCate', b2c_firstCate);
                        $('#b2c_category').attr('secondCate', b2c_secondCate);
                        $('#b2c_category').html(b2c_firstCate_name+'，'+b2c_secondCate_name);
                        layer.close(index);
                    }else{
                        layer.open({
                            type:0,
                            title:"提示信息",
                            content:"商品应放在二级分类下,如您对应的上级分类还没有二级分类,请点击添加分类按钮进行分类添加操作!",
                            btn:['添加分类','取消'],
                            yes:function(){
                                location.reload();
                            }
                        });
                    }
                }
            });
            //分类联动菜单第一级
            $.ajax({
                type:"get",
                url:"/user/lib/category.php",
                data:{"action":"fB2cCate"},
                dataType:'json',
                success:function(data){
                    $.each(data,function(i,n){
                        var option="<option value='"+ n.Category_ID+"'>"+ n.Category_Name+"</option>";
                        $("#b2c_firstCate").append(option);
                    })
                }
            });
        });

        //分类联动菜单第二级
        $("#b2c_firstCate").live('change',function(){
            var me = $(this);
            $.getJSON("/user/lib/category.php",{"action":"sB2cCate","fB2cCateID":me.val()},function(data){
                if(data){
                    if($("#b2c_secondCate").length<=0){
                        var sel="<select name=\"b2c_secondCate\" class=\"select\" id=\"b2c_secondCate\"></select>"
                        $(".select_containers").append(sel);
                    }
                    $("#b2c_secondCate").empty();
                    $.each(data, function(i, n){
                        var option="<option value='"+ n.Category_ID+"'>"+n.Category_Name+"</option>";
                        $("#b2c_secondCate").append(option);
                    });
                }else{
                    if($("#b2c_secondCate").length>0){
                        $("#b2c_secondCate").remove();
                    }
                }
            });
        });

        //点击分类（自营）按钮进行的操作
        $("#test2").click(function(){
            var me = $(this);
            layer.open({
                type:1,
                content:"<div class=\"select_containers\">请选择一级分类:<select name=\"firstCate\" class=\"select\" id=\"firstCate\"><option value=\"0\">请选择顶级分类</option></select><br/>请选择二级分类:</div>",
                title:[
                    '<span style="float:left">请选择要添加到的分类</span><span style="float: right"><a href="javascript:void(0);">新增分类</a></span>',
                    'background-color:#f0f0f0;font-weight:bold;'
                ],
                style: 'width:100%;position:fixed;bottom:0;left:0;border-radius:8px;',
                btn:['确定','重选'],
                shadeClose:false,
                yes:function(index){
                    if ($("#secondCate").length > 0) {
                        var firstCate = $("#firstCate").val();
                        var secondCate = $("#secondCate").val();
                        var firstCate_name = $("#firstCate").find('option:selected').text();
                        var secondCate_name = $("#secondCate").find('option:selected').text();
                        $('#category').attr('firstCate', firstCate);
                        $('#category').attr('secondCate', secondCate);
                        $('#category').html(firstCate_name+'，'+secondCate_name);
                        layer.close(index);
                    }else{
                        layer.open({
                            type:0,
                            title:"提示信息",
                            content:"商品应放在二级分类下,如您对应的上级分类还没有二级分类,请点击添加分类按钮进行分类添加操作!",
                            btn:['添加分类','取消'],
                            yes:function(){
                                location.reload();
                            }
                        });
                    }
                }
            });
            //分类联动菜单第一级
            $.ajax({
                type:"get",
                url:"/user/lib/category.php",
                data:{"action":"fCate"},
                dataType:'json',
                success:function(data){
                    $.each(data,function(i,n){
                        var option="<option value='"+ n.Category_ID+"'>"+ n.Category_Name+"</option>";
                        $("#firstCate").append(option);
                    })
                }
            });
        });

        //分类联动菜单第二级
        $("#firstCate").live('change',function(){
            var me = $(this);
            $.getJSON("/user/lib/category.php",{"action":"sCate","fcateID":me.val()},function(data){
                if(data){
                    if($("#secondCate").length<=0){
                        var sel="<select name=\"secondCate\" class=\"select\" id=\"secondCate\"></select>"
                        $(".select_containers").append(sel);
                    }
                    $("#secondCate").empty();
                    $.each(data, function(i, n){
                        var option="<option value='"+ n.Category_ID+"'>"+n.Category_Name+"</option>";
                        $("#secondCate").append(option);
                    });
                }else{
                    if($("#secondCate").length>0){
                        $("#secondCate").remove();
                    }
                }
            });
        });
    })

</script>

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

</body>
</html>
