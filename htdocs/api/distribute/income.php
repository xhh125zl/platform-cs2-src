<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');

$today = strtotime('today');
$now = strtotime('now');
$before_oneweek = strtotime('-1 week');
$before_onemonth = strtotime('-1 month');

//计算此用户今天的分销收入
$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Record_Type=0 and Record_Status = 1";
$condition .= " and Record_CreateTime >".$today." and Record_CreateTime <".$now;

$day = $DB->getRs('distribute_account_record','sum(Record_Money) as sum',$condition);

$day_sum = ($day['sum'] != null)?$day['sum']:0;

//计算此用户的累计收入
$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Record_Type=0 and Record_Status = 1";
$all = $DB->GetRs('distribute_account_record','sum(Record_Money) as sum',$condition);
$all_sum = ($all['sum'] != null)?$all['sum']:0;

//计算此用户一周内的分销收入
$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Record_Type=0 and Record_Status = 1";
$condition .= " and Record_CreateTime >".$before_oneweek." and Record_CreateTime <".$now;

$week = $DB->GetRs('distribute_account_record','sum(Record_Money) as sum',$condition);
$week_sum = ($week['sum'] != null)?$week['sum']:0;

//计算此用户一月内的分销收入
$condition = "where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Record_Type=0 and Record_Status = 1";
$condition .= " and Record_CreateTime >".$before_onemonth." and Record_CreateTime <".$now;

$month = $DB->GetRs('distribute_account_record','sum(Record_Money) as sum',$condition);
$month_sum = ($month['sum'] != null)?$month['sum']:0;

//获取此用户的不可用余额(即已申请提现，单未执行提现的现金金额)
$condition ="where User_ID=".$_SESSION[$UsersID."User_ID"]." and Users_ID='".$UsersID."' and Record_Type=1 and Record_Status = 0";

$withdraw_records = $DB->getRs("distribute_account_record","sum(Record_Money) as useless_sum",$condition);
$useless_sum =  !empty($withdraw_records['useless_sum'])?$withdraw_records['useless_sum']:0;
$header_title = '我的团队';
require_once('header.php');
?>
<body>
<link href="/static/api/distribute/css/income.css" rel="stylesheet">
<div class="wrap">
	<div class="container">
    <h4 class="row page-title">我的收入</h4>
    </div>
    
  
    <ul id="distribute_group">
   		 <li class="item"><a href="/api/<?=$UsersID?>/distribute/group/">我的团队</a></li>
   		<li class="item"><a href="/api/<?=$UsersID?>/distribute/my_distribute/">我的推广</a></li>
   		<li class="item cur"><a href="/api/<?=$UsersID?>/distribute/income/">分销佣金</a></li>
  		<li class="clearfix"></li>
  	</ul>

  
  	<div id="income_list">
    	 <div class="income_item">
         	 <p>本周收入</p>
             <h5>&yen;&nbsp;&nbsp;<?=$week_sum?></h5>
         </div>
         
         <div class="income_item">
         	 <p>本月收入</p>
             <h5>&yen;&nbsp;&nbsp;<?=$month_sum?></h5>
         </div>
         
         <div class="income_item">
         	 <p>累计收入</p>
             <h5>&yen;&nbsp;&nbsp;<?=$all_sum ?></h5>
         </div>
         
         <div class="income_item">
         	 <p>实际总收入</p>
             <h5>&yen;&nbsp;&nbsp;<?=$rsAccount['Total_Income']?></h5>
         </div>
         
         <div class="clearfix">
         </div>
    </div>
  
  
  	<div class="container">
    	<div class="row" id="withdraw_panel">
        	<div class="col-xs-5">
            	<p>
                	可提现金额:<br/>
                    <font style="color:red;font-weight:bold;">&yen;&nbsp;&nbsp;<?=$rsAccount['balance']?></font>
                </p>
            </div>
            
            <div class="col-xs-5" style="text-align:center;">
            	<button class="btn btn-info btn-sm">提现</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>

