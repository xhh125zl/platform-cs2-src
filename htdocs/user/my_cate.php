<?php
require_once CMS_ROOT . '/include/api/product_category.class.php';
require_once CMS_ROOT . '/include/helper/tools.php';

//获取商家自己的分类
$res = product_category::getDev401firstCate($BizAccount);
$Category = [];
if (isset($res['errorCode']) && $res['errorCode'] == 0) {
    foreach ($res['cateData'] as $k => $v) {
        $result = product_category::getDev401SecondCate(['Biz_Account' => $BizAccount, 'firstCateID' => $v['Category_ID']]);
        if (isset($result['errorCode']) && $result['errorCode'] == 0 && isset($result['cateData']) && count($result['cateData']) > 0) {
            $res['cateData'][$k]['child'] = $result['cateData'];
        }
    }
    $Category = $res['cateData'];
}

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>我的分类</title>
</head>
    <link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
    <link href="../static/user/css/layer.css" type="text/css" rel="stylesheet">
    <link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="../static/user/js/layer.js"></script>
<body>
<div class="w">
    <div class="back_x">
        <a class="l" href='?act=store'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>分类管理
        <a class="r addCate" title="新建一级分类" cateId="" style="color:#ff5500">添加分类</a>
    </div>
    <div class="clear"></div>
    <div class="cate_list">
        <ul>
            <?php if (!empty($Category)) { foreach($Category as $k => $v) { ?>
            <li>
                <span class="left title_x firstCate" cateId="<?php echo $v['Category_ID']; ?>" value="">
                    <h1>
                        <?php if (isset($v['child']) && count($v['child']) > 0) {echo '<i class="fa fa-plus-square fa-x" aria-hidden="true"></i>';} else {echo '<span style="display:inline-block; width:22px;"></span>'; } ?>
                        <a href="javascript:;"><?php echo $v['Category_Name']; ?></a>
                    </h1>
                </span>
                <span class="right edit_x">
                    <i class="fa fa-plus-square fa-x addCate" aria-hidden="true" title="新建二级分类" cateId="<?php echo $v['Category_ID']; ?>"></i>
                    <i class="fa fa-pencil fa-x editCate" aria-hidden="true" title="编辑一级分类" cateName="<?php echo $v['Category_Name']; ?>" cateId="<?php echo $v['Category_ID']; ?>"></i>
                	<i class="fa fa-trash-o fa-x delCate" aria-hidden="true" title="删除一级分类" cateId="<?php echo $v['Category_ID']; ?>"></i>
                </span>
                <div class="clear"></div>
            </li>
                <?php if (isset($v['child']) && !empty($v['child'])) { foreach ($v['child'] as $kk => $vv) { ?>
                <li class="secondCate secondCate_<?php echo $v['Category_ID']; ?>" style="display:none;">
                    <span class="left title_x">
                        <h1 style="margin-left: 27px;"><a href="javascript:;"><?php echo $vv['Category_Name']; ?></a></h1>
                    </span>
                    <span class="right edit_x">
                        <i class="fa fa-pencil fa-x editCate" aria-hidden="true" title="编辑二级分类" cateName="<?php echo $vv['Category_Name']; ?>" cateId="<?php echo $vv['Category_ID']; ?>"></i>
                        <i class="fa fa-trash-o fa-x delCate" aria-hidden="true" title="删除二级分类" cateId="<?php echo $vv['Category_ID']; ?>"></i>
                    </span>
                    <div class="clear"></div>
                </li>
                <?php }} ?>
            <?php }} ?>
        </ul>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>
