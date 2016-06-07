<?php
ini_set("display_errors","On");

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

$max_level = 9;
$base_url = base_url();

if($_POST){	
	$Menu = array();	
	$dis_config = Dis_Config::find($_SESSION["Users_ID"]);
	
	//分销商门槛
	$dis_config->Distribute_Type = $_POST['Distribute_Type'];
	
	//提现门槛	
	$dis_config->Dis_Level = $_POST['Dis_Level'];
	$dis_config->Dis_Mobile_Level = $_POST['Dis_Mobile_Level'];
	$dis_config->Dis_Self_Bonus = !empty($_POST['Dis_Self_Bonus'])?$_POST['Dis_Self_Bonus']:0;
	
	//购买协议
	$dis_config->Distribute_Agreement = htmlspecialchars($_POST['Agreement'], ENT_QUOTES);
	$dis_config->Distribute_AgreementTitle = htmlspecialchars($_POST['AgreementTitle'], ENT_QUOTES);
	$dis_config->Distribute_AgreementOpen = $_POST['AgreementOpen'];
	$Flag = $dis_config->save();
	
	if($Flag){
		echo '<script language="javascript">alert("设置成功");window.location="setting.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}else{
	$rsConfig = $DB->GetRs('distribute_config','*','where Users_ID="'.$_SESSION['Users_ID'].'"');
	if(!$rsConfig){
		$Data = array(
			'Users_ID'=>$_SESSION['Users_ID']
		);
		$DB->Add('distribute_config',$Data);
		$rsConfig = $DB->GetRs('distribute_config','*','where Users_ID="'.$_SESSION['Users_ID'].'"');
	}
	
	//获取分销商级别列表
	$list_level = array();
	$DB->Get('distribute_level','*','where Users_ID="'.$_SESSION["Users_ID"].'"');
	while($r = $DB->fetch_assoc()){
		$list_level[] = $r;
	}
	//分销商级别不存在，插入默认值
	if(empty($list_level)){
		$Data=array(
			"Users_ID"=>$_SESSION['Users_ID'],
			"Level_Name"=>"普通分销商",
			"Level_LimitType"=>3,
			"Level_PeopleLimit"=>json_encode(array(1=>'0'),JSON_UNESCAPED_UNICODE),
			"Level_CreateTime"=>time()
		);
		$DB->Add('distribute_level',$Data);
		update_dis_level($DB,$_SESSION['Users_ID']);
		$list_level[] = $Data;
	}
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/distribute/config.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/third_party/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
	$(document).ready(config_obj.base_config);
    KindEditor.ready(function(K) {
        K.create('textarea[name="Agreement"]', {
            themeType : 'simple',
			filterMode : false,
            uploadJson : '/third_party/kindeditor/php/upload_json.php',
            fileManagerJson : '/third_party/kindeditor/php/file_manager_json.php',
            allowFileManager : true
        });
    });
</script>
<style type="text/css">
#level_intro table{margin:8px 0px; border:1px #dfdfdf solid}
#level_intro table th{height:30px; line-height:30px; background:#f5f5f5; border-right:1px #dfdfdf solid}
#level_intro table td{padding:8px 0px; line-height:20px; border-right:1px #dfdfdf solid; text-align:center; border-top:1px #dfdfdf solid; background:#FFF}
#level_intro .last{border-right:none}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="setting.php">分销设置</a></li>
        <li><a href="setting_withdraw.php">提现设置</a></li>
        <li><a href="setting_other.php">其他设置</a></li>
        <li><a href="setting_protitle.php">爵位设置</a></li>
		<li><a href="setting_distribute.php">分销首页设置</a></li>
      </ul>
    </div>
    <div class="r_con_wrap">
      <form id="distribute_config_form" class="r_con_form" method="post" action="?">
        <input type="hidden" id="level_has" value="<?php echo count($list_level);?>">
        <input type="hidden" id="level" value="<?php echo $rsConfig["Dis_Level"];?>">
      	<div class="rows">
        	<label>分销级别</label>
            <span class="input">
            	<select name="Dis_Level">
                    <?php for($i=1;$i<=$max_level;$i++):?>
                    	<option value="<?=$i?>" <?=($rsConfig["Dis_Level"]==$i)?'selected':''?>><?=$i?>级</option>
                    <?php endfor;?>
           		</select>
            </span>
             <div class="clear"></div>
        </div>
        
        <div class="rows">
        	<label>分销商自买得佣金</label>
            <span class="input">
           		 <input type="checkbox" name="Dis_Self_Bonus"  id="Dis_Self_Bonus" value="1" <?php echo empty($rsConfig["Dis_Self_Bonus"])?"":" checked"; ?> />
           	     <label>开启后分销商自己购买也可得到佣金</label>
            </span>
            <div class="clear"></div>
        </div>
        
        <div class="rows">
        	<label>手机分销中心显示级别</label>
            <span class="input">
              <select name="Dis_Mobile_Level">
                <?php for($i=1;$i<=$rsConfig['Dis_Level'];$i++):?>
                	
                    <option value="<?=$i?>" <?=($rsConfig['Dis_Mobile_Level'] == $i)?'selected':''?>><?=$i?>级</option>
				
				<?php endfor;?>
              </select>
            </span>
            <div class="clear"></div>
        </div>
        
        <div class="rows">
        	<label>成为分销商门槛</label>
            <span class="input">
              <select name="Distribute_Type">
                   <option value="0"<?php echo $rsConfig['Distribute_Type']==0 ? ' selected' : ''?>>直接购买</option>
                   <option value="1"<?php echo $rsConfig['Distribute_Type']==1 ? ' selected' : ''?>>消费额</option>
                   <option value="2"<?php echo $rsConfig['Distribute_Type']==2 ? ' selected' : ''?>>购买商品</option>
              </select>
            </span>
            <div class="clear"></div>
        </div>
        <div class="rows">
        	<label>分销商级别</label>
            <span class="input">
              <a href="javascript:void(0);" class="setting_level_btn">[设置级别]</a>
              <div id="level_intro"></div>
            </span>
            <div class="clear"></div>
        </div>
		
		<div class="rows">
        	<label>购买协议</label>
            <span class="input">
              <input name="AgreementOpen" value="0" type="radio" id="agree_0"<?php echo $rsConfig['Distribute_AgreementOpen']==0 ? ' checked' : '';?>/><label for="agree_0">关闭</label>&nbsp;&nbsp;
			  <input name="AgreementOpen" value="1" type="radio" id="agree_1"<?php echo $rsConfig['Distribute_AgreementOpen']==1 ? ' checked' : '';?>/><label for="agree_1">开启</label>
            </span>
            <div class="clear"></div>
        </div>
		
		<div class="rows">
        	<label>购买协议标题</label>
            <span class="input">
              <input name="AgreementTitle" value="<?php echo $rsConfig["Distribute_AgreementTitle"];?>" type="text" class="form_input" size="30" />
            </span>
            <div class="clear"></div>
        </div>
		
		<div class="rows">
        	<label>购买协议内容</label>
            <span class="input">
              <textarea name="Agreement" style="width:100%;height:400px;visibility:hidden;"><?php echo $rsConfig["Distribute_Agreement"];?></textarea>
			  <div class="tips">此协议仅在购买成为分销商页面显示</div>
            </span>
            <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" name="submit_button" value="提交保存" />
          </span>
          <div class="clear"></div>
        </div> 
      </form>
    </div>
  </div>
</div>
</body>
</html>