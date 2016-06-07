<?php
$Home_Json=json_decode($rsSkin['Home_Json'],true);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig['SiteName'];?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/web/skin/default/style.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/web/js/web.js?t=<?php echo time();?>'></script>
<?php if($rsConfig['Animation']==1) echo '<script type="text/javascript" src="/static/js/plugin/animation/snow.js"></script>';?>
<?php if($rsConfig['Animation']==2) echo '<script type="text/javascript" src="/static/js/plugin/animation/fireworks.js"></script>';?>
<script language="javascript">
	var links=new Array('/api/<?php echo $UsersID ?>/web/');
	$(document).ready(web_obj.page_init);
</script>
</head>

<body>
<?php if($rsConfig["PagesShow"]){?>
<div id="PagesShow_blank"></div>
<script language="javascript">$("#PagesShow_blank").height($(window).height());</script>
<?php }?>
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
  <link href='/static/js/plugin/flexslider/flexslider.css' rel='stylesheet' type='text/css' />
  <link href='/static/api/web/skin/default/index.css' rel='stylesheet' type='text/css' />
  <script type='text/javascript' src='/static/js/plugin/flexslider/flexslider.js'></script> 
  <script type='text/javascript' src='/static/api/web/js/index.js?t=<?php echo time();?>'></script>
  <script language="javascript">
  	var web_skin_data=[];
	var MusicPath='<?php echo $rsConfig['MusicPath'] ?>';
 	$(document).ready(index_obj.index_init);
  </script> 
  <?php echo '<script type="text/javascript" src="/static/js/plugin/swipe/swipe.js"></script>' ?> 
  <script type="text/javascript">
	var skin_index_init=function(){
 		if($('#global_support').size()){
			$('#web_skin_index').css({'paddingBottom':'16px'});
		}
	};
  </script>
  <div id="web_skin_index">
