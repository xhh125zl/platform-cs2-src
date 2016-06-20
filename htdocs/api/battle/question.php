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
$json = $rsMaterial = $DB->GetRs("wechat_material", "Material_Json", "where Users_ID='" . $UsersID . "' and Material_Table='battle' and Material_TableID=" . $BattleID);
$intro = $img_url = '';
if ($json) {
    $rsMaterial = json_decode($json['Material_Json'], true);
    $img_url = $rsMaterial["ImgPath"];
    $intro = $rsMaterial["TextContents"];
}
$_SESSION[$UsersID."HTTP_REFERER"] = "/api/" . $UsersID . "/battle/question/" . $BattleID . "/";
$rsUsers = $DB->GetRs("users", "*", "where Users_ID='" . $UsersID . "'");
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
$UserInfo = $DB->GetRs("user", "*", "where User_ID=" . $_SESSION[$UsersID."User_ID"]);
$_SESSION[$UsersID."User_Integral"] = $UserInfo["User_Integral"];
$_SESSION[$UsersID."User_NickName"] = $UserInfo["User_NickName"];
$_SESSION[$UsersID."User_HeadImg"] = $UserInfo["User_HeadImg"];
if ($actid > 0) {
    if (isset($_POST["Act_End_Time"]) && $_POST["Act_End_Time"] == 'Act_End_Time') {
        $rsStar_Time = $DB->GetRs("battle_sn", "SN_CreateTime,Act_ID,Act_Time", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Act_ID=" . $actid);
        $end_time_arr = explode(' ', microtime());
        $end_time = $end_time_arr[1] + $end_time_arr[0];
        $act_time = $end_time - $rsStar_Time["SN_CreateTime"];
        $Data = array(
            "Act_Time" => $act_time,
        );
        if (empty($rsStar_Time["Act_Time"])) {
            $DB->Set("battle_sn", $Data, "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Battle_ID=" . $BattleID . " and Act_ID=" . $rsStar_Time["Act_ID"]);
        }
    }
}
if ($rsBattle) {
    $fromtime = strtotime(date("Y-m-d") . " 00:00:00");
    $totime = strtotime(date("Y-m-d") . " 23:59:59");
    $rsCount = $DB->GetRs("battle_sn", "count(SN_ID) as SN_Count", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and SN_CreateTime>" . $fromtime . " and SN_CreateTime<" . $totime . " order by Battle_ID desc");
    if ($rsCount['SN_Count'] >= $rsBattle['Battle_EveryDayLotteryTimes']) {
        header("location:/api/" . $UsersID . "/battle/");
        exit;
    }
    //加入访问记录
    $Data = array(
        "Users_ID" => $UsersID,
        "S_Module" => "battle",
        "S_CreateTime" => time()
    );
    $DB->Add("statistics", $Data);
    if ($actid > 0) {
        $rsPK = $DB->GetRs("battle_sn", "count(SN_ID) as count", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Act_ID=" . $actid);
        $Boss = $DB->GetRs("battle_act", "User_ID", "where Users_ID='" . $UsersID . "' and Act_ID=" . $actid);
        if (!empty($rsPK['count'])) {
            $actid = 0; //一次押注结束才能重新记录！
            
        }
    }
    //开始答题
    $Data = array(
        "Users_ID" => $UsersID,
        "User_ID" => $_SESSION[$UsersID."User_ID"],
        "Battle_ID" => $BattleID,
        "User_Name" => isset($_SESSION[$UsersID."User_NickName"]) ? $_SESSION[$UsersID."User_NickName"] : '',
        "User_Mobile" => isset($_SESSION[$UsersID."User_Mobile"]) ? $_SESSION[$UsersID."User_Mobile"] : '',
        "User_Head" => isset($_SESSION[$UsersID."User_HeadImg"]) ? $_SESSION[$UsersID."User_HeadImg"] : '/static/api/zhuli/images/user.jpg',
        "SN_Integral" => 0,
        "SN_CreateTime" => time() ,
        "Act_ID" => $actid,
        "Boss_ID" => isset($Boss["User_ID"]) ? $Boss["User_ID"] : 0,
    );
    if (!isset($_POST["Act_End_Time"])) $DB->Add("battle_sn", $Data);
    $_SESSION[$UsersID."SN_Integral"] = empty($_SESSION[$UsersID."SN_Integral"]) ? 0 : $_SESSION[$UsersID."SN_Integral"];
    $DB->query("SELECT * FROM `battle_exam` AS a JOIN ( SELECT ROUND(RAND() * ( ( SELECT MAX(Exam_ID) FROM `battle_exam` ) - ( SELECT MIN(Exam_ID) FROM `battle_exam` ) ) + ( SELECT MIN(Exam_ID) FROM `battle_exam` ) ) AS Rand_ID ) AS b WHERE a.Users_ID='" . $UsersID . "' and a.Exam_ID >= b.Rand_ID ORDER BY a.Exam_ID LIMIT 1");
    $rsExam = $DB->fetch_assoc();
    if (empty($rsExam)) {
        echo '题库中无题可出';
        exit;
    }
} else {
    echo '未开通一战到底';
    exit;
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
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/battle.css?t=<?php
echo time(); ?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/js/battle.js?t=<?php
echo time(); ?>'></script>
<script type="text/javascript">
var UsersID = '<?php
echo $UsersID; ?>';
var ActID = <?php
echo $actid; ?>;
$(function(){
	$(window).load(function(){
		battle_obj.remainTime($("#hidLimitTime").val()); //超出的答题时间
		setTimeout(function(){
			battle_obj.selectAnswer();
		}, 1000);
		battle_obj.nextQuestion();
		global_obj.share_init({
		'img_url':'http://'+document.domain+'<?php
echo $img_url; ?>',
		'img_width':100,
		'img_height':100,
		'link':window.location.href,
		'desc':'<?php
echo $intro; ?>',
		'title':'<?php
echo $rsBattle["Battle_Title"]; ?>'
	});
	});
});
</script>
</head>

<body>
<div id="notice-item">
  <div class="timesUpImg"><img src="/static/api/images/battle/timeout.png" /></div>
  <div class="timeNotice">啊哦，答题超时了</div>
  <a class="timeBtn next-btn" href="#">进入下一题</a> </div>
<div id="wrong-item">
  <div class="wrongImg"><img src="/static/api/images/battle/wrong.png" /></div>
  <div class="wrongNotice">对不起，你答错了</div>
  <a class="timeBtn next-btn"  href="#">进入下一题</a> </div>
<div id="right-item">
  <div class="rightImg"><img src="/static/api/images/battle/rigth.png" /></div>
  <div class="rightNotice">恭喜您，答对了</div>
  <a class="timeBtn next-btn"  href="#">进入下一题</a> </div>
  
  
<div id="source">
  <div class="source-item">
    <div class="square sqfirst">2</div>
    <div class="square">0</div>
    <div class="maohao">:</div>
    <div class="square">0</div>
    <div class="square">0</div>
    <em></em>
    <div class="clean"></div>
    . </div>
<div class="source-title">
<div style="width:55px; height:55px; border-radius:50%; overflow:hidden;">
<img src="<?php
echo isset($_SESSION[$UsersID."User_HeadImg"]) ? $_SESSION[$UsersID."User_HeadImg"] : '/static/api/zhuli/images/user.jpg'; ?>" width="100%" />
</div>
<span>
<?php
echo $_SESSION[$UsersID."User_NickName"]; ?><br />
积分:<?php
echo $_SESSION[$UsersID."User_Integral"]; ?>
</span>
</div>
<?php
$battle_sn = $DB->GetRs("battle_sn", "sum(SN_Integral) as amount", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Battle_ID=" . $BattleID);
?>
  <div class="fenshu">总得分<div><?php
echo empty($battle_sn["amount"]) ? 0 : $battle_sn["amount"]; ?></div></div>
</div>

<div id="q2">
  <div class="q2_content">
    <div class="questionTitle">
      <div class="questionFrame">第<b class="currentNum">1</b>题：<?php
echo $rsExam['Exam_Name'] ?></div>
      <div class="clean"></div>
    </div>
    <div currentId="<?php
echo $rsExam['Exam_ID'] ?>" class="questionList">A. <?php
echo $rsExam['Exam_AnswerA'] ?></div>
    <div currentId="<?php
echo $rsExam['Exam_ID'] ?>" class="questionList">B. <?php
echo $rsExam['Exam_AnswerB'] ?></div>
    <div currentId="<?php
echo $rsExam['Exam_ID'] ?>" class="questionList">C. <?php
echo $rsExam['Exam_AnswerC'] ?></div>
    <div currentId="<?php
echo $rsExam['Exam_ID'] ?>" class="questionList">D. <?php
echo $rsExam['Exam_AnswerD'] ?></div>
  </div>
</div>
<div class="b8"></div>
<input type="hidden" id="hidUrl" value="/api/<?php
echo $UsersID
?>/battle/get_exam/" />
<input type="hidden" id="hidBAId" value="<?php
echo $BattleID
?>" />
<input type="hidden" id="hidExamID" value="" />
<input type="hidden" id="hidAllQuestionNum" value="<?php
echo $rsBattle['Battle_QuestionNum'] ?>" />
<input type="hidden" id="hidIsSound" value="<?php
echo $rsBattle['Battle_IsSound'] ?>" />
<input type="hidden" id="hidLimitTime" value="<?php
echo $rsBattle['Battle_LimitTime'] ?>" />
<input type="hidden" id="hidCurrentExamID" value="<?php
echo $rsExam['Exam_ID'] ?>" />
</body>
</html>