<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
$condition = "where Order_Type='shop'";
$fields = $kw = $minprice = $maxprice = '';
$status=-1;
if(isset($_GET["action"])){
	if($_GET["action"]=='search'){
		if(!empty($_GET["kw"])){
			$fields = $_GET["fields"];
			$kw = $_GET["kw"];
			if($_GET["fields"]=='Account'){
				$ids = "'0'";
				$DB->get("users","Users_ID","where Users_Account LIKE '".$kw."'");
				while($r=$DB->fetch_assoc()){
					$ids .= ",'".$r["Users_ID"]."'";
				}
				$condition .= " and Users_ID IN(".$ids.")";
			}else{
				$condition .= " and $fields LIKE '$kw'";
			}
		}
		if(!empty($_GET["status"])){
			if($_GET["status"]>-1){
				$status = $_GET["status"];
				$condition .= " and Order_Status=".$status;
			}
		}
		if(!empty($_GET["minprice"])){
			if($_GET["minprice"]>0){
				$minprice = $_GET["minprice"];
				$condition .= " and Order_TotalPrice>=".$minprice;
			}
		}
		if(!empty($_GET["maxprice"])){
			if($_GET["maxprice"]>0){
				$maxprice = $_GET["maxprice"];
				$condition .= " and Order_TotalPrice<=".$maxprice;
			}
		}
	}
}

$orderby = " order by Order_ID desc";
$_Status=array("待付款","待确认","已付款","已发货","已完成");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="index.php">订单管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="?">
        <input type="hidden" name="action" value="search" />
        <select name="fields">
         <option value="Account"<?php echo $fields=='Account' ? ' selected' : '';?>>商家名称</option>
         <option value="Address_Name"<?php echo $fields=='Address_Name' ? ' selected' : '';?>>购买人</option>
         <option value="Address_Mobile"<?php echo $fields=='Address_Mobile' ? ' selected' : '';?>>购买手机</option>
         <option value="Address_Detailed"<?php echo $fields=='Address_Detailed' ? ' selected' : '';?>>收获地址</option>
        </select>
        <input name="kw" value="<?php echo $kw;?>" type="text" size="30"/>&nbsp;
        价格：<input name="minprice" value="<?php echo $minprice;?>" type="text" size="5" style="text-align:center" /> ~ <input name="maxprice" value="<?php echo $maxprice;?>" type="text" size="5"  style="text-align:center" />&nbsp;
        状态：
        <select name="status">
          <option value="-1">全部</option>
          <?php
           foreach($_Status as $k=>$s){
		  ?>
          <option value="<?php echo $k;?>"<?php echo $status==$k ? ' selected' : '';?>><?php echo $s;?></option>
          <?php }?>
        </select>
        <input type="submit" class="search_btn" value=" 订单搜索 " style="cursor:pointer" />
      </form>
      <div class="b10"></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="15%" nowrap="nowrap">订单号</td>
            <td width="20%" nowrap="nowrap">所属商家</td>
            <td width="18%" nowrap="nowrap">购买人</td>
            <td width="10%" nowrap="nowrap">金额</td>
            <td width="10%" nowrap="nowrap">状态</td>   
            <td width="19%" nowrap="nowrap">购买时间</td>            
            <td width="8%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
			$lists = array();
			$DB->getPage("user_order","*",$condition.$orderby,10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			foreach($lists as $t){
				$company = $DB->GetRs("users","Users_Account","where Users_ID='".$r["Users_ID"]."'");
				$t["Company"] = $company["Users_Account"];
		?>
          <tr>
            <td nowrap="nowrap"><?php echo date("Ymd",$t["Order_CreateTime"]).$t["Order_ID"] ?></td>
            <td nowrap="nowrap"><?php echo $t["Company"] ?></td>
            <td nowrap="nowrap"><?php echo $t["Address_Name"];?><br /><?php echo $t["Address_Mobile"];?></td>
            <td nowrap="nowrap" style="color:blue"><?php echo $t["Order_TotalPrice"] ?></td>
            <td nowrap="nowrap"><?php echo $_Status[$t["Order_Status"]]; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$t["Order_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="view.php?OrderID=<?php echo $t["Order_ID"];?>"><img src="/static/admin/images/ico/view.gif" align="absmiddle" alt="详情" title="详情" /></a></td>
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