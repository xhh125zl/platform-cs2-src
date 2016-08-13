<?php require_once('top.php'); ?>
<body id="loadingPicBlock" class="g-acc-bg">
<link href="/static/api/cloud/css/lottery.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<link href="/static/api/cloud/css/goodsrecords.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<link href="/static/api/cloud/plug/jquery.jscrollpane.css?t=<?php echo time();?>" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='/static/api/cloud/js/Comm.js?t=<?php echo time();?>'></script> 
<script type='text/javascript' src='/static/api/cloud/plug/jquery.jscrollpane.js?t=<?php echo time();?>'></script> 
<script type='text/javascript' src='/static/api/cloud/plug/jquery.mousewheel.js?t=<?php echo time();?>'></script> 
<script type='text/javascript' src='/static/api/cloud/js/pageDialog.js?t=<?php echo time();?>'></script>
<div class="column">
	<h2 style="text-align: center;height: 37px;line-height: 37px;color: #999;">
	<a href="javascript:history.go(-1)" class="pull-left"><img src="/static/api/shop/skin/default/images/black_arrow_left.png" /></a>
	<?php echo empty($_GET['myself']) ? '所有参与记录-本商品' : '我的购买记录-本商品';?>
	<h2>
</div>
<div class="clear"></div>
<div class="buy_records">
	<ul id="divRecordList">
		<?php if(!empty($records)){?>
		<?php foreach($records as $order_id => $arr){
			$buyids = array();
			foreach($arr as $val){
				$buyids[] = $val['Cloud_Code'];
				$username = $val['User_NickName'];
				$faceImg = $val['User_HeadImg'];
				$time = $val['Order_CreateTime'];
				$qishu = $val['qishu'];
			}
		?>
		<li buyids="<?php echo implode(' ', $buyids);?>" buynum="<?php echo count($arr);?>" username="<?php echo $username;?>"><i class="fr z-set"></i>
			<p><img src="<?php echo $faceImg;?>"></p>
			<dl>
				<dt><span class="fl blue"><?php echo $username;?></span><cite class="fl">云购了<b class="orange"><?php echo count($arr);?></b>人次</cite>（第<?=$qishu ?>期）</dt>
				<dd class="gray9">下单时间：<?php echo date('Y-m-d H:i:s', $time);;?></dd>
			</dl>
		</li>
		<?php }?>
		<?php }?>
	</ul>
</div>
<?php require_once('footer.php'); ?>
<script>
$("#divRecordList li").bind("click", function(u) {
	var C = $(this).attr('username');
	var E = $(this).attr('buynum');
	var K = $(this).attr('buyids');
	stopBubble(u);
	var s = function() {
		var O = "";
		var O = '<div class="codes-box clearfix">';
		O += '<a id="a_close" href="javascript:;" class="z-set box-close"></a>';
		O += '<div class="buy_codes">';
		O += "<dl>";
		O += '<dt class="gray9"><span class="fl"><a href="javascript:;" class="blue">' + C.substring(0, 7) + '</a></span>本次参与<em class="orange">' + E + "</em>人次</dt>";
		O += '<dd class="gray9" id="dd_container" style="overflow-y:scroll;height:220px;">';
		O += '<div id="div_list" style="padding:0 8px;color:#999999">' + K + "</div>";
		O += "</dd>";
		O += "</dl>";
		O += "</div>";
		O += "</div>";
		return O
	};
    var v = function() {
	    _DialogObj = $("#pageDialog");
		$("#a_close", _DialogObj).click(function(w) {
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