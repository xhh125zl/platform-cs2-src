<?php
header("content-Type: text/html; charset=UTF-8");
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once(CMS_ROOT.'/include/library/pay_order.class.php');
require_once(CMS_ROOT.'/include/helper/tools.php');
require_once(CMS_ROOT.'/include/support/yiji_helper.php');
require_once(CMS_ROOT.'/include/api/const.php');
require_once(CMS_ROOT.'/include/api/pay.class.php');
require_once(CMS_ROOT.'/include/api/users.class.php');
$action = "first";
$UserID = "";
// 判断当前是PC还是移动端
$termalType = 'mobile';
$Biz_Account = isset($_SESSION['Biz_Account']) && $_SESSION['Biz_Account']?$_SESSION['Biz_Account']:'';
$rsBindUser = "";
$rsPay = "";
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
}
$baseInfo = [];
if(!empty($rsBindUser)){
    $payAccountConfig = $rsBindUser['payAccountConfig'];
    if($payAccountConfig){
        $baseInfo = json_decode($payAccountConfig, true);
    }
}
if(empty($baseInfo)){
    header("Location:/pay/yijipay/reg.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>开通易极付支付账户</title>
</head>
<link href="/static/api/account/sub.css" type="text/css" rel="stylesheet">
<link href="/static/api/account/font-awesome.min.css" type="text/css" rel="stylesheet">
<link href="/static/user/css/layer.css" type="text/css" rel="stylesheet">
<link href="/static/css/select2.css" rel="stylesheet"/>
<link href="/static/pay/yiji/reg.css" rel="stylesheet"/>
<script type='text/javascript' src='/static/js/jquery-1.11.1.min.js'></script>
<script type="text/javascript" src="/static/user/js/jquery.uploadView.js"></script>
<script type="text/javascript" src="/static/user/js/layer.js"></script>
<script type='text/javascript' src="/static/js/select2.js"></script>
<script type="text/javascript" src="/static/js/locationnew.js"></script>
<script type="text/javascript" src="/static/js/area.js"></script>
<style>
img {
    width:120px;height:100px;
}
</style>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>易极付状态
    </div>
    <div class="clear"></div>
    <div class="list_table">
    	<table width="100%" class="table_x1">
    		<?php if(isset($baseInfo['OrderNo'])){ ?>
            <tr>
                <th>普通会员请求流水号（OrderNo）：</th>
                <td><?=$baseInfo['OrderNo'] ?></td> 
            </tr>
            <?php } ?>
            <?php if(isset($baseInfo['payAccountOrderNo'])){ ?>
            <tr>
                <th>支付会员请求流水号（OrderNo）：</th>
                <td><?=$baseInfo['payAccountOrderNo'] ?></td> 
            </tr>
            <?php } ?>
            <?php if(isset($baseInfo['UserYijiId'])){ ?>
            <tr>
                <th>易极付会员ID：</th>
                <td><?=$baseInfo['UserYijiId'] ?></td> 
            </tr>
            <?php } ?>
            <tr>
                <th>设备类型：</th>
                <td><?=$termalType=='mobile'?'移动设备':'PC设备' ?></td> 
            </tr> 
            <tr>
                <th>注册类型：</th>
                <td>
                	 <?=isset($baseInfo['registerUserType']) && $baseInfo['registerUserType']=="PERSONAL" || !isset($baseInfo['registerUserType'])?"个人用户注册":"" ?>
                     <?=isset($baseInfo['registerUserType']) && $baseInfo['registerUserType']=="ENTERPRISE"?"企业用户注册":"" ?>
                     <?=isset($baseInfo['registerUserType']) && $baseInfo['registerUserType']=="INDIVIDUAL"?"个体户注册":"" ?>
                    </select>
                </td> 
            </tr>
            <?php if(isset($baseInfo['userName'])){ ?>
            <tr> 
                <th>用户名：</th> 
                <td><?=isset($baseInfo['userName'])?$baseInfo['userName']:"" ?></td> 
            </tr>
            <?php } ?>
            <?php if(isset($baseInfo['email'])){ ?>
            <tr> 
                <th>邮箱信息：</th> 
                <td><?=isset($baseInfo['email'])?$baseInfo['email']:"" ?></td> 
            </tr>
            <?php } ?>
            <tr> 
                <th>手机号：</th> 
                <td><?=isset($baseInfo['mobile'])?$baseInfo['mobile']:"" ?></td> 
            </tr>
            <?php if(isset($baseInfo['registerUserType']) && $baseInfo['registerUserType']!="PERSONAL"){ ?>
            <tr class="nopersonal"> 
                <th>营业执照类型：</th> 
                <td>
                	<?=isset($baseInfo['enterpriseLicenseType']) && $baseInfo['enterpriseLicenseType']=="G" || !isset($baseInfo['enterpriseLicenseType'])?"普通营业执照":"" ?>
                    <?=isset($baseInfo['enterpriseLicenseType']) && $baseInfo['enterpriseLicenseType']=="S"?"三合一营业执照":"" ?>
                </td> 
            </tr>
            <tr class="nopersonal"> 
                <th>企业名称：</th> 
                <td><?=isset($baseInfo['enterpriseName'])?$baseInfo['enterpriseName']:'' ?></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>营业执照号码：</th> 
                <td><?=isset($baseInfo['licenceNo'])?$baseInfo['licenceNo']:'' ?></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>单位所在省：</th> 
                <td>
                	<?=isset($baseInfo['provinceName'])?$baseInfo['provinceName']:'' ?>
                </td> 
            </tr>
            <tr class="nopersonal"> 
                <th>单位所在市：</th> 
                <td>
                	<?=isset($baseInfo['cityName'])?$baseInfo['cityName']:'' ?>
                </td> 
            </tr>
            <tr class="nopersonal"> 
                <th>单位所在地址：</th> 
                <td><?=isset($baseInfo['address'])?$baseInfo['address']:'' ?></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>营业期限：</th> 
                <td><?=isset($baseInfo['businessTerm'])?$baseInfo['businessTerm']:'' ?></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>经营范围：</th> 
                <td><?=isset($baseInfo['businessScope'])?$baseInfo['businessScope']:'' ?></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>组织机构代码：</th> 
                <td><?=isset($baseInfo['organizationCode'])?$baseInfo['organizationCode']:'' ?></td> 
            </tr>
            <tr class="nopersonal"> 
                <th>控股人类型：</th> 
                <td>
                	<?=isset($baseInfo['holdingType']) && $baseInfo['holdingType']=="HOLDING_PERSON" || !isset($baseInfo['holdingType'])?"个人":"" ?>
                    <?=isset($baseInfo['holdingType']) && $baseInfo['holdingType']=="HOLDING_COM"?"企业":"" ?>
                </td>
            </tr>
            <?php }?>
            <tr> 
                <th>职业：</th> 
                <td>
                	<?=isset($baseInfo['profession']) && $baseInfo['profession']=="COMPUTER" || !isset($baseInfo['profession'])?"计算机/互联网/通信/电子":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="SALE"?"销售/零售/采购等业务员":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="MEAL"?"餐饮/旅游/美容/家政":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="IT"?"化工/机械/设计等技术人员":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="ACCOUNTING"?"会计/金融/银行/保险":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="OPERATION"?"生产/运营/采购/物流":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="LIFE"?"生物/制药/医疗/护理":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="ADVERT"?"广告/市场/媒体/艺术":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="ARCHITECT"?"建筑/房地产":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="HUMAN"?"人事/行政/高级管理":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="TRANSLATE"?"律师/公务员/教育/翻译":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="GRAZIERY"?"农/林/牧/渔业":"" ?>
                    <?=isset($baseInfo['profession']) && $baseInfo['profession']=="STAFF"?"职员":"" ?>
					<?=isset($baseInfo['profession']) && $baseInfo['profession']=="OTHERS"?"自由职业":"" ?>
                </td>
            </tr>
            <?php if(isset($baseInfo['image_licensePhotoPath'])){  ?>
        	<tr> 
                <th>营业执照：</th> 
                <td>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_licensePhotoPath'])?$baseInfo['image_licensePhotoPath']:"" ?>" /></div>
                    </div>
                </div>
                </td> 
            </tr>
            <?php } ?>
            <?php if(isset($baseInfo['image_organizationCodePath']) || isset($baseInfo['image_taxCertPath']) || isset($baseInfo['image_openLicensePath']) || isset($baseInfo['image_attorneyPath'])){  ?>
            <tr> 
                <th>其他证件照：</th> 
                <td>
                <?php if(isset($baseInfo['image_organizationCodePath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_organizationCodePath'])?$baseInfo['image_organizationCodePath']:"" ?>" /></div>
                    </div>
                </div>
                <?php } ?>
                <?php if(isset($baseInfo['image_taxCertPath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
          				<div class="photoview"><img src="<?=isset($baseInfo['image_taxCertPath'])?$baseInfo['image_taxCertPath']:"" ?>" /></div>     
                    </div>
                </div>
                <?php } ?>
                <?php if(isset($baseInfo['image_openLicensePath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
          				<div class="photoview"><img src="<?=isset($baseInfo['image_openLicensePath'])?$baseInfo['image_openLicensePath']:"" ?>" /></div>          
                    </div>
                </div>
                <?php } ?>
                <?php if(isset($baseInfo['image_attorneyPath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_attorneyPath'])?$baseInfo['image_attorneyPath']:"" ?>" /></div> 
                    </div>
                </div>
                <?php } ?>
                </td> 
            </tr>
            <?php } ?>
            <?php if(isset($baseInfo['image_specialBusinessFirst']) || isset($baseInfo['image_specialBusinessSecond'])){  ?>
            <tr> 
                <th>特许经营许可证：</th> 
                <td>
                <?php if(isset($baseInfo['image_specialBusinessFirst'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_specialBusinessFirst'])?$baseInfo['image_specialBusinessFirst']:"" ?>" /></div> 
                    </div>
                </div>
                <?php } ?>
                <?php if(isset($baseInfo['image_specialBusinessSecond'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_specialBusinessSecond'])?$baseInfo['image_specialBusinessSecond']:"" ?>" /></div> 
                    </div>
                </div>
                <?php } ?>
                </td> 
            </tr>
            <?php } ?>
            <?php if(isset($baseInfo['image_legalCertFrontPath']) && isset($baseInfo['image_legalCertBackPath'])){  ?>
            <tr> 
                <th>法人身份证证件照：</th> 
                <td>
                <?php if(isset($baseInfo['image_legalCertFrontPath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_legalCertFrontPath'])?$baseInfo['image_legalCertFrontPath']:"" ?>" /></div> 
                    </div>
                </div>
                <?php } ?>
                <?php if(isset($baseInfo['image_legalCertBackPath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_legalCertBackPath'])?$baseInfo['image_legalCertBackPath']:"" ?>" /></div> 
                    </div>
                </div>
                <?php } ?>
                </td> 
            </tr>
            <?php } ?>
            <?php if(isset($baseInfo['image_agentCertFrontPath']) && isset($baseInfo['image_agentCertBackPath'])){  ?>
            <tr> 
                <th>代理人身份证：</th> 
                <td>
                <?php if(isset($baseInfo['image_agentCertFrontPath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_agentCertFrontPath'])?$baseInfo['image_agentCertFrontPath']:"" ?>" /></div> 
                    </div>
                </div>
                <?php } ?>
                <?php if(isset($baseInfo['image_agentCertBackPath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_agentCertBackPath'])?$baseInfo['image_agentCertBackPath']:"" ?>" /></div>  
                    </div>
                </div>
                <?php } ?>
                </td> 
            </tr>
            <?php } ?>
            <?php if(isset($baseInfo['image_personCertFrontPath']) && isset($baseInfo['image_personCertBackPath'])){  ?>
            <tr> 
                <th>个人身份证件照：</th> 
                <td>
                <?php if(isset($baseInfo['image_personCertFrontPath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_personCertFrontPath'])?$baseInfo['image_personCertFrontPath']:"" ?>" /></div> 
                    </div>
                </div>
                <?php } ?>
                <?php if(isset($baseInfo['image_personCertBackPath'])){  ?>
                <div class="js_uploadBox">
                    <div class="js_showBox">
                    	<div class="photoview"><img src="<?=isset($baseInfo['image_personCertBackPath'])?$baseInfo['image_personCertBackPath']:"" ?>" /></div> 
                    </div>
                </div>
                <?php } ?>
                </td> 
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>