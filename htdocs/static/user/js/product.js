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
//检测是数字类型  默认检查正数  1：检查正整数
function check_number(input, type = 0) {
    var self_attr = input.attr('style');
    var value = $.trim(input.val());
    var add_attr = ";border:1px solid blue;";
    if (type == 0) {
        var regular = /^\d+(?=\.{0,1}\d+$|$)/;
    } else if (type == 1) {
        var regular = /^[0-9]*[1-9][0-9]*$/;
    }
    if(value == '' || value == 0 || !regular.test(value)) {
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
                        alert(data.msg);
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
                            alert(data.msg);
                        }
                    }
                });
            }
        });
    });
    //是否推荐 推荐填写供货价
    $('input[name="is_Tj"]').click(function(){
        var is_Tj = $('input[name="is_Tj"]:checked').val();
        if(is_Tj == 'on') {
            $('.is_Tj').attr('style', 'display:table-row;');
        } else {
            $('.is_Tj').attr('style', 'display:none;');
            $('input[name="PriceS"]').attr('value', '');
            $('#b2c_category').attr({'firstCate': '', 'secondCate': ''});
            $('#b2c_category').html('');
        }
    });
});