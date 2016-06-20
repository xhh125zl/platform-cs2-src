<?php
require_once('global.php');

$posterity = $accountObj->getPosterity($rsConfig['Dis_Level']);

if ($rsConfig['Distribute_Customize'] == 0) 
{
	$show_name = $rsUser['User_NickName'];
	$show_logo = !empty($rsUser['User_HeadImg']) ? $rsUser['User_HeadImg'] : '/static/api/images/user/face.jpg';
} else {
	$show_name = !empty($rsAccount['Shop_Name']) ? $rsAccount['Shop_Name'] : '暂无';
	$show_logo = !empty($rsAccount['Shop_Logo']) ? $rsAccount['Shop_Logo'] : '/static/api/images/user/face.jpg';
}

$total_sales = round_pad_zero(get_my_leiji_sales($UsersID,$User_ID,$posterity),2);
$total_income = round_pad_zero(get_my_leiji_income($UsersID,$User_ID),2);

$levelIds = $accountObj->getAncestorIds($rsAccount['Level_ID']);
$list = array();
if (is_array($levelIds) && count($levelIds) > 0) 
{
	if (count($levelIds) > 3) { $levelIds = array_slice($levelIds, -3); }
    //获取上线会员姓名
    $q = $DB->query('SELECT a.`User_ID`, a.`User_NickName`, a.`User_Level`,a.User_HeadImg, a.`User_Mobile`, b.`Dis_Path`, b.`Level_ID` FROM `user` AS a LEFT JOIN `distribute_account` AS b ON a.User_ID = b.User_ID WHERE a.User_ID IN('.implode(',', $levelIds).')');
    
    while ($row = $DB->fetch_assoc($q)) 
    {
      $list[$row['User_ID']]['User_ID'] = $row['User_ID'];
      $list[$row['User_ID']]['User_NickName'] = $row['User_NickName'];
      $list[$row['User_ID']]['User_Level'] = $row['User_Level'];
      $list[$row['User_ID']]['User_Mobile'] = $row['User_Mobile'];
      $list[$row['User_ID']]['Dis_Path'] = $row['Dis_Path'];
      $list[$row['User_ID']]['Level_ID'] = $row['Level_ID'];
	  $list[$row['User_ID']]['User_HeadImg'] = $row['User_HeadImg'];
    }

    $list = array_reverse($list);
}

$recommend = array(1 => '直接推荐人', 2 => '二级推荐人', 3 => '三级推荐人');

if (!empty($rsConfig['Index_Professional_Json'])){
	$Index_Professional_Json = json_decode($rsConfig['Index_Professional_Json'], TRUE);
	$recommend = $Index_Professional_Json['childtuijian'];
}

?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title></title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/css/distribute_center.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>
	<script src="/static/api/distribute/js/distribute.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			distribute_obj.init();
		});
    </script>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style type="text/css">
	.recommend-box {
		margin: 10px 0px;
		background: #FFF;
		display: block;
		overflow: hidden;
	}
	.recommend-line {
		border: 1px solid #e1e1e1;
		display: block;
		overflow: hidden;
		border-radius: 3px;
		padding: 8px 10px;
	}
	.profile-box {
		width: 25%;
		float: left;
	}
	.profile-box img {
		border-radius: 50%;
		height: 80px;
		width: 80px;
		margin-left: 0px;
		margin-top: 10px;
	}
	.detail-box {
		width: 75%;
		float: right;
		text-align: left;
		padding: 0;
		margin: 0;
		height: 90px;
	}
	.detail-box ul {
		margin: 3px 0px 0px;
		padding: 0;
	}
	.detail-box ul li {
		padding-left:5px;
		line-height: 20px;
		height: 20px;
	}
</style>
</head>

<body>
<?php if ($rsAccount['Is_Audit'] == 1): ?>
<div class="wrap">
<?php if ($rsAccount['status']): ?>
	<div class="container">

		<div class="row">
		    <div class="distribute_header">
				<div id="header_cushion">

					<div id="account_info" style="width:100%">
						<div class="pull-left" style="width:25%"><img id="hd_image" src="<?php echo $rsUser['User_HeadImg'] ? $rsUser['User_HeadImg'] : '/static/api/images/user/face.jpg'?>"/></div>

					    <div class="pull-right" style="width:75%;">
					    	<ul id="txt" style="padding-left:0px;">
					          <li>昵称：<?php echo $show_name; ?>　　ID：<?php echo $rsUser['User_ID']; ?></li>
					          <li style="padding-right:3px;">分销级别：<?php echo $dis_level[$rsAccount['Level_ID']]['Level_Name']; ?> 【<a style="color:#FFF;" href="<?=distribute_url('upgrade/')?>">升级</a>】</li>
					          <li style="padding-right:3px;">
					            <?php if(strlen($rsUser['User_Mobile'])==0): ?> 
					               <a style="color:#4878C6;text-decoration:underline;line-height:20px;" href="<?=distribute_url('bind_mobile/')?>">&nbsp;【绑定手机】</a>
					            <?php else:?>
					               手机：<?php echo $rsUser['User_Mobile'];?>　　<a style="color:#4878C6;line-height:20px;" href="<?=distribute_url('change_bind/')?>">&nbsp;【更改手机】</a>
					            <?php endif;?>
					          </li>
					        </ul>
					    </div>
						<div class="clearfix"></div>
					</div>
				</div>
			 
				<div id="account_sum">	
					<a href="javascript:void(0)"><span><?=$total_sales?></span><br>累计销售额</a>
					<a href="javascript:void(0)"><span><?=$total_income?></span><br>累计佣金</a>
					<div class="clearfix"></div>
				</div>
		    </div>
		</div>

	</div>
	<?php if (!empty($list)): ?>
	<?php foreach ($list as $k => $v) { $k++; ?>
	<div class="recommend-box">
		<div class="recommend-line">
			<div class="profile-box"><img id="hd_image" src="<?php echo $v['User_HeadImg'] ? $v['User_HeadImg'] : '/static/api/images/user/face.jpg'?>"/></div>
			<div class="detail-box">
				<ul>
					<li>昵称：<?php echo $v['User_NickName']; ?>　　　　ID：<?php echo $v['User_ID']; ?></li>
					<li>分销级别：<?php echo $dis_level[$v['Level_ID']]['Level_Name']; ?></li>
					<li><span class="red"><?php if (in_array($k, array_keys($recommend))) : ?><?php echo $recommend[$k]; ?><?php endif; ?></span></li>
					<li>手机：<?php echo $v['User_Mobile']; ?></li>
				</ul>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php endif; ?>


</div>
<div style="height:15px; width:100%; clear:both"></div>
   
<?php else: ?>
    <p>您的分销账号已被禁用</p>
    <a href="<?=$shop_url?>">返回</a>
<?php endif;?>
<?php else: ?>
	<div>
    	<div id="desc" class="col-xs-10">
			<p style="font-size:18px;color:red;">
				 <br/>
			<br/>
   				您的分销申请正在审核中,<br/>
                请耐心等待...
			</p>
		</div>
    </div>
<?php endif;?>

<?php require_once '../shop/skin/distribute_footer.php';?>

</body>
</html>