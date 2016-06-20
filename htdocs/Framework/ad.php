<?php
function ad($UsersID,$bit,$id) {
	global $DB;
    $guanggaoWRs = $DB->GetRs("ad_advertising", "*", "where Users_ID='" . $UsersID . "' and Model_ID=" . $id);
	
	if(empty($guanggaoWRs)) return '';
    $guanggaoRs = $DB->GetRs("ad_list", "*", "where Users_ID='" . $UsersID . "' and AD_IDS=" . $guanggaoWRs["AD_IDS"]." and AD_StarTime<".time()." and AD_EndTime>".time()." order by AD_ID desc");
	if(empty($guanggaoRs)) return '';
	if($guanggaoWRs["AD_Status"] == 0) return '';
    if ($guanggaoWRs["AD_Status"] == 1) {
		$html = '';
		$end = '';
		if($guanggaoRs["AD_Link"]){
			$html .='<a href="'.$guanggaoRs["AD_Link"].'">';
			$end = '</a>';
		}
		$img_url = json_decode($guanggaoRs["AD_Img"],true);
		echo '<div id="header_ad" style="clear:both; font-size:1px; width:100%"></div>'.$html.'<img id="header_img" src="'.$img_url[0].'" style="width:100%; display:block; position:fixed; '.($bit==1 ? 'top:0px;' : 'bottom:0px;').' left:0px; z-index:999999" />'.$end.'<script type="text/javascript">$(function(){$("#header_ad").animate({height:$("#header_img").height()}, 100);});</script>';
	}
}
?>
