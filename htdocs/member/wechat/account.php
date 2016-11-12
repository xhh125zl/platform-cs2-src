<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
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
	
	$carbon = Carbon\Carbon::createFromTimestamp(1440055725);
	
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
	$today_payed_order_num = $order->statistics($Users_ID,'num',$today,$now,2);
	//今日销售额
	$today_order_sales = $order->statistics($Users_ID,'sales',$today,$now,2);
	//本月销售额
	$month_order_sales = $order->statistics($Users_ID,'sales',$month_start,$month_end,2);
	//今日支出佣金
	$today_output_money = $account_record->recordMoneySum($Users_ID,$today,$now);
	//本月支出佣金

	$month_output_money = $account_record->recordMoneySum($Users_ID,$month_start,$month_end,1);

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
					   ->where('Order_Status',4)
					   ->get(array('Order_ID','Order_TotalAmount','Order_CreateTime'));
	
	
	//统计每日销量
	$max_val = 0;	
	if($month_order_list->count()>0){
		$order_array = 	$month_order_list->toArray();
		$new_collect = collect($order_array);
		foreach($month_days_range as $key => $day){
			
		   $sum = $new_collect->filter(function($order) use($day){
					if($order['Order_CreateTime'] >$day['begin']&&$order['Order_CreateTime'] <= $day['end']){
						return true;
					}
				})->sum('Order_TotalAmount');
			if($sum>$max_val){
				$max_val = $sum;
			}
			$month_sales[$key] = $sum;
		}
	}
	
	
	
   
	$chart_max_val = 100000;
	if($max_val > 100000){
		$unit = 25000;
		$nums =$max_val/$unit;
        $max_nums = ceil($nums);
		$chart_max_val =  $max_nums*$unit;		
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

	<script type='text/javascript' src='/static/member/js/account.js'></script>
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
	</head>
<style>
.chart{line-height:30px;}
.chart a{margin-right:10px;font-size:14px;}
</style>
	<body>
    <div id="wrap"> 
      <!-- tip begin -->
      <!--
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
      -->
      <!-- tip end --> 
      <!-- statistics begin -->
      
      	
      
	  <div class="clearfix"></div>
      <!-- statistics end --> 
      
    
      
      <!-- sales chart begin  -->
    

        <div class="col-md-12" id="sale-chart-panel">
        <div class="panel panel-default"> 
          <!-- Default panel contents -->
          <div class="panel-heading"><span class="fa fa-bar-chart sky-blue fz-20"></span>快捷导航</div>
          <div class="panel-body">
            <div class="chart">
            <a href="/member/shop/products.php">产品列表</a> <a href="/member/shop/category.php">产品分类</a><a href="/member/shop/category_add.php">+添加分类</a><br />
<div><a href="/member/biz/apply_config.php" target="iframe">入驻描述设置</a>
					<a href="/member/biz/apply_other.php" target="iframe">年费设置</a>
					<a href="/member/biz/apply.php" target="iframe">入驻资质审核列表</a>
					<a href="/member/biz/authpay.php" target="iframe">入驻支付列表</a>
					<a href="/member/biz/chargepay.php" target="iframe">续费支付列表</a>
					<a href="/member/biz/bond_back.php" target="iframe">保证金退款</a></div>
          <div><a href="/member/biz/announce.php" target="iframe">公告管理</a> <a href="/member/biz/announce_add.php">+添加公告</a></div>

<div><a href="/member/biz/send_push.php" target="iframe">推送消息</a>
<a href="/member/biz/msg_push.php" target="iframe">推送消息管理</a></div>

			      	<div><a href="/member/shop/articles.php" target="iframe">文章管理</a><a href="/member/shop/articles_category.php" target="iframe">分类管理</a><a href="/member/shop/articles_category_add.php">+添加分类</a></div>

<div><a href="/member/yijipay/trans_yijipay_batch.php" target="iframe">批量转账</a><a href="/member/yijipay/mypay.php" target="iframe">我的钱包</a></div>


            </div>
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
