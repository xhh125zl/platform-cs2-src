<?php
function base64ToFilename($base64code, $filepath)
{
    $image = base64_decode(explode(',', $base64code)[1]);
    $type = explode(',', $base64code)[0];
    $ext = substr($type, strpos($type, '/') + 1, strrpos($type, ':') - strpos($type, '/') - 1);
    $filename = uniqid() . '.' . $ext;
    if (!is_dir($filepath)) {
        mkdir($filepath);
    }
    $fullname = $filepath . '/' . $filename;
    $ch = fopen($fullname, 'w+');
    $result = fwrite($ch, $image);
    fclose($ch);
    if ($result) {
        return json_encode(['errorCode' => 0, 'msg' => $fullname], JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(['errorCode' => 1, 'msg' => '上传失败'], JSON_UNESCAPED_UNICODE);
    }
}

function delImg($imagepath, $index)
{
    $imageArr = explode(',', $imagepath);
    $file = $imageArr[$index];
    $fileext = pathinfo($file)['extension'];
    $allowArr = ['jpg', 'png', 'jpeg', 'gif', 'bmp'];
    if (!in_array($fileext, $allowArr)) {
        return json_encode(['errorCode' => 2, 'msg' => '请勿非法操作!']);
    }
    $flag = unlink($file);
    if ($flag) {
        unset($imageArr[$index]);
        $imageStr = implode(',', $imageArr);
        return json_encode(['errorCode' => 0, 'msg' => $imageStr]);
    } else {
        return json_encode(['errorCode' => 1, 'msg' => '发生错误!']);
    }
}

if ($_POST['act'] == 'uploadFile') {
    $base64code = $_POST['data'];
    echo base64ToFilename($base64code, 'test');
} elseif ($_POST['act'] == 'delImg') {
    $imagepath = htmlspecialchars(trim($_POST['image_path']));
    $index = (int)$_POST['index'];
    echo delImg($imagepath, $index);
}