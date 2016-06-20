<?php
$base_url = base_url();
require_once($_SERVER["DOCUMENT_ROOT"].'/data/areachange.php');
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$dis_config = Dis_Config::find($_SESSION['Users_ID']);
$psize = 10;
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
$agentition = "where Users_ID='".$_SESSION["Users_ID"]."'";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and `".$_GET["Fields"]."` like '%".$_GET["Keyword"]."%'";
		}		
		if(isset($_GET["Status"])){
			if($_GET["Status"]<>''){
				$condition .= " and type=".$_GET["Status"];
			}
		}
		if(!empty($_GET["AccTime_S"])){
			$condition .= " and create_at>=".strtotime($_GET["AccTime_S"]);
		}
		if(!empty($_GET["AccTime_E"])){
			$condition .= " and create_at<=".strtotime($_GET["AccTime_E"]);
		}
		if(!empty($_GET["psize"])){
			$psize = intval($_GET["psize"]);
		}
	}
}
$curid = 2;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>地区代理列表</title>

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
			<option value='area_name'>代理区域</option>
			<option value='Account_ID'>分销商ID</option>
		</select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />&nbsp;		
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
            <td width="15%" nowrap="nowrap">序号</td>
			 <td width="10%" nowrap="nowrap">分销商ID</td>
            <td width="25%" nowrap="nowrap">微信昵称</td>           
             <td width="15%" nowrap="nowrap">代理区域</td>
			<td width="10%" nowrap="nowrap">代理区域所获金额</td>
            <td width="15%" nowrap="nowrap">代理类型</td>
            <td width="20%">时间</td>    
          </tr>
        </thead>
        <tbody>     
		<?php
		  $condition .= " order by id desc";
		  $DB->getPage("distribute_agent_areas","*",$condition,$psize);		  
		  $Record_Type=array("无","省代","市代","县（区）代");		  
		  /*获取订单列表牵扯到的分销商*/
		  $record_list = array();
		  $nickname = array();
		  $accountid = array();
		  $areaid = array();
		  while($rr=$DB->fetch_assoc()){
			$record_list[] = $rr;
			$accountid[] = $rr['Account_ID'];
			$areaid[] = $rr['area_id'];
		  }	
		$DB->query("select sum(Record_Money) as money, area_id from distribute_agent_rec ".$agentition." group by area_id");		 
		  $agent_rec = array();		  
		  $agentrecarr = array();		 
		  $agentrecarr = $DB->toArray();
		  foreach($agentrecarr as $key=>$aeid){
			$agent_rec[$aeid['area_id']] = $aeid['money'];
		}
		$accountidarr = array_unique($accountid);		
		foreach($accountidarr as $key=>$accid){
		$userid = $DB->query("select user.User_NickName from distribute_account, user where distribute_account.Account_ID = $accid and distribute_account.User_ID = user.User_ID");
		$dbar = $DB->toArray();
		if(count($dbar) > 0){
		$nickname[$accid] = $dbar[0]["User_NickName"];
		}
		}
		$areachange_filter = array();		
		foreach($areaid as $key=>$aeid){
			$areachange_filter[$aeid] = $areachange[$aeid];
		}
		unset($areachange);
		  $i = 1;
		  $zmoney = 0;
			?> 
	<?php foreach($record_list as $key=>$area):?>        
           <tr>
           	<td><?=$i?></td>
			<td><?=$area['Account_ID']?></td>
			<?php if(array_key_exists($area['Account_ID'],$nickname)):?>
			<td><?=$nickname[$area['Account_ID']]?></td>
			<?php else:?>
			<td>无</td>
			<?php endif;?>            
			<?php if($areachange_filter[$area['area_id']]['area_deep'] == 1):?>
			<td><?=$area['area_name']?></td>
			<?php elseif($areachange_filter[$area['area_id']]['area_deep'] == 2):?>
			<td><?=$areachange_filter[$area['area_id']]['pro_name'].'&gt;'.$area['area_name']?></td>
			<?php else:?>
			<td><?=$areachange_filter[$area['area_id']]['pro_name'].'&gt;'.$areachange_filter[$area['area_id']]['city_name'].'&gt;'.$area['area_name']?></td>
			<?php endif;?>
			
			<?php if(array_key_exists($area['area_id'],$agent_rec)):?>
			<td><?=round_pad_zero($agent_rec[$area['area_id']],2)?></td>
			<?php else:?>
			<td><?=0?>
			</td>
			<?php endif;?>
			
			<td><?=$Record_Type[$area['type']]?></td>
			<td><?=ldate($area['create_at'])?></td>
          </tr>
		  <?php 
		  $i++;
		  if(array_key_exists($area['area_id'],$agent_rec)){
		  $zmoney += round_pad_zero($agent_rec[$area['area_id']],2);
		  }
		  ?>
      <?php endforeach; ?>
        </tbody>
      </table>
      <div class="page center-block"><?php $DB->showPage();?><strong class="red"><?php echo count($record_list) > 0 ? '金额总计：'.$zmoney : '';?></strong></div>
    </div>
  </div>
</div>
</div>
</body>
</html>

