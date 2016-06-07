<?php
$base_url = base_url();

$_SERVER['HTTP_REFERER'] =  $base_url.'member/distribute/withdraw.php';
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

global $DB;

$condition = ' WHERE `Users_ID`="' .$_SESSION['Users_ID']. '"';

if(isset($_GET["search"]))
{
	if ($_GET['search'] == 1){
		if(!empty($_GET["Keyword"])){
			$user_ids = array(0);
			if($_GET['Fields']=='User_ID'){
				$DB->Get('user','User_ID',"where Users_ID='".$_SESSION['Users_ID']."' and User_NickName like '%".$_GET["Keyword"]."%'");
				while($r = $DB->fetch_assoc()){
					$user_ids[] = $r['User_ID'];
				}
				if(!empty($user_ids)){
					$condition .= " and User_ID in(".implode(',',$user_ids).")";
				}
			}elseif($_GET['Fields']=='Buyer_ID'){
				$DB->Get('user','User_ID',"where Users_ID='".$_SESSION['Users_ID']."' and User_NickName like '%".$_GET["Keyword"]."%'");
				while($r = $DB->fetch_assoc()){
					$user_ids[] = $r['User_ID'];
				}
				if(!empty($user_ids)){
					$condition .= " and Buyer_ID in(".implode(',',$user_ids).")";
				}
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

$condition .= ' ORDER BY  `Order_ID` DESC,level ASC';

$recordList = array();//分销记录数组
$userids = array();//用户ID集
$orderids = array();//订单ID集
$DB->getPage('distribute_order_record', '*', $condition,10);
while($r = $DB->fetch_assoc()){
	$recordList[] = $r;
	if(!in_array($r['Buyer_ID'],$userids)){
		$userids[] = $r['Buyer_ID'];
	}
	if(!in_array($r['User_ID'],$userids)){
		$userids[] = $r['User_ID'];
	}
}

$user_list = array();//用户列表
if(!empty($userids)){
	$DB->Get('user','User_NickName,User_ID','where User_ID in('.implode(',',$userids).')');
	while($v = $DB->fetch_assoc()){
		$user_list[$v['User_ID']] = $v['User_NickName'];
	}
}


//获取分销商级别
$levelList = get_dis_level($DB,$_SESSION['Users_ID']);
$level_arr = array('','一','二','三','四','五','六','七','八','九','十');
$_Status = array('<font style="padding:6px 10px; border-radius:5px; background:#009D4E; color:#FFF">购买级别</font>','<font style="padding:6px 10px; border-radius:5px; background:#D25E2A; color:#FFF">级别升级</font>');
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
		<li><a href="order_history.php">订单记录</a></li>
        <li class="cur"><a href="commsion.php">佣金记录</a></li>        
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
        <select name="Fields">
			<option value="Buyer_ID">下单人昵称</option>
			<option value="User_ID">获奖人昵称</option>
		</select>
        <input name="Keyword" value="" class="form_input" size="15" type="text">
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
			<td width="5%" nowrap="nowrap">记录号</td>
            <td width="6%" nowrap="nowrap">订单号</td>
			<td width="8%" nowrap="nowrap">下单人</td>
            <td width="8%" nowrap="nowrap">获奖人</td>
			<td width="10%" nowrap="nowrap">获奖人级别</td>
            <td width="10%" nowrap="nowrap">得奖级别</td>
			<td nowrap="nowrap">描述</td>
			<td width="8%" nowrap="nowrap">得奖金额</td>
			<td width="12%" nowrap="nowrap">创建时间</td>
          </tr>
        </thead>
        <tbody>
		<?php if (!empty($recordList)) : $i=0; $orderid = 0;?>
		<?php foreach ($recordList as $key => $value) : if($orderid!=$value['Order_ID']){$orderid=$value['Order_ID']; $i++;}?>
			<tr>
				<td><?php echo $value['Record_ID']; ?></td>
				<td><?php echo $value['Order_ID']; ?></td>
                <td><?php echo empty($user_list[$value['Buyer_ID']]) ? '' : $user_list[$value['Buyer_ID']]; ?></td>
                <td><?php echo empty($user_list[$value['User_ID']]) ? '' : $user_list[$value['User_ID']]; ?></td>
				<td><?php if (in_array($value['level'], array_keys($levelList))) { echo $levelList[$value['level']]['Level_Name']; } else { echo "无级别"; } ?></td>
                <td><font style="padding:6px 10px; border-radius:5px; background:#<?php if($i%2==0){?>009D4E<?php }else{?>D25E2A<?php }?>; color:#FFF"><?php echo $level_arr[$value['level']]; ?>级</font></td>
				<td><?php echo $value['Record_Description']; ?></td>
				<td><?php echo $value['Record_Money']; ?></td>
				<td><?php echo date('Y-m-d H:i:s', $value['Record_CreateTime']); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php endif; ?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
  
</div>
</div>
</body>
</html>

