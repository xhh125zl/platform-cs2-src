<?php
require_once('global.php');

$all_distribute_count = $self_distribute_count = $posterity_distribute_count = 0;

//购买或升级分销级别自销
$r = $DB->GetRs('distribute_order_record','count(Order_ID) as num','where Owner_ID='.$User_ID.' and User_ID='.$User_ID);
$self_distribute_count = $r['num'];

//下级销售次数
$posterity_temp = $accountObj->getPosterity($rsConfig['Dis_Level']);
$posterityids = array();
if (count($posterity_temp) > 0) {
	$posterityids = $posterity_temp->map(function ($node) {
		return $node->User_ID;
	})->toArray();
}

if(!empty($posterityids)){	
	$r = $DB->GetRs('distribute_order_record','count(Order_ID) as num','where Owner_ID in('.implode(',',$posterityids).') and User_ID='.$User_ID);
	$posterity_distribute_count = $r['num'];
}

$all_distribute_count = $self_distribute_count + $posterity_distribute_count;

$distribute_record = array();
$page_url = $distribute_url.'buylevellist/';
if($_GET['filter'] != 'all'){
	
	if($_GET['filter'] == 'self'){
		$page_url .= 'self/';
		$DB->getPage('distribute_order_record','*','where Owner_ID='.$User_ID.' and User_ID='.$User_ID,10);
		while($r = $DB->fetch_assoc()){
			$distribute_record[] = $r;
		}
	}elseif($_GET['filter'] == 'down'){
		$page_url .= 'down/';
		if(!empty($posterityids)){
			$DB->getPage('distribute_order_record','*','where Owner_ID in('.implode(',',$posterityids).')'.' and User_ID='.$User_ID,10);
			while($r = $DB->fetch_assoc()){
				$distribute_record[] = $r;
			}
		}			
	}
}else{
	$page_url .= 'all/';
	$posterityids[] = $User_ID;
	$DB->getPage('distribute_order_record','*','where Owner_ID in('.implode(',',$posterityids).')'.' and User_ID='.$User_ID,10);
	while($r = $DB->fetch_assoc()){
		$distribute_record[] = $r;
	}
}

$header_title = '分销账户明细';
require_once('header.php');
?>

<body>
<link href="/static/api/distribute/css/detaillist.css" rel="stylesheet">

<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="<?=$distribute_url;?>" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">分销账户明细</h1>
  
</header>
<div style="height:50px; line-height:50px; width:100%; background:#FFF; border-bottom:1px #dfdfdf solid">
	<a href="<?php echo $distribute_url;?>detaillist/all/" style="display:block; width:50%; height:50px; line-height:50px; text-align:center; font-size:16px; float:left; box-sizing:border-box; border-right:1px #dfdfdf solid;">商城明细</a>
	<a href="<?php echo $distribute_url;?>buylevellist/all/" style="display:block; width:50%; height:50px; line-height:50px; text-align:center; font-size:16px; float:left; color:#F60">购买级别</a>
	<div style="clear:booth"></div>
</div>
<div class="wrap">
	<div class="container">
    	
	
        <div class="row">
        <ul id="distribute-brief-info">
         <li class="item"><a href="<?php echo $distribute_url;?>buylevellist/all/"><span class="red bold">&nbsp;<?=$all_distribute_count?></span><br/>全部</a></li>
         <li class="item"><a href="<?php echo $distribute_url;?>buylevellist/self/"><span class="red bold">&nbsp;<?=$self_distribute_count?></span><br/>自销</a></li>
         <li class="item"><a href="<?php echo $distribute_url;?>buylevellist/down/"><span class="red bold">&nbsp;<?=$posterity_distribute_count?></span><br/>下级分销</a></li>
         <li class="clearfix"></li>
      </ul>
      
      <ul class="list-group" id="record-panel">
    
      	<?php foreach($distribute_record as $key=>$item):?>
        <li class="list-group-item">
        	<p class="record-description">
			<?=$item['Record_Description']?>&nbsp;&nbsp;<span class="red">&yen;(<?=round_pad_zero($item['Record_Money'],2)?>)</span>
			
                     
            </p>
            <p class="record-description" ><?=ldate($item['Record_CreateTime'])?>&nbsp;&nbsp;&nbsp;&nbsp;</p>
            <p >
             <?php 
			 	if($item['Record_Status'] == 0){
					echo '进行中';
				}else{
					echo '已完成';
				}
			 ?>
             </p>
             
              
        </li>
        <?php endforeach;?>
      </ul>   
      
        </div>
<?php $DB->showWechatPage($page_url); ?>
    </div>
    
  	
  
    
</div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>
