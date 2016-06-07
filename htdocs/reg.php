<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$DB->showErr=false;
if(isset($_GET["action"])){
	if($_GET["action"] == "trade" && isset($_GET["id"])){
		$html = array();
		$DB->get("industry","*","where parentid=".$_GET["id"]." order by id asc");
		while($r=$DB->fetch_assoc()){
			$html[] = $r;
		}
		$Data = array(
			"status"=> count($html)>0 ? 1 : 0,
			"html"=>count($html)>0 ? $html : ""
		);
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($_GET["action"] == "smscode" && isset($_GET["mobile"])){
		if($setting["sms_enabled"]==0){
			$Data = array(
				"status"=> 0,
				"msg"=>"短信发送功能已关闭"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		$mobile = trim($_GET["mobile"]);
		if(!$mobile){
			$Data = array(
				"status"=> 0,
				"msg"=>"请输入手机号码"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(!isset($_SESSION['mobile_send']) || empty($_SESSION['mobile_send'])){
			$_SESSION['mobile_send'] = 0;
		}
		
		if(isset($_SESSION['mobile_time']) && !empty($_SESSION['mobile_time'])){
			if(time() - $_SESSION['mobile_time'] < 300){
				$Data = array(
					"status"=> 0,
					"msg"=>"发送短信过快"
				);
				echo json_encode($Data,JSON_UNESCAPED_UNICODE);
				exit;
			}
		}
		
		if($_SESSION['mobile_send'] > 4){
			$Data = array(
				"status"=> 0,
				"msg"=>"发送短信次数频繁"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}		
		
		function randcode1($length=6){
			$chars = '0123456789';
			$temchars = '';
			for($i=0;$i<$length;$i++)
			{
				$temchars .= $chars[ mt_rand(0, strlen($chars) - 1) ];
			}
			return $temchars;
		}
		$code = randcode1(6);		
		
		$message = $SiteName."注册验证码：".$code."。此验证码十分钟有效。";
		require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Ext/sms.func.php');
		$result = send_sms($mobile,$message);
		
		if($result==1){
			$_SESSION['mobile'] = $mobile;
			$_SESSION['mobile_code'] = md5($mobile.'|'.$code);
			$_SESSION['mobile_time'] = time();
			$_SESSION['mobile_send'] = $_SESSION['mobile_send'] + 1;
			$Data = array(
				"status"=> 1,
				"msg"=>"发送成功，验证码十分钟有效。"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}else{
			$Data = array(
				"status"=> 0,
				"msg"=>"发送失败"
			);
			echo json_encode($Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
}
if($_POST){
	$flag=true;
	$msg="";
	mysql_query("begin");
	if(empty($_POST["Users_Account"])){
		$Data=array(
			'status'=>0,
			'msg'=>'请填写用户名'
		);
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if(empty($_POST["Users_PasswordA"]) || empty($_POST["Users_PasswordB"])){
		$Data=array(
			'status'=>0,
			'msg'=>'登录密码和确认密码都必须填写'
		);
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
		exit;		
	}
	if($_POST["Users_PasswordA"]!=$_POST["Users_PasswordB"]){
		$Data=array(
			'status'=>0,
			'msg'=>'登录密码和确认密码不一致，请修改'
		);
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if(!$_POST["Users_Mobile"]){
		$Data=array(
			'status'=>0,
			'msg'=>'请输入手机号码'
		);
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($setting["sms_enabled"]==1){
		if(!$_POST["sms_code"]){
			$Data=array(
				'status'=>0,
				'msg'=>'请输入手机验证码'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		if(!preg_match("/[0-9]{6}/", $_POST['sms_code']) || $_SESSION['mobile_code'] != md5($_POST['Users_Mobile'].'|'.$_POST['sms_code'])){
			$Data=array(
				'status'=>0,
				'msg'=>'手机验证码不正确'
			);
			echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}
	
	if(!$_POST["Trade"]){
		$Data=array(
			'status'=>0,
			'msg'=>'请选择二级行业'
		);
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
		exit;
	}	
	
	$rsUsers=$DB->GetRs("users","*","where Users_Account='".$_POST["Users_Account"]."'");
	if($rsUsers){
		$Data=array(
			'status'=>0,
			'msg'=>'该用户已经存在，请修改'
		);
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
		exit;
	}else{
		$rsUsers=$DB->GetRs("users","*","order by Users_ID desc");
		function RandChar($length=10){
			$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$temchars = '';
			for($i=0;$i<$length;$i++)
			{
				$temchars .= $chars[ mt_rand(0, strlen($chars) - 1) ];
			}
			return $temchars;
		}
		for($i=0;$i<=1;$i++){
			$Users_ID=RandChar(10);
			$rsUsers=$DB->GetRs("users","*","where Users_ID='".$Users_ID."'");
			$i=$rsUsers?0:1;
		}
		
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Users_WechatToken"=>RandChar(10),
			"Users_Account"=>$_POST["Users_Account"],
			"Users_Password"=>md5($_POST["Users_PasswordA"]),
			"Users_Mobile"=>$_POST["Users_Mobile"],
			"Users_Right"=>'{"web":["web"],"kanjia":["kanjia"],"zhuli":["zhuli"],"zhongchou":["zhongchou"],"games":["games"],"weicuxiao":["sctrach","fruit","turntable","battle"],"votes":["votes"]}',
			"Users_Status"=>1,
			"Users_ExpireDate"=>time()+86400*3,
			"Users_Industry"=>$_POST["Trade"],
			"Users_Remarks"=>'',
			"Users_CreateTime"=>time()
		);
		$Add=$DB->Add("users",$Data);
		$flag=$flag&&$Add;
		//设置上传文件夹
		$save_path = $_SERVER["DOCUMENT_ROOT"].'/uploadfiles/'.$Users_ID.'/';
		if(!is_dir($save_path)){
			mkdir($save_path,0777,true);
		}
		if(!is_dir($save_path.'image/')){
			mkdir($save_path.'image/');
		}
		if(!is_dir($save_path.'media/')){
			mkdir($save_path.'media/');
		}
		if(!is_dir($save_path.'file/')){
			mkdir($save_path.'file/');
		}
		//设置首次关注
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Reply_TextContents"=>"非常高兴认识你，新朋友！"
		);
		$Add=$DB->Add("wechat_attention_reply",$Data);
		$flag=$flag&&$Add;
		//初始化微商城
		$Data=array(
			"Users_ID"=>$Users_ID,
			"ShopName"=>$_POST["Users_Account"]."的微商城",
			"Skin_ID"=>9
		);
		$Add=$DB->Add("shop_config",$Data);
		$flag=$flag&&$Add;
		$skin_home = $DB->GetRs("shop_skin","Skin_Json","where Skin_ID=9");
		//初始化微商城首页
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Skin_ID"=>9,
			"Home_Json"=>$skin_home["Skin_Json"]
		);
		$Add=$DB->Add("shop_home",$Data);
		$flag=$flag&&$Add;


		//初始化分销配置 edit 2016.3.23
		$Data = array(
			'Users_ID'=>$Users_ID
		);
		$Add=$DB->Add('distribute_config',$Data);
		$flag=$flag&&$Add;

		//初始化分销级别
		$Data=array(
			"Users_ID"=>$Users_ID,
			"Level_Name"=>"普通分销商",
			"Level_LimitType"=>3,
			"Level_PeopleLimit"=>json_encode(array(1=>'0'),JSON_UNESCAPED_UNICODE),
			"Level_CreateTime"=>time()
		);
		$Add=$DB->Add('distribute_level',$Data);
		update_dis_level($DB,$Users_ID);
		$flag=$flag&&$Add;
		
		//循环设置各功能模块	
		$Permit=array(
			"shop"=>"微商城",
			"user"=>"会员中心",
			"scratch"=>"刮刮卡",
			"fruit"=>"水果达人",
			"turntable"=>"欢乐大转盘",
			"battle"=>"一战到底"
		);
		foreach($Permit as $k=>$v){
			//根据授权的功能模块添加素材
			$Material=array(
				"Title"=>$v,
				"ImgPath"=>"/static/api/images/cover_img/".$k.".jpg",
				"TextContents"=>"",
				"Url"=>"/api/".$Users_ID."/".$k."/"
			);
			$Data=array(
				"Users_ID"=>$Users_ID,
				"Material_Table"=>$k,
				"Material_TableID"=>0,
				"Material_Display"=>0,
				"Material_Type"=>0,
				"Material_Json"=>json_encode($Material,JSON_UNESCAPED_UNICODE),
				"Material_CreateTime"=>time()
			);
			$Add=$DB->Add("wechat_material",$Data);
			$flag=$flag&&$Add;
			//添加关键词自动回复功能,并将素材id对应进去
			$Data=array(
				"Users_ID"=>$Users_ID,
				"Reply_Table"=>$k,
				"Reply_TableID"=>0,
				"Reply_Display"=>0,
				"Reply_Keywords"=>$v,
				"Reply_PatternMethod"=>0,
				"Reply_MsgType"=>1,
				"Reply_MaterialID"=>$DB->insert_id(),
				"Reply_CreateTime"=>time()
			);
			$Add=$DB->Add("wechat_keyword_reply",$Data);
			$flag=$flag&&$Add;
		}
		if($flag){
			mysql_query("commit");
			$Data=array(
				'status'=>1
			);
		}else{
			mysql_query("roolback");
			$Data=array(
				'status'=>0,
				'msg'=>'注册失败，请联系客服人员'
			);
		}
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);
		exit;
	}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SiteName;?></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/login.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/account.js'></script>
<script language="javascript">$(document).ready(account_obj.reg_init);</script>
</head>
<body>
<div class="login-box regiest-box">
	<div class="login-title">
		<h1>注册</h1>
		<span class="login-english">Register</span>
	</div>

	<div class="login-form">
		<form action="?">
			<label for="Account" class="label"><div class="label-content username"></div></label>
  			<input type="text" name="Users_Account" id="Account" class="input" placeholder="用户名"  />
			<div class="split"></div>

  			<label for="Password" class="label"><div class="label-content password"></div></label>
  			<input type="password" name="Users_PasswordA" id="Password" class="input" placeholder="密码"  />
			<div class="split"></div>

			<label for="Password" class="label"><div class="label-content password"></div></label>
  			<input type="password" name="Users_PasswordB" id="Password" class="input" placeholder="确认密码"  />
			<div class="split"></div>

			<label for="mobile" class="label"><div class="label-content mobile"></div></label>
  			<input type="text" name="Users_Mobile" id="mobile" class="input mobile" placeholder="手机号码"  />
			<div class="split"></div>
			
			<?php if($setting["sms_enabled"]==1){?>
			<label for="VerifyCode" class="label"><div class="label-content virtrfy"></div></label>
  			<input type="text" name="sms_code" id="VerifyCode" class="input virtrfycode" placeholder="短信验证码"  /><span class="faxinxi" id="sendsms">发送验证码</span>
			<div class="split split-min"></div>
			<?php }?>
			<select id="trade_0" class="trade_1">
        	<option value="0">选择行业</option>
	        <?php
				$lists = array();
				$DB->get("industry","*","where parentid=0 order by id asc");
				while($r=$DB->fetch_assoc()){
					$lists[] = $r;
				}
				foreach($lists as $t){
					echo '<option value="'.$t["id"].'">'.$t["name"].'</option>';
				}
			?>
			</select>
			<select id="trade_1" name="Trade" class="trade_2">
				<option value="0">选择行业</option>
			</select>
  			<div class="split split-min"></div>
  			<input type="submit" value="立即申请" class="submit login_btn">
		</form>
	</div>

	<a class="tips-login" href="javascript:void(0);" onclick="window.location.href='/member/login.php';">登录</a>

	<div class="login_msg"></div>
</div>
</body>
</html>