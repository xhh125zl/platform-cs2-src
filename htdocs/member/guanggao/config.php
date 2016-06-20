<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$r = $DB->GetRs("ad_advertising","count(*) as num","where Users_ID='".$_SESSION["Users_ID"]."'");
if($r["num"]==0){
	$lists = array();
	$DB->get("ad_model","*","where 1");
	while($r1=$DB->fetch_assoc()){
		$lists[] = $r1;
	}
	foreach($lists as $v){
		$Data = array(
			"Model_ID"=>$v["Model_ID"],
			"AD_Name"=>$v["Model_Name"],
			"AD_Status"=>0,
			"AD_Width"=>$v["Model_Width"],
			"AD_Height"=>$v["Model_Height"],
			"AD_Text"=>$v["Model_Text"],
			"Users_ID"=>$_SESSION["Users_ID"],
			"AD_CreateTime"=>time()
		);
		$DB->Add("ad_advertising",$Data);
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

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/guanggao.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/guanggao.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="config.php">广告位管理</a></li>
         <li class=""><a href="ad_list.php">广告列表</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(shop_obj.products_list_init);</script>
      <!--<div class="control_btn">
      <a href="config_add.php" class="btn_green btn_w_120">添加广告位</a>  
      </div>-->
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">名称</td>
            <td width="10%" nowrap="nowrap">状态</td>
          
            <td width="22%" nowrap="nowrap">备注</td>
            <td width="20%" nowrap="nowrap">时间</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $lists = array();
		  $DB->getPage("ad_advertising","*","where Users_ID='".$_SESSION["Users_ID"]."' order by AD_IDS asc",10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$ads){
			  ?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $ads["AD_IDS"] ?></td>
            <td><?php echo $ads["AD_Name"] ?></td>
            <td nowrap="nowrap"><?php echo empty($ads["AD_Status"])?'<font color="#FF0000">关闭</font>':'<font color="#00FF00">开启</font>';?></td>
          
            <td nowrap="nowrap"><?php echo empty($ads["AD_Text"])?'':$ads["AD_Text"];?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d",$ads["AD_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="config_edit.php?id=<?php echo $ads["AD_IDS"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="ad_list.php?pid=<?php echo $ads["AD_IDS"] ?>"><img src="/static/member/images/ico/view.gif" align="absmiddle" alt="查看广告" /></a></td>
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