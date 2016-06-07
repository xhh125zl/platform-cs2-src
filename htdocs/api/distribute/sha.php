<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/compser_library/Protitle.php');
$Sha_Rate = NULL;
$enable = 0;
if (isset($dis_config) && !empty($dis_config['Sha_Rate'])) 
{
	$Sha_Rate = json_decode($dis_config['Sha_Rate'], true);
	$enable = $Sha_Rate['Shaenable'];
	unset($Sha_Rate['Shaenable']);
}

if (!empty($dis_config['Pro_Title_Level'])) 
{
	$Pro_Title_Level = json_decode($dis_config['Pro_Title_Level'], true);
}

if (!empty($_POST)) 
{
	if(empty($_POST['Applyfor_Name']) || empty($_POST['Applyfor_Mobile']))
	{
		exit('用户名和电话不能为空！');
	}
	$DATA = array(
		'Users_ID' => $UsersID,
		'User_ID' => $User_ID,
		'Applyfor_Name' => htmlspecialchars(trim($_POST['Applyfor_Name']), ENT_QUOTES),
		'Applyfor_Mobile' => htmlspecialchars(trim($_POST['Applyfor_Mobile']), ENT_QUOTES),
		'Order_Status' => 0,
		'Order_CreateTime' => time(),
		'Level_ID' => $rsAccount['Level_ID'],
		'Level_Name' => empty($dis_level[$rsAccount['Level_ID']]) ? '' : $dis_level[$rsAccount['Level_ID']]['Level_Name'],
		'Order_TotalPrice' => !empty($Sha_Rate['sha']['price']) ? $Sha_Rate['sha']['price'] : 0,
		'Owner_ID' => $User_ID,
	);

	$Flag = $DB->Add('sha_order', $DATA);
	if ($Flag) 
	{
		header('Location:/api/'.$UsersID.'/distribute/my_sha/'); exit();
	}
}

//查询当前分销商权限 未设置则不允许申请
$user_consue = Order::where(array('User_ID'=>$_SESSION[$UsersID.'User_ID'],'Order_Status'=>4))->sum('Order_TotalPrice');

$ProTitle = new ProTitle($UsersID, $User_ID);
$childs = $ProTitle->get_sons($dis_config['Dis_Level'],$User_ID);
$childs[] = $User_ID;

if(!empty($childs)){
	$Sales_Group = Order::where(array('Order_Status'=>4))->whereIn('Owner_ID',$childs)->sum('Order_TotalPrice');
}else{
	$Sales_Group = 0;
}

$MY_Distribute_Level = $rsAccount['Level_ID'];

$roleFlag = '';

  if (!empty($Sha_Rate)) 
  {
    foreach ($Sha_Rate as $k => $v) 
	{
		$v['Protitle'] = isset($v['Protitle']) ? $v['Protitle'] : 0;
		$v['Level'] = isset($v['Level']) ? $v['Level'] : 0;
		$v['Selfpro'] = isset($v['Selfpro']) ? $v['Selfpro'] : 0;
		$v['Teampro'] = isset($v['Teampro']) ? $v['Teampro'] : 0;
		
		/* if ($v['Protitle'] == 0 &&  $v['Level'] == 0 && $v['Selfpro'] == 0 && $v['Teampro'] == 0) 
		{
			$roleFlag = '';
			break;
		} */

		//判断分销商级别是否达到预设值的区域代理等级	
		if ($MY_Distribute_Level >= $v['Level'] && $rsAccount['Professional_Title'] >= $v['Protitle'] && $user_consue >= $v['Selfpro'] && $Sales_Group >= $v['Teampro']) 
		{
			$roleFlag = $k;
			break;
		}
	}

  }


$shaInfo = $DB->GetRs('sha_order', 'count(Order_ID) as num', ' where `Users_ID`="' .$UsersID. '" AND `User_ID`="' .$User_ID. '" AND `Order_Status`!=3');

if ($shaInfo['num'] > 0) 
{
	header('Location:/api/'.$UsersID.'/distribute/my_sha/'); exit();
}

$header_title = '申请成为股东';
$flagz = $flaga = $flagb = $flagc = $flagd = true;
require_once('header.php');
?>

<body>
<link href="/static/api/distribute/css/detaillist.css?t=<?php echo time();?>" rel="stylesheet">

