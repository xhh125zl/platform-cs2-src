<?php
require_once('global.php');
$level_config = $rsConfig['Dis_Level'];

$posterity = $accountObj->getPosterity($level_config);
//var_dump($posterity);exit;
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

$total_sales = round_pad_zero(get_my_leiji_sales($UsersID,$User_ID,$posterity),2);
$total_income = round_pad_zero(get_my_leiji_income($UsersID,$User_ID),2);

					 	  
$dis_agent_type = $rsConfig['Dis_Agent_Type'];
$is_agent = is_agent($rsConfig,$rsAccount);

//爵位设置
$pro_titles = Dis_Config::get_dis_pro_title($UsersID);
$show_support = true;

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
            	<div class="pull-left" style="width:30%">
                	  	 <img id="hd_image" src="<?=$show_logo?>"/>
                </div>
            
                <div class="pull-right" style="width:70%;">
                	<ul id="txt" style="padding-left:0px;">
                      <li><?=$show_name?></li>
                      <li style="padding-right:3px;">
                        
                        <?php if(strlen($rsUser['User_Mobile'])==0): ?>	
                           <a style="color:#4878C6;text-decoration:underline;line-height:20px;" href="<?=shop_url('distribute/bind_mobile/')?>">&nbsp;【点击绑定手机】</a>
                        <?php else:?>
                           <a style="color:#4878C6;text-decoration:underline;line-height:20px;" href="<?=shop_url('distribute/change_bind/')?>">&nbsp;【更改绑定手机】</a>
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
     <a href="javascript:void(0)" class="item_group_title"><img src="/static/api/distribute/images/group.png"/> 我的团队&nbsp;&nbsp;<span class="pink font17">(<?=$rsAccount['Group_Num']?>)</span></a>
      <div class="dline"></div>
    
    <?php foreach ($posterity_list as $key => $sub_list): ?>
    <a href="javascript:void(0)" class="item item_<?=$key?> item_group_account_btn" status="close"><span class="ico"></span><?=$level_name_list[$key]?><button status="close" class="btn btn-default btn-sm group_count"><?=count($sub_list)?></button></a>
    
     	<?php if (count($sub_list) > 0): ?>
          <ul class="list-group distribute-sub-list">
          	<?php foreach ($sub_list as $k => $v): ?>
       		 	<li class="list-group-item ">
				<?php
				if (strlen($v['Shop_Name']) > 0) {
					echo $v['Shop_Name'];
				} else {
					echo '暂无';
				}
				?>

	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=ldate($v['Account_CreateTime'])?></li>
            <?php endforeach;?>
		  </ul>
        <?php endif;?>
        
	<?php endforeach;?>

	</div>
  		<!-- 我的分销商列表end -->

  		<!-- 我的收入统计begin -->
  		<div class="list_item">
   <div class="dline"></div>
     <a href="javascript:void(0)" class="item_group_title"><img src="/static/api/distribute/images/coin_stack.png"/>&nbsp;&nbsp;我的佣金&nbsp;&nbsp;<span class="pink font17">(<?=$bonus_list['total']?>)</span></a>
    <div class="dline"></div>

    <a href="javascript:void(0)" class="item item_0"><span class="ico"></span>本周收入<button class="btn btn-default btn-sm bonus_sum"><?=$bonus_list['week_income']?></button></a>
	<a href="javascript:void(0)" class="item item_1"><span class="ico"></span>本月收入<button class="btn btn-default btn-sm bonus_sum"><?=$bonus_list['month_income']?></button></a>
    <a href="javascript:void(0)" class="item item_2"><span class="ico"></span>未付款佣金<button class="btn btn-default btn-sm bonus_sum"><?=$bonus_list['un_pay'];?></button></a>
    <a href="javascript:void(0)" class="item item_2"><span class="ico"></span>已付款佣金<button class="btn btn-default btn-sm bonus_sum"><?=$bonus_list['payed'];?></button></a>
    <a href="/api/<?=$UsersID?>/shop/distribute/fanben_detail/" class="item item_2"><span class="ico"></span>返本明细<button class="btn btn-default btn-sm bonus_sum"><?=$rsAccount['Fanxian_Count'];?></button></a>
    <a href="javascript:void(0)" class="item item_2"><span class="ico"></span>可提现佣金
    <button class="btn-sm btn btn-default" id="withdraw_btn" link="/api/<?=$UsersID?>/shop/distribute/withdraw/">提&nbsp;&nbsp;现</button><span class="divider">&nbsp;</span>
    <button class="btn btn-default btn-sm" id="balance_sum"><?=round_pad_zero($rsAccount['balance'],2)?></button></a>
	<div class="clearfix"></div>
</div>
  		<!-- 我的收入统计end -->


    	<div class="list_item">
     <div class="dline"></div>
     <a href="/api/<?=$UsersID?>/shop/distribute/qrcodehb/" class="item_group_title"><img src="/static/api/distribute/images/qrcode.png"/>&nbsp;&nbsp;我的推广二维码
      <span class="fa fa-2x fa-chevron-right grey pull-right"></span>
     </a>



</div>

    <div class="list_item">
     <div class="dline"></div>
     <a href="/api/<?=$UsersID?>/shop/distribute/income_list/" class="item_group_title"><img src="/static/api/distribute/images/income_list.jpg"/>&nbsp;&nbsp;财富排行榜
      <span class="fa fa-2x fa-chevron-right grey pull-right"></span>
     </a>



	</div>
    
   <?php if($dis_agent_type >0&$is_agent):?> 
   <div class="list_item">
     <div class="dline"></div>
     <a href="/api/<?=$UsersID?>/shop/distribute/agent_info/" class="item_group_title">&nbsp;<img src="/static/api/distribute/images/agent.png"/>&nbsp;&nbsp;代理信息 
      <span class="fa fa-2x fa-chevron-right grey pull-right"></span>
     </a>
	</div>
    
    <?php endif; ?>

    <div class="list_item">
	 <div class="dline"></div>
     <a href="/api/<?=$UsersID?>/shop/distribute/edit_shop/" class="item_group_title"><img src="/static/api/distribute/images/config.png"/>&nbsp;&nbsp;我的店铺配置
     <span class="fa fa-2x fa-chevron-right grey pull-right"></span>
     </a>

	</div>

	<?php if ($rsConfig['Distribute_Customize'] == 1): ?>

     <div class="list_item">
	 <div class="dline"></div>
     <a href="/api/<?=$UsersID?>/shop/distribute/edit_headimg/" class="item_group_title"><img src="/static/api/distribute/images/face_pre.png"/>&nbsp;&nbsp;自定义头像
 	 <span class="fa fa-2x fa-chevron-right grey pull-right"></span>
     </a>
     </div>

   <?php endif;?>
   	<?php if (!empty($pro_titles)): ?>
    	<div class="list_item">
	 		<div class="dline"></div>
     		<a href="/api/<?=$UsersID?>/shop/distribute/pro_title/" class="item_group_title"><img src="/static/api/distribute/images/title.jpg"/>&nbsp;&nbsp;爵位晋升     <span class="fa fa-2x fa-chevron-right grey pull-right"></span>
     		</a>
     	</div>
   <?php endif;?>
   
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

<?php require_once '../skin/distribute_footer.php';?>

</body>
</html>