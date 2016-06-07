<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("message_template","Users_ID='".$_SESSION["Users_ID"]."' and Template_ID=".$_GET["MessageID"]);
	}
	if($Flag){
		echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else{
		echo '<script language="javascript">alert("删除失败");history.back();</script>';
	}
	exit;
}
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
        <li class="cur"><a href="/member/wechat/message.php">模板消息管理</a></li>        
      </ul>
    </div>
    <div id="message" class="r_con_wrap">
      <div class="control_btn"><a href="message_add.php" class="btn_green btn_w_120">添加模板</a></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="30%" nowrap="nowrap">模板类型</td>
            <td width="30%" nowrap="nowrap">模板ID</td>
            <td width="20%" nowrap="nowrap">添加时间</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php
			$lists = array();
			$DB->getPage("message_template","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Template_ID desc",$pageSize=10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			foreach($lists as $k=>$rsMessage){
				$item = $DB->GetRs("message_model","*","where Model_ID=".$rsMessage["Model_ID"]);
				$rsMessage["Name"] = $item["Model_Name"];
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $k+1; ?></td>
            <td nowrap="nowrap"><?php echo $rsMessage["Name"]; ?></td>
            <td nowrap="nowrap"><?php echo $rsMessage["Template_LinkID"]; ?></td>
            <td><?php echo date("Y-m-d H:i:s",$rsMessage["Template_CreateTime"]); ?></td>
            <td class="last" nowrap="nowrap"><a href="message_edit.php?MessageID=<?php echo $rsMessage["Template_ID"]; ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="?action=del&MessageID=<?php echo $rsMessage["Template_ID"]; ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
          </tr>
          <?php
}?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>