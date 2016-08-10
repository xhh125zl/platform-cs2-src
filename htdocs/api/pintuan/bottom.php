<div class="kb"></div>
<div class="clear"></div>
<div class="cotrs">
	<a href="<?=isset($_SESSION["Index_URI"])?$_SESSION["Index_URI"]:'' ?>" class="thisclass"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br />首页</a>
	<a href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"><img src="/static/api/pintuan/images/002-2.png" width="22px" height="22px" style="margin-top: 3px;" /><br />搜索</a>
	<a href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/002-3.png" width="22px" height="22px" style="margin-top: 3px;" /><br />我的</a>
</div>