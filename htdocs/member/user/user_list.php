<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
$rsConfig=$DB->GetRs("user_config","UserLevel","where Users_ID='".$_SESSION["Users_ID"]."'");
if(empty($rsConfig)){
	header("location:config.php");
}else{
	if(empty($rsConfig['UserLevel'])){
		$UserLevel[0]=array(
			"Name"=>"普通会员",
			"UpIntegral"=>0,
			"ImgPath"=>""
		);
		$Data=array(
			"UserLevel"=>json_encode($UserLevel,JSON_UNESCAPED_UNICODE)
		);
		$DB->Set("user_config",$Data,"where Users_ID='".$_SESSION["Users_ID"]."'");
	}else{
		$UserLevel=json_decode($rsConfig['UserLevel'],true);
		
	}
}


$condition = "where Users_ID='".$_SESSION["Users_ID"]."'";
if(isset($_GET["search"])){
	if($_GET["search"]==1){
		if(!empty($_GET["Keyword"])){
			$condition .= " and ".$_GET["Fields"]." LIKE '%".$_GET["Keyword"]."%'";
		}
		if(isset($_GET["UserFrom"])){
			if($_GET["UserFrom"]<>''){
				$condition .= " and User_From=".$_GET["UserFrom"];
			}
		}
		if(isset($_GET["MemberLevel"])){
			if($_GET["MemberLevel"]<>''){
				$condition .= " and User_Level=".$_GET["MemberLevel"];
			}
		}
	}
}
$condition .= " order by User_CreateTime desc";
$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];
if(isset($action))
{
	if($action=="del"){
		//1删除用户表记录
		$okdel = $DB->GetRs("distribute_account","User_ID","where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_GET["UserID"]);
		if($okdel)
		{
			echo '<script language="javascript">alert("该会员有分销账号，不能删除");history.back();</script>';
			exit;
		}
		//1删除用户表记录
		$Flag=$DB->Del("user","Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_GET["UserID"]);
		//删除用户订单记录
		//删除用户签到
		//删除用户团购记录
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
	if($action=="mod_password")
	{
		$Data=array(
			"User_Password"=>md5($_POST['Password'])
		);
		$Set=$DB->Set("user",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_POST["UserID"]);
		if($Set){
			$Data=array("status"=>1);
		}else{
			$Data=array("status"=>0);
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($action=="user_mod")
	{
		$FieldName=array("User_No","User_Name","User_Mobile","User_Integral","","User_Level");
		$Data=array(
			$FieldName[$_POST['field']]=>$_POST['Value']
		);
		$Set=$DB->Set("user",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_POST["UserID"]);
		if($Set){
			$Data=array("status"=>1);
		}else{
			$Data=array("status"=>0,"msg"=>"写入数据库失败");
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	if($action=="integral_mod")
	{
		$rsUser=$DB->GetRs("user","User_Integral,User_TotalIntegral,User_Level","where Users_ID='".$_SESSION["Users_ID"]."' and User_ID='".$_POST["UserID"]."'");
		$levelID=$rsUser["User_Level"];
		$levelName=$UserLevel[$rsUser["User_Level"]]["Name"];
		if($rsUser['User_Integral']+$_POST['Value']<0){
			$Data=array("status"=>2);
		}else{
			//增加
			$Data=array(
				'Record_Integral'=>$_POST['Value'],
				'Record_SurplusIntegral'=>$rsUser['User_Integral']+$_POST['Value'],
				'Operator_UserName'=>$_SESSION["Users_Account"],
				'Record_Type'=>1,
				'Record_Description'=>"手动修改积分",
				'Record_CreateTime'=>time(),
				'Users_ID'=>$_SESSION['Users_ID'],
				'User_ID'=>$_POST["UserID"]
			);
			$Add=$DB->Add('user_Integral_record',$Data);
			if($_POST['Value']>0){
				foreach($UserLevel as $k=>$v){
					if(!empty($v['UpIntegral'])){
					 if($rsUser['User_TotalIntegral']+$_POST['Value']>=$v['UpIntegral']){
						 $levelID=$k;
						 $levelName=$v['Name'];
					 }
					}
				}
				$Data=array(
					"User_Level"=>$levelID,
					"User_Integral"=>$rsUser['User_Integral']+$_POST['Value'],
					"User_TotalIntegral"=>$rsUser['User_TotalIntegral']+$_POST['Value']
				);
			}else{
				$levelName=$UserLevel[$rsUser["User_Level"]]["Name"];
				$Data=array(
					"User_Integral"=>$rsUser['User_Integral']+$_POST['Value']
				);
			}
			$Set=$DB->Set("user",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_POST["UserID"]);
			if($Set){
				$Data=array("status"=>1,"lvl"=>1,"level"=>$levelName);
				
				require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
				$weixin_message = new weixin_message($DB,$_SESSION["Users_ID"],$_POST["UserID"]);
				$contentStr = $_POST['Value']>0 ? "管理员手动增加".$_POST['Value']."积分" : "管理员手动减少".$_POST['Value']."积分";
				$weixin_message->sendscorenotice($contentStr);
				
			}else{
				$Data=array("status"=>0,"msg"=>"写入数据库失败");
			}
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($action=="money_mod")
	{
		$rsUser=$DB->GetRs("user","User_Money","where Users_ID='".$_SESSION["Users_ID"]."' and User_ID='".$_POST["UserID"]."'");
		if($rsUser['User_Money']+$_POST['Value']<0){
			$Data=array("status"=>2);
		}else{
			//增加充值记录
			if($_POST['Value']>0){
				$Data=array(
					'Users_ID'=>$_SESSION['Users_ID'],
					'User_ID'=>$_POST["UserID"],
					'Amount'=>$_POST['Value'],
					'Total'=>$rsUser['User_Money']+$_POST['Value'],
					'Operator'=>$_SESSION["Users_Account"]." 线下充值 +".$_POST['Value'],
					'Status'=>1,
					'CreateTime'=>time()
				);
				$Add=$DB->Add('user_charge',$Data);
			}
			//增加资金流水
			$Data=array(
				'Users_ID'=>$_SESSION['Users_ID'],
				'User_ID'=>$_POST["UserID"],				
				'Type'=>1,
				'Amount'=>$_POST['Value'],
				'Total'=>$rsUser['User_Money']+$_POST['Value'],
				'Note'=>$_SESSION["Users_Account"].($_POST['Value']>0 ? " 线下充值 +".$_POST['Value'] : " 线下减余额 ".$_POST['Value']),
				'CreateTime'=>time()			
			);
			$Add=$DB->Add('user_money_record',$Data);
			//更新用户余额
			$Data=array(				
				'User_Money'=>$rsUser['User_Money']+$_POST['Value']					
			);
			$Set=$DB->Set("user",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and User_ID=".$_POST["UserID"]);
			if($Set){
				$Data=array("status"=>1);
			}else{
				$Data=array("status"=>0,"msg"=>"写入数据库失败");
			}
		}
		echo json_encode($Data,JSON_UNESCAPED_UNICODE);
		exit;
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/user.js?t=01456231056'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a>
          <dl>
            <dd class="first"><a href="lbs.php">一键导航设置</a></dd>
          </dl>
        </li>
        <li class="cur"> <a href="user_list.php">会员管理</a>
          <dl>
            <dd class="first"><a href="user_level.php">会员等级设置</a></dd>
            <dd class=""><a href="user_profile.php">会员注册资料</a></dd>
            <dd class=""><a href="card_benefits.php">会员权利说明</a></dd>
            <dd class=""><a href="user_list.php">会员管理</a></dd>
          </dl>
        </li>
        <li class=""> <a href="card_config.php">会员卡设置</a></li>
        
        <li class=""><a href="business_password.php">商家密码设置</a></li>
		<li class="cur"><a href="message.php">消息发布管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
    <script language="javascript">
	var level_ary=Array();
	<?php foreach($UserLevel as $k=>$v){
		 echo 'level_ary['.$k.']="'.$v['Name'].'";';
	}?>
	$(document).ready(function(){user_obj.user_init();});
</script>
    <div id="update_post_tips"></div>
    <div id="user" class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="?">
        <div class="l">
		  <select name="Fields">
		    <option value="User_Name">会员名称</option>
			<option value="User_NickName">会员昵称</option>
			<option value="User_Mobile">会员手机</option>
			<option value="User_No">会员卡号</option>
		  </select>
          <input type="text" name="Keyword" value="" class="form_input" size="15" />
          来源：
          <select name="UserFrom">
            <option value="">--请选择--</option>
            <option value='0'>微信</option>
            <option value='1'>注册</option>
          </select>
          会员等级：
     
          <select name="MemberLevel">
            <option value="">--请选择--</option>
            
            <?php foreach($UserLevel as $k=>$u){?>
            	<option value='<?php echo $k;?>'><?php echo $u["Name"];?></option>
            <?php }?>
          </select>
          <input type="hidden" name="search" value="1" />
          <input type="submit" class="search_btn" value=" 搜索 " />
        </div>
        <div class="r"><strong>提示：</strong><span class="fc_red">双击表格可修改会员资料，按回车提交修改</span></div>
      </form>
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
            <td width="8%" nowrap="nowrap">来源</td>
            <td width="10%" nowrap="nowrap">手机号</td>
            <td width="8%" nowrap="nowrap">姓名</td>
            <td width="12%" nowrap="nowrap">总消费额</td>
            <td width="10%" nowrap="nowrap">头像</td>
            <td width="7%" nowrap="nowrap">会员卡号</td>
            <td width="8%" nowrap="nowrap">会员等级</td>
            <td width="7%" nowrap="nowrap">积分</td>
			<td width="7%" nowrap="nowrap">余额</td>
            <td width="10%" nowrap="nowrap">注册时间</td>
            <td width="8%" nowrap="nowrap" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("user","*",$condition,$pageSize=10);
		$i=1;
		while($rsUser=$DB->fetch_assoc()){?>
          <tr UserID="<?php echo $rsUser['User_ID'] ?>">
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
			<td nowrap="nowrap"><?php echo $rsUser['User_From']==0?'微信':'注册'?></td>
            <td nowrap="nowrap" class="upd_rows" field="2"><span class="upd_txt"><?php echo $rsUser['User_Mobile'] ?></span></td>
            <td nowrap="nowrap" class="upd_rows" field="1"><span class="upd_txt"><?php echo $rsUser['User_Name'] ?></span></td>
            <td nowrap="nowrap">&yen;&nbsp;<?php echo $rsUser['User_Cost']?></td>
            <td nowrap="nowrap"><?php echo $rsUser['User_Money'] ? '<img src="'.$rsUser['User_HeadImg'].'" width="60" height="60" />' : "";?></td>
            <td nowrap="nowrap" class="upd_rows" field="0">No. <span class=""><?php echo $rsUser['User_No'] ?></span></td>
            <td nowrap="nowrap" class="upd_select" field="5"><span class="upd_txt"><?php echo empty($UserLevel[$rsUser["User_Level"]]["Name"]) ? '' : $UserLevel[$rsUser["User_Level"]]["Name"]; ?></span></td>
            <td nowrap="nowrap" class="upd_points" field="3"><span class="upd_txt"><?php echo $rsUser['User_Integral'] ?></span></td>
			<td nowrap="nowrap" class="upd_money" field="4"><span class="upd_txt"><?php echo $rsUser['User_Money'] ?></span></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsUser['User_CreateTime']) ?></td>
            <td nowrap="nowrap" class="last"><a href="#modpass"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a><a href="user_view.php?UserID=<?php echo $rsUser['User_ID'] ?>"><img src="/static/member/images/ico/view.gif" align="absmiddle" /></a></td>
		  </tr>
          <?php $i++;
		  }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
  <div id="mod_user_pass" class="lean-modal lean-modal-form">
    <div class="h">修改会员密码<span></span><a class="modal_close" href="#"></a></div>
    <form class="form">
      <div class="rows">
        <label>密码：</label>
        <span class="input">
        <input name="Password" value="" type="text" class="form_input" size="26" maxlength="16" notnull>
        <font class="fc_red">*</font></span>
        <div class="clear"></div>
      </div>
      <div class="rows">
        <label></label>
        <span class="submit">
        <input type="submit" value="确定提交" name="submit_btn">
        </span>
        <div class="clear"></div>
      </div>
      <input type="hidden" name="UserID" value="">
      <input type="hidden" name="action" value="mod_password">
    </form>
    <div class="tips"></div>
  </div>
</div>
</div>
</body>
</html>