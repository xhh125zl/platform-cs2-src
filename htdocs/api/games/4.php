<?php
if(isset($_POST["action"]) && $_POST["action"]=="over"){
	if($rsGames["Games_Pattern"]=="0"){//推广模式
		$Data = array(
			"Users_ID"=>$UsersID,
			"User_ID"=>empty($_SESSION[$UsersID."User_ID"]) ? 0 : $_SESSION[$UsersID."User_ID"],
			"Games_ID"=>$GamesID,
			"Games_Pattern"=>0,
			"Open_ID"=>empty($_SESSION[$UsersID."OpenID"]) ? "" : $_SESSION[$UsersID."OpenID"],
			"Score"=>$_POST["Result"],
			"CreateTime"=>time()
		);
		$flag = $DB->Add("games_result",$Data);
		$itemid = $DB->insert_id();
		if($flag){
			$Data=array(
				"status"=>1,
				"url"=>"/api/".$UsersID."/games/result/".$itemid."/"
			);
		}else{
			$Data=array(
				"status"=>0
			);
		}
	}else{//积分模式
		if(isset($_SESSION[$UsersID."User_ID"]) && $_SESSION[$UsersID."User_ID"]>0){
			$integrel = 0;
			$KEYS = array("10-60","60-120","120-240","240-300","300-0");
			$SCORE = $rsGames['Games_ScoreRules'] ? json_decode($rsGames['Games_ScoreRules'],true) : array();
			$rsUser=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]);
			foreach($KEYS as $k=>$a){
				$arr = explode("-",$a);
				if($arr[1]==0){
					if($_POST["Result"]>$arr[0]){
						$integrel = empty($SCORE[$k]) ? 0 : $SCORE[$k];
					}
				}else{
					if($_POST["Result"]>$arr[0] && $_POST["Result"]<=$arr[1]){
						$integrel = empty($SCORE[$k]) ? 0 : $SCORE[$k];
					}
				}
			}
			if($integrel>0){
				$Data = array(
					"User_Integral"=>($rsUser["User_Integral"]+$integrel)
				);
				$DB->Set("User",$Data,"where User_ID=".$_SESSION[$UsersID."User_ID"]);
				$Data=array(
					'Record_Integral'=>$integrel,
					'Record_SurplusIntegral'=>$rsUser['User_Integral']+$integrel,
					'Operator_UserName'=>'',
					'Record_Type'=>2,
					'Record_Description'=>$rsGames["Games_Name"].'获取积分',
					'Record_CreateTime'=>time(),
					'Users_ID'=>$UsersID,
					'User_ID'=>$_SESSION[$UsersID."User_ID"]
				);
				$DB->Add('user_Integral_record',$Data);
			}
			$Data = array(
				"Users_ID"=>$UsersID,
				"User_ID"=>empty($_SESSION[$UsersID."User_ID"]) ? 0 : $_SESSION[$UsersID."User_ID"],
				"Games_ID"=>$GamesID,
				"Games_Pattern"=>0,
				"Open_ID"=>empty($_SESSION[$UsersID."OpenID"]) ? "" : $_SESSION[$UsersID."OpenID"],
				"Score"=>$_POST["Result"],
				"CreateTime"=>time()
			);
			$flag = $DB->Add("games_result",$Data);
			$itemid = $DB->insert_id();
			if($flag){
				$Data=array(
					"status"=>1,
					"url"=>"/api/".$UsersID."/games/result/".$itemid."/"
				);
			}else{
				$Data=array(
					"status"=>0
				);
			}
		}else{
			$_SESSION[$UsersID."HTTP_REFERER"]="/api/".$UsersID."/games/game/".$GamesID."/?wxref=mp.weixin.qq.com";
			header("location:/api/".$UsersID."/user/login/?wxref=mp.weixin.qq.com");
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
	exit;
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
<title><?php echo $rsGames['Games_Name'];?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js'></script>
<script type='text/javascript' src='/static/api/games/js/games.js'></script>
<link href='/static/api/games/css/games.css' rel='stylesheet' type='text/css' />
</head>

<body>
<script language="javascript">
	var GameConfig=<?php echo $rsGames["Games_Json"];?>;
</script>

<style type="text/css">
	body,html{background:#fff;}
</style>

<div id="warp">
    <div id="ctrl" class="clean">
        <div id="step"><img src="/static/api/games/images/pic.jpg" ></div>
        <div class="section1">
        	<div id="time">时间：00:00</div>
            <div id="return">重来</div>
        </div>
    </div>
    <ul></ul>
</div>
<input type="hidden" id="img_path_0" value="<?php echo !empty($JSON["img1"]) ? $JSON["img1"] : '/static/api/games/images/01.jpg';?>" />
<input type="hidden" id="img_path_1" value="<?php echo !empty($JSON["img2"]) ? $JSON["img2"] : '/static/api/games/images/02.jpg';?>" />
<input type="hidden" id="img_path_2" value="<?php echo !empty($JSON["img3"]) ? $JSON["img3"] : '/static/api/games/images/03.jpg';?>" />
<input type="hidden" id="img_path_3" value="<?php echo !empty($JSON["img4"]) ? $JSON["img4"] : '/static/api/games/images/04.jpg';?>" />
<input type="hidden" id="img_path_4" value="<?php echo !empty($JSON["img5"]) ? $JSON["img5"] : '/static/api/games/images/05.jpg';?>" />

<link href='/static/api/games/css/<?php echo $rsGames["Model_ID"];?>.css' rel='stylesheet' type='text/css'/>
<script type='text/javascript' src='/static/api/games/js/<?php echo $rsGames["Model_ID"];?>.js'></script>
</body>
</html>

