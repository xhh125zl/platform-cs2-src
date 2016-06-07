<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("ad_list","Users_ID='".$_SESSION["Users_ID"]."' and AD_ID=".$_GET["id"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}
function get_category($catid){
	global $DB;
	$r = $DB->GetRs("ad_advertising","AD_Name","where AD_IDS='".$catid."'");
	return $r['AD_Name'];
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

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/guanggao.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/guanggao.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">广告位管理</a></li>
        <li class="cur"><a href="ad_list.php">广告列表</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap"> 
      <script language="javascript">$(document).ready(shop_obj.products_list_init);</script>
      <div class="control_btn">
      <a href="ad_add.php" class="btn_green btn_w_120">添加广告</a>  
      </div>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="8%" nowrap="nowrap">序号</td>
            <td width="10%" nowrap="nowrap">广告位</td>
            <td width="25%" nowrap="nowrap">链接</td>
            <td width="25%" nowrap="nowrap">图片</td>
            <td width="22%" nowrap="nowrap">投放时间</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php 
		  $lists = array();
		  $where = !empty($_GET["pid"])?" and AD_IDS=".$_GET["pid"]:"";
		  $DB->getPage("ad_list","*","where Users_ID='".$_SESSION["Users_ID"]."'".$where." order by AD_ID desc",10);
		  
		  while($r=$DB->fetch_assoc()){
			  $lists[] = $r;
		  }
		  foreach($lists as $k=>$ads){
			  ?>
              
          <tr>
            <td nowrap="nowrap"><?php echo $ads["AD_ID"]; ?></td>
            <td nowrap="nowrap"><?php echo get_category($ads["AD_IDS"]); ?></td>
            <td><?php echo $ads["AD_Link"]; ?></td>
            <td nowrap="nowrap" style="overflow:hidden;"><img width="90px" src="<?php $img = json_decode(empty($ads["AD_Img"]) ? array() : $ads["AD_Img"],true);echo empty($img[0]) ? "" : $img[0];?>" /></td>
            <td nowrap="nowrap"><?php if(time()>$ads["AD_StarTime"] && time()<$ads["AD_EndTime"])echo date("Y-m-d h:i",$ads["AD_StarTime"]).'~'.date("Y-m-d h:i",$ads["AD_EndTime"]);else echo "广告已过期"; ?></td>
            <td class="last" nowrap="nowrap"><a href="ad_edit.php?id=<?php echo $ads["AD_ID"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a> <a href="ad_list.php?action=del&id=<?php echo $ads["AD_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a></td>
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