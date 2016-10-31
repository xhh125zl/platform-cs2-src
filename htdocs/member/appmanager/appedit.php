<?php
if(empty($_SESSION["Users_Account"]))
{
    header("location:/member/login.php");
    exit;
}
if (!isset($_GET['id'])) {
    echo "缺少必要的参数";
    exit;
}
if ($_POST) {
    if ($_GET['act'] == 'update') {
        $data = [
            'update_intro' => isset($_POST['updateIntro']) ? htmlspecialchars($_POST['updateIntro']) : 0,
        ];
        if ($DB->Set('app_info', $data, "where id = " . (int)$_POST['id'])) {
            echo json_encode(['errorCode' => 0, 'msg' => '更新成功']);
            exit;
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '更新失败']);
            exit;
        }
    }
    echo json_encode(['errorCode' => 1, 'msg' => '更新失败']);
    exit;
}
$info = $DB->GetRs('app_info', '*', "where id = " . (int)$_GET['id']);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>软件在线升级</title>
    <script type="text/javascript" src="../../static/user/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $(".up_xx").click(function(){
                alert(1);
                update_intro = $("#update_intro").val();
                id = $("#id").val();
                if (update_intro.length == 0) {
                    alert('请填写更新内容!');
                } else {
                    $.ajax({
                        type:"post",
                        url:"?act=update&id=" + id,
                        data:{"id":id ,"updateIntro":update_intro},
                        dataType:'json',
                        success:function(data){
                            if (data.errorCode == 0) {
                                alert(data.msg);
                                location.href='appmanage.php';
                            } else {
                                alert(data.msg);
                            }
                        }
                    });
                }
            })
        });
    </script>
</head>
<style>
    *{margin:0px; padding:0px;}
    ul, li, dt, dd, ol, dl{list-style-type:none;}
    a{color:#666; text-decoration:none;}
    input, textarea, button, a{ -webkit-tap-highlight-color:rgba(0,0,0,0); }
    .clear{clear:both;}
    body{background-color:#f8f8f8;-webkit-tap-highlight-color:transparent;font-size:14px; font-family:"微软雅黑"}
    .w{width:100%; margin:0 auto;}
    input[type=button], input[type=submit], input[type=file], button { cursor: pointer; -webkit-appearance: none;vertical-align:middle}
    .width_x{text-align: left;margin: 0 auto; margin-top:20px}
    .width_x textarea{ color:#717171; font-weight:normal; line-height:35px;padding:5px; width:45%; outline:none; background:none;border: 1px #cfcfcf solid;margin-left: 50px;}
    .header_x{ background:#fff; color:#717171;font-weight: 700;border-bottom: 1px solid #cfcfcf;height: 50px; line-height:50px;padding: 0 15px;}
    .line{position:relative; margin:40px 50px; color:#717171; height:28px; line-height:28px;}
    .line span{float:left}
    .up_xx{background:#1fbba6;color:#fff; border:none;padding:8px 15px;  border-radius:3px; margin-left:50px; margin-top:20px;}
    .up_xx:hover{background:#26cbb5;}
    .file {position: relative;display: inline-block;background: #1fbba6;border: 1px solid #99D3F5;border-radius: 4px;padding: 4px 12px;color: #ffffff;line-height: 20px;overflow: hidden;}
    .file input {position: absolute;font-size: 100px; right: 0; top: 0; opacity: 0;}
    .uploadSuccess{color:#1fbba6;font-weight:bold;}
    .up_table{ text-align:center; width:45.7%; margin:20px 50px; overflow:hidden; border:1px #ddd solid; border-collapse: collapse; background:#f8f8f8}
    .up_table tr td{border:1px #ddd solid; height:35px; line-height:45px; padding:0 5px; color:#666}
    .up_table tr td.name_xx{ width:130px}
</style>
<body>
<div class="w">
    <header class="header_x">在线升级软件</header>
    <div class="clear"></div>
    <table class="up_table">
        <tr>
            <td class="name_xx">下载文件名</td>
            <td class="right_xx" id="download_Name"><?=$info['file_name']?></td>
        </tr>
        <tr>
            <td class="name_xx">包名</td>
            <td class="right_xx" id="package_Name"><?=$info['package_name']?></td>
        </tr>
        <tr>
            <td class="name_xx">版本号</td>
            <td class="right_xx" id="version_Code"><?=$info['version_code']?></td>
        </tr>
        <tr>
            <td class="name_xx">版本名称</td>
            <td class="right_xx" id="version_Name"><?=$info['version_name']?></td>
        </tr>
    </table>

    <div class="width_x">
        <input type="hidden" id="id" value="<?=$info['id']?>"/>
        <textarea name="update_intro" id="update_intro" rows="10" placeholder="软件更新介绍"><?=$info['update_intro']?></textarea>
    </div>
    <input type="button" value="点击提交" class="up_xx">
</div>
</body>
</html>
