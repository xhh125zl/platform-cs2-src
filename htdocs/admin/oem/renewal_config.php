<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}

if($_POST){

	$Data=array(
		"sys_price"=>json_encode($_POST['Price'],JSON_UNESCAPED_UNICODE),
	);
	
	$DB->Set("setting",$Data,"where id=1");
	echo '<script language="javascript">';
	echo 'alert("设置成功！");';
	echo '	window.open("renewal_config.php","_self");';
	echo '</script>';
	exit();
}

$setting = $DB->GetRs("setting","sys_price","where id=1");
$price_list = json_decode($setting['sys_price'],true);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$("#renewal_price_form").submit(function(){
		if (global_obj.check_form($('*[notnull]'))) {
				return false;
			};
		});
		
	});

</script>

<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
		<ul>
       
        <li><a href="renewal_record.php">续费记录</a></li>
         <li class="cur"><a href="renewal_config.php">费用设置</a></li>
      </ul>
	</div>
    <div class="r_con_wrap">
		<form class="r_con_form"  id="renewal_price_form" method="post" action="?">
        	<div class="rows">
                <label>续费价格</label>
                <span class="input">
                 <table id="wholesale_price_list" class="item_data_table" border="0" cellpadding="3" cellspacing="0">
            <tbody><tr>
            
            </tr>
                        <tr>
              <td>年限:1年 &nbsp;&nbsp;价格：￥
                <input name="Price[1]" notnull value="<?=$price_list[1]?>" class="form_input" size="5" maxlength="10" type="text">
             </td>
            </tr>
                        <tr>
              <td>年限:2年 &nbsp;&nbsp; 价格：￥
                <input name="Price[2]" notnull value="<?=$price_list[2]?>" class="form_input" size="5" maxlength="10" type="text">
             </td>
            </tr>
                        <tr>
              <td> 年限:3年 &nbsp;&nbsp;价格：￥
                <input name="Price[3]" notnull value="<?=$price_list[3]?>" class="form_input" size="5" maxlength="10" type="text">
               </td>
            </tr>
                      </tbody></table>
                </span>
                <div class="clear"></div>
            </div>
            
       
            <div class="rows">
                <label></label>
                <span class="input"><input type="submit" name="Submit" value="确定" class="submit">
                  <input type="reset" value="重置"></span>
                <div class="clear"></div>
            </div>
          
        </form>
     </div>
  </div>
</div>
</body>
</html>