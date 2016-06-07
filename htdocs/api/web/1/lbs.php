<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["SiteName"] ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/web/skin/default/style.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/web/js/web.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/js/plugin/animation/snow.js'></script>
<script language="javascript">
$(document).ready(web_obj.page_init);
var links=new Array('/api/<?php echo $UsersID ?>/web/');
</script>
</head>

<body>
<div id="web_page_contents_loading"><img src="/static/api/web/images/loading.gif" /></div>
<div id="header" class="wrap">
  <ul>
    <li class="home first"><a href="/api/<?php echo $UsersID ?>/web/"></a></li>
    <li class="back"><a href="javascript:;"></a></li>
    <?php echo empty($rsConfig['CallEnable'])?'':'<li class="tel"><a href="tel:'.$rsConfig['CallPhoneNumber'].'" class="autotel"></a></li>';
	echo empty($rsConfig["Stores_PrimaryLat"])||empty($rsConfig["Stores_PrimaryLng"])?'':'<li class="lbs"><a href="/api/'.$UsersID.'/web/lbs/"></a> </li>';
	echo empty($rsConfig['MusicPath'])?'':'<li class="music"><a href="javascript:;" class="on"></a></li>'; ?>
  </ul>
</div>
<div id="web_page_contents">
  <style type="text/css">
body,html{background:#eee;}
</style>
  <div id="lbs" class="wrap">
    <div class="shop_img"><img src="<?php echo $rsConfig["Stores_ImgPath"] ?>"></div>
    <a class="gps" href="http://api.map.baidu.com/marker?location=<?php echo $rsConfig["Stores_PrimaryLat"].','.$rsConfig["Stores_PrimaryLng"] ?>&title=<?php echo $rsConfig["Stores_Name"] ?>&name=<?php echo $rsConfig["Stores_Name"] ?>&content=<?php echo $rsConfig["Stores_Address"] ?>&output=html&wxref=mp.weixin.qq.com" target="_self"><img src="/static/api/web/skin/default/images/gps.png"></a>
    <div class="item">
      <div class="name"><?php echo $rsConfig["Stores_Name"] ?></div>
    </div>
    <div class="item">
      <div class="tel_ico"></div>
      <div class="item_name">电话:</div>
      <div class="tel_number"><a href="tel:<?php echo $rsConfig["CallPhoneNumber"] ?>" target="_self"><?php echo $rsConfig["CallPhoneNumber"] ?></a></div>
    </div>
    <div class="item">
      <div class="address_ico"></div>
      <div class="item_name">地址:</div>
      <div class="address"><?php echo $rsConfig["Stores_Address"] ?></div>
    </div>
    <div class="item">
      <div class="description"><?php echo $rsConfig["Stores_Description"] ?></div>
    </div>
  </div>
</div>
<?php $DB->get("web_column","Column_ID,Column_Name,Column_ImgPath,Column_Link,Column_LinkUrl,Column_PopSubMenu","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc",4);
	$Column=array();
	while($rsColumn=$DB->fetch_assoc()){
		$Column[]=$rsColumn;
	}
	if(count($Column)>0){
		echo '<div id="footer_points"></div><footer id="footer"><ul>';
	}
	foreach($Column as $key=>$value){
		echo '<li class="'.($key==0?'first':'').'">';
		if(empty($value['Column_PopSubMenu'])){
			echo '<a '.(empty($value["Column_Link"])?'href="/api/'.$UsersID.'/web/column/'.$value["Column_ID"].'/':'href="'.$value["Column_LinkUrl"]).'"><img src="/static/api/web/skin/default/images/nav-dot.png" />'.$value['Column_Name'].'</a>';
		}else{
			echo '<dl>';
			$DB->get("web_article","Article_ID,Article_Title,Article_Link,Article_LinkUrl","where Users_ID='".$UsersID."' and Column_ID=".$value['Column_ID']." order by Article_CreateTime desc",10);
			$i=0;
			while($rsArticle=$DB->fetch_assoc()){
				echo '<dd class="'.($i==0?'first':'').'"><a '.(empty($rsArticle["Article_Link"])?'href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/"':'href="'.$rsArticle["Article_LinkUrl"].'"').'>'.$rsArticle['Article_Title'].'</a></dd>';
				$i++;
			}
			echo '</dl>';
			echo '<div><img src="/static/api/web/skin/default/images/nav-dot.png" />'.$value['Column_Name'].'</div>';
		}
		echo '</li>';
	}
	if(count($Column)>0){
		echo '</ul></footer>';
	}?>
<div id="global_support_point"></div>
<div id="global_support">
  <div class="bg"></div>
  <?php echo $Copyright;?></div>
 
<img src='/static/api/images/cover_img/web.jpg' class='shareimg'/>
</body>
</html>