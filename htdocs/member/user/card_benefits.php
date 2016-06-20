<?php 
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}
if(isset($_GET["action"]))
{
	if($_GET["action"]=="del")
	{
		$Flag=$DB->Del("user_card_benefits","Users_ID='".$_SESSION["Users_ID"]."' and Benefits_ID=".$_GET["BenefitsID"]);
		if($Flag)
		{
			echo '<script language="javascript">alert("删除成功");window.location="'.$_SERVER['HTTP_REFERER'].'";</script>';
		}else
		{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
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
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
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
    <script type='text/javascript' src='/static/member/js/user.js'></script>
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
      </ul>
    </div>
    <div id="card_benefits" class="r_con_wrap">
      <div class="control_btn"><a href="card_benefits_add.php" class="btn_green btn_w_120">添加内容</a></div>
      <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%"><strong>序号</strong></td>
            <td width="30%"><strong>内容标题</strong></td>
            <td width="15%"><strong>会员等级</strong></td>
            <td width="20%"><strong>显示时间</strong></td>
            <td width="15%"><strong>显示状态</strong></td>
            <td width="10%" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("user_card_benefits","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Benefits_ID desc",$pageSize=10);
		  $i=1;
		  while($rsBenefits=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsBenefits["Benefits_Title"] ?></td>
            <td><?php echo $UserLevel[$rsBenefits["Benefits_UserLevel"]]["Name"] ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsBenefits["Benefits_StartTime"])."<br>~<br>".date("Y-m-d H:i:s",$rsBenefits["Benefits_EndTime"]) ?></td>
            <td nowrap="nowrap" class="status"><?php if($rsBenefits["Benefits_StartTime"]>time()){
				echo '未显示';
			}elseif($rsBenefits["Benefits_EndTime"]<time()){
				echo '已过期';
			}else{
				echo '正常';
			}?></td>
            <td nowrap="nowrap" class="last"><a href="card_benefits_edit.php?BenefitsID=<?php echo $rsBenefits["Benefits_ID"] ?>"><img src="/static/member/images/ico/mod.gif" /></a> <a href="card_benefits.php?action=del&BenefitsID=<?php echo $rsBenefits["Benefits_ID"] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" /></a></td>
          </tr>
          <?php $i++;
		  }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>