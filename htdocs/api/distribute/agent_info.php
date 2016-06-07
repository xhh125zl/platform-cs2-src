<?php
require_once('global.php');

$dsAgentArea = $accountObj->disAreaAgent()->with('area')->getResults()->toArray();
$dsAgentProvince = $dsAgentCity = array();

foreach($dsAgentArea as $key=>$AgentArea){
	if($AgentArea['area']['area_deep'] == 1){
		$dsAgentProvince[] = $AgentArea['area_name'];
	}else{
		$dsAgentCity[] = $AgentArea['area_name'];
	}
}

$Total_Agent_Money = Dis_Agent_Record::multiWhere(array('Users_ID'=>$UsersID,'Account_ID'=>$rsAccount['Account_ID']))
                                      ->sum('Record_Money');
$records = Dis_AGent_Record::multiWhere(array('Users_ID'=>$UsersID,'Account_ID'=>$rsAccount['Account_ID']))
                                      ->get();

$record_list = array();				
if(!empty($records)){
	$record_list = $records->toArray();
}

$record_type = array(1=>'合伙人',2=>'地区代理',3=>'城市代理');
$header_title = '代理信息';
require_once('header.php');

?>
<body>
<link href="/static/api/distribute/css/withdraw.css" rel="stylesheet">
<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">获得区域代理奖金记录</h1>
  <style type="text/css">
  .hide_box { display: none; position: absolute; z-index: 100; top: 50%; left: 50%; background: #ccc; width: 260px; margin-left: -130px; margin-top: -132px; }
  .hide_box h3 { display: block; overflow: hidden; height: 40px; line-height: 40px; background: #19468b; font-size: 16px; color: #FFF; text-align: center; padding: 0; margin: 0; }
  .hide_box ul { padding: 10px; }
  .hide_box ul li { height: 40px; line-height: 40px; display: block; overflow: hidden; border-bottom: 1px dotted #ccc; }
  .mask { display: none; position: fixed; top: 0; bottom: 0; left: 0; right: 0; z-index: 9; background: rgba(0, 0, 0, .5); }
  </style>
</header>
<script type="text/javascript">
  $(document).ready(function(){
    $('.show_detail').click(function(){
      $(this).next('.hide_box').show();
      $('.mask').show();
    });
    $('.mask').click(function(){
      $('.hide_box').hide();
      $(this).hide();
    })
  });
</script>
<div class="wrap">
	<div class="container">
    
    	<div class="row">
        	 
             <div class="panel panel-default" style="width:97%; margin:10px auto;">
      			<?php if(!empty($record_list)):?>
	       			<table class="table">
      <caption>&nbsp;&nbsp;&nbsp;&nbsp;获得代理佣金记录</caption>
      	<tbody>
			<?php $i= 1;?>
        	<?php foreach($record_list as $key=>$record):?>
            <tr>
        	<th scope="row"><?=$i?></th>
          	<td>&yen;<sapn class="red"><?=round_pad_zero($record['Record_Money'],2)?></span></td>
          	<td><?=sdate($record['Record_CreateTime'])?></td>
            <td><a href="javascript:void(0);" class="show_detail">详细</a>
            <div class="hide_box">
              <h3>佣金详细</h3>
              <ul>
                <li><span>订单编号：</span><span><?php echo date("Ymd",$record["Order_CreateTime"]).$record['Order_ID']; ?></span></li>
                <li><span>商品名称：</span><span><?php  echo $record['Products_Name']; ?></span></li>
                <li><span>购买数量：</span><span><?php  echo $record['Products_Qty']; ?></span></li>
                <li><span>商品价格：</span><span><?php  echo $record['Products_PriceX']; ?></span></li>
                <li><span>区域代理佣金比例：</span><span><?php  echo $record['area_Proxy_Reward'].'%'; ?></span></li>
              </ul>
            </div>
            </td>
            
        	</tr>
			<?php $i++; ?>
   			<?php endforeach; ?> 
  		    </tbody>
    	</table>
        		<?php else:?>
					<ul style="margin-top:10px;" class="list-group">
				<li class="list-group-item">
				<span class="red">暂无代理佣金记录</span>
				</li>
			</ul>
     		   <?php endif;?>  
             
             </div>
        
        </div>
    

    </div>

</div>
<div class="mask"></div>
 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>

	