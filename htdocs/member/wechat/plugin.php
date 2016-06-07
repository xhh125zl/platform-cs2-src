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
<style type="text/css">
body, html{background:url(/static/member/images/main/main-bg.jpg) left top fixed no-repeat;}
</style>
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
      </ul>
    </div>
    <script language="javascript">$(document).ready(wechat_obj.plugin_init);</script>
    <div id="plugin" class="r_con_wrap">
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%">工具名称</td>
            <td width="30%">回复关键词</td>
            <td width="10%">状态</td>
            <td width="10%">工具名称</td>
            <td width="30%">回复关键词</td>
            <td width="10%" class="last">状态</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>成语解释</td>
            <td class="left">格式如：成语五颜六色</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="chengyu" Status="1" /></td>
            <td>即时翻译</td>
            <td class="left">格式如：@翻译内容（中文或英文）</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="fanyi" Status="1" /></td>
          </tr>
          <tr>
            <td>快递查询</td>
            <td class="left">格式如：顺丰2222222222</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="kuaidi" Status="1" /></td>
            <td>电话区号</td>
            <td class="left">格式如：广州区号</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="quhao" Status="1" /></td>
          </tr>
          <tr>
            <td>人品测试</td>
            <td class="left">格式如：人品小李</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="renpin" Status="1" /></td>
            <td>身份证查询</td>
            <td class="left">格式如：身份证445281200001012563</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="shengfenzheng" Status="1" /></td>
          </tr>
          <tr>
            <td>手机归属地</td>
            <td class="left">格式如：手机13800138000</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="shouji" Status="1" /></td>
            <td>城市天气</td>
            <td class="left">格式如：广州天气</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="tianqi" Status="1" /></td>
          </tr>
          <tr>
            <td>看笑话</td>
            <td class="left">输入"笑话"关键词</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="xiaohua" Status="1" /></td>
            <td>邮政编码</td>
            <td class="left">格式如：广州邮编</td>
            <td><img src="/static/member/images/ico/on.gif" plugin="youbian" Status="1" /></td>
          </tr>
          <tr> </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>