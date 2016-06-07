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

$_SESSION[$UsersID."HTTP_REFERER"] = "/api/turntable/index.php?UsersID=" . $UsersID;
$rsUsers = $DB->GetRs("users", "*", "where Users_ID='" . $UsersID . "'");
$is_login = 1;
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');
function turntable_check($LotteryTime = array(), $EveryDayLotteryTimes, $AllLotteryTimes, $PrizeCount) {
	if (time()>$LotteryTime[1] || time()<$LotteryTime[0]) {
        $Data = array(
            "status" => 0,
            "msg" => "活动已结束！",
            "url" => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
        );
		exit(json_encode($Data, JSON_UNESCAPED_UNICODE));
    }
	if($EveryDayLotteryTimes==0 || $EveryDayLotteryTimes<0){
		$Data = array(
            "status" => 0,
            "msg" => "您的抽奖次数已耗尽！",
            "url" => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
        );
		exit(json_encode($Data, JSON_UNESCAPED_UNICODE));
	}
	if($AllLotteryTimes==0 || $AllLotteryTimes<0){
		$Data = array(
            "status" => 0,
            "msg" => "本次活动的抽奖机会您已用完！",
            "url" => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
        );
		exit(json_encode($Data, JSON_UNESCAPED_UNICODE));
	}
    if ($PrizeCount == 0 || $PrizeCount<0) {
        $Data = array(
            "status" => 0,
            "msg" => "没有奖品了！活动结束！",
            "url" => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
        );
		exit(json_encode($Data, JSON_UNESCAPED_UNICODE));
    }
}
$rsTurntable = $DB->GetRs("turntable", "*", "where Users_ID='" . $UsersID . "' and Turntable_StartTime<=" . time() . " and Turntable_EndTime>=" . time() . " and Turntable_Status=2 order by Turntable_CreateTime desc");
//产品剩余
$PrizeCount = array(
    0,
    0,
    0,
    0,
);
$DB->get("turntable_sn", "Turntable_PrizeID,Count(Turntable_PrizeID) as Turntable_PrizeCount", "where Users_ID='" . $UsersID . "' and Turntable_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) . " and SN_Code<>0 and SN_Status>0 GROUP BY Turntable_PrizeID");
while ($rsPrizeCount = $DB->fetch_assoc()) {
    $PrizeCount[$rsPrizeCount['Turntable_PrizeID']] = $rsPrizeCount['Turntable_PrizeCount'];
}
$Prize1Num = ($rsTurntable["Turntable_FirstPrizeCount"] - $PrizeCount[1]) > 0 ? $rsTurntable["Turntable_FirstPrizeCount"] - $PrizeCount[1] : 0;
$Prize2Num = ($rsTurntable["Turntable_SecondPrizeCount"] - $PrizeCount[2]) > 0 ? $rsTurntable["Turntable_SecondPrizeCount"] - $PrizeCount[2] : 0;
$Prize3Num = ($rsTurntable["Turntable_ThirdPrizeCount"] - $PrizeCount[3]) > 0 ? $rsTurntable["Turntable_ThirdPrizeCount"] - $PrizeCount[3] : 0;
$p_last_num = $Prize1Num + $Prize2Num + $Prize3Num;
//+每天额外抽奖次数并把当前总次数入库
$fromtime = strtotime(date("Y-m-d") . " 00:00:00");
$totime = strtotime(date("Y-m-d") . " 23:59:59");
$Today = $DB->GetRs("action_num_record", "AllDayLotteryTimes_have", "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='turntable' and Users_ID='" . $UsersID . "' and S_CreateTime>=" . $fromtime . " and S_CreateTime<=" . $totime . " and Act_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0));
//上一次记录
$last_time = $DB->GetRs("action_num_record", "AllDayLotteryTimes_have", "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module ='turntable' and Users_ID='" . $UsersID . "' and S_CreateTime<" . $fromtime . " and Act_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) . " order by S_CreateTime desc");
if (!$last_time) {
    $last_time["AllDayLotteryTimes_have"] = 0;
}
if (!$Today) {
    $Data = array(
        "Users_ID" => $UsersID,
        "S_Module" => "turntable",
        "S_CreateTime" => time() ,
        "User_ID" => $_SESSION[$UsersID."User_ID"],
        "AllDayLotteryTimes_have" => $rsTurntable["Turntable_EveryDayLotteryTimes"] + $last_time["AllDayLotteryTimes_have"],
        "Act_ID" => (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) ,
    );
    $DB->Add("action_num_record", $Data);
}
$Today = $DB->GetRs("action_num_record", "AllDayLotteryTimes_have", "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module ='turntable' and Users_ID='" . $UsersID . "' and S_CreateTime>=" . $fromtime . " and S_CreateTime<=" . $totime . " and Act_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0));
$EveryDayLotteryTimes_have = $Today["AllDayLotteryTimes_have"];
if (isset($_POST["action"]) && $_POST["action"] != "move") { //不用判断
    if (isset($_POST['action'])) {
        //开始事务定义
        $Flag = true;
        $msg = "";
        mysql_query("begin");
        $action = $_POST['action'];
        switch ($action) {
            case 'mobile':
                $rsSN = $DB->GetRs("turntable_sn", "*", "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and Turntable_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) . " order by SN_CreateTime desc");
                if (empty($rsSN['SN_Code'])) {
                    $Data = array(
                        "status" => 0,
                        "msg" => '请勿非法操作'
                    );
                } else {
                    if ($rsSN['SN_Status'] >= 1) {
                        $Data = array(
                            "status" => 0,
                            "msg" => '请勿非法操作'
                        );
                    } else {
                        $Data = array(
                            "User_Mobile" => $_POST['MobilePhone'],
                            "SN_Status" => 1
                        );
                        $Flag = $DB->Set("turntable_sn", $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and SN_ID=" . $rsSN['SN_ID'] . " and Turntable_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0));
                        if ($Flag) {
                            $Data = array(
                                "status" => 1
                            );
                        } else {
                            $Data = array(
                                "status" => 0
                            );
                        }
                    }
                }
                break;

            case 'used':
                if ($_POST['bp'] == $rsTurntable['Turntable_BusinessPassWord']) {
                    $Data = array(
                        "SN_UsedTimes" => time() ,
                        "SN_Status" => 2
                    );
                    $Flag = $DB->Set("turntable_sn", $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and SN_ID=" . $_POST['SNID'] . " and Turntable_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0));
                    if ($Flag) {
                        $Data = array(
                            "status" => 1
                        );
                    } else {
                        $Data = array(
                            "status" => 0
                        );
                    }
                } else {
                    $Data = array(
                        "status" => 0,
                        "msg" => '商家密码不正确'
                    );
                }
                break;
        }
        if ($Flag) {
            mysql_query("commit");
        } else {
            mysql_query("roolback");
        }
        echo json_encode(empty($Data) ? array(
            'status' => 0,
            'msg' => '请勿非法操作！'
        ) : $Data, JSON_UNESCAPED_UNICODE);
        exit;
    }
} else {
    $error_message = "";
    if ($p_last_num == 0) {
        $error_message = "没有奖品了！";
    }
    $use_num = $DB->GetRs("turntable_sn", "Count(SN_ID) as count", "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and Turntable_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0));
    if (isset($rsTurntable["Turntable_LotteryTimes"]) && $use_num["count"] >= $rsTurntable["Turntable_LotteryTimes"]) {
        $error_message = "本次活动的抽奖机会您已用完";
    } else {
        if (isset($rsTurntable["Turntable_EveryDayLotteryTimes"]) && $EveryDayLotteryTimes_have <= 0) {
            $error_message = "您的抽奖机会已用完，请明天再来吧！";
        } else {
            if (isset($_POST['action']) && $_POST["action"] == "move") {
                //开始事务定义
                $Flag = true;
                $msg = "";
                mysql_query("begin");
                $action = $_POST['action'];
                switch ($action) {
                    case 'move':
                        if ($p_last_num == 0) {
                            $Data = array(
                                "status" => 0,
                                "msg" => "没有奖品了！",
                                "url" => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
                            );
                            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                        if (!$rsTurntable) {
                            $Data = array(
                                "status" => 0,
                                "msg" => "活动已结束！",
                                "url" => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
                            );
                            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                        function get_rand($proArr) {
                            $result = '';
                            //概率数组的总概率精度
                            $proSum = array_sum($proArr) * 100;
                            //概率数组循环
                            foreach ($proArr as $key => $proCur) {
                                $randNum = mt_rand(1, $proSum);
                                if ($randNum <= $proCur * 100) {
                                    $result = $key;
                                    break;
                                } else {
                                    $proSum-= $proCur * 100;
                                }
                            }
                            unset($proArr);
                            return $result;
                        }
                        $rsTurntable['Turntable_FirstPrizeProbability'] = $PrizeCount[1] >= $rsTurntable['Turntable_FirstPrizeCount'] ? 0 : $rsTurntable['Turntable_FirstPrizeProbability'];
                        $rsTurntable['Turntable_SecondPrizeProbability'] = $PrizeCount[2] >= $rsTurntable['Turntable_SecondPrizeCount'] ? 0 : $rsTurntable['Turntable_SecondPrizeProbability'];
                        $rsTurntable['Turntable_ThirdPrizeProbability'] = $PrizeCount[3] >= $rsTurntable['Turntable_ThirdPrizeCount'] ? 0 : $rsTurntable['Turntable_ThirdPrizeProbability'];
                        $prize_arr = array(
                            '0' => array(
                                'id' => 1,
                                'rand' => mt_rand(335, 385) ,
                                'level' => '一等奖',
                                'prize' => $rsTurntable['Turntable_FirstPrize'],
                                'v' => $rsTurntable['Turntable_FirstPrizeProbability']
                            ) ,
                            '1' => array(
                                'id' => 2,
                                'rand' => mt_rand(215, 265) ,
                                'level' => '二等奖',
                                'prize' => $rsTurntable['Turntable_SecondPrize'],
                                'v' => $rsTurntable['Turntable_SecondPrizeProbability']
                            ) ,
                            '2' => array(
                                'id' => 3,
                                'rand' => mt_rand(95, 145) ,
                                'level' => '三等奖',
                                'prize' => $rsTurntable['Turntable_ThirdPrize'],
                                'v' => $rsTurntable['Turntable_ThirdPrizeProbability']
                            ) ,
                            '3' => array(
                                'id' => 4,
                                'rand' => array(
                                    '0' => array(
                                        35,
                                        85
                                    ) ,
                                    '1' => array(
                                        155,
                                        205
                                    ) ,
                                    '2' => array(
                                        275,
                                        325
                                    )
                                ) ,
                                'prize' => '谢谢您参与，下次再努力！',
                                'v' => 100 - $rsTurntable['Turntable_FirstPrizeProbability'] - $rsTurntable['Turntable_SecondPrizeProbability'] - $rsTurntable['Turntable_ThirdPrizeProbability']
                            ) ,
                        );
                        foreach ($prize_arr as $key => $val) {
                            $arr[$val['id']] = $val['v'];
                        }
                        $rid = get_rand($arr); //根据概率获取奖项id
                        $SN_Code = mt_rand(1000, 9999) . mt_rand(1000, 9999);
                        //更新当前抽奖次数
                        $num_date = array(
                            "AllDayLotteryTimes_have" => (int)$EveryDayLotteryTimes_have - 1,
                        );
                        $DB->Set("action_num_record", $num_date, "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='turntable' and Users_ID='" . $UsersID . "' and Act_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) . " order by S_CreateTime desc");
                        //加入访问记录
                        $Data = array(
                            "Users_ID" => $UsersID,
                            "S_Module" => "turntable",
                            "S_CreateTime" => time()
                        );
                        $DB->Add("statistics", $Data);
                        $Data = array(
                            "Turntable_ID" => (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) ,
                            "Turntable_PrizeID" => $rid,
                            "Turntable_Prize" => $prize_arr[$rid - 1]['prize'],
                            "SN_Code" => $rid < 4 ? $SN_Code : 0,
                            "Users_ID" => $UsersID,
                            "User_ID" => $_SESSION[$UsersID."User_ID"],
                            "Open_ID" => $_SESSION[$UsersID."OpenID"],
                            "SN_CreateTime" => time()
                        );
                        $Flag = $Flag && $DB->Add("turntable_sn", $Data);
                        if ($rid < 4 && $Flag) {
                            $Data = array(
                                "status" => 1,
                                "rand" => $prize_arr[$rid - 1]['rand'],
                                "msg" => '恭喜您，抽中' . $prize_arr[$rid - 1]['level'] . '！',
                                "prize" => 1,
                                "sn" => $SN_Code,
                                "prizemsg" => $prize_arr[$rid - 1]['level']
                            );
                        } else {
                            $n = mt_rand(0, 2);
                            $r = $prize_arr[$rid - 1]['rand'][$n];
                            $rand = mt_rand($r[0], $r[1]);
                            $Data = array(
                                "status" => 1,
                                "rand" => $rand,
                                "msg" => '谢谢您参与，下次再努力！',
                                "prize" => '',
                                "sn" => '',
                                "prizemsg" => ''
                            );
                        }
                        break;
                }
                if ($Flag) {
                    mysql_query("commit");
                } else {
                    mysql_query("roolback");
                }
                echo json_encode(empty($Data) ? array(
                    'status' => 0,
                    'msg' => '请勿非法操作！'
                ) : $Data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
}
//用积分获取抽奖次数
if ($rsTurntable["Turntable_UsedIntegral"]) {
    if (isset($_POST["action_do"]) && $_POST["action_do"] == 'jifen') {
        $rsJifen = $DB->GetRs("user", "User_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]);
        if ($rsJifen["User_Integral"] < $rsTurntable["Turntable_UsedIntegralValue"]) {
            $jifenInfo = array(
                "status" => 0,
                "msg" => '积分不足！'
            );
        } else {
            $jifenInfo = array(
                "status" => 1,
                "msg" => '成功获取一次抽奖机会！'
            );
            $Integral_info = array(
                "User_Integral" => ($rsJifen["User_Integral"] - $rsTurntable["Turntable_UsedIntegralValue"])
            );
            $DB->Set("User", $Integral_info, "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]);
            $Integral_record = array(
                'Record_Integral' => - $rsTurntable["Turntable_UsedIntegralValue"],
                'Record_SurplusIntegral' => $rsJifen['User_Integral'] - $rsTurntable["Turntable_UsedIntegralValue"],
                'Operator_UserName' => '',
                'Record_Type' => 7,
                'Record_Description' => '消耗积分赚取抽奖机会_大转盘',
                'Record_CreateTime' => time() ,
                'Users_ID' => $UsersID,
                'User_ID' => $_SESSION[$UsersID."User_ID"],
                'Action_ID' => $rsTurntable["Turntable_ID"]
            );
            $DB->Add('user_Integral_record', $Integral_record);
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
			$weixin_message = new weixin_message($DB,$UsersID,$_SESSION[$UsersID."User_ID"]);
			$contentStr = "消耗".$rsTurntable["Turntable_UsedIntegralValue"]."积分赚大转盘取抽奖机会";
			$weixin_message->sendscorenotice($contentStr);
			
            //更新当前抽奖次数
            $num_date = array(
                "AllDayLotteryTimes_have" => (int)$EveryDayLotteryTimes_have + 1,
            );
            $DB->Set("action_num_record", $num_date, "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='turntable' and Users_ID='" . $UsersID . "' and Act_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) . " order by S_CreateTime desc");
        }
        echo json_encode($jifenInfo, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
//分享获取抽奖次数
if ($rsTurntable["Turntable_If_Share"]) {
    if (isset($_POST["action_do"]) && $_POST["action_do"] == 'share') {
        $Share_record = array(
            'Users_ID' => $UsersID,
            'User_ID' => $_SESSION[$UsersID."User_ID"],
            'Type' => 'turntable',
            'CreateTime' => time() ,
            'Share_Type' => 0, //分享到好友
            'Record_Description' => '分享好友获取抽奖机会',
            'Action_ID' => $rsTurntable["Turntable_ID"]
        );
        $DB->Add('share_record', $Share_record);
        $Share_get_num = array(
            0
        );
        $Share_get_num = $DB->GetRs("share_record", "Count(Record_ID) as count", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Type='turntable' and Action_ID=" . $rsTurntable["Turntable_ID"] . " and CreateTime>=" . $fromtime . " and CreateTime<=" . $totime);
        if (floor($Share_get_num["count"] / $rsTurntable["Turntable_Share_num"]) > 0) {
            $num_date = array(
                "AllDayLotteryTimes_have" => (int)$EveryDayLotteryTimes_have + floor($Share_get_num["count"] / $rsTurntable["Turntable_Share_num"]) ,
            );
            $DB->Set("action_num_record", $num_date, "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='turntable' and Users_ID='" . $UsersID . "' and Act_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) . " order by S_CreateTime desc");
            echo "<script>window.location.href=window.location.href;</script>";
        }
    }
}

//自定义初始化
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_jssdk.class.php');
$weixin_jssdk = new weixin_jssdk($DB,$UsersID);
$share_config = $weixin_jssdk->jssdk_get_signature();

//自定义分享
if(!empty($share_config)){
	$share_config["title"] = $rsTurntable['Turntable_Title'];
	$share_config["desc"] = $rsTurntable['Turntable_Title'];
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/turntable.jpg';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="telephone=no" name="format-detection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php
echo $rsTurntable ? $rsTurntable['Turntable_Title'] : '当前没有进行中的活动！' ?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/turntable.css?t=<?php
echo time(); ?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php
echo time(); ?>'></script>
<script type='text/javascript' src='/static/js/plugin/jquery/easing.min.js'></script>
<script type='text/javascript' src='/static/js/plugin/rotate/rotate.2.2.js'></script>
<script type='text/javascript' src='/static/api/js/turntable.js?t=<?php
echo time(); ?>'></script>
<script language="javascript">
var start=false;
$(document).ready(function(){
	turntable_obj.turntable_init();
	turntable_obj.use_sn_init();
});
</script>
</head>

<body>
<?php
ad($UsersID, 1, 4);
?>
<div id="turntable">
  <div id="WheelEvent">
    <div class="demo">
      <div id="disk">
      <?php
if (!$error_message) { ?>
<div id="start">
 <div id="startbtn">
  <img id="pan" src="/static/api/images/turntable/wheel_arrow_pan.png" />
  <img id="zhen" src="/static/api/images/turntable/wheel_arrow_zhen.png" />
 </div>
 
</div>
</div>
<?php
} ?>
    </div>
    <p align="center" class="fs">今日抽奖次数：<i><?php
echo ($EveryDayLotteryTimes_have > 0) ? $EveryDayLotteryTimes_have : 0; ?></i>次&nbsp;&nbsp;&nbsp;剩余奖品数量：<span><?php
echo $p_last_num; ?></span>个</p>
<?php
if ($rsTurntable["Turntable_UsedIntegral"]) { ?>
	<div id="jifen">使用积分抽奖</div>
    <p align="center"><?php
    echo $rsTurntable["Turntable_UsedIntegralValue"]; ?>积分获得一次刮奖机会。<a href="javascript:;" id="more_jf">查看如何获取更多积分>></a></p>
<?php
} ?>
<?php
if ($rsTurntable["Turntable_If_Share"]) { ?>
    <div id="share_btn">分享给好友</div>
    <p align="center">每分享<?php
    echo $rsTurntable["Turntable_Share_num"]; ?>个好友可获得一次机会</p>
<?php
} ?>
    <?php
if ($rsTurntable) { ?>
    <script language="javascript">
		var start=true;
    </script>
    <div id="WinPrize" class="refer none"> <span> 恭喜您在本活动中抽中<font id="PrizeClass"></font>！<br>
      SN码：<font id="SnNumber"></font><br>
      请输入您的手机号，作为领奖凭证！ </span>
      <div>
        <form id="GetPrize">
          <div class="input">
            <input type="input" name="MobilePhone" value="" class="form_input" pattern="[0-9]*" maxlength="11" placeholder="请输入您的手机号" />
          </div>
          <div class="input">
            <input type="submit" value="提交" class="submit" />
          </div>
        </form>
      </div>
    </div>
    <div id="turntable_success">提交成功！</div>
    <div class="refer"> <span class="s2">活动规则</span>
      <div><?php
    echo $error_message ? $error_message : $rsTurntable['Turntable_Description']; ?></div>
    </div>
    <div class="refer"> <span class="s1">奖品设置（<font color="#FF0000">亲，中奖后请务必输入您的手机号并提交，否则无法领奖喔！</font>）</span>
      <div> 一等奖：<?php
    echo $rsTurntable['Turntable_FirstPrize'];
    if ($rsTurntable['Turntable_IsShowPrizes']) { ?>。奖品数量：<?php
        echo $Prize1Num;
    } ?><br />
        二等奖：<?php
    echo $rsTurntable['Turntable_SecondPrize'];
    if ($rsTurntable['Turntable_IsShowPrizes']) { ?>。奖品数量：<?php
        echo $Prize2Num;
    } ?><br />
        三等奖：<?php
    echo $rsTurntable['Turntable_ThirdPrize'];
    if ($rsTurntable['Turntable_IsShowPrizes']) { ?>。奖品数量：<?php
        echo $Prize3Num;
    } ?><br />
      </div>
    </div>
    <div class="refer times"> <span class="s3">活动时间</span>
      <div>
      <?php
    echo date('Y-m-d H:i:s', $rsTurntable["Turntable_StartTime"]) . '-' . date('Y-m-d H:i:s', $rsTurntable["Turntable_EndTime"]); ?>
      </div>
    </div>
    <div id="UsedSn" class="refer none"> <span>兑奖申请(SN码：<font id="UsedSnNumber"></font>)</span>
      <div class="use">
        <form id="UsedPrize">
          <div class="input">
            <input type="password" name="bp" value="" class="form_input" placeholder="请输入商家兑奖密码" maxlength="16" />
          </div>
          <div class="input">
            <input type="submit" value="提交" class="submit" />
            <input type="button" value="关闭" class="close" />
          </div>
          <div class="clear"></div>
          <input type="hidden" name="SNID" value="" />
        </form>
      </div>
    </div>
    <div id="PrizeTips" class="refer"> <span class="s2">我的中奖记录</span>
      <div class="prize_list">
        <ul>
          <?php
    $level = array(
        '谢谢参与',
        '一等奖',
        '二等奖',
        '三等奖'
    );
    $DB->get("turntable_sn", "*", "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and Turntable_ID=" . (isset($rsTurntable['Turntable_ID']) ? $rsTurntable['Turntable_ID'] : 0) . " and SN_Code<>0 and SN_Status>0 order by SN_CreateTime desc");
    $i = 1;
    while ($rsSN = $DB->fetch_assoc()) {
        echo '<li' . ($i == 1 ? ' class="bn"' : '') . ' style="color:#FFF">
            <label>' . $i . '、</label>
            <span sn="' . $rsSN['SN_Code'] . '" snid="' . $rsSN['SN_ID'] . '"> 奖项：' . $level[$rsSN['Turntable_PrizeID']] . '（' . $rsSN['Turntable_Prize'] . '）<br />
            中奖SN码：<font class="sn">' . $rsSN['SN_Code'] . '</font>' . ($rsSN['SN_Status'] == 2 ? '' : ' <a href="#usesn">申请兑奖</a>') . '<br />
            中奖时间：' . date("Y-m-d H:i:s", $rsSN["SN_CreateTime"]) . '<br />
            ' . ($rsSN['SN_Status'] == 2 ? '兑奖时间：<font class="time">' . date("Y-m-d H:i:s", $rsSN["SN_UsedTimes"]) . '</font>' : '') . '</span>
            <div class="clear"></div>
          </li>';
        $i++;
    } ?>
        </ul>
      </div>
    </div>
	<div class="b10"></div>
    <?php
} else {
    echo '<div class="refer"><div>当前没有进行中的活动！</div></div>';
} ?>
  </div>
</div>
<div class='share_layer'><img src='/static/api/images/turntable/share.png' /></div>
<div class="off_layer" style="left:0; top:0"></div>
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
<span id="more_jf_txt" style="display:none;"><?php
echo $rsTurntable['Turntable_More_Integral']; ?></span>
<style>
#turntable{position:static;}
</style>
<?php
ad($UsersID, 2, 2); //第一个数字参数代表广告位置：1顶部2底部；第二个数字参数代表广告位的编号，从后台查看

?>
</body>
</html>
