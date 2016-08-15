<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$projectid=empty($_REQUEST['projectid'])?0:$_REQUEST['projectid'];
$item=$DB->GetRs("zhongchou_project","*","WHERE usersid='{$UsersID}' AND Biz_ID={$BizID} AND itemid=".$projectid);
if(!$item){
	echo '<script language="javascript">alert("该项目不存在！");window.location="project.php";</script>';
}
if(isset($_GET["action"])){
	if($_GET["action"]=="del"){
		$Flag=$DB->Del("zhongchou_prize","usersid='{$UsersID}' and prizeid=".$_GET["prizeid"]);
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
<link href="/static/style.css" rel="stylesheet" type="text/css" />
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
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="project.php">项目管理</a></li>
      </ul>
    </div>
    <div id="column" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script>
      <div class="control_btn"><a href="prize_add.php?projectid=<?php echo $projectid;?>" class="btn_green btn_w_120">添加赠品</a></div>
      <div style="background:#F7F7F7; border:1px #dddddd solid; height:40px; line-height:40px; font-size:12px; margin:10px 0px; padding-left:15px; color:#ff0000">赠品，即用户支持多少金额，可以获取相应的赠品</div>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
                <tr>
                  <td width="10%" align="center"><strong>序号</strong></td>
                  <td width="20%" align="center"><strong>支持金额</strong></td>
                  <td width="40%" align="center"><strong>赠品</strong></td>
                  <td width="20%" align="center"><strong>赠品图片</strong></td>
                  <td width="10%" align="center"><strong>操作</strong></td>
                </tr>
				<?php
				$DB->get("zhongchou_prize","*","where usersid='{$UsersID}' and projectid=$projectid order by addtime asc");
				$i = 0;
				while($r=$DB->fetch_assoc()){
					$i++;
				?>
                <tr onMouseOver="this.bgColor='#D8EDF4';" onMouseOut="this.bgColor='';">
                  <td align="center"><?php echo $i; ?></td>
                  <td align="center"><?php echo $r["money"]; ?></td>
                  <td align="center"><?php echo $r["title"]; ?></td>
                  <td align="center"><img src="<?php echo $r["thumb"]; ?>" width="100" /> </td>
                  <td align="center"><a href="prize_edit.php?prizeid=<?php echo $r["prizeid"]; ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a> <a href="?action=del&prizeid=<?php echo $r["prizeid"]; ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
                </tr>
                <?php }?>
              </table>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>