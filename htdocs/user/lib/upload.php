<?php

if ($_POST['act'] == 'uploadFile') {
    $imagepath = trim($_POST['data']);
    



} elseif ($_POST['act'] == 'delImg') {
    $imagepath = htmlspecialchars(trim($_POST['image_path']));
    $index = (int)$_POST['index'];
    echo delImg($imagepath, $index);
}