<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $rsConfig["Zhuli_Name"];?></title>
<link href='/static/css/global.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<link href='/static/api/zhuli/css/zhuli.css?t=<?php echo time();?>' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/js/global.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/zhuli/js/zhuli.js?t=<?php echo time();?>'></script>
<script language="javascript">
var UsersID = '<?php echo $UsersID;?>';
var ActID = '<?php echo $actid;?>';
$(document).ready(zhuli_obj.zhuli_init);
</script>
</head>

<body>
<div class="banner"><img src="<?php echo $rsConfig["Banner"];?>" ></div>
<div class="main">
 <img src="/static/api/zhuli/images/main_bg.jpg" class="main_bg" />
 <div class="my_score"><?php echo $my_score;?></div>
 <div class="my_rank">当前的排名：第<?php echo $my_rank;?>名</div>
 <?php if($myact==1){?>
 <div class="main_btn" id="share_zhuli"><img id="share_zhuli_btn" src="/static/api/zhuli/images/invite.png" /></div>
 <?php }elseif($actid>0){?>
 <?php if($my_zhuli==1){?>
	<div class="main_btn"><img src="/static/api/zhuli/images/help_pass.png" /></div>
 <?php }else{?>
 <div class="main_btn" id="help_zhuli"><img id="help_zhuli_btn" src="/static/api/zhuli/images/help.png" /></div>
 <?php }?>
 <?php }else{?>
 <div class="main_btn" id="join_zhuli"><img id="join_zhuli_btn" src="/static/api/zhuli/images/join_btn.png" /></div>
 <?php }?>
</div>
<div class="main_bottom">
 <div class="main_content">
  <div class="btns">
   <p class="cur" rel="0">助力规则</p>
   <p rel="1">排行榜</p>
   <?php if($myact==1){?>
   <p rel="2">给力好友</p>
   <?php }else{?>
   <p rel="3" class="my_btn">我要参加</p>
   <?php }?>
   <div class="clear"></div>
  </div>
  <div class="stores">
 
   <div id="rank_list_0" style="padding-bottom:10px;">
     <h3>活动奖品</h3>
	<table class="zebra">
    <thead>
    <tr>
		<th>奖品级别</th>
        <th>奖品图片</th> 
        <th>奖品名称</th>
		<th>奖品数量</th>
    </tr>
    </thead>
  
    <tbody>
	<!--奖品列表 开始-->
    <?php if(!empty($Prizes)){foreach($Prizes as $P){?>
  	 <tr>
		<td align="center"><?php echo $P["Level"];?></td>
        <td align="center"><img class="prize_thumb" src="<?php echo $P["ImgPath"];?>"/></td>
        <td align="center"><?php echo $P["Name"];?></td>
        <td align="center"><?php echo $P["Num"];?></td>
     </tr>
	<?php }}?>
   <!--奖品列表 结束-->
  
   
</tbody></table>
	 <h3>活动规则</h3>
     <p style="padding:0px 8px">      
	  <?php echo $rsConfig["Rules"];?>
     </p>
     <h3>兑奖规则</h3>
     <p style="padding:0px 8px">
		<?php echo $rsConfig["Awordrules"];?>
     </p>
     <h3>活动时间</h3>
     &nbsp;&nbsp;&nbsp;&nbsp;<?php echo date("Y-m-d H:i:s",$rsConfig["Fromtime"]);?> ~ <?php echo date("Y-m-d H:i:s",$rsConfig["Totime"]);?>
   </div>
   <div id="rank_list_1">
   <table class="zebra">
    <thead>
    <tr>
        <th>排名</th>       
        <th>头像</th>
        <th>昵称</th>
		<th>人气指数</th>
    </tr>
    </thead>
  
    <tbody>
	<!--奖品列表 开始-->
    <?php foreach($rank as $R){?>
  	 	<tr>
       		<td align="center"><?php echo $R["rank"];?></td>
        	<td align="center"><img src="<?php echo $R["User_HeadImg"] ? $R["User_HeadImg"] : '/static/api/zhuli/images/user.jpg';?>" width="50" height="50"/></td>
        	<td align="center"><?php echo $R["User_NickName"] ? $R["User_NickName"] : '微友助力';?></td>
        	<td align="center"><?php echo $R["Act_Score"];?></td>
    	</tr>        
   <?php }?>
   <!--奖品列表 结束-->
  
   
</tbody></table>
   </div>
   <div id="rank_list_2">
   	<table class="zebra">
	<thead>
    <tr>
        <th>头像</th>       
        <th>昵称</th>
        <th>助力指数</th>
		<th>时间</th>
    </tr>
    </thead>
    <tbody> 
   <?php foreach($firend as $k=>$F){?>
  	 	<tr>
        	<td align="center"><img src="<?php echo $F["User_HeadImg"] ? $F["User_HeadImg"] : '/static/api/zhuli/images/user.jpg';?>" width="50" height="50"/></td>
        	<td align="center"><?php echo $F["User_NickName"] ? $F["User_NickName"] : '得力好友';?></td>
        	<td align="center"><?php echo $F["Record_Score"];?></td>
			<td align="center"><?php echo $F["Record_Time"];?></td>
    	</tr>        
   <?php }?>
</tbody></table>
   </div>
  </div>
 </div>
</div>
<div class='share_layer'><img src='/static/api/zhuli/images/share.png' /></div>
<?php if(!empty($share_config)){?>
	<script language="javascript">
		var share_config = {
		   appId:"<?php echo $share_config["appId"];?>",   
		   timestamp:<?php echo $share_config["timestamp"];?>,
		   nonceStr:"<?php echo $share_config["noncestr"];?>",
		   url:"<?php echo $share_config["url"];?>",
		   signature:"<?php echo $share_config["signature"];?>",
		   title:"<?php echo $share_config["title"];?>",
		   desc:"<?php echo str_replace(array("\r\n", "\r", "\n"), "", $share_config["desc"]);?>",
		   img_url:"<?php echo $share_config["img"];?>",
		   link:""
		};
		
		$(document).ready(global_obj.share_init_config);
	</script>
<?php }?>
<?php ad($UsersID,2,2);//第一个数字参数代表广告位置：1顶部2底部；第二个数字参数代表广告位的编号，从后台查看?>
</body>
</html>