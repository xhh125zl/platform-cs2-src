<?php if(isset($_GET['cfgPay']) && $_GET['cfgPay']==1){?>
<?php

    require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
    require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/flow.php');

    if ($_POST) {
        $RunType = $_POST['RunType'];
        $day = intval($_POST['day']);
        $Time = $_POST['Time'];
        $Users_Id = isset($_SESSION["Users_ID"]) ? $_SESSION["Users_ID"] : '';
        $StartRunTime = "";
        if(!$Users_Id){
            echo "<script> alert(\"Session过期，请重新登录\");top.location.href = '/member/login.php'; </script>";
            exit;
        }
        if(!$day){
            $day =1;
        }
        if(empty($Time) || !$Time){
            $Time = date("H:i");
        }

        $data = array(
            'Users_ID' => $Users_Id,
            'StartRunTime' => $Time,
            'RunType' => $RunType,
            'Status' => 1,
            'LastRunTime' => strtotime(date("Y-m-d",time())),
            'day' =>$day
        );
        $sch = $DB->GetRs("users_schedule", "*", "WHERE Users_ID='{$Users_Id}'");
        if ($sch) {
            $DB->Set("users_schedule", $data, "WHERE Users_ID='{$Users_Id}'");
        } else {
            $DB->Add("users_schedule", $data);
        }
        echo "<script> alert(\"修改成功\");history.go(-1);</script>";
        exit;
    }
    ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
</head>

<body>
	<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
	<style type="text/css">
body, html {
	background: url(/static/member/images/main/main-bg.jpg) left top fixed
		no-repeat;
}
</style>
	<div id="iframe_page">
		<div class="iframe_content">
			<link href='/static/member/css/shop.css' rel='stylesheet'
				type='text/css' />

			<div class="r_nav">
				<ul>
					<li><a href="/member/shop/sales_record.php">销售记录</a></li>
					<li><a href="/member/shop/payment.php">付款单</a></li>
					<li class="cur"><a href="/member/shop/setting/config.php?cfgPay=1">自动结算配置</a></li>
				</ul>
			</div>
			<div id="payment" class="r_con_wrap">
				<link href='/static/js/plugin/operamasks/operamasks-ui.css'
					rel='stylesheet' type='text/css' />
				<script type='text/javascript'
					src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
				<script type='text/javascript'
					src='/static/js/plugin/daterangepicker/moment_min.js'></script>
				<link href='/static/js/plugin/daterangepicker/daterangepicker.css'
					rel='stylesheet' type='text/css' />
				<script type='text/javascript'
					src='/static/js/plugin/daterangepicker/daterangepicker.js'></script>
				<script language="javascript">$(document).ready(payment.payment_edit_init);</script>
				<form id="payment_form" class="r_con_form" method="post" action="/member/shop/setting/config.php?cfgPay=1">
					<?php $sch = $DB->GetRs("users_schedule", "*", "WHERE Users_ID='{$_SESSION['Users_ID']}'");
					       $type = 2;
					       if($sch){
					           $type = $sch['RunType'];
					           $time = $sch['StartRunTime'];
					           $day = $sch['day'];
					           $lastRunTime = $sch['LastRunTime'];
					          
					       }
					?>
					<div class="rows">
						<label>结算类型</label> <span class="input time"> <select
							name='RunType'>
								<option value="1" <?=$type==1?"selected":"" ?>>按周结算</option>
								<option value="2" <?=$type==2?"selected":"" ?>>按天结算</option>
								<option value="3" <?=$type==3?"selected":"" ?>>按月结算</option>
						</select>&nbsp; (若按天结算，请手动填写天数)<font class="fc_red">*</font></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>选择结算时间</label> <span class="input time"> <input name="Time"
							type="text" value="<?=isset($time)?$time:date('H:i:s') ?>" class="form_input"
							size="40" notnull /> <font class="fc_red">*</font> <span
							class="tips">需要结算的销售记录的时间段</span></span>
						<div class="clear"></div>
						<label>结算天数</label> <span class="input time"> <input name="day"
							type="text" value="<?php echo isset($day)?$day:2; ?>" class="form_input" size="40" notnull /> <font
							class="fc_red">*</font> <span class="tips">每隔N天进行结算</span></span>
					</div>
					<div class="rows">
						<label></label> <span class="input"> <input type="submit"
							class="btn_green" value="确定" name="submit_btn"></span>
						<div class="clear"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(function(){
		$("select[name='RunType']").change(function(){

			var RunType = $("select[name='RunType']").val();
			if(RunType==1){
				$("input[name='day']").val("7");
			}else if(RunType==3){
				$("input[name='day']").val("<?php echo date("t",time());?>");
			}
	    });

	});
	</script>
</body>
</html>
<?php die; } ?>

