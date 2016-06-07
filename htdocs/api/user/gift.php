<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$TypeID=empty($_GET["TypeID"])?0:$_GET["TypeID"];	
if(isset($_GET['OpenID'])){
	$_SESSION[$UsersID.'OpenID']=$_GET['OpenID'];
	header("location:/api/".$UsersID."/user/gift/".(empty($TypeID)?'':$TypeID.'/')."?wxref=mp.weixin.qq.com");
	exit;
}else{
	if(empty($_SESSION[$UsersID.'OpenID'])){
		$_SESSION[$UsersID.'OpenID']=session_id();
	}
}
if(!strpos($_SERVER['REQUEST_URI'],"mp.weixin.qq.com")){
	header("location:?wxref=mp.weixin.qq.com");
}
$rsConfig=$DB->GetRs("user_config","*","where Users_ID='".$UsersID."'");
if(isset($_SESSION[$UsersID."User_ID"])){
	$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
}else{
	$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/user/gift/";
	header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/user.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/user.js'></script>
</head>

<body>
<div class="pop_form">
  <form id="gift_form">
    <h1>积分兑换礼品</h1>
    <div class="input integral">兑换本礼品需要<span></span>积分</div>
    <div class="address">
      <h1>联系人信息</h1>
      <ul>
      <?php $DB->get("user_address","*","where Users_ID='".$UsersID."' and User_ID='".$_SESSION[$UsersID."User_ID"]."'");
		$i=0;
		while($rsAddress=$DB->fetch_assoc()){
			echo '<li> <span class="lbar">
          <input type="radio" name="AddressID" value="'.$rsAddress["Address_ID"].'" id="address-'.$rsAddress["Address_ID"].'"'.($i==0?" checked":"").' />
          </span> <span class="rbar">
          <label for="address-'.$rsAddress["Address_ID"].'">'.$rsAddress["Address_Province"].$rsAddress["Address_City"].$rsAddress["Address_Area"].$rsAddress["Address_Detailed"].'【'.$rsAddress["Address_Name"].'，'.$rsAddress["Address_Mobile"].'】</label>
          </span> </li>
        <li>';
			$i++;
		}?>
        
          <input type="radio" name="AddressID" id="new-address" value="0"<?php echo $i==0?" checked":"" ?> />
          <label for="new-address">使用新的联系人信息</label>
        </li>
      </ul>
      <dl>
        <dd>
          <input type="text" name="Name" value="" placeholder="姓名" notnull />
        </dd>
        <dd>
          <input type="text" name="Mobile" value="" pattern="[0-9]*" placeholder="手机" notnull />
        </dd>
        <dd>
          <select name="Province" notnull>
          </select>
          <select name="City" notnull>
          </select>
          <select name="Area" notnull>
          </select>
          <script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script> 
          <script language="javascript">new PCAS('Province', 'City', 'Area', '', '', '');</script> 
        </dd>
        <dd>
          <input type="text" name="Detailed" value="" placeholder="详细地址" notnull />
        </dd>
      </dl>
    </div>
    <div class="btn">
      <input type="button" class="submit" value="确认兑换" />
      <input type="button" class="cancel" value="取 消" />
      <div class="clear"></div>
    </div>
  </form>
</div>
<script language="javascript">$(document).ready(user_obj.gift_init);</script>
<div id="gift">
  <div class="t_list"> <a href="/api/<?php echo $UsersID ?>/user/gift/" class="<?php echo $TypeID==0?'c':'' ?>">我兑换的礼品</a> <a href="/api/<?php echo $UsersID ?>/user/gift/1/" class="<?php echo $TypeID==1?'c':'' ?>">积分兑换礼品</a> </div>
  <?php if($TypeID==0){
	  $DB->get("user_gift`,`user_gift_orders","user_gift.Gift_Name,user_gift.Gift_ImgPath,user_gift.Gift_Shipping,user_gift.Gift_BriefDescription,user_gift_orders.Orders_ID,user_gift_orders.Orders_Status,user_gift_orders.Orders_Shipping,user_gift_orders.Orders_ShippingID,user_gift_orders.Orders_FinishTime","where user_gift.Gift_ID=user_gift_orders.Gift_ID and user_gift.Users_ID='".$UsersID."' and user_gift_orders.User_ID=".$_SESSION[$UsersID."User_ID"]." order by user_gift_orders.Orders_Status asc,user_gift_orders.Orders_ID desc");
	  while($rsGift=$DB->fetch_assoc()){
	  echo '<div class="item">
    <h1>【'.$rsGift['Gift_Name'].'】</h1>
    <div class="d"><img src="'.$rsGift['Gift_ImgPath'].'" /></div>
    <h2>'.(empty($rsGift['Gift_Shipping'])?(empty($rsGift['Orders_Status'])?'未领取, 兑换':'已领取, 领取'):(empty($rsGift['Orders_Status'])?'未发货, 兑换':'已发货, 发货')).'时间: '.date("Y-m-d H:i:s",$rsGift['Orders_FinishTime']).'</h2>
    <h3>'.$rsGift['Gift_BriefDescription'].'</h3>
  </div>';
	  }
  }else{
	  $DB->query("SELECT Gift_ID,Gift_Name,Gift_ImgPath,Gift_Shipping,Gift_Integral,Gift_Qty,Gift_BriefDescription FROM user_gift WHERE Users_ID='".$UsersID."' and Gift_Qty>0 order by Gift_MyOrder asc");
	  while($rsGift=$DB->fetch_assoc()){
		  echo '<div class="item">
    <h1>【'.$rsGift['Gift_Name'].'】</h1>
    <div class="p"> <img src="'.$rsGift['Gift_ImgPath'].'" />
      <div class="get" GiftID="'.$rsGift['Gift_ID'].'" Shipping="'.$rsGift['Gift_Shipping'].'" Integral="'.$rsGift['Gift_Integral'].'">兑换</div>
    </div>
    <h2>兑换需<span>'.$rsGift['Gift_Integral'].'</span>积分，还剩<span>'.$rsGift['Gift_Qty'].'</span>件</h2>
    <h3>'.$rsGift['Gift_BriefDescription'].'</h3>
  </div>';
	  }
  }
  ?>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>