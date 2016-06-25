<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
if($rsConfig["Withdraw_Type"]==3){
    $accountObj->Enable_Tixian = 0;
	$accountObj->save();
}
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
                }else{
                    header("location:".distribute_url()."withdraw_apply/");
                }
            }
        } elseif ($rsConfig['Withdraw_Type']==2){
            $Withdraw_Limt = !empty($rsConfig['Withdraw_Limit'])?explode('|',$rsConfig['Withdraw_Limit']):'array(0)';
            if ($Withdraw_Limt[0] == 0){
                $row = $DB->GetRs('user_order','Order_ID',"where Users_ID='".$UsersID."' and User_ID= '".$_SESSION[$UsersID.'User_ID']."'");
                if ($row) {
                    $accountObj->Enable_Tixian = 1;
                    $accountObj->save();	
                }
                 
            }else{
                if (empty($Withdraw_Limt[1])) {
                    $row = $DB->GetRs('user_order','Order_ID',"where Users_ID='".$UsersID."' and User_ID= '".$_SESSION[$UsersID.'User_ID']."'");
                    if ($row) {
                        $accountObj->Enable_Tixian = 1;
                        $accountObj->save();	
                    } else {
                        header("location:".distribute_url()."withdraw_apply/");
                    }
                }else{
                    $id_array = explode(',',$Withdraw_Limt[1]);  
                    
                    $rs = $DB->Get('user_order','Order_CartList',"where Users_ID='".$UsersID."' and User_ID= '".$_SESSION[$UsersID.'User_ID']."'");
                    $orderlist = $DB->toArray($rs); 
                    $flag = '';
                    foreach ($orderlist as $k => $v) {
                        $Order_CartList = json_decode($v['Order_CartList'],true);
                        foreach ($Order_CartList as $ks => $vs) {
                            if (in_array($ks,$id_array)){
                                $flag = 1;
                                $accountObj->Enable_Tixian = 1;
                                $accountObj->save();
                                break 2;
                            }
                        }
                    }
                    if($flag != 1){
                        header("location:".distribute_url()."withdraw_apply/");
                    }
                }
            }  
        }elseif($rsConfig["Withdraw_Type"]==3){//等级限制
            if($rsConfig["Withdraw_Limit"]==0){			
                $accountObj->Enable_Tixian = 1;
                $accountObj->save();			
            }else{
                if($rsAccount['Level_ID'] >= $rsConfig["Withdraw_Limit"]){
                        $accountObj->Enable_Tixian = 1;
                        $accountObj->save();
                }else{
                        header("location:".distribute_url()."withdraw_apply/");
                }
            }
	}else{
		header("location:".distribute_url()."withdraw_apply/");
	}
}

//提现方式列表
$rsUserMethods = $DB->Get("distribute_withdraw_methods","*","where Users_ID='".$UsersID."' and User_ID= '".$_SESSION[$UsersID.'User_ID']."'");
$user_method_list = $DB->toArray($rsUserMethods);
//获取帮助此用户的记录
$condition = "where Users_ID='".$UsersID."' and User_ID= '".$_SESSION[$UsersID.'User_ID']."'";

$rsRecord = $DB->Get('distribute_account_record','*',$condition);
$Records = $DB->toArray($rsRecord);

//统计

$User_ID = $_SESSION[$UsersID.'User_ID'];
//自销次数
$builder = Dis_Account_Record::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID));
$self_distribute_count = $builder->whereHas('DisRecord',function($query) use($User_ID){
									$query->where('Owner_ID','=',$User_ID);
								})->count();
//购买分销级别自销
$r = $DB->GetRs('distribute_order_record','count(Order_ID) as num','where Owner_ID='.$User_ID.' and User_ID='.$User_ID);
$self_distribute_count = $self_distribute_count + $r['num'];
//下级销售次数
$builder = Dis_Account_Record::Multiwhere(array('Users_ID'=>$UsersID,'User_ID'=>$User_ID ));
$posterity_distribute_count = $builder->whereHas('DisRecord',function($query) use($User_ID){
									$query->where('Owner_ID','!=',$User_ID);
								})->count();
//购买级别下属分销
$posterity_temp = $accountObj->getPosterity($rsConfig['Dis_Level']);
$posterityids = array();
if (count($posterity_temp) > 0) {
	$posterityids = $posterity_temp->map(function ($node) {
		return $node->User_ID;
	})->toArray();
}
if(!empty($posterityids)){	
	$r = $DB->GetRs('distribute_order_record','count(Order_ID) as num','where Owner_ID in('.implode(',',$posterityids).')'.' and User_ID='.$User_ID);
	$posterity_distribute_count = $posterity_distribute_count + $r['num'];
}
								
//提现次数
$withdraw_count_result = $DB->GetRs('distribute_withdraw_record','count(Record_ID) as num','where User_ID='.$User_ID);
$withdraw_count = $withdraw_count_result['num'];
								

