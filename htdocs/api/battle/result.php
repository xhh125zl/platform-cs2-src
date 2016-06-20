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
$_SESSION[$UsersID."HTTP_REFERER"] = "/api/" . $UsersID . "/battle/result/" . $BattleID . "/";
if ($rsBattle) {
    $rsUsers = $DB->GetRs("users", "*", "where Users_ID='" . $UsersID . "'");
    $is_login = 1;
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
    $SN_Integral = $_SESSION[$UsersID."SN_Integral"] * $rsBattle['Battle_Integral'];
    $User_ID = $_SESSION[$UsersID."User_ID"];
    $Data = array(
        "SN_Integral" => $SN_Integral
    );
    $rsSN = $DB->GetRs("battle_sn", "SN_ID,SN_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Battle_ID=" . $BattleID . " order by SN_ID desc");
    if (empty($rsSN['SN_Integral'])) {
        $_SESSION[$UsersID."SN_Integral"] = 0;
        $DB->Set("battle_sn", $Data, "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Battle_ID=" . $BattleID . " and SN_ID=" . $rsSN['SN_ID']);
        $rsUser = $DB->GetRs("user", "User_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]);
        function max_arr($array) {
            //搜索最大元素
            foreach ($array as $key => $val) if (empty($maxval) || $val > $maxval) $maxval = $val;
            //构造新的数组
            foreach ($array as $key => $val) if ($val == $maxval) $max_arr[$key] = $val;
            return $max_arr;
        };
        function min_arr($array) {
            //搜索最小元素
            foreach ($array as $key => $val) if (empty($maxval) || $val < $maxval) $maxval = $val;
            //构造新的数组
            foreach ($array as $key => $val) if ($val == $maxval) $max_arr[$key] = $val;
            return $max_arr;
        };
		$score_flag = 0;
		$flag = 1;
        if ($actid > 0) {
            $act_score = $DB->GetRs("battle_act", "User_ID,Act_Score", "where Users_ID='" . $UsersID . "' and Act_ID=" . $actid);
            $act_sn = $DB->Get("battle_sn", "SN_Integral,User_ID", "where Users_ID='" . $UsersID . "' and Act_ID=" . $actid . " and Battle_ID=" . $BattleID);
            if ($act_sn) {
                while ($r = $DB->fetch_assoc()) {
                    $list_sn[] = $r;
                }
            }
            if (count($list_sn) >= 2) { //两人以上参加
                $score_flag = 1;
                foreach ($list_sn as $key => $val) {
                    $SN_Integral_arr[$val["User_ID"]] = $val["SN_Integral"];
                }
                $max_SN_Integral = max_arr($SN_Integral_arr); //赢家ids
                $boss_win = 0; //庄家输赢
                $guests_flag = 0; //嘉宾标示
                if (count($max_SN_Integral) > 1) { //如果分数有相同的 则比较答题时间
                    foreach ($max_SN_Integral as $k => $v) {
                        $item[$k] = $DB->GetRs("battle_sn", "Act_Time", "where Users_ID='" . $UsersID . "' and Act_ID=" . $actid . " and User_ID=" . $k);
                    }
                    foreach ($item as $k1 => $v1) {
                        $narr[$k1] = $v1['Act_Time'];
                    }
                    $min_act_time_arr = min_arr($narr); //确定最终赢家
                    $max_act_time_arr = max_arr($narr); //确定最终输家
                    foreach ($min_act_time_arr as $k2 => $v2) {
                        if ($act_score["User_ID"] == $k2) { //庄家赢 输家赔
                            $boss_win = 1;
                            foreach ($max_act_time_arr as $k3 => $v3) {
                                $guests_flag = 1;
                                $SN_Integral = - $act_score["Act_Score"];
                                $User_ID = $k3;
                            }
                        } else { //庄家输 赢家赚
                            $boss_win = 0;
                            foreach ($min_act_time_arr as $k3 => $v3) {
                                $guests_flag = 1;
                                $SN_Integral = $act_score["Act_Score"];
                                $User_ID = $k3;
                            }
                        }
                    }
                } else { //如果从积分就可以比较的话
                    $min_SN_Integral = min_arr($SN_Integral_arr); //输家ids
                    foreach ($max_SN_Integral as $k => $v) {
                        if ($act_score["User_ID"] == $k) { //庄家赢 输家赔
                            $boss_win = 1;
                            foreach ($min_SN_Integral as $k1 => $v1) {
                                $guests_flag = 1;
                                $SN_Integral = - $act_score["Act_Score"];
                                $User_ID = $k1;
                            }
                        } else { //庄家输 赢家赚
                            $boss_win = 0;
                            foreach ($min_SN_Integral as $k1 => $v1) {
                                $guests_flag = 1;
                                $SN_Integral = $act_score["Act_Score"];
                                $User_ID = $k1;
                            }
                        }
                    }
                }
                //结果对比之后  积分入库
                if ($boss_win == 1) {
                    $DB->Set("battle_sn", array(
                        "SN_Integral" => $act_score["Act_Score"] * 2
                    ) , "where Users_ID='" . $UsersID . "' and User_ID=" . $act_score["User_ID"] . " and Battle_ID=" . $BattleID . " and Act_ID=" . $actid);
                    $rsUser = $DB->GetRs("user", "User_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $act_score["User_ID"]);
                    $flag = $DB->Set("user", array(
                        "User_Integral" => $rsUser['User_Integral'] + $act_score["Act_Score"] * 2
                    ) , "where User_ID=" . $act_score["User_ID"]);
                } else {
                    $DB->Set("battle_sn", array(
                        "SN_Integral" => - $act_score["Act_Score"]
                    ) , "where Users_ID='" . $UsersID . "' and User_ID=" . $act_score["User_ID"] . " and Battle_ID=" . $BattleID . " and Act_ID=" . $actid);
                    $rsUser = $DB->GetRs("user", "User_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $act_score["User_ID"]);
                    $flag = $DB->Set("user", array(
                        "User_Integral" => $rsUser['User_Integral'] - $act_score["Act_Score"]
                    ) , "where User_ID=" . $act_score["User_ID"]);
                }
                if ($guests_flag == 1) {
                    $Data1 = array(
                        "SN_Integral" => $SN_Integral
                    );
                    $DB->Set("battle_sn", $Data1, "where Users_ID='" . $UsersID . "' and User_ID=" . $User_ID . " and Battle_ID=" . $BattleID . " and Act_ID=" . $actid);
                    $rsUser = $DB->GetRs("user", "User_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $User_ID);
                    $Data2 = array(
                        "User_Integral" => $rsUser['User_Integral'] + $SN_Integral
                    );
                    $flag = $DB->Set("user", $Data2, "where User_ID=" . $User_ID);
                }
            }
        } else { //非挑战闯关
            $Data2 = array(
                "User_Integral" => $rsUser['User_Integral'] + $SN_Integral
            );
            $flag = $DB->Set("user", $Data2, "where User_ID=" . $User_ID);
        }
    }
    if ($flag) {
        $Data0 = array(
            'Record_Integral' => $SN_Integral,
            'Record_SurplusIntegral' => $rsUser['User_Integral'] + $SN_Integral,
            'Operator_UserName' => '',
            'Record_Type' => empty($actid) ? 2 : 9,
            'Record_Description' => empty($actid) ? '一战到底获取积分' : '一站到底好友挑战积分记录',
            'Record_CreateTime' => time() ,
            'Users_ID' => $UsersID,
            'User_ID' => $_SESSION[$UsersID."User_ID"]
        );
        $DB->Add('user_Integral_record', $Data0);
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
		$weixin_message = new weixin_message($DB,$UsersID,$_SESSION[$UsersID."User_ID"]);
		$contentStr = "一战到底获取".$SN_Integral."积分";
		$weixin_message->sendscorenotice($contentStr);
    }
} else {
    echo '未开通一战到底';
    exit;
}
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta charset="utf-8">
<title><?php
echo $rsBattle["Battle_Title"]; ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/battle.css?t=<?php
echo time(); ?>' rel='stylesheet' type='text/css' />
</head>

<body style="background-color:#0aa6eb">
<div id="result1"></div>
<div id="result2">
  <div class="re-show">
 <?php
if ($score_flag == 1) {
    $rsSN2 = $DB->GetRs("battle_sn", "SN_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Battle_ID=" . $BattleID . " and Act_ID=" . $actid);
    $SN_Integral = $rsSN2["SN_Integral"];
    if ($SN_Integral > 0) {
        echo "<span style=\"font-size:18px;\">挑战成功！赢取：" . $SN_Integral . "积分！</span>";
    } else {
        echo "挑战失败";
    }
    $DB->Set("battle_act", array(
        "Act_Score" => 0
    ) , "where Act_ID=" . $actid);
    $DB->Set("battle_sn", array(
        "Act_ID" => 0
    ) , "where Users_ID='" . $UsersID . "' and Act_ID=" . $actid . " and Battle_ID=" . $BattleID);
} else {
    echo empty($SN_Integral) ? 0 : $SN_Integral;
}
?></div>
<div class="self_paihang" style="display:none;">当前排名：1027名</div>
</div>
<div id="result3">
  <div class="ranking">
    <div class="icon"><img src="/static/api/images/battle/phb.png" /></div>
  </div>
  <div class="rank_list_box">
  <?php
$DB->get("battle_sn", "User_Name,sum(SN_Integral) as SN_Integral", "where Users_ID='" . $UsersID . "' group by User_ID order by SN_Integral desc", 10);
$i = 1;
$round = '';
while ($rsRecord = $DB->fetch_assoc()) {
    switch ($i) {
        case 1:
            $round = 'gold';
            break;

        case 2:
            $round = 'silver';
            break;

        case 3:
            $round = 'copper';
            break;

        default:
            $round = 'round';
    }
    echo '<div class="rank_list">
    <div class="' . $round . '">' . $i . '</div>
    <div class="showName">恭喜<b>' . $rsRecord["User_Name"] . '</b>荣获第<strong>' . $i . '</strong>名，总分：' . $rsRecord["SN_Integral"] . '分。</div>
  </div>';
    $i++;
} ?>
</div>
  <div class="backBtn"><a href="/api/<?php
echo $UsersID
?>/user/"><img src="/static/api/images/battle/back.jpg" width="100%" /></a></div>
  <div class="clean"></div>
</div>
</body>
</html>