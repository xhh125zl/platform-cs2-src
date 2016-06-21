<?php require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["BIZ_ID"])){
	header("location:/biz/login.php");
}
if($_POST){
    $userid = $_POST['userid'];
    if(!preg_match("/^[1-9]\d*$/",$userid)){
        echo "<script>alert('会员id只能是数字!');window.location='bind_user.php';</script>";
        exit;
    }
    $rsBiz=$DB->GetRs("biz","*","where Biz_ID=".$_SESSION["BIZ_ID"]);

    $UserRs = $DB->GetRs('user','*',"where Users_ID='".$rsBiz['Users_ID']."' and User_ID=".$userid);
    if(empty($UserRs)){
        echo "<script>alert('该会员不存在!');window.location='bind_user.php';</script>";
        exit;
    }else{
		 $UserRow = $DB->GetRs('biz','*','where UserID='.$userid.' and Biz_ID!='.$_SESSION["BIZ_ID"]);
		 if($UserRow){
			  echo "<script>alert('该会员其他商家已被绑定!');window.location='bind_user.php';</script>";
			  exit;
		 }
        $data = array('UserID'=>$userid);
        $Flag = $DB->Set('biz',$data,"where Biz_ID=".$_SESSION["BIZ_ID"]);
        if($Flag){
		echo '<script language="javascript">alert("绑定成功");window.location="bind_user.php";</script>';
	}else{
		echo '<script language="javascript">alert("绑定失败");history.back();</script>';
	}
	exit;
    }	
}else{
    $rsBiz=$DB->GetRs("biz","*","where Biz_ID=".$_SESSION["BIZ_ID"]);
    $rsUsers=$DB->GetRs("users","Users_WechatAccount,Users_WechatName","where Users_ID='".$rsBiz["Users_ID"]."'");
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
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/weicbd.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/biz/js/shop.js'></script>
    <script language="javascript">$(document).ready(shop_obj.biz_edit_init);</script>
    <div class="r_nav">
      <ul>
        <li><a href="account.php">商家资料</a></li>
        <li><a href="account_edit.php">修改资料</a></li>
        <li><a href="address_edit.php">收货地址</a></li>
        <li class="cur"><a href="bind_user.php">绑定会员</a></li>
        <li><a href="account_password.php">修改密码</a></li>
        <li><a href="account_payconfig.php">结算配置</a></li>
      </ul>
    </div>
    <div id="bizs" class="r_con_wrap">
      <form class="r_con_form" method="post" action="?" id="biz_edit">
        <div class="rows">
          <label>会员id</label>
          <span class="input">
          <input type="text" name="userid" value="<?=empty($rsBiz['UserID'])?'':$rsBiz['UserID'];?>" class="form_input" size="35" maxlength="50" notnull/>
          <font class="fc_red">*</font>如果您没有该商城会员,请先关注公众号“<?php echo !empty($rsUsers['Users_WechatAccount'])?$rsUsers['Users_WechatAccount']:$rsUsers['Users_WechatName'];?>”,进入商城个人中心,查看您的会员id。</span>
          <div class="clear"></div>
        </div>
        <?php 
        if(!empty($rsBiz['UserID'])){
            $rsUser = $DB->GetRs('user','User_ID,User_NickName',"where Users_ID='".$rsBiz["Users_ID"]."' and User_ID=".$rsBiz['UserID']);
        ?>
            <div class="rows">
                <label>绑定信息</label>
                <span class="input">
                    会员&nbsp;&nbsp;&nbsp;id：<?=empty($rsBiz['UserID'])?'':$rsBiz['UserID']?><br>
                    会员昵称&nbsp;:&nbsp;<?=!empty($rsUser['User_NickName'])?$rsUser['User_NickName']:'未填写'?>
                </span>
                <div class="clear"></div>
            </div>
          
        <?php } ?>
        <div class="rows">
          <label></label>
          <span class="input">
              <input type="submit" class="btn_green" name="submit_button" value="<?=empty($rsBiz['UserID'])?'提交绑定':'修改绑定'?>" /><?php if(!empty($rsBiz['UserID'])){ echo '<font class="fc_red">*</font>您已经绑定过会员，请仔细查看会员id和会员昵称是否正确，可以重新绑定!';}?></span>
          <div class="clear"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>