<div id="footer_user_points"></div>
<div id="footer_user"> <a href="/api/<?php echo $UsersID ?>/user/mycard/" class="m0">会员卡</a> <a href="/api/<?php echo $UsersID ?>/user/message/" class="m1">消息
  <?php $rsMessage=$DB->GetRs("user_message","count(Message_ID) as Message_Count","where Users_ID='".$UsersID."' and Message_ID NOT IN(select Message_ID from user_message_record where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"].")");
  echo empty($rsMessage['Message_Count'])?'':'<div><span><font>'.$rsMessage['Message_Count'].'</font></span></div>' ?>
  </a> <a href="/api/<?php echo $UsersID ?>/user/integral/" class="m2">签到&nbsp;&nbsp;</a> <a href="/api/<?php echo $UsersID ?>/user/" class="m4">我的</a> </div>
  
<?php
$KfIco = '';
$kfConfig=$DB->GetRs("kf_config","*","where Users_ID='".$UsersID."' and KF_IsShop=1 and KF_Code<>''");
$kfConfig["KF_Code"] = htmlspecialchars_decode($kfConfig["KF_Code"],ENT_QUOTES);
?>
<?php if(!empty($kfConfig)){?>
<?php echo $kfConfig["KF_Code"];?>
<?php }?>