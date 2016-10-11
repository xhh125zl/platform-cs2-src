<?php
require_once "/config.inc.php";
require_once(CMS_ROOT . '/include/api/count.class.php');

//$BizInfo = $DB->GetRs('biz_apply', "*", "WHERE Users_ID='" . $UsersID . "' AND Biz_ID=" . $BizID);
//var_dump($BizInfo);

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>基本信息</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<script>
 function checktype(the) {
    if (the.value == 1){
        $('.company').show();
    } else {
        $('.company').hide();
    }
}
</script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>基本信息
    </div>
<div class="clear"></div>
<form id="form1" name="form1" method="post" action="?act=basic_2">    
    <div class="list_table">
    	<table width="96%" class="table_x"> 
            <tr> 
                <th>认证类型：</th> 
                <td><label class="radio-inline"> <input type="radio" checked name="authtype" onclick="checktype(this)" value="1"/> 企业认证 </label>
<label class="radio-inline"> <input type="radio" name="authtype" onclick="checktype(this)" value="2" /> 个人认证 </label></td> 
            </tr>
            <tr class="company"> 
                <th>公司名称：</th> 
                <td><input type="text" name="basedata[company_name]" class="user_input" placeholder="公司名称与营业执照上一致"></td> 
            </tr>
            <tr class="company"> 
                <th>公司主体：</th> 
                <td>
                	<select name="basedata[main_type]" >
                    	<option>请选择</option>
                        <option>大陆企业</option>
                    </select>
                </td> 
            </tr>
            <tr class="company"> 
                <th>公司固话：</th> 
                <td><input type="text" name="basedata[tel][]" class="user_input" placeholder="格式如：021-12345678"></td> 
            </tr>
            <tr class="company"> 
                <th>企业所在地：</th> 
                <td>
                	<select name="basedata[city][]" id="loc_province" >
                        <option>选择省份</option>
                    </select>
                    <select name="basedata[city][]" id="loc_city">
                        <option>选择城市</option>
                    </select>
                    <select name="basedata[city][]" id="loc_town">
                        <option>选择区县</option>
                    </select>
                    <input type="text" name="basedata[address]" class="user_input" placeholder="具体地址（与营业执照上一致）">
                </td>
            </tr>
            <tr> 
                <th>主营商品：</th> 
                <td><input type="text" class="user_input" name="basedata[goods]" placeholder="多个主营商品以逗号间隔"></td> 
            </tr>
            <tr> 
                <th>联系人：</th> 
                <td><input type="text" class="user_input" placeholder="" name="basedata[contacts]"></td> 
            </tr>
            <tr> 
                <th>手机：</th> 
                <td><input type="text" class="user_input" placeholder="" name="basedata[mobile]"></td>
            </tr>
            <tr> 
                <th>邮箱地址：</th> 
                <td><input type="text" class="user_input" placeholder="" name="basedata[email]"></td>
            </tr>
        </table> 
        <div style="text-align:center">
            <a href="javascript:form1.submit()"><button class="back_xx">下一步</button></a>
    	</div>
</form>        
    </div>
</div>
</body>
</html>
