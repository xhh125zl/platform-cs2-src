<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$base_url = base_url();

//未登录则跳转到登录页
if(empty($_SESSION["Distribute_ID"]))
{
	header("location:/dis/login.php");
}

$Users_ID  =  $_SESSION["Dis_Users_ID"]; 
$User_ID = $_SESSION["Distribute_ID"];


$action=empty($_GET['action'])?'':$_GET['action'];

if(isset($action))
{	

	if($action=="del"){
		//1删除分销记录
		$Flag=$DB->Del("distribute_account_record","Users_ID='".$_SESSION["Dis_Users_ID"]."' and Record_ID=".$_GET["RecordID"]);
	
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}elseif($action == "fullfill"){
	
		$data = array("Record_Status"=>1);
		$condition = "where Users_ID='".$_SESSION["Dis_Users_ID"]."' and Record_ID='".$_GET['RecordID']."'";
		$Flag = $DB->set("distribute_account_record",$data,$condition);
		
		if($Flag)
		{
			echo '<script language="javascript">alert("更新成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("更新失败");history.back();</script>';
		}
		exit;
		
	}elseif($action == "reject"){
		
		//获取此次提现记录内容
	
		$condition = "where Users_ID='".$_SESSION["Dis_Users_ID"]."' and Record_ID='".$_GET['RecordID']."'";
		$rsRecord = $DB->getRs("distribute_account_record","Record_Money,User_ID",$condition);
	
		$data = array("Record_Status"=>2);
		$condition = "where Users_ID='".$_SESSION["Dis_Users_ID"]."' and Record_ID='".$_GET['RecordID']."'";
		$Flag = $DB->set("distribute_account_record",$data,$condition);
		
		//将钱退回
		$money = $rsRecord['Record_Money'];
		
		$condition = "where User_ID=".$rsRecord['User_ID']." and Users_ID='".$_SESSION["Dis_Users_ID"]."'";
	    $DB->set('distribute_account',"balance=balance+$money",$condition);
		
		if($Flag)
		{
			unset($_Get);
			echo '<script language="javascript">window.location="withdraw_record.php";</script>';
		}else
		{
			echo '<script language="javascript">alert("更新失败");history.back();</script>';
		}
		exit;
	}
}


$condition = "where Users_ID='".$_SESSION["Dis_Users_ID"]."'";

if(isset($_GET["search"])){

	if($_GET["search"]==1){
	
    if(!empty($_GET["Keyword"])){
      $condition .= " and Record_Sn like '%".$_GET["Keyword"]."%'";
    }
    if(isset($_GET["Status"])){
      if($_GET["Status"]<>''){
        $condition .= " and Record_Status=".$_GET["Status"];
      }
    }
    if(!empty($_GET["AccTime_S"])){
      $condition .= " and Record_CreateTime>=".strtotime($_GET["AccTime_S"]);
    }
    if(!empty($_GET["AccTime_E"])){
      $condition .= " and Record_CreateTime<=".strtotime($_GET["AccTime_E"]);
    }
	
	}
}


$condition .= " and Record_Type = 1";
$condition .= " order by Record_CreateTime desc";

$rsRecords = $DB->getPage("distribute_account_record","*",$condition,$pageSize=10);
$record_list = $DB->toArray($rsRecords);

$user_array = array();
$product_array = array();
$bank_array = array();

//获取用户列表
$condition = "where Users_ID='".$_SESSION["Dis_Users_ID"]."'"; 
if(count($user_array)>0){
	$condition .= "and User_ID in (".implode(',',$user_array).")";
}

$rsUsers = $DB->get('user','User_ID,User_NickName',$condition);
$User_list = $DB->toArray($rsUsers );

//获取商品列表
$condition = "where Users_ID='".$_SESSION["Dis_Users_ID"]."'";

if(count($user_array)>0){
	$condition .= "and Products_ID in (".implode(',',$product_array).")";
}

$rsProducts = $DB->get('shop_products',"Products_ID,Products_Name",$condition);
$Product_list = $DB->toArray($rsProducts );

$user_dropdown = get_dropdown_list($User_list,'User_ID','User_NickName');
$product_dropdown = get_dropdown_list($Product_list,'Products_ID','Products_Name');


$status = array("申请中","已执行","已驳回");

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
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    
     <!-- nav begin -->
   <?php $cur_nav = 'withdraw_record'?>
   <?php require_once('nav.php')?>
   <!-- nav end-->
   
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
     <script type='text/javascript' src='/static/js/inputFormat.js'></script>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">
	$(document).ready(function(){shop_obj.distribute_init();});
	
</script>
    <div id="update_post_tips"></div>
    <div id="user" class="r_con_wrap">
      
      <form class="search" id="search_form" method="get" action="?">
        关键词：
        <input name="Keyword" value="" class="form_input" size="15" type="text">
        记录状态：
        <select name="Status">
          <option value="">--请选择--</option>
          <option value="0">申请中</option>
          <option value="1">已执行</option>
          <option value="2">已驳回</option>
        </select>
        时间：
         <input type="text" class="input" name="AccTime_S" value="" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="" maxlength="20" />
		<input value="1" name="search" type="hidden">
        <input class="search_btn" value="搜索" type="submit">
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
            <td width="8%" nowrap="nowrap">用户</td>
            <td width="5%" nowrap="nowrap">流水号</td>
            <td width="15%" nowrap="nowrap">提现账户</td>
            <td width="8%" nowrap="nowrap">金额</td>
            
      	   <td width="8%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap">时间</td>
     
          </tr>
        </thead>
        <tbody>
      
		  
	<?php foreach($record_list as $key=>$rsRecord):?>
           <tr Record_ID="<?php echo $rsRecord['Record_ID'] ?>">
          	<td nowarp="nowrap"><?=$rsRecord['Record_ID']?></td>
            <td nowarp="nowrap" field=1>
            <?=!empty($user_dropdown[$rsRecord['User_ID']])?$user_dropdown[$rsRecord['User_ID']]:'用户已被删除';?>
            </td>
            <td nowarp="nowrap"><?=$rsRecord['Record_Sn']?></td>
             <td nowarp="nowrap"><?=$rsRecord['Account_Info']?></td>
            <td nowarp="nowrap">&yen;<?=$rsRecord['Record_Money']?></td>           
            <td nowrap="nowrap"><?=$status[$rsRecord['Record_Status']]?></td>
            <td nowrap="nowrap" class="last"><?php echo date("Y-m-d H:i:s",$rsRecord['Record_CreateTime']) ?></td>
            
          </tr>
      <?php endforeach; ?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
  
  <div id="reject_withdraw" class="lean-modal lean-modal-form">
    <div class="h">驳回用户提现理由<a class="modal_close" href="#"></a></div>
    <form class="form">
      <div class="rows">
        <label>驳回理由：</label>
        <span class="input">
        <textarea name="Reject_Reason" id="Reject_Reason" notnull ></textarea>
        <font class="fc_red">*</font></span>
        <div class="clear"></div>
      </div>
      <div class="rows">
        <label></label>
        <span class="submit">
        <input type="submit" value="确定提交" name="submit_btn">
        </span>
        <div class="clear"></div>
      </div>
      <input type="hidden" name="Record_ID" value="" >
      <input type="hidden" name="action" value="reject_withdraw">
    </form>
    <div class="tips"></div>
  </div>
  
</div>
</div>
</body>
</html>