<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(isset($_GET["OrderID"])){
	$OrderID=$_GET["OrderID"];
}else{
	echo '缺少必要的参数';
	exit;
}

if(empty($_SESSION[$UsersID."User_ID"])){
	header("location:/api/".$UsersID."/user/login/");
}

$base_url = base_url();
$cloud_url = base_url().'api/'.$UsersID.'/cloud/';

$rsOrder = $DB->GetRs("shipping_orders","*","where Orders_ID=".$OrderID." and User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Orders_Status=3");

if($owner['id'] != '0'){
	$rsConfig["ShopName"] = $owner['shop_name'];
	$rsConfig["ShopLogo"] = $owner['shop_logo'];
	$cloud_url = $cloud_url.$owner['id'].'/';
};
//print_R($rsOrder);
if(!$rsOrder){
	echo "此订单不存在";
	exit;
}elseif($rsOrder["Is_Commit"]==1){
	echo "此订单已评论，不可重复评论！";
	exit;
}
require_once('../../share.php');
?>
<?php require_once('../top.php');?>
<body>
<div id="shop_page_contents">
  <div id="cover_layer"></div>
  <link href='/static/api/shop/skin/default/css/member.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
  <ul id="member_nav">
    <li><a href="/api/<?php echo $UsersID ?>/cloud/member/status/0/">待付款</a></li>
    <li><a href="/api/<?php echo $UsersID ?>/cloud/member/status/1/">待确认</a></li>
    <li><a href="/api/<?php echo $UsersID ?>/cloud/member/status/2/">已付款</a></li>
    <li><a href="/api/<?php echo $UsersID ?>/cloud/member/status/3/">已发货</a></li>
    <li class="cur"><a href="/api/<?php echo $UsersID ?>/cloud/member/status/4/">已完成</a></li>    
  </ul>  
  <div id="commit">
    <script language="javascript">$(document).ready(shop_obj.commit_init);</script>
    <form action="/api/<?php echo $UsersID ?>/cloud/member/" method="post" id="commit_form">
      <dl>
        <dd> 为卖家打分 <font class="fc_red">*</font><br />
          <select name="Score" class="score_select">
           <option value="5">非常满意</option>
           <option value="4">满意</option>
           <option value="3">一般</option>
           <option value="2">差</option>
           <option value="1">非常差</option>
          </select>
        </dd>
        
        <dd> 评论内容 <br />
          <textarea name="Note" value="" notnull class="score_textarea"></textarea>
        </dd>
        <dt>
          <input type="button" class="submit" value="提交保存" />
          <input type="button" class="back" value="取消" />
        </dt>
      </dl>
      <input type="hidden" name="OrderID" value="<?php echo $OrderID;?>" />
      <input type="hidden" name="action" value="commit" />
    </form>
  </div>
</div>
<?php require_once('../footer.php'); ?>
</body>
</html>