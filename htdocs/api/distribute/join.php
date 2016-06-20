<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

$base_url = base_url();
$shop_url = shop_url();

if(isset($_GET["UsersID"])){
  $UsersID = $_GET["UsersID"];
}else{
  echo '缺少必要的参数';
  exit;
}

//商城配置信息
$rsConfig = shop_config($UsersID);
//分销相关设置
$dis_config = dis_config($UsersID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);

$is_login = 1;
$owner = get_owner($rsConfig,$UsersID);
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/library/wechatuser.php';
$owner = get_owner($rsConfig,$UsersID);

//分销级别处理文件
include($_SERVER["DOCUMENT_ROOT"].'/api/distribute/distribute.php');

if($distribute_flag){
	header("location:/api/" . $UsersID . "/distribute/");
	exit;
}
$arr_level_name = array('一','二','三','四','五','六','七','八','九','十');
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>成为分销商</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/distribute/css/style.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/distribute/css/apply.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/distribute/js/apply.js?t=<?php echo time();?>'></script>
<script language="javascript">
var UsersID = "<?=$UsersID?>";
var ajax_url  = "<?=distribute_url()?>ajax/";
$(document).ready(apply_obj.apply_init);
</script>
</head>

<body>

<div class="apply_head"><img  width="100%" src="<?php echo $rsConfig["ApplyBanner"] ? $rsConfig["ApplyBanner"] : '/static/api/distribute/images/apply_distribute.png';?>" /></div>
<?php if($rsConfig['Distribute_Type']==0){//直接购买?>
	<form id="apply_buy" class="distribute_type_0">
    <div class="level_list">
    	<h2>请选择级别</h2>
    	<ul>
        	<?php
			$i=0;
			$first_price = 0;
			$first_levelid = 0;
            foreach($dis_level as $id=>$value){
				if($value['Level_LimitType']<>0){
					$value['Level_LimitValue'] = 0;
				}else{
					$value['Level_LimitValue'] = number_format($value['Level_LimitValue'],2,'.','');
				}
			?>
            <?php
            if(!empty($value['Level_LimitValue'])){
				$i++;
				if($i==1){
					$first_price = $value['Level_LimitValue'];
					$first_levelid = $value['Level_ID'];
				}
			?>
            
        	<li lid="<?php echo $value['Level_ID'];?>"<?php echo $i==1 ? ' class="cur" style="border-top:none"' : ''?>><img src="<?php echo $value['Level_ImgPath'];?>"><strong><?php echo $value['Level_Name'];?></strong><b>&yen;<?php echo $value['Level_LimitValue'];?></b></li>
            <?php }?>
            <?php }?>
        </ul>
    </div>
    <div class="level_param">
    	<ul>
        	<li><label>姓名</label><input name="Name" type="text" notnull></li>
            <li><label>手机</label><input name="Mobile" type="text" notnull></li>
            <li><label>微信号</label><input name="WeixinID" type="text" notnull></li>
            <li><label>详细地址</label><input name="Detail" type="text" notnull></li>
        </ul>
    </div>
	<input type="hidden" name="agree" value="<?php echo $rsConfig["Distribute_AgreementOpen"];?>" />
	<?php if($rsConfig["Distribute_AgreementOpen"]==1){?>
	<div class="agreement"><input type="checkbox" name="agreement" value="1" checked="true"> 同意<a href="<?php echo distribute_url('agreement/');?>" style="color:#4F93CE">《<?php echo htmlspecialchars_decode($rsConfig["Distribute_AgreementTitle"],ENT_QUOTES);?>》</a></div>
	<?php }?>
    <div class="level_total">总计：<span>&yen; <?php echo $first_price;?></span></div>
    <?php if($first_price>0){?>
    <div class="level_submit">
    <input type="hidden" name="action" value="deal_level_order" />
    <input name="LevelID" type="hidden" value="<?php echo $first_levelid;?>" />
    <div class="submit_btn">确定</div>
    </div>
    <?php $rsPay = $DB->GetRs("users_payconfig","*","where Users_ID='".$UsersID."'");?>
    <div class="pay_select_bg"></div>
    <div class="pay_select_list" id="">
        <h2><span>[×]</span>请选择支付方式</h2>
		<h3>支付总额：<span></span></h3>
        <?php if(!empty($rsPay["PaymentWxpayEnabled"])){ ?>
		<a href="javascript:void(0)" class="btn btn-default btn-pay direct_pay" id="wzf" data-value="1"><img  src="/static/api/shop/skin/default/wechat_logo.jpg" width="16px" height="16px"/>&nbsp;微信支付</a>
		<?php }?>
		<?php if(!empty($rsPay["Payment_AlipayEnabled"])){?>
		<a href="javascript:void(0)" class="btn btn-danger btn-pay direct_pay" id="zfb" data-value="2"><img  src="/static/api/shop/skin/default/alipay.png" width="16px" height="16px"/>&nbsp;支付宝支付</a>
		<?php }?>
		<a href="javascript:void(0)" class="btn btn-warning  btn-pay" id="money" data-value="余额支付"><img  src="/static/api/shop/skin/default/money.jpg"  width="16px" height="16px"/>&nbsp;余额支付</a>
        <p class="money_info">
			<b style="display:block; width:85%; height:40px; line-height:38px; font-size:14px; margin:0px auto; font-weight:normal">您当前余额：<span style="padding:0px 5px; font-size:16px; color:#F00">&yen;<?php echo $rsUser['User_Money'];?></span></b>
        	<input type="password" placeholder="请输入支付密码，默认123456" style="display:block; width:85%; margin:5px auto 0px; border:1px #dfdfdf solid; border-radius:5px; height:40px; line-height:40px;">
            <button style="display:block; width:85%; margin:5px auto 0px; border:none; border-radius:5px; background:#4F93CE; color:#FFF; font-size:14px; text-align:center; height:40px; line-height:38px;">确认支付</button>
        </p>
    <?php }?>
    </form>
	
<?php }elseif($rsConfig['Distribute_Type']==1){//消费额?>
	<?php
    foreach($dis_level as $id=>$value){
		$PeopleLimit = json_decode($value['Level_PeopleLimit'],true);
		$arr_limit = explode('|',$value['Level_LimitValue']);
		if($value["Level_LimitType"]==3){
			$limit = '无条件';
		}else{
			$limit = $arr_limit[0]==0 ? '商城总消费额需满'.$arr_limit[1].'元' : '需一次性消费'.$arr_limit[1].'元';
		}
	?>
<table cellpadding="0" cellspacing="0" class="distribute_type_1">	
	<tr>
    	<th colspan="2"><?php echo $value['Level_Name'];?></th>
    </tr>
    <tr>
    	<td class="td_1">条件</td>
        <td class="td_2">
		<?php echo $limit;?>
        </td>
    </tr>
    <tr>
    	<td class="td_1">权限</td>
        <td class="td_2">
			<?php
                foreach($PeopleLimit as $k=>$v){
					if($k>1){
						echo '<br />';
					}
					echo $arr_level_name[$k-1].'级&nbsp;&nbsp;';
					if($v==0){
						echo '无限制';
					}elseif($v==-1){
						echo '禁止';
					}else{
						echo $v.'&nbsp;个';
					}
				}
			?>
        </td>
    </tr>    
    <tr>
   	 <td class="td_3" colspan="2">
     	<?php if($value["Level_LimitType"]==3){?>
        <a href="<?php echo distribute_url();?>">进入分销中心</a>
        <?php }else{?>
     	<a href="<?php echo $shop_url;?>">立即消费</a>
        <?php }?>
     </td>
    </tr>
</table>
<?php }?>
<?php }elseif($rsConfig['Distribute_Type']==2){//购买商品?>
	<?php
	$products = array();
	$DB->Get('shop_products','Products_ID,Products_Name','where Users_ID="'.$UsersID.'"');
	while($r = $DB->fetch_assoc()){
		$products[$r['Products_ID']] = $r['Products_Name'];
	}
    foreach($dis_level as $id=>$value){
		$PeopleLimit = json_decode($value['Level_PeopleLimit'],true);
		$limit_arr = explode('|',$value['Level_LimitValue']);
		if($value['Level_LimitType'] == 3){
			$limit = '无条件';
		}else{
			if($limit_arr[0]==0){//任意商品
				$limit = '购买任意商品';
			}else{
				$limit = '购买以下任一商品：';
				$pids = explode(',',$limit_arr[1]);
				foreach($pids as $id){
					$limit .= empty($products[$id]) ? '' : '<br />'.$products[$id];
				}
			} 
		}
	?>
<table cellpadding="0" cellspacing="0" class="distribute_type_1">	
	<tr>
    	<th colspan="2"><?php echo $value['Level_Name'];?></th>
    </tr>
    <tr>
    	<td class="td_1">条件</td>
        <td class="td_2">		
		<?php echo $limit?>
        </td>
    </tr>
    <tr>
    	<td class="td_1">权限</td>
        <td class="td_2">
			<?php
                foreach($PeopleLimit as $k=>$v){
					if($k>1){
						echo '<br />';
					}
					echo $arr_level_name[$k-1].'级&nbsp;&nbsp;';
					if($v==0){
						echo '无限制';
					}elseif($v==-1){
						echo '禁止';
					}else{
						echo $v.'&nbsp;个';
					}
				}
			?>
        </td>
    </tr>
    
    <tr>
   	 <td class="td_3" colspan="2">
     	<?php if($value["Level_LimitType"]==3){?>
        <a href="<?php echo distribute_url();?>">进入分销中心</a>
        <?php }elseif($limit_arr[0]==0){?>
     	<a href="<?php echo $shop_url;?>">立即购买</a>
        <?php }else{?>
        <a href="javascript:void(0);" class="products_buy" lid="<?php echo $value['Level_ID'];?>">立即购买</a>
        <?php }?>
     </td>
    </tr>
</table>
<?php }?>
<div class="products_list_bg"></div>
<div class="products_list_tobuy">
	<h2><span>[×]</span>请选择商品</h2>
    <div class="products_list_content">
    </div>
</div>
<?php }?>
</body>
</html>

