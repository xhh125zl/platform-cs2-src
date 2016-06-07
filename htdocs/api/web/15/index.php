<?php
$Dwidth = array('640','420','210');
$DHeight = array('1010','210','210');

if(empty($rsSkin['Home_Json'])){
	for($no=1;$no<=3;$no++){
		$json[$no-1]=array(
			"ContentsType"=>$no==1?"1":"0",
			"Title"=>$no==1?json_encode(array("")):"",
			"ImgPath"=>$no==1?json_encode(array("/api/web/skin/".$rsSkin['Skin_ID']."/banner.jpg")):"/api/web/skin/".$rsSkin['Skin_ID']."/i".($no-2).".jpg",
			"Url"=>$no==1?json_encode(array("")):"",
			"Postion"=>"t0".$no,
			"Width"=>$Dwidth[$no-1],
			"Height"=>$DHeight[$no-1],
			"NeedLink"=>$no==1 ? "0" : "1"
		);
	}
}else{
	$Home_Json=json_decode($rsSkin['Home_Json'],true);
	for($no=1;$no<=3;$no++){
		$json[$no-1]=array(
			"ContentsType"=>$no==1?"1":"0",
			"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
			"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
			"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
			"Postion"=>"t0".$no,
			"Width"=>$Dwidth[$no-1],
			"Height"=>$DHeight[$no-1],
			"NeedLink"=>$no==1 ? "0" : "1"
		);
	}
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
	$('#header, #footer, #footer_points, #global_support, #global_support_point').hide();
	$('#skin_index_menu').show().css('z-index', '10');
	$('#skin_index_menu .bg').css('z-index', '2');
	$('#skin_index_menu a').css('z-index', '3');
	$('#web_skin_index').height($(window).height());
	$('#web_skin_index .box').css({position:'fixed', bottom:0, 'z-index':10});
	$('#web_skin_index .banner *').not('img').height($(window).height());
});
</script>
 <div id="web_skin_index">
    <div class="web_skin_index_list banner" rel="edit-t01">
        <div class="img"></div>
    </div>
    <div class="box">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="66%">
					<div class="web_skin_index_list cp-left list" rel="edit-t02">
						<div class="img"></div>
						<div class="bg"></div>
						<div class="text"></div>
					</div>
				</td>
				<td width="34%">
					<div class="web_skin_index_list list" rel="edit-t03">
						<div class="img"></div>
					</div>
				</td>
			</tr>
		</table>
    </div>
</div>
	<ul id="skin_index_menu">
    <?php
		$i=0;
	    $back = array('','#246CB6','#825A1D','#6A61A2','#5A9863','#BF7C7D','#4F83AE','#246CB6','#825A1D');
		$DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_NavDisplay=1 order by Column_Index asc");
		while($rsColumn=$DB->fetch_assoc()){
			$i++;
			if($i>=9){
				$i=1;
			}
			echo '<li><div class="bg" style="background:'.$back[$i].';"></div><a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'"><img src="'.$rsColumn["Column_ImgPath"].'" align="absmiddle" />'.$rsColumn["Column_Name"].'</a></li>';
	}?>
			</ul>
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
			echo $html.'<a href="'.(empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]).'">'.$rsColumn["Column_Name"].'</a></li>';
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