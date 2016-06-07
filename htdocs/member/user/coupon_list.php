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
		$Del=$DB->Del("user_coupon","Users_ID='".$_SESSION["Users_ID"]."' and Coupon_ID=".$_GET["CouponID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("user_coupon_record","Users_ID='".$_SESSION["Users_ID"]."' and Coupon_ID=".$_GET["CouponID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("wechat_keyword_reply","Users_ID='".$_SESSION["Users_ID"]."' and Reply_Display=0 and Reply_Table='reserve' and Reply_TableID=".$_GET["CouponID"]);
		$Flag=$Flag&&$Del;
		$Del=$DB->Del("wechat_material","Users_ID='".$_SESSION["Users_ID"]."' and Material_Display=0 and Material_Table='reserve' and Material_TableID=".$_GET["CouponID"]);
		$Flag=$Flag&&$Del;
		if($Flag){
			mysql_query("commit");
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			mysql_query("roolback");
			echo '<script language="javascript">alert("保存失败");history.go(-1);</script>';
		}
	}
}
$rsConfig=$DB->GetRs("user_config","UserLevel","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsConfig)){
	header("location:config.php");
}else{
	if(empty($rsConfig['UserLevel'])){
		$UserLevel[0]=array(
			"Name"=>"普通会员",
			"UpIntegral"=>0,
			"ImgPath"=>""
		);
		$Data=array(
			"UserLevel"=>json_encode($UserLevel,JSON_UNESCAPED_UNICODE)
		);
		$DB->Set("user_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	}else{
		$UserLevel=json_decode($rsConfig['UserLevel'],true);
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
        <li class="cur"> <a href="coupon_config.php">优惠券</a>
          <dl>
            <dd class="first"><a href="coupon_config.php">优惠券设置</a></dd>
            <dd class=""><a href="coupon_list.php">优惠券管理</a></dd>
            <dd class=""><a href="coupon_list_logs.php">优惠券使用记录</a></dd>
          </dl>
        </li>
        <li class=""> <a href="gift_orders.php">礼品兑换</a>
          <dl>
            <dd class="first"><a href="gift.php">礼品管理</a></dd>
            <dd class=""><a href="gift_orders.php">兑换订单管理</a></dd>
          </dl>
        </li>
        <li class=""><a href="business_password.php">商家密码设置</a></li>
        <li class=""><a href="message.php">消息发布管理</a></li>
      </ul>
    </div>
    <div id="coupon_list" class="r_con_wrap">
      <div class="control_btn"><a href="coupon_add.php" class="btn_green btn_w_120">添加优惠券</a></div>
      <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%"><strong>序号</strong></td>
            <td width="16%"><strong>优惠券名称</strong></td>
            <td width="10%"><strong>触发关键词</strong></td>
            <td width="10%"><strong>会员等级</strong></td>
            <td width="10%"><strong>可用次数</strong></td>
            <td width="12%"><strong>有效时间</strong></td>
            <td width="8%"><strong>状态</strong></td>
            <td width="8%" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("user_coupon","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Coupon_CreateTime desc",$pageSize=10);
		$i=1;
		while($rsCoupon=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsCoupon['Coupon_Subject'] ?></td>
            <td nowrap="nowrap">【<?php echo str_replace("\n","】【",$rsCoupon['Coupon_Keywords']) ?>】</td>
            <td><?php echo $rsCoupon["Coupon_UserLevel"]==-1?'不限':$UserLevel[$rsCoupon["Coupon_UserLevel"]]["Name"] ?></td>
            <td nowrap="nowrap"><?php echo $rsCoupon['Coupon_UsedTimes']==-1?'不限':$rsCoupon['Coupon_UsedTimes'].'次' ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsCoupon["Coupon_StartTime"])."<br>~<br>".date("Y-m-d H:i:s",$rsCoupon["Coupon_EndTime"]) ?></td>
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