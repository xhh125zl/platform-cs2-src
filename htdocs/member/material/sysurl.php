<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/support/sysurl_helpers.php');
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
if($_POST){
	$name = trim($_POST["Url_Name"]);
	if(empty($name)){
		$response = array("status"=>0,"msg"=>"标题不能为空");
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$link = trim($_POST["Url_Link"]);
	if(empty($link)){
		$response = array("status"=>0,"msg"=>"链接不能为空");
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	$link = strpos($link,"http://")>-1 ? $link : 'http://'.$link;
	
	$Data = array(
		"Url_Name"=>$name,
		"Url_Value"=>$link,
		"Users_ID"=>$_SESSION["Users_ID"]
	);
	$Flag=$DB->Add("wechat_url",$Data);
	
	if($Flag){
		$response = array("status"=>1,"msg"=>"");
	}else{
		$response = array("status"=>0,"msg"=>"添加失败");
	}
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	exit;
}else{
	$modules = get_sys_modules($DB);
	$menus = array();
	$module = $type = '';
	if(!empty($modules)){
		$default_array = current($modules);
		if(isset($_GET["module"])){
			$module = $_GET["module"];
		}else{
			$module = $default_array["module"];
		}
		
		$type = isset($_GET["type"]) ? $_GET["type"] : (in_array($module,array('shop','article','web')) ? 'category' : '');
		$menus = get_sys_url_menu($DB, $modules);
	}else{
		echo '暂无模块，请联系维护人员';
		exit;
	}
	
	$dialog = empty($_GET["dialog"]) ? 0 : 1;
	$input = empty($_GET["input"]) ? '' : $_GET["input"];
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $SiteName;?></title>
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
    <script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
    <link href='/static/member/css/material.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/material.js'></script>
    <?php if($dialog==0){?>
    <div class="r_nav">
      <ul>
        <li class=""><a href="index.php">图文消息管理</a></li>
        <li class=""><a href="url.php">自定义URL</a></li>
        <li class="cur"><a href="sysurl.php">系统URL查询</a></li>
      </ul>
    </div>
    <?php }else{ if($input){?>
    <script language="javascript">var parent_input = '<?php echo $input;?>'; $(document).ready(material_obj.url_select);</script>
    <?php }}?>
    <div id="url" class="r_con_wrap" style="min-height:500px;">
      <div class="type">
       <?php foreach($modules as $m){?>
          <a href="<?php echo empty($menus[$m["moduleid"]]["type"]) ? 'sysurl.php?dialog='.$dialog.'&input='.$input.'&module='.$m["module"] : '#';?>"<?php echo $module == $m["module"] ? ' class="cur"' : '';?>><?php echo $m["name"];?>
          <?php if(!empty($menus[$m["moduleid"]]["type"])){?>
          	<?php foreach($menus[$m["moduleid"]]["type"] as $t){?>
            <span onClick="window.location.href='sysurl.php?dialog=<?php echo $dialog;?>&input=<?php echo $input;?>&module=<?php echo $m["module"];?>&type=<?php echo $t["module"];?>';"><?php echo $t["name"];?></span>
            <?php }?>
          <?php }?>
          </a>
       <?php }?>
       <?php if($dialog==1 && $input){?>
       <a href="sysurl.php?dialog=<?php echo $dialog;?>&input=<?php echo $input;?>&module=diy"<?php echo $module == "diy" ? ' class="cur"' : '';?>>自定义URL</a>
       <?php }?>
      </div>
      <?php require_once('sysurl/'.$module.($type ? '_'.$type : '').'.php');?>
    </div>
  </div>
</div>
</body>
</html>