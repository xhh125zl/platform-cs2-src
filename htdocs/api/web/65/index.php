<?php
$Dwidth = array('640');
$DHeight = array('360');

$Home_Json=json_decode($rsSkin['Home_Json'],true);
for($no=1;$no<=1;$no++){
	$json[$no-1]=array(
		"ContentsType"=>$no==1?"1":"0",
		"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
		"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
		"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
		"Postion"=>$no>9 ? "t".$no : "t0".$no,
		"Width"=>$Dwidth[$no-1],
		"Height"=>$DHeight[$no-1],
		"NeedLink"=>"1"
	);
}
?>
<?php require_once('header.php');?>
<?php if($rsConfig["PagesShow"]){?>
<div id="PagesShow_blank"></div>
<script language="javascript">$("#PagesShow_blank").height($(window).height());</script>
<?php }?>
<div id="web_page_contents">
<link href='/static/js/plugin/flexslider/flexslider.css' rel='stylesheet' type='text/css' />
<link href='/static/api/web/skin/<?php echo $rsConfig['Skin_ID'];?>/page.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/web/skin/<?php echo $rsConfig['Skin_ID'];?>/page_media.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/flexslider/flexslider.js'></script>
<script type='text/javascript' src='/static/api/web/js/index.js?t=<?php echo time();?>'></script>
<script language="javascript">
var web_skin_data=<?php echo json_encode($json) ?>;
var MusicPath='<?php echo $rsConfig['MusicPath'] ? $rsConfig['MusicPath'] : ''?>';
$(document).ready(index_obj.index_init);
</script>
<script language="javascript">
$(function(){
	$('#header, #footer, #footer_points').hide();
	if($('#global_support').size()){
		$('#global_support').css('bottom',0);
	}
	$('.touchslider-viewport, .touchslider-item').width($(document).width());
	$('.touchslider').touchSlider({
		mouseTouch:false,
		autoplay:false
	});
})
</script>
<script type='text/javascript' src='/static/js/plugin/touchslider/touchslider.min.js' ></script>
<div id="web_skin_index">
    <div class="web_skin_index_list banner" rel="edit-t01">
        <div class="img"></div>
    </div>
	<div id="web_contents">
		<?php
		$columns = array();
		$DB->Get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 order by Column_ID desc");
		while($r=$DB->fetch_assoc()){
			$columns[] = $r;
		}
		foreach($columns as $c){
			echo '<h1><span>'.$c["Column_Name"].'</span><a href="'.(empty($c["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$c["Column_ID"].'/':$c["Column_LinkUrl"]).'">更多</a></h1><div class="touchslider">
				<div class="img">
					<div class="touchslider-viewport">
						<div class="list">';
			$childlist = array();
			$childlist[] = $c["Column_ID"];
			$DB->Get("web_column","Column_ID","where Users_ID='".$UsersID."' and Column_ParentID=".$c["Column_ID"]." order by Column_ID desc");
			while($item=$DB->fetch_assoc()){
				$childlist[] = $item["Column_ID"];
			}
			$DB->Get("web_article","*","where Users_ID='".$UsersID."' and Column_ID in(".implode(",",$childlist).") order by Article_Index asc,Article_CreateTime desc");
			while($rsArticle=$DB->fetch_assoc()){
				echo '<div class="touchslider-item">
									<dl>
											<dd>
												<div class="item_img"><a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/"><img src="'.$rsArticle["Article_ImgPath"].'" /></a></div>
												<div class="a"><a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">'.$rsArticle["Article_Title"].'</a></div>
											</dd>
									</dl>
								</div>';
			}
			echo '			</div>
					</div>
				</div>
			</div>';
		}
		?>
	</div>
</div></div>
<div id="footer_points"></div>
<footer id="footer">
	<ul>
     <?php
	 	$i=0;
		$DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc limit 0,4");
		while($rsColumn=$DB->fetch_assoc()){
			$i++;
			$html = $i==1 ? '<li class="first">' : '<li>';
			echo $html.'<a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><img src="/static/api/web/skin/default/images/nav-dot.png" />'.$rsColumn["Column_Name"].'</a></li>';
	}?>
	</ul>
</footer>
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
<?php require_once('footer.php');?>