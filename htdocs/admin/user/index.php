<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
if(empty($_SESSION["ADMINID"])){
	header("location:login.php");
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/admin/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/admin/js/global.js'></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="index.php">会员管理</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    <div class="r_con_wrap">
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="6%" nowrap="nowrap">序号</td>
            <td width="18%" nowrap="nowrap">所属商家</td>
            <td width="18%" nowrap="nowrap">所在地</td>
            <td width="12%" nowrap="nowrap">头像</td>
            <td width="12%" nowrap="nowrap">会员卡</td>
            <td width="8%" nowrap="nowrap">积分</td>
            <td width="20%" nowrap="nowrap">注册时间</td>
            <td width="6%" nowrap="nowrap" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
        <?php
			$lists = array();
			$DB->getPage("user","*","order by User_ID DESC",10);
			while($r=$DB->fetch_assoc()){
				$lists[] = $r;
			}
			$i=0;
			foreach($lists as $t){
				$i++;
				$item = $DB->GetRs("users","Users_Account","where Users_ID='".$t["Users_ID"]."'");
				$t["company"] = $item["Users_Account"];
		?>
          <tr>
            <td nowrap="nowrap"><?php echo $i;?></td>
            <td nowrap="nowrap"><?php echo $t["company"] ?></td>
            <td nowrap="nowrap"><?php echo $t['User_Province'].$t['User_City'] ?></td>
            <td nowrap="nowrap"><?php echo $t['User_HeadImg'] ? '<img src="'.$t['User_HeadImg'].'" width="60" height="60" />' : "";?></td>
            <td nowrap="nowrap">No.<?php echo $t['User_No'] ?></td>
            <td nowrap="nowrap"><?php echo $t['User_Integral'] ?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$t["User_CreateTime"]) ?></td>
            <td class="last" nowrap="nowrap"><a href="view.php?UserID=<?php echo $t["User_ID"];?>"><img src="/static/admin/images/ico/view.gif" align="absmiddle" alt="详情" title="详情" /></a></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</body>
</html>