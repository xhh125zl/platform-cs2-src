<?php
require_once('global.php');
$level_id = intval($_GET['levelId']);
if (!$level_id) 
{
  exit('缺少必要参数');
}

$level_config = $level_id;
$posterity_tem = $accountObj->getPosterity($rsConfig['Dis_Level']);
$posterity = $accountObj->getPosterity($level_config);
$posterity_count = $posterity->count();
$posterity_list = $accountObj->getLevelList($level_config);
$posterity_list = array_slice($posterity_list,0,$rsConfig['Dis_Mobile_Level'],TRUE);
//获取此分销账户佣金情况
$record_list = Dis_Account_Record::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID,'Record_Type'=>0))
                                   ->get(array('Record_Money','Record_Status','Record_CreateTime'))
								   ->toArray();

$bonus_list = dsaccount_bonus_statistic($record_list);

if ($rsConfig['Distribute_Customize'] == 0) {
	$show_name = $rsUser['User_NickName'];
	$show_logo = !empty($rsUser['User_HeadImg']) ? $rsUser['User_HeadImg'] : '/static/api/images/user/face.jpg';
} else {
	$show_name = !empty($rsAccount['Shop_Name']) ? $rsAccount['Shop_Name'] : '暂无';
	$show_logo = !empty($rsAccount['Shop_Logo']) ? $rsAccount['Shop_Logo'] : '/static/api/images/user/face.jpg';
}	

$level_name_list = array(1 => '一级分销商', 2 => '二级分销商', 3 => '三级分销商',
                         4 => '四级分销商',5=>'五级分销商',6=>'六级分销商',
						 7=>'七级分销商',8=>'八级分销商',9=>'九级分销商');

//判断当前分销账户可否提现并处理
if($rsAccount["Enable_Tixian"] == 0){
	if($rsConfig["Withdraw_Type"]==0){//无限制
		$accountObj->Enable_Tixian = 1;
		$accountObj->save();
	}elseif($rsConfig["Withdraw_Type"]==1){//佣金限制
		if($rsConfig["Withdraw_Limit"]==0){			
			$accountObj->Enable_Tixian = 1;
			$accountObj->save();			
		}else{
			if($rsAccount['Total_Income'] >= $rsConfig["Withdraw_Limit"]){
				$accountObj->Enable_Tixian = 1;
				$accountObj->save();
			}
		}
	}
}

$total_sales = round_pad_zero(get_my_leiji_sales($UsersID,$User_ID,$posterity_tem),2);
$total_income = round_pad_zero(get_my_leiji_income($UsersID,$User_ID),2);

//首页自定义设置 edit in 2016.3.24
$Index_Professional_Json = $rsConfig['Index_Professional_Json'] ? json_decode($rsConfig['Index_Professional_Json'], TRUE) : array();

$count_posterity_list = 0;
//edit in 2016.3.24
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

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style type="text/css">
 .last_list_line ul { display: block;
    margin: 0;
    padding: 0; }
  .last_list_line li { display: block;
    overflow: hidden;
    background: #FFF;
    width: 100%;
    text-indent: 20px;
    height: 50px;
    line-height: 50px;
    margin: 5px 0 15px 0; }
    .last_list_line li span {     float: right;
    margin-right: 20px; }
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
            	<div class="pull-left" style="width:25%">
                	  	 <img id="hd_image" src="<?=$show_logo?>"/>
                </div>
            
                <div class="pull-right" style="width:75%;">
                	<ul id="txt" style="padding-left:0px;">
                      <li>昵称：<?=$show_name?>　　ID：<?php echo $rsAccount['User_ID']; ?></li>
                      <li style="padding-right:3px;">分销级别：<?php echo empty($dis_level[$rsAccount['Level_ID']]) ? '' : $dis_level[$rsAccount['Level_ID']]['Level_Name']; ?> 【<a style="color:#FFF;" href="<?=distribute_url('upgrade/')?>">升级</a>】</li>
                      <li style="padding-right:3px;">
                        <?php if(strlen($rsUser['User_Mobile'])==0): ?> 
                           <a style="color:#4878C6;text-decoration:underline;line-height:20px;" href="<?=distribute_url('bind_mobile/')?>">&nbsp;【绑定手机】</a>
                        <?php else:?>
                           手机：<?php echo $rsUser['User_Mobile'];?>　　<a style="color:#4878C6;line-height:20px;" href="<?=distribute_url('change_bind/')?>">&nbsp;【更改手机】</a>
                        <?php endif;?>
                      </li>
                  
                    </ul>
                </div>
                
           		<div class="clearfix">
                </div>
            </div>
      </div>
	 
      <div id="account_sum">	
      	    <a href="javascript:void(0)"><span><?=$total_sales?></span><br>累计销售额</a>
   			    <a href="javascript:void(0)"><span><?=$total_income?></span><br>累计佣金</a>
            <div class="clearfix"></div>
      </div>      
      </div>
    </div>
   	    <div class="clearfix"></div>
  		</div>

  	 	<!-- 我的分销商列表begin -->
    	<div class="list_item" id="posterity_list">

	<div class="dline"></div>
    <?php if (!empty($posterity_list)) : ?>
    <?php foreach ($posterity_list as $key => $sub_list): ?>
    <?php if (count($sub_list) > 0 && $key == $level_id): ?>
    <?php  $count_posterity_list = count($sub_list); ?>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
     <a href="javascript:void(0);" class="item_group_title"><img src="/static/api/distribute/images/group.png"/> <?php echo empty($Index_Professional_Json['childlevelterm'][$level_config]) ? $level_name_list[$level_config] : $Index_Professional_Json['childlevelterm'][$level_config]; ?>&nbsp;&nbsp;<span class="pink font17">(<?php echo $count_posterity_list; ?>)</span></a>
      
      <div class="last_list_line">       
          <?php if (!empty($posterity_list)) : ?>
          <?php foreach ($posterity_list as $key => $sub_list): ?>
          <?php if (count($sub_list) > 0 && $key == $level_id): ?>
          <?php foreach ($sub_list as $k => $v): ?>
		  <div class="dislist">
		  <div class="img">
		  <img class="hd_img" src="<?=$v['Shop_Logo']?>"/>
		  </div>
		  <div class="imgli">
		  <dd>昵称：<?=$v['Real_Name']?>&nbsp;&nbsp;ID：<?=$v['User_ID']?></dd>
		  <dd>分销级别：<?php echo empty($dis_level[$v['Level_ID']]) ? '' : $dis_level[$v['Level_ID']]['Level_Name']; ?></dd>
		  <dd>手机：<?php echo empty(handle_getuserinfo($UsersID,$v['User_ID'])) ? '' : handle_getuserinfo($UsersID,$v['User_ID']);?></dd>
		  <dd><?php echo empty($v['Account_CreateTime']) ? '' : ldate($v['Account_CreateTime']); ?></dd>
		  </div>		 
		  </div>		  			
          <?php endforeach; ?>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php endif; ?>       
      </div>
	</div>
  		<!-- 我的分销商列表end -->

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