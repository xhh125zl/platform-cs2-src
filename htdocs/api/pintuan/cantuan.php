<?php 
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

    $goodsid = $_GET['productid'];
    $goodsInfo = $DB->GetRs("pintuan_products","*","WHERE products_id = {$goodsid}");

    $parameter = json_decode(htmlspecialchars_decode($goodsInfo["Products_Parameter"]), true);
    $images = json_decode(htmlspecialchars_decode($goodsInfo["Products_JSON"]), true)['ImgPath']['0'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>参团</title>
	<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="w">
	<div class="fanhui_bj">
		<span class="fanhui l"><a href="javascript:history.go(-1);"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
		<span class="querendd l">拼团中</span>
		<div class="clear"></div>
	</div>
	<div class="chanpin1">
        <span class="l"><img style="width:100px;height: 100px;" src="<?php echo empty($images) ? '' : $images; ?>"></span>
        <span class="cp l" style="width:50%;">
            <ul>
                <li><strong><?php echo $goodsInfo['Products_Name']; ?></strong></li>
                <li>数量：1</li>
                <li>
                  <?php echo $goodsInfo['Products_BriefDescription']; ?>
                </li>
            </ul>
        </span>
        <span class="jiage r"><?php echo $goodsInfo['Products_PriceT']; ?>/件</span>
        <div class="clear"></div>
    </div>
	<div class="pintuan">
		<?php 
			$sql1 = "select t.productid,t.id,t.teamnum,t.teamstatus,t.userid,u.User_NickName,u.User_Mobile,u.User_HeadImg,u.User_Area,p.starttime,p.stoptime,p.people_num from pintuan_team t left join user u on t.userid=u.user_id LEFT JOIN pintuan_products AS p ON t.productid=p.Products_ID  where t.productid={$goodsid} order by t.addtime desc";
			$result1 = $DB->query($sql1);
			$teams = array();
			while ($res = $DB->fetch_assoc($result1)) {
				$teams[] = $res;
			}
            
			foreach ($teams as $item) {
		?>
		<div class="pint">
			<div class="bjt">
				<div class="bjt-img"><img style="width:50px;height:50px;" src="<?php echo $item['User_HeadImg']; ?>"></div>
			</div>
			<div class="bjx">
				<div class="bjxt">
					<span class="l"><?php echo empty($item['User_NickName']) ? $item['User_Mobile'] : $item['User_NickName']; ?></span>
					<?php $num = $item['people_num'] - $item['teamnum'];
						if ($num > 0) {
							echo "<span class='bjxt1 r'>还差{$num}人成团</span>";
						}
					?>
					<div class="clear"></div>
					<span class="bjxt2  l"><?php echo $item['User_Area'];?></span>
					<span class="bjxt2 r">
					<?php 
            		 $time=time();

						if ($item['stoptime']>=$time) {
							echo  date('Y-m-d', $item['stoptime']).'结束';
						} else {    
							echo '已结束';
						}
					?>
					</span>
				</div>
			</div>
			<div class="bjtp">
				<?php if ($time >= $item['starttime'] && $time <= $item['stoptime'] && $item['teamstatus'] == 0) {
						echo '<a href="/api/'.$UsersID.'/pintuan/teamdetail/'.$item['id'].'/">去参团</a>';
					} else {
						  if($team['teamstatus']==1){
                   echo "<a>已完成</a>";   
              }else{
                   echo '<a>已结束</a>';
              }
					}
				 ?>
			</div>
		</div>
		<div class="clear"></div>
		<?php } ?>
	</div>
</div>
<div style="height:70px;"></div>
<div class="cotrs">
  <a id="f1" href="<?php echo "/api/$UsersID/pintuan/"; ?>" class="thisclass"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br/>首页</a>
  <a id="f2" href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"><img src="/static/api/pintuan/images/002-2.png" width="25px" height="25px" /><br/>搜索</a>
  <a id="f3" href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/002-3.png" width="25px" height="25px" /><br/>我的</a>
</div> 
</body>
</html>