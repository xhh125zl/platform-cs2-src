<?php
$Dwidth = array('640','40','40');
$DHeight = array('320','40','40');
$Home_Json=json_decode($rsSkin['Home_Json'],true);
for($no=1;$no<=3;$no++){
	$json[$no-1]=array(
		"ContentsType"=>$no==1?"1":"0",
		"Title"=>$no==1?json_encode($Home_Json[$no-1]['Title']):$Home_Json[$no-1]['Title'],
		"ImgPath"=>$no==1?json_encode($Home_Json[$no-1]['ImgPath']):$Home_Json[$no-1]['ImgPath'],
		"Url"=>$no==1?json_encode($Home_Json[$no-1]['Url']):$Home_Json[$no-1]['Url'],
		"Postion"=> $no<=9 ? "t0".$no : "t".$no,
		"Width"=>$Dwidth[$no-1],
		"Height"=>$DHeight[$no-1],
		"NeedLink"=>$no==1?"1":"0"
	);
}
$ColumnID = array();
$ColumnInfo = $DB->GetRs("web_column","*","where Column_PopSubMenu=1 and Column_ParentID=0 order by Column_Index desc,Column_ID desc");
$ColumnID[] = empty($ColumnInfo["Column_ID"]) ? 0 : $ColumnInfo["Column_ID"];
$DB->Get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=".(empty($ColumnInfo["Column_ID"]) ? 0 : $ColumnInfo["Column_ID"]));
while($r=$DB->fetch_assoc()){
	$ColumnID[] = $r["Column_ID"];
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
var skin_index_init=function(){
	$('#header').hide();
	$('a').filter('[ajax_url]').off().each(function(){
		$(this).attr('href', $(this).attr('ajax_url'));
	});
	$('#web_skin_index_col_0 .more').click(function(){
		$('#web_skin_index_col_0, #art_list').fadeOut();
	});
}
</script>
<div id="web_skin_index">
    <div class="web_skin_index_list banner" rel="edit-t01">
        <div class="img"></div>
    </div>
	<div id="web_skin_index_col_0">
		<div class="web_skin_index_list txt" rel="edit-t02">
			<div class="img"></div>
			<div class="text"></div>
		</div>
		<div class="more_bg"></div>
		<a href="#" class="more"></a>
	</div>
	<div id="art_list">
		<div class="border">
        <?php $DB->Get("web_article","*","where Users_ID='".$UsersID."' and Column_ID in(".implode(",",$ColumnID).") order by Article_Index asc limit 0,4");
		while($rsArticle=$DB->fetch_assoc()){
			echo '<a href="/api/'.$UsersID.'/web/article/'.$rsArticle["Article_ID"].'/">
				<div class="item first">
					<div class="img"><img src="'.$rsArticle["Article_ImgPath"].'"></div>
					<div class="info">
						<h1>'.$rsArticle["Article_Title"].'</h1>
						<h2>'.$rsArticle["Article_BriefDescription"].'</h2>
					</div>
				</div>
			</a>';
		}
		?>
		</div>
	</div>
			<div id="web_skin_index_col_1">
			<div class="web_skin_index_list txt" rel="edit-t03">
				<div class="img"></div>
				<div class="text"></div>
			</div>
            <?php if($rsConfig['CallEnable']==1 && $rsConfig['CallPhoneNumber']<>''){?>
		     <a href="tel:<?php echo $rsConfig['CallPhoneNumber'];?>"><?php echo $rsConfig['CallPhoneNumber'];?></a>
		    <?php }?>
			
		</div>
		<div id="column_list">
		<?php
	 	$i=0;
		$DB->get("web_column","*","where Users_ID='".$UsersID."' and Column_ParentID=0 and Column_ID<>".$ColumnID[0]." order by Column_Index asc limit 0,4");
		while($rsColumn=$DB->fetch_assoc()){?>
		<a href="<?php echo (empty($rsColumn["Column_Link"])?'/api/'.$UsersID.'/web/column/'.$rsColumn["Column_ID"].'/':$rsColumn["Column_LinkUrl"]);?>">
		<div class="item first">
			<div class="img"><img src="<?php echo $rsColumn["Column_ImgPath"];?>"></div>
			<div class="info">
				<h1><?php echo $rsColumn["Column_Name"];?></h1>
				<h2><?php echo $rsColumn["Column_Description"];?></h2>
			</div>
			<div class="detail"><span></span></div>
		</div>
	</a>
	<?php }?>
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