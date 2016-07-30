<?php require_once('top.php'); ?>
<body>
<script type='text/javascript' src='/static/api/cloud/js/Comm.js?t=<?php echo time();?>'></script>
<script type='text/javascript' src='/static/api/cloud/js/pageDialog.js?t=<?php echo time();?>'></script>
<link href="/static/api/cloud/css/lottery.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<div id="calResult" class="wrapper">
	<div class="g-formula clearfix">
		<div class="for-con1 z-oval clearfix"><em class="orange"><?php echo $rsDetail['Luck_Sn'];?></em><i class="colorbbb">最终计算结果</i></div>
		<p></p>
		<div class="for-con2 clearfix"><cite>(</cite><span class="z-oval"><em class="orange"><?php echo $Result['sumTime'] ? $Result['sumTime'] : 0;?></em><i class="colorbbb">时间取值之和</i></span><cite>%</cite><span class="z-oval"><em class="orange"><?php echo $Result['zongrenci'] ? $Result['zongrenci'] : 0;?></em><i class="colorbbb">商品总需人次</i></span><cite>)</cite><cite>+</cite><span class="z-oval"><em class="orange">10000001</em><i class="colorbbb">固定数值</i></span></div>
		<?php 
			if(strpos($rsDetail['Products_End_Time'], '.')){
				list($usec, $sec) = explode('.', $rsDetail['Products_End_Time']);
				$date = date('Y-m-d H:i:s', $usec);
			}else{
				$date = date('Y-m-d H:i:s', $rsDetail['Products_End_Time']);
				$sec = 0;
			}
		?>	 
		<div class="orange z-and">截止该商品最后购买时间【<?php echo $date.'.'.$sec;?>】<br>
			网站所有商品的最后100条购买时间取值之和<a id="a_showway" href="javascript:;" class="orange">如何计算<i class="z-set"></i></a></div>
	</div>
	<div class="calCon clearfix">
		<dl class="dl1">
			<dt><span>云购时间</span><span></span><span>转换数据</span><span>会员</span></dt>
		</dl>
		<dl style="height: auto;" id="dl_nginner" class="dl2">
		<?php if(!empty($Result['timerecord'])){?>
		<?php foreach($Result['timerecord'] as $key => $Add_Time){?>
		    <?php 
				list($usec, $sec) = explode('.', $Add_Time);
				$date = date('Y-m-d', $usec);
				$time = date('H:i:s', $usec);
				$h = explode(':', $time)[0];
				$i = explode(':', $time)[1];
				$s = explode(':', $time)[2];
				$rsUser = $DB->GetRs('user', 'User_NickName', 'where User_ID='.$Result['userrecord'][$key]);
			?>
			<dd><span style="font-size:11px"><?php echo $date;?><b></b></span><span style="font-size:11px"><?php echo $time.'.'.$sec;?><s></s></span><span style="font-size:11px"><i><em></em></i><?php echo $h.$i.$s.$sec;?><s></s></span><span style="font-size:11px"><?php echo $rsUser['User_NickName'] ? $rsUser['User_NickName'] : '匿名';?></span></dd>
		<?php }?>
		<?php }?>
		</dl>
	</div>
</div>
<?php require_once('footer.php'); ?>
<script>
$("#a_showway").bind("click", function(u) {
	stopBubble(u);
	var s = function() {
		var w = "";
		w += '<div id="div_container" class="acc-pop clearfix z-box-width">';
		w += '<a id="a_cancle" href="javascript:;" class="z-set box-close"></a>';
		w += "<dl>";
		w += '<dt class="gray6">如何计算？</dt>';
		w += "<dd>1、取该商品最后购买时间前网站所有商品的最后100条购买时间记录；</dd>";
		w += "<dd>2、按时、分、秒、毫秒排列取值之和，除以该商品总参与人次后取余数；</dd>";
		w += "<dd>3、余数加上10000001 即为“幸运云购码”；</dd>";
		w += "<dd>4、余数是指整数除法中被除数未被除尽部分， 如7÷3 = 2 ......1，1就是余数。</dd>";
		w += "</dl>";
		w += "</div>";
		return w
	};
	var v = function() {
		_DialogObj = $("#pageDialog");
		$("#a_cancle", _DialogObj).click(function(w) {
			t.cancel()
		});
		$("#div_container", _DialogObj).click(function(w) {
			stopBubble(w)
		});
		$("#pageDialogBG").click(function() {
			t.cancel()
		})
	};
	var t = new $.PageDialog(s(), {
		W: 290,
		H: 257,
		close: true,
		autoClose: false,
		ready: v
	})
})
</script>
</body>
</html>