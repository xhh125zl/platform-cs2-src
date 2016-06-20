<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("wechat_material","Users_ID='".$_SESSION["Users_ID"]."' and Material_ID=".$_GET["MaterialID"]." and Material_TableID=0 and Material_Display=1");
	}
	if($Flag)
	{
		echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
	}else
	{
		echo '<script language="javascript">alert("删除失败");history.back();</script>';
	}
	exit;
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
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/material.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/material.js'></script>
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="index.php">图文消息管理</a></li>
        <li class=""><a href="url.php">自定义URL</a></li>
        <li class=""><a href="sysurl.php">系统URL查询</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
    <div id="material" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/masonry/masonry.min.js'></script> 
      <script language="javascript">$(window).load(material_obj.material_init);</script>
      <div class="list">
        <div class="item first_item">
          <div>
            <div><a href="add.php"></a></div>
            <a href="add.php">+单图文消息</a> </div>
          <div class="multi">
            <div><a href="madd.php"></a></div>
            <a href="madd.php">+多图文消息</a> </div>
        </div>
        <?php $DB->getPage("wechat_material","*","where Users_ID='".$_SESSION["Users_ID"]."' and Material_TableID='0' and Material_TableID=0 and Material_Display=1 order by Material_ID desc",$pageSize=10);
while($rsMaterial=$DB->fetch_assoc()){	
	$json=json_decode($rsMaterial['Material_Json'],true);
	if($rsMaterial['Material_Type']){?>
        <div class="item multi">
          <div><?php echo date("Y-m-d",$rsMaterial['Material_CreateTime']) ?></div>
<?php foreach($json as $k=>$v){?>
          <div class="<?php echo $k?"list":"first" ?>">
            <div class="info">
              <div class="img"><img src="<?php echo $v['ImgPath'] ?>" /></div>
              <div class="title"><?php echo $v['Title'] ?></div>
            </div>
          </div>
<?php }?>
          <div class="mod_del">
           <div class="mod"><a href="medit.php?MaterialID=<?php echo $rsMaterial['Material_ID'] ?>"><img src="/static/member/images/ico/mod.gif" /></a></div>
            <div class="del"><a href="index.php?action=del&MaterialID=<?php echo $rsMaterial['Material_ID'] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" /></a></div>
          </div>
        </div>
        <?php }else{
?>
        <div class="item one">
          <div class="title"><?php echo $json['Title'] ?></div>
          <div><?php echo date("Y-m-d",$rsMaterial['Material_CreateTime']) ?></div>
          <div class="img"><img src="<?php echo $json['ImgPath'] ?>" /></div>
          <div class="txt"><?php echo $json['TextContents'] ?></div>
          <div class="mod_del">
            <div class="mod"><a href="edit.php?MaterialID=<?php echo $rsMaterial['Material_ID'] ?>"><img src="/static/member/images/ico/mod.gif" /></a></div>
            <div class="del"><a href="index.php?action=del&MaterialID=<?php echo $rsMaterial['Material_ID'] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" /></a></div>
          </div>
        </div>
        <?php }
}?>
        <div class="clear"></div>
      </div>
      <div class="blank12"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>