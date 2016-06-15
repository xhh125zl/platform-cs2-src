<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/compser_library/Protitle.php');

//会员信息
$front_title = get_dis_pro_title($DB,$UsersID);

$dis_config = Dis_Config::where('Users_ID',$UsersID)->first();

$user_consue = Order::where(array('User_ID'=>$_SESSION[$UsersID.'User_ID']))
->where('Order_Status','>=', $dis_config->Pro_Title_Status)
->sum('Order_TotalPrice');

$ProTitle = new ProTitle($UsersID, $_SESSION[$UsersID.'User_ID']);
//$user_count = Order::where(array('Owner_ID'=>$_SESSION[$UsersID.'User_ID'],'Order_Status'=>4))->sum('Order_TotalPrice');
//自身销售额(直接下级普通用户)
$user_count = Order::where(array('Owner_ID'=>$_SESSION[$UsersID.'User_ID']))
->where('Order_Status','>=', $dis_config->Pro_Title_Status)
->sum('Order_TotalPrice');

//团队销售额计算使用
$Sales_Group_2 = $user_count;

//获取所有直接下级分销商用户销售额
$sess_userid = $_SESSION[$UsersID.'User_ID'];
$sons_dis_userid = $ProTitle->get_sons_dis_userid($sess_userid);
$user_dis_sale_count = 0;
if ($sons_dis_userid) {
    $user_dis_sale_count = Order::where('Order_Status', '>=', $dis_config->Pro_Title_Status)
    ->whereIn('Owner_ID', $sons_dis_userid)
    ->sum('Order_TotalPrice');

    $user_count = $user_count + $user_dis_sale_count;

}
unset($sons_dis_userid);

//团队销售额

$childs = $ProTitle->get_sons($dis_config->Dis_Level,$_SESSION[$UsersID.'User_ID']);
if(!empty($childs)){
	$Sales_Group = Order::where('Order_Status', '>=', $dis_config->Pro_Title_Status)
 						->whereIn('Owner_ID',$childs)
 						->sum('Order_TotalPrice');
}else{
	$Sales_Group = 0;
}
//修正团队销售客未包含“自身消费额”和“自身销售额”,而在"自身销售额"里已经包含过了 自身消费额，所以直接加上“自身销售额”就可以了。
$Sales_Group = $Sales_Group + $Sales_Group_2;

$ex_bonus = array(
	"total"=>0,
	"pay"=>0,
	"payed"=>0
);
$DB->Get("distribute_account_record","Nobi_Money,Record_Status","where User_ID=".$_SESSION[$UsersID.'User_ID']." and Nobi_Money>0");
while($r=$DB->fetch_assoc()){
	if($r["Record_Status"]==2){
		$ex_bonus["payed"] += $r["Nobi_Money"];
	}else{
		$ex_bonus["pay"] += $r["Nobi_Money"];
	}
	$ex_bonus["total"] += $r["Nobi_Money"];
}

$header_title = '爵位晋升';
$ProTitle->up_nobility_level();
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
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
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
    </h4></p>
     
	 <p>自身消费额:&nbsp;&nbsp;&yen;<span class="red"><?=$user_consue?></span></p>
	 <p>自身销售额:&nbsp;&nbsp;&yen;<span class="red"><?=$user_count?></span></p>
     <p>团队销售额:&nbsp;&nbsp;&yen;<span class="red"><?=$Sales_Group?></span></p>	  
	  
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
				<th>自身消费额</th>
				<th>自身销售额</th>
				<th>团队销售额</th>
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
            <td><span><?=$item['Sales_Self']?></span></td>
            <td><?=$item['Sales_Group']?></td>
			<td><span class="label label-info"><?=$item['Bonus']?>%</span></td>
          </tr>
           <?php endforeach;?>
        </tbody>
      </table>
    	
    </div>
  </div>
</div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>

