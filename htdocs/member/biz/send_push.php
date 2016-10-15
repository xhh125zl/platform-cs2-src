<?
if(empty($_SESSION["Users_Account"]))
{
    header("location:/member/login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="/static/user/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

        });
        function sendPushall(){
            var data = $('#n_content').val();
            $('form#all').unbind('submit');

            $.ajax({
                url: "send.php?action=send",
                type: 'POST',
                data: {content:data},
                beforeSend: function() {

                    html = "<b>请等待，正在发送中……</b>";
                    $('.info').html(html);

                },
                success: function(data) {
                    // $('.txt_message').val("");
                    $('.info').html(data);
                }
            });
            return false;
        }

    </script>
</head>
<body style="font-family:'微软雅黑'">
<form id="all" method="post" onsubmit="return sendPushall()">
    <div style=" width:94%;margin:20px auto;overflow:hidden;">
        <p style=" font-size:18px; color:#333; line-height:30px;"><label>消息内容：</label></p>
        <p><textarea rows="3" name="n_content" id="n_content" cols="105" class="n_content" placeholder="请填写要推送的消息,长度不得超过255个字符" style=" font-size:14px; color:#666; line-height:25px; height:200px; padding:5px; outline:none"></textarea><p>
        <p><input type="submit" class="send_btn" value="开始推送" onclick="" style=" padding: 5px 15px;text-align:center; color:#666"/></p>
    </div>
</form>
<div class="info"></div>
</body>
</html>
