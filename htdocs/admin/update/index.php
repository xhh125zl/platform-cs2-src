<?php
require($_SERVER['DOCUMENT_ROOT'] . '/version.php');
defined('IN_UPDATE') or exit('No Access');
require($_SERVER['DOCUMENT_ROOT'] . '/include/update/file.func.php');

$url = 'http://down.haofenxiao.net/version_list.php?product='.$version['product'].'&type='.$version['type'];
$code = @file_get_contents($url);
if($code){
	if(substr($code, 0, 8) == 'StatusOK') {
		$code = substr($code, 8);
	} else {
		msg($code);
	}
}

//获取版本列表
$version_lists = json_decode($code,true);

//获取下一版本
$release = $version['release'];
$dates = array_keys($version_lists);

if($release==$dates[0]){
	$release = 0;
}elseif($release<$dates[count($dates)-1]){
	$release = $dates[count($dates)-1];
}else{
	for($i=0;$i<count($dates);$i++){
		if($release<$dates[$i] && $release>=$dates[$i+1]){
			$release = $dates[$i];
		}
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="index.php">系统更新</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
    <div class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="20%" nowrap="nowrap">版本号</td>
            <td width="16%" nowrap="nowrap">更新时间</td>
            <td width="16%" nowrap="nowrap">文件大小</td>
            <td width="16%" nowrap="nowrap">在线更新</td>
            <td width="16%" nowrap="nowrap">手动下载</td>   
            <td width="16%" nowrap="nowrap">更新说明</td>
          </tr>
        </thead>
        <tbody>
        <?php
			foreach($version_lists as $key=>$value){
		?>
          <tr>
            <td nowrap="nowrap">V<?php echo $value["version"];?>R<?php echo $key;?></td>
            <td nowrap="nowrap"><?php echo date('Y-m-d',strtotime($value['datetime']));?></td>
            <td nowrap="nowrap"><?php echo $value["filesize"];?></td>
            <td nowrap="nowrap"><?php if($release==$value['datetime']){?><a href="update.php?release=<?php echo $value['datetime']?>">[一键升级]</a><?php }else{?>[一键升级]<?php }?></td>
            <td nowrap="nowrap"><a href="update.php?action=getdown&release=<?php echo $value['datetime']?>">[下载]</a></td>
            <td class="last" nowrap="nowrap"></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>