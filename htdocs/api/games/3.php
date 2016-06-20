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
			$KEYS = array("1000-4000","4000-10000","10000-30000","30000-40000","40000-0");
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
<style type="text/css">body,html{background:#fff;}</style>
<header>
    <h1><?php echo $rsGames["Games_Name"];?></h1>
    <p>score : <span id="score">0</span></p>
</header>

<div id="grid-container">
    <div class="grid-cell" id="grid-cell-0-0"></div>
    <div class="grid-cell" id="grid-cell-0-1"></div>
    <div class="grid-cell" id="grid-cell-0-2"></div>
    <div class="grid-cell" id="grid-cell-0-3"></div>

    <div class="grid-cell" id="grid-cell-1-0"></div>
    <div class="grid-cell" id="grid-cell-1-1"></div>
    <div class="grid-cell" id="grid-cell-1-2"></div>
    <div class="grid-cell" id="grid-cell-1-3"></div>

    <div class="grid-cell" id="grid-cell-2-0"></div>
    <div class="grid-cell" id="grid-cell-2-1"></div>
    <div class="grid-cell" id="grid-cell-2-2"></div>
    <div class="grid-cell" id="grid-cell-2-3"></div>

    <div class="grid-cell" id="grid-cell-3-0"></div>
    <div class="grid-cell" id="grid-cell-3-1"></div>
    <div class="grid-cell" id="grid-cell-3-2"></div>
    <div class="grid-cell" id="grid-cell-3-3"></div>
</div>
<input type="hidden" id="card_2" value="<?php echo !empty($JSON["img2"]) ? $JSON["img2"] : '/static/api/games/images/2.jpg';?>">
<input type="hidden" id="card_4" value="<?php echo !empty($JSON["img4"]) ? $JSON["img4"] : '/static/api/games/images/4.jpg';?>">
<input type="hidden" id="card_8" value="<?php echo !empty($JSON["img8"]) ? $JSON["img8"] : '/static/api/games/images/8.jpg';?>">
<input type="hidden" id="card_16" value="<?php echo !empty($JSON["img16"]) ? $JSON["img16"] : '/static/api/games/images/16.jpg';?>">
<input type="hidden" id="card_32" value="<?php echo !empty($JSON["img32"]) ? $JSON["img32"] : '/static/api/games/images/32.jpg';?>">
<input type="hidden" id="card_64" value="<?php echo !empty($JSON["img64"]) ? $JSON["img64"] : '/static/api/games/images/64.jpg';?>">
<input type="hidden" id="card_128" value="<?php echo !empty($JSON["img128"]) ? $JSON["img128"] : '/static/api/games/images/128.jpg';?>">
<input type="hidden" id="card_256" value="<?php echo !empty($JSON["img256"]) ? $JSON["img256"] : '/static/api/games/images/256.jpg';?>">
<input type="hidden" id="card_512" value="<?php echo !empty($JSON["img512"]) ? $JSON["img512"] : '/static/api/games/images/512.jpg';?>">
<input type="hidden" id="card_1024" value="<?php echo !empty($JSON["img1024"]) ? $JSON["img1024"] : '/static/api/games/images/1024.jpg';?>">
<input type="hidden" id="card_2048" value="<?php echo !empty($JSON["img2048"]) ? $JSON["img2048"] : '/static/api/games/images/2048.jpg';?>">
<input type="hidden" id="card_4096" value="<?php echo !empty($JSON["img4096"]) ? $JSON["img4096"] : '/static/api/games/images/4096.jpg';?>">
<input type="hidden" id="card_8192" value="<?php echo !empty($JSON["img8192"]) ? $JSON["img8192"] : '/static/api/games/images/8192.jpg';?>">

<link href='/static/api/games/css/<?php echo $rsGames["Model_ID"];?>.css' rel='stylesheet' type='text/css'/>
<script type='text/javascript' src='/static/api/games/js/<?php echo $rsGames["Model_ID"];?>.js'></script>
</body>
</html>
