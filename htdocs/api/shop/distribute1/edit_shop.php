<?php
require_once('global.php');
$before_edit_shop_name = $rsAccount['Shop_Name'];

if($_POST){	
	
	$data = array();
	if($rsConfig['Distribute_Customize'] == 1){
		$data["Shop_Name"] = $_POST["Shop_Name"];
	}	

	$data["Shop_Announce"] = $_POST["Shop_Announce"];
	$data["Is_Regeposter"] = 1;
	
	$condition = "Where Users_ID = '".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID'];	
	$Flag = $DB->Set('shop_distribute_account',$data,$condition);
	
	if($Flag){
		header("location:".$shop_url);
	}
}
$header_title = '修改资料';
require_once('header.php');
?>
<body>
<link href="/static/api/distribute/css/edit_info.css" rel="stylesheet">
<script type="text/javascript">
	$(document).ready(function(){
		distribute_obj.init();
	});
</script>

<div class="wrap">
	<div class="container">
    	<div class="row page-title">
           <h4>&nbsp;&nbsp;&nbsp;&nbsp;修改资料</h4>
        </div>
		<div class="row">
        	<ul class="list-group" id="edit_info_panel">
  <form method="post" action="/api/<?=$UsersID?>/shop/distribute/edit_shop/"  id="edit_shop_form">
  <?php if($rsConfig['Distribute_Customize'] == 1): ?>
  <li class="list-group-item" >
  	 <label>店名</label>&nbsp;&nbsp;<input type="text" name="Shop_Name" value="<?=$rsAccount['Shop_Name']?>" placeholder="请输入您的店名" />
  </li>
  <?php endif;?>
  
	<li class="list-group-item" >
    <label id="annoce-label">自定<br />义分<br />享语&nbsp;&nbsp;&nbsp;&nbsp;</label>
    
    <textarea id="annoce-content" name="Shop_Announce"><?=$rsAccount['Shop_Announce']?></textarea>
  
  	
  </li>
 	<li class="list-group-item  text-center">

     <input type="submit" value="修改资料" class="btn btn-default" id="submit-btn"/>
  </li>


 </form>
     
</ul>
        
        </div>

    </div>
    
  	
  
    
</div>

<?php require_once('../skin/distribute_footer.php');?> 
 
 
</body>
</html>
