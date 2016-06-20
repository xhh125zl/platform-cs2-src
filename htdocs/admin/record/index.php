<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["Agent_Account"]))
{
	header("location:account_login.php");
}
$Keywords=empty($_REQUEST["Keywords"])?"":trim($_REQUEST["Keywords"]);
$type=empty($_REQUEST["type"])?"":trim($_REQUEST["type"]);
$orderby=empty($_REQUEST["orderby"])?"":trim($_REQUEST["orderby"]);
$fromdate = empty($_REQUEST["AccTime_S"]) ? "" : $_REQUEST["AccTime_S"];
$todate = empty($_REQUEST["AccTime_E"]) ? "" : $_REQUEST["AccTime_E"];
$condition = "where Agent_ID=".$_SESSION["Agent_ID"];
if($Keywords){
	$condition .= " and Note like '%".$Keywords."%'";
}
if($type){
	$condition .= " and Type=".$type;
}
if($fromdate){
	$condition .= " and CreateTime>=".strtotime($fromdate);
}
if($todate){
	$condition .= " and CreateTime<=".strtotime($todate);
}
$orderby = $orderby ? " order by ".$orderby : " order by Record_ID desc";

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/agent/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/agent/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="index.php">资金流水</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
    <script type='text/javascript' src='/static/agent/js/record.js'></script>
    <script language="javascript">
		$(document).ready(record_obj.record_init);
	</script>
    <div class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="">
        关键字：<input name="Keywords" value="<?php echo $Keywords;?>" type="text" class="form_input" size="15"/>&nbsp;&nbsp;
        类型：<select name="type">
        <option value="0">全部</option>
        <option value="1"<?php echo $type==1 ? " selected" : ""?>>收入</option>
        <option value="2"<?php echo $type==2 ? " selected" : ""?>>支出</option>
        </select>&nbsp;&nbsp;
        排序：<select name="orderby">
        <option value="Record_ID desc">默认</option>
        <option value="Record_ID asc"<?php echo $orderby=="Record_ID asc" ? " selected" : ""?>>ID升序</option>
        <option value="Amount desc"<?php echo $orderby=="Amount desc" ? " selected" : ""?>>金额降序</option>
        <option value="Amount asc"<?php echo $orderby=="Amount asc" ? " selected" : ""?>>金额升序</option>
        <option value="CreateTime desc"<?php echo $orderby=="CreateTime desc" ? " selected" : ""?>>时间降序</option>
        <option value="CreateTime asc"<?php echo $orderby=="CreateTime asc" ? " selected" : ""?>>时间升序</option>
        </select>
        &nbsp;&nbsp;<input type="submit" class="search_btn" value="记录搜索" />
        <div class="b10"></div>
        时&nbsp;&nbsp;&nbsp; 间：<input type="text" class="input" name="AccTime_S" value="<?php echo $fromdate;?>" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="<?php echo $todate;?>" maxlength="20" />
      </form>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="15%" nowrap="nowrap">记录ID</td>
            <td width="15%" nowrap="nowrap">金额</td>
            <td width="15%" nowrap="nowrap">收/支</td>
            <td width="15%" nowrap="nowrap">时间</td>
            <td width="10%" nowrap="nowrap">备注</td>
          </tr>
        </thead>
        <tbody>
        <?php 
			$DB->getPage("money_record","*",$condition.$orderby,10);
			while($rsRecord=$DB->fetch_assoc()){
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $rsRecord["Record_ID"]; ?></td>
            <td nowrap="nowrap"><?php echo $rsRecord["Amount"]; ?></td>
            <td nowrap="nowrap"><?php echo $rsRecord["Type"]==1 ? '<font style="color:blue">收入</font>' : '<font style="color:red">支出</font>'; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsRecord["CreateTime"]) ?></td>
            <td nowrap="nowrap" style="padding-left:5px"><?php echo $rsRecord["Note"];?></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>