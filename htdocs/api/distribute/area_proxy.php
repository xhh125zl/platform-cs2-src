<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/compser_library/Protitle.php');
$Agent_Rate = array();
if (!empty($dis_config['Agent_Rate'])) 
{
	$Agent_Rate = json_decode($dis_config['Agent_Rate'], true);
	$Agentenable = isset($Agent_Rate['Agentenable']) ? $Agent_Rate['Agentenable'] : 0;
	unset($Agent_Rate['Agentenable']);
	unset($Agent_Rate['Nameshow']);
}

if (!empty($dis_config['Pro_Title_Level'])) 
{
	$Pro_Title_Level = json_decode($dis_config['Pro_Title_Level'], true);
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
$areaStepFlag = '';
foreach ($Agent_Rate as $k => $v) 
{
	$vProtitle = isset($v['Protitle']) ? $v['Protitle'] : 0;
	$vLevel = isset($v['Level']) ? $v['Level'] : 0;
	$vSelfpro = isset($v['Selfpro']) ? $v['Selfpro'] : 0;
	$vTeampro = isset($v['Teampro']) ? $v['Teampro'] : 0;

	if ($vProtitle == 0 && $vLevel == 0 && $vSelfpro == 0 && $vTeampro == 0) 
	{
		$areaStepFlag = '';
		break;
	}

	//判断分销商级别是否达到预设值的区域代理等级
	if ($MY_Distribute_Level >= $vLevel && $rsAccount['Professional_Title'] >= $vProtitle && $user_consue >= $vSelfpro && $Sales_Group >= $vTeampro) 
	{
		$areaStepFlag = $k;
		break;
	}
}
$AreaBs = array('省级代理', '市级代理', '县级代理');

$stepid = intval($_GET['stepid']) ? intval($_GET['stepid']) : '1';
if ($stepid > 1 && empty($_POST)) { exit('非法操作！'); }
switch ($stepid) {
	case '2':
		$paramForm = array(
			'Applyfor_Name' => htmlspecialchars(trim($_POST['Applyfor_Name']), ENT_QUOTES),
			'Applyfor_Mobile' => htmlspecialchars(trim($_POST['Applyfor_Mobile']), ENT_QUOTES),
			'Area' => intval($_POST['area'])
			);
		break;

	case '3':
		$ProvinceId = isset($_POST['ProvinceId']) ? intval($_POST['ProvinceId']) : 0;
		$CityId = isset($_POST['CityId']) ? intval($_POST['CityId']) : 0;
		$AreaId = isset($_POST['AreaId']) ? intval($_POST['AreaId']) : 0;

		$filterCondition = " where `Order_Status` = 2";
		if ($ProvinceId) { $filterCondition .= " AND `ProvinceId` = {$ProvinceId}"; }
		if ($CityId) { $filterCondition .= " AND `CityId` = {$CityId}"; }
		if ($AreaId) { $filterCondition .= " AND `AreaId` = {$AreaId}"; }

		$resultInfo = $DB->GetRs('agent_order', 'count(*) as num', $filterCondition);
        $flag = $DB->GetRs("distribute_agent_areas","*","WHERE Users_ID='{$UsersID}' AND area_id={$AreaId}");
        if($flag && $resultInfo['num'] > 0){
            exit('该区域已经被申请！');
        }

		$DATA = array(
			'Users_ID' => $UsersID,
			'User_ID' => $User_ID,
			'Applyfor_Name' => htmlspecialchars(trim($_POST['Applyfor_Name']), ENT_QUOTES),
			'Applyfor_Mobile' => htmlspecialchars(trim($_POST['Applyfor_Mobile']), ENT_QUOTES),
			'ProvinceId' => $ProvinceId,
			'CityId' => $CityId,
			'AreaId' => $AreaId,
			'Order_Status' => 0,
			'Order_CreateTime' => time(),
			'Level_ID' => $rsAccount['Level_ID'],
			'Level_Name' => empty($dis_level[$rsAccount['Level_ID']]) ? '' : $dis_level[$rsAccount['Level_ID']]['Level_Name'],
			'Area_Concat' => htmlspecialchars(trim($_POST['Area_Concat']), ENT_QUOTES)
		);

		if (!empty($_POST['Area'])) 
		{
			$areaFlag = intval($_POST['Area']) - 1;
			$Agent_Rate_Keys = array_keys($Agent_Rate);
			$DATA['Order_TotalPrice'] = $Agent_Rate[$Agent_Rate_Keys[$areaFlag]]['Provincepro'];
			$DATA['Area'] = $AreaMark = intval($_POST['Area']);
			$DATA['AreaMark'] = $AreaBs[$AreaMark-1];
		}

		$Flag = $DB->Add('agent_order', $DATA);
		if ($Flag) 
		{
			header('Location:/api/'.$UsersID.'/distribute/my_area_proxy/');
		}
		break;
}

$header_title = '申请区域代理';
require_once('header.php');
?>

<body>
<link href="/static/css/select2.css" rel="stylesheet"/>
<link href="/static/api/distribute/css/detaillist.css?t=<?php echo time();?>" rel="stylesheet">
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.validate.min.js'></script>
<script type='text/javascript' src='/static/api/js/user.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src="/static/js/select2.js?t=<?php echo time();?>"></script>
<script type="text/javascript" src="/static/js/location.js?t=<?php echo time();?>"></script>
<script type="text/javascript" src="/static/js/area.js?t=<?php echo time();?>"></script>
<script type="text/javascript" src="/static/api/distribute/js/area_proxy.js?t=<?php echo time();?>"></script>

<script type="text/javascript">
	$(document).ready(function(){
		showLocation(0, 0, 0);
		user_obj.my_address_init();

		$('#loc_province').change(function() {
			$('#hiddenForm').val($('#loc_province option:selected').text());
		});

		$('#loc_city').change(function() {
			$('#hiddenForm').val($('#loc_province option:selected').text() + '>' + $('#loc_city option:selected').text());
		});

		$('#loc_town').change(function() {
			$('#hiddenForm').val($('#loc_province option:selected').text() + '>' + $('#loc_city option:selected').text() + '>' + $('#loc_town option:selected').text());
		})

		$('#btn-addcard').click(function(){
			var Applyfor_Name_Obj = $('input[name="Applyfor_Name"]');
			var Applyfor_Mobile_Obj = $('input[name="Applyfor_Mobile"]');
			if (Applyfor_Name_Obj.val().length <= 0) { global_obj.win_alert('用户名称不能为空！', function() {}); Applyfor_Name_Obj.addClass('red').focus(); return false; };
			if (Applyfor_Mobile_Obj.val().length <= 0) { global_obj.win_alert('联系电话不能为空！', function() {}); Applyfor_Mobile_Obj.addClass('red').focus(); return false; };
			// var checkedProxy = $('input:radio[name="area"]:checked').val();
			// if (checkedProxy == null) { global_obj.win_alert('请选择您要申请的代理级别！', function() {}); return false; };
			$lastValue = $('.lastNode option:selected').val();

			if ($lastValue == "选择区县" || $lastValue == "选择城市" || $lastValue == "") { global_obj.win_alert('请选择要申请的区域!', function() {}); return false; };
			if ($('.lastNode').next('span').hasClass('bold')) { global_obj.win_alert('该区域已经被申请!', function() {}); return false; };
			$('#area_post_box').submit();
		});

		$('.lastNode').change(function(){
			var UsersID = "<?php echo $UsersID; ?>";
			var currentObj = $(this);
			
			var ProvinceId = $('#loc_province').val();
			var CityId = $('#loc_city').val();
			var AreaId = $('#loc_town').val();

			$.get('/api/distribute/ajax.php?UsersID='+UsersID, {'action':'checkCityApply', 'ProvinceId':ProvinceId, 'CityId':CityId, 'AreaId':AreaId, 'ajax':1}, function(data){
				if (data.status == 1) { 
					currentObj.parent('li.list-group-item').children('span').html(data.msg).removeClass('hideMsg').addClass('bold'); 
				} else {
					currentObj.parent('li.list-group-item').children('span').html(data.msg).addClass('hideMsg').removeClass('bold'); 
				}
			}, 'json');
		});

	})
</script>

<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">申请区域代理</h1>
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
	h1.tips { background: red; color: #FFF; width: %inherit; width: 97%; margin: 0 auto; font-size: 16px; text-align: center; padding: 8px 0; border-radius: 5px; font-weight: bold; -webkit-box-shadow: 0 0 10px #F29611; -moz-box-shadow: 0 0 10px #F29611; box-shadow: 0 0 10px #F29611; }
	.show_detail_link { display: block; overflow: hidden; width: 97%; padding: 0; margin: 10px auto; }
	.show_detail_link  a { display: inline-block; overflow: hidden; float: left; width: 40%; text-align: center; padding: 10px 0; background: #FFF; border: 1px solid #ddd; }
	.show_detail_link  a.r { float: right; }
</style>
<div class="wrap">
	<div class="container">
		<div class="row">
		<?php if(!empty($areaStepFlag)): ?>
			<form id="area_post_box" method="post" action="/api/<?php echo $UsersID; ?>/distribute/area_proxy/<?php echo $stepid+1; ?>/">
				<li class="list-group-item">申请区域代理 > #STEP <?php echo $stepid; ?></li>
			<?php if($stepid == '1') { ?>
        		
		        <li class="list-group-item bank_card" style="display:block">
		            <label>姓&nbsp;&nbsp;名</label>&nbsp;&nbsp;<input type="text" name="Applyfor_Name" placeholder="请输入您的姓名">
		        </li> 
		          
		        <li class="list-group-item bank_card" style="display:block">
		          <label>电&nbsp;&nbsp;话</label>&nbsp;&nbsp;<input type="text" name="Applyfor_Mobile" placeholder="请输入您的联系电话">	
		        </li>

		        <li class="list-group-item bank_card" style="display:block">
		          <label>区&nbsp;&nbsp;域</label>&nbsp;&nbsp;
		          <?php if($areaStepFlag == 'pro'): ?><input type="radio" name="area" value="1" <?php if($areaStepFlag == 'pro'): ?>checked<?php endif; ?>>省级代理<?php endif; ?>
		          <?php if($areaStepFlag == 'cit' || $areaStepFlag == 'pro'): ?><input type="radio" name="area" value="2" <?php if($areaStepFlag == 'cit'): ?>checked<?php endif; ?>>市级代理<?php endif; ?>
		          <?php if($areaStepFlag == 'pro' || $areaStepFlag == 'cit' || $areaStepFlag == 'cou'): ?><input type="radio" name="area" value="3" <?php if($areaStepFlag == 'cou'): ?>checked<?php endif; ?>>县级代理<?php endif; ?>
		        </li>
			<?php } elseif($stepid == '2') { ?>
					<?php if($paramForm['Area'] >= 1): ?>
					<li class="list-group-item">
			            <label>地&nbsp;&nbsp;区</label>&nbsp;&nbsp;
			            <select  name="ProvinceId" <?php if($paramForm['Area'] == 1): ?> class="lastNode"<?php endif; ?> id="loc_province" required style="height:30px; line-height:30px; width:65%; margin-left: -4px;">
	                        <option>选择省份</option> 
			            </select>
			            <span class="hideMsg"></span>
			        </li>
			    	<?php endif; ?>
					<?php if ($paramForm['Area'] >= 2): ?>
			        <li class="list-group-item">
						<label>城&nbsp;&nbsp;市</label>&nbsp;&nbsp;
						<select name="CityId" id="loc_city" <?php if($paramForm['Area'] == 2): ?> class="lastNode"<?php endif; ?> required style="height:30px; line-height:30px; width:65%; margin-left: -4px;">
							<option>选择城市</option>
						</select>
						<span class="hideMsg"></span>
					</li>
					<?php endif; ?>
					<?php if ($paramForm['Area'] >= 3): ?>
					<li class="list-group-item">
						<label>区&nbsp;&nbsp;县</label>&nbsp;&nbsp;
						<select name="AreaId" id="loc_town" <?php if($paramForm['Area'] == 3): ?> class="lastNode"<?php endif; ?> required style="height:30px; line-height:30px; width:65%; margin-left: -4px;">
							<option>选择区县</option>
						</select>
						<span class="hideMsg"></span>
					</li>
					<?php endif; ?>
					<input type="hidden" name="Applyfor_Name" value="<?php echo $paramForm['Applyfor_Name']; ?>">
					<input type="hidden" name="Applyfor_Mobile" value="<?php echo $paramForm['Applyfor_Mobile']; ?>">
					<input type="hidden" name="Area" value="<?php echo $paramForm['Area']; ?>">
					<input type="hidden" name="Area_Concat" id="hiddenForm">
		    <?php } ?>

		        <li class="list-group-item text-center">
		             <a href="javascript:void(0)" id="btn-addcard" class="btn btn-default submit-btn"><?php if($stepid == '1'): ?>下一步<?php else: ?>立即申请<?php endif; ?></a>
		        </li>
        	</form>
        <?php else: ?>
       		<h1 class="tips">你无权申请区域代理!</h1>
        <?php endif; ?>
		</div>

		<div class="show_detail_link">
			<a href="/api/<?=$UsersID?>/distribute/my_area_proxy/">我的申请记录</a>
			<a href="/api/<?=$UsersID?>/distribute/agent_info/" class="r">我的代理佣金明细</a>
		</div>

        <div class="row">
	      <h4>申请区域代理需满足以下对应条件：</h4>
	      <table width="100%" border="1" cellspacing="0"> 
		    <tr> 
		    	<th style="width: 16%;" class="bodytable">区域</th>
		        <th class="bodytable">申请条件描述</th> 
		    </tr> 
		    <?php foreach ($Agent_Rate as $k => $v) : ?>
		    <tr> 
		        <td class="bold">
		        	<?php
		        	switch ($k) {
		        		case 'pro':
		        			echo "省级";
		        			break;
		        		
		        		case 'cit':
		        			echo "市级";
		        			break;

		        		case 'cou':
		        			echo "县（区）级";
		        			break;
		        	}
		        	?>
		        </td> 
		        <td class="l">
		        	<div class="tdContent">
		        		<div class="leftContent">申请条件</div>
		        		<div class="rightContent">
		        			<?php if(!empty($v['Level'])): ?><span class="m">分销商等级：<?php echo $dis_level[$v['Level']]['Level_Name']; ?></span><?php endif; ?>
				        	<?php if(!empty($v['Protitle'])): ?><span class="m">爵位等级：<?php echo $Pro_Title_Level[$v['Protitle']]['Name']; ?></span><?php endif; ?>
				        	<?php if(!empty($v['Selfpro'])): ?><span class="m">个人消费额：<span class="bold">&yen;<?php echo $v['Selfpro']; ?></span></span><?php endif; ?>
				        	<?php if(!empty($v['Teampro'])): ?><span class="m">团队销售额：<span class="bold">&yen;<?php echo $v['Teampro']; ?></span></span><?php endif; ?>
		        		</div>
		        	</div>
		        	<div class="tdContent">
		        		<div class="leftContent cleftContent">所需金额</div>
		        		<div class="rightContent crightContent"><span class="m bold">&yen;<?php echo $v['Provincepro']; ?></span></div>
		        	</div>
		        	<div class="tdContent">
		        		<div class="leftContent cleftContent">佣金比例</div>
		        		<div class="rightContent crightContent"><span class="m bold" style="display:inline;"><?php echo $v['Province']; ?> %</span>(占网站商品利润分发区域代理的百分比)</div>
		        	</div>
		        </td> 
		    </tr> 
			<?php endforeach; ?>
		    
		  </table> 
        </div>


    </div>
</div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>
