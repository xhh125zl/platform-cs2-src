<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		if (empty($_GET["itemid"])) {
			echo '<script language="javascript">alert("非法操作");history.back();</script>';
			exit;
		}
		//$Flag=$DB->Del("biz_apply","Users_ID='".$_SESSION["Users_ID"]."' and id=".$_GET["itemid"]);
                mysql_query("BEGIN");
                $Flag=$DB->Set("biz_apply",array("is_del"=>0),"where Users_ID='".$_SESSION["Users_ID"]."' and id=".$_GET["itemid"]);
                
                $Flag_a = $DB->Set("biz",array("is_auth"=>0),"where Users_ID='".$_SESSION["Users_ID"]."' and Biz_ID=".$_GET["itemid"]);
		if($Flag && $Flag_a)
		{
                    mysql_query('commit');
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
                    mysql_query("ROLLBACK");
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
	
	if($_GET["action"]=="read"){
		if (empty($_GET["itemid"])) {
			echo '<script language="javascript">alert("非法操作");history.back();</script>';
			exit;
		}
		$Flag = $DB->Set("biz_bond_back",array("status"=>2),"where Users_ID='".$_SESSION["Users_ID"]."' and id=".$_GET["itemid"]);
		if($Flag )
		{
                    echo '<script language="javascript">alert("同意成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("同意失败");history.back();</script>';
		}
		exit;
	}
    if($_GET["action"]=="back"){
		if (empty($_GET["itemid"])) {
			echo '<script language="javascript">alert("非法操作");history.back();</script>';
			exit;
		}	
		$Flag=$DB->Set("biz_bond_back",array("status"=>-1),"where Users_ID='".$_SESSION["Users_ID"]."' and id=".$_GET["itemid"]);
		if($Flag)
		{   
			echo '<script language="javascript">alert("驳回成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("驳回失败");history.back();</script>';
		}
		exit;
	}
	if($_GET["action"]=="begin_pay"){
		if (empty($_GET["itemid"])) {
			echo '<script language="javascript">alert("记录不存在");history.back();</script>';
			exit;
		}
		if (empty($_GET["bizid"])) {
			echo '<script language="javascript">alert("商家不存在");history.back();</script>';
			exit;
			
		}
		$Flag1 = $DB->Set("biz_bond_back",array("status"=>3),"where Users_ID='".$_SESSION["Users_ID"]."' and id=".$_GET["itemid"]);
		mysql_query('BEGIN');
		$bizInfo = $DB->GetRs('biz','bond_free',"where Users_ID='".$_SESSION["Users_ID"]."' and Biz_ID=".$_GET["bizid"]);
		if (empty($bizInfo)) {
			echo '<script language="javascript">alert("该商家没有保证金,不能退款");history.back();</script>';
			exit;
		}
		$backInfo = $DB->GetRs('biz_bond_back','back_money',"where Users_ID='".$_SESSION["Users_ID"]."' and id=".$_GET["itemid"]);
		if ($bizInfo['bond_free'] < $backInfo['back_money']) {
			echo '<script language="javascript">alert("该商家的保证金小于所退保证金,不能退款");history.back();</script>';
			exit;
		}
		$bond_free = $bizInfo['bond_free'] - $backInfo['back_money'];
		$Flag2 = $DB->Set("biz",array("bond_free"=>$bond_free),"where Users_ID='".$_SESSION["Users_ID"]."' and Biz_ID=".$_GET["bizid"]);
		
		if($Flag1 && $Flag2)
		{
			mysql_query('commit');
            echo '<script language="javascript">alert("退款成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			mysql_query('ROLLBACK');
			echo '<script language="javascript">alert("退款失败");history.back();</script>';
		}
		exit;
	}
}
$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
 
$condition .= " order by addtime desc";
 
 
$DB->get("biz","Biz_ID,Users_ID,Biz_Account,Biz_Name","where Users_ID='".$_SESSION["Users_ID"]."'");
while ($r = $DB->fetch_assoc()) {
    $bizList[$r['Biz_ID']] = $r; 
}

// echo "<pre>";print_r($bizList);

$_Status = array(1=>'<font style="color:#ff0000">申请中</font>',2=>'<font style="color:blue">审核通过</font>',3=>'<font style="color:blue">已退款</font>',-1=>'<font style="color:blue">已驳回</font>');
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
<script src="/static/js/plugin/layer/layer.js"></script>
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
	<li><a href="apply_config.php">入驻设置</a></li>
        <li class="cur"><a href="bond_back.php">保证金退款</a></li>
      </ul>
    </div>
	
    <div id="bizs" class="r_con_wrap">
       
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>

            <td width="6%" nowrap="nowrap">ID</td>
            <td width="8%" nowrap="nowrap">商家账号</td>
            <td width="8%" nowrap="nowrap">商家名称</td>
			<td width="8%" nowrap="nowrap">支付宝姓名</td>
            <td width="8%" nowrap="nowrap">支付宝账号</td>
            <td width="8%" nowrap="nowrap">退款金额</td>
            <td width="14%" nowrap="nowrap">申请时间</td>
            <td width="8%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php 
		  $lists = array();
		  $DB->getPage("biz_bond_back","*",$condition,10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$rsBiz){
		?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $rsBiz["id"] ?></td>
            <td><?php echo !empty($bizList[$rsBiz["biz_id"]]['Biz_Account'])?$bizList[$rsBiz["biz_id"]]['Biz_Account']:'商家不存在' ?></td>
            <td><?php echo !empty($bizList[$rsBiz["biz_id"]]['Biz_Name'])?$bizList[$rsBiz["biz_id"]]['Biz_Name']:''  ?></td>
			 <td><?php echo $rsBiz["alipay_username"] ?></td>
			  <td><?php echo $rsBiz["alipay_account"] ?></td>
            <td><?php echo $rsBiz["back_money"] ?></td>
           

             
            
			 
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsBiz["addtime"]) ?></td>
            <td nowrap="nowrap"><?php echo $_Status[$rsBiz["status"]]; ?></td>
            <td class="last" nowrap="nowrap">
                <a class="see" href="javascript:void(0)" url="./back_detail.php?itemid=<?php echo $rsBiz["id"] ?>">[查看]</a>
                <?php if($rsBiz["status"] == 1){?>
                <a href="?action=read&itemid=<?php echo $rsBiz["id"] ?>">[通过]</a>
                <a href="?action=back&itemid=<?php echo $rsBiz["id"] ?>">[驳回]</a>
                <?php } ?>
                <?php if($rsBiz["status"] == 2){?>
                <a href="?action=begin_pay&itemid=<?php echo $rsBiz["id"]?>&bizid=<?php echo $rsBiz['biz_id']?>">[退款]</a>
                <?php } ?>
                <!--<a href="?action=del&itemid=<?php echo $rsBiz["id"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};">[删除]</a>-->
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
    <script>
    $(".see").click(function(){
            var url = $(this).attr('url');
            layer.open({
                title:'申请理由',
                type: 2,
                area:['600px','400px'],
                content: url
            });
            
        })
    </script>
</body>
</html>