if(!empty($_GET['filter'])){
	
	if($_GET['filter'] == 'self'){
		$filter_record = $self_record;
	}elseif($_GET['filter'] == 'down'){
		$filter_record =$posterity_record;
	}elseif($_GET['filter'] == 'withdraw'){
		$filter_record =$withdraw_record;
	}
	
}else{
	$filter_record = $Records; 
}


//获取此用户可用的提现方式
$condition = "where Users_ID= '".$UsersID."' and Status = 1 order by Method_ID";
$rsMethods = $DB->Get('distribute_withdraw_method','*',$condition);
$enabled_method_list = $DB->toArray($rsMethods);
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>用户提现</title>
 <link href="/static/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="/static/css/font-awesome.css">
    <link href="/static/api/distribute/css/style.css" rel="stylesheet">
     <link href="/static/api/distribute/css/withdraw.css" rel="stylesheet">
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/static/js/jquery-1.11.1.min.js"></script>
    <script type='text/javascript' src='/static/js/jquery.validate.js'></script>
    <script src="/static/js/bankInput.js"></script>
    <script type='text/javascript' src='/static/api/js/global.js'></script>
    <script src="/static/api/distribute/js/distribute.js?t=1234"></script>
     <script language="javascript">
	jQuery.extend(jQuery.validator.messages, {  
       	 	required: "必须填写",  
			email: "请输入正确格式的电子邮件",  
			url: "请输入合法的网址",  
			date: "请输入合法的日期",  
			dateISO: "请输入合法的日期 (ISO).",  
			number: "请输入合法的数字",  
			digits: "只能输入整数",  
			creditcard: "请输入合法的信用卡号",  
			equalTo: "请再次输入相同的值",  
			accept: "请输入拥有合法后缀名的字符串",  
			maxlength: jQuery.validator.format("请输入一个长度最多是 {0} 的字符串"),  
			minlength: jQuery.validator.format("请输入一个长度最少是 {0} 的字符串"),  
			rangelength: jQuery.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),  
			range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),  
			max: jQuery.validator.format("请输入一个最大为 {0} 的值"),  
			min: jQuery.validator.format("请输入一个最小为 {0} 的值")  
	});  

	var base_url = '<?=$base_url?>';
	var UsersID = '<?=$UsersID?>';
	var withdraw_limit = '<?=$rsConfig["Withdraw_PerLimit"]?>';
	$(document).ready(distribute_obj.init);
	$(document).ready(distribute_obj.withdraw_page);

</script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>

<body>
<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/shop/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">申请提现</h1>
  
</header>

<div class="wrap">
	<div class="container">
    	<div class="row page-title">
           <h4>&nbsp;&nbsp;&nbsp;&nbsp;可提现余额&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">&yen;<?=$rsAccount['balance']?></span> </h4>  
         	
        </div>
        
		
        <div class="row">
    <form action="/api/<?=$UsersID?>/distribute/ajax/" id="withdraw-form"/>     
        	<ul class="list-group withdraw-panel">
     
     <input type="hidden" id="balance" value="<?=$rsAccount['balance']?>"/>
  	 <input type="hidden" name="action" value="withdraw_appy"/>
  	 
  <li class="list-group-item">
	<?php if(!empty($rsConfig["Balance_Ratio"]) && !empty($rsConfig["Poundage_Ratio"])){?>
	申请提现后，系统会自动扣除您提现的总金额的<?php echo $rsConfig["Poundage_Ratio"];?>%的手续费，<?php echo $rsConfig["Balance_Ratio"];?>%转入您的会员余额，<?php echo (100-$rsConfig["Balance_Ratio"]-$rsConfig["Poundage_Ratio"]);?>%店主会手动将钱打入您的账号；若全部转入余额则不扣除手续费。
	<?php }elseif(!empty($rsConfig["Balance_Ratio"])){?>
    申请提现后，您提现的金额<?php echo $rsConfig["Balance_Ratio"];?>%转入您的会员余额，<?php echo (100-$rsConfig["Balance_Ratio"]);?>%店主会手动将钱打入您的账号。
	<?php }elseif(!empty($rsConfig["Poundage_Ratio"])){?>
	申请提现后，系统会自动扣除您提现的总金额的<?php echo $rsConfig["Poundage_Ratio"];?>%的手续费，<?php echo (100-$rsConfig["Poundage_Ratio"]);?>%店主会手动将钱打入您的账号；若全部转入余额则不扣除手续费。
	<?php }else{?>
	申请提现后，店主会手动将钱打入您的账号
	<?php }?>
  </li>       
  <li class="list-group-item">
  	 <label>提现额</label>&nbsp;&nbsp;<input name="money" id="withdraw-money" notnull type="text" placeholder="提现的金额" />
  </li>
  
  <li class="list-group-item">
  
  	  <label>账&nbsp;&nbsp;号</label> &nbsp;&nbsp;
     <?php if(count($user_method_list) >0 ):?>
     <select id="user_method_id"  name="User_Method_ID">
      	<?php foreach($user_method_list as $key=>$item):?>
        	<option value="<?=$item['User_Method_ID']?>"><?=$item['Method_Name']?>&nbsp;&nbsp;<?=$item['Account_Val']?></option>        
		<?php endforeach;?>
      </select>
      
     <?php else:?>
     	添加提现方法后才可体现
     <?php endif;?>
     
    </li>
	<?php if($rsAccount["status"]==0){?>
    <li class="list-group-item text-center">
    	<span class="red">您的分销账号已被禁用，不可提现</span>
    </li>
    <?php }elseif(count($user_method_list) >0){ ?>
    <li class="list-group-item text-center">
    	<a href="javascript:void(0)" id="btn-withdraw" class="submit-btn btn btn-default btn-disable">申请提现</a>
    </li>
    <?php }?>
