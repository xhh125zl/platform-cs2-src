<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsColumn["Column_Name"] ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/web/skin/default/style.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/web/web.js'></script>
<?php if($rsConfig['Animation']==1) echo '<script type="text/javascript" src="/static/js/plugin/animation/snow.js"></script>';?>
<?php if($rsConfig['Animation']==2) echo '<script type="text/javascript" src="/static/js/plugin/animation/fireworks.js"></script>';?>
<script language="javascript">
	var links=new Array('/api/<?php echo $UsersID ?>/web/');
	$(document).ready(web_obj.page_init);
</script>
</head>

<body>
<div id="web_page_contents_loading"><img src="/static/api/images/ico/loading.gif" /></div>
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
  <div class="wrap" id="columnchild">
  <?php if($rsColumn["Column_ChildTypeID"]==0){?>
   <div class="list-type-0">
    <?php
	$DB->Get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=".$ColumnID." order by Column_Index asc,Column_ID desc");
	while($rsChild=$DB->fetch_assoc()){
		echo '<a href="'.(empty($rsChild["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsChild["Column_ID"].'/':$rsChild["Column_LinkUrl"]).'">
				<div class="item">';
		echo $rsChild["Column_ImgPath"] ? '<div class="img"><img src="'.$rsChild["Column_ImgPath"].'"></div>' : '';
		echo '<div class="info"><h2>'.$rsChild["Column_Name"].'</h2></div><div class="detail"><span></span></div>
				</div>
			</a>';
	}
	?>
	</div>
  <?php }elseif($rsColumn["Column_ChildTypeID"]==1){?>
   <div class="list-type-1">
	<div class="list">
    <?php
	$DB->Get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=".$ColumnID." order by Column_Index asc,Column_ID desc");
	while($rsChild=$DB->fetch_assoc()){
		echo '<a href="'.(empty($rsChild["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsChild["Column_ID"].'/':$rsChild["Column_LinkUrl"]).'">
			<div class="item">
				<div>
					<ul>
						<li class="img"><img src="'.$rsChild["Column_ImgPath"].'"></li>
						<li class="title">'.$rsChild["Column_Name"].'</li>
					</ul>
				</div>
			</div>
		</a>';
	}
	?>
		<div class="clear"></div>
	</div>	
   </div>
  <?php }?>
   <input type="hidden" name="ShareTitle" value="<?php echo $rsColumn["Column_Name"] ?>" />
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