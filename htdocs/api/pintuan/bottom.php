<?php 
    $request_uri = $_SERVER['REQUEST_URI'];
    $first_parm = basename(dirname($request_uri));
    $end_parm = basename($request_uri);
    $isDefault = 'index';
    if($first_parm == 'pintuan' && stripos($end_parm,'act') !==false) { //首页
        $isDefault = 'index';
    }else if (in_array($first_parm, [ 'seach','sousuo' ])) {
        $isDefault = 'search';
    }else{
        $isDefault = 'user';
    }
?>
<div class="kb"></div>
<div class="clear"></div>
<div class="cotrs">
	<a href="<?=isset($_SESSION["Index_URI"])?$_SESSION["Index_URI"]:'' ?>"
		<?=$isDefault == 'index' ? 'class="thisclass"':'' ?>><img
		src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br />首页</a>
	<a href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"
		<?=$isDefault == 'search' ? 'class="thisclass"':'' ?>><img
		src="/static/api/pintuan/images/002-2.png" width="22px" height="22px"
		style="margin-top: 3px;" /><br />搜索</a> <a
		href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"
		<?=$isDefault == 'user' ? 'class="thisclass"':'' ?>><img
		src="/static/api/pintuan/images/002-3.png" width="22px" height="22px"
		style="margin-top: 3px;" /><br />我的</a>
</div>