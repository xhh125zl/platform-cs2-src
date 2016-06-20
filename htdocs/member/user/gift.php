<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"]))
{
	$action=empty($_GET["action"])?"":$_GET["action"];
	if($action=="del")
	{
		//开始事务定义
		$Flag=true;
		$msg="";
		mysql_query("begin");
		$Flag=$Flag&&$DB->Del("user_gift","Users_ID='".$_SESSION["Users_ID"]."' and Gift_ID=".$_GET["GiftID"]);
		if($Flag){
			mysql_query("commit");
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			mysql_query("roolback");
			echo '<script language="javascript">alert("保存失败");history.go(-1);</script>';
		}
	}
}
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
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a>
          <dl>
            <dd class="first"><a href="lbs.php">一键导航设置</a></dd>
          </dl>
        </li>
        <li class=""> <a href="user_list.php">会员管理</a>
          <dl>
            <dd class="first"><a href="user_level.php">会员等级设置</a></dd>
            <dd class=""><a href="user_profile.php">会员注册资料</a></dd>
            <dd class=""><a href="card_benefits.php">会员权利说明</a></dd>
            <dd class=""><a href="user_list.php">会员管理</a></dd>
          </dl>
        </li>
        <li class=""> <a href="card_config.php">会员卡设置</a></li>
        <li class=""> <a href="coupon_config.php">优惠券</a>
          <dl>
            <dd class="first"><a href="coupon_config.php">优惠券设置</a></dd>
            <dd class=""><a href="coupon_list.php">优惠券管理</a></dd>
            <dd class=""><a href="coupon_list_logs.php">优惠券使用记录</a></dd>
          </dl>
        </li>
        <li class="cur"> <a href="gift_orders.php">礼品兑换</a>
          <dl>
            <dd class="first"><a href="gift.php">礼品管理</a></dd>
            <dd class=""><a href="gift_orders.php">兑换订单管理</a></dd>
          </dl>
        </li>
        <li class=""><a href="business_password.php">商家密码设置</a></li>
        <li class=""><a href="message.php">消息发布管理</a></li>
      </ul>
    </div>
    <div id="gift" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(user_obj.gift_list_init);</script>
      <div class="control_btn"> <a href="gift_add.php" class="btn_green btn_w_120">添加礼品</a> <a href="#search" class="btn_green btn_w_120">礼品搜索</a> </div>
      <form class="search" method="get" action="">
        关键词：
        <input type="text" name="Keyword" value="" class="form_input" size="15" />
        <input type="submit" class="search_btn" value="搜索" />
        <input type="hidden" name="m" value="user" />
        <input type="hidden" name="a" value="gift" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="20%" nowrap="nowrap">名称</td>
            <td width="20%" nowrap="nowrap">图片</td>
            <td width="10%" nowrap="nowrap">兑换所需积分</td>
            <td width="10%" nowrap="nowrap">剩余数量</td>
            <td width="10%" nowrap="nowrap">需要物流</td>
            <td width="10%" nowrap="nowrap">时间</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("user_gift","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Gift_CreateTime desc",$pageSize=10);
		$i=1;
		while($rsGift=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsGift['Gift_Name'] ?></td>
            <td nowrap="nowrap"><a href="<?php echo $rsGift['Gift_ImgPath'] ?>" target="_blank"><img src="<?php echo $rsGift['Gift_ImgPath'] ?>" class="img" /></a></td>
            <td nowrap="nowrap"><?php echo $rsGift['Gift_Integral'] ?></td>
            <td nowrap="nowrap"><?php echo $rsGift['Gift_Qty'] ?></td>
            <td nowrap="nowrap"><?php echo empty($rsGift['Gift_Shipping'])?'否':'是' ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsGift["Gift_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="gift_edit.php?GiftID=<?php echo $rsGift['Gift_ID'] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="gift.php?action=del&GiftID=<?php echo $rsGift['Gift_ID'] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
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