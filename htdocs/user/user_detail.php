<?
require_once "lib/user.php";
$From_arr = ['微信','注册','PC', 'QQ'];
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>会员详情</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
<body>
<div class="w">
    <div class="back_x">
        <a class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>会员管理
    </div>
    <div class="clear"></div>
    <div class="list_table">
        <table width="96%" class="table_x">
            <tr>
                <th>头像：</th>
                <td><img src="<?=strlen($rsUser['User_HeadImg']) > 0 ? $rsUser['User_HeadImg'] : '/static/api/images/user/face.jpg'?>" width="40" height="40"></td>
            </tr>
            <tr>
                <th>来源：</th>
                <td><input type="text" class="user_input" value="<?=$From_arr[$rsUser['User_From']]?>" readonly ></td>
            </tr>
            <tr>
                <th>手机号：</th>
                <td><input type="text" class="user_input" name="User_Mobile" value="<?=$rsUser['User_Mobile']?>"></td>
            </tr>
            <tr>
                <th>姓名：</th>
                <td><input type="text" class="user_input" name="User_Name" value="<?=$rsUser['User_Name']?>"></td>
            </tr>
            <tr>
                <th>总消费额：</th>
                <td><input type="text" class="user_input" value="<?=$rsUser['User_Cost']?>" readonly ></td>
            </tr>
            <tr>
                <th>会员卡号：</th>
                <td><input type="text" class="user_input" value="<?=$rsUser['User_No']?>" readonly ></td>
            </tr>
            <tr>
                <th>积分：</th>
                <td><input type="text" class="user_input" name="User_Integral" value="<?=$rsUser['User_Integral']?>"></td>
            </tr>
            <tr>
                <th>余额：</th>
                <td><input type="text" class="user_input" name="User_Money" value="<?=$rsUser['User_Money']?>"></td>
            </tr>
            <tr>
                <th>注册时间：</th>
                <td><input type="text" class="user_input" value="<?=date("Y-m-d H:i:s",$rsUser['User_CreateTime'])?>" readonly></td>
            </tr>
            <tr style="display: none;">
                <th>修改密码：</th>
                <td><input type="text" class="user_input" value=""></td>
            </tr>
        </table>
        <input type="button" value="保存" class="up_xx" style="display: none;">
    </div>
</div>
</body>
</html>