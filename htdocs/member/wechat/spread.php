
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
			<div class="tips_info">
			1. 您的公众平台帐号类型必须为<span>服务号</span>，并且帐号已通过<span>微信认证</span>。<br />
			2. 在微信公众平台申请接口使用的<span>AppId</span>和<span>AppSecret</span>，然后在【<a href="/member/wechat/auth_set.php">微信授权配置</a>】中正确设置，否则无法下载推广使用的二维码。<br />
			3. 下载二维码，当用户扫描此二维码时，便可识别用户的来源信息，请到【<a href="./?m=statistics&a=spread">数据魔方</a>】中查看用户来源统计信息。
		</div>
		<div class="control_btn"><a href="/member/wechat/spread.php&d=edit" class="btn_green btn_w_120">添加推广渠道</a></div>
		<table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
			<thead>
				<tr>
					<td width="10%" nowrap="nowrap">序号</td>
					<td width="15%" nowrap="nowrap">渠道名称</td>
					<td width="10%" nowrap="nowrap">渠道类型</td>
					<td width="25%" nowrap="nowrap">渠道信息</td>
					<td width="10%" nowrap="nowrap">下载二维码</td>
					<td width="10%" nowrap="nowrap" class="last">操作</td>
				</tr>
			</thead>
			<tbody>
							<tr>
					<td nowrap="nowrap">1</td>
					<td nowrap="nowrap">aaa渠道名称</td>
					<td nowrap="nowrap">线下</td>
					<td>天津市静海县</td>
					<td nowrap="nowrap"><a href="/member/wechat/spread.php&do_action=wechat.spread_qr_download&SId=35">下载</a></td>
					<td class="last" nowrap="nowrap">
						<a href="/member/wechat/spread.php&d=edit&SId=35"><img src="/static/member/images/ico/mod.gif" align="absmiddle" alt="修改" /></a>
						<a href="/member/wechat/spread.php&do_action=wechat.spread_del&SId=35" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" alt="删除" /></a>
					</td>
				</tr>
						</tbody>
		</table>
		<div class="blank20"></div>
		<div id="turn_page"><font class='page_noclick'><<上一页</font>&nbsp;<font class='page_item_current'>1</font>&nbsp;<font class='page_noclick'>下一页>></font></div>
	</div>	</div>
</div>
</body>
</html>