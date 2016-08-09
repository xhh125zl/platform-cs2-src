<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

	$UserID=$_SESSION[$UsersID.'User_ID'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>我的收藏</title>
</head>
<link href="/static/api/pintuan/css/shoucang.css" rel="stylesheet" type="text/css">
<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/static/api/pintuan/js/jquery.1.4.2-min.js"></script>
<script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script>
<body>
<div class="dingdan">
<span class="fanhui l"><a href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
<span class="querendd l">我的收藏</span>
<div class="clear"></div>
</div>

		<?php
			$DB->query("SELECT * FROM `pintuan_collet` where users_id='".$UsersID."'and userid ='".$UserID."'");
			$ress=$DB->toArray();
			foreach ($ress as $k => $v) {
				$DB->query("SELECT * FROM `pintuan_products` where users_id='".$UsersID."'and Products_ID ='".$v['productid']."'");
				$result=$DB->toArray();
				foreach ($result as $key => $val) {
							echo '<div class="chanpin">';
							echo '<div class="chanpin1">
							         <span class="chanpin3 l"><img src="'.json_decode($val['Products_JSON'],true)['ImgPath']['0'].'"></span>
							         <span class="cp l">'.$val['Products_Name'].'</span>
							         <span class="sc l"><input class="ssi" type="hidden" name="pid" value="'.$val['Products_ID'].'"><img class="image" src="/static/api/pintuan/images/index-1_18_1_18.png" width="14" height="16">
							         </span>
							         <div class="clear"></div>
    						 </div>';
									if($val['pintuan_type']==0){
										echo '<div class="futime2">
									        <span class="fuk1 r"><a href="/api/'.$UsersID.'/pintuan/xiangqing/'.$val['Products_ID'].'/">立即开团</a></span>
									        <span class="fuk r" style="border-width: 0px;color: #333;">'.$val['people_num'].'人团</span>
				   			    </div>';
									}else{
										echo '<div class="futime3">活动已结束</div> ';
									}
							echo '</div>';
							echo '<div class="clear"></div>';
				}
			}
		?> 

<?php include 'bottom.php';?>              
</body>
	<script type="text/javascript">
			$('.sc').click(function(){
				$(this).parents('.chanpin').remove();
				var goodsid=$(this).find(".ssi").val();
				var userid="<?php echo $UserID;?>";
				var usersid="<?php echo $UsersID; ?>";
				var orther ="10002";
				$.post('/api/<?php echo $UsersID;?>/pintuan/ajax/',{UsersID:usersid,userid:userid,goodsid:goodsid,orther:orther},function(data){		  
					var warning=data.msg;
					console.log(warning);
			  	}, 'json');
			})
	</script>
</html>
