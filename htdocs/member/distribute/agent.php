<?php
$base_url = base_url();

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$dis_config = Dis_Config::find($_SESSION['Users_ID']);

$psize = 10;
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and `".$_GET["Fields"]."` like '%".$_GET["Keyword"]."%'";
		}
		if(!empty($_GET["OrderNo"])){
			$OrderID = substr($_GET["OrderNo"],8);
			$OrderID =  empty($OrderID) ? 0 : intval($OrderID);
			$condition .= " and Order_ID=".$OrderID;
		}
		if(isset($_GET["Status"])){
			if($_GET["Status"]<>''){
				$condition .= " and Record_Type=".$_GET["Status"];
			}
		}
		if(!empty($_GET["AccTime_S"])){
			$condition .= " and Record_CreateTime>=".strtotime($_GET["AccTime_S"]);
		}
		if(!empty($_GET["AccTime_E"])){
			$condition .= " and Record_CreateTime<=".strtotime($_GET["AccTime_E"]);
		}
		if(!empty($_GET["psize"])){
			$psize = intval($_GET["psize"]);
		}
	}
}
$curid = 1;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/distribute/order.js?t=041655521'></script>
<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
<script language="javascript">$(document).ready(order_obj.orders_init);</script>
</head>
<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <?php require_once('agentmenu.php'); ?>   
    <div id="user" class="r_con_wrap">
	<form class="search" id="search_form" method="get" action="?">
        <select name="Fields">			
			<option value='Real_Name'>姓名</option>			
		</select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />&nbsp;
		订单号：<input type="text" name="OrderNo" value="" class="form_input" size="15" />&nbsp;
        代理类型：
        <select name="Status">
          <option value="">--请选择--</option>          
          <option value='1'>省代理</option>
          <option value='2'>市代理</option>
		  <option value='3'>县（区）代理</option>
        </select>
        时间：
        <input type="text" class="input" name="AccTime_S" value="" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="" maxlength="20" />
        &nbsp;
        <input type="text" name="psize" value="" class="form_input" size="5" /> 条/页
        <input type="submit" class="search_btn" value="搜索" />        
        <input type="hidden" value="1" name="search" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">微信昵称</td>
            <td width="15%" nowrap="nowrap">真实姓名</td>			
			<td width="15%" nowrap="nowrap">订单号</td>
             <td width="15%" nowrap="nowrap">金额</td>
            <td width="15%" nowrap="nowrap">类型</td>
            <td width="15%">时间</td>    
          </tr>
        </thead>		
        <tbody>	
			<?php
		  $condition .= " order by Record_ID desc";
		  $DB->getPage("distribute_agent_rec","*",$condition,$psize);		  
		  $Record_Type=array("无","省代理","市代理","县（区）代理");		  
		  /*获取订单列表牵扯到的分销商*/
		  $record_list = array();
		  $nickname = array();
		  $accountid = array();
		  while($rr=$DB->fetch_assoc()){
			$record_list[] = $rr;
			$accountid[] = $rr['Account_ID'];
		  }	
		$accountidarr = array_unique($accountid);		
		foreach($accountidarr as $key=>$accid){
		$userid = $DB->query("select user.User_NickName from distribute_account, user where distribute_account.Account_ID = $accid and distribute_account.User_ID = user.User_ID");
		$dbar = $DB->toArray();
		if(count($dbar) > 0){
		$nickname[$accid] = $dbar[0]["User_NickName"];
		}
		}
		  $i = 1;
		$zmoney = 0;
			?>
	<?php foreach($record_list as $key=>$record):?>	
           <tr>
           	<td><?=$i?></td>
            <td><?=$nickname[$record['Account_ID']]?></td>
            <td><?=$record['Real_Name']?></td>
			<td><?=date("Ymd",$record["Order_CreateTime"]).$record["Order_ID"]?></td>
            <td><span class="red">&yen;<?=round_pad_zero($record['Record_Money'],2)?></span></td>
            <td><?=$Record_Type[$record['Record_Type']]?></td>
            <td><?=ldate($record['Record_CreateTime'])?></td>
          </tr>
		  <?php 
		  $i++;
		  $zmoney += round_pad_zero($record['Record_Money'],2);
		  ?>
      <?php endforeach; ?>
        </tbody>		
      </table>
      <div class="page center-block"><?php $DB->showPage();?><strong class="red"><?php echo count($record_list) > 0 ? '金额总计：'.$zmoney : '';?></strong></div>
    </div>
  </div>  
</div>

</body>
</html>

