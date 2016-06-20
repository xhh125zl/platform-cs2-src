<?php
require_once('global.php');

$dis_agent_type = $rsConfig['Dis_Agent_Type'];

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

$record_type = array(1=>'合伙人',2=>'地区代理');
$header_title = '代理信息';
require_once('header.php');

?>
<body>
<link href="/static/api/distribute/css/withdraw.css" rel="stylesheet">
<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/shop/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">代理信息</h1>
</header>

<div class="wrap">
	<div class="container">
    
    	<div class="row">
        	 
             <div class="panel panel-default">
             	 <div class="panel-body">	
             
                 <?php if($dis_agent_type == 1):?>		
            		<h4 style="color:#F29611;">代理商类型:合伙人</h4>
      		  	 <?php else:?>
         			<h4 style="color:#F29611;">代理商类型:地区代理</h4>
        	 	 <?php endif;?>
                 
             <?php if($dis_agent_type == 2):?>		
				<?php if(!empty($dsAgentProvince)):?>
			   	省代地区:<span><?=implode(',',$dsAgentProvince)?></span>
				<?php endif;?>
			
				<?php if(!empty($dsAgentCity)):?>
					市代地区:<span><?=implode(',',$dsAgentCity)?></span>	
				<?php endif;?>
			
			
		 	 <?php endif;?>
         
                
                 
                 </div>
      			<?php if(!empty($record_list)):?>
	       			<table class="table">
      <caption>&nbsp;&nbsp;&nbsp;&nbsp;获得代理佣金记录</caption>
      	<tbody>
			<?php $i= 1;?>
        	<?php foreach($record_list as $key=>$record):?>
            <tr>
        	<th scope="row"><?=$i?></th>
        	<td><?=$record_type[$record['Record_Type']]?></td>
          	<td>&yen;<sapn class="red"><?=round_pad_zero($record['Record_Money'],2)?></span></td>
          	<td><?=sdate($record['Record_CreateTime'])?></td>
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

 
<?php require_once('../skin/distribute_footer.php');?> 
 
 
</body>
</html>

	