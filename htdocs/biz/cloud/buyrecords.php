<?php  
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/update/common.php');

if (isset($_GET["action"])) {
    if ($_GET["action"] == "del") {
        $Flag = $DB->Del("cloud_products_detail", "Users_ID='{$UsersID}' AND Cloud_Detail_ID=" . $_GET["DetailID"]);
        if ($Flag) {
            echo '<script language="javascript">alert("删除成功");window.location="' . $_SERVER['HTTP_REFERER'] . '";</script>';
        } else {
            echo '<script language="javascript">alert("删除失败");history.back();</script>';
        }
        exit();
    }
}
if (! empty($_GET['DetailID'])) {
    $DetailID = $_GET['DetailID'];
} else {
    exit('缺少参数');
}
$rsDetail = $DB->GetRS('cloud_products_detail', '*', 'WHERE Cloud_Detail_ID=' . $DetailID);
$rsUser = $DB->GetRS('user', '*', 'where User_ID=' . $rsDetail['User_ID']);
$ProductsID = $rsDetail['Products_ID'];
$rsProducts = $DB->GetRs("cloud_Products", "*", "WHERE Users_ID='{$UsersID}' AND Biz_ID={$BizID} AND Products_ID=" . $ProductsID);
$cate = $DB->GetRs("cloud_category", "*", "WHERE Category_ID='" . $rsProducts['Products_Category'] . "'");
$cloud_record = $DB->GetRs("cloud_record", "count(*) AS total", "WHERE Products_ID={$ProductsID} AND qishu={$rsDetail['qishu']} AND Biz_ID={$BizID}");
$cloud_record['total'] = isset($cloud_record['total']) ? $cloud_record['total'] : 0;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>购买记录</title>
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
		<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
		<script type='text/javascript' src='/static/member/js/shop.js'></script>
		<?php include 'top.php';  ?>
		<div id="products" class="r_con_wrap">
		    <style>
			.tips_info {
				background: #f7f7f7 none repeat scroll 0 0;
				border: 1px solid #ddd;
				border-radius: 5px;
				font-size: 12px;
				line-height: 22px;
				margin-bottom: 10px;
				padding: 10px;
			}
			</style>
			<div class="tips_info">
			商品&nbsp;&nbsp;ID：<font style="color:#F00; font-size:12px;"><?php echo $ProductsID;?></font><br />
			商品期数：<font style="color:#F00; font-size:12px;">第(<?php echo $rsDetail['qishu'];?>)期</font><br />
			商品标题：<font style="color:#F00; font-size:12px;"><?php echo $rsProducts['Products_Name'];?></font><br />
			商品信息：<font style="color:#F00; font-size:12px;">总价格:<?php echo $rsProducts['Products_PriceY'];?>&nbsp;&nbsp;
			单价:<?php echo $rsProducts['Products_PriceX'];?>&nbsp;&nbsp;
			总需人次:<?php echo $rsProducts['zongrenci'];?>&nbsp;&nbsp;
			参与人次:<?php echo $cloud_record['total'];?>&nbsp;&nbsp;
			剩余人次:<?php echo $rsProducts['zongrenci']-$cloud_record['total'];?></font> <br />
			本期得主：<font style="color:#F00; font-size:12px;"><?php echo $rsUser['User_NickName'];?></font><br />
			幸&nbsp;运&nbsp;码：<font style="color:#F00; font-size:12px;"><?php echo $rsDetail['Luck_Sn'];?></font><br />
			</div>
			<script language="javascript">$(document).ready(shop_obj.products_list_init);</script>
			<table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
				<thead>
					<tr>
						<td width="10%" nowrap="nowrap">购买时间</td>
						<td width="10%" nowrap="nowrap">购买次数</td>
						<td width="10%" nowrap="nowrap">购买人</td>
						<td width="20%" nowrap="nowrap">来自</td>
						<td width="40%" nowrap="nowrap">所购云码</td>
					</tr>
				</thead>
				<tbody>
					<?php 
					    $sql = "SELECT o.Order_ID,o.Order_CreateTime,o.Order_CartList,r.Cloud_Code,r.User_ID,u.User_NickName,u.User_Province,u.User_City 
					    FROM user_order o RIGHT JOIN cloud_record r ON o.Order_ID = r.Order_ID LEFT JOIN user u ON r.User_ID = u.User_ID 
					    WHERE r.Products_ID={$rsProducts['Products_ID']} AND r.Biz_ID={$BizID} AND r.qishu=".$rsDetail['qishu'];
					    
						$res = $DB->query($sql);
						$lists = array();
						while($rs = $DB->fetch_assoc($res)){		
							$lists[$rs["Order_ID"]][] = $rs;	
						}
						if(!empty($lists)){
					        foreach($lists as $order_id=>$Cloud_Detail){
								foreach($Cloud_Detail as $k => $v){
									$codes[] = $v['Cloud_Code'];
									$username = $v['User_NickName'];
									$province = $v['User_Province'];
									$city = $v['User_City'];
									$time = $v['Order_CreateTime'];
								}
					?>
					<tr>
						<td nowrap="nowrap"><?php echo date('Y-m-d H:i:s', $time);?></td>
						<td><?php echo count($codes); ?></td>
						<td><?php echo $username; ?></td>
						<td><?php echo $province.$city; ?></td>
						<td nowrap="nowrap">
						<div style="white-space:normal; word-break:break-all;">
						<?php foreach($codes as $code){?>
						<?php echo "<span>".$code."</span>"; ?>
						<?php }?>
						</div>
						</td>
					</tr>
					    <?php }?>
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