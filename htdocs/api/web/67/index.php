<?php
$Dwidth = array('640');
$DHeight = array('410');

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
$(function(){
	$('a').filter('[ajax_url]').off().each(function(){
		$(this).attr('href', $(this).attr('ajax_url'));
	});
	$('#header,#global_support_point,#global_support').hide();
})
</script>
<div id="web_skin_index">
    <div class="web_skin_index_list banner" rel="edit-t01">
        <div class="img"></div>
    </div>
    <div id="column_index">
    	<?php
		$i=0;
		$DB->Get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 order by Column_Index asc,Column_ID desc");
		while($r=$DB->fetch_assoc()){
			if($i==5){
				$i=0;
			}		
		?>
		<div class="items bgcolor_<?php echo $i;?>">
            <a href="<?php echo (empty($r["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$r["Column_ID"].'/':$r["Column_LinkUrl"]);?>">
                <div class="cont">
                    <div class="lbar">
                    	<div class="name"><?php echo $r["Column_Name"];?></div>
                        <div class="intro"><?php echo $r["Column_Description"];?></div>
                    </div>
                    <div class="rbar"></div>
                    <div class="clear"></div>
                </div>
            </a>
        </div>
    	<?php
			$i++;
		}?>
    </div>
</div>
</div>
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