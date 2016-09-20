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
<body>
<div class="w">
    <div class="back_x">
        <a class="l">&nbsp;取消</a><h3>发布产品</h3>
    </div>
    <div class="name_pro">
        <input type="text" value="" placeholder="请输入商品名称">
        <div class="img_add">
            <a href="#">+</a>
        </div>
        <p>商品封面（最少一张，最多三张）</p>
    </div>
    <div class="name_pro">
        <input type="text" value="" placeholder="请输入商品描述">
        <div class="img_add">
            <a href="#">+</a>
        </div>
        <p>配图（最多10张）</p>
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
                <td><i class="fa  fa-toggle-off fa-x" aria-hidden="true" style="font-size:20px; float:right"></i></td>
                <td style="display:none"><i class="fa  fa-toggle-on fa-x" aria-hidden="true" style="font-size:20px; float:right; color:#ff5500"></i></td>
            </tr>
            <tr>
                <th>是否推荐：</th>
                <td><i class="fa  fa-toggle-off fa-x" aria-hidden="true" style="font-size:20px; float:right"></i></td>
                <td style="display:none"><i class="fa  fa-toggle-on fa-x" aria-hidden="true" style="font-size:20px; float:right; color:#ff5500"></i></td>
            </tr>
            <tr>
                <th>是否新品：</th>
                <td><i class="fa  fa-toggle-off fa-x" aria-hidden="true" style="font-size:20px; float:right"></i></td>
                <td style="display:none"><i class="fa  fa-toggle-on fa-x" aria-hidden="true" style="font-size:20px; float:right; color:#ff5500"></i></td>
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
