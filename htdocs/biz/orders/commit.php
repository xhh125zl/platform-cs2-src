<?php
require_once('../global.php');

if(isset($_GET["action"])){
	if($_GET["action"]=="check"){
		$DB->Set("user_order_commit","Status=1","where Biz_ID='".$_SESSION["BIZ_ID"]."' and Item_ID=".$_GET["ItemID"]);
		echo '<script language="javascript">alert("已通过审核");window.location.href="commit.php";</script>';
		exit;
	}elseif($_GET["action"]=="uncheck"){
		$DB->Set("user_order_commit","Status=0","where Biz_ID='".$_SESSION["BIZ_ID"]."' and Item_ID=".$_GET["ItemID"]);
		echo '<script language="javascript">alert("已取消审核");window.location.href="commit.php";</script>';
		exit;
	}
	
}

function get_title($itemid){
	global $DB;
	$r = $DB->GetRs("shop_products","*","where Products_ID='".$itemid."'");
	return $r['Products_Name'];
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
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/weicbd.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/biz/js/weicbd.js'></script>
    <div class="r_nav">
      <ul>
        <li><a href="orders.php">订单列表</a></li>
        <li><a href="virtual_orders.php">消费认证</a></li>
        <li><a href="backorders.php">退货列表</a></li>
        <li class="cur"><a href="commit.php">评论管理</a></li>
      </ul>
    </div>
    <div id="reviews" class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table" id="review_list">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="20%" nowrap="nowrap">评论产品</td>
            <td width="10%" nowrap="nowrap">分数</td>
            <td width="30%" nowrap="nowrap">评论内容</td>
            <td width="12%" nowrap="nowrap">时间</td>
            <td width="10%" class="last">状态</td>
          </tr>
        </thead>
        <tbody>
         <?php
		  $lists = array();
		  $DB->getPage("user_order_commit","*","where Biz_ID='".$_SESSION["BIZ_ID"]."' and MID='shop' order by CreateTime desc",10);
		  $_Status=array('<font style="color:red">待审核</font>','<font style="color:blue">已审核</font>');
		  $_Check = array('check','uncheck');
		  $_Title = array('点击通过审核','点击取消审核');
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$rsCommit){
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $k+1; ?></td>
            <td nowrap="nowrap"><?php echo get_title($rsCommit["Product_ID"]); ?></td>
			<td nowrap="nowrap"><?php echo $rsCommit["Score"];?></td>
            <td><?php echo $rsCommit["Note"] ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsCommit["CreateTime"]) ?></td>
            <td class="last"><a href="?action=<?php echo $_Check[$rsCommit["Status"]];?>&ItemID=<?php echo $rsCommit["Item_ID"] ?>" title="<?php echo $_Title[$rsCommit["Status"]];?>"><?php echo $_Status[$rsCommit["Status"]] ?></a></td>
           </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      
    </div>
  </div>
</div>
</body>
</html>