<?php if(isset($Home_Json)){?>
    <?php foreach($Home_Json as $key=>$value){
	$url=explode('|',$value['url']);
	$pic=explode('|',$value['pic']);
	$txt=explode('|',$value['txt']);
	if($value['type']=='p0'){
		$txtColor=explode('|',$value['txtColor']);
		$bgColor=explode('|',$value['bgColor']);
		echo '<div class="packagep0Sprite wrap_content">
      <div class="wrapP0Item" style="margin:0;">
        <div class="p0Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
		if(!empty($txt[0])){
			echo '<div class="p0Word" style="color:'.$txtColor[0].'; background:'.$bgColor[0].';"><a href="'.$url[0].'" style="color:'.$txtColor[0].';">'.$txt[0].'</a></div>';
		}
		echo '</div>
      </div>
      <div class="wrapP0Item">
        <div class="p0Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
		if(!empty($txt[1])){
			echo '<div class="p0Word" style="color:'.$txtColor[1].'; background:'.$bgColor[1].';"><a href="'.$url[1].'" style="color:'.$txtColor[1].';">'.$txt[1].'</a></div>';
		}
		echo '</div>
      </div>
      <div class="wrapP0Item">
        <div class="p0Img"><a href="'.$url[2].'"><img src="'.$pic[2].'" width="100%" /></a>';
		if(!empty($txt[2])){
			echo '<div class="p0Word" style="color:'.$txtColor[2].'; background:'.$bgColor[2].';"><a href="'.$url[2].'" style="color:'.$txtColor[2].';">'.$txt[2].'</a></div>';
		}
		echo '</div>
      </div>
      <div class="clean"></div>
    </div>';
	}elseif($value['type']=='p1'){
		$txtColor=explode('|',$value['txtColor']);
		$bgColor=explode('|',$value['bgColor']);
		echo '<div class="packagep1Sprite wrap_content">
      <div class="wrapP1Item" style="margin:0;">
        <div class="p1Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
		if(!empty($txt[0])){
			echo '<div class="p1Word" style="color:'.$txtColor[0].'; background:'.$bgColor[0].';"><a href="'.$url[0].'" style="color:'.$txtColor[0].';">'.$txt[0].'</a></div>';
		}
		echo '</div>
      </div>
      <div class="wrapP1Item">
        <div class="p1Img"><a href="'.$url[1].'"><img src="'.$pic[1].'" width="100%" /></a>';
		if(!empty($txt[1])){
			echo'<div class="p1Word" style="color:'.$txtColor[1].'; background:'.$bgColor[1].';"><a href="'.$url[1].'" style="color:'.$txtColor[1].';">'.$txt[1].'</a></div>';
		}
		echo '</div>
      </div>
      <div class="clean"></div>
    </div>';
	}elseif($value['type']=='p2'){
		$txt[0] = str_replace('&quot;','"',$txt[0]);
		echo '<div class="packagep2Sprite wrap_content">
      <div class="p2word">'.$txt[0].'</div>
    </div>';
	}elseif($value['type']=='p3'){
		$txtColor=explode('|',$value['txtColor']);
		$bgColor=explode('|',$value['bgColor']);
		echo '<div class="packagep3Sprite wrap_content">
      <div class="wrapP3Item">
        <div class="p3Img"><a href="'.$url[0].'"><img src="'.$pic[0].'" width="100%" /></a>';
		if(!empty($txt[0])){
			echo '<div class="p3Word" style="color:'.$txtColor[0].'; background:'.$bgColor[0].';"><a href="'.$url[0].'" style="color:'.$txtColor[0].';">'.$txt[0].'</a></div>';
		}
		echo '</div>
      </div>
    </div>';
	}elseif($value['type']=='p4'){
		echo '<div id="p4_'.$rsSkin['Home_ID'].'" class="packagep4Sprite wrap_content">
      <ul>';
	  for($i=0;$i<count($pic);$i++){
		  if($pic[$i]<>'' && $pic[$i]<>'undefined'){
			  echo '<li><a href="'.$url[$i].'"><img src="'.$pic[$i].'" alt="'.($i+1).'" style="width:100%;vertical-align:top;"/></a></li>';
		  }
	  }
      echo '</ul>
    </div>
    <script>$(function(){new Swipe(document.getElementById("p4_'.$rsSkin['Home_ID'].'"), {speed:500,auto:3000})});</script>';
	}elseif($value['type']=='p5'){
		echo '<ul style="background:'.$value['bgColor'].'" class="packagep5Sprite wrap_content">';
		$tels=explode('<br>',$value['txt']);
		foreach($tels as $k=>$v){
			$tel=explode('/',$v);
			echo '<li style="'.(count($tels)>1?'':'width:100%').'"><a style="color:'.$value['txtColor'].'; font-size:'.$value['fontSize'].'px;" href="tel:'.$tel[0].'">'.$tel[0].(count($tel)>1 ? '('.$tel[1].')' :'').'</a></li>';
		}
		echo '</ul>';
	}
	}
}?>
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
<?php if($rsConfig["PagesShow"]){?>
<script type='text/javascript' src='/static/js/plugin/animation/pagesshow.js'></script>
<?php if($rsConfig["PagesShow"]==1){?>
	<script language="javascript">
		var showtime='<?php echo $rsConfig["ShowTime"];?>000';
		pagesshow_obj.url='<?php echo 'http://'.$_SERVER["HTTP_HOST"].$rsConfig["PagesPic"];?>';
		$(document).ready(pagesshow_obj.msk_init);
		window.onresize=function(){pagesshow_obj.msk_init(1)};
	</script>
<?php }?>
<?php if($rsConfig["PagesShow"]==2){?>
	<script language="javascript">
		var showtime='<?php echo $rsConfig["ShowTime"];?>000';
		$(document).ready(pagesshow_obj.fade_init);
		window.onresize=function(){pagesshow_obj.fade_init(1)};
	</script>
<?php }?>
<?php if($rsConfig["PagesShow"]==3){?>
	<script language="javascript">
		var showtime='<?php echo $rsConfig["ShowTime"];?>000';
		pagesshow_obj.url='<?php echo 'http://'.$_SERVER["HTTP_HOST"].$rsConfig["PagesPic"];?>';
		$(document).ready(pagesshow_obj.door_init);
		window.onresize=function(){pagesshow_obj.door_init(1)};
	</script>
<?php }?>
<div id="PagesShow"><img src="http://<?php echo $_SERVER["HTTP_HOST"].$rsConfig["PagesPic"];?>" /></div>
<?php }?>
</body>
</html>