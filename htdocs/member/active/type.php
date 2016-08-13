<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/update/common.php');

if (isset($_GET["action"])) {
    if ($_GET["action"] == "del") {
        
        $Flag = $DB->Del("active_type", "Users_ID='{$UsersID}' AND Type_ID=" . $_GET['typeid']);
        if ($Flag) {
            echo '<script language="javascript">alert("删除成功");window.location="' . $_SERVER['HTTP_REFERER'] . '";</script>';
        } else {
            echo '<script language="javascript">alert("删除失败");history.back();</script>';
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href="/static/style.css" rel="stylesheet" type="text/css" />
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/shop.js'></script>
    <div id="products" class="r_con_wrap"> 
      <script type='text/javascript' src='/static/js/plugin/dragsort/dragsort-0.5.1.min.js'></script>
      <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
      <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
      <script language="javascript">$(document).ready(shop_obj.products_category_init);</script>
      <div class="category">
        <div class="control_btn"><a href="type_add.php" class="btn_green btn_w_120">添加类型</a></div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mytable">
          <tr bgcolor="#f5f5f5">
            <td width="50" align="center"><strong>序号</strong></td>
            <td width="100" align="center"><strong>类型名称</strong></td>
            <td align="center"><strong>模型</strong></td>
            <td align="center"><strong>添加时间</strong></td>
            <td align="center"><strong>类型状态</strong></td>
            <td width="60" align="center"><strong>操作</strong></td>
          </tr>
          <?php
          $res = $DB->get("active_type","*","WHERE Users_ID='{$UsersID}' ORDER BY Type_ID ASC");
          $list = $DB->toArray($res);
          foreach($list as $key => $val){
          ?>
          <tr>
            <td><?=$val['Type_ID']?></td>
            <td align="center"><?=isset($val['Type_Name'])?$val['Type_Name']:'' ?></td>
            <td align="center"><?=isset($val['module'])?$val['module']:'' ?></td>
            <td align="center"><?=date("Y-m-d H:i:s",$val['addtime']) ?></td>
            <td align="center"><?=$val['Status']?'启用':'禁用'?></td>
            <td align="center">
                <a href="type_edit.php?typeid=<?=$val["Type_ID"] ?>" title="修改"><img src="/static/member/images/ico/mod.gif" align="absmiddle" /></a>
                <a href="type.php?action=del&typeid=<?=$val["Type_ID"] ?>" title="删除" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
          <?php
            }
          ?>
        </table>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>