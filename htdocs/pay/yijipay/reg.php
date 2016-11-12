<?php
header("content-Type: text/html; charset=UTF-8");
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once(CMS_ROOT.'/include/library/pay_order.class.php');
require_once(CMS_ROOT.'/include/helper/tools.php');
require_once(CMS_ROOT.'/include/helper/global_func.php');
require_once(CMS_ROOT.'/include/support/yiji_helper.php');
require_once(CMS_ROOT.'/include/api/users.class.php');
require_once(CMS_ROOT . '/include/api/shopconfig.class.php');
require_once(CMS_ROOT . '/include/api/const.php');
//第一步基本信息注册
$action = "first";
$UsersID = "";
$UserID = 0;

$Biz_Account = isset($_SESSION['Biz_Account']) && $_SESSION['Biz_Account']?$_SESSION['Biz_Account']:'';
if(!$Biz_Account){
    header("Location:/user/login.php");
    exit;
}
$result = users::getRuleUser([
    'Biz_Account' => $Biz_Account
]);

$rsBindUser = [];
if($result['errorCode'] == 0){
    $rsBindUser = $result['data'];
    $UsersID = $result['data']['Users_ID'];
    $UserID = $result['data']['User_ID'];
    if($rsBindUser['status']==2){
        header("Location:/pay/yijipay/userinfo.php");
        exit;
    } else if($rsBindUser['status']==1) {
        //我的账户审核中
        echo '您已注册过支付账户， 一般需要1-3个工作日的审核期，请耐心等待!';
        exit();
    }
}else{
    $result = users::getUser([
        'Biz_Account' => $Biz_Account
    ]);
    $UsersID = $result['data']['Users_ID'];
    $UserID = $result['data']['User_ID'];  
}
$rsPay = Users_PayConfig::where(['Users_ID' => $_SESSION['Users_ID']])->first()->toArray();

