<?php
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php'); 
require_once(CMS_ROOT . '/include/api/shopconfig.class.php'); 
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
 
                mysql_query("BEGIN");
				$Biz_Account = $_GET["Biz_Account"];	
				$Flag = shopconfig::updateBizapply(['Biz_Account'=>$Biz_Account,'bizApplyData'=>array("is_del"=>0,'status'=>-1)]);		
				$Flag_a = $DB->Set("biz",array("is_auth"=>-1),"where Biz_Account='".$Biz_Account."'");
                 
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
        mysql_query("BEGIN");
		
		$Biz_Account = $_GET["Biz_Account"];	
		$Flag = shopconfig::updateBizapply(['Biz_Account'=>$Biz_Account,'bizApplyData'=>array('status'=>2)]);		
		$Flag_a = $DB->Set("biz",array("is_auth"=>2),"where Biz_Account='".$Biz_Account."'");		
		if($Flag && $Flag_a)
		{
                    mysql_query('commit');
                    echo '<script language="javascript">alert("审核成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
                   mysql_query("ROLLBACK");
			echo '<script language="javascript">alert("审核失败");history.back();</script>';
		}
		exit;
	}
    if($_GET["action"]=="back"){
        mysql_query("BEGIN");
		
		$Biz_Account = $_GET["Biz_Account"];	
		$Flag = shopconfig::updateBizapply(['Biz_Account'=>$Biz_Account,'bizApplyData'=>array('status'=>-1)]);		
		$Flag_a = $DB->Set("biz",array("is_auth"=>-1),"where Biz_Account='".$Biz_Account."'");		
		if($Flag && $Flag_a)
		{   
                     mysql_query('commit');
			echo '<script language="javascript">alert("驳回成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
                    mysql_query("ROLLBACK");
			echo '<script language="javascript">alert("驳回失败");history.back();</script>';
		}
		exit;
	}
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


 
$condition .= " and is_del =1  order by CreateTime desc";

$_Status = array(1=>'<font style="color:#ff0000">未审核</font>',2=>'<font style="color:blue">审核通过</font>',-1=>'<font style="color:blue">已驳回</font>');
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
		<li class="cur"><a href="apply.php">资质审核列表</a></li>
                <li class=""><a href="authpay.php">入驻支付列表</a></li>
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
          <option value="1">未审核</option>
          <option value="2">审核通过</option>
          <option value="-1">已驳回</option>
        </select>
        <input type="hidden" name="search" value="1" />
        <input type="submit" class="search_btn" value="搜索" />
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>

            <td width="6%" nowrap="nowrap">ID</td>
            <td width="8%" nowrap="nowrap">商家账号</td>
            <td width="8%" nowrap="nowrap">认证类型</td>
            <td width="13%" nowrap="nowrap">申请时间</td>
            <td width="8%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php 
	 
		$apply_res = shopconfig::getBizapply(['pageSize'=>20,'is_del'=>1]);
		$lists = !empty($apply_res['data'])?$apply_res['data']:array();	
		  
		  foreach($lists as $k=>$rsBiz){
		?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $rsBiz["id"];?></td>
            
 
            <td><?php echo !empty($rsBiz["Biz_Account"])?$rsBiz["Biz_Account"]:'商家不存在或已删除'; ?></td>
            <td><?php if($rsBiz['authtype']==1){echo '企业认证';}elseif($rsBiz['authtype']==2){echo'个人认证';}?></td>   
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsBiz["CreateTime"]) ?></td>
            <td nowrap="nowrap"><?php echo $_Status[$rsBiz["status"]]; ?></td>
            <td class="last" nowrap="nowrap">
                <a href="./apply_detail.php?itemid=<?php echo $rsBiz["Biz_Account"] ?>">[查看]</a>
                <?php if($rsBiz["status"] < 2){?>
                <a href="?action=read&Biz_Account=<?php echo $rsBiz["Biz_Account"] ?>">[通过]</a>
                <?php } ?>
                 <?php if($rsBiz["status"] == 1){?>
                <a href="?action=back&Biz_Account=<?php echo $rsBiz["Biz_Account"] ?>">[驳回]</a>
                <?php } ?>
                <a href="?action=del&Biz_Account=<?php echo $rsBiz["Biz_Account"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};">[删除]</a>
            </td>

          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
	  <div style="background:#F7F7F7; border:1px #dddddd solid; height:40px; line-height:40px; font-size:12px; margin:10px 0px; padding-left:15px; color:#ff0000">提示：商家入驻地址 <a href="<?=SHOP_URL?>reg.php" target="_blank"><?=SHOP_URL?>reg.php</a></div> 
     </div>
  </div>
</div>
</body>
</html>