<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>

<?php
echo $rsConfig["ShopName"];
?>

</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/api/shop/skin/default/style.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href="/static/api/cloud/css/comm.css" rel="stylesheet" type="text/css">
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/cloud/js/shop.js?t=<?php echo time();?>'></script>
<script language="javascript">
var UsersID = '<?php echo $UsersID;?>';
$(document).ready(shop_obj.page_init);
</script>
</head>
