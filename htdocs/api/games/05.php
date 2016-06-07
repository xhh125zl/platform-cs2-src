<div id="result">
	<div id="header">
		<?php if(!empty($rsConfig["Games_Logo"])){?><div><a href="/api/<?php echo $UsersID;?>/games/"><img src="<?php echo $rsConfig["Games_Logo"];?>"></a></div><?php }?>
	</div>
	<div class="over"><img src="/static/api/games/images/game-over.png" /></div>
	<div class="result_f">
		<div>酷！配成了<?php echo $rsResult["Score"];?>对</div>
	</div>
	<ul class="link">
		<li><a href="#" class="share">晒战绩</a></li>
		<li><a href="/api/<?php echo $UsersID;?>/games/detail/<?php echo $rsGames["Games_ID"]?>/" class="again">再来一局</a></li>
		<li><a href="/api/<?php echo $UsersID;?>/user/gift/1/" class="integral">积分兑换区</a></li>
		<li><a href="/api/<?php echo $UsersID;?>/games/" class="more">更多游戏</a></li>
	</ul>
	<div class="clear"></div>
</div>
<div id="rank">
	<h1>排行榜</h1>
	<ul>
    <?php
	$lists = array();
    $DB->get("games_result","*","where Users_ID='".$UsersID."' and Games_ID=".$rsGames["Games_ID"]." and Score>0 order by Score desc");
	while($r=$DB->fetch_assoc()){
		if(empty($lists[$r["Open_ID"]])){
			$lists[$r["Open_ID"]] = $r["Score"];
		}else{
			if($lists[$r["Open_ID"]]<$r["Score"]){
				$lists[$r["Open_ID"]] = $r["Score"];
			}
		}
	}
	$i=0;
	foreach($lists as $k=>$v){
		$i++;
		$usersinfo=array();
		$face = $nickname = "";
		$usersinfo=$DB->GetRs("user","*","where Users_ID='".$UsersID."' and User_OpenID='".$k."'");
		$face = empty($usersinfo["headimgurl"]) ? '/static/api/games/images/no_img.jpg' : $usersinfo["headimgurl"];
		$nickname = empty($usersinfo["nickname"]) ? "匿名" : $usersinfo["nickname"];
	?>
		<li<?php if($i%2==1){?> class="f"<?php }?>>
			<div class="num<?php if($i<=3){?> good<?php }?>"><?php echo $i;?></div>
			<div><img src="<?php echo $face;?>" /></div>
			<div class="name"><?php echo $nickname;?></div>
			<div class="wheel">配成了<?php echo $v;?>对</div>
		</li>
	<?php }?>	
	</ul>
</div>