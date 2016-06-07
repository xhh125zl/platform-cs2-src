<?php

require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}


//获取此用户的续费记录

$rsRecord = $DB->get("users_money_record","*","where Users_ID='".$_SESSION['Users_ID']."' and Record_Type=0");
$record_list = $DB->toArray($rsRecord);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
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
        <li class="cur"><a href="/member/wechat/renewal_record.php">续费记录</a></li>        
      </ul>
    </div>
    <div id="message" class="r_con_wrap">
    
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="10%" nowrap="nowrap">序号</td>
            <td width="15%" nowrap="nowrap">续费号</td>
            <td width="10%" nowrap="nowrap">续费时长</td>
            <td width="5%" nowrap="nowrap">续费金额</td>
            <td width="10%" nowrap="nowrap">时间</td>
            <td width="20%" nowrap="nowrap">交易编号</td>
            <td width="10%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        
       <?php foreach($record_list as $key=>$item):?> 
          <tr>
            <td nowrap="nowrap"><?=$item['Record_ID']?></td>
            <td nowrap="nowrap"><?=$item['Record_Sn']?></td>
            <td nowrap="nowrap"><?=$item['Record_Qty']?>年</td>
            <td nowrap="nowrap"><span class="fc_red"><?=$item['Record_Money']?></span>元</td>
            <td><?=ldate($item['Record_CreateTime'])?></td>
            <td><?=$item['trade_no']?></td>
            <td>
            	<?php if($item['Record_Status'] == 0): ?>
                	未付款
                <?php else: ?>
                    已付款
				<?php endif;?>
            </td>
            <td class="last" nowrap="nowrap">
            <?php if($item['Record_Status'] == 0): ?>
            <a  href="/member/pay.php?ID=<?=$item['Record_ID']?>" class="btn btn-sm btn-default">付款</a>
            <?php endif;?>
            </td>
          </tr>
      	<?php endforeach; ?>
      </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>