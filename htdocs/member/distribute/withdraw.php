<?php
$base_url = base_url();
ini_set("display_errors","On");
$_SERVER['HTTP_REFERER'] =  $base_url.'member/distribute/withdraw.php';
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$action=empty($_GET['action'])?'':$_GET['action'];

if(isset($action))
{	

	if($action == "fullfill"){
		/*
		$data = array("Record_Status"=>1);
		$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Record_ID='".$_GET['RecordID']."'";
		*/
		$data = array("Record_Status"=>1);
		$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Record_ID='".$_GET['RecordID']."'";
		$withdraw_data = $DB->getRs("distribute_withdraw_record","*",$condition);
		$amount = $withdraw_data['Record_Money'];
		$UsersID = $withdraw_data['Users_ID'];
		$UserID = $withdraw_data['User_ID'];
		$rsUser = $DB->GetRs("user", "User_Money", "where Users_ID='".$UsersID."' and User_ID =".$UserID);
		$Data=array(
			'Users_ID'=>$UsersID,
			'User_ID'=>$UserID,			
			'Type'=>1,
			'Amount'=>$amount,
			'Total'=>$rsUser['User_Money']+$amount,
			'Note'=>"佣金提现转入余额 +".$amount,
			'CreateTime'=>time()		
		);

		$Flag=$DB->Add('user_money_record',$Data);
		//更新用户余额
		$Data=array(				
			'User_Money'=>$rsUser['User_Money']+$amount					
		);		
		//$Set = $DB->Set("user",$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
		$Set = $DB->Set("user",$Data,"where Users_ID='".$UsersID."' and User_ID=".$UserID);
		$Flag = $Flag && $Set;
		
		$Flag = $DB->set("distribute_withdraw_record",$data,$condition);
		
		if($Flag)
		{
			echo '<script language="javascript">alert("更新成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("更新失败");history.back();</script>';
		}
		exit;
		
	
	}elseif($action == "wx_hongbao" || $action == "wx_zhuanzhang"){
		$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Record_ID='".$_GET['RecordID']."'";
		$rsRecord = $DB->getRs("distribute_withdraw_record","Record_Money,User_ID,Users_ID,Record_Status",$condition);
		if(!$rsRecord){
			echo '<script language="javascript">alert("信息不存在");history.back();</script>';
			exit;
		}
		
		if($rsRecord["Record_Status"]==1){
			echo '<script language="javascript">alert("该记录已提现");window.location="withdraw.php";</script>';
			exit;
		}
		
		if($action == "wx_hongbao"){
			if($rsRecord["Record_Money"]<1 && $rsRecord["Record_Money"]>200){
				echo '<script language="javascript">alert("金额在1-200之间才可使用微信红包提现");history.back();</script>';
				exit;
			}
		}else{
			if($rsRecord["Record_Money"]<0){
				echo '<script language="javascript">alert("金额必须大于零才可使用微信转帐");history.back();</script>';
				exit;
			}
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
		$pay_order = new pay_order($DB, 0);
		$Data = $pay_order->withdraw($_SESSION["Users_ID"],$rsRecord['User_ID'],$_GET['RecordID'],$action);
		
		if($Data["status"]==1){
			unset($_Get);
			echo '<script language="javascript">alert("操作成功");window.location="withdraw.php";</script>';
			exit;
		}else{
			unset($_Get);
			echo '<script language="javascript">alert("'.$Data["msg"].'");window.location="withdraw.php";</script>';
			exit;
		}
	}
}


$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";

if(isset($_GET["search"])){

	if($_GET["search"]==1){
	
    if(!empty($_GET["Keyword"])){
      $condition .= " and Method_Account like '%".$_GET["Keyword"]."%'";
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

$condition .= " order by Record_CreateTime desc";

$rsRecords = $DB->getPage("distribute_withdraw_record","*",$condition,$pageSize=10);
$record_list = $DB->toArray($rsRecords);
// var_dump($record_list); exit();
$user_array = array();
$product_array = array();
$bank_array = array();

//获取用户列表
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'"; 
if(count($user_array)>0){
	$condition .= "and User_ID in (".implode(',',$user_array).")";
}

$rsUsers = $DB->get('user','User_ID,User_NickName',$condition);
$User_list = $DB->toArray($rsUsers );

//获取商品列表
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";

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
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/distribute/withdraw.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="withdraw.php">提现记录</a></li>
        <li><a href="withdraw_method.php">提现方法管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
     <script type='text/javascript' src='/static/js/inputFormat.js'></script>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">
	$(document).ready(function(){withdraw_obj.withdraw_init();});
	
</script>
    <div id="update_post_tips"></div>
    <div id="user" class="r_con_wrap">
      
      <form class="search" id="search_form" method="get" action="?">
        账户名称：
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
			<td width="5%" nowrap="nowrap">提现方式</td>
			<td width="15%" nowrap="nowrap">提现账户</td>
			<td width="8%" nowrap="nowrap">提现账号</td>
			<td width="8%" nowrap="nowrap">提现总金额</td>
			<td width="8%" nowrap="nowrap">提现手续费</td>
			<td width="8%" nowrap="nowrap">提现转入余额</td>
			<td width="10%" nowrap="nowrap">状态</td>
			<td width="10%" nowrap="nowrap">时间</td>
			<td width="8%" nowrap="nowrap" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
      
		  
	<?php foreach($record_list as $key=>$rsRecord):
		$userinfo = $DB->GetRs('user','User_NickName','where User_ID='.$rsRecord['User_ID']);
	?>
           <tr Record_ID="<?php echo $rsRecord['Record_ID'] ?>">
          	
          	<td nowarp="nowrap"><?=$rsRecord['Record_ID']?></td>
            <td nowarp="nowrap"><?php echo $userinfo ? $userinfo['User_NickName'] : '无昵称'?></td>
            <td nowarp="nowrap"><?=$rsRecord['Method_Name']?></td>
            <td nowarp="nowrap"><?=$rsRecord['Method_Account']?></td>
            <td nowarp="nowrap"><?=$rsRecord['Method_No']?></td>   
            <td nowarp="nowrap"><?=$rsRecord['Record_Total']?></td>          
            <td nowarp="nowrap"><?=$rsRecord['Record_Fee']?></td>       
            <td nowarp="nowrap"><?=$rsRecord['Record_Yue']?></td>       
            <td nowrap="nowrap"><?=$status[$rsRecord['Record_Status']]?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsRecord['Record_CreateTime']) ?></td>

            <td nowrap="nowrap" class="last">
            <?php if($rsRecord['Record_Status'] == 0):?>
            <?php if(trim($rsRecord['Method_Name'])=='微信红包' && $rsRecord['Record_Money']>=1 && $rsRecord['Record_Money']<=200){?>
            	<a href="<?=$base_url?>member/distribute/withdraw.php?action=wx_hongbao&RecordID=<?=$rsRecord['Record_ID']?>">发送红包</a>
			<?php }elseif(trim($rsRecord['Method_Name'])=='微信转帐' && $rsRecord['Record_Money']>0){?>
            	<a href="<?=$base_url?>member/distribute/withdraw.php?action=wx_zhuanzhang&RecordID=<?=$rsRecord['Record_ID']?>">微信转帐</a>
            <?php }else{?>
            	<a href="<?=$base_url?>member/distribute/withdraw.php?action=fullfill&RecordID=<?=$rsRecord['Record_ID']?>">完成</a>
            <?php }?>
            <a class="reject_btn" href="javascript:void(0)">驳回</a>
			<?php endif; ?>
            </td>
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