<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');

if(isset($_GET["UsersID"])){
	$UsersID=$_GET["UsersID"];
}else{
	echo '缺少必要的参数';
	exit;
}
$rsConfig = $DB->GetRs("hongbao_config","*","where usersid='".$UsersID."'");
if($rsConfig["fromtime"]){
	if($rsConfig["fromtime"]>time()){
		$Data = array(
			"status"=>0,
			"msg"=>'活动未开始'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}

if($rsConfig["totime"]){
	if($rsConfig["totime"]<time()){
		$Data = array(
			"status"=>0,
			"msg"=>'活动已结束'
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}

$action = !empty($_REQUEST['action'])?$_REQUEST['action']:'do_zhuli';

if($action == 'help'){
	if(isset($_GET["actid"])){
		$actid=$_GET["actid"];
	}else{
		echo '缺少必要的参数';
		exit;
	}
	$actinfo = $DB->GetRs("hongbao_act","*","where usersid='".$UsersID."' and actid=$actid");
	if(!$actinfo){
		$Data = array(
			"status"=>0,
			"msg"=>'该红包不存在'	
		);
	}else{
		if($actinfo["status"]==1){
			$Data = array(
				"status"=>0,
				"msg"=>'该红包已拆启'	
			);
		}else{
			if($actinfo["userid"] == $_SESSION[$UsersID."User_ID"]){
				$Data = array(
					"status"=>0,
					"msg"=>'请邀请好友帮助'	
				);
			}else{
				$rsRecord = $DB->GetRs("hongbao_record","count(*) as num","where usersid='".$UsersID."' and userid=".$_SESSION[$UsersID."User_ID"]);
				if($rsRecord["num"]>0){
					$Data = array(
						"status"=>0,
						"msg"=>'你已经帮助过他咯'	
					);
				}else{
					$Data = array(
						"actid"=>$actid,
						"usersid"=>	$UsersID,
						"userid"=>$_SESSION[$UsersID."User_ID"],
						"addtime"=>time()
					);
					$flag = $DB->Add("hongbao_record",$Data);
					$Data = array(
						"expire"=>$actinfo["expire"]+1
					);
					$flag = $DB->Set("hongbao_act",$Data,"where usersid='".$UsersID."' and actid=$actid");
					if($flag){
						$Data = array(
							"status"=>1,
							"msg"=>'非常感谢你了帮他一把，你的朋友离成功又近了一步哦',
							"url"=>"/api/".$UsersID."_".$actid."/hongbao/detail/"	
						);
					}else{
						$Data = array(
							"status"=>0,
							"msg"=>'系统繁忙，请稍后再试'	
						);
					}					
				}
			}			
		}
	}	
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}elseif($action == 'qiang'){
	$start = 1;	
	if($rsConfig["pertime"]>0){
		$actinfo = $DB->GetRs("hongbao_act","*","where usersid='".$UsersID."' and userid=".$_SESSION[$UsersID."User_ID"]." order by addtime desc");
		if($actinfo){
			$time_diff = time()-intval($rsConfig["pertime"])*60;
			if($actinfo["addtime"]>$time_diff){
				$start = 0;
			}
		}
	}
	
	if($start==0){
		$Data = array(
			"status"=>0,
			"msg"=>'每'.$rsConfig["pertime"].'分钟抢一次红包，请稍候片刻！',
			"url"=>"/api/".$UsersID."/hongbao/"
		);
	}else{
		function get_rand($proArr) {
			$result = '';
			//概率数组的总概率精度
			$proSum = array_sum($proArr)*100;
			//概率数组循环
			foreach ($proArr as $key => $proCur) {
				$randNum = mt_rand(1, $proSum);
				if ($randNum <= $proCur*100) {
					$result = $key;
					break;
				} else {
					$proSum -= $proCur*100;
				}
			}
			unset ($proArr);
			return $result;
		}
		$arr = $prizes = array();
		$sum = $DB->GetRs("hongbao_prize","sum(amount) as num","where usersid='".$UsersID."'");
		$DB->Get("hongbao_prize","*","where usersid='".$UsersID."'");
		while($r=$DB->fetch_assoc()){
			if($r["expire"]>=$r["amount"]){
				$arr[$r["prizeid"]] = 0;
			}else{
				$arr[$r["prizeid"]] = sprintf("%.2f", $r["amount"]*10000/$sum["num"]);
			}
			$prizes[$r["prizeid"]] = $r;
		}
		$prizeid = get_rand($arr);
		$data = array(
			'usersid'=>$UsersID,
			'userid'=>$_SESSION[$UsersID."User_ID"],
			'prizeid'=>$prizeid,
			'money'=>$prizes[$prizeid]["money"],
			'friend'=>$prizes[$prizeid]["money"]>0 ? $prizes[$prizeid]["friend"] : 0,
			'expire'=>0,
			'status'=>$prizes[$prizeid]["money"]>0 ? 0 : 1,
			'addtime'=>time(),
		);
		$flag = $DB->Add("hongbao_act",$data);
		$actid = $DB->insert_id();
		if($flag){
			if($prizes[$prizeid]["money"]>0){
				$Data = array(
					"status"=>1,
					"msg"=>'恭喜抢到 '.$prizes[$prizeid]["money"].' 元红包,需要'.$prizes[$prizeid]["friend"].'位伙伴帮你拆开,快去拆开吧',
					"url"=>"/api/".$UsersID."_".$actid."/hongbao/detail/"
				);
			}else{
				$Data = array(
					"status"=>1,
					"msg"=>'运气差了点！',
					"url"=>"/api/".$UsersID."/hongbao/"
				);
			}
		}else{
			$Data = array(
				"status"=>0,
				"msg"=>'系统繁忙，请稍后再试'				
			);
		}
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}elseif($action=='chai_flag'){
	if(isset($_GET["actid"])){
		$actid=$_GET["actid"];
	}else{
		echo '缺少必要的参数';
		exit;
	}
	$actinfo = $DB->GetRs("hongbao_act","*","where usersid='".$UsersID."' and actid=$actid");
	if($actinfo["expire"]>=$actinfo["friend"]){
		$Data = array(
			"status"=>1			
		);
	}else{
		$Data = array(
			"status"=>0,
			"count"=>$actinfo["friend"]-$actinfo["expire"]		
		);
	}
	echo json_encode($Data,JSON_UNESCAPED_UNICODE);
}
?>