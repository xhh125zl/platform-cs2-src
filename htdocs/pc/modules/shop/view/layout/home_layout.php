<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $output['title'];?></title>
</head>
<body>
<script>
    var SITEURL = '<?php echo $output['_site_url'];?>';
    var UsersID = '<?php echo $output['UsersID'];?>';
	var shop_ajax_url = '<?php echo url('ajax/index');?>';
</script>
<?php require_once(__DIR__ . '/common.php');?>
<?php if($output['_controller'] == 'store'){?>
    <style>
	    .main_nav_bg,.w_bg {display:none;} 
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.main_nav_bg,.w_bg').remove();
		});
	</script>
<?php }?>
</body>
</html>