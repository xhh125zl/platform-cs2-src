<?php require_once('top.php'); ?>
<body style="" id="loadingPicBlock" class="g-acc-bg">
<link href="/static/api/cloud/css/goodsrecords.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='/static/api/shop/js/toast.js'></script> 
<script type='text/javascript' src='/static/api/cloud/js/category.js'></script>
<script>
var cloud_url = '<?php echo $cloud_url;?>';
var OwnerID = <?php echo $owner['id'];?>;
var cid = <?php echo $CategoryID;?>;
var ActiveID = <?=isset($_SESSION[$UsersID.'_CurrentActive'])?$_SESSION[$UsersID.'_CurrentActive']:0 ?>;
var BizID = <?=isset($_SESSION[$UsersID.'_CurrentBiz'])?$_SESSION[$UsersID.'_CurrentBiz']:0 ?>;
var category = 'category';
$(document).ready(function(){
	category_obj.category_init();
});
</script>
<div class="column"> 
	<a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a>
    <h2 style="text-align: center;height: 37px;line-height: 37px;color: #999;"><?php echo empty($CategoryID) ? '所有产品' : $rsCategory['Category_Name'];?><h2>
</div>
<div class="marginB">
	<div class="goodList">
	</div>
	<div class="loading clearfix" style="display:none;" page="1">加载更多</div>
</div>
<?php require_once('footer.php');?>
</body>
</html>