<?php
$base_url = base_url();

$_SERVER['HTTP_REFERER'] =  $base_url.'member/distribute/order_history.php';
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$condition = ' WHERE `Users_ID`="' .$_SESSION['Users_ID']. '" and `Order_Status`=4';

if(isset($_GET["search"]))
{
	if ($_GET['search'] == 1){
		if(!empty($_GET['kw'])){
			$condition .= " and `".$_GET["Fields"]."` like '%".$_GET["kw"]."%'";
		}
		if(!empty($_GET['Type'])){
			$condition .= " and Order_Type=".($_GET['Type']-1);
		}
	    if(!empty($_GET["AccTime_S"])){
	      $condition .= " and Order_CreateTime>=".strtotime($_GET["AccTime_S"]);
	    }
	    if(!empty($_GET["AccTime_E"])){
	      $condition .= " and Order_CreateTime<=".strtotime($_GET["AccTime_E"]);
	    }
	}
}

$condition .= ' ORDER BY  `Order_ID` DESC';
$recordObj = $DB->getPage('distribute_order', '*', $condition);
$recordList = $DB->toArray($recordObj);

//获取分销商级别
$levelList = array();
$levelObj = $DB->Get('distribute_level', 'Level_ID, Level_Name');
while ($r = $DB->fetch_assoc($levelObj)) 
{
	$levelList[$r['Level_ID']] = $r;
}

$_STATUS = array('全部','购买级别','级别升级');
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
		<li class="cur"><a href="order_history.php">订单记录</a></li>
        <li><a href="commsion.php">佣金记录</a></li>        
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
			<!--<option value='Order_CartList'>商品</option>-->
			<option value='Address_Name'>购买人</option>
			<option value='Address_Mobile'>购买手机</option>
			<!--<option value='Address_Detailed'>收货地址</option>-->
		</select>
        <input type="text" name="kw" value="" class="form_input" size="15" />&nbsp;
		订单类型：
		<select name="Type">
			<option value="0">全部</option>
			<option value="1">购买级别</option>
			<option value="2">级别升级</option>
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
			<td width="8%" nowrap="nowrap">订单号</td>
			<td width="8%" nowrap="nowrap">用户</td>
			<td width="8%" nowrap="nowrap">分销商级别</td>
			<td width="5%" nowrap="nowrap">订单金额</td>
			<td width="5%" nowrap="nowrap">支付方式</td>
			<td width="8%" nowrap="nowrap">订单类型</td>
			<td width="8%" nowrap="nowrap">手机号</td>
			<td width="8%" nowrap="nowrap">姓名</td>
			<td width="8%" nowrap="nowrap">微信号</td>			
			<td nowrap="nowrap">详细地址</td>
			<td width="8%" nowrap="nowrap">下单时间</td>
			<td width="8%" nowrap="nowrap" class="last">支付时间</td>
          </tr>
        </thead>
        <tbody>
		<?php if (!empty($recordList)) : ?>
		<?php foreach ($recordList as $key => $value) : ?>
		<?php
			if (!empty($value['User_ID'])) 
			{
				$userInfo = $DB->GetRs('user', 'User_NickName', ' WHERE `User_ID`='.$value['User_ID']);
			}
		?>
			<tr>
				<td><?php echo $value['Order_ID']; ?></td>
				<td><?php echo $userInfo['User_NickName']; ?></td>
				<td><?php echo $value['Level_Name'];?></td>
				<td><?php echo $value['Order_TotalPrice']; ?></td>
				<td><?php echo $value['Order_PaymentMethod']; ?></td>				
				<td><?php echo $_Status[$value['Order_Type']]; ?></td>
				<td><?php echo $value['Address_Mobile']; ?></td>
				<td><?php echo $value['Address_Name']; ?></td>
				<td><?php echo $value['Address_WeixinID']; ?></td>
				<td><?php echo $value['Address_Detail']; ?></td>
				<td><?php echo date('Y-m-d H:i:s', $value['Order_CreateTime']); ?></td>
				<td><?php echo date('Y-m-d H:i:s', $value['Order_PayTime']); ?></td>
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

