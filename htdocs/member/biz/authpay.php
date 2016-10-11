<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
  
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
if(isset($_GET['search'])){
	if($_GET['Biz_Account']){
            $BizInfo = $DB->getRs('biz','Biz_ID','where Biz_Account = "'.$_GET['Biz_Account'].'"');
            if (!empty($BizInfo)) {
              $condition .= " and biz_id = ".$BizInfo['Biz_ID'];  
            } else {
                $condition .= " and biz_id = 'a'";  
            }	
	} 
	if($_GET['status']!=""){
           
		$condition .= " and status=".$_GET['status'];
	}
}


$condition .= " and type =1  order by addtime desc";

$_Status = array(0=>'<font style="color:#ff0000">未付款</font>',1=>'<font style="color:blue">已付款</font>');
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
<style type="text/css">
#bizs .search{padding:10px; background:#f7f7f7; border:1px solid #ddd; margin-bottom:8px; font-size:12px;}
#bizs .search *{font-size:12px;}
#bizs .search .search_btn{background:#1584D5; color:white; border:none; height:22px; line-height:22px; width:50px;}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li><a href="index.php">商家列表</a></li>
        <li><a href="group.php">商家分组</a></li>
		<li class=""><a href="apply.php">资质审核列表</a></li>
                <li class="cur"><a href="authpay.php">入驻支付列表</a></li>
                <li class=""><a href="chargepay.php">续费支付列表</a></li>
		<li><a href="apply_config.php">入驻设置</a></li>
      </ul>
    </div>
	
    <div id="bizs" class="r_con_wrap">
      <form class="search" method="get" action="?">
          商家账号：
        <input type="text" name="Biz_Account" value="" placeholder='请输入商家账号' class="form_input" size="15" />       
        状态：
        <select name="status">
          <option value="">全部</option>
          <option value="0">未付款</option>
          <option value="1">已付款</option>
        </select>
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>

            <td width="6%" nowrap="nowrap">ID</td>
            <td width="8%" nowrap="nowrap">商家账号</td>
            <td width="8%" nowrap="nowrap">订单类型</td>
            <td width="8%" nowrap="nowrap">保证金</td>
            <td width="8%" nowrap="nowrap">开通年限</td>
            <td width="8%" nowrap="nowrap">年费</td>
            <td width="8%" nowrap="nowrap">总额</td>
            <td width="8%" nowrap="nowrap">状态</td>
            <td width="13%" nowrap="nowrap">支付方式</td>
            <td width="8%" nowrap="nowrap">提交时间</td>
            <td width="10%" nowrap="nowrap" class="last">支付时间</td>
          </tr>
        </thead>
        <tbody>
        <?php 
		  $lists = array();
		  $DB->getPage("biz_pay","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$rsBiz){
		?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $rsBiz["id"];?></td>
            
            <td><?php echo !empty($rsBiz["Biz_Account"])?$rsBiz["Biz_Account"]:'商家不存在或已删除'; ?></td>
           
            <td><?php if($rsBiz['type']==1){echo '入驻订单';}elseif($rsBiz['type']==2){echo'年费订单';}elseif($rsBiz['type']==3){echo'保证金订单';}?></td>   
            <td nowrap="nowrap"><?php echo $rsBiz["bond_free"]?></td>
            <td nowrap="nowrap"><?php echo $rsBiz["years"].'年'; ?></td>
            <td nowrap="nowrap"><?php echo $rsBiz["year_free"]; ?></td>
            <td nowrap="nowrap"><?php echo $rsBiz["total_money"]; ?></td>
            <td nowrap="nowrap"><?php echo $_Status[$rsBiz["status"]]; ?></td>
			<td nowrap="nowrap"><?php echo $rsBiz["order_paymentmethod"]; ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsBiz["addtime"]) ?></td>
            <td class="last" nowrap="nowrap">
                <?php echo !empty($rsBiz["paytime"])?date("Y-m-d H:i:s",$rsBiz["paytime"]):'' ?>
            </td>

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