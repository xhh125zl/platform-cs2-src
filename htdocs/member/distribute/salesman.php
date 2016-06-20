<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

$condition = "where Users_ID='".$_SESSION["Users_ID"]."' and Is_Salesman=1";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and ".$_GET["Fields"]." like '%".$_GET["Keyword"]."%'";
		}
	}
}

$condition .= " order by Account_ID desc";

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Set("distribute_account",array("Is_Salesman"=>0,'Salesman_Deltime'=>time()),"where Users_ID='".$_SESSION["Users_ID"]."' and Account_ID=".$_GET["AccountID"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="salesman.php";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}
$user_tel = array();
$DB->GET('user','User_ID,User_Mobile',"where Users_ID = '".$_SESSION['Users_ID']."'");
while ($row = $DB->fetch_assoc()) {
     $user_tel[$row['User_ID']] = $row['User_Mobile'];
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
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div class="r_nav">
      <ul>
      	<li><a href="salesman_config.php">创始人设置</a></li>
        <li class="cur"><a href="salesman.php">创始人列表</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <script language="javascript">
		$(document).ready(shop_obj.orders_init);
	</script>
    <div id="orders" class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="?">
        <select name="Fields">
        	<option value="User_Name">昵称</option>
            <option value="Real_Name">姓名</option>
        </select>
        <input type="text" name="Keyword" value="" class="form_input" size="15" />&nbsp;
		<input type="hidden" value="1" name="search" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
	  <form name="form1" method="post" action="?">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table" id="order_list">
        <thead>
          <tr>
            <td width="12%" nowrap="nowrap">序号</td>
            <td width="18%" nowrap="nowrap">昵称</td>
            <td width="18" nowrap="nowrap">手机号</td>
            <td width="16%" nowrap="nowrap">商家</td>
            <td width="16%" nowrap="nowrap">总提成</td>
            <td width="16%" nowrap="nowrap">可提现提成</td>
            <td width="14%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
		  $i=0;
		  $lists = array();
		  $DB->getPage("distribute_account","*",$condition,10);
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$item){
		  	$i++;
			$r = $DB->GetRs("biz","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."' and Invitation_Code='".$item["Invitation_Code"]."'");
			$item["company"] = $r["num"];
			$r = $DB->GetRs("distribute_sales_record","SUM(Sales_Money) as money","where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$item["User_ID"]);
			$item["sales"] = $r["money"];
		?>


          <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo !empty($item["Real_Name"])?$item["Real_Name"]:'暂无昵称'; ?></td>
            <td nowrap="nowrap"><?php echo !empty($user_tel[$item["User_ID"]])?$user_tel[$item["User_ID"]]:'暂无手机号';?></td>
            <td nowrap="nowrap"><?php echo $item["company"]; ?></td>
            <td><?php echo $item["sales"]; ?></td>
            <td><?php echo $item["Salesman_Income"]; ?></td>
            <td class="last" nowrap="nowrap"><a href="?action=del&AccountID=<?php echo $item["Account_ID"] ?>" title="删除"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php $i++;}?>
        </tbody>
      </table>
	  </form>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>