if(isset($_POST['act']) && $_POST['act']=='base'){
    $yijiRequestOrderNo = 'OU'.date("YmdHis",time()).$UserID.time();
    $post = $_POST;
    validateInput($post);
    if(empty($rsBindUser)){
        //支付账户的创建
        require_once(CMS_ROOT.'/pay/yijipay/autoload.php');
        $param = [
            'userName' => $_POST['userName'],
            'email' => $_POST['email'],
            'mobile' => $_POST['mobile'],
            'registerUserType' => $_POST['registerUserType'],
            'OrderNo' => $yijiRequestOrderNo
        ];
        $account = new Account();
        $result = $account->ppmNewRuleRegisterUser($param);
        $result = json_decode($result, true);
        if ($result && $result['success'] == true && $result['resultCode'] == 'EXECUTE_SUCCESS') {
            $post['Yiji_UserID'] = $result['userId'];
            
        }else{
            echo "<script>alert(\"".(isset($result['resultMessage'])?addslashes($result['resultMessage']):'')."！\");location.href=\"/pay/yijipay/reg.php\"</script>";
            exit;
        }
        logging("我的基本会员信息", $post);
    }
    $payAccountConfig = json_encode($post, JSON_UNESCAPED_UNICODE);
    $_SESSION[$UsersID.'_'.$UserID.'_payAccountConfig'] = $payAccountConfig;
    $action = "second";
}else if(isset($_POST['act']) && $_POST['act']=='uploadFile'){
    //图片处理
    
    $uri = rtrim(IMG_SERVER, '/').'/user/lib/upload.php';
    // 参数数组
    $data = array (
        'act' => $_POST['act'],
        'data' => $_POST['data'],
        'filepath' => '../../uploadfiles/userfile',
        'Users_Account' => $Biz_Account
    );
    
    echo http_request($uri, "post" , $data);
    exit;
}elseif (isset($_POST['act']) && $_POST['act'] == 'delImg') {
    $uri = rtrim(IMG_SERVER, '/').'/user/lib/upload.php';
	// 参数数组
	$data = array (
		'act' => $_POST['act'],
        'image_path' => htmlspecialchars(trim($_POST['image_path'])),
        'index' => 0
	);
	echo http_request($uri, "post", $data);
    exit;
}elseif (isset($_POST['act']) && $_POST['act'] == 'other') {
    $yijiRequestOrderNo = 'PA'.date("YmdHis",time()).$UserID.time();
    $post = $_POST;
    $payAccountConfig = isset($_SESSION[$UsersID.'_'.$UserID.'_payAccountConfig'])?$_SESSION[$UsersID.'_'.$UserID.'_payAccountConfig']:"";
    $yijiRequestOrderNo = 'PA'.date("YmdHis",time()).$UserID.time();
    if($payAccountConfig){
        
        $payAccount  = json_decode($payAccountConfig, true);
        $payAccount = array_merge($payAccount, $post);
        $payAccount['payAccountOrderNo'] = $yijiRequestOrderNo;
        $data = json_encode($payAccount, JSON_UNESCAPED_UNICODE);
        require_once(CMS_ROOT.'/pay/yijipay/autoload.php');
        $uri = SHOP_URL;
        $param = [
            'userTerminalType' => "MOBILE",
            'registerUserType' => $payAccount['registerUserType'],
            'outUserId' => $payAccount['outUserId'],
            'userId' => $payAccount['Yiji_UserID'],
            'mobile' => $payAccount['mobile'],
            'profession' => $payAccount['profession'],
            'returnUrl' => SITE_URL. 'pay/yijipay/return_reg_url.php',
            'notifyUrl' => SHOP_URL. 'pay/yijipay/notify_reg_url.php',
            'orderNo' => $yijiRequestOrderNo
        ];
        if(isset($payAccount['image_personCertFrontPath'])){
            $param['personCertFrontPath'] = $uri.trim($payAccount['image_personCertFrontPath']);
        }
        if(isset($payAccount['image_personCertBackPath'])){
            $param['personCertBackPath'] = $uri.trim($payAccount['image_personCertBackPath']);
        }
        logging("我的基本会员信息", $data);
        $userdata = [
            'Users_ID' => $UsersID,
            'User_ID' => $UserID,
            'Yiji_UserID' =>$payAccount['Yiji_UserID'],
            'payAccountConfig' => $data
        ];
        $result = users::addYijiBind(['usersData' => $userdata ]);
        if($result['errorCode'] != 0){
            echo "<script>alert(\"添加失败\");location.href='history.go(-1);'</script>";
            exit;
        }
        //写入商家认证记录
        $BizRes = shopconfig::getBizapply(['Biz_Account'=>$Biz_Account]);
        if($BizRes['errorCode'] != 0){
            echo '<script language="javascript">alert("未找到该商家");history.back();</script>';
            exit;
        }
        $BizInfo = $BizRes['data'][0];
        $baseinfo = json_decode($BizInfo['baseinfo'],true);
        $authinfo = json_decode($BizInfo['authinfo'],true);
        $bizType = isset($payAccount['registerUserType']) && $payAccount['registerUserType']== 'PERSONAL' ? 2: 1;

        if($bizType == 1){
            $baseinfo['company_name'] = isset($payAccount['enterpriseName'])?$payAccount['enterpriseName']:'';
            $baseinfo['address'] = isset($payAccount['address'])?$payAccount['address']:'';
            $baseinfo['main_type'] = 1;
        }
        $baseinfo['goods'] = isset($payAccount['goods'])?$payAccount['goods']:'';
        $baseinfo['contacts'] = isset($payAccount['contacts'])?$payAccount['contacts']:'';
        $baseinfo['mobile'] = isset($payAccount['mobile'])?$payAccount['mobile']:'';
        $baseinfo['email'] = isset($payAccount['email'])?$payAccount['email']:'';
        if($bizType == 1){
            $authinfo['compay_add'] = isset($payAccount['address'])?$payAccount['address']:'';
            $authinfo['compay_license'] = isset($payAccount['licenceNo'])?$payAccount['licenceNo']:'';
            $authinfo['compay_shenfenimg'] = isset($payAccount['image_legalCertFrontPath'])?$payAccount['image_legalCertFrontPath']:'';
            $authinfo['compay_shenfenbackimg'] = isset($payAccount['image_legalCertBackPath'])?$payAccount['image_legalCertBackPath']:'';
            $authinfo['compay_shuiwuimg'] = isset($payAccount['image_taxCertPath'])?$payAccount['image_taxCertPath']:'';
        }
        $authinfo['personCertFrontPath'] = isset($payAccount['image_personCertFrontPath'])?$payAccount['image_personCertFrontPath']:'';
        $authinfo['personCertBackPath'] = isset($payAccount['image_personCertBackPath'])?$payAccount['image_personCertBackPath']:'';
        $Applydata = [
            'baseinfo' => json_encode($baseinfo, JSON_UNESCAPED_UNICODE),
            'authinfo' => json_encode($authinfo, JSON_UNESCAPED_UNICODE),
            'authtype' => $bizType,
            'CreateTime' => time()
        ];
        $Flag = shopconfig::updateBizapply(['Biz_Account'=>$Biz_Account,'bizApplyData'=> $Applydata ]);
        if($Flag['errorCode'] != 0){
            echo '<script language="javascript">alert("写入商家记录失败");history.back();</script>';
            exit;
        }

        $_SESSION[$UsersID.'_'.$UserID.'_payAccountConfig'] = "";
        $account = new Account();
        if($payAccount['registerUserType']=='PERSONAL'){
            /*
            if(!$payAccount['image_personCertFrontPath'] || !$payAccount['image_personCertBackPath']){
                echo "<script>alert(\"请上传身份证正面或者反面照片\");location.href='history.go(-1);'</script>";
                exit;
            }*/
            unset($payAccount);
            $account->registerPayAccount($param);
        }else{
            // 企业和个体户传入参数
            $businessParam = [
                'licensePhotoPath' => isset($payAccount['image_licensePhotoPath'])?$uri.$payAccount['image_licensePhotoPath']:'',
                'legalCertFrontPath' => isset($payAccount['image_legalCertFrontPath'])?$uri.$payAccount['image_legalCertFrontPath']:'',
                'legalCertBackPath' => isset($payAccount['image_legalCertBackPath'])?$uri.$payAccount['image_legalCertBackPath']:'',
                'openLicensePath' => isset($payAccount['image_openLicensePath'])?$uri.$payAccount['image_openLicensePath']:'',
                'organizationCode' => $payAccount['organizationCode'],
                'profession' => $payAccount['profession'],
                'holdingType' => $payAccount['holdingType'],
                'businessTerm' => $payAccount['businessTerm'],
                'businessScope' => $payAccount['businessScope'],
                'title' => 1,
                'enterpriseLicenseType' => $payAccount['enterpriseLicenseType'],
                'enterpriseName' => $payAccount['enterpriseName'],
                'licenceNo' => $payAccount['licenceNo'],
                'province' => $payAccount['provinceName'],
                'city' => $payAccount['cityName'],
                'address' => $payAccount['address'],
                'userTerminalType' => "MOBILE",
                'registerUserType' => $payAccount['registerUserType'],
                'outUserId' => $payAccount['outUserId'],
                'userId' => $payAccount['UserYijiId'],
                'returnUrl' => SITE_URL. 'pay/yijipay/return_reg_url.php',
                'notifyUrl' => SITE_URL. 'pay/yijipay/notify_reg_url.php',
                'OrderNo' => $yijiRequestOrderNo
            ];
            if($payAccount['enterpriseLicenseType']=='G'){
                $businessParam['organizationCodePath'] = $uri.$payAccount['image_organizationCodePath'];
                $businessParam['taxCertPath'] = $uri.$payAccount['image_taxCertPath'];
            }
            if($payAccount['special']==1){
                $businessParam['specialBusinessFirst'] = $uri.$payAccount['image_specialBusinessFirst'];
                $businessParam['specialBusinessSecond'] = $uri.$payAccount['image_specialBusinessSecond'];
            }
            if($payAccount['agentPeople']==1){
                $businessParam['agentCertFrontPath'] = $uri.$payAccount['image_agentCertFrontPath'];
                $businessParam['agentCertBackPath'] = $uri.$payAccount['image_agentCertBackPath'];
                $businessParam['attorneyPath'] = $uri.$payAccount['image_attorneyPath'];
            }
            unset($payAccount);
            $account->registerPayAccount($businessParam);
        }
        exit;
    }
}

