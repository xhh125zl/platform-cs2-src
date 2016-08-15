<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("zhongchou_project","usersid='{$UsersID}' AND Biz_ID={$BizID} AND itemid=".$_GET["itemid"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
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
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="project.php">项目管理</a></li>
      </ul>
    </div>
    <div id="project" class="r_con_wrap">
      <div class="control_btn"><a href="project_add.php" class="btn_green btn_w_120">添加项目</a></div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="6%">序号</td>
            <td width="20%">项目名称</td>
            <td width="15%">形象图片</td>
            <td width="15%">时间</td>
            <td width="12%">进度</td>
            <td width="8%">赠品</td>
            <td width="8%">支持者</td>
            <td width="8%">状态</td>
            <td width="8%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php
		  	$lists = array();
          	$DB->getPage("zhongchou_project","*","WHERE usersid='{$UsersID}' AND Biz_ID={$BizID} order by addtime desc",10);
		  	while($r=$DB->fetch_assoc()){
            	$lists[] = $r;
			}
			foreach($lists as $k=>$v){
				$item = $DB->GetRs("user_order","count(*) as num,sum(Order_TotalPrice) as amount","WHERE Order_Type='zhongchou_".$v["itemid"]."' and Users_ID='{$UsersID}' AND Biz_ID={$BizID}");
				$v["people"] = empty($item["num"]) ? 0 : $item["num"];
				$v["complete"] = empty($item["amount"]) ? 0 : $item["amount"];
				$item = $DB->GetRs("zhongchou_prize","count(*) as num","WHERE usersid='{$UsersID}' AND projectid=".$v["itemid"]);
				$v["prize"] = $item["num"];
		  ?>
          <tr>
            <td nowrap="nowrap"><?php echo $k+1;?></td>
            <td><?php echo $v["title"] ?></td>
            <td nowrap="nowrap"><img src="<?php echo $v["thumb"];?>" width="128" /></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$v["fromtime"]);?><br />~<br /><?php echo date("Y-m-d H:i:s",$v["totime"]);?></td>
            <td style="text-align:left; padding-left:15px;">目标：<?php echo $v["amount"];?><br />筹到：<?php echo $v["complete"];?></td>
            <td nowrap="nowrap"><?php echo $v["prize"];?><br /><a href="prize.php?projectid=<?php echo $v["itemid"];?>">[设置]</a></td>
            <td nowrap="nowrap"><?php echo $v["people"];?><br /><a href="users.php?projectid=<?php echo $v["itemid"];?>">[查看]</a></td>
            <td nowrap="nowrap"><?php echo $v["status"]==1 ? '<font style="color:blue">已审核</font>' : '<font style="color:red">未审核</font>';?></td>
            <td nowrap="nowrap" class="last"><a href="project_edit.php?itemid=<?php echo $v["itemid"] ?>"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" title="修改" /></a> <a href="?action=del&itemid=<?php echo $v["itemid"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" title="删除" /></a></td>
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