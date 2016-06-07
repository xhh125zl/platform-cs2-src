<?php
ini_set("display_errors","On");

require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/eloquent.php');
require_once(BASEPATH.'/include/support/order_helpers.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');


//设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$template_dir = $_SERVER["DOCUMENT_ROOT"].'/member/shop/html';
$smarty->template_dir = $template_dir;

$base_url = base_url();

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}else{
	  
	//订单状态列表

	$Users_ID = $_SESSION["Users_ID"];
	$rsConfig = shop_config($Users_ID);
	
	$rsUsers = Users::find($Users_ID)->toArray();
	$createtime = $rsUsers['Users_CreateTime'];
	$users_endtime = $rsUsers['Users_ExpireDate'];
	
	//获取续费价格
	$SysConfig = Setting::first()->toArray();
	
	$price_list = json_decode($SysConfig['sys_price'],true);
	$one_year_price = $price_list[1];
	
	//检测此店主是否有续费记录	
	$renewal_count = Users_Money_Record::Multiwhere(array('Users_ID'=>$Users_ID,'Record_Status'=>1))
	                 ->count();
	
	if($renewal_count > 0 ){
		$btn_text = '&nbsp;&nbsp;续费&nbsp;&nbsp;';
	}else{
		$btn_text = '立即升级';
	}
	
	//进行数据统计
	
	//计算时间点
	$carbon = Carbon\Carbon::now();
	$now = $carbon->timestamp;
	$today = $carbon->startOfDay()->timestamp;
	
	//计算本月时间始末
	$month_start = $carbon->startOfMonth()->timestamp;
	$month_end =  $carbon->endOfMonth()->timestamp;
	
	$order = new Order();
	$account_record = new Dis_Account_Record();
	$account = new Dis_Account();
	
	//今日订单总数目
	$today_all_order_num = $order->statistics($Users_ID,'num',$today,$now);
	//今日已付款订单
	$today_payed_order_num = $order->statistics($Users_ID,'num',$today,$now,3);
	//今日销售额
	$today_order_sales = $order->statistics($Users_ID,'sales',$today,$now);
	//本月销售额
	$month_order_sales = $order->statistics($Users_ID,'sales',$month_start,$month_end);
	//今日支出佣金
	$today_output_money = $account_record->recordMoneySum($Users_ID,$today,$now);
	//本月支出佣金
	$month_output_money = $account_record->recordMoneySum($Users_ID,$month_start,$month_end);
    //今日加入分销商
	$today_new__account_num = $account->accountCount($Users_ID,$today,$now);
	//本月加入分销商
	$month_new_account_num =  $account->accountCount($Users_ID,$month_start,$month_end);
	
	$fields = array('Order_ID','Order_CreateTime','Order_TotalAmount','Order_Status');
	$Begin_Time = $today;
	$End_Time = $now;
	
	//本日进账记录
	$input_info = order_input_record($Users_ID,$Begin_Time,$End_Time);
	$order_input_record_table = generate_input_record_table($smarty,$input_info); 
	
	//本日出账记录
	$output_info  = output_record($Users_ID,$Begin_Time,$End_Time);
	$output_record_table = generate_output_table($smarty,$output_info); 
	
	
	//年，月
	$month = intval(date('m'));
	$year = date('Y');
	$day = intval(date('d'));
	$daysnum = days_in_month($month,$year);

	$carbon = Carbon\Carbon::createFromFormat('Y/m/d',"$year/$month/$day");
		
	$mon_start = $carbon->startOfMonth()->timestamp;
	$month_days_range = array();
	$month_sales = array();
	//获取每天的时间戳
	for ($i=1; $i<=$daysnum; $i++) { 
		$month_days_range[$i] = array('begin'=>$carbon->startOfDay()->timestamp,
		                               'end'=>$carbon->endOfDay()->timestamp);
		$month_sales[$i] = 0;
		if($i < $daysnum){
			$carbon->addDay();							   
		}
		
	}

	$mon_end = $carbon->endOfMonth()->timestamp;
	
	//获取本月内所有订单列表
	$month_order_list = Order::where('Users_ID',$Users_ID)
	                   ->whereBetween('Order_CreateTime', array($mon_start,$mon_end))
					   ->get(array('Order_ID','Order_TotalAmount','Order_CreateTime'));
	
	
	//统计每日销量
	if($month_order_list->count()>0){
		$order_array = 	$month_order_list->toArray();
		$new_collect = collect($order_array);
		foreach($month_days_range as $key => $day){
			
		  $sum = $new_collect->filter(function($order) use($day){
					if($order['Order_CreateTime'] >$day['begin']&&$order['Order_CreateTime'] <= $day['end']){
						return true;
					}
				})->sum('Order_TotalAmount');
		
			$month_sales[$key] = $sum;
		
		}
	}
    
				   
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="UTF-8">
	<title>后台首页</title>
	<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
     <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
	<link href='/static/css/bootstrap.min.css' rel='stylesheet' type='text/css' />
	<link href='/static/css/font-awesome.css' rel='stylesheet' type='text/css' />
	<link href='/static/css/daterangepicker.css' rel='stylesheet' type='text/css' />
	<link href='/static/member/css/account_home.css' rel='stylesheet' type='text/css' />
    	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="/static/js/html5shiv.js"></script>
		<script src="/static/js/respond.min.js"></script>
		<![endif]-->
        
	<script type='text/javascript' src='/static/js/jquery-1.11.1.min.js'></script>
	<script type='text/javascript' src='/static/js/bootstrap.min.js'></script>
	<script type='text/javascript' src='/static/js/jquery.twbsPagination.min.js'></script>
	<script type='text/javascript' src='/static/member/js/global.js'></script>
	<script src="/static/js/moment.js"></script>
	<script src="/static/js/daterangepicker.js"></script>
	<script type='text/javascript' src='/static/js/plugin/highcharts/highcharts_account.js'></script>
	<script type='text/javascript' src='/static/member/js/account.js'></script>
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
	<script language="javascript">
    var one_year_price = '<?=$one_year_price?>';
	
	var chart_data={
		
		"count": [ {
			"name": "本月销售曲线图",
			"data": [<?=implode(',',array_values($month_sales))?>],
			"type": "spline"
		}],
		"date": [<?=implode(',',array_keys($month_sales))?>]
	};
	var base_url = '<?=$base_url?>';
	var Users_ID = '<?=$Users_ID?>'; 
	var ranges  =  {
						'今日': [moment(), moment()],
						'昨日': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'本月': [moment().startOf('month'), moment().endOf('month')]
					};
	
	var input_total_pages = '<?= $input_info['total_pages'] ?>';
	var output_total_pages = '<?= $output_info['total_pages'] ?>';				
	
	$(document).ready(account_obj.home_init);	
	</script>
	</head>

	<body>
    <div id="wrap"> 
      <!-- tip begin -->
      
      	<div id="welcome"  >
        <p>欢迎你!&nbsp;&nbsp;<span id="Account_Name">
          <?=$rsUsers["Users_Account"]?>
          </span>&nbsp;&nbsp;<span id="Account_Data_Range">开通时间：<?php echo date("Y-m-d",$createtime) ?> 到期时间：<?php echo date("Y-m-d",$users_endtime) ?></span>&nbsp;&nbsp;
          <?php if($renewal_count == 0):?>
          <span id="Account_Type">[普通用户]</span>
          <?php endif;?>
          &nbsp;&nbsp; <a href="javascript:void(0)" id="level-up-btn" class="btn btn-sm btn-info">
          <?=$btn_text?>
          </a></p>
      </div>
      
      <!-- tip end --> 
      <!-- statistics begin -->
      
      	<dl id="statistics" class="list">
        
        <dd class="statis-item " id="all_order">
          <p> <span class="statis-name pull-left">今日所有订单</span> <span class="statis-num pull-right">
            <?=$today_all_order_num?>
            <img src="/static/member/images/accout_back/notebook.png"/></span> </p>
        </dd>
        <dd class="statis-item " id="payed_order">
          <p> <span class="statis-name pull-left">今日已付款订单</span> <span class="statis-num pull-right">
            <?=$today_payed_order_num ?>
            <img src="/static/member/images/accout_back/notebook2.png"/></span> </p>
        </dd>
        <dd class="statis-item " id="today_sales">
          <p> <span class="statis-name pull-left">今日销售额</span> <span class="statis-num pull-right">
            <?=$today_order_sales?>
            <img src="/static/member/images/accout_back/coin_stack.png"/></span> </p>
        </dd>
        <dd class="statis-item " id="month_sales">
          <p> <span class="statis-name pull-left">本月销售额</span> <span class="statis-num pull-right">
            <?=$month_order_sales?>
            <img  src="/static/member/images/accout_back/coin_and_paper.png"/></span> </p>
        </dd>
        <dd class="statis-item " id="today_bonus">
          <p> <span class="statis-name pull-left">今日支出佣金</span> <span class="statis-num pull-right">
            <?=round_pad_zero($today_output_money,2)?>
            <img  src="/static/member/images/accout_back/money_paper.png"/></span> </p>
        </dd>
        <dd class="statis-item " id="month_bonus">
          <p> <span class="statis-name pull-left">本月支出佣金</span> <span class="statis-num pull-right">
            <?=round_pad_zero($month_output_money,2)?>
            <img  src="/static/member/images/accout_back/money_bag.png"/></span> </p>
        </dd>
        <dd class="statis-item " id="today_new_dis_num">
          <p> <span class="statis-name pull-left">今日加入分销商</span> <span class="statis-num pull-right">
            <?=$today_new__account_num?>
            <img  src="/static/member/images/accout_back/user_add.png"/></span> </p>
        </dd>
        <dd class="statis-item" id="month_new_dis_num">
          <p> <span class="statis-name pull-left">本月加入分销商</span> <span class="statis-num pull-right">
            <?=$month_new_account_num?>
            <img  src="/static/member/images/accout_back/month_account.png"/></span> </p>
        </dd>
      </dl>
      
	  <div class="clearfix"></div>
      <!-- statistics end --> 
      
      <!-- 财务统计begin -->
      
      	
        <div class="col-md-12" id="sale-chart-panel">
        <div class="panel panel-default"> 
          <!-- Default panel contents -->
          <div class="panel-heading"><span class="fa fa-usd golden fz-20"></span>财务统计</div>
          <div class="panel-body"> 
            <!-- 日期选择表单 -->
            <form class="form-inline">
              <div class="form-group">
                <div class="input-group" id="reportrange">
                  <div class="input-group-addon "><span class="fa fa-calendar"></span></div>
                  <input type="text" class="form-control" id="reportrange-input" name="date-range-picker" value="<?=sdate($today)?>-<?=sdate($now)?>" placeholder="日期间隔">
                </div>
              </div>
			  <div class="form-group">
				<button type="button" class="btn btn-primary" id="input-record-search" >搜索</button>
			  </div>
			</form>
            <!-- 日期结束表单--> 
            
            <!-- 财务信息列表begin -->
            <div id="finacial_detail"> 
              
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#province" aria-controls="province" role="tab" data-toggle="tab">进账记录</a></li>
                <li role="presentation"><a href="#city" aria-controls="city" role="tab" data-toggle="tab">出账记录</a></li>
              </ul>
              
              <!-- Tab panes -->
              
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="province"> 
                  <!-- 进账记录begin -->
                  <div id="input-record-brief">
                    <p class="fz-16">时间：<span id="input-record-begin" class="red fz-16">
                      <?=ldate($Begin_Time)?>
                      </span>到<span id="input-record-end"class="red fz-16">
                      <?=ldate($End_Time)?>
                      </span> 共计：&yen;<span class="red fz-18" id="input-record-sum">
                      <?=$input_info['sum']?>
                      </span> </p>
                  </div>
                  <div id="input_record_table">
                    <?=$order_input_record_table?>
                  </div>
                  <div id="pagination_container">
                    <ul id="input_record_pagination" class="pagination-sm">
                    </ul>
                  </div>
                  
                  <!-- 进账记录end --> 
                </div>
                <div role="tabpane2" class="tab-pane" id="city"> 
                  
                  <!-- 出账记录begin -->
                  <div id="output-record-brief">
                    <p class="fz-16">时间：<span id="output-record-begin" class="red fz-16">
                      <?=ldate($Begin_Time)?>
                      </span>到<span id="output-record-end"class="red fz-16">
                      <?=ldate($End_Time)?>
                      </span> 共计：&yen;<span class="red fz-18" id="output-record-sum">
                      <?=$output_info['sum']?>
                      </span> </p>
                  </div>
                  <div id="output_record_table">
                    <?=$output_record_table?>
                  </div>
                  <div id="pagination_container">
                    <ul id="output_record_pagination" class="pagination-sm">
                    </ul>
                  </div>
                  
                  <!-- 出账记录end --> 
                  
                </div>
              </div>
            </div>
            <!-- 财务信息列表end --> 
            
          </div>
        </div>
      </div>
      	
      
      <!-- 财务统计end --> 
      
      <!-- sales chart begin  -->
    

        <div class="col-md-12" id="sale-chart-panel">
        <div class="panel panel-default"> 
          <!-- Default panel contents -->
          <div class="panel-heading"><span class="fa fa-bar-chart sky-blue fz-20"></span>月销售曲线图</div>
          <div class="panel-body">
            <div class="chart"> </div>
          </div>
        </div>
      </div>
  
     
      <!-- sales chart end --> 
    </div>
    
    <div id="confirm_renewal" class="lean-modal lean-modal-form">
    <div class="h">选择升级年限<span></span><a class="modal_close" href="#"></a></div>

    <form class="form"  id="renewal_form" method="post" action="/member/pay.php">
      <div class="rows">
        
       <p>
     升级年数： 
         
       	<?php foreach($price_list as $Qty=>$item):?>  
         <span id="<?=$Qty?>_<?=$item?>" class="qty_filter <?php if($Qty == 1){echo 'cur'; $cur_qty = $Qty;$cur_price=$item;} ?>">&nbsp;&nbsp&nbsp;&nbsp;<?=$Qty?>年&nbsp;&nbsp&nbsp;&nbsp;</span>
        <?php endforeach;?> 
       
        
        </p>
        <p>
        所需费用:&nbsp;&nbsp;<span class="fc_red" id="renewal_price"><?=$cur_price?></span>元
        </p>
        <p>
        为您节省:&nbsp;&nbsp;<span class="fc_red" id="renewal_sheng">0</span>元
        </p>
        
        <div class="clear"></div>
      </div>
      <div class="rows">
        <label></label>
        <span class="submit">
       	<input type="hidden" name="Qty" id="qty_val" value="<?=$cur_qty?>"/>
        <input type="hidden" name="Money" id="money_val" value="<?=$cur_price?>"/>
        
        <input type="submit" value="确定提交" name="submit_btn">
        </span>
        <div class="clear"></div>
      </div>
      
      <input type="hidden" name="UserID" value="<?=$_SESSION['Users_ID']?>">
      <input type="hidden" name="action" value="confirm_renewal">
    </form>
    <div class="tips"></div>
  </div>
</body>
</html>