$baseInfo = isset($_SESSION[$UsersID.'_'.$UserID.'_payAccountConfig'])?$_SESSION[$UsersID.'_'.$UserID.'_payAccountConfig']:'';
if($baseInfo){
    $baseInfo = json_decode($baseInfo, true);
    $action = "second";
}

?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>开通支付账户</title>
</head>
<link href="/static/api/account/sub.css" type="text/css" rel="stylesheet">
<link href="/static/api/account/font-awesome.min.css" type="text/css" rel="stylesheet">
<link href="/static/user/css/layer.css" type="text/css" rel="stylesheet">
<link href="/static/css/select2.css" rel="stylesheet"/>
<link href="/static/pay/yiji/reg.css" rel="stylesheet"/>
<script type='text/javascript' src='/static/js/jquery-1.11.1.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.zh_cn.js'></script>
<script type="text/javascript" src="/static/pay/yiji/jquery.uploadView.js"></script>
<script type="text/javascript" src="/static/user/js/layer.js"></script>
<script type='text/javascript' src="/static/js/select2.js"></script>
<script type="text/javascript" src="/static/js/locationnew.js"></script>
<script type="text/javascript" src="/static/js/area.js"></script>
<script>
$(function(){
    $(".next_yy").click(function(){
        location.href = $(this).attr("url");
    });
});
</script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l" href="javascript:history.back();"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>开通支付账户
    </div>
    <div class="clear"></div>
    <div class="list_table">
    	<!-- 基本信息注册项 -->
    	<?php if($action == "first" ){ ?>
    	<script>
    	var isRegister = <?=!empty($rsBindUser) && $rsBindUser['Yiji_UserID']?1:0 ?>;
    	</script>
    	<script type="text/javascript" src="/static/pay/yiji/reg.js"></script>
        <script>
        $(function(){
        	showLocation(<?=isset($baseInfo['province']) && $baseInfo['province']?$baseInfo['province']:0 ?>,<?=isset($baseInfo['city']) && $baseInfo['city']?$baseInfo['city']:0 ?>,0);
        });
        </script>
    	<form method="post" action="?step=2" id="base_addform">
    	<input type="hidden" name="act" value="base" />
    	<input type="hidden" name="outUserId" value="<?=$UsersID.'_'.$UserID ?>" />
    	<input type="hidden" name="UserYijiId" value="<?=isset($rsBindUser['Yiji_UserID'])?$rsBindUser['Yiji_UserID']:'' ?>" />
    	<input type="hidden" name="UsersID" value="<?=$UsersID ?>" />
    	<input type="hidden" name="UserID" value="<?=$UserID ?>" />
    	<table width="100%" class="table_x1"> 
            <tr>
                <th>用户类型：</th>
                <td>
                	<select name="registerUserType" >
                    	<option value="PERSONAL" <?=isset($baseInfo['registerUserType']) && $baseInfo['registerUserType']=="PERSONAL" || !isset($baseInfo['registerUserType'])?"selected":"" ?>>个人用户注册</option>
                        <option value="ENTERPRISE" <?=isset($baseInfo['registerUserType']) && $baseInfo['registerUserType']=="ENTERPRISE"?"selected":"" ?> >企业用户注册</option>
                        <option value="INDIVIDUAL" <?=isset($baseInfo['registerUserType']) && $baseInfo['registerUserType']=="INDIVIDUAL"?"selected":"" ?> >个体户用户注册</option>
                    </select>
                </td> 
            </tr>
            <tr>
                <th>主营商品：</th>
                <td><input type="text" name="goods" value="<?=isset($baseInfo['goods']) ? $baseInfo['goods']:"" ?>" class="user_input" placeholder="请输入主营商品"></td>
            </tr>
            <?php if(empty($rsBindUser)){ ?>
            <tr> 
                <th>用户名：</th> 
                <td><input type="text" name="userName" value="<?=isset($baseInfo['userName']) ? $baseInfo['userName']:"" ?>" class="user_input" placeholder="请输入用户名"></td> 
            </tr>
            <tr> 
                <th>邮箱信息：</th> 
                <td><input type="text" name="email" value="<?=isset($baseInfo['email']) ? $baseInfo['email']:"" ?>" class="user_input" placeholder="请输入邮箱"></td> 
            </tr>
            <?php } ?>
            <tr> 
                <th>联系人：</th> 
                <td><input type="text" name="contacts" value="<?=isset($baseInfo['contacts']) ? $baseInfo['contacts']:"" ?>" class="user_input" placeholder="请输入联系人"></td> 
            </tr>
            <tr> 
                <th>手机号：</th> 
                <td><input type="text" name="mobile" value="<?=isset($baseInfo['mobile'])?$baseInfo['mobile']:$Biz_Account ?>" class="user_input" placeholder="请输入手机号"></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>营业执照类型：</th> 
                <td>
                	<select name="enterpriseLicenseType" >
                    	<option value="G" <?=isset($baseInfo['enterpriseLicenseType']) && $baseInfo['enterpriseLicenseType']=="G" || !isset($baseInfo['enterpriseLicenseType'])?"selected":"" ?>>普通营业执照</option>
                        <option value="S" <?=isset($baseInfo['enterpriseLicenseType']) && $baseInfo['enterpriseLicenseType']=="S"?"selected":"" ?> >三合一营业执照</option>
                    </select>
                </td> 
            </tr>
            <tr class="nopersonal"> 
                <th>企业名称：</th> 
                <td><input type="text" name="enterpriseName" value="<?=isset($baseInfo['enterpriseName'])?$baseInfo['enterpriseName']:'' ?>" class="user_input" placeholder="请输入企业名称"></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>营业执照号码：</th> 
                <td><input type="text" name="licenceNo" value="<?=isset($baseInfo['licenceNo'])?$baseInfo['licenceNo']:'' ?>" class="user_input" placeholder="请输入营业执照号码"></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>是否启用代理人：</th> 
                <td>
                	启用<input type="radio" name="agentPeople" value="1" <?=(isset($baseInfo['agentPeople']) && $baseInfo['agentPeople']==1 )?"checked":'' ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                	禁用<input type="radio" name="agentPeople" value="0" <?=(isset($baseInfo['agentPeople']) && $baseInfo['agentPeople']==0) || !isset($baseInfo['agentPeople'])?"checked":'' ?>>
                </td> 
            </tr>
            <tr class="nopersonal"> 
                <th>特许经营：</th> 
                <td>
                	有<input type="radio" name="special" value="1" <?=(isset($baseInfo['special']) && $baseInfo['special']==1 )?"checked":'' ?>>
                	无<input type="radio" name="special" value="0" <?=(isset($baseInfo['special']) && $baseInfo['special']==0) || !isset($baseInfo['special'])?"checked":'' ?>>
                </td> 
            </tr>
            <tr class="nopersonal"> 
                <th>单位所在省：</th> 
                <td>
                	<select name="province" id="loc_province">
                    </select>
                    <input type="hidden" name="provinceName" value="" />
                </td> 
            </tr>
            <tr class="nopersonal"> 
                <th>单位所在市：</th> 
                <td>
                	<select name="city"  id="loc_city">
                    </select>
                    <input type="hidden" name="cityName" value="" />
                </td> 
            </tr>
            <tr class="nopersonal"> 
                <th>单位所在地址：</th> 
                <td><input type="text" name="address" class="user_input" placeholder="" value="<?=isset($baseInfo['address'])?$baseInfo['address']:'' ?>"></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>营业期限：</th> 
                <td><input type="text" name="businessTerm" class="user_input" placeholder="营业期限的格式：YYYY-mm-dd" value="<?=isset($baseInfo['businessTerm'])?$baseInfo['businessTerm']:'' ?>"></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>经营范围：</th> 
                <td><input type="text" name="businessScope" class="user_input" placeholder="" value="<?=isset($baseInfo['businessScope'])?$baseInfo['businessScope']:'' ?>"></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>组织机构代码：</th> 
                <td><input type="text" name="organizationCode" class="user_input" placeholder="" value="<?=isset($baseInfo['organizationCode'])?$baseInfo['organizationCode']:'' ?>" ></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>控股人类型：</th> 
                <td>
                	<select name="holdingType" >
                		<option value="HOLDING_PERSON" <?=isset($baseInfo['holdingType']) && $baseInfo['holdingType']=="HOLDING_PERSON" || !isset($baseInfo['holdingType'])?"selected":"" ?>>控股人是个人</option>
                    	<option value="HOLDING_COM" <?=isset($baseInfo['holdingType']) && $baseInfo['holdingType']=="HOLDING_COM"?"selected":"" ?> >控股人是企业</option>
                    </select>
                </td>
            </tr>
            <tr> 
                <th>职业：</th> 
                <td>
                	<select name="profession" >
                    	<option value="COMPUTER" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="COMPUTER" || !isset($baseInfo['profession'])?"selected":"" ?> >计算机/互联网/通信/电子</option>
                        <option value="SALE" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="SALE"?"selected":"" ?>>销售/零售/采购等业务员</option>
                        <option value="MEAL" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="MEAL"?"selected":"" ?>>餐饮/旅游/美容/家政</option>
                        <option value="IT" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="IT"?"selected":"" ?>>化工/机械/设计等技术人员</option>
                        <option value="ACCOUNTING" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="ACCOUNTING"?"selected":"" ?>>会计/金融/银行/保险</option>
                        <option value="OPERATION" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="OPERATION"?"selected":"" ?>>生产/运营/采购/物流</option>
                        <option value="LIFE" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="LIFE"?"selected":"" ?>>生物/制药/医疗/护理</option>
                        <option value="ADVERT" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="ADVERT"?"selected":"" ?>>广告/市场/媒体/艺术</option>
                        <option value="ARCHITECT" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="ARCHITECT"?"selected":"" ?>>建筑/房地产</option>
                        <option value="HUMAN" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="HUMAN"?"selected":"" ?>>人事/行政/高级管理</option>
                        <option value="TRANSLATE" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="TRANSLATE"?"selected":"" ?>>律师/公务员/教育/翻译</option>
                        <option value="GRAZIERY" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="GRAZIERY"?"selected":"" ?>>农/林/牧/渔业</option>
                        <option value="STAFF" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="STAFF"?"selected":"" ?>>职员</option>
						<option value="OTHERS" <?=isset($baseInfo['profession']) && $baseInfo['profession']=="OTHERS"?"selected":"" ?>>自由职业</option>
                    </select>
                </td>
            </tr>
        </table>
        <div style="text-align:center">
            <a><button class="back_xx" type="submit" id="onbtnclk">下一步</button></a>
    	</div>
    	</form>
    	<?php }else if( $action=='second' && !empty($baseInfo)){ 
    	    ?>
    	<form action="" method="post">
    	<input type="hidden" name="act" value="other" />
    	<input type="hidden" name="UsersID" value="<?=$UsersID ?>" />
    	<input type="hidden" name="UserID" value="<?=$UserID ?>" />
    	<table width="100%" class="table_x1 selectPic">
    	<?php if($baseInfo['registerUserType']!='PERSONAL'){ ?>
        	<tr> 
                <th>营业执照：</th> 
                <td>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传营业执照<input class="js_upFile" type="file" name="licensePhotoPath">
                    	</div>
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_licensePhotoPath" name="image_licensePhotoPath" class="imgpath" value="">
                </div>
                </td> 
            </tr>
         <?php } ?>
         <?php if($baseInfo['registerUserType']!='PERSONAL'){ ?>
            <tr> 
                <th>其他证件照：</th> 
                <td>
                <?php if($baseInfo['enterpriseLicenseType']=='G'){ ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	组织机构代码证<input class="js_upFile" type="file" name="organizationCodePath">
                    	</div>
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_organizationCodePath" name="image_organizationCodePath" class="imgpath"  value="">
                </div>
                <div class="js_uploadBox">
                    <div class="js_showBox">
          				<div class="orgin">
                    	上传税务登记证<input class="js_upFile" type="file" name="taxCertPath">
                    	</div>           
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_taxCertPath" name="image_taxCertPath" class="imgpath" value="">
                </div>
                <?php } ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
          				<div class="orgin">
                    	上传开户许可证<input class="js_upFile" type="file" name="openLicensePath">
                    	</div>          
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_openLicensePath" name="image_openLicensePath" class="imgpath" value="">
                </div>
                <?php if($baseInfo['agentPeople']==1){ ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传委托授权书<input class="js_upFile" type="file" name="attorneyPath">
                    	</div>  
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_attorneyPath" name="image_attorneyPath" class="imgpath"  value="">
                </div>
                <?php } ?>
                </td> 
            </tr>
            <?php } ?>
            <?php if($baseInfo['special']==1){ ?>
            <tr> 
                <th>特许经营许可证：</th> 
                <td>
                
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传证件一<input class="js_upFile" type="file" name="specialBusinessFirst">
                    	</div> 
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_specialBusinessFirst" name="image_specialBusinessFirst" class="imgpath"  value="">
                </div>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传证件二<input class="js_upFile" type="file" name="specialBusinessSecond">
                    	</div> 
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_specialBusinessSecond" name="image_specialBusinessSecond" class="imgpath" value="">
                </div>
                </td> 
            </tr>
            <?php } ?>
            <?php if($baseInfo['registerUserType']!='PERSONAL'){ ?>
            <tr> 
                <th>法人身份证证件照：</th> 
                <td>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传正面照<input class="js_upFile" type="file" name="legalCertFrontPath">
                    	</div> 
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_legalCertFrontPath" name="image_legalCertFrontPath" class="imgpath"  value="">
                </div>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传反面照<input class="js_upFile" type="file" name="legalCertBackPath">
                    	</div> 
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_legalCertBackPath" name="image_legalCertBackPath" class="imgpath"  value="">
                </div>
                </td> 
            </tr>
            <?php } ?>
            <?php if($baseInfo['agentPeople']==1){ ?>
            <tr> 
                <th>代理人身份证：</th> 
                <td>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传正面照<input class="js_upFile" type="file" name="agentCertFrontPath">
                    	</div> 
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_agentCertFrontPath" name="image_agentCertFrontPath" class="imgpath"  value="">
                </div>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传反面照<input class="js_upFile" type="file" name="agentCertBackPath">
                    	</div> 
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_agentCertBackPath" name="image_agentCertBackPath" class="imgpath"  value="">
                </div>
                </td> 
            </tr>
            <?php } ?>
            <?php if($baseInfo['registerUserType']=='PERSONAL'){ ?>
            <tr> 
                <th>个人身份证件照：</th> 
                <td>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传正面照<input class="js_upFile" type="file" name="personCertFrontPath">
                    	</div> 
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_personCertFrontPath" name="image_personCertFrontPath" class="imgpath"  value="">
                </div>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="orgin">
                    	上传反面照<input class="js_upFile" type="file" name="personCertBackPath">
                    	</div> 
                    </div>
                    <!--image_files显示base64编码过的字符串,image_path存放所有的图片路径-->
                    <input type="hidden" class="baseImg" value="">
                    <input type="hidden" id="image_personCertBackPath" name="image_personCertBackPath" class="imgpath" value="">
                </div>
                </td> 
            </tr>
            <?php } ?>
        </table>
        <div style="text-align:center;margin-top:30px;">
        	<a><button type="button" class="next_yy" url="?step=1">返回</button></a>
            <a><button class="back_xx" type="submit" id="onbtnclk">提交</button></a>
    	</div>
    	</form>
    	<script>
        $(function(){
            $("#onbtnclk").click(function(){
                var isperson = <?=isset($baseInfo['registerUserType']) && $baseInfo['registerUserType']=='PERSONAL'?1:0 ?>;
                if(isperson){
                    if($("#image_personCertFrontPath").val()=="" || $("#image_personCertBackPath").val()==""){
                        alert("请上传身份证正面照片和反面照片");
                        return false;
                    }
                }else{
                	if($("#image_licensePhotoPath").val()==""){
                        alert("请上传营业执照证件照");
                        return false;
                    }
                	if($("#image_legalCertFrontPath").val()=="" || $("#image_legalCertBackPath").val()==""){
                        alert("请上传法人身份证正面照片和反面照片");
                        return false;
                    }
                }
            });

            var up = function(){
            	$(".js_upFile").uploadView({
                    uploadBox: '.js_uploadBox',//设置上传框容器
                    showBox : '.js_showBox',//设置显示预览图片的容器
                    width : 120, //预览图片的宽度，单位px
                    height : 100, //预览图片的高度，单位px
                    allowType: ["gif", "jpeg", "jpg", "bmp", "png"], //允许上传图片的类型
                    maxSize :10, //允许上传图片的最大尺寸，单位M
                    success:function(e,ids){
                        $.ajax({
                            type:"POST",
                            url:"",
                            data:{"act":"uploadFile", "data":e},
                            dataType:"json",
                            success:function(data){
                                if (data.errorCode == 0) {
                                    $("#"+ids).val(data.msg);
                                } else {
                                    alert(data.msg);
                                }
                            }
                        });
                    }
                });
            };
        	up();
            //删除图片
            $(document).on('click', '.deleted', function(){
                var me = $(this);
                var obj = me.parent().parent().parent().find(".imgpath");
                
                layer.open({
                    content: '确定删除吗?',
                    btn: ['确定', '取消'],
                    yes: function(){
                        layer.closeAll();
                        $.ajax({
                            type:"POST",
                            url:"",
                            data:{"act":"delImg", "index":me.parent().index(),"image_path":obj.val()},
                            dataType:"json",
                            success:function(data) {
                                if (data.errorCode == 0) {
                                    obj.val("");
                                    me.parent().parent().find(".orgin").show();
                                    me.parent().parent().next(".baseImg").val("");
                                    me.parent().hide();
                                    up();
                                } else {
                                    alert(data.msg);
                                }
                            }
                        });
                    }
                });
            });
        }); 
    	</script>
    	<?php } ?>
    </div>
</div>
</body>
</html>





