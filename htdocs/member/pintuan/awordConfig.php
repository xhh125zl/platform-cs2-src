<?php
  require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
  require_once($_SERVER["DOCUMENT_ROOT"] . '/cron/windowSchedule.php');
    
    if(isset($_GET['action']) && $_GET['action'] == 'taskRemove'){
        $Users_Id = isset($_SESSION["Users_ID"]) ? $_SESSION["Users_ID"] : '';
        $taskName = $_SESSION["Users_ID"]."_Task";
        $task = new Task();
        $task->remove($taskName);
        $DB->Del("users_schedule","Users_ID='{$Users_Id}'");
        echo "<script> alert(\"删除计划任务成功\");history.go(-1); </script>";
        exit;
    }
    if ($_POST) {
        $RunType = $_POST['RunType'];
        $day = intval($_POST['day']);
        $Time = $_POST['Time'];
        $Users_Id = isset($_SESSION["Users_ID"]) ? $_SESSION["Users_ID"] : '';
        $StartRunTime = "";
        if(!$Users_Id){
            echo "<script> alert(\"Session过期，请重新登录\");top.location.href = '/member/login.php'; </script>";
            exit;
        }
        if(!$day){
            $day =1;
        }
        if(empty($Time) || !$Time){
            $Time = date("H:i");
        }

        $data = array(
            'Users_ID' => $Users_Id,
            'StartRunTime' => $Time,
            'RunType' => $RunType,
            'Status' => 1,
            'LastRunTime' => strtotime(date("Y-m-d",time())),
            'day' =>$day
        );
        //添加计划任务

        $sch = $DB->GetRs("users_schedule", "*", "WHERE Users_ID='{$Users_Id}'");
        if ($sch) {
            $taskName = $_SESSION["Users_ID"]."_Task";
            $task = new Task();
            $type = "";
            if($RunType == 1){  //按周
                $task->add("mo",1);
                $type = "WEEKLY";
            }else if($RunType ==2 ){  //按天
                $task->add("mo",$day);
                $type = "DAILY";
            }else{  //按月
                $task->add("mo",1);
                $type = "MONTHLY";
            }
            $task->add("st",$Time);
            $task->add("ru",'"System"');
            $task->remove($taskName);
            $task->create($taskName ,"cmd /c " .$_SERVER["DOCUMENT_ROOT"]."/cron/Run.bat http://".$_SERVER['HTTP_HOST']."/api/pintuan/sync/");
            $task->getXML($taskName);
            $DB->Set("users_schedule", $data, "WHERE Users_ID='{$Users_Id}'");
        } else {
            $taskName = $_SESSION["Users_ID"]."_Task";
            $task = new Task();
            $type = "";
            if($RunType == 1){  //按周
                $task->add("mo",1);
                $type = "WEEKLY";
            }else if($RunType ==2 ){  //按天
                $task->add("mo",$day);
                $type = "DAILY";
            }else{  //按月
                $task->add("mo",1);
                $type = "MONTHLY";
            }
            $task->add("st",$Time);
            $task->add("ru",'"System"');
            $task->create($taskName ,"cmd /c " .$_SERVER["DOCUMENT_ROOT"]."/cron/Run.bat  http://".$_SERVER['HTTP_HOST']."/api/pintuan/sync/");
            $task->getXML($taskName);
            $DB->Add("users_schedule", $data);
        }
        echo "<script> alert(\"修改成功\");history.go(-1);</script>";
        exit;
    }
    ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>计划任务配置</title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
</head>

<body>
	<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
	<div id="iframe_page">
		<div class="iframe_content">
			<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />

			<?php include 'top.php'; ?>
			<div id="payment" class="r_con_wrap">
				<form id="payment_form" class="r_con_form" method="post" action="/member/pintuan/awordConfig.php">
					<?php $sch = $DB->GetRs("users_schedule", "*", "WHERE Users_ID='{$_SESSION['Users_ID']}'");
					       $type = 2;
					       if($sch){
					           $type = $sch['RunType'];
					           $time = $sch['StartRunTime'];
					           $day = $sch['day'];
					           $lastRunTime = $sch['LastRunTime'];
					          
					       }
					?>
					<div class="rows">
						<label>运行方式</label> <span class="input time"> <select
							name='RunType'>
								<option value="1" <?=$type==1?"selected":"" ?>>按周</option>
								<option value="2" <?=$type==2?"selected":"" ?>>按天</option>
								<option value="3" <?=$type==3?"selected":"" ?>>按月</option>
						</select>&nbsp; (若按天运行，请手动填写天数)<font class="fc_red">*</font></span>
						<div class="clear"></div>
					</div>
					<div class="rows">
						<label>运行时间</label> <span class="input time"> <input name="Time"
							type="text" value="<?=isset($time)?$time:date('H:i:s') ?>" class="form_input"
							size="40" notnull /> <font class="fc_red">*</font> <span
							class="tips">设置抽奖运行的时间段</span></span>
						<div class="clear"></div>
						<label>运行天数</label> <span class="input time"> <input name="day"
							type="text" value="<?php echo isset($day)?$day:2; ?>" class="form_input" size="40" notnull /> <font
							class="fc_red">*</font> <span class="tips">每隔N天进行运行</span></span>
					</div>
					<div class="rows">
						<label></label> <span class="input"> <input type="submit"
							class="btn_green" value="确定" name="submit_btn">   <input type="button"
							class="btn_green" value="删除计划任务" name="removeTask"></span>
						<div class="clear"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
	$(function(){
		$("input[name='removeTask']").click(function(){
			location.href = "/member/pintuan/awordConfig.php";
		});
		
		$("select[name='RunType']").change(function(){

			var RunType = $("select[name='RunType']").val();
			if(RunType==1){
				$("input[name='day']").val("7");
			}else if(RunType==3){
				$("input[name='day']").val("<?php echo date("t",time());?>");
			}
	    });

	});
	</script>
</body>
</html>