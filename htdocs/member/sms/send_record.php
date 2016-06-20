<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if(isset($_POST["action"])){
	if($_POST["action"]=='send'){
		if(isset($_POST["itemid"])){
			$item = $DB->GetRs("sms","*","where itemid=".$_POST["itemid"]." and usersid='".$_SESSION["Users_ID"]."'");
			if(!$item){
				$Data = array(
					"status"=> 0,
					"msg"=>"你要重发的短信不存在"
				);
			}else{
				$rsUsers = $DB->GetRs("users","Users_Sms","where Users_ID='".$_SESSION["Users_ID"]."'");
				if($rsUsers["Users_Sms"]<=0){
					$Data = array(
						"status"=> 0,
						"msg"=>"你的剩余的短信不足，不能重发短信"
					);
				}else{					
					require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
					send_sms($item["mobile"], $item["message"], $_SESSION["Users_ID"]);
					$Data = array(
						"status"=> 1,
						"msg"=>"短信发送成功"
					);
				}
			}
		}else{
			$Data = array(
				"status"=> 1,
				"msg"=>"缺少必要的参数"
			);
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}

$rsRecord = $DB->get("sms","*","where usersid='".$_SESSION['Users_ID']."'");
$record_list = $DB->toArray($rsRecord);
$i = 0;
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
</head>
<script type="text/javascript">
function send_sms(id){
	var data = {action:'send',itemid:id};
	$.post('?', data, function(data){
		if(data.status == 1){
			alert(data.msg);
			window.location.href='send_record.php';
		}else{
			alert(data.msg);
		}
	},"json");
}
</script>
<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/wechat.js'></script>
    <div class="r_nav">
      <ul>
	    <li><a href="/member/sms/sms_add.php">购买短信</a></li>
        <li><a href="/member/sms/sms_record.php">购买记录</a></li>
		<li class="cur"><a href="/member/sms/send_record.php">发送记录</a></li>	
      </ul>
    </div>
    <div id="message" class="r_con_wrap">
    
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">手机号码</td>
            <td width="37%" nowrap="nowrap">内容</td>
            <td width="20%" nowrap="nowrap">时间</td>
            <td width="15%" nowrap="nowrap">状态</td>
            <td width="8%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        
       <?php foreach($record_list as $key=>$item):$i++?> 
          <tr>
            <td nowrap="nowrap"><?=$i?></td>
            <td nowrap="nowrap"><?=$item['mobile']?></td>
            <td nowrap="nowrap"><?=$item['message']?></td>
            <td><?=ldate($item['sendtime'])?></td>
            <td>
            	<?=$item['code']?>
            </td>
            <td class="last" nowrap="nowrap"><button style="color:blue; cursor:pointer" onclick="send_sms(<?php echo $item["itemid"];?>);$(this).attr('disabled', true);">重发</button></td>
          </tr>
      	<?php endforeach; ?>
      </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>