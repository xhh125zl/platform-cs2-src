/**
 * 发布产品
 * 编辑产品
 */

//检测输入框是否为空
function check_null(input) {
    var self_attr = input.attr('style');
    var add_attr = ";border:1px solid red;";
    if($.trim(input.val()) == '') {
        input.attr('style', self_attr+add_attr);
        input.focus();
        return false;
    } else {
        if(self_attr) {
            self_attr = self_attr.replace(add_attr, '');
        }
        input.attr('style', self_attr);
        return true;
    }
}
//检测是数字类型  默认检查正浮点数  1：非负浮点数  2：检查正整数 3: 检查非负整数
function check_number(input, type) {
    if(!type) {type = 0;}
    var self_attr = input.attr('style');
    var value = $.trim(input.val());
    var add_attr = ";border:1px solid blue;";
    var number_regular = '';
    if (type == 0) {
        number_regular = Boolean( value > 0 );
    } else if (type == 1) {
        number_regular = Boolean( value >= 0 );
    } else if (type == 2) {
        number_regular = Boolean( value > 0 && value%1 == 0 );
    } else if (type == 3) {
        number_regular = Boolean( value >= 0 && value%1 == 0 );
    }
    if(value == '' || !number_regular) {
        input.attr('style', self_attr+add_attr);
        input.focus();
        return false;
    } else {
        if(self_attr) {
            self_attr = self_attr.replace(add_attr, '');
        }
        input.attr('style', self_attr);
        return true;
    }
}

