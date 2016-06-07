<?php
require_once('global.php');
$DB->Get("shop_fanben_record","*","where Users_ID='".$UsersID."' and User_ID=".$User_ID);
$lists = array();
while($r = $DB->fetch_assoc()){
	$lists[] = $r;
}
$header_title = '返本明细';
require_once('header.php');
?>

<body>
<link href="/static/api/distribute/css/detaillist.css" rel="stylesheet">

<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/shop/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">返本明细</h1>
  
</header>

<div class="wrap">
	<div class="container">
    	
	
        <div class="row">
              
      <ul class="list-group" id="record-panel">
    
      	<?php foreach($lists as $key=>$item):?>
        <li class="list-group-item">
        	<p class="record-description">
			<?=$item['Note']?>&nbsp;&nbsp;<span class="red">&yen;(<?=round_pad_zero($item['Record_Money'],2)?>)</span>
			
                     
            </p>
            <p class="record-description" ><?=ldate($item['CreateTime'])?>&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </li>
        <?php endforeach;?>
      </ul>   
      
        </div>

    </div>
    
  	
  
    
</div>

 
<?php require_once('../skin/distribute_footer.php');?> 
 
 
</body>
</html>
