<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
if (isset($_GET["UsersID"])) {
    $UsersID = $_GET["UsersID"];
} else {
    echo '缺少必要的参数';
    exit;
}
$actid = 0;
if (!strpos($_GET["UsersID"], "_")) {
    $UsersID = $_GET["UsersID"];
} else {
    $arr = explode("_", $_GET["UsersID"]);
    $UsersID = $arr[0];
    $actid = $arr[1];
}

if (isset($_GET["BattleID"])) {
    $BattleID = $_GET["BattleID"];
    $rsBattle = $DB->GetRs("battle", "*", "where Users_ID='" . $UsersID . "' and Battle_ID=" . $BattleID);
} else {
    $rsBattle = $DB->GetRs("battle", "*", "where Users_ID='" . $UsersID . "' order by Battle_ID desc");
    $BattleID = $rsBattle["Battle_ID"];
}
$_SESSION[$UsersID."HTTP_REFERER"] = "/api/battle/index.php?UsersID=" . $UsersID;
if ($rsBattle) {
    $rsUsers = $DB->GetRs("users", "*", "where Users_ID='" . $UsersID . "'");
    $is_login = 1;
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
} else {
    echo '未开通一战到底';
    exit;
}
$myact = 0;
//加入挑战
$actinfo_self = $DB->GetRs("battle_act", "*", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]);
if (!$actinfo_self) {
    $data = array(
        'Users_ID' => $UsersID,
        'User_ID' => $_SESSION[$UsersID."User_ID"],
        'Act_Time' => time()
    );
    $flag = $DB->Add("battle_act", $data);
}
if (empty($actid)) {
    if ($actinfo_self) {
        $actid = $actinfo_self["Act_ID"];
        header("Location:/api/battle/index.php?UsersID=" . $UsersID . "_" . $actid);
    }
}
if ($actid > 0) {
    $actinfo = $DB->GetRs("battle_act", "*", "where Users_ID='" . $UsersID . "' and Act_ID=" . $actid);
    if ($actinfo) {
        //获取我的朋友
        $rsList = $DB->query("SELECT sum(SN_Integral) as score,User_ID,User_Name,User_Head FROM battle_sn where Users_ID='" . $UsersID . "' and Boss_ID=".$_SESSION[$UsersID."User_ID"]." and Battle_ID=".$BattleID." group by User_ID order by sum(SN_Integral) desc, Act_Time asc");
        $firend = $DB->toArray($rsList);
		foreach ($firend as $key => $item) {
			$firend[$key]['rank'] = $key + 1;
		}
        if ($_SESSION[$UsersID."User_ID"] == $actinfo["User_ID"]) {
            $myact = 1;//自己的活动
        }
    }
}
//押注开始
if (isset($_POST["Send_Integral"]) && !empty($_POST["Send_Integral"])) {
    if ($actinfo_self["Act_Score"]) {
        $Data = array(
            "status" => 1,
            "msg" => '你已经有押注了！' . $actinfo_self["Act_Score"] . '积分！赶快分享好友挑战吧！',
        );
    } else {
        $rsJifen = $DB->GetRs("user", "User_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]);
        if ($rsJifen["User_Integral"] < $_POST["Send_Integral"]) {
            $Data = array(
                "status" => 0,
                "msg" => '积分不足！',
            );
        } else {
            $battle_act_data = array(
                "Act_Score" => $_POST["Send_Integral"],
            );
            $DB->Set("battle_act", $battle_act_data, "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]); //记录押注
            $Data = array(
                "status" => 1,
                "msg" => '押注成功！赶紧分享好友发起挑战吧！',
            );
        }
    }
    echo json_encode($Data, JSON_UNESCAPED_UNICODE);
    exit;
}
//分享好友成功
if (isset($_POST["action_do"]) && $_POST["action_do"] == 'share') {
    $Data = array(
        "status" => 1,
        "msg" => '',
        "url" => "/api/battle/question.php?UsersID=" . $UsersID . "_" . $actid,
    );
    echo json_encode($Data, JSON_UNESCAPED_UNICODE);
    exit;
}
//接受PK
if (isset($_POST['action']) && $_POST["action"] == "PK") {
    $actinfo = $DB->GetRs("battle_act", "*", "where Users_ID='" . $UsersID . "' and Act_ID=" . $_POST["actid"]);
    if (!$actinfo) {
        $Data = array(
            "status" => 0,
            "msg" => '不存在该活动'
        );
    } else {
        if (!$actinfo["Act_Score"]) {
            $Data = array(
                "status" => 0,
                "msg" => '本次PK已经结束！',
                "url" => "/api/battle/index.php?UsersID=" . $UsersID,
            );
        } else {
            $rsJifen = $DB->GetRs("user", "User_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]);
            if ($rsJifen["User_Integral"] < $actinfo["Act_Score"]) {
                $Data = array(
                    "status" => 0,
                    "msg" => '积分不足！',
                );
            } else {
                $PK_Info = $DB->GetRs("battle_sn", "count(SN_ID) as count", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Act_ID=" . $_POST["actid"]);
                if ($PK_Info["count"]) {
                    $Data = array(
                        "status" => 0,
                        "msg" => '你已经挑战过啦！',
                    );
                } else {
                    $Data = array(
                        "status" => 1,
                        "msg" => '接受成功！开始挑战！',
                        "url" => "/api/battle/question.php?UsersID=" . $UsersID . "_" . $_POST["actid"],
                    );
                }
            }
        }
    }
    echo json_encode($Data, JSON_UNESCAPED_UNICODE);
    exit;
}
$fromtime = strtotime(date("Y-m-d") . " 00:00:00");
$totime = strtotime(date("Y-m-d") . " 23:59:59");
$rsCount = $DB->GetRs("battle_sn", "count(SN_ID) as SN_Count", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and SN_CreateTime>" . $fromtime . " and SN_CreateTime<" . $totime . " order by Battle_ID desc");
$json = $rsMaterial = $DB->GetRs("wechat_material", "Material_Json", "where Users_ID='" . $UsersID . "' and Material_Table='battle' and Material_TableID=" . $BattleID);
$img_url = '';
$intro = '';
if ($json) {
    $rsMaterial = json_decode($json['Material_Json'], true);
    $img_url = $rsMaterial["ImgPath"];
    $intro = $rsMaterial["TextContents"];
}
//排行榜

$rsRank = $DB->query("SELECT sum(SN_Integral) as score,User_ID,User_Name,User_Head FROM battle_sn where Users_ID='" . $UsersID . "' and Battle_ID=".$BattleID." group by User_ID order by sum(SN_Integral) desc, Act_Time asc");
$rank = $DB->toArray($rsRank);
foreach ($rank as $key => $item) {
    $rank[$key]['rank'] = $key + 1;
}

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();

//自定义分享
if(!empty($share_config)){
	$share_flag = 1;
	$signature = $share_config["signature"];
	$share_config["title"] = $rsBattle["Battle_Title"];
	$share_config["desc"] = '你的好友向你发起挑战';
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/battle.jpg';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php
echo $rsBattle["Battle_Title"]; ?></title>
<link href='/static/css/global.css?t=<?php
echo time(); ?>' rel='stylesheet' type='text/css' />
<link href='/static/api/css/battle.css?t=<?php
echo time(); ?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php
echo time(); ?>'></script>
<script type='text/javascript' src='/static/api/js/battle.js?t=<?php
echo time(); ?>'></script>
<script type="text/javascript">
var share_flag = <?php
echo $share_flag; ?>;
var signature = "<?php
echo $signature; ?>";
var UsersID = '<?php
echo $UsersID; ?>';
var ActID = <?php
echo $actid; ?>;
$(function(){
	battle_obj.user_init();
<?php
if (empty($actinfo["Act_Score"])) { ?>
	global_obj.hide_opt_menu();
<?php
} ?>
<?php
if ($rsCount['SN_Count'] >= $rsBattle['Battle_EveryDayLotteryTimes']) { ?>
	battle_obj.page_init();
	<?php
} ?>
	
});</script>
</head>

<body>
<?php
ad($UsersID, 1, 5);
?>
<header><img src="/static/api/images/battle/header.png" width="100%" /></header>
<div id="content">
  <div class="con-item">
<?php
if ($rsCount['SN_Count'] < $rsBattle['Battle_EveryDayLotteryTimes']) { ?>
   <?php
    if ($myact == 1) { ?>
<div class="ci1">
<?php 
$act_sn = $DB->GetRs("battle_sn", "Act_ID", "where Users_ID='" . $UsersID . "' and Act_ID=" . $actid ." and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Battle_ID=" . $BattleID);
if($actinfo_self["Act_Score"] && empty($act_sn["Act_ID"])){
?>
    <a href="/api/<?php
        echo $UsersID."_".$actid
?>/battle/question/<?php
        echo $BattleID
?>/" class="kstz">开始挑战</a>
<?php } else{?>
    <a href="/api/<?php
        echo $UsersID
?>/battle/question/<?php
        echo $BattleID
?>/" class="kstz">开始挑战</a>
<?php }?>
    <div id="send_friends">向好友发起挑战</div>
</div>
   <?php
    } else { ?>
    <div class="tz_tips">
    <div class="tz_tips_bg"></div>
    <?php echo '<div class="tz_tips_text"><b>提示：</b>对方押注'.$actinfo["Act_Score"].'积分！<br />挑战成功则获取'.$actinfo["Act_Score"].'积分，如果失败则扣除'.$actinfo["Act_Score"].'积分!</div>'; ?>
    </div>
    <div id="do_action">接受挑战</div>
   <?php
    } ?>
<?php
} ?>


</div>
  <div class="clean"></div>
</div>
<div id="footer">
  <div class="main_bottom">
    <div class="main_content">
      <div class="btns">
        <p class="cur" rel="0">游戏规则</p>
        <p rel="1">看看谁最牛</p>
        <p rel="2" class="my_btn">好友pk榜</p>
        <div class="clear"></div>
      </div>
      <div class="stores">
        <div id="rank_list_0" style="display: block;">
          
          <h3>活动规则</h3>
          <p style="padding:0px 8px">
                 <?php for($i=1;$i<=5;$i++){
		if(!empty($rsBattle['Battle_Game'.$i])){
			echo $i.".&nbsp;&nbsp;".$rsBattle['Battle_Game'.$i]."<br />";
		}
	}?>
          </p>
          <h3>积分规则</h3>
          <p style="padding:0px 8px"> 
          <?php for($i=1;$i<=5;$i++){
		if(!empty($rsBattle['Battle_Game'.$i])){
			echo $i.".&nbsp;&nbsp;".$rsBattle['Battle_Rule'.$i]."<br />";
		}
	}?>
           </p>
          <h3>活动时间</h3>
          &nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('Y-m-d h:m',$rsBattle["Battle_StartTime"]) . " ~ " . date('Y-m-d h:m',$rsBattle["Battle_EndTime"])?></div>
        <div id="rank_list_1" style="display: none;">
          <table class="zebra">
            <thead>
              <tr>
                <th>排名</th>
                <th>头像</th>
                <th>昵称</th>
                <th>积分</th>
              </tr>
            </thead>
            <tbody>
              <!--奖品列表 开始-->
              
     <?php foreach($rank as $R){
	 if($R["score"]>0){
	 ?>
  	 	<tr>
       		<td align="center"><?php echo $R["rank"];?></td>
        	<td align="center"><img src="<?php echo $R["User_Head"] ? $R["User_Head"] : '/static/api/zhuli/images/user.jpg';?>" width="50" height="50"/></td>
        	<td align="center"><?php echo $R["User_Name"] ? $R["User_Name"] : '路人甲';?></td>
        	<td align="center"><?php echo $R["score"];?></td>
    	</tr>        
   <?php }}?>
              
              <!--奖品列表 结束-->
              
            </tbody>
          </table>
        </div>
        <div id="rank_list_2" style="display: none;">
          <table class="zebra">
            <thead>
              <tr>
                <th>编号</th>
                <th>头像</th>
                <th>昵称</th>
                <th>积分</th>
              </tr>
            </thead>
            <tbody>
               <?php foreach($firend as $k=>$R){
			   if($R["score"]>0){
			   ?>
  	 	<tr>
       		<td align="center"><?php echo $R["rank"];?></td>
        	<td align="center"><img src="<?php echo $R["User_Head"] ? $R["User_Head"] : '/static/api/zhuli/images/user.jpg';?>" width="50" height="50"/></td>
        	<td align="center"><?php echo $R["User_Name"] ? $R["User_Name"] : '路人甲';?></td>
        	<td align="center"><?php echo $R["score"];?></td>
    	</tr>        
   <?php }}?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="hidNotice" value="<?php
if ($rsCount['SN_Count'] >= $rsBattle['Battle_EveryDayLotteryTimes']) {
    echo '亲，您今日的参与次数已经用完了，请下次再来吧！';
} ?>" />
<?php if(!empty($share_config)){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_config["appId"];?>",   
		   timestamp:<?php echo $share_config["timestamp"];?>,
		   nonceStr:"<?php echo $share_config["noncestr"];?>",
		   url:"<?php echo $share_config["url"];?>",
		   signature:"<?php echo $share_config["signature"];?>",
		   title:"<?php echo $share_config["title"];?>",
		   desc:"<?php echo str_replace(array("\r\n", "\r", "\n"), "", $share_config["desc"]);?>",
		   img_url:"<?php echo $share_config["img"];?>",
		   link:""
		};
		
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
<div class='share_layer'><img src='/static/api/images/battle/share.png' /></div>
</body>
</html>
