<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}

$Keywords  = empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);
$mobile  = empty($_REQUEST["mobile"])?"":trim($_REQUEST["mobile"]);

$condition = "where 1";

if(!empty($_GET['search'])&&$_GET['search'] ==1){
	if(strlen($_REQUEST["Keywords"]) > 0){
		$condition .= " and (message like '%".$_REQUEST["Keywords"]."%')";	
	}
	
	if(strlen($_REQUEST["mobile"]) > 0){
		$condition .= " and mobile='".$_REQUEST["mobile"]."'";	
	}	
}


$condition .= " order by itemid desc";

//重发开始
if(!empty($_POST["action"])){
	if($_POST["action"]=='send'){
		if(isset($_POST["itemid"])){
			$item = $DB->GetRs("sms","*","where itemid=".$_POST["itemid"]);
			if(!$item){
				$Data = array(
					"status"=> 0,
					"msg"=>"你要重发的短信不存在"
				);
			}else{				
				require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
				send_sms($item["mobile"], $item["message"]);
				$Data = array(
					"status"=> 1,
					"msg"=>"短信发送成功"
				);
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

//获取续费记录
$rsRecords = $DB->getPage("sms","*",$condition,10);
$record_list = $DB->toArray($rsRecords);
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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="send_record.php">发送记录</a></li>
        <li><a href="sms_record.php">购买记录</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="">
      	<input type="hidden" name="search" value="1" />
        关键字：<input name="Keywords" value="<?php echo $Keywords;?>" class="form_input" size="30" type="text">
        手机：<input name="mobile" value="<?php echo $mobile;?>" class="form_input" size="30" type="text">
        <input class="search_btn" value="搜索" type="submit">
        
      </form>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">ID</td>
            <td width="15%" nowrap="nowrap">手机号码</td>
            <td nowrap="nowrap">内容</td>        
            <td width="20%" nowrap="nowrap">时间</td>
            <td width="10%">状态</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
		foreach($record_list as $key=>$item):
		?>
          <tr>
          	<td><?=$item['itemid']?></td>
            <td><?=$item['mobile']?></td>
            <td style="text-align:left; padding:0px 5px"><?=$item['message']?></td>
            <td><?=ldate($item['sendtime'])?></td>
            <td><?=$item['code']?></td>
            <td><button style="color:blue; cursor:pointer" onclick="send_sms(<?php echo $item["itemid"];?>);$(this).attr('disabled', true);">重发</button></td>
          </tr>
          <?php 
		  endforeach;
		  ?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>