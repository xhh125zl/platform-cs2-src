<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Framework/Conn.php');
require_once(CMS_ROOT.'/Framework/Ext/virtual.func.php');
require_once(CMS_ROOT.'/include/helper/order.php');
require_once(CMS_ROOT.'/include/helper/tools.php');
require_once(CMS_ROOT.'/Framework/Ext/sms.func.php');
require_once(CMS_ROOT.'/include/library/pay_order.class.php');
require_once(CMS_ROOT .'/include/api/b2cshopconfig.class.php');
require_once(CMS_ROOT .'/include/api/shopconfig.class.php');
require_once(CMS_ROOT.'/include/api/users.class.php');

$notifyData = $_GET;
if(isset($notifyData['creatTradeResult'])){
	$creatTradeResult = json_decode($notifyData['creatTradeResult'],true);
	if($creatTradeResult[0]){
		echo $creatTradeResult[0]['failReason'];
		exit;
	}
}
if($notifyData['resultCode'] == 'EXECUTE_SUCCESS' && $notifyData['success'] == true){
    $orderno = $notifyData['orderNo'];
    $result = users::Getruleuserbyyijiid(['Yiji_UserID' => $notifyData['userId']]);
    if($result['errorCode']!=0){
        echo "<script>alert(\"获取Yiji信息失败！\");location.href=\"/pay/yijipay/reg.php\"</script>";
        exit;
    }
    $rsBindUser = $result['data'];
    if(!$rsBindUser){
        echo "<script>alert(\"非法访问！\");location.href=\"/pay/yijipay/reg.php\"</script>";
        exit;
    }
    $UsersID = $rsBindUser['Users_ID'];
    $payconfig = json_decode($rsBindUser['payAccountConfig'],true);
    $biz_account = $_SESSION['Biz_Account'];
    shopconfig::updateBizapply([
        'Biz_Account' => $biz_account,
        'bizApplyData' => [
            'is_del' => 1,
            'status' => 1,
            'CreateTime' => time()
        ]
    ]);
    
    
    $rsObjBindUser = Biz::where(['Biz_Account' => $biz_account])->first();
    $rsObjBindUser->is_auth = 1;
    $rsObjBindUser->save();
    
    $payconfig['payAccountOrderNo'] = $orderno;
    $payAccountConfig = json_encode($payconfig,JSON_UNESCAPED_UNICODE);
    $result = users::Updateyijistatus(['Yiji_UserID' => $notifyData['userId'],'usersData' =>[
        'status' => 1,
        'Type' => 1,
        'isPayAccount' => 1,
        'payAccountConfig' =>$payAccountConfig
    ]]);
    if($result['errorCode'] == 0){
        header("Location:/user/admin.php?act=store");
        exit;
    }else{
        echo "<script>alert(\"修改状态失败！\");location.href=\"/pay/yijipay/reg.php\"</script>";
        exit;
    }
}else{
    echo $notifyData['resultMessage'];
    exit;
}
?>