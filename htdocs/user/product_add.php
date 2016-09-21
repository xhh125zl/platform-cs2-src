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
<link href="../static/user/css/layer.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../static/user/js/layer.js"></script>
<script  type="text/javascript"  src="../static/user/js/jquery.uploadView.js"></script>
<style type="text/css">
    .btn-upload {width: 43px;height: 43px;position: relative; float: left; border:1px #999 dashed; margin-left: 10px; }
    .btn-upload a {display: block;width: 43px;line-height: 43px;text-align: center;color: #4c4c4c;background: #fff;}
    .btn-upload input {width: 43px;height: 43px;position: absolute;left: 0px;top: 0px;z-index: 1;filter: alpha(opacity=0);-moz-opacity: 0;opacity: 0;cursor: pointer;}
    .deleted{cursor: pointer;width: 4px;display: block;height: 20px;line-height: 20px;text-align: center;position: relative;background: #000;color: #fff;font-size: 12px;filter: alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;margin-top: -20px;}
</style>
<script type="text/javascript">
    $(function(){
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
                            if ($("input[name=image_path]").val().length == 0) {
                                $("input[name=image_path]").val(data.msg);
                            } else {
                                $("input[name=image_path]").val($("input[name=image_path]").val() + ',' + data.msg);
                            }
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            }
        });
        $(document).on('click', '.deleted', function(){
            var me = $(this);
            if( confirm('确定删除吗?') ){
                $.ajax({
                    type:"POST",
                    url:"lib/upload.php",
                    data:{"act":"delImg", "index":$(this).parent().index(),"image_path":$("input[name=image_path]").val()},
                    dataType:"json",
                    success:function(data) {
                        if (data.errorCode == 0) {
                            $("input[name=image_path]").val(data.msg);
                            me.parent().remove();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            }
        });
    })
</script>
<body>
<div class="w">
    <div class="back_x">
        <a class="l">&nbsp;取消</a><h3>发布产品</h3>
    </div>
    <div class="name_pro">
        <input type="text" value="" placeholder="请输入商品名称">
        <div class="img_add">
            <div class="js_uploadBox">
                <div class="js_showBox"></div>
                <div class="btn-upload">
                    <a href="javascript:void(0);">+</a>
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
        <textarea style="width: 100%;height: 100px;line-height: 25px;border: none" placeholder="请输入商品描述信息"></textarea>
        <!--<div class="img_add">
            <!--这里写配图的一些代码--
        </div>
        <p>配图（最多6张）</p>-->
    </div>
    <div class="list_table">
        <table width="96%" class="table_x">
            <tr>
                <th>价格（￥）：</th>
                <td><input type="text" class="user_input" value="" placeholder="请输入商品价格"></td>
            </tr>
            <tr>
                <th>库存（件）：</th>
                <td><input type="text" class="user_input" value="" placeholder="请输入商品库存"></td>
            </tr>
            <tr>
                <th>产品利润：</th>
                <td><input type="text" class="user_input" value="" placeholder="佣金将按照产品利润发放" /></td>
            </tr>
            <tr>
                <th>产品重量：</th>
                <td><input type="text" class="user_input" value="" placeholder="产品重量,单位为kg" /> </td>
            </tr>
            <tr>
                <th>选择运费：</th>
                <td><input type="radio" value="1" name="freeshipping" />&nbsp;&nbsp;免运费 &nbsp;&nbsp;<input type="radio" value="2" name="freeshipping"/>&nbsp;&nbsp;运费模板 </td>
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
                <th>所属分类：</th>
                <td id="test2"><span class="right">选择分类&nbsp;<i class="fa  fa-angle-right fa-x" aria-hidden="true" style="font-size:20px;"></i></span></td>
            </tr>
            <tr>
                <th>是否置顶：</th>
                <td><input class="toggle-switch" type="checkbox" checked=""></td>
            </tr>
            <tr>
                <th>是否推荐：</th>
                <td><input class="toggle-switch" type="checkbox" checked=""></td>
            </tr>
            <tr>
                <th>是否新品：</th>
                <td><input class="toggle-switch" type="checkbox" checked=""></td>
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
    $("#test2").click(function(){
        var url = $("#test2");
        var index = layer.open({
            title: [
                '选择分类(方便买家筛选)',
                'background-color: #ddd; color:#666;line-height:40px;height:40px;text-align:left'
            ]
            ,
            content: '<div class="new_cate"><div class="but_lab"><ul><li><label><input name="Cate_ID" type="radio" value="1" />衣服 </label></li></ul></div><div class="but_x"><span class="left"><button class="button_x">取消</button></span><span class="left"><button class="button_x1">确定</button></span></div></div>'
        });
        $(".button_x").click(function(){
            layer.close(index);
        });
        $(".button_x1").click(function(){
            var cateValue = $("input[name=Cate_ID]");
            layer.open({
               content:cateValue.val(),
            });
        })
    });

    $(function(){
        $("#set_commission").click(function(){
            layer.open({
                type:0,
                title:"提示信息",
                content:"请到PC端操作!手机端不支持设置佣金,将按照默认佣金比例!",
                btn:['确认'],
            })
        })
    })

</script>
</body>
</html>
