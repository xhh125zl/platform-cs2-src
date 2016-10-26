/**
 * Created by Administrator on 2016/9/13 0013.
 */
$(function(){
    $("#addCate").click(function(){
        var index = layer.open({
            title: [
                '新建二级分类',
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
            var catename = $.trim($("#Category_Name").val());
            if (catename.length == 0) {
                layer.open({
                    content:"分类名称不能为空!",
                })
            }else{
                $.ajax({
                    type:"POST",
                    url:"/user/lib/category.php?action=addCate",
                    data:{"Category_Name":catename,"firstCateID":$("input[name=firstCateID]").val()},
                    dataType:"json",
                    success:function(data) {
                        layer.open({
                            content:data.msg,
                            time:2,
                            end:function(){
                                location.reload();
                            }
                        })
                    }
                })
            }
        });
    });


    //监听回车键,用于添加分类
    $('html').bind('keydown',function(e){
        if(e.keyCode==13){
            $(".button_x1").click();
        }
    });

    //删除分类
    $(".fa-trash-o").click(function(){
        var me = $(this);
        var tr = me.parent().parent('li');
        layer.open({
            type:0,
            title:[
                '是否删除?',
                'text-align:left;'
            ],
            content:"确认删除次分类吗?",
            btn:['确认', '取消'],
            yes:function(){
                $.ajax({
                    type:"POST",
                    url:"/user/lib/category.php?action=delCate",
                    data:{"Cate_ID":me.attr("del_id")},
                    dataType:"json",
                    success:function(data){
                        if (data.errorCode == 0) {
                            layer.open({
                                content:data.msg,
                                time:2,
                                end:function(){
                                    tr.remove();
                                }
                            })
                        }else{
                            layer.open({
                                content:data.msg,
                                time:2,
                            })
                        }
                    }
                })
            }
        });
    });


    //编辑分类
    $(".fa-check-square-o").click(function(){
        var me = $(this);
        var index = layer.open({
            title: [
                '编辑二级分类',
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
            var catename = $.trim($("#Category_Name").val());
            if (catename.length == 0) {
                layer.open({
                    content:"分类名称不能为空!",
                })
            }else{
                $.ajax({
                    type:"POST",
                    url:"/user/lib/category.php?action=updateCate",
                    data:{"Category_Name":catename,"Cate_ID":me.attr('cateID')},
                    dataType:"json",
                    success:function(data) {
                        layer.open({
                            content:data.msg,
                            time:2,
                            end:function(){
                                location.reload();
                            }
                        })
                    }
                })
            }
        });
    });
})