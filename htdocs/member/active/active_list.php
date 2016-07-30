<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(IS_GET && isset($_GET["action"]) && $_GET["action"]=="del"){
    $Active_ID = intval($_GET["Active_ID"]);
    $Flag=$DB->Del("active","Users_ID='{$UsersID}' AND Active_ID='{$Active_ID}'");
    if($Flag){
        sendAlert("删除成功!",$_SERVER['HTTP_REFERER'],3);
	}else{
	    sendAlert("删除失败!",$_SERVER['HTTP_REFERER'],3);
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
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div id="products" class="r_con_wrap">
      <div class="control_btn">
      <a href="active_add.php" class="btn_green btn_w_120">添加活动</a>
      </div>
      <table align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="48px" align="center"><strong>活动ID</strong></td>
            <td width="48px" align="center"><strong>活动名称</strong></td>
            <td align="center" width="66px"><strong>活动类型</strong></td>
            <td align="center" width="66px"><strong>商家最多参与商品数</strong></td>
            <td align="center" width="66px"><strong>最多允许参与商家数</strong></td>
            <td align="center" width="66px"><strong>活动参与者列表</strong></td>
            <td align="center" width="66px"><strong>活动时间</strong></td>
            <td width="50px" align="center"><strong>活动状态</strong></td>
            <td width="70px" align="center"><strong>操作</strong></td>
          </tr>
          </tr>
        </thead>
        <tbody>
    	<?php 
		  $lists = array();
		  $result = $DB->getPage("active","*","WHERE Users_ID='{$UsersID}' ORDER BY Status ASC,Active_ID DESC",10);
		  $lists = $DB->toArray($result);
		  foreach($lists as $k => $v){
	    ?>      
          <tr>
            <td nowrap="nowrap" class="id"><?=$v["Active_ID"] ?></td>
            <td nowrap="nowrap" class="id"><?=$v["Active_Name"] ?></td>
            <td nowrap="nowrap"><?=$ActiveType[$v["Type_ID"]] ?></td>
            <td nowrap="nowrap"><?=$v["MaxGoodsCount"] ?></td>
            <td nowrap="nowrap"><?=$v["MaxBizCount"] ?></td>
            <td nowrap="nowrap"><a href="biz_active.php?activeid=<?=$v["Active_ID"] ?>">查看</a></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d",$v["starttime"]); ?> 至 <?php echo date("Y-m-d",$v["stoptime"]); ?></td>
            <td nowrap="nowrap"><?php echo $v["Status"]==0 ? '<font style="color:red">已关闭</font>' : '<font style="color:blue">已开启</font>'; ?></td>
            <td class="last" nowrap="nowrap">
            	<a href="active_edit.php?typeid=<?=$v["Active_ID"]?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a>&nbsp;&nbsp;
            	<a class="onclik" onClick="del(<?=$v["Active_ID"]?>)"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a>
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
function del(id){
    if(confirm('您确定删除此活动么？')){   
    	location.href="active_list.php?action=del&Active_ID="+id;
    }   
}
</script>
</body>
</html>