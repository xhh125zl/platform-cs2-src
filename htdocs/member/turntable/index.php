<?php 
if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
require_once('vertify.php');
if(isset($_POST['action'])){
	if($_POST['action']=='turntable_add'){
		$Time=empty($_POST["Time"])?array(time(),time()):explode(" - ",$_POST["Time"]);
		$StartTime=strtotime($Time[0]);
		$EndTime=strtotime($Time[1]);
		$Data=array(
			"Turntable_Title"=>$_POST['Title'],
			"Turntable_StartTime"=>$StartTime,
			"Turntable_EndTime"=>$EndTime,
			"Turntable_CreateTime"=>time(),
			"Turntable_Status"=>0,
			"Users_ID"=>$_SESSION["Users_ID"]
		);
		$Flag=$DB->Add("turntable",$Data);
		if($Flag){
			$Data=array(
				"status"=>1,
				"TurntableID"=>$DB->insert_id()
			);
		}else{
			$Data=array(
				"status"=>0
			);
		}
	}elseif($_POST['action']=='del'){
		$Flag=true;
		$msg="";
		mysql_query("begin");
		$Flag=$Flag&&$DB->Del("turntable","Users_ID='".$_SESSION["Users_ID"]."' and Turntable_ID=".$_POST["TurntableID"]);
		if($Flag){
			$Data=array(
				"status"=>1
			);
			mysql_query("commit");
		}else{
			$Data=array(
				"status"=>0
			);
			mysql_query("roolback");
		}
	}elseif($_POST['action']=='stop'){
		$Data=array(
			"Turntable_Status"=>1
		);
		$Flag=$DB->Set("turntable",$Data,"where Users_ID='".$_SESSION["Users_ID"]."' and Turntable_ID=".$_POST["TurntableID"]);
		if($Flag){
			$Data=array(
				"status"=>1
			);
			mysql_query("commit");
		}else{
			$Data=array(
				"status"=>0
			);
			mysql_query("roolback");
		}
	}
	echo json_encode(empty($Data)?array("status"=>0):$Data,JSON_UNESCAPED_UNICODE);
	exit;
}
?>
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
    <link href='/static/member/css/turntable.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/member/js/turntable.js'></script>
    <div class="r_nav">
      <ul>
        <li class=""><a href="config.php">基本设置</a></li>
        <li class="cur"><a href="index.php">欢乐大转盘</a></li>
      </ul>
    </div>
    <link href='/static/js/plugin/lean-modal/style.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/lean-modal/lean-modal.min.js'></script> 
    <script type='text/javascript' src='/static/js/plugin/daterangepicker/moment_min.js'></script>
    <link href='/static/js/plugin/daterangepicker/daterangepicker.css' rel='stylesheet' type='text/css' />
    <script type='text/javascript' src='/static/js/plugin/daterangepicker/daterangepicker.js'></script> 
    <script language="javascript">$(document).ready(turntable_obj.turntable_init);</script>
    <div id="turntable" class="r_con_wrap">
      <div class="control_btn"> <a href="#turntable_add" class="btn_green btn_w_120">新增</a>
        <div class="tips"><strong>注意：</strong>大转盘总配额为10个，如活动配额满请删除已结束活动</div>
      </div>
      <table border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%">编号</td>
            <td width="25%">活动名称</td>
            <td width="20%">活动时间</td>
            <td width="10%">状态</td>
            <td width="35%" class="last">操作</td>
          </tr>
        </thead>
        <tbody>
          <?php $DB->getPage("turntable","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Turntable_CreateTime desc",$pageSize=10);
		$i=1;
		while($rsTurntable=$DB->fetch_assoc()){?>
          <tr>
            <td nowrap="nowrap"><?php echo $pageSize*($DB->pageNo-1)+$i; ?></td>
            <td><?php echo $rsTurntable['Turntable_Title'] ?></td>
            <td><?php echo date("Y-m-d H:i:s",$rsTurntable["Turntable_StartTime"])."<br>~<br>".date("Y-m-d H:i:s",$rsTurntable["Turntable_EndTime"]) ?></td>
            <td><?php if($rsTurntable['Turntable_Status']!=1){
				echo '进行中';
			}else{
				echo '已结束';
			}?></td>
            <td class="last" Data='{"TurntableID":"<?php echo $rsTurntable['Turntable_ID'] ?>","Title":"<?php echo $rsTurntable['Turntable_Title'] ?>","StartTime":"<?php echo $rsTurntable['Turntable_StartTime'] ?>","EndTime":"<?php echo $rsTurntable['Turntable_EndTime'] ?>","Probability":null}'> [<a href="turntable_mod.php?TurntableID=<?php echo $rsTurntable['Turntable_ID'] ?>">设置</a>]
              <?php if($rsTurntable['Turntable_Status']>0){ ?>[<a href="turntable_sncode.php?TurntableID=<?php echo $rsTurntable['Turntable_ID'] ?>">SN码管理</a>]
              [<a href="turntable_logs.php?TurntableID=<?php echo $rsTurntable['Turntable_ID'] ?>">抽奖记录</a>]<?php }?>
              <?php if($rsTurntable['Turntable_Status']>1){ ?>[<a href="#stop">停止</a>]<?php }?>
              <?php if($rsTurntable['Turntable_Status']<=1){ ?>[<a href="#del">删除</a>]<?php }?> </td>
          </tr>
          <?php $i++;
		  }?>
        </tbody>
      </table>
    </div>
    <div id="turntable_add" class="lean-modal lean-modal-form">
      <div class="h">新增活动<a class="modal_close" href="#"></a></div>
      <form class="form" id="turntable_add_form">
        <div class="rows">
          <label>活动名称：</label>
          <span class="input">
          <input name="Title" value="" type="text" class="form_input" size="26" maxlength="100" notnull>
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>活动时间：</label>
          <span class="input">
          <input name="Time" type="text" value="" class="form_input" size="42" readonly="readonly" notnull />
          <font class="fc_red">*</font> </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label></label>
          <span class="submit">
          <input type="submit" value="确定提交" name="submit_btn">
          </span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="action" value="turntable_add">
      </form>
    </div>
  </div>
</div>
</body>
</html>