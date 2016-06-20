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

$_SESSION[$UsersID."HTTP_REFERER"] = "/api/scratch/index.php?UsersID=" . $UsersID;
$rsUsers = $DB->GetRs("users", "*", "where Users_ID='" . $UsersID . "'");
$is_login = 1;

require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/wechatuser.php');

$rsScratch = $DB->GetRs("scratch", "*", "where Users_ID='" . $UsersID . "' and Scratch_StartTime<=" . time() . " and Scratch_EndTime>=" . time() . " and Scratch_Status=2 order by Scratch_CreateTime desc");

if(!$rsScratch){
	echo '<meta charset="utf-8"/>';
	echo '没有进行中的活动';
	exit();
}

//产品剩余
$PrizeCount = array(
                            0,
                            0,
                            0,
                            0,
                        );
$DB->get("scratch_sn", "Scratch_PrizeID,Count(Scratch_PrizeID) as Scratch_PrizeCount", "where Users_ID='" . $UsersID . "' and Scratch_ID=" . (isset($rsScratch['Scratch_ID']) ? $rsScratch['Scratch_ID'] : 0) . " and SN_Code<>0 and SN_Status>0 GROUP BY Scratch_PrizeID");
while ($rsPrizeCount = $DB->fetch_assoc()) {
       $PrizeCount[$rsPrizeCount['Scratch_PrizeID']] = $rsPrizeCount['Scratch_PrizeCount'];
}
$prize1num=($rsScratch["Scratch_FirstPrizeCount"]-$PrizeCount[1])>0?$rsScratch["Scratch_FirstPrizeCount"]-$PrizeCount[1]:0;
$prize2num=($rsScratch["Scratch_SecondPrizeCount"]-$PrizeCount[2])>0?$rsScratch["Scratch_SecondPrizeCount"]-$PrizeCount[2]:0;
$prize3num=($rsScratch["Scratch_ThirdPrizeCount"]-$PrizeCount[3])>0?$rsScratch["Scratch_ThirdPrizeCount"]-$PrizeCount[3]:0;
$p_last_num = $prize1num + $prize2num + $prize3num;
//+每天额外抽奖次数并把当前总次数入库
$fromtime = strtotime(date("Y-m-d") . " 00:00:00");
$totime = strtotime(date("Y-m-d") . " 23:59:59");
$Today = $DB->GetRs("action_num_record", "AllDayLotteryTimes_have", "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='scratch' and Users_ID='" . $UsersID . "' and Act_ID=".$rsScratch["Scratch_ID"]." and S_CreateTime>=" . $fromtime . " and S_CreateTime<=" . $totime);
//最近一次记录
$last_time = $DB->GetRs("action_num_record", "AllDayLotteryTimes_have", "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module ='scratch' and Users_ID='" . $UsersID . "' and Act_ID=".$rsScratch["Scratch_ID"]." and S_CreateTime<" . $fromtime . " order by S_CreateTime desc");
if (!$last_time) {
    $last_time["AllDayLotteryTimes_have"] = 0;
}
if (!$Today) {
    $Data = array(
        "Users_ID" => $UsersID,
        "S_Module" => "scratch",
        "S_CreateTime" => time() ,
        "User_ID" => $_SESSION[$UsersID."User_ID"],
        "AllDayLotteryTimes_have" => $rsScratch["Scratch_EveryDayLotteryTimes"] + $last_time["AllDayLotteryTimes_have"],
		"Act_ID" => $rsScratch["Scratch_ID"],
    );
    $DB->Add("action_num_record", $Data);

}
$Today = $DB->GetRs("action_num_record", "AllDayLotteryTimes_have", "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='scratch' and Users_ID='" . $UsersID . "' and Act_ID=".$rsScratch["Scratch_ID"]." and S_CreateTime>=" . $fromtime . " and S_CreateTime<=" . $totime);
$EveryDayLotteryTimes_have = $Today["AllDayLotteryTimes_have"];
$error_message = "";
if (isset($_POST["action"]) && $_POST["action"] != "move") { //不用判断
    if (isset($_POST['action'])) {
        //开始事务定义
        $Flag = true;
        $msg = "";
        mysql_query("begin");
        $action = $_POST['action'];
        switch ($action) {
            case 'mobile':
                $rsSN = $DB->GetRs("scratch_sn", "*", "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and Scratch_ID=".(isset($rsScratch['Scratch_ID']) ? $rsScratch['Scratch_ID'] : 0)." order by SN_CreateTime desc");
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
                        $Flag = $DB->Set("scratch_sn", $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and SN_ID=" . $rsSN['SN_ID']." and Scratch_ID=".(isset($rsScratch['Scratch_ID']) ? $rsScratch['Scratch_ID'] : 0));
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
                if ($_POST['bp'] == $rsScratch['Scratch_BusinessPassWord']) {
                    $Data = array(
                        "SN_UsedTimes" => time() ,
                        "SN_Status" => 2
                    );
                    $Flag = $DB->Set("scratch_sn", $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and SN_ID=" . $_POST['SNID']." and Scratch_ID=".(isset($rsScratch['Scratch_ID']) ? $rsScratch['Scratch_ID'] : 0));
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
    if ($p_last_num == 0) {
        $error_message = "没有奖品了！";
    }
    $use_num = $DB->GetRs("Scratch_sn", "Count(SN_ID) as count", "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and Scratch_ID=" . (isset($rsScratch['Scratch_ID']) ? $rsScratch['Scratch_ID'] : 0));
    if (isset($rsScratch["Scratch_LotteryTimes"]) && $use_num["count"] >= $rsScratch["Scratch_LotteryTimes"]) {
        $error_message = "本次活动的抽奖机会您已用完";
    } else {
        if (isset($rsScratch["Scratch_EveryDayLotteryTimes"]) && $EveryDayLotteryTimes_have <= 0) {
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
                                "url" => $_SERVER['PHP_SELF']
                            );
                            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
                            exit;
                        }
                        if (!$rsScratch) {
                            $Data = array(
                                "status" => 0,
                                "msg" => "活动已结束！",
                                "url" => $_SERVER['PHP_SELF']
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
                        $rsScratch['Scratch_FirstPrizeProbability'] = $PrizeCount[1] >= $rsScratch['Scratch_FirstPrizeCount'] ? 0 : $rsScratch['Scratch_FirstPrizeProbability'];
                        $rsScratch['Scratch_SecondPrizeProbability'] = $PrizeCount[2] >= $rsScratch['Scratch_SecondPrizeCount'] ? 0 : $rsScratch['Scratch_SecondPrizeProbability'];
                        $rsScratch['Scratch_ThirdPrizeProbability'] = $PrizeCount[3] >= $rsScratch['Scratch_ThirdPrizeCount'] ? 0 : $rsScratch['Scratch_ThirdPrizeProbability'];
                        $prize_arr = array(
                            '0' => array(
                                'id' => 1,
                                'level' => '一等奖',
                                'prize' => $rsScratch['Scratch_FirstPrize'],
                                'v' => $rsScratch['Scratch_FirstPrizeProbability']
                            ) ,
                            '1' => array(
                                'id' => 2,
                                'level' => '二等奖',
                                'prize' => $rsScratch['Scratch_SecondPrize'],
                                'v' => $rsScratch['Scratch_SecondPrizeProbability']
                            ) ,
                            '2' => array(
                                'id' => 3,
                                'level' => '三等奖',
                                'prize' => $rsScratch['Scratch_ThirdPrize'],
                                'v' => $rsScratch['Scratch_ThirdPrizeProbability']
                            ) ,
                            '3' => array(
                                'id' => 4,
                                'prize' => '谢谢参与',
                                'v' => 100 - $rsScratch['Scratch_FirstPrizeProbability'] - $rsScratch['Scratch_SecondPrizeProbability'] - $rsScratch['Scratch_ThirdPrizeProbability']
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
                        $DB->Set("action_num_record", $num_date, "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='scratch' and Users_ID='" . $UsersID . "' and Act_ID=".$rsScratch["Scratch_ID"]." order by S_CreateTime desc");
                        //加入访问记录
                        $Data = array(
                            "Users_ID" => $UsersID,
                            "S_Module" => "scratch",
                            "S_CreateTime" => time()
                        );
                        $DB->Add("statistics", $Data);
                        $Data = array(
                            "Scratch_ID" => $rsScratch['Scratch_ID'],
                            "Scratch_PrizeID" => $rid,
                            "Scratch_Prize" => $prize_arr[$rid - 1]['prize'],
                            "SN_Code" => $rid < 4 ? $SN_Code : 0,
                            "Users_ID" => $UsersID,
                            "Open_ID" => $_SESSION[$UsersID."OpenID"],
                            "SN_CreateTime" => time() ,
                            "User_ID" => $_SESSION[$UsersID."User_ID"],
                        );
                        $Flag = $Flag && $DB->Add("scratch_sn", $Data);
                        if ($rid < 4 && $Flag) {
                            $Data = array(
                                "status" => 1,
                                "sn" => $SN_Code,
                                "msg" => $prize_arr[$rid - 1]['level']
                            );
                        } else {
                            $Data = array(
                                "status" => 0,
                                "msg" => "谢谢参与"
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
if ($rsScratch["Scratch_UsedIntegral"]) {
    if (isset($_POST["action_do"]) && $_POST["action_do"] == 'jifen') {
        $rsJifen = $DB->GetRs("user", "User_Integral", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]);
        if ($rsJifen["User_Integral"] < $rsScratch["Scratch_UsedIntegralValue"]) {
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
                "User_Integral" => ($rsJifen["User_Integral"] - $rsScratch["Scratch_UsedIntegralValue"])
            );
            $DB->Set("User", $Integral_info, "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"]);
            $Integral_record = array(
                'Record_Integral' => - $rsScratch["Scratch_UsedIntegralValue"],
                'Record_SurplusIntegral' => $rsJifen['User_Integral'] - $rsScratch["Scratch_UsedIntegralValue"],
                'Operator_UserName' => '',
                'Record_Type' => 8,
                'Record_Description' => '消耗积分赚取抽奖机会_刮刮乐',
                'Record_CreateTime' => time() ,
                'Users_ID' => $UsersID,
                'User_ID' => $_SESSION[$UsersID."User_ID"],
                'Action_ID' => $rsScratch["Scratch_ID"]
            );
            $DB->Add('user_Integral_record', $Integral_record);
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
			$weixin_message = new weixin_message($DB,$UsersID,$_SESSION[$UsersID."User_ID"]);
			$contentStr = "消耗".$rsScratch["Scratch_UsedIntegralValue"]."积分赚刮刮乐取抽奖机会";
			$weixin_message->sendscorenotice($contentStr);
			
            //更新当前抽奖次数
            $num_date = array(
                "AllDayLotteryTimes_have" => (int)$EveryDayLotteryTimes_have + 1,
            );
            $DB->Set("action_num_record", $num_date, "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='scratch' and Users_ID='" . $UsersID . "' and Act_ID=".$rsScratch["Scratch_ID"]." order by S_CreateTime desc");
        }
        echo json_encode($jifenInfo, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
//分享获取抽奖次数
if ($rsScratch["Scratch_If_Share"]) {
    if (isset($_POST["action_do"]) && $_POST["action_do"] == 'share') {
        $Share_record = array(
            'Users_ID' => $UsersID,
            'User_ID' => $_SESSION[$UsersID."User_ID"],
            'Type' => 'scratch',
            'CreateTime' => time() ,
            'Share_Type' => 0, //分享到好友
            'Record_Description' => '分享好友获取刮奖机会',
            'Action_ID' => $rsScratch["Scratch_ID"]
        );
        $DB->Add('share_record', $Share_record);
        $Share_get_num = array(
            0
        );
        $Share_get_num = $DB->GetRs("share_record", "Count(Record_ID) as count", "where Users_ID='" . $UsersID . "' and User_ID=" . $_SESSION[$UsersID."User_ID"] . " and Type='scratch' and Action_ID=" . $rsScratch["Scratch_ID"] . " and CreateTime>=" . $fromtime . " and CreateTime<=" . $totime);
        if (floor($Share_get_num["count"] / $rsScratch["Scratch_Share_num"]) > 0) {
            $num_date = array(
                "AllDayLotteryTimes_have" => (int)$EveryDayLotteryTimes_have + floor($Share_get_num["count"] / $rsScratch["Scratch_Share_num"]) ,
            );
            $DB->Set("action_num_record", $num_date, "where User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and S_Module='scratch' and Users_ID='" . $UsersID . "' and Act_ID=".$rsScratch["Scratch_ID"]." order by S_CreateTime desc");
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
	$share_config["title"] = $rsScratch['Scratch_Title'];
	$share_config["desc"] = $rsScratch['Scratch_Title'];
	$share_config["img"] = 'http://'.$_SERVER["HTTP_HOST"].'/static/api/images/cover_img/scratch.jpg';
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
echo $rsScratch ? $rsScratch['Scratch_Title'] : '当前没有进行中的活动！' ?></title>
<link href='/static/api/css/scratch.css?t=<?php
time(); ?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php
echo time(); ?>'></script>
<script type='text/javascript' src='/static/js/plugin/websanova/wScratchPad.js'></script>
<script type='text/javascript' src='/static/api/js/scratch.js?t=<?php
echo time(); ?>'></script>
<script type='text/javascript'>
$(window).load(function(){
	scratch_obj.scratch_init();
	scratch_obj.use_sn_init();
});
var start=false;
</script>
</head>

<body>
<?php
ad($UsersID, 1, 2);
?>
<div id="scratch">
  <div id="WheelEvent">
    <div class="cover"> <img src="/static/api/images/scratch/scratch-bg.png" />
    <i class="i1"><?php
echo $EveryDayLotteryTimes_have; ?></i><b class="i2"><?php
echo $p_last_num; ?></b>
      <div id="prize"></div>
      <?php
if (!$error_message) { ?><div id="scratchpad"></div><?php
} ?>
      <div id="get_prize" class="none">
        <input type="button" value="我要领奖" />
      </div>
    </div>
<?php
if ($rsScratch["Scratch_UsedIntegral"]) { ?>
   <div class="b5"></div>
   <div id="jifen">使用积分抽奖</div>
   <div class="introduce"><?php
    echo $rsScratch["Scratch_UsedIntegralValue"]; ?>积分可获取一次刮奖机会。<a href="javascript:;" id="more_jf">查看如何获取更多积分>></a></div>
<?php
} ?>
<?php
if ($rsScratch["Scratch_If_Share"]) { ?>
   <div id="share">分享给好友</div>
   <div class="introduce">每分享<?php
    echo $rsScratch["Scratch_Share_num"]; ?>个好友可获得一次机会。</div>
<?php
} ?>
<?php
if ($rsScratch) { ?>
<script type='text/javascript'>
	var start=true;
	var isMove=false;
</script>
    <div id="WinPrize" class="refer none"> <span> 恭喜您在本活动中抽中<font id="PrizeClass"></font>！<br>
      SN码：<font id="SnNumber"></font><br>
      请输入您的手机号，作为领奖凭证！ </span>
      <div>
        <form id="GetPrize">
          <div class="input">
            <input type="tel" name="MobilePhone" value="" class="form_input" pattern="[0-9]*" maxlength="11" placeholder="请输入您的手机号" />
          </div>
          <div class="input">
            <input type="submit" value="提交" class="submit" />
          </div>
        </form>
      </div>
    </div>
    <div id="scratch_success">提交成功！</div>
    <div class="refer"> <span class="s2">活动说明</span>
      <div><?php
    echo $error_message ? $error_message : $rsScratch['Scratch_Description']; ?></div>
    </div>
    <div class="refer"> <span class="s1">兑奖说明（<font>亲，中奖后请务必输入您的手机号并提交，否则无法领奖喔！</font>）</span>
      <div> 一等奖：<?php
    echo $rsScratch['Scratch_FirstPrize'];
    if ($rsScratch['Scratch_IsShowPrizes']) { ?>。奖品数量：<?php
        echo $prize1num;
    } ?><br />
        二等奖：<?php
    echo $rsScratch['Scratch_SecondPrize'];
    if ($rsScratch['Scratch_IsShowPrizes']) { ?>。奖品数量：<?php
        echo $prize2num;
    } ?><br />
        三等奖：<?php
    echo $rsScratch['Scratch_ThirdPrize'];
    if ($rsScratch['Scratch_IsShowPrizes']) { ?>。奖品数量：<?php
        echo $prize3num;
    } ?><br />
      </div>
    </div>
    <div class="refer" style="height:80px;"> <span class="s3">活动时间</span>
      <div>
      <?php
    echo date('Y.m.d h:m', $rsScratch["Scratch_StartTime"]) . '-' . date('Y.m.d h:m', $rsScratch["Scratch_EndTime"]); ?>
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
    <div id="PrizeTips" class="refer"> <span  class="s2">我的中奖记录</span>
      <div class="prize_list">
        <ul>
        <?php
    $level = array(
        '谢谢参与',
        '一等奖',
        '二等奖',
        '三等奖'
    );
    $DB->get("scratch_sn", "*", "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION[$UsersID."User_ID"] . "' and Scratch_ID=" . (isset($rsScratch['Scratch_ID']) ? $rsScratch['Scratch_ID'] : 0) . " and SN_Code<>0 and SN_Status>0 order by SN_CreateTime desc");
    $i = 1;
    while ($rsSN = $DB->fetch_assoc()) {
        echo '<li' . ($i == 1 ? ' class="bn"' : '') . '>
            <label>' . $i . '、</label>
            <span sn="' . $rsSN['SN_Code'] . '" snid="' . $rsSN['SN_ID'] . '"> 奖项：' . $level[$rsSN['Scratch_PrizeID']] . '（' . $rsSN['Scratch_Prize'] . '）<br />
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
    
<?php
} else {
    echo '<div class="refer"><div>当前没有进行中的活动！</div></div>';
} ?>
  </div>
</div>
<div class='share_layer'><img src='/static/api/images/turntable/share.png' /></div>
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
echo $rsScratch['Scratch_More_Integral']; ?></span>
<?php
ad($UsersID, 2, 2); //第一个数字参数代表广告位置：1顶部2底部；第二个数字参数代表广告位的编号，从后台查看
 ?>
</body>
</html>