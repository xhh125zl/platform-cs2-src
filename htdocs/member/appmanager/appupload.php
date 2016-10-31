<?php
if(empty($_SESSION["Users_Account"]))
{
    header("location:/member/login.php");
    exit;
}

if (isset($_GET['act']) && $_GET['act'] == 'upload') {
    $filename = date('YmdHis', time());
    $extension = pathinfo($_FILES['appApk']['name'])['extension'];
    if (!in_array($extension, ['apk'])) {
        echo json_encode(['errorCode' => 1, 'msg' => '只允许上传apk文件,请确认文件后缀名']);
        exit;
    } else {
        $dirpath = CMS_ROOT . '/data/app/';
        if (!file_exists($dirpath)) {
            mkdir($dirpath);
        }
        move_uploaded_file($_FILES['appApk']['tmp_name'], $dirpath . $filename . '.' . $extension);
        /*解析安卓apk包中的压缩XML文件，还原和读取XML内容
        依赖功能：需要PHP的ZIP包函数支持。*/
        require_once CMS_ROOT . '/include/api/Apkparser.class.php';

        $appObj  = new Apkparser();
        $targetFile = $dirpath . $filename . '.' . $extension;//apk所在的路径地址
        $res   = $appObj->open($targetFile);
        $packageName = $appObj->getPackage();    // 应用包名
        $versionName = $appObj->getVersionName();  // 版本名称
        $versionCode = $appObj->getVersionCode();  // 版本代码
        echo json_encode(['errorCode' => 0, 'versionCode' => $versionCode, 'versionName' => $versionName, 'packageName' => $packageName, 'msg' => '上传成功', 'filename' => $filename . '.' . $extension]);
        exit;
    }
}

if ($_POST) {
    if ($_GET['act'] == 'submit') {
        $data = [
            'package_name' => isset($_POST['packageName']) ? htmlspecialchars($_POST['packageName']) : 0,
            'version_name' => isset($_POST['versionName']) ? htmlspecialchars($_POST['versionName']) : 0,
            'version_code' => isset($_POST['versionCode']) ? htmlspecialchars($_POST['versionCode']) : 0,
            'file_name' => isset($_POST['downloadName']) ? htmlspecialchars($_POST['downloadName']) : 0,
            'update_intro' => isset($_POST['updateIntro']) ? htmlspecialchars($_POST['updateIntro']) : 0,
        ];
        if ($DB->Add('app_info', $data)) {
            echo json_encode(['errorCode' => 0, 'msg' => '提交成功']);
            exit;
        } else {
            echo json_encode(['errorCode' => 1, 'msg' => '提交失败']);
            exit;
        }
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>软件在线升级</title>
    <script type="text/javascript" src="../../static/user/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="../../static/user/js/ajaxfileupload.js"></script>
    <script type="text/javascript">
        $(function(){
            $(".up_table").hide();
            $("#appApk").change(function(){
                $.ajaxFileUpload({
                    url:'?act=upload', //你处理上传文件的服务端
                    secureuri:false,
                    fileElementId:'appApk',
                    dataType:'json',
                    success: function (data)
                    {
                        if (data.errorCode == 0) {
                            $(".up_table").show();
                            $("#package_Name").text(data.packageName);
                            $("#version_Name").text(data.versionName);
                            $("#version_Code").text(data.versionCode);
                            $("#download_Name").text(data.filename);
                            $("#uploadInfo").removeClass('file').addClass('uploadSuccess').html("已上传,请填写更新介绍");
                        }

                    },error:function(data) {
                        alert('上传失败');
                    }
                });
            });

            $(".up_xx").click(function(){
                download_Name = $("#download_Name").text();
                package_Name = $("#package_Name").text();
                version_Code = $("#version_Code").text();
                version_Name = $("#version_Name").text();
                update_intro = $("#update_intro").val();
                if (download_Name.length == 0 || package_Name.length == 0 || version_Code.length == 0 || version_Name.length == 0 || update_intro.length == 0) {
                    alert('请先上传文件,并填写更新内容!');
                } else {
                    $.ajax({
                        type:"post",
                        url:"?act=submit",
                        data:{"packageName":package_Name, "versionName":version_Name, "versionCode":version_Code, "downloadName":download_Name, "updateIntro":update_intro},
                        dataType:'json',
                        success:function(data){
                            if (data.errorCode == 0) {
                                alert(data.msg);
                                location.reload();
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
    <div class="line">
        <span>
            <label>上传文件：</label>
            <a href="javascript:;" class="file" id="uploadInfo">选择文件
                <input type="file" name="appApk" id="appApk">
            </a>
        </span>
    </div>
    <div class="clear"></div>
    <table class="up_table">
        <tr>
            <td class="name_xx">下载文件名</td>
            <td class="right_xx" id="download_Name"></td>
        </tr>
        <tr>
            <td class="name_xx">包名</td>
            <td class="right_xx" id="package_Name"></td>
        </tr>
        <tr>
            <td class="name_xx">版本号</td>
            <td class="right_xx" id="version_Code"></td>
        </tr>
        <tr>
            <td class="name_xx">版本名称</td>
            <td class="right_xx" id="version_Name"></td>
        </tr>
    </table>

    <div class="width_x">
        <textarea name="update_intro" id="update_intro" rows="10" placeholder="软件更新介绍"></textarea>
    </div>
    <input type="button" value="点击提交" class="up_xx">
</div>
</body>
</html>