<script type="text/javascript">
    $(function(){
        //编辑分类
        $('#editCate').click(function(){
            var value = $(this).attr('value');
            if (value == 0) {
                $('.edit_x').removeAttr('style');
                $(this).attr('value', '1');
                $(this).html('取消编辑');
            } else {
                $('.edit_x').attr('style', 'display:none;');
                $(this).attr('value', '0');
                $(this).html('编辑分类');
            }
        });

        //显示子分类
        $(document).on('click', '.firstCate', function(){
            var firstCateId = $(this).attr('cateId');
            var secondCate_style = $(this).attr('value');
            if (secondCate_style == '') {
                $('.secondCate').slideUp(300);  //收起全部
                $('.secondCate_'+firstCateId).slideDown(300);   //展开点击项
                $(this).attr('value', '1');
            } else {
                $('.secondCate_'+firstCateId).slideUp(300);   //展开点击项
                $(this).attr('value', '');
            }
        });

        //添加分类  一级、二级  通过有无cateId判断
        $(document).on('click', '.addCate' , function(){
            var me = $(this);
            var index = layer.open({
                title: [
                    me.attr('title'),
                    'background-color: #ff5500; color:#fff;line-height:40px;height:40px'
                ],
                content: '<div class="new_cate"><input type="text" maxlength="30" name="Category_Name" id="Category_Name" /><div class="but_x"><span class="left"><button class="button_x">取消</button></span><span class="left"><button class="button_x1">确定</button></span></div></div>'
            });
            //取消按钮
            $(".button_x").on('click',function(){
                layer.close(index);
            });
            //确定按钮
            $(".button_x1").on('click', function() {
                var newCateName = $.trim($("#Category_Name").val());
                if (newCateName.length == 0) {
                    layer.open({
                        content:"分类名称不能为空!",
                    });
                } else {
                    $.ajax({
                        type:"POST",
                        url:"lib/category.php?action=addCate",
                        data:{"Category_Name":newCateName, "firstCateID":me.attr('cateId')},
                        dataType:"json",
                        success:function(data) {
                            layer.open({
                                content:data.msg,
                                time:1,
                                end:function(){
                                    location.reload();
                                }
                            });
                        }
                    });
                }
            });
        });

        //编辑分类
        $(document).on('click', '.editCate', function(){
            var me = $(this);
            var index = layer.open({
                title: [
                    me.attr('title'),
                    'background-color: #ff5500; color:#fff;line-height:40px;height:40px'
                ],
                content: '<div class="new_cate"><input type="text" name="Category_Name" id="Category_Name" /><div class="but_x"><span class="left"><button class="edit_button_x">取消</button></span><span class="left"><button class="edit_button_x1">确定</button></span></div></div>'
            });
            $("#Category_Name").val(me.attr('cateName'));

            //取消按钮
            $(".edit_button_x").on('click',function(){
                layer.close(index);
            });
            //确定按钮
            $(".edit_button_x1").on('click', function() {
                var newCateName = $.trim($("#Category_Name").val());
                if (newCateName.length == 0) {
                    layer.open({
                        content:"分类名称不能为空!",
                    });
                } else {
                    $.ajax({
                        type:"POST",
                        url:"lib/category.php?action=updateCate",
                        data:{"Category_Name":newCateName,"Cate_ID":me.attr('cateId')},
                        dataType:"json",
                        success:function(data) {
                            if (data.errorCode == 0) {
                                layer.open({
                                    content:data.msg,
                                    time:1,
                                    end:function(){
                                        me.parent().parent('li').find('a').html(newCateName);
                                        me.attr('cateName', newCateName);
                                    }
                                });
                            } else {
                                layer.open({
                                    content:data.msg,
                                    btn: '确认'
                                });
                            }
                        }
                    });
                }
            });
        });

        //删除分类
        $(document).on('click', '.delCate', function(){
            var me = $(this);
            layer.open({
                type:0,
                content:"确认删除此分类吗?",
                btn:['确认', '取消'],
                shadeClose: false,
                yes:function(){
                    layer.closeAll();
                    layer.open({type: 2, shadeClose: false});
                    $.ajax({
                        type:"POST",
                        url:"lib/category.php?action=delCate",
                        data:{"Cate_ID":me.attr('cateId')},
                        dataType:"json",
                        success:function(data){
                            layer.closeAll();
                            if (data.errorCode == 0) {
                                layer.open({
                                    content:data.msg,
                                    time:1,
                                    end:function(){
                                        me.parent().parent('li').remove();
                                    }
                                });
                            } else {
                                layer.open({
                                    content:data.msg,
                                    btn: '确认'
                                });
                            }
                        }
                    });
                }
            });
        });

        //监听回车键,用于添加分类
        $('html').bind('keydown',function(e){
            if(e.keyCode==13){
                $(".button_x1").click();
            }
        });
    });
</script>