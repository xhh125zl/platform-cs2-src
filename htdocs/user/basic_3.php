<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>账户信息</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>账户信息
    </div>
    <div class="clear"></div>
    <div class="list_table">
    	<table width="96%" class="table_x"> 
            <tr> 
                <th>提现方式：</th> 
                <td>
                	<select name="accountdata[withdraw_type]" onChange="paychange()"  >
                    	<option>请选择</option>
                        <option value="1" >银行卡</option>
                        <option value="2" >支付宝</option>
                    </select>
                </td> 
            </tr>
            <tr> 
                <th>开户城市：</th> 
                <td><input type="text" class="user_input" placeholder="" name="accountdata[blan_city]"></td> 
            </tr>
            <tr> 
                <th>开户银行：</th> 
                <td><input type="text" class="user_input" placeholder="" name="accountdata[blan_name]"></td> 
            </tr>
            <tr> 
                <th>开户姓名：</th> 
                <td><input type="text" class="user_input" placeholder="" name="accountdata[blan_realname]"></td> 
            </tr>
            <tr> 
                <th>银行卡号：</th> 
                <td><input type="text" class="user_input" placeholder="" name="accountdata[blan_card]"></td> 
            </tr>
        </table> 
        <div style="text-align:center">
        	<a><button class="next_yy">返回</button></a>
            <a><button class="back_xx">下一步</button></a>
    	</div>
    </div>
</div>
</body>
</html>
