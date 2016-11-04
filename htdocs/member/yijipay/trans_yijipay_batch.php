<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once(CMS_ROOT . '/include/helper/tools.php');
require_once(CMS_ROOT . '/include/api/distribute.class.php');

function generate_orderno($prefix = '')
{
    mt_srand((double) microtime() * 1000000);
    return date('YmdHis') . str_pad(mt_rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
}

if(empty($_SESSION["Users_Account"])) {
	header("location:/member/login.php");
  exit();
}
$UsersID = $_SESSION['Users_ID'];

if ($_POST && isset($_POST['User_ID'])) {
  $userid_arr = $_POST['User_ID'];
  if (count($userid_arr) == 0) {
    echo '<script>alert("至少需要选择一个转账用户")</script>';
    exit();
  }
  $useridlist = [];
  foreach($userid_arr as $v){
	  $useridlist[$v] = $v;
  }

  $rsObjPay = Users_PayConfig::where('Users_ID', $UsersID)->first();
  $rsPay = $rsObjPay->toArray();
  require_once (CMS_ROOT . '/pay/yijipay/autoload.php');
  //@todo 批量转账
  $result = distribute::getyijibalancebyuserid(['userids'=>$useridlist]);
  if($result['errorCode'] == 0){
      $data = $result['data'];
	  $yijilist = TransYijipayRecord::whereIn("User_ID",$userid_arr)->where("status",0)->get();
	  if($yijilist){
			$yijipayTransData = [];
		    $orderNo = generate_orderno("BT");
			$yijilist = $yijilist->toArray();
			$idlist = "";
			foreach($yijilist as $k => $v){
			  if(isset($data[$v['User_ID']]) && $data[$v['User_ID']] > 0){
				  if(empty($idlist)){
					  $idlist = $v['ID'];
				  }else{
					  $idlist .= ",".$v['ID'];
				  }
				  
				  $yijipayTransData[] = [
					 'money' => $data[$v['User_ID']],
					 'itemMerchOrderNo' => generate_orderno("MBT").$v['created_at'],
					 'payeeUserId' => $v['Yiji_UserID'],
					 'outPayeeShopName' => '转账',
					 'memo' => '会员转账'
				  ];
				  $DB->Set("trans_yijipay_record", ['balance' => $data[$v['User_ID']]],"WHERE ID = " . $v['ID']);
			  }
			}
			if(count($yijipayTransData)>0){
				$DB->Set("trans_yijipay_record",['orderNo' => $orderNo, 'transTime' => time()], "WHERE ID IN (" . $idlist . ")");
				$param = [
					'orderNo' => $orderNo,
					'returnUrl' => SITE_URL . 'pay/yijipay/tfBalance_return_url.php',
					'notifyUrl' => SITE_URL . 'pay/yijipay/tfBalance_notify_url.php',
					'payerUserId' => PARTNER_ID
				];
				$param['toBalanceList'] =  json_encode($yijipayTransData, JSON_UNESCAPED_UNICODE);
				$account = new Account();
				$account->qftBatchTransfer($param);
				exit;
			}
			
	  }
  }
}


isset($_GET['type']) && $_GET['type'] == 2 ? $status = 1 : $status = 0;

$condition = "WHERE status=" . $status;

$lists = array();
$DB->getPage("trans_yijipay_record", "*", $condition, 10);

while($r=$DB->fetch_assoc()){
  $lists[] = $r;
}


?>

<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="UTF-8">
	<title>指量转账</title>
	
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />

<link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />      
<script type='text/javascript' src='/static/js/jquery-1.11.1.min.js'></script>
	<style>
.r_con_table td{text-align:center; padding-top:8px; padding-bottom:8px;}
.btn_green{border:none;height: 30px;line-height: 30px;background: url(/static/member/images/global/ok-btn-bg.jpg);  border: none;color: #fff;width: 145px;    border-radius: 5px;    text-align: center;    text-decoration: none;    margin-right: 10px;}
.btn_w_120{width:120px; float:left;}
.control_btn{display:block; height:30px;}
</style>
</head>
<script>

      
var SelectFalse = false; //用于判断是否被选择条件
function Submit()
{
  var chboxValue = [];
  var CheckBox = $('input[name=User_ID[]]:checked');//得到所的复选框

  for(var i = 0; i < CheckBox.length; i++) {
  if(CheckBox[i].checked)//如果有1个被选中时
  {
    SelectFalse = true;
    chboxValue.push(CheckBox[i].value)//将被选择的值追加到
  }
  }

  if(!SelectFalse)
  {
    alert("至少选择一项");
    return false;
  }
  
}


</script>

	<body>
<div id="wrap" style="overflow:hidden; padding:5px;"> 

<div class="control_btn">
      <a href="?type=1" class="btn_green btn_w_120">未转</a> 
      <a href="?type=2" class="btn_green btn_w_120" id="excoutput">已转</a>
</div>

<br>

<form onsubmit="chkSelected()" method = "post">
<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <th width="6%" nowrap="nowrap">序号</th>
            <th width="12%" nowrap="nowrap">登记时间</th>
            <th width="12%" nowrap="nowrap">商城</th>
            <th width="12%" nowrap="nowrap">会员ID</th>
            <th width="12%" nowrap="nowrap">易极付UID</th>
            <th width="8%" nowrap="nowrap">金额(参考)</th>
            <th width="8%" nowrap="nowrap">申请转账时间</th>
            <th width="8%" nowrap="nowrap">确认转账时间</th>
            <th width="8%" nowrap="nowrap">同步状态</th>
           <th width="8%" nowrap="nowrap">转帐状态</th>
          </tr>
        </thead>
        <tbody>
<?php
$SynStatus = [
	'0' => '',
	'-1' => '<span style="color:blue">同步失败</span>',
	'1' => '同步成功'
];
if (count($lists) > 0) {
    foreach($lists as $row) {
?>
    <tr>
    <td>
<?php
if ($row["status"] == 0) { 
?>  
    <input type="checkbox" name="User_ID[]" value="<?php echo $row['User_ID'];?>">
<?php
 }
 ?>    <?php echo $row['ID'];?></td>
            <td nowrap="nowrap"><?php echo $row["Users_ID"] ?></td>
<td><?php echo date('Y-m-d H:i:s', $row["crated_at"])  ?></td>
            <td><?php echo $row["User_ID"] ?></td>
            <td><?php echo $row["Yiji_UserID"];?></td>
            <td><?php echo $row["balance"] ?></td>
            <td><?php echo $row["transTime"] > 0 ? date('Y-m-d H:i:s', $row["transTime"]) : '' ?></td>	
            <td><?php echo $row["confirmTime"] > 0 ? date('Y-m-d H:i:s', $row["transTime"]) : ''  ?></td>
            <td><?php echo $SynStatus[$row["status"]]; ?></td> 
			<td><?php echo $row["status"] == 0 ? '<font color=blue>未转</font>' : '已转' ?></td> 
			</tr>              
<?php
    }
}
?>      
</table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
<?php
if ($status == 0) {
?>
<div style="clear:both;margin-top:20px; text-align:center"><input  type="submit" class="btn_green" onclick="Submit()" value="开始批量转账"></div>
<?php
}
?>
</form>
  </div>
</body>
</html>
