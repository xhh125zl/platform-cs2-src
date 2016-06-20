<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}

$Keywords  = empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);

$condition = "where Record_Type=0";

if(!empty($_GET['search'])&&$_GET['search'] ==1){
	
	if($_REQUEST["Record_Status"] != ''){
		$Status = $_REQUEST["Record_Status"];
		$condition .= " and Record_Status=".$Status;
	}
	
	
	if(strlen($_REQUEST["Keywords"]) > 0){
		$condition .= " and (Record_Sn like '%".$_REQUEST["Keywords"]."%')";	
	}
	
}


$condition .= " order by Users_ID desc";

//删除开始
if(!empty($_GET["action"])&&$_GET["action"]=="Del"){
	$ID=empty($_GET["ID"]) ? "0" : $_GET["ID"];
	mysql_query("delete from users_money_record where Record_ID='".$ID."'");
	echo "<script language='javascript'>";
	echo "alert('删除成功！');";
	echo "window.open('renewal_record.php','_self');";
	echo "</script>";
	exit();
}

//获取续费记录
$rsRecords = $DB->getPage("users_money_record","*",$condition,10);
$record_list = $DB->toArray($rsRecords);

$users_array = array(); //商家列表数组

foreach($record_list as $key=>$item){
	if(!in_array($item['Users_ID'],$users_array)){
		$users_array[] = $item['Users_ID'];
	}	
}


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
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="renewal_record.php">续费记录</a></li>
        <li><a href="renewal_config.php">费用设置</a></li>
     
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="">
      	<input type="hidden" name="search" value="1" />
        关键字：<input name="Keywords" value="" class="form_input" size="30" type="text">
        状态:<select name="Record_Status">
        		<option value="">全部</option>
                <option value="0">未付款</option>
                <option value="1">已付款</option>
        	</select>
        <input class="search_btn" value="搜索" type="submit">
        
      </form>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
            <td width="10%" nowrap="nowrap">商家名称</td>
            <td width="10%" nowrap="nowrap">续费号</td>
            <td width="5%" nowrap="nowrap">续费时长</td>         
            <td width="10%" nowrap="nowrap">时间</td>
            <td width="20%" nowrap="nowrap">交易编号</td>
            <td width="5%">状态</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
		foreach($record_list as $key=>$item):
		?>
          <tr>
          	<td><?=$item['Record_ID']?></td>
            <td><?=$item['Users_ID']?></td>
            <td><?=$item['Record_Sn']?></td>
            <td><?=$item['Record_Qty']?>年</td>
            <td><?=ldate($item['Record_CreateTime'])?></td>
            <td><?=!empty($item['trade_no'])?$item['trade_no']:'暂无';?></td>
            <td><?=$item['Record_Status']?'已付款':'未付款';?></td>
            <td> <a href="?action=Del&ID=<?php echo $item["Record_ID"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/admin/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
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