<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">申请成为股东</h1>
</header>
<style type="text/css">
	.row { margin-top: 10px; }
	table { width: 97%; margin: 0 auto; border-color: #ddd; margin-bottom: 20px; }
	table tr td, table tr th.bodytable { height: 40px; line-height: 40px; text-align: center; }
	table tr th.bodytable { background: #eee; }
	tbody { border: 1px solid #ddd; background: #FFF; }
	h4 { width: 97%; margin: 10px  auto; }
	b { display: block; overflow: hidden; font-weight: normal; height: 20px; line-height: 20px; font-size: 12px; color: #888; margin-top: -10px; }
	td.l { text-align: left; font-size: 12px; }
	span.m { display: block; overflow: hidden; font-size: 12px; height: 30px; line-height: 30px; border-bottom: 1px dotted #ddd; text-indent: 5px; }
	.leftContent { width: 20%; float: left; display: block; overflow: hidden; font-weight: bold; text-align: center; }
	.rightContent { width: 78%; float: left; display: block; overflow: hidden; border-left: 1px dotted #ddd; }
	.cleftContent, .crightContent { height: 30px; line-height: 30px; border-top: 1px dotted #ddd; }
	.tdContent { display: block; overflow: hidden; }
	span.bold { color: #ef363e; font-weight: bold; }
	td.bold { font-weight: bold; }
	form { width: 97%; margin: 0 auto; }
	.list-group-item { position: relative; display: block; padding: 10px 15px; margin-bottom: -1px; background-color: #fff; border: 1px solid #ddd; }
	.submit-btn { width: 80%; background: #3396FE; color: #ffffff; }
	.red { border-color: red; color: #333; }
	span.hideMsg { display: none; }
	h1.tips { background: red; color: #FFF; width: %inherit; width: 97%; margin: 0 auto; font-size: 16px; text-align: center; padding: 8px 0; border-radius: 5px; font-weight: bold; -webkit-box-shadow: 0 0 10px #F29611; -moz-box-shadow: 0 0 10px #F29611; box-shadow: 0 0 10px #F29611; margin-top: 20px; }
	.show_detail_link { display: block; overflow: hidden; width: 97%; padding: 0; margin: 10px auto; }
	.show_detail_link  a { display: inline-block; overflow: hidden; float: left; width: 40%; text-align: center; padding: 10px 0; background: #FFF; border: 1px solid #ddd; }
	.show_detail_link  a.r { float: right; }
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('#btn-addcard').click(function(){
		var Applyfor_Name = $('input[name="Applyfor_Name"]').val();
		var Applyfor_Mobile = $('input[name="Applyfor_Mobile"]').val();
		if(Applyfor_Name == "" || Applyfor_Mobile == "") { global_obj.win_alert('用户名和电话不能为空！', function() {}); return false; }
		$('#area_post_box').submit();
	});
});
	
</script>
<div class="wrap">
	<div class="container">
		<?php if(!empty($roleFlag)): ?>
		<div class="row">
			<form id="area_post_box" method="post" action="">
				<li class="list-group-item">申请成为股东</li>
        		
		        <li class="list-group-item bank_card" style="display:block">
		            <label>姓&nbsp;&nbsp;名</label>&nbsp;&nbsp;<input type="text" name="Applyfor_Name" placeholder="请输入您的姓名">
		        </li> 
		          
		        <li class="list-group-item bank_card" style="display:block">
		          <label>电&nbsp;&nbsp;话</label>&nbsp;&nbsp;<input type="text" name="Applyfor_Mobile" placeholder="请输入您的联系电话">	
		        </li>

		        <li class="list-group-item text-center">
		             <a href="javascript:void(0)" id="btn-addcard" class="btn btn-default submit-btn">立即申请</a>
		        </li>
        	</form>
		</div>
		<?php else: ?>
		<h1 class="tips">你无权申请成为股东!</h1>
		<?php endif; ?>

		<div class="show_detail_link">
			<a href="/api/<?=$UsersID?>/distribute/my_sha_info/" class="">我的股东分红明细</a>
		</div>

		<?php if (!empty($Sha_Rate)) : ?>
		<div class="row">
	      <h4>申请成为股东需满足以下对应条件：</h4>
	      <table width="100%" border="1" cellspacing="0"> 
		    <tbody>
		    <tr> 
		        <th class="bodytable">申请条件描述</th> 
		    </tr> 
		    <tr> 
		        <td class="l">
		        	<div class="tdContent">		        		
						<div class="leftContent">申请条件</div>
		        		<div class="rightContent">
						<?php if(!empty($dis_level[$Sha_Rate['sha']['Level']]['Level_Name'])): ?>
		        			<span class="m">分销商等级：<?php echo $dis_level[$Sha_Rate['sha']['Level']]['Level_Name']; ?></span>
							<?php $flaga = false; ?>
							<?php endif; ?>
						<?php if(!empty($Sha_Rate['sha']['Protitle'])): ?>
		        			<span class="m">爵位等级：<?php echo $Pro_Title_Level[$Sha_Rate['sha']['Protitle']]['Name']; ?></span>
							<?php $flagb = false; ?>
							<?php endif; ?>
							<?php if(!empty($Sha_Rate['sha']['Selfpro'])): ?>
		        			<span class="m">个人消费额：<span class="bold">¥<?php echo $Sha_Rate['sha']['Selfpro']; ?></span></span>
							<?php $flagc = false; ?>
							<?php endif; ?>
							<?php if(!empty($Sha_Rate['sha']['Teampro'])): ?>		        	
		        			<span class="m">团队销售额：<span class="bold">¥<?php echo $Sha_Rate['sha']['Teampro']; ?></span></span>
							<?php $flagd = false; ?>
							<?php endif; ?>
							<?php $flagz = $flaga&&$flagb&&$flagc&&$flagd; ?>
							<?php if($flagz): ?>
							<span class="m">条件暂无</span>	
							<?php endif; ?>
		        		</div>
		        	</div>
		        	<div class="tdContent">
		        		<div class="leftContent cleftContent">所需金额</div>
		        		<div class="rightContent crightContent"><span class="m bold">¥<?php echo !empty($Sha_Rate['sha']['price']) ? $Sha_Rate['sha']['price'] : ''; ?></span></div>
		        	</div>
		        </td> 
		    </tr> 
					    
		  </tbody></table> 
        </div>
		<?php endif; ?>
    </div>
</div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>
