
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>微易宝</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<style type="text/css">body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}</style>
<div id="iframe_page">
	<div class="iframe_content">
		<link href='/static/member/css/wechat.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/wechat.js'></script>
<div class="r_nav">
	<ul>
					<li class=""><a href="/member/wechat/attention_reply.php">首次关注设置</a></li>
					<li class=""><a href="/member/wechat/menu.php">自定义菜单设置</a></li>
					<li class=""><a href="/member/wechat/keyword_reply.php">关键词回复</a></li>
					<li class=""><a href="/member/wechat/token_set.php">微信接口配置</a></li>
					<li class="cur"><a href="/member/wechat/spread.php">推广渠道管理</a></li>
			</ul>
</div><div id="spread" class="r_con_wrap">
			<script type='text/javascript' src='/static/js/plugin/pcas/pcas.js'></script>
		<script language="javascript">$(document).ready(wechat_obj.spread_init);</script>
		<form id="spread_form" class="r_con_form">
			<div class="rows">
				<label>渠道名称</label>
				<span class="input"><input type="text" name="Name" class="form_input" value="aaa渠道名称" size="25" maxlength="15" notnull /> <span class="fc_red">*</span></span>
				<div class="clear"></div>
			</div>
			<div class="rows">
				<label>渠道类型</label>
				<span class="input">
					<input type="radio" name="SpreadType" value="0" checked />线下<br />
					<input type="radio" name="SpreadType" value="1"  />线上<br />
				</span>
				<div class="clear"></div>
			</div>
			<div class="rows pcas">
				<label>所在地区</label>
				<span class="input">
					<select name="Province"></select><select name="City"></select><select name="Area"></select>
					<div class="blank6"></div>
					<input name="Address" value="" type="text" class="form_input" size="45" maxlength="100" placeholder="详细地址">
				</span>
				<div class="clear"></div>
				<script language="javascript">new PCAS('Province', 'City', 'Area', "天津市", "市辖县", "静海县");</script>
			</div>
			<div class="rows url">
				<label>渠道网址</label>
				<span class="input"><input type="text" name="Url" class="form_input" value="" size="35" maxlength="100" /></span>
				<div class="clear"></div>
			</div>
			<div class="rows">
				<label>备注信息</label>
				<span class="input"><textarea name="Remark">sdfsd</textarea></span>
				<div class="clear"></div>
			</div>
			<div class="rows">
				<label></label>
				<span class="input"><input type="submit" class="btn_green" name="submit_button" value="提交保存" /><a href="" class="btn_gray">返回</a></span>
				<div class="clear"></div>
			</div>
			<input type="hidden" name="do_action" value="wechat.spread">
			<input type="hidden" name="SId" value="35">
		</form>
	</div>	</div>
</div>
</body>
</html>