<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

//获取帮助此用户的记录
$condition = "where Users_ID='".$UsersID."' and Provider_ID= '".$_SESSION[$UsersID.'User_ID']."' group by User_ID";
$fields = 'User_ID,count(Product_ID) as num,sum(Bonous) as total';

$rsHelper = $DB->Get('shop_distribute_record',$fields,$condition);
$Helpers = $DB->toArray($rsHelper);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["ShopName"] ?>-分销中心</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/style.css' rel='stylesheet' type='text/css' />
<link href='/static/api/dist/css/font-awesome.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/css/distribute.css' rel='stylesheet' type='text/css' />

<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/inputFormat.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">
var base_url = '<?=$base_url?>';

$(document).ready(shop_obj.distribute_init);
</script>
</head>

<body>

<header class="bar bar-nav">
  <a  href="javascript:history.back()"class="icon icon-2x icon-chevron-left grey pull-left"></a>
  <a  href="<?=$base_url?>api/<?=$UsersID?>/shop/distribute/" class="icon icon-2x icon-sitemap grey pull-right"></a>
  <h1 class="title">分销订单</h1>
</header>

<div id="page_contents">

  <div id="cover_layer"></div>
  

  <div id="friend-list">
  <table class="bordered friend-table">
    	<thead>

    		<tr>   
        		<th>用户名</th>
        		<th>用户头像</th>
        		<th>分销次数</th>
    			<th>奖金总和</th>
    		</tr>
    </thead>
    		<?php foreach($Helpers as $key=>$item):?>
    		<tr>
    			<td><?=$item['User_ID']?></td>
    			<td></td>
    			<td><?=$item['num']?>次</td>
    			<td><span class="fc_red">&yen;<?=$item['total']?></span></td>
    		</tr>
    	    <?php endforeach;?>
	</table>
  </div>
  
  
    
</div> 	
<div id="footer_points"></div>
<footer id="footer">
  <ul>
    <li class="category"><a href="#">产品分类</a></li>
    <li class="cart"><a href="/api/<?php echo $UsersID ?>/shop/cart/">购物车</a></li>
    <li class="member"><a href="/api/<?php echo $UsersID ?>/shop/member/">会员中心</a></li>
    <li class="home"><a href="/api/<?php echo $UsersID ?>/shop/">商城首页</a></li>
  </ul>
</footer>
<div id="category">
  <div class="close"></div>
  <dl>
    <?php
		$DB->get("shop_category","Category_Name,Category_ID","where Users_ID='".$UsersID."' and Category_ParentID=0 order by Category_Index asc");
		$ParentCategory=array();
		$i=1;
		while($rsPCategory=$DB->fetch_assoc()){
			$ParentCategory[$i]=$rsPCategory;
			$i++;
		}
		foreach($ParentCategory as $key=>$value){
			echo '<dt><a href="/api/'.$UsersID.'/shop/category/'.$value["Category_ID"].'/?OpenID='.$_SESSION[$UsersID."OpenID"].'">'.$value["Category_Name"].'</a></dt>';
			$DB->get("shop_category","Category_Name,Category_ID","where Users_ID='".$UsersID."' and Category_ParentID=".$value["Category_ID"]." order by Category_Index asc");
			while($rsCategory=$DB->fetch_assoc()){
				echo '<dd><a href="/api/'.$UsersID.'/shop/category/'.$rsCategory["Category_ID"].'/?OpenID='.$_SESSION[$UsersID."OpenID"].'">&gt; '.$rsCategory["Category_Name"].'</a></dd>';
			}
		}
	?>
  </dl>
</div>

<?php
$KfIco = '';
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1");
$KfIco = empty($kfConfig["KF_Icon"]) ? '' : $kfConfig["KF_Icon"];
?>
<?php if(!empty($kfConfig)){?>
<script language='javascript'>var KfIco='<?php echo $KfIco;?>'; var OpenId='<?php echo $_SESSION[$UsersID."OpenID"];?>'; var UsersID='<?php echo $UsersID;?>'; </script>
<script type='text/javascript' src='/kf/js/webchat.js?t=<?php echo time();?>'></script>
<?php }?>
</body>
</html>