/**
 * Created by Administrator on 2016/9/10.
 */
$(function() {
	/*$("#Users_PayCharge").click(function() {
        $(this).unbind("click");
        $(this).html('<input type="text" class="input" name="Users_PayCharge" maxlength="30" placeholder="请填写收费金额,鼠标点击其他地方会自动保存" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')"  />');
        $("input[name=Users_PayCharge]").focus();
    });*/
    $("input[name=Users_PayCharge]").live("change",function(){
        var Users_PayCharge = $(this).val();//alert(Users_PayCharge);return false;
        if (Users_PayCharge.length == 0) {
            //这里可以执行一些长度为空的时候的判断和逻辑
        }else{
            $.ajax({
                type:"post",
                url:"/include/library/CompanyB2c/Action.php",
                data:{"action":"updateUsersPayCharge", "Users_PayCharge":Users_PayCharge},
                success:function(data) {
                    var data = eval('(' + data + ')');;
                    var indexloader=layer.msg("提交中...",{icon:16,time:500},function() {
                        if (data.status == 1) {
                            layer.msg("更新成功", {icon:6, time:500}, function(){
                                layer.close(indexloader);
                                /*$("#Users_PayCharge").html('<p style="padding-left:30px;">&yen;'+data.Users_PayCharge+'元</p>');
                                $("#Users_PayCharge").bind("click", function() {
                                    $(this).unbind("click");
                                    $(this).html('<input type="text" class="input" name="Users_PayCharge"	 maxlength="30" placeholder="请填写收费金额,鼠标点击其他地方会自动保存" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')"  />');
                                    $("input[name=Users_PayCharge]").focus();
                                });*/
                            });
                        }else{
                            layer.msg("更新失败,请联系管理员", {icon:5, time:2000}, function(){
                                layer.close(indexloader);
                                /*$("#Users_PayCharge").html('<p style="padding-left:30px;">&yen;'+data.Users_PayCharge+'元</p>');
                                $("#Users_PayCharge").bind("click", function() {
                                    $(this).unbind("click");
                                    $(this).html('<input type="text" class="input" name="Users_PayCharge"	 maxlength="30" placeholder="请填写收费金额,鼠标点击其他地方会自动保存" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')"  />');
                                    $("input[name=Users_PayCharge]").focus();
                                });*/
                            });
                        }
                    });
                }
            })
        }
    })
})