<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/distribute.php');
$header_title = '修改头像';
require_once('header.php');
?>
<body>
<link href="/static/api/distribute/css/edit_info.css" rel="stylesheet">
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/api/shop/js/shop.js'></script>
<script language="javascript">
	$(document).ready(shop_obj.page_init);
</script>

<div class="wrap">
	<div class="container">
    	<div class="row page-title">
           <h4>&nbsp;&nbsp;&nbsp;&nbsp;修改头像</h4>
        </div>
		<div class="row">
        	<ul class="list-group" id="edit_info_panel">
            
  			<form action="/api/shop/distribute/upmobile.php?TableField=headimg&UsersID=<?php echo $UsersID;?>" method="post" enctype="multipart/form-data" target="_blank">
  			<li class="list-group-item" style="text-align:center">
             <img id="hdImg" src="<?php echo $rsAccount["Shop_Logo"] ? $rsAccount["Shop_Logo"] : '/static/api/images/user/face.jpg'?>"/><br /><span style="display:block; width:100%; text-align:center; padding-top:10px; color:#999; font-family:'宋体'; font-size:12px">注：头像尺寸100*100 (gif, jpg, jpeg, png)</span>
            </li>
            <li class="list-group-item  text-center">
                <div style="position:relative; width:80%; height:34px; margin:0px auto">
                 <div style="width:100%; height:34px; padding:0px; margin:0px; font-size:14px; line-height:34px; text-align:center; color:#FFF; cursor:pointer; background:#3396FE; border:none">上传头像</div>
                 <input type="file" style="position:absolute; top:0; left:0; height:34px; filter:alpha(opacity:0);opacity: 0;width:100%; cursor:pointer" name="upthumb" onchange="this.form.submit();" />
                </div>
  			</li>
 			</form>
		</ul>
        </div>
    </div>
</div>

<?php require_once('../skin/distribute_footer.php');?> 
 
 
</body>
</html>
