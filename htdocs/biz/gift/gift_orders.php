<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["BIZ_ID"]))
{
	header("location:/biz/login.php");
}
if(isset($_GET["action"]))
{
	$action=empty($_GET["action"])?"":$_GET["action"];
	if($action=="del")
	{
		$Flag=$DB->Del("user_gift_orders","Biz_ID=".$_SESSION["BIZ_ID"]." and Orders_ID=".$_GET["OrderId"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("保存失败");history.go(-1);</script>';
		}
	}
}
function get_biz($bizid){
	global $DB;
	$r = $DB->GetRs("weicbd_biz","*","where Biz_ID='".$bizid."'");
	$name = $r ? $r['Biz_Name'] : "全站通用"; 
	return $name;
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
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""> <a href="gift.php">礼品管理</a></li>
        <li> <a href="gift_add.php">添加礼品</a></li>
        <li class="cur"><a href="gift_orders.php">兑换订单管理</a></li>
      </ul>
    </div>
    <div id="gift_orders" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(user_obj.gift_orders_init);</script>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="20%" nowrap="nowrap">名称</td>
            <td width="20%" nowrap="nowrap">图片</td>
            <td width="10%" nowrap="nowrap">姓名</td>
            <td width="10%" nowrap="nowrap">手机</td>
            <td width="10%" nowrap="nowrap">时间</td>
            <td width="10%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php $DB->getPage("user_gift_orders","*","where Biz_ID=".$_SESSION["BIZ_ID"]." order by Orders_CreateTime desc",$pageSize=10);
		$i=1;
		$lists = array();
		while($rsGift=$DB->fetch_assoc()){
			$lists[] = $rsGift;
		}
		foreach($lists as $k=>$v){
			$gift = $DB->GetRs("user_gift","*","where Gift_ID=".$v['Gift_ID']."");
			$v['Gift_Name'] = $gift['Gift_Name'];
			$v['Gift_ImgPath'] = $gift['Gift_ImgPath'];
			?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $v['Gift_Name'];?></td>
            <td nowrap="nowrap"><img src="<?php echo $v['Gift_ImgPath']?>" class="img" /></a></td>
            <td nowrap="nowrap"><?php echo $v['Address_Name'];?></td>
            <td nowrap="nowrap"><?php echo $v['Address_Mobile'];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$v["Orders_CreateTime"]);?></td>
            <td nowrap="nowrap"> <?php echo $v['Orders_Status'] ? '已领取' : '未领取';?> </td>
            <td class="last" nowrap="nowrap"><a href="gift_orders_view.php?OrderId=<?php echo $v['Orders_ID']?>&page=<?php echo $DB->pageNo;?>"><img src="/static/member/images/ico/view.gif" align="absmiddle" alt="查看" /></a> <a href="?action=del&OrderId=<?php echo $v['Orders_ID']?>&page=<?php echo $DB->pageNo;?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
          </tr>
          
          <?php $i++;
		  }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>