</ul>
	
    </form>
    
        <!--添加提现方法表单-->
        <ul class="list-group withdraw-panel" id="add_card_panel" style="display:<?php echo count($user_method_list)>0 ? 'none' : 'block';?>">
        <?php if(!empty($enabled_method_list)):?>
          <form action="/api/<?=$UsersID?>/distribute/ajax/" id="bank_card_form">
          <li class="list-group-item">添加提现方法</li>
            <input type="hidden" name="action" value="add_user_withdraw_method"/>
            <input type="hidden" name="Method_Type" id="Method_Type" value="<?=$enabled_method_list[0]['Method_Type']?>"/>
          <li class="list-group-item">
            <label>提现方式</label>&nbsp;&nbsp;<select name="Method_Name" id="User_Method_Name">
                <?php foreach($enabled_method_list as $key=>$item): ?>
                    <option vlaue="<?=$item['Method_Name']?>" method_type="<?=$item['Method_Type']?>"><?=$item['Method_Name']?></option> 
                <?php endforeach; ?>
					<option vlaue="转入余额" method_type="yue_income">转入余额</option> 
            </select>
          </li>
        <li class="list-group-item bank_card" style="display:<?php echo $enabled_method_list[0]['Method_Type'] == 'wx_hongbao' || $enabled_method_list[0]['Method_Type'] == 'wx_zhuanzhang' ? 'none' : 'block';?>">
            <label>户&nbsp;&nbsp;名</label>&nbsp;&nbsp;<input type="text" name="Account_Name" placeholder="请输入您的户名" />
        </li>
          
        <li class="list-group-item bank_card" style="display:<?php echo $enabled_method_list[0]['Method_Type'] == 'wx_hongbao' || $enabled_method_list[0]['Method_Type'] == 'wx_zhuanzhang' ? 'none' : 'block';?>">
          <label>帐&nbsp;&nbsp;号</label>&nbsp;&nbsp;<input type="text" name="Account_Val" placeholder="请输入您的帐号" />	
        </li>
        
        <li class="list-group-item  bank_card_info bank_card" style="display:<?php echo $enabled_method_list[0]['Method_Type'] != 'bank_card' ? 'none' : 'block';?>" id="bank_position">
          <label>开户行</label>&nbsp;&nbsp;<input type="text" name="Bank_Position" placeholder="请输入您的开户行" />	
        </li>
        
        <li class="list-group-item text-center">
             <a href="javascript:void(0)" id="btn-addcard" class="btn btn-default submit-btn">添加</a>
        </li>
        </form>
        <?php else: ?>
          <li class="list-group-item red">管理员尚未设置可用提现方式</li>
        <?php endif; ?>	
        </ul>
    <?php if(count($user_method_list) >0 ):?>
         <ul class="list-group withdraw-panel">
			<li class="list-group-item"> <a class="btn btn-default" id="add-card"><span class="fa fa-plus"></span>&nbsp;&nbsp;添加新的提现方法</a>	</li>
            <li class="list-group-item"> <a class="btn btn-default" href="/api/<?=$UsersID?>/distribute/bankcards/" id="manage-card"><span class="fa fa-credit-card"></span>&nbsp;&nbsp;管理我的提现方法</a></li>
	     </ul>
   <?php endif;?>
        </div>
        
        <div class="row">
        	<ul id="distribute-brief-info">
         <li class="item"><a href="<?=distribute_url()?>detaillist/self/"><span class="red bold">&nbsp;<?=$self_distribute_count?></span><br/>自销</a></li>
         <li class="item"><a href="<?=distribute_url()?>detaillist/down/"><span class="red bold">&nbsp;<?=$posterity_distribute_count?></span><br/>下级分销</a></li>
         <li class="item"><a href="<?=distribute_url()?>withdraw_record/"><span class="red bold">&nbsp;<?=$withdraw_count?></span><br/>提现次数</a></li>
         <li class="clearfix"></li>
      </ul>
    
        </div>

    </div>
    
  	
  
    
</div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>