<?php
$DB->showErr = false;
if (empty($_SESSION["Users_Account"])) {
    header("location:/member/login.php");
}
$item = $DB->GetRs("users", "Users_Sms", "where Users_ID='" . $_SESSION["Users_ID"] . "'");
$rsConfig = $DB->GetRs("shop_config", "*", "where Users_ID='" . $_SESSION["Users_ID"] . "'");
$rsKeyword = $DB->GetRs("wechat_keyword_reply", "*", "where Users_ID='" . $_SESSION["Users_ID"] . "' and Reply_Table='shop' and Reply_TableID=0 and Reply_Display=0");
$json = $DB->GetRs("wechat_material", "*", "where Users_ID='" . $_SESSION["Users_ID"] . "' and Material_Table='shop' and Material_TableID=0 and Material_Display=0");
$rsMaterial = json_decode($json['Material_Json'], true);

$man_list = json_decode($rsConfig['Man'], true);
$integral_use_laws = json_decode($rsConfig['Integral_Use_Laws'], true);
// var_dump($rsConfig);

if ($_POST) {
    // 开始事务定义
    $flag = true;
    $msg = "";
    mysql_query("begin");
    $_POST['ShopAnnounce'] = str_replace('"', '&quot;', $_POST['ShopAnnounce']);
    $_POST['ShopAnnounce'] = str_replace("'", "&quot;", $_POST['ShopAnnounce']);
    $_POST['ShopAnnounce'] = str_replace('>', '&gt;', $_POST['ShopAnnounce']);
    $_POST['ShopAnnounce'] = str_replace('<', '&lt;', $_POST['ShopAnnounce']);
    $Data = array(
        "ShopName" => $_POST["ShopName"],
        "ShopAnnounce" => $_POST["ShopAnnounce"],
        "ShopLogo" => $_POST["Logo"],
        "NeedShipping" => 1,
        "SendSms" => isset($_POST["SendSms"]) ? $_POST["SendSms"] : 0,
        "MobilePhone" => $_POST["MobilePhone"],
        "CallEnable" => isset($_POST["CallEnable"]) ? $_POST["CallEnable"] : 0,
        "CallPhoneNumber" => $_POST["CallPhoneNumber"],
        "CheckOrder" => isset($_POST["CheckOrder"]) ? $_POST["CheckOrder"] : 0,
        "Confirm_Time" => isset($_POST["Confirm_Time"]) ? $_POST["Confirm_Time"] * 86400 : 0,
        "Commit_Check" => isset($_POST["CommitCheck"]) ? $_POST["CommitCheck"] : 0,
        "Substribe" => isset($_POST["Substribe"]) ? $_POST["Substribe"] : 0,
        "SubstribeUrl" => trim($_POST["SubstribeUrl"]),
        "Distribute_Share" => isset($_POST["DistributeShare"]) ? $_POST["DistributeShare"] : 0,
        "Distribute_ShareScore" => $_POST["DistributeShareScore"],
        "Member_Share" => isset($_POST["MemberShare"]) ? $_POST["MemberShare"] : 0,
        "Member_ShareScore" => $_POST["MemberShareScore"],
        "ShareLogo" => $_POST["ShareLogo"],
        "ShareIntro" => $_POST["ShareIntro"]
    )
    ;
    
    $Set = $DB->Set("shop_config", $Data, "where Users_ID='" . $_SESSION["Users_ID"] . "'");
    
    $flag = $flag && $Set;
    $Data = array(
        "Reply_Keywords" => $_POST["Keywords"],
        "Reply_PatternMethod" => isset($_POST["PatternMethod"]) ? $_POST["PatternMethod"] : 0
    );
    $Set = $DB->Set("wechat_keyword_reply", $Data, "where Users_ID='" . $_SESSION["Users_ID"] . "' and Reply_Table='shop' and Reply_TableID=0 and Reply_Display=0");
    $flag = $flag && $Set;
    $Material = array(
        "Title" => $_POST["Title"],
        "ImgPath" => $_POST["ImgPath"],
        "TextContents" => "",
        "Url" => "/api/" . $_SESSION["Users_ID"] . "/shop/"
    );
    $Data = array(
        "Material_Json" => json_encode($Material, JSON_UNESCAPED_UNICODE)
    );
    
    $Set = $DB->Set("wechat_material", $Data, "where Users_ID='" . $_SESSION["Users_ID"] . "' and Material_Table='shop' and Material_TableID=0 and Material_Display=0");
    $flag = $flag && $Set;
    if ($flag) {
        mysql_query("commit");
        echo '<script language="javascript">alert("设置成功");window.location="config.php";</script>';
    } else {
        mysql_query("roolback");
        echo '<script language="javascript">alert("设置失败");history.back();</script>';
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet'
	type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/shop.js'></script>
<link rel="stylesheet"
	href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript'
	src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript'
	src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=web_article',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	
	K('#LogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#Logo').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#Logo').val(url);
					K('#LogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#ShareLogoUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ShareLogo').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ShareLogo').val(url);
					K('#ShareLogoDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#ReplyImgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ReplyImgPath').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ReplyImgPath').val(url);
					K('#ReplyImgDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
<style type="text/css">
#config_form img {
	width: 100px;
	height: 100px;
}
</style>
</head>

<body>
	<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

	<div id="iframe_page">
		<div class="iframe_content">
			<link href='/static/member/css/shop.css' rel='stylesheet'
				type='text/css' />

			<div class="r_nav">
				<ul>
					<li class="cur"><a href="config.php">基本设置</a></li>
					<li class=""><a href="skin.php">风格设置</a></li>
					<li class=""><a href="home.php">首页设置</a></li>
					<li><a href="menu_config.php">菜单配置</a></li>
				</ul>
			</div>
			<link href='/static/js/plugin/operamasks/operamasks-ui.css'
				rel='stylesheet' type='text/css' />
			<script type='text/javascript'
				src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script>
			<script language="javascript">$(document).ready(function(){
		
		global_obj.config_form_init();
		shop_obj.confirm_form_init();
		
	});</script>
			<div class="r_con_config r_con_wrap">
				<form id="config_form" action="config.php" method="post">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="50%" valign="top"><h1>
									<span class="fc_red">*</span> <strong>微商城名称</strong>
								</h1> <input type="text" class="input" name="ShopName"
								value="<?php echo $rsConfig["ShopName"] ?>" maxlength="30"
								notnull /></td>
							<td width="50%" valign="top"></td>
						</tr>
						<tr>
							<td width="50%" valign="top"><h1>
									<strong>订单确认</strong><span class="tips">（关闭后下订单可直接付款，无需经过卖家确认）</span>
								</h1>
								<div class="input">
									<input type="checkbox" name="CheckOrder" value="1"
										<?php echo empty($rsConfig["CheckOrder"])?"":" checked"; ?> />
									<span class="tips">关闭(只针对在线付款有效)</span>
								</div></td>
							<td width="50%" valign="top"><h1>
									<strong>评论审核</strong><span class="tips">（关闭后客户评论可不经过审核直接显示在前台页面）</span>
								</h1>
								<div class="input">
									<input type="checkbox" name="CommitCheck" value="1"
										<?php echo empty($rsConfig["Commit_Check"])?"":" checked"; ?> />
									<span class="tips">关闭</span>
								</div></td>
						</tr>
						<tr>
							<td width="50%" valign="top">
								<h1>
									<strong>一键拨号</strong> <input type="checkbox" name="CallEnable"
										value="1"
										<?php echo empty($rsConfig["CallEnable"])?"":" checked"; ?> />
									<span class="tips">启用</span>
								</h1> <input type="text" class="input" name="CallPhoneNumber"
								value="<?php echo empty($rsConfig["CallPhoneNumber"])?"":$rsConfig["CallPhoneNumber"]; ?>"
								maxlength="20" />
							</td>
							<td width="50%" valign="top">
								<h1>
									<span class="fc_red">*</span> <strong>订单自动确认收货时间(单位是天)</strong>
								</h1> <input type="text" class="input" name="Confirm_Time"
								value="<?php echo $rsConfig["Confirm_Time"]/86400 ?>" size="10"
								notnull />
							</td>
						</tr>

						<tr>
							<td width="50%" valign="top">
								<h1>
									<strong>订单手机短信通知</strong> <input type="checkbox" name="SendSms"
										value="1"
										<?php echo empty($rsConfig["SendSms"])?"":" checked"; ?> /> <span
										class="tips">启用（填接收短信的手机号）</span>
								</h1> <input type="text" class="input" name="MobilePhone"
								style="width: 120px"
								value="<?php echo $rsConfig["MobilePhone"] ?>" maxlength="11" /><span
								class="tips"> 短信剩余 <font style="color: red"><?php echo $item["Users_Sms"];?></font>
									条&nbsp;&nbsp;<a href="/member/sms/sms_add.php"
									style="color: #F60; text-decoration: underline">点击购买</a></span>
							</td>
							<td width="50%" valign="top">
								<h1>
									<strong>商城关注提醒</strong> <input type="checkbox" name="Substribe"
										value="1"
										<?php echo empty($rsConfig["Substribe"])?"":" checked"; ?> />
									<span class="tips">启用(若用户未关注公众号，则提示用户关注)</span>
								</h1> <input type="text" class="input" name="SubstribeUrl"
								value="<?php echo empty($rsConfig["SubstribeUrl"])?"":$rsConfig["SubstribeUrl"]; ?>"
								placeholder="请填写链接地址" />
							</td>
						</tr>
						<tr style="display: none">
							<td width="50%" valign="top">
								<h1>
									<strong>非分销商分享获取积分</strong> <input type="checkbox"
										name="MemberShare" value="1"
										<?php echo empty($rsConfig["Member_Share"])?"":" checked"; ?> />
									<span class="tips">开启</span>
								</h1> <input type="text" class="input" name="MemberShareScore"
								style="width: 50px"
								value="<?php echo $rsConfig["Member_ShareScore"] ?>" />
							</td>
							<td width="50%" valign="top">
								<h1>
									<strong>分销商分享获取积分</strong> <input type="checkbox"
										name="DistributeShare" value="1"
										<?php echo empty($rsConfig["Distribute_Share"])?"":" checked"; ?> />
									<span class="tips">开启</span>
								</h1> <input type="text" class="input"
								name="DistributeShareScore" style="width: 50px"
								value="<?php echo $rsConfig["Distribute_ShareScore"] ?>" />

							</td>
						</tr>
						<tr>
							<td width="50%" valign="top">
								<h1>
									<strong>logo</strong>
								</h1>
								<div id="card_style">
									<div class="file">
										<span class="fc_red">（请上传png透明格式）</span><br /> <span
											class="tips">&nbsp;&nbsp;尺寸建议：100*100px</span><br />
										<br /> <input name="LogoUpload" id="LogoUpload" type="button"
											style="width: 80px;" value="上传图片" /><br />
										<br />
										<div class="img" id="LogoDetail">
                                <?php echo $rsConfig && $rsConfig['ShopLogo']<>'' ? '<img src="'.$rsConfig['ShopLogo'].'" />' : ''?>
								
                                </div>
										<input type="hidden" id="Logo" name="Logo"
											value="<?php echo $rsConfig && $rsConfig['ShopLogo']<>'' ? $rsConfig['ShopLogo'] : ''?>" />
									</div>
									<div class="clear"></div>
								</div>
							</td>
							<td width="50%" valign="top">
								<h1>
									<strong>自定义分享图片</strong>
								</h1>
								<div id="card_style">
									<div class="file">
										<span class="tips">&nbsp;&nbsp;尺寸建议：100*100px</span><br />
										<br /> <input name="ShareLogoUpload" id="ShareLogoUpload"
											type="button" style="width: 80px;" value="上传图片" /><br />
										<br />
										<div class="img" id="ShareLogoDetail">
                                <?php echo $rsConfig && $rsConfig['ShareLogo']<>'' ? '<img src="'.$rsConfig['ShareLogo'].'" />' : ''?>
								
                                </div>
										<input type="hidden" id="ShareLogo" name="ShareLogo"
											value="<?php echo $rsConfig && $rsConfig['ShareLogo']<>'' ? $rsConfig['ShareLogo'] : ''?>" />
									</div>
									<div class="clear"></div>
								</div>
							</td>
						</tr>

						<tr>
							<td width="50%" valign="top">
								<h1>
									<strong>商城公告</strong>
								</h1> <textarea name="ShopAnnounce"><?php echo $rsConfig["ShopAnnounce"] ?></textarea>
							</td>
							<td width="50%" valign="top">
								<h1>
									<strong>自定义分享语</strong>
								</h1> <textarea name="ShareIntro"><?php echo $rsConfig["ShareIntro"] ?></textarea>
							</td>
						</tr>

					</table>
					<table align="center" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td><h1>
									<strong>触发信息设置</strong>
								</h1>
								<div class="reply_msg">

									<div class="m_left">
										<span class="fc_red">*</span> 触发关键词<span class="tips_key">
										</span><br /> <input type="text" class="input" name="Keywords"
											value="<?php echo $rsKeyword["Reply_Keywords"] ?>"
											maxlength="100" notnull /> <br /> <br /> <br /> <span
											class="fc_red">*</span> 匹配模式<br />
										<div class="input">
											<input type="radio" name="PatternMethod" value="0"
												<?php echo empty($rsKeyword["Reply_PatternMethod"])?" checked":""; ?> />
											精确匹配<span class="tips">（输入的文字和此关键词一样才触发）</span>
										</div>
										<div class="input">
											<input type="radio" name="PatternMethod" value="1"
												<?php echo $rsKeyword["Reply_PatternMethod"]==1?" checked":""; ?> />
											模糊匹配<span class="tips">（输入的文字包含此关键词就触发）</span>
										</div>
										<br /> <br /> <span class="fc_red">*</span> 图文消息标题<br /> <input
											type="text" class="input" name="Title"
											value="<?php echo $rsMaterial["Title"] ?>" maxlength="100"
											notnull />
									</div>
									<div class="m_right">
										<span class="fc_red">*</span> 图文消息封面<span class="tips">（大图尺寸建议：640*360px）</span><br />
										<div class="file">
											<input name="ReplyImgUpload" id="ReplyImgUpload"
												type="button" style="width: 80px;" value="上传图片" />
										</div>
										<br />
										<div class="img" id="ReplyImgDetail">
                       <?php echo $rsMaterial["ImgPath"] ? '<img src="'.$rsMaterial["ImgPath"].'" />' : '';?>
                    </div>
									</div>
									<div class="clear"></div>
								</div> <input type="hidden" id="ReplyImgPath" name="ImgPath"
								value="<?php echo $rsMaterial["ImgPath"] ?>" /></td>
						</tr>
					</table>
					<div class="submit">
						<input type="submit" name="submit_button" value="提交保存" />
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>