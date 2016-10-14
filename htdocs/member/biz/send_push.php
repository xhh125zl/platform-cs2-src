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
<body>
<form id="all" method="post" onsubmit="return sendPushall()">
    <p><label>消息内容:</label></p>
    <p><textarea rows="3" name="n_content" id="n_content" cols="105" class="n_content" placeholder="请填写要推送的消息,长度不得超过255个字符"></textarea><p>
    <p><input type="submit" class="send_btn" value="Send" onclick=""/></p>
</form>
<div class="info"></div>
</body>
</html>
