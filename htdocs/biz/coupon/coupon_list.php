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
		$Flag = $DB->Del("user_coupon","Biz_ID=".$_SESSION["BIZ_ID"]." and Coupon_ID=".$_GET["CouponID"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.go(-1);</script>';
		}
	}
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
        <li class="cur"> <a href="coupon_list.php">优惠券管理</a></li>
        <li> <a href="coupon_add.php">添加优惠券</a></li>
        <li> <a href="coupon_list_logs.php">使用记录</a></li>
      </ul>
    </div>
    <div id="coupon_list" class="r_con_wrap">
      <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%"><strong>序号</strong></td>
            <td width="34%"><strong>优惠券名称</strong></td>
            <td width="10%"><strong>可用次数</strong></td>
            <td width="26%"><strong>有效时间</strong></td>
            <td width="10%"><strong>状态</strong></td>
            <td width="10%" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("user_coupon","*","where Biz_ID=".$_SESSION["BIZ_ID"]." order by Coupon_CreateTime desc",$pageSize=10);
		$i=1;
		while($rsCoupon=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsCoupon['Coupon_Subject'] ?></td>
            <td nowrap="nowrap"><?php echo $rsCoupon['Coupon_UsedTimes']==-1?'不限':$rsCoupon['Coupon_UsedTimes'].'次' ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsCoupon["Coupon_StartTime"])."&nbsp;~&nbsp;".date("Y-m-d H:i:s",$rsCoupon["Coupon_EndTime"]) ?></td>
            <?php echo '<td nowrap="nowrap" class="';
			if($rsCoupon["Coupon_StartTime"]>time()){
				echo 'fc_blue">未显示';
			}elseif($rsCoupon["Coupon_EndTime"]<time()){
				echo 'fc_red">已过期';
			}else{
				echo 'status">正常';
			}
			echo '</td>';?>
            <td nowrap="nowrap" class="last"><a href="coupon_edit.php?CouponID=<?php echo $rsCoupon['Coupon_ID'] ?>"><img src="/static/member/images/ico/mod.gif" /></a> <a href="coupon_list.php?action=del&CouponID=<?php echo $rsCoupon['Coupon_ID'] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" /></a></td>
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