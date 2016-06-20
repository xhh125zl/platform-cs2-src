<?php
$base_url = base_url();

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$dis_config = Dis_Config::find($_SESSION['Users_ID']);

$psize = 10;
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
$conditrec = "where Users_ID = '".$_SESSION['Users_ID']."'";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and `".$_GET["Fields"]."` like '%".$_GET["Keyword"]."%'";
		}
		if(!empty($_GET["Account_ID"])){			
			$condition .= " and Account_ID=".$_GET["Account_ID"];
		}		
		if(!empty($_GET["AccTime_S"])){
			$conditrec .= " and Record_CreateTime>=".strtotime($_GET["AccTime_S"]);
		}
		if(!empty($_GET["AccTime_E"])){
			$conditrec .= " and Record_CreateTime<=".strtotime($_GET["AccTime_E"]);
		}
		if(!empty($_GET["psize"])){
			$psize = intval($_GET["psize"]);
		}
	}
}
$curid = 2;

if (isset($dis_config) && !empty($dis_config['Sha_Rate'])) 
{
    $Sha_Rate = json_decode($dis_config['Sha_Rate'], true);
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
    <?php require_once('shamenu.php'); ?>   
    <div id="user" class="r_con_wrap">
	<form class="search" id="search_form" method="get" action="?">
        <select name="Fields">			
			<option value='Real_Name'>真实姓名</option>			
		</select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />&nbsp;		
		股东ID：<input type="text" name="Account_ID" value="" class="form_input" size="15" />&nbsp;
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

            <td width="7%" nowrap="nowrap">序号</td>
                <td width="8%" nowrap="nowrap">股东ID</td>
                <td width="20" nowrap="nowrap">股东级别</td>
                <td width="25%" nowrap="nowrap">微信昵称</td>
                <td width="25%" nowrap="nowrap">真实姓名</td>
             <td width="15%" nowrap="nowrap">分红</td>

          </tr>
        </thead>		
        <tbody>	
			<?php
		  $condition .= " and Enable_Agent = 1 order by User_ID desc";

		  $DB->getPage("distribute_account","User_ID,Account_ID,Real_Name,Enable_Agent,sha_level",$condition,$psize);		  

		  /*获取订单列表牵扯到的分销商*/
		  $sha_list = array();		  
		  $userid = array();
		  $accid = array();
		  $newsha_list = array();
		  while($rr=$DB->fetch_assoc()){
			$sha_list[] = $rr;
			$userid[] = $rr['User_ID'];
			$accid[] = $rr['Account_ID'];
		  }
		  if(count($sha_list)>0){
		  $DB->query("select Users_ID,Record_Money,Sha_Qty,Sha_Accountid from distribute_sha_rec ".$conditrec);
		  $sha_rec = array();
		  $sha_fenh = array();
		  $shafenh = 0;
		  while($rb=$DB->fetch_assoc()){
			$sha_rec[] = $rb;			
		  }		  
			foreach($accid as $key=>$acid){			  
			 foreach($sha_rec as $key=>$rec){
			if(strpos($rec['Sha_Accountid'],$acid)){
			$shafenh += $rec['Record_Money']/$rec['Sha_Qty'];
			}	
			 }
			 $sha_fenh[]['sha_fenh'] = $shafenh;			 
			 $shafenh = 0;
		}
		
		$userstr = '';
		foreach($userid as $key=>$useid){
			$userstr .= $useid.',';
		}
		$userstr = substr($userstr,0,-1);		
			$DB->query("select User_ID,User_NickName from user where User_ID in ($userstr) order by User_ID desc");
		$nickname = array();
		$nickname = $DB->toArray();
		$newsha_list = array();
		foreach($sha_list as $key=>$list){			
			$list['User_NickName'] = $nickname[$key]['User_NickName'];
			$list['sha_fenh'] = $sha_fenh[$key]['sha_fenh'];			
			$newsha_list[] = $list;			
		}
		}
		  $i = 1;
		$zmoney = 0;		

			 ?>

	<?php foreach($newsha_list as $key=>$record):?>
           <tr>
           	<td><?=$i?></td>
			<td><?=$record['Account_ID']?></td>

                        <td><?=!empty($Sha_Rate['sha'][$record['sha_level']]['name'])?$Sha_Rate['sha'][$record['sha_level']]['name']:'未知' ?></td>

			<td><?=empty($record['User_NickName'])?'无':$record['User_NickName']?></td>            
            <td><?=empty($record['Real_Name'])?'无':$record['Real_Name']?></td>				
           <td><?=round_pad_zero((float)$record['sha_fenh'],2)?></td>			
          </tr>
		  <?php		 
		  $i++;
		  $zmoney += round_pad_zero($record['sha_fenh'],2);
		  ?>
      <?php endforeach; ?>
        </tbody>		
      </table>
      <div class="page center-block"><?php $DB->showPage();?><strong class="red"><?php echo count($sha_list) > 0 ? '分红金额计算：'.$zmoney : '';?></strong></div>
    </div>
  </div>  
</div>
</body>
</html>