$(function(){
    //判断图片上传的张数
    $('#add_img').click(function(){
        if($('.js_showBox div').length > 2) {
            layer.open({
                content: '最多只能上传三张图片',
            });
            return false;
        } else {
            return $('.js_upFile').click();
        }
    });
    //图片上传
    $(".js_upFile").uploadView({
        uploadBox: '.js_uploadBox',//设置上传框容器
        showBox : '.js_showBox',//设置显示预览图片的容器
        width : 43, //预览图片的宽度，单位px
        height : 43, //预览图片的高度，单位px
        allowType: ["gif", "jpeg", "jpg", "bmp", "png"], //允许上传图片的类型
        maxSize :10, //允许上传图片的最大尺寸，单位M
        success:function(e){
            $.ajax({
                type:"POST",
                url:"lib/upload.php",
                data:{"act":"uploadFile", "data":$("#image_files").val()},
                dataType:"json",
                success:function(data){
                    if (data.errorCode == 0) {
                        $('#add_img').removeAttr('style');
                        if ($("input[name=image_path]").val().length == 0) {
                            $("input[name=image_path]").val(data.msg);
                        } else {
                            $("input[name=image_path]").val($("input[name=image_path]").val() + ',' + data.msg);
                        }
                    } else {
                        layer.open({content: data.msg, shadeClose: false, btn: '确定'});
                    }
                }
            });
        }
    });
    //删除图片
    $(document).on('click', '.deleted', function(){
        var me = $(this);
        layer.open({
            content: '确定删除吗?',
            shadeClose: false,
            btn: ['确定', '取消'],
            yes: function(){
                layer.closeAll();
                $.ajax({
                    type:"POST",
                    url:"lib/upload.php",
                    data:{"act":"delImg", "index":me.parent().index(),"image_path":$("input[name=image_path]").val()},
                    dataType:"json",
                    success:function(data) {
                        if (data.errorCode == 0) {
                            $("input[name=image_path]").val(data.msg);
                            me.parent().remove();
                        } else {
                            layer.open({content: data.msg, shadeClose: false, btn: '确定'});
                        }
                    }
                });
            }
        });
    });

    //内容配图
    //判断图片上传的张数
    $('#add_img1').click(function(){
        if($('.js_showBox1 div').length > 6) {
            layer.open({
                content: '最多只能上传7张图片',
            });
            return false;
        } else {
            return $('.js_upFile1').click();
        }
    });
    //图片上传
    $(".js_upFile1").uploadView1({
        uploadBox: '.js_uploadBox1',//设置上传框容器
        showBox : '.js_showBox1',//设置显示预览图片的容器
        width : 43, //预览图片的宽度，单位px
        height : 43, //预览图片的高度，单位px
        allowType: ["gif", "jpeg", "jpg", "bmp", "png"], //允许上传图片的类型
        maxSize :10, //允许上传图片的最大尺寸，单位M
        success:function(e){
            $.ajax({
                type:"POST",
                url:"lib/upload.php",
                data:{"act":"uploadFile", "data":$("#image_files1").val()},
                dataType:"json",
                success:function(data){
                    if (data.errorCode == 0) {
                        $('#add_img1').removeAttr('style');
                        if ($("input[name=image_path1]").val().length == 0) {
                            $("input[name=image_path1]").val(data.msg);
                        } else {
                            $("input[name=image_path1]").val($("input[name=image_path1]").val() + ',' + data.msg);
                        }
                    } else {
                        layer.open({content: data.msg, shadeClose: false, btn: '确定'});
                    }
                }
            });
        }
    });
    //删除图片
    $(document).on('click', '.deleted1', function(){
        var me = $(this);
        layer.open({
            content: '确定删除吗?',
            shadeClose: false,
            btn: ['确定', '取消'],
            yes: function(){
                layer.closeAll();
                $.ajax({
                    type:"POST",
                    url:"lib/upload.php",
                    data:{"act":"delImg", "index":me.parent().index(),"image_path":$("input[name=image_path1]").val()},
                    dataType:"json",
                    success:function(data) {
                        if (data.errorCode == 0) {
                            $("input[name=image_path1]").val(data.msg);
                            me.parent().remove();
                        } else {
                            layer.open({content: data.msg, shadeClose: false, btn: '确定'});
                        }
                    }
                });
            }
        });
    });
    //内容配图 end

    //是否推荐 推荐填写供货价
    $('input[name="is_Tj"]').click(function(){
        var isSolding = $('input[name="isSolding"]').val();
        var is_Tj = $('input[name="is_Tj"]:checked').val();
        if(is_Tj == 'on') {
            $('.is_Tj').attr('style', 'display:table-row;');
        } else {
            if (isSolding == 1) {
                $('input[name="is_Tj"]').prop('checked', true);
                layer.open({content: '此推荐商品有未完成订单，不允许撤销推荐', time: 1});
                return;
            }
            $('.is_Tj').attr('style', 'display:none;');
            $('input[name="PriceS"]').attr('value', '');
            $('#b2c_category').attr({'firstCate': '', 'secondCate': ''});
            $('#b2c_category').html('');
        }
    });

    //
    $("#set_commission").click(function(){
        layer.open({
            type:0,
            title:"提示信息",
            content:"请到PC端操作!<br/>手机端不支持设置佣金<br/>将按照默认佣金比例!",
            style: 'line-height:25px;',
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
                '<span style="float:left">请选择要添加到的分类</span>',
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
        $('.select_containers #b2c_secondCate').remove();
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
                '<span style="float:left">请选择要添加到的分类</span><span style="float: right"><a href="/user/admin.php?act=my_cate">分类管理</a></span>',
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
        $('.select_containers #secondCate').remove();
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
    
    //上架、编辑商品  提交数据 并验证
    $('.pro_foot').on('click',function(){
        var productData = {
            'Products_ID' : $.trim($('input[name="Products_ID"]').val()),      //商品id
            'Products_Name' : $.trim($('input[name="Products_Name"]').val()),      //商品名称
            'Products_JSON' : $.trim($('input[name="image_path"]').val()),      //商品封面图片的路径集
            //'Products_BriefDescription' : $.trim($('textarea[name="BriefDescription"]').val()),     //商品简介
            'Products_Description' : $.trim($('textarea[name="Description"]').val()),     //商品详情描述
            'Products_JSON1' : $.trim($('input[name="image_path1"]').val()),      //商品详情添加的图片
            'Products_PriceY' : $.trim($('input[name="PriceY"]').val()),      //商品原价
            'Products_PriceX' : $.trim($('input[name="PriceX"]').val()),      //商品现价
            'isSolding' : $('input[name="isSolding"]').val(),    //编辑时  判断此推荐商品是否有未完成订单， 1： 不允许撤销推荐
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
            'Products_IsNew' : $('input[name="IsNew"]:checked').val() == 'on' ? 1 : 0,    //是否为新品
            'Products_IsPaysBalance' : $('input[name="IsPaysBalance"]:checked').val() == 'on' ? 1 : 0,    //是否余额支付  特殊属性
            'Products_IsShow' : $('input[name="IsShow"]:checked').val() == 'on' ? 1 : 0    //是否显示    特殊属性
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
        /*if (!check_null($('textarea[name="BriefDescription"]'))) {
            return  false;
        }*/
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
            var PriceX = parseFloat(productData.Products_PriceX);   //现价
            var PriceS = parseFloat(productData.Products_PriceS);   //供货价
            if ((PriceX < PriceS) || (PriceX*0.7 > PriceS)) {    //供货价为现价的 70% ~ 100%
                $('input[name="PriceS"]').attr('style', 'border: 1px solid red;');
                layer.open({content: '供货价为现价的70% ~ 100%', shadeClose: false, btn: '确认'});
                return false;
            } else {
                $('input[name="PriceS"]').removeAttr('style');
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
        if (!check_null($('input[name="Products_Profit"]')) || !check_number($('input[name="Products_Profit"]'), 1)) {
            return false;
        }
        //产品送积分
        if (!check_null($('input[name="Products_Integration"]')) || !check_number($('input[name="Products_Integration"]'), 3)) {
            return false;
        }
        //产品重量
        if (!check_null($('input[name="Products_Weight"]')) || !check_number($('input[name="Products_Weight"]'))) {
            return false;
        }
        //库存
        if (!check_null($('input[name="count"]')) || !check_number($('input[name="count"]'), 2)) {
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
        layer.open({type: 2, shadeClose: false});
        $.ajax({
            type:"POST",
            url:"lib/products.php",
            data:{"act":"addEditProduct", "productData":productData},
            dataType:"json",
            success:function(data) {
                layer.closeAll();
                if (data.errorCode == 0) {
                    window.location.href = data.url;
                    layer.open({
                       content:data.msg,
                       style: 'border:none; background-color:green; color:#fff;',
                       time: 1
                    });
                    
                } else {
                    layer.open({
                       content:data.msg,
                       shadeClose: false,
                       btn: '确定'
                    });
                }
            }
        });
    });
    /*$(document).keydown(function(e){
        if (e.keyCode == 13) {
            $('.pro_foot').click();
        }
    });*/
});