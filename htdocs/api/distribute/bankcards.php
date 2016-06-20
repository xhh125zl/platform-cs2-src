<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');

//银行账号列表
$rsMethods = $DB->Get("distribute_withdraw_methods","*","where Users_ID='".$UsersID."' and User_ID= '".$_SESSION[$UsersID.'User_ID']."'");
$method_list = $DB->toArray($rsMethods);

$header_title = '我的提现方式';
require_once('header.php');

?>
<body>
<link href="/static/api/distribute/css/cards.css" rel="stylesheet">
<script language="javascript">
	var base_url = '<?=$base_url?>';
	var UsersID = '<?=$UsersID?>';	
	$(document).ready(distribute_obj.bank_card_manage);
</script>

<header class="bar bar-nav">
  <a href="javascript:history.back()" class="fa fa-2x fa-chevron-left grey pull-left"></a>
  <a href="/api/<?=$UsersID?>/distribute/" class="fa fa-2x fa-sitemap grey pull-right"></a>
  <h1 class="title">我的提现方式</h1>
  
</header>

<div class="wrap">
 <div class="container">
    
  
  <div id="bank-card-list" class="row">
    <?php foreach($method_list as $key=>$item):?>
    <div class="item">
    
       <h1><i class="fa fa-credit-card grey"></i>&nbsp;&nbsp;<?=$item['Method_Name']?></h1>
       <p>
	   <span style="float:left"><?=$item['Account_Name']?></span><br/>
	   <?=$item['Account_Val']?></p>
       <?php if($item['Method_Type'] == 'bank_card'):?>
            <p><?=$item['Bank_Position']?></p>
       <?php endif;?>
       <p>
       
       <span style="float:right"><a class="remove-card"  data-method-id="<?=$item['User_Method_ID']?>" href="javascript:void(0)"><i class="red fa fa-remove"></i></a></span>
       </p>
    </div>
     <?php endforeach; ?>
  </div>
  </div>
</div>

 
<?php require_once('../shop/skin/distribute_footer.php');?> 
 
 
</body>
</html>

