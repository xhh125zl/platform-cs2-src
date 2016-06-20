<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

//会员信息
$front_title = get_dis_pro_title($DB,$UsersID);

$user_consue = Order::Multiwhere(array('User_ID'=>$_SESSION[$UsersID.'User_ID'],'Order_Status'=>'4'))->sum('Order_TotalPrice');
$user_count = Dis_Account::where('invite_id',$_SESSION[$UsersID.'User_ID'])->count();
$ex_bonus = array(
	"total"=>0,
	"pay"=>0,
	"payed"=>0
);
$DB->Get("shop_distribute_account_record","Nobi_Money,Record_Status,Nobi_Status","where User_ID=".$_SESSION[$UsersID.'User_ID']." and Nobi_Money>0");
while($r=$DB->fetch_assoc()){
	if($r["Record_Status"]==2){
		$ex_bonus["payed"] += $r["Nobi_Money"];
	}else{
		$ex_bonus["pay"] += $r["Nobi_Money"];
	}
	$ex_bonus["total"] += $r["Nobi_Money"];
}

$header_title = '爵位晋升';
require_once('header.php');
?>
<body>
<link href="/static/api/distribute/css/protitle.css" rel="stylesheet">
<script language="javascript">
	var base_url = '<?=$base_url?>';
	var UsersID = '<?=$UsersID?>';
	$(document).ready(distribute_obj.pro_file_init);
</script>

<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/shop/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">我的称号</h1>
  
</header>

<div class="wrap">
 <div class="container">
    
  
  	<div class="row">
      
    	
        <div class="panel panel-default">
  <!-- Default panel contents -->
 
  <div class="panel-body">
    
    <p><h4 style="color:#F29611;">
	<?php if(!empty($rsAccount['Professional_Title'])&&!empty($front_title[$rsAccount['Professional_Title']])):?>
		<?php if(!empty($front_title[$rsAccount['Professional_Title']]['ImgPath'])):?><img src="<?=$front_title[$rsAccount['Professional_Title']]['ImgPath']?>" /><?php endif;?> <?=$front_title[$rsAccount['Professional_Title']]['Name']?>
    <?php else:?>
       暂无爵位
	<?php endif;?>
    </h4>
     
	 <p>消费额:&nbsp;&nbsp;&yen;<span class="red"><?=$user_consue?></span></p>
	 <p>直销人数:&nbsp;&nbsp;<span class="red"><?=$user_count?></span></p>
     <p>团队人数:&nbsp;&nbsp;<span class="red"><?=$rsAccount['Group_Num']?></span></p>	  
	  
	  <?php if($ex_bonus["total"]){?>
		<p>总奖金:&nbsp;&nbsp;<span class="red">&yen;<?=$ex_bonus["total"]?></span></p>
		<p>已有 <span class="red">&yen;<?=$ex_bonus['payed']?></span> 发放到您的可提现佣金中</p>
	  <?php }else{?>	  
		<p class="red">目前无奖金!!!</p>  
	  <?php }?>
  </div>
        
 
		
	
		
		<table class="table">
        <thead>
          <tr>
           	<th>#</th>
				<th>爵位</th>
				<th>消费额</th>
				<th>直销人数</th>
				<th>团队人数</th>
				<th>奖励百分比</th>
          </tr>
        </thead>
        <tbody>
		  <?php 
		  foreach($front_title as $key=>$item):?>	
          <tr>
            <td scope="row"><?=$key?></td>
            <td><?=$item['Name']?></td>
            <td><span class="red">&yen;<?=$item['Consume']?></span></td>
            <td><span><?=$item['Saleroom']?></span></td>
            <td><?=$item['Group_Num']?></td>
			<td><span class="label label-info"><?=$item['Bonus']?>%</span></td>
          </tr>
           <?php endforeach;?>
        </tbody>
      </table>
    	
    </div>
  </div>
</div>

 
<?php require_once('../skin/distribute_footer.php');?> 
 
 
</body>
</html>

