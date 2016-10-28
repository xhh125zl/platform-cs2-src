<?php
require_once "config.inc.php";
require_once(CMS_ROOT . '/include/api/count.class.php');

if ($_POST) {
    echo '<pre>';
    print_r($_POST);
    DIE();
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>资质认证</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>资质认证
    </div>
    <div class="clear"></div>
    <div class="list_table">
    	<table width="96%" class="table_x"> 
            <tr> 
                <th style="text-align:center">企业类型：</th> 
                <td>
                	<select name="authdata[company_type]">
                    	<option>请选择</option>
                        <option value="1" >有限责任公司</option>
                        <option value="2" >农民专业合作社</option>
                        <option value="3" >中外合资企业</option>
                        <option value="4" >外国或港澳台地区独资企业</option>
                    </select>
                </td> 
            </tr>
            <tr> 
                <th style="text-align:center">企业住所：</th> 
                <td><input type="text" class="user_input" placeholder="需与营业执照上保持一致" name="authdata[compay_add]"></td> 
            </tr>
            <tr> 
                <th style="text-align:center">注册资金：</th> 
                <td>
                    <input type="text" class="user_input" placeholder="" name="authdata[compay_reg_money]">
                </td>
            </tr>
            <tr> 
                <th style="text-align:center">企业法人：</th> 
                <td><input type="text" class="user_input" placeholder="" name="authdata[compay_user]"></td> 
            </tr>          
        </table>
        <table class="img_tab">
       		<tr> 
                <th>营业执照注册号或统一社会信用代码：</th> 
            </tr>
            <tr> 
                <td><input type="text" class="user_input" style="width:96%;"  name="authdata[compay_license]"></td> 
            </tr>
        	<tr> 
                <th>法人身份证扫描件：<input type="hidden" id="compay_shenfenimgPath" value="<?php echo !empty($authinfo['compay_shenfenimg'])?$authinfo['compay_shenfenimg']:''?>" data-validate="required:法人身份证扫描件必须填写" name="authdata[compay_shenfenimg]" /></th> 
            </tr>
            <tr> 
                <th><input type="file"></th>
            </tr>
            <tr> 
                <th>营业执照影印件：<input type="hidden" id="compay_licenseimgPath" data-validate="required:营业执照影印件必须填写" name="authdata[compay_licenseimg]" value="<?php echo !empty($authinfo['compay_licenseimg'])?$authinfo['compay_licenseimg']:''?>" /></th> 
            </tr>
            <tr> 
                <th><input type="file"></th>
            </tr>
            <tr> 
                <th>税务登记证扫描件：<input type="hidden" id="compay_shuiwuimgPath" data-validate="required:税务登记证扫描件必须填写" name="authdata[compay_shuiwuimg]" value="<?php echo !empty($authinfo['compay_shuiwuimg'])?$authinfo['compay_shuiwuimg']:''?>" /></th> 
            </tr>
            <tr> 
                <th><input type="file"></th>
            </tr>
        </table> 

<!-- 个人 -->
<table class="table_x">
                <tr>
                    <th>真实姓名：</th> 
                    <td>
                        <input type="text" class="user_input" placeholder="" name="authdata[per_realname]"">
                    </td>
                </tr>
                <th>身份证号码：</th> 
                <td>
                    <input type="text" class="user_input" placeholder="" name="authdata[per_shenfenid]">
                </td>
                </tr>
            <tr> 
                <th>身份证扫描件：<input type="hidden" id="per_shenfenimgPath" data-validate="required:身份证扫描件必须填写" name="authdata[per_shenfenimg]" value="<?php echo !empty($authinfo['per_shenfenimg'])?$authinfo['per_shenfenimg']:''?>" /></th> 
                <td>
                    <input type="file">
                </td>
            </tr>
            </table>

    </div>
    <div style="text-align:center">
        	<a><button class="next_yy">返回</button></a>
            <a><button class="back_xx">下一步</button></a>
    </div>
</div>
</body>
</html>
