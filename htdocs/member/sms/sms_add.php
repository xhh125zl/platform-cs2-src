<?php


if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$DB->showErr=false;

$price = json_decode($setting["sms_price"],true);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<style type="text/css">#man_panel li.item{margin-right:5px; margin-top:5px;}</style>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<script type='text/javascript'>
function check(){
	if($("#num").val()==''){
		alert("请输入购买数量");
		$("#num").focus();
		return false;
	}
	return true;
}
</script>
</head>
<body>
<div id="iframe_page">
  <div class="iframe_content">
	<div class="r_nav">
	  <ul>
        <li class="cur"><a href="/member/sms/sms_add.php">购买短信</a></li>
        <li><a href="/member/sms/sms_record.php">购买记录</a></li>
		<li><a href="/member/sms/send_record.php">发送记录</a></li>	
      </ul>
	</div>
    <div class="r_con_wrap">
        <form class="r_con_form" method="post" action="/member/pay_sms.php" onsubmit="return check();">
        	<div class="rows">
                <label>购买数量</label>
                <span class="input"><input type="text" id="num" name="Qty" value="" size="15" class="form_input"  pattern="[0-9]*" /> 条</span>
                <div class="clear"></div>
            </div>            
            <div class="rows">
			  <label>短信价格</label>
			  <span class="input">
			   <table border="0" cellpadding="5" cellspacing="0" class="r_con_table" width="300">
				<thead>
				  <tr>
					<td width="200" nowrap="nowrap">数量区间</td>
					<td width="100" nowrap="nowrap">价格</td>					
				  </tr>
				</thead>
				<tbody>
				<?php
					if(!empty($price)){
						foreach($price as $k=>$v){
				?>
				  <tr>
					<td nowrap="nowrap"><?php echo $v["max"]>0 ? $v["min"].' ~ '.$v["max"] : $v["min"].'以上';?></td>
					<td nowrap="nowrap" class="last"><span class="fc_red"><?php echo $v['price']?></span> 元/条</td>
				  </tr>
				<?php }}?>
				</tbody>
			   </table>
			   <p><span class="tips">(注：短信价格表，如100 ~ 200 表示 100<购买条数<=200 价格0.1元/条，则表示购买数量在100-200条之间，每条0.1元；)</span></p>
			  </span>
			  <div class="clear"></div>
			</div>
            <div class="rows">
                <label></label>
                <span><input type="submit" class="btn_green btn_w_120" style="margin-top:5px" name="submit_button" value="确定" />
				<a href="javascript:void(0);" class="btn_gray" style="margin-top:5px" onClick="history.go(-1);">返回</a></span>
                <div class="clear"></div>
            </div>
        </form>
    </div>
  </div>
</div>
</body>
</html>