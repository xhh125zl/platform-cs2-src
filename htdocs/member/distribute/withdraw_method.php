<?php
$base_url = base_url();

$_SERVER['HTTP_REFERER'] =  $base_url.'member/distribute/withdraw_method.php';
if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$action=empty($_REQUEST['action'])?'':$_REQUEST['action'];
if(!empty($action)){
	if($action=="del"){
		//删除分销记录
		$Flag=$DB->Del("distribute_withdraw_method","Users_ID='".$_SESSION["Users_ID"]."' and Method_ID=".$_GET["MethodID"]);
		if($Flag){
			echo '<script language="javascript">alert("删除成功");window.location="withdraw_method.php";</script>';
		}else{
			echo '<script language="javascript">alert("删除失败");history.back();</script>';
		}
		exit;
	}
}

$condition = "where Users_ID='".$_SESSION["Users_ID"]."' order by Method_CreateTime";
$rsMethods = $DB->getPage("distribute_withdraw_method","*",$condition,$pageSize=15);
$method_list = $DB->toArray($rsMethods);

$_METHOD = array(
	'bank_card'=>'银行卡',
	'alipay'=>'支付宝',
	'wx_hongbao'=>'微信红包',
	'wx_zhuanzhang'=>'微信转账',
);

$_STATUS = array('<font style="color:red">未启用</font>','<font style="color:blue">已启用</font>');

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
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/user.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/distribute/withdraw.js'></script>
    <div class="r_nav">
      <ul>
        <li><a href="withdraw.php">提现记录</a></li>
        <li class="cur"><a href="withdraw_method.php">提现方法管理</a></li>
      </ul>
    </div>
    <div id="user" class="r_con_wrap">
      <div class="control_btn">
      <a href="withdraw_method_add.php" class="btn_green btn_w_120">添加</a>
      </div>
      
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
            <td width="8%" nowrap="nowrap">类型</td>
            <td width="10%" nowrap="nowrap">银行名</td>
             <td width="10%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap">添加时间</td>
            <td width="8%" nowrap="nowrap" class="last"><strong>操作</strong></td>
          </tr>
        </thead>
        <tbody>
      
		  
	<?php foreach($method_list as $key=>$rsMethod):?>
           <tr>
            <td><?=$rsMethod['Method_ID']?></td>
            <td>
            	<?php echo $_METHOD[$rsMethod['Method_Type']];?>
            </td>
            <td><?=$rsMethod['Method_Name']?></td>
            <td><?=$_STATUS[$rsMethod['Status']]?></td>
            <td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsMethod['Method_CreateTime']) ?></td>
            <td nowrap="nowrap" class="last">
            <a href="withdraw_method_edit.php?action=del&MethodID=<?php echo $rsMethod['Method_ID'] ?>"><img src="/static/member/images/ico/mod.gif" alt="修改" align="absmiddle"></a>
            <a href="?action=del&MethodID=<?php echo $rsMethod['Method_ID'] ?>" onClick="if(!confirm('删除后不可恢复，继续吗？')){return false};"><img src="/static/member/images/ico/del.gif" align="absmiddle" /></a></td>
          </tr>
      <?php endforeach; ?>
        </tbody>
      </table>
      <div class="blank20"></div>
      <?php $DB->showPage(); ?>
    </div>
  </div>
</div>
</div>
</body>
</html>