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
if (! empty($_GET['ProductsID'])) {
    $ProductsID = $_GET['ProductsID'];
} else {
    exit('缺少参数');
}
$rsProducts = $DB->GetRs("cloud_Products", "*", "WHERE Users_ID='{$UsersID}' AND Products_ID=" . $ProductsID);
$cate = $DB->GetRs("cloud_category", "*", "WHERE Category_ID='" . $rsProducts['Products_Category'] . "'");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
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
    <?php include "top.php"; ?>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(shop_obj.products_list_init);</script>

      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="15%" nowrap="nowrap">序号</td>
            <td width="25%" nowrap="nowrap">产品名称</td>
			<td width="10%" nowrap="nowrap" style="display:none;">分销佣金</td>
            <td width="10%" nowrap="nowrap" style="display:none;">佣金/爵位比</td>
            <td width="10%" nowrap="nowrap">云购价格</td>
            <td width="10%" nowrap="nowrap">期数</td>
			<td width="15%" nowrap="nowrap">揭晓时间</td>
            <td width="25%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $lists = array();
		  $condition = "WHERE Users_ID='{$UsersID}' AND Products_ID=".$ProductsID;
		  $DB->getPage("cloud_products_detail","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$Cloud_Detail){
	      ?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $Cloud_Detail["Cloud_Detail_ID"] ?></td>
            <td><?php echo $rsProducts["Products_Name"] ?></td>
            <td style="display:none;">
			<?php 
				$distribute_list = json_decode($Cloud_Detail["Products_Distributes"],true);
				$dis_config = dis_config($UsersID);
				$arr = array('一','二','三','四','五','六','七','八','九','十');
				$level =  $dis_config['Dis_Self_Bonus']?$dis_config['Dis_Level']+1:$dis_config['Dis_Level'];
				for($i=0;$i<$level;$i++){?>
				<?php echo $arr[$i];?>级&nbsp;&nbsp;%<?=!empty($distribute_list[$i])?$distribute_list[$i]:0?><br/>
				<?php }?>
            </td>
            <td style="display:none;">
            <?php echo $Cloud_Detail["commission_ratio"].'/'.(100-$Cloud_Detail["commission_ratio"]);?>
			</td>
            <td nowrap="nowrap">￥<?php echo $Cloud_Detail["Products_PriceX"] ?></td>
            <td nowrap="nowrap">第&nbsp;<b><?php echo $Cloud_Detail["qishu"] ?></b>&nbsp;期</td>
            <td nowrap="nowrap">
			<?php
				if(strpos($Cloud_Detail["Products_End_Time"], '.')){
					list($usec, $sec) = explode('.', $Cloud_Detail["Products_End_Time"]);
					$date = date('Y-m-d H:i:s', $usec);
				}else{
					$date = date('Y-m-d H:i:s', $Cloud_Detail["Products_End_Time"]);
					$sec = 0;
				}
			?>
			<?php echo $date.'.'.$sec;?> </dd>
			</td>
            <td class="last" nowrap="nowrap">
			    <a href="buyrecords.php?DetailID=<?php echo $Cloud_Detail["Cloud_Detail_ID"]?>">[购买详细]</a>				
			</td>
          </tr>
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