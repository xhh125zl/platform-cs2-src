<?php
ini_set("display_errors","On");

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

$base_url = base_url();

if(isset($_GET["action"])){
	if($_GET["action"] == 'get_product'){
		$cate_id = $_GET['cate_id'];
	    $keyword = $_GET['keyword'];
	    $condition = "where Users_ID = '".$_SESSION['Users_ID']."'";
	   
	    if(strlen($cate_id)>0){
			$condition .= " and Products_Category like '%".','.$cate_id.','."%'";
	   }
	   
	   if(strlen($keyword)>0){
			$condition .= " and Products_Name like '%".$_GET["keyword"]."%'";	
	   }
	   
	   $rsProducts = $DB->Get("shop_products",'Products_ID,Products_Name,Products_PriceX',$condition);
	   $product_list = $DB->toArray($rsProducts);
	   $option_list = '';
	   foreach($product_list as $v){
		   $option_list .= '<option value="'.$v['Products_ID'].'">'.$v['Products_Name'].'---'.$v['Products_PriceX'].'</option>';
	   }
	   echo $option_list;
	   exit;
	}
}

$distribute_level = Dis_Level::get_dis_pro_level($_SESSION["Users_ID"]);
$dis_title_level = Dis_Config::get_dis_pro_title($_SESSION['Users_ID']);
if($_POST){
	$Menu = array();	
	$distribute_config = Dis_Config::find($_SESSION["Users_ID"]);
	//提现门槛
	
	$distribute_config->Distribute_Customize = $_POST['Customize'];
	$distribute_config->HIncomelist_Open = $_POST['HIncomelist_Open'];
	$distribute_config->H_Incomelist_Limit = !empty($_POST['H_Incomelist_Limit'])?$_POST['H_Incomelist_Limit']:0;
	$distribute_config->QrcodeBg = $_POST['QrcodeBg'];
	$distribute_config->ApplyBanner = $_POST['ApplyBanner'];
	$distribute_config->Dis_Agent_Type = $_POST['Dis_Agent_Type'];
	$distribute_config->Sha_Agent_Type = $_POST['Sha_Agent_Type'];
	
	if($_POST['Dis_Agent_Type'] == 0){
		$Agent_Rate = '';
	}elseif($_POST['Dis_Agent_Type'] == 1){
		$Agent_Rate = json_encode($_POST['Agent_Rate'],JSON_UNESCAPED_UNICODE);
	}
	
	if($_POST['Sha_Agent_Type'] == 0){
		$Sha_Rate = '';
	}elseif($_POST['Sha_Agent_Type'] == 1){
		$Sha_Rate = json_encode($_POST['Sha_Rate'],JSON_UNESCAPED_UNICODE);
	}
	
	$distribute_config->Agent_Rate = $Agent_Rate;
	$distribute_config->Sha_Rate = $Sha_Rate;
	
	//返本规则	
	$distribute_config->Fanben_Open = $_POST["Fanben"];
	if($_POST["Fanben"]==0){//关闭
		$distribute_config->Fanben_Rules = '';
	}else{
		$_POST["Fanben_Rules"][0] = empty($_POST["Fanben_Rules"][0]) ? 1 : intval($_POST["Fanben_Rules"][0]);
		$_POST["Fanben_Rules"][1] = empty($_POST["Fanben_Rules"][1]) ? 1 : number_format($_POST["Fanben_Rules"][1],2,'.','');
		$_POST["Fanben_Rules"][2] = empty($_POST["Fanben_Rules"][2]) ? 0 : intval($_POST["Fanben_Rules"][2]);
		$distribute_config->Fanben_Rules = json_encode($_POST["Fanben_Rules"],JSON_UNESCAPED_UNICODE);
	}
	
	//返本限制
	$distribute_config->Fanben_Type = $_POST['Type'];
	if($_POST['Type']==1){
		$distribute_config->Fanben_Limit = (empty($_POST['Limit'][$_POST['Type']]) ? '' : substr($_POST['Limit'][$_POST['Type']],1,-1));
	}else{
		$distribute_config->Fanben_Limit = '';
	}
	
	//复销规则
	$distribute_config->Fuxiao_Open = $_POST["Fuxiao"];
	if($_POST["Fuxiao"]==0){//关闭
		$distribute_config->Fuxiao_Rules = '';
	}else{
		$_POST["Fuxiao_Rules"][0] = empty($_POST["Fuxiao_Rules"][0]) ? 1 : number_format($_POST["Fuxiao_Rules"][0],2,'.','');
		$_POST["Fuxiao_Rules"][1] = empty($_POST["Fuxiao_Rules"][1]) ? 1 : intval($_POST["Fuxiao_Rules"][1]);
		$_POST["Fuxiao_Rules"][2] = empty($_POST["Fuxiao_Rules"][2]) ? 1 : intval($_POST["Fuxiao_Rules"][2]);		
		$distribute_config->Fuxiao_Rules = json_encode($_POST["Fuxiao_Rules"],JSON_UNESCAPED_UNICODE);
	}
	
	//分销商人数限制设置
	$distribute_config->Distribute_Limit = $_POST["Distribute_Limit"];
	
	//商城入口设置
	$distribute_config->Distribute_ShopOpen = $_POST["Distribute_ShopOpen"];
	
	$Flag = $distribute_config->save();
	
	if($Flag){
		echo '<script language="javascript">alert("设置成功");window.location.href="setting_other.php";</script>';
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
	
	//获取产品分类列表
	$category_list = array();
	$DB->get("shop_category","*","where Users_ID='".$_SESSION["Users_ID"]."' order by Category_ParentID asc,Category_Index asc");
	while($rsCategory = $DB->fetch_assoc()){
		if($rsCategory["Category_ParentID"] != $rsCategory["Category_ID"]){
			if($rsCategory["Category_ParentID"] == 0){
				$category_list[$rsCategory["Category_ID"]] = $rsCategory;
			}else{
				$category_list[$rsCategory["Category_ParentID"]]["child"][] = $rsCategory;
			}
		}
	}
	
	//返本规则初始化
	$Fanben_Rules = $rsConfig["Fanben_Rules"] ? json_decode($rsConfig['Fanben_Rules'],true) : array('1','1','0');
	
	//复销规则初始化
	$Fuxiao_Rules = $rsConfig["Fuxiao_Rules"] ? json_decode($rsConfig['Fuxiao_Rules'],true) : array('1','1','1');
}
$json_level = json_encode($distribute_level,JSON_UNESCAPED_UNICODE);
$json_title = json_encode($dis_title_level,JSON_UNESCAPED_UNICODE);
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
<script type='text/javascript' src='/static/member/js/distribute/config.js?data=160322'></script>

<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
    
<script type="text/javascript">
var base_url = '<?=$base_url?>';
$(document).ready(config_obj.other_config);
var level = <?php echo $json_level;?>;
var title = <?php echo $json_title;?>;
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=web_article&Users_ID=<?php echo $_SESSION["Users_ID"];?>',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	
	K('#QrcodeBgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#QrcodeBg').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#QrcodeBg').val(url);
					K('#QrcodeBgDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
	
	K('#ApplyBannerUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ApplyBanner').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ApplyBanner').val(url);
					K('#ApplyBannerDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li><a href="setting.php">分销设置</a></li>
        <li><a href="setting_withdraw.php">提现设置</a></li>
        <li class="cur"><a href="setting_other.php">其他设置</a></li>
        <li><a href="setting_protitle.php">爵位设置</a></li>
		<li><a href="setting_distribute.php">分销首页设置</a></li>
      </ul>
    </div>
    <div class="r_con_wrap">
      <form id="distribute_config_form" class="r_con_form" method="post" action="?">
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">分销相关设置</h2>
        <div class="rows">
          <label>自定义店名和头像</label>
          <span class="input">
           <input type="radio" name="Customize" id="c_0" value="0"<?php echo $rsConfig["Distribute_Customize"]==0 ? ' checked' : '';?>/><label for="c_0"> 关闭</label>&nbsp;&nbsp;
           <input type="radio" name="Customize" id="c_1" value="1"<?php echo $rsConfig["Distribute_Customize"]==1 ? ' checked' : '';?>/><label for="c_1"> 开启</label>
           <span class="tips">&nbsp;&nbsp;(设置分销商能否自定义店名与头像)</span>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
          <label>我的二维码背景图片</label>
          <span class="input">
           <span class="upload_file">
            <div>
             <div class="up_input"><input type="button" id="QrcodeBgUpload" value="上传图片" style="width:80px;" /></div>
             <div class="tips">图片建议尺寸：640*1010px</div>
             <div class="clear"></div>
            </div>
            <div class="img" id="QrcodeBgDetail" style="padding-top:8px;"><img src="<?php echo $rsConfig["QrcodeBg"] ? $rsConfig["QrcodeBg"] : '/static/api/distribute/images/qrcode_bg.jpg';?>" /></div>
           </span>
          </span>
          <div class="clear"></div>
        </div>
        
        <div class="rows">
            <label>成为分销商提醒页面banner</label>
            <span class="input">
             <span class="upload_file">
              <div>
               <div class="up_input"><input type="button" id="ApplyBannerUpload" value="上传图片" style="width:80px;" /></div>
               <div class="tips">图片建议尺寸：640*自定义</div>
               <div class="clear"></div>
              </div>
              <div class="img" id="ApplyBannerDetail" style="padding-top:8px;"><img src="<?php echo $rsConfig["ApplyBanner"] ? $rsConfig["ApplyBanner"] : '/static/api/distribute/images/apply_distribute.png';?>" /></div>
            </span>
          </span>
          <div class="clear"></div>
        </div>
        
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">分销商排行设置</h2>
        <div class="rows">
        	<label>总部分销商排行榜</label>
            <span class="input">
            	  <input type="radio" id="z_0" name="HIncomelist_Open" 
                  value="1"<?php echo $rsConfig["HIncomelist_Open"]==1 ? ' checked' : '';?>/><label for="z_0">公开</label>&nbsp;&nbsp;
           <input type="radio" id="z_1" name="HIncomelist_Open"  value="0" <?php echo $rsConfig["HIncomelist_Open"]==0 ? ' checked' : '';?>/><label for="z_1">不公开</label>
           <span class="tips">&nbsp;&nbsp;(仅上榜后才有权限查看)</span>
            </span>
            <div class="clear"></div>
        </div>
         
        <div class="rows">
        	<label>入榜最低佣金</label>
            <span class="input">
            	<input type="text" name="H_Incomelist_Limit" value="<?php echo $rsConfig["H_Incomelist_Limit"];?>" class="form_input" size="8" maxlength="10" /> <span class="tips">&nbsp;注:单位是元.</span>
            </span>
            <div class="clear"></div>
        </div>
        
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">代理设置</h2>
         <div class="rows" >
        	<label>分销商代理设置</label>
            <span class="input">
                <?php
					$dis_type_list = array('关闭','地区代理');
				?>
                <?php foreach($dis_type_list as $key=>$agent_name):?>
               <input type="radio" id="f_<?=$key?>" name="Dis_Agent_Type" value="<?=$key?>" <?=$key==$rsConfig["Dis_Agent_Type"]?'checked':''?> /><label for="f_<?=$key?>"><?=$agent_name?></label>&nbsp;&nbsp;                 
                <?php endforeach;?>
            </span>
            <div class="clear"></div>
        </div>
		
        <!--edit in 20160409-->
         <!-- 代理省级设置begin -->
        <div  class="rows" id="Agent_Rate_Row"<?php echo $rsConfig["Dis_Agent_Type"]>0 ? ' style="display:block"' : ' style="display:none"'?>>
        	<label>省级设置</label>
       		<span class="input" id="Agent_Rate_Input">				
                  <?php 
				    $Agent_Rate_list = json_decode($rsConfig['Agent_Rate'],TRUE);
				  ?>
            	 利润率%<input type="text" name="Agent_Rate[pro][Province]" value="<?=isset($Agent_Rate_list['pro']['Province'])?$Agent_Rate_list['pro']['Province']:''?>" class="form_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;代理价格<strong class="red">（元）</strong><input type="text" name="Agent_Rate[pro][Provincepro]" value="<?=isset($Agent_Rate_list['pro']['Provincepro'])?$Agent_Rate_list['pro']['Provincepro']:''?>" class="form_input" size="3" maxlength="10" notnull />认证条件：
				 
				 等级<select name="Agent_Rate[pro][Level]">
				 <option value="0" <?php echo (isset($Agent_Rate_list['pro']['Level'])?$Agent_Rate_list['pro']['Level']:'')== 0 ? ' selected' : '';?>>---选择等级---</option>				 
				 <?php if(!empty($distribute_level)):?>
				 <?php foreach($distribute_level as $key=>$level):?>				 
                   <option value="<?=$level['Level_ID']?>" <?php echo $level['Level_ID']==(isset($Agent_Rate_list['pro']['Level'])?$Agent_Rate_list['pro']['Level']:'') ? ' selected' : '';?>><?=$level['Level_Name']?></option>
				   <?php endforeach;?>
				   <?php endif;?>
              </select>
			  
			  
			  &nbsp;&nbsp;&nbsp;爵位<select name="Agent_Rate[pro][Protitle]">
			  <option value="0" <?php echo (isset($Agent_Rate_list['pro']['Protitle'])?$Agent_Rate_list['pro']['Protitle']:'')== 0 ? ' selected' : '';?>>---选择爵位---</option>
				 <?php if(!empty($dis_title_level)):?>
				 <?php foreach($dis_title_level as $key=>$title):?>
                   <option value="<?=$key?>" <?php echo $key==(isset($Agent_Rate_list['pro']['Protitle'])?$Agent_Rate_list['pro']['Protitle']:'') ? ' selected' : '';?>><?=$title['Name']?></option>
				   <?php endforeach;?>
				   <?php endif;?>
              </select>
			  
			  &nbsp;&nbsp;&nbsp;自费金额<strong class="red">（元）</strong><input type="text" name="Agent_Rate[pro][Selfpro]" value="<?=isset($Agent_Rate_list['pro']['Selfpro'])?$Agent_Rate_list['pro']['Selfpro']:''?>" class="form_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;团队销售额<strong class="red">（元）</strong><input type="text" name="Agent_Rate[pro][Teampro]" value="<?=isset($Agent_Rate_list['pro']['Teampro'])?$Agent_Rate_list['pro']['Teampro']:''?>" class="form_input" size="3" maxlength="10" notnull />	   			
        	</span>
        	<div class="clear"></div>
         </div>
         <!-- 代理省级设置end -->
		 
		 <!-- 代理市级设置begin -->
        <div class="rows" id="Agent_Rata_Row"<?php echo $rsConfig["Dis_Agent_Type"]>0 ? ' style="display:block"' : ' style="display:none"'?>>
        	<label>市级设置</label>
       		<span class="input" id="Agent_Rata_Input">				
                 利润率%<input type="text" name="Agent_Rate[cit][Province]" value="<?=isset($Agent_Rate_list['cit']['Province'])?$Agent_Rate_list['cit']['Province']:''?>" class="form_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;代理价格<strong class="red">（元）</strong><input type="text" name="Agent_Rate[cit][Provincepro]" value="<?=isset($Agent_Rate_list['cit']['Provincepro'])?$Agent_Rate_list['cit']['Provincepro']:''?>" class="form_input" size="3" maxlength="10" notnull />认证条件：
				 
				 等级<select name="Agent_Rate[cit][Level]">
				 <option value="0" <?php echo (isset($Agent_Rate_list['cit']['Level'])?$Agent_Rate_list['cit']['Level']:'')== 0 ? ' selected' : '';?>>---选择等级---</option>
				 <?php if(!empty($distribute_level)):?>
				 <?php foreach($distribute_level as $key=>$level):?>				 
                   <option value="<?=$level['Level_ID']?>" <?php echo $level['Level_ID']==(isset($Agent_Rate_list['cit']['Level'])?$Agent_Rate_list['cit']['Level']:'') ? ' selected' : '';?>><?=$level['Level_Name']?></option>
				   <?php endforeach;?>
				   <?php endif;?>
              </select>
			  
			  
			  &nbsp;&nbsp;&nbsp;爵位<select name="Agent_Rate[cit][Protitle]">
			  <option value="0" <?php echo (isset($Agent_Rate_list['cit']['Protitle'])?$Agent_Rate_list['cit']['Protitle']:'')== 0 ? ' selected' : '';?>>---选择爵位---</option>
				 <?php if(!empty($dis_title_level)):?>
				 <?php foreach($dis_title_level as $key=>$title):?>
                   <option value="<?=$key?>" <?php echo $key==(isset($Agent_Rate_list['cit']['Protitle'])?$Agent_Rate_list['cit']['Protitle']:'') ? ' selected' : '';?>><?=$title['Name']?></option>
				   <?php endforeach;?>
				   <?php endif;?>
              </select>
			  
			  &nbsp;&nbsp;&nbsp;自费金额<strong class="red">（元）</strong><input type="text" name="Agent_Rate[cit][Selfpro]" value="<?=isset($Agent_Rate_list['cit']['Selfpro'])?$Agent_Rate_list['cit']['Selfpro']:''?>" class="form_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;团队销售额<strong class="red">（元）</strong><input type="text" name="Agent_Rate[cit][Teampro]" value="<?=isset($Agent_Rate_list['cit']['Teampro'])?$Agent_Rate_list['cit']['Teampro']:''?>" class="form_input" size="3" maxlength="10" notnull />	   			
        	</span>
        	<div class="clear"></div>
         </div>
         <!-- 代理市级设置end -->
		 
		 <!-- 代理县级设置begin -->
        <div class="rows" id="Agent_Ratc_Row"<?php echo $rsConfig["Dis_Agent_Type"]>0 ? ' style="display:block"' : ' style="display:none"'?>>
        	<label>县（区）级设置</label>
       		<span class="input" id="Agent_Ratc_Input">				
                 利润率%<input type="text" name="Agent_Rate[cou][Province]" value="<?=isset($Agent_Rate_list['cou']['Province'])?$Agent_Rate_list['cou']['Province']:''?>" class="form_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;代理价格<strong class="red">（元）</strong><input type="text" name="Agent_Rate[cou][Provincepro]" value="<?=isset($Agent_Rate_list['cou']['Provincepro'])?$Agent_Rate_list['cou']['Provincepro']:''?>" class="form_input" size="3" maxlength="10" notnull />认证条件：
				 <?php if(!empty($distribute_level)):?>
				 等级<select name="Agent_Rate[cou][Level]">
				 <option value="0" <?php echo (isset($Agent_Rate_list['cou']['Level'])?$Agent_Rate_list['cou']['Level']:'')== 0 ? ' selected' : '';?>>---选择等级---</option>
				 <?php foreach($distribute_level as $key=>$level):?>				 
                   <option value="<?=$level['Level_ID']?>" <?php echo $level['Level_ID']==(isset($Agent_Rate_list['cou']['Level'])?$Agent_Rate_list['cou']['Level']:'') ? ' selected' : '';?>><?=$level['Level_Name']?></option>
				   <?php endforeach;?>
              </select>
			  <?php endif;?>
			  
			  &nbsp;&nbsp;&nbsp;爵位<select name="Agent_Rate[cou][Protitle]">
			  <option value="0" <?php echo (isset($Agent_Rate_list['cou']['Protitle'])?$Agent_Rate_list['cou']['Protitle']:'')== 0 ? ' selected' : '';?>>---选择爵位---</option>
				 <?php if(!empty($dis_title_level)):?>
				 <?php foreach($dis_title_level as $key=>$title):?>
                   <option value="<?=$key?>" <?php echo $key==(isset($Agent_Rate_list['cou']['Protitle'])?$Agent_Rate_list['cou']['Protitle']:'') ? ' selected' : '';?>><?=$title['Name']?></option>
				   <?php endforeach;?>
				   <?php endif;?>
              </select>
			  
			  &nbsp;&nbsp;&nbsp;自费金额<strong class="red">（元）</strong><input type="text" name="Agent_Rate[cou][Selfpro]" value="<?=isset($Agent_Rate_list['cou']['Selfpro'])?$Agent_Rate_list['cou']['Selfpro']:''?>" class="form_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;团队销售额<strong class="red">（元）</strong><input type="text" name="Agent_Rate[cou][Teampro]" value="<?=isset($Agent_Rate_list['cou']['Teampro'])?$Agent_Rate_list['cou']['Teampro']:''?>" class="form_input" size="3" maxlength="10" notnull />	   			
        	</span>
        	<div class="clear"></div>
         </div>
         <!-- 代理县级设置end -->
		 
		 <!-- 代理申请是否显示begin -->
        <div  class="rows" id="Agent_Ratb_Row"<?php echo $rsConfig["Dis_Agent_Type"]>0 ? ' style="display:block"' : ' style="display:none"'?>>
        	<label>申请入口是否开启</label>
       		<span class="input" id="Agent_Ratb_Input">				
			  <input type="radio" id="s_0" name="Agent_Rate[Agentenable]" 
                  value="1"<?php echo (isset($Agent_Rate_list['Agentenable'])?$Agent_Rate_list['Agentenable']:'') == 1 ? ' checked' : '';?>/><label for="s_0">开启</label>&nbsp;&nbsp;
           <input type="radio" id="s_1" name="Agent_Rate[Agentenable]"  value="0" <?php echo (isset($Agent_Rate_list['Agentenable'])?$Agent_Rate_list['Agentenable']:'') == 0 ? ' checked' : '';?>/><label for="s_1">关闭</label>
        	</span>
        	<div class="clear"></div>
         </div>
         <!-- 代理申请是否显示end -->
		 
		 <!-- 代理商姓名电话否显示begin -->
        <div  class="rows" id="Agent_Ratd_Row"<?php echo $rsConfig["Dis_Agent_Type"]>0 ? ' style="display:block"' : ' style="display:none"'?>>
        	<label>姓名电话显示设置</label>
       		<span class="input" id="Agent_Ratd_Input">				
			  <input type="radio" id="d_0" name="Agent_Rate[Nameshow]" 
                  value="1"<?php echo (isset($Agent_Rate_list['Nameshow'])?$Agent_Rate_list['Nameshow']:'') == 1 ? ' checked' : '';?>/><label for="d_0">显示</label>&nbsp;&nbsp;
           <input type="radio" id="d_1" name="Agent_Rate[Nameshow]"  value="0" <?php echo (isset($Agent_Rate_list['Nameshow'])?$Agent_Rate_list['Nameshow']:'') == 0 ? ' checked' : '';?>/><label for="d_1">隐藏</label>	   			
        	</span>
        	<div class="clear"></div>
         </div>
         <!-- 代理商姓名电话否显示end -->
		 
		 <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">股东设置</h2>
         <div class="rows" >
        	<label>分销商股东设置</label>
            <span class="input">
                <?php
					$sha_type_list = array('关闭','股东');
				?>
                <?php foreach($sha_type_list as $key=>$agent_name):?>
               <input type="radio" id="g_<?=$key?>" name="Sha_Agent_Type" value="<?=$key?>" <?=$key==$rsConfig["Sha_Agent_Type"]?'checked':''?> /><label for="g_<?=$key?>"><?=$agent_name?></label>&nbsp;&nbsp;                 
                <?php endforeach;?>
            </span>
            <div class="clear"></div>
        </div>		
		 <!-- 股东条件设置begin -->
        <div class="rows" id="Agent_Ratf_Row"<?php echo $rsConfig["Sha_Agent_Type"]==1 ? ' style="display:block"' : ' style="display:none"'?>>
        	<label>条件设置</label>
                <?php 
				    $Sha_Rate_list = json_decode($rsConfig['Sha_Rate'],TRUE);
				  ?>
       		<span class="input Agent_Ratf_Input">
				初级股东<input type="text" class="form_input sha_input1" size="12" name="Sha_Rate[sha][1][name]" placeholder="股东级别名称" value="<?=isset($Sha_Rate_list['sha'][1]['name'])?$Sha_Rate_list['sha'][1]['name']:''?>" notnull>&nbsp;&nbsp;&nbsp;
				分红%<input type="text" name="Sha_Rate[sha][1][Province]" value="<?=isset($Sha_Rate_list['sha'][1]['Province'])?$Sha_Rate_list['sha'][1]['Province']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />&nbsp;&nbsp;&nbsp;
                                <?php if(!empty($distribute_level)):?>
                 等级<select name="Sha_Rate[sha][1][Level]" class="sha_input">
				 <option value="0" <?php echo (isset($Sha_Rate_list['sha'][1]['Level'])?$Sha_Rate_list['sha'][1]['Level']:'')== 0 ? ' selected' : '';?>>---选择等级---</option>
				 <?php foreach($distribute_level as $key=>$level):?>				 
                                <option value="<?=$level['Level_ID']?>" <?php echo $level['Level_ID']==(isset($Sha_Rate_list['sha'][1]['Level'])?$Sha_Rate_list['sha'][1]['Level']:'') ? ' selected' : '';?>><?=$level['Level_Name']?></option>
				   <?php endforeach;?> 
              </select>
			  <?php endif;?>
			  <?php if(!empty($dis_title_level)):?>
                 &nbsp;&nbsp;&nbsp;爵位<select name="Sha_Rate[sha][1][Protitle]" class="sha_input">
			  <option value="0" <?php echo (isset($Sha_Rate_list['sha'][1]['Protitle'])?$Sha_Rate_list['sha'][1]['Protitle']:'')== 0 ? ' selected' : '';?>>---选择爵位---</option>
				 <?php foreach($dis_title_level as $key=>$title):?>
                   <option value="<?=$key?>" <?php echo $key==(isset($Sha_Rate_list['sha'][1]['Protitle'])?$Sha_Rate_list['sha'][1]['Protitle']:'') ? ' selected' : '';?>><?=$title['Name']?></option>
				   <?php endforeach;?>
              </select>
			  <?php endif;?>
			  &nbsp;&nbsp;&nbsp;自费金额<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][1][Selfpro]" value="<?=isset($Sha_Rate_list['sha'][1]['Selfpro'])?$Sha_Rate_list['sha'][1]['Selfpro']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;团队销售额<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][1][Teampro]" value="<?=isset($Sha_Rate_list['sha'][1]['Teampro'])?$Sha_Rate_list['sha'][1]['Teampro']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />	   			
        	  &nbsp;&nbsp;&nbsp;申请价格<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][1][price]" value="<?=isset($Sha_Rate_list['sha'][1]['price'])?$Sha_Rate_list['sha'][1]['price']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />
        	</span>
			<label></label>
       		<span class="input Agent_Ratf_Input" id="">
				中级股东<input type="text" class="form_input sha_input1" size="12" name="Sha_Rate[sha][2][name]" placeholder="股东级别名称" value="<?=isset($Sha_Rate_list['sha'][2]['name'])?$Sha_Rate_list['sha'][2]['name']:''?>" notnull>&nbsp;&nbsp;&nbsp;
				分红%<input type="text" name="Sha_Rate[sha][2][Province]" value="<?=isset($Sha_Rate_list['sha'][2]['Province'])?$Sha_Rate_list['sha'][2]['Province']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />&nbsp;&nbsp;&nbsp;
                                <?php if(!empty($distribute_level)):?>
                 等级<select name="Sha_Rate[sha][2][Level]" class="sha_input">
				 <option value="0" <?php echo (isset($Sha_Rate_list['sha'][2]['Level'])?$Sha_Rate_list['sha'][2]['Level']:'')== 0 ? ' selected' : '';?>>---选择等级---</option>
				 <?php foreach($distribute_level as $key=>$level):?>				 
                   <option value="<?=$level['Level_ID']?>" <?php echo $level['Level_ID']==(isset($Sha_Rate_list['sha'][2]['Level'])?$Sha_Rate_list['sha'][2]['Level']:'') ? ' selected' : '';?>><?=$level['Level_Name']?></option>
				   <?php endforeach;?>
              </select>
			  <?php endif;?>
			  <?php if(!empty($dis_title_level)):?>
			  &nbsp;&nbsp;&nbsp;爵位<select name="Sha_Rate[sha][2][Protitle]" class="sha_input">
			  <option value="0" <?php echo (isset($Sha_Rate_list['sha'][2]['Protitle'])?$Sha_Rate_list['sha'][2]['Protitle']:'')== 0 ? ' selected' : '';?>>---选择爵位---</option>
				 <?php foreach($dis_title_level as $key=>$title):?>
                   <option value="<?=$key?>" <?php echo $key==(isset($Sha_Rate_list['sha'][2]['Protitle'])?$Sha_Rate_list['sha'][2]['Protitle']:'') ? ' selected' : '';?>><?=$title['Name']?></option>
				   <?php endforeach;?>
              </select>
			  <?php endif;?>
			  &nbsp;&nbsp;&nbsp;自费金额<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][2][Selfpro]" value="<?=isset($Sha_Rate_list['sha'][2]['Selfpro'])?$Sha_Rate_list['sha'][2]['Selfpro']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;团队销售额<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][2][Teampro]" value="<?=isset($Sha_Rate_list['sha'][2]['Teampro'])?$Sha_Rate_list['sha'][2]['Teampro']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />	   			
        	  &nbsp;&nbsp;&nbsp;申请价格<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][2][price]" value="<?=isset($Sha_Rate_list['sha'][2]['price'])?$Sha_Rate_list['sha'][2]['price']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />
        	</span>
			<label></label>
       		<span class="input Agent_Ratf_Input" id="">
				高级股东<input type="text" class="form_input sha_input1" size="12" name="Sha_Rate[sha][3][name]" placeholder="股东级别名称" value="<?=isset($Sha_Rate_list['sha'][3]['name'])?$Sha_Rate_list['sha'][3]['name']:''?>" notnull>&nbsp;&nbsp;&nbsp;
				分红%<input type="text" name="Sha_Rate[sha][3][Province]" value="<?=isset($Sha_Rate_list['sha'][3]['Province'])?$Sha_Rate_list['sha'][3]['Province']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />&nbsp;&nbsp;&nbsp;
                                <?php if(!empty($distribute_level)):?>
                 等级<select name="Sha_Rate[sha][3][Level]" class="sha_input">
				 <option value="0" <?php echo (isset($Sha_Rate_list['sha'][3]['Level'])?$Sha_Rate_list['sha'][3]['Level']:'')== 0 ? ' selected' : '';?>>---选择等级---</option>
				 <?php foreach($distribute_level as $key=>$level):?>				 
                   <option value="<?=$level['Level_ID']?>" <?php echo $level['Level_ID']==(isset($Sha_Rate_list['sha'][3]['Level'])?$Sha_Rate_list['sha'][3]['Level']:'') ? ' selected' : '';?>><?=$level['Level_Name']?></option>
				   <?php endforeach;?>
              </select>
			  <?php endif;?>
			  <?php if(!empty($dis_title_level)):?>
			  &nbsp;&nbsp;&nbsp;爵位<select name="Sha_Rate[sha][3][Protitle]" class="sha_input">
			  <option value="0" <?php echo (isset($Sha_Rate_list['sha'][3]['Protitle'])?$Sha_Rate_list['sha'][3]['Protitle']:'')== 0 ? ' selected' : '';?>>---选择爵位---</option>
				 <?php foreach($dis_title_level as $key=>$title):?>
                   <option value="<?=$key?>" <?php echo $key==(isset($Sha_Rate_list['sha'][3]['Protitle'])?$Sha_Rate_list['sha'][3]['Protitle']:'') ? ' selected' : '';?>><?=$title['Name']?></option>
				   <?php endforeach;?>
              </select>
			  <?php endif;?>
			  &nbsp;&nbsp;&nbsp;自费金额<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][3][Selfpro]" value="<?=isset($Sha_Rate_list['sha'][3]['Selfpro'])?$Sha_Rate_list['sha'][3]['Selfpro']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />
				 &nbsp;&nbsp;&nbsp;团队销售额<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][3][Teampro]" value="<?=isset($Sha_Rate_list['sha'][3]['Teampro'])?$Sha_Rate_list['sha'][3]['Teampro']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />	   			
        	  &nbsp;&nbsp;&nbsp;申请价格<strong class="red">（元）</strong><input type="text" name="Sha_Rate[sha][3][price]" value="<?=isset($Sha_Rate_list['sha'][3]['price'])?$Sha_Rate_list['sha'][3]['price']:''?>" class="form_input sha_input" size="3" maxlength="10" notnull />
        	</span>
        	<div class="clear"></div>
         </div>
         <!-- 股东条件设置end -->
		 
		 <!-- 股东申请是否显示begin -->
        <div  class="rows" id="Agent_Ratg_Row"<?php echo $rsConfig["Sha_Agent_Type"]==1 ? ' style="display:block"' : ' style="display:none"'?>>
        	<label>申请入口是否开启</label>
       		<span class="input" id="Agent_Ratg_Input">				                
			  <input type="radio" id="q_0" name="Sha_Rate[Shaenable]" 
                  value="1"<?php echo (isset($Sha_Rate_list['Shaenable'])?$Sha_Rate_list['Shaenable']:'') == 1 ? ' checked' : '';?>/><label for="q_0">开启</label>&nbsp;&nbsp;
           <input type="radio" id="q_1" name="Sha_Rate[Shaenable]"  value="0" <?php echo (isset($Sha_Rate_list['Shaenable'])?$Sha_Rate_list['Shaenable']:'') == 0 ? ' checked' : '';?>/><label for="q_1">关闭</label>
        	</span>
        	<div class="clear"></div>
         </div>
         <!-- 代理申请是否显示end -->
		 
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">返本设置</h2>        
        <div class="rows">
        	<label>返本规则</label>
            <span class="input">
            	<input type="radio" id="j_0" name="Fanben" id="Fanben_0" value="0"<?php echo $rsConfig["Fanben_Open"]==0 ? ' checked' : ''?> /><label for="j_0"> 关闭</label>&nbsp;&nbsp;<input type="radio" id="j_1" name="Fanben" id="Fanben_1" value="1"<?php echo $rsConfig["Fanben_Open"]==1 ? ' checked' : ''?> /><label for="j_1"> 开启</label>
                <div style="display:<?php echo $rsConfig["Fanben_Open"]==0 ? 'none' : 'block'?>; margin:5px 0px">
                	直接下属 <input type="text" name="Fanben_Rules[0]" class="form_input" size="5" value="<?php echo empty($Fanben_Rules[0]) ? 1 : $Fanben_Rules[0];?>" style="text-align:center" /> 个，返现 <input type="text" name="Fanben_Rules[1]" class="form_input" size="5" value="<?php echo empty($Fanben_Rules[1]) ? 1 : $Fanben_Rules[1];?>" style="text-align:center" /> 元，返现 <input type="text" name="Fanben_Rules[2]" class="form_input" size="5" value="<?php echo empty($Fanben_Rules[2]) ? 0 : $Fanben_Rules[2];?>" style="text-align:center" /> 次
                    <br /><span class="tips">注：分销商每发展X个直接下属，则返现Y元，以此类推，可返Z次；返现次数若设置0，则不限次数。</span>
                </div>
            </span>
            <div class="clear"></div>
        </div>
        
        <div id="fanben_limit"<?php echo $rsConfig["Fanben_Open"] != 1 ? ' style="display:none"' : '';?>>
            <div class="rows">
              <label>直接下级限制</label>
              <span class="input">
                <select name="Type">
                    <option value="0"<?php echo $rsConfig["Fanben_Type"]==0 ? ' selected' : '';?>>无限制</option>
                    <option value="1"<?php echo $rsConfig["Fanben_Type"]==1 ? ' selected' : '';?>>购买商品</option>
                </select>
              </span>
              <div class="clear"></div>
            </div> 
		
            <div id="type_1"<?php echo $rsConfig["Fanben_Type"] != 1 || $rsConfig["Fanben_Open"]==0 ? ' style="display:none"' : '';?>>
              <div class="rows">
               <label>选择商品</label>
               <span class="input">
                <div class="products_option">
                    <div class="search_div">
                      <select>
                      <option value=''>--请选择--</option>
                      <?php foreach($category_list as $key=>$item):?>
                      <option value="<?=$key?>"><?=$item['Category_Name']?></option>
                       <?php if(!empty($item['child'])):?>              
                           <?php foreach($item['child'] as $cate_id=>$child):?>
                            <option value="<?php echo $child["Category_ID"];?>">&nbsp;&nbsp;&nbsp;&nbsp;<?=$child["Category_Name"]?></option>
                           <?php endforeach;?>
                       <?php endif;?>
                      <?php endforeach;?>
                     </select>
                     <input type="text" placeholder="关键字" value="" class="form_input" size="35" maxlength="30" />
                     <button type="button" class="button_search">搜索</button>
                   </div>
                   
                   <div class="select_items">
                     <select size='10' class="select_product0" style="width:300px; height:100px; display:block; float:left">
                     </select>
                     <button type="button" class="button_add">=></button>
                     <select size='10' class="select_product1" multiple style="width:300px; height:100px; display:block; float:left">
                        <?php if($rsConfig["Fanben_Type"]==1 && !empty($rsConfig["Fanben_Limit"])){
                            $DB->Get("shop_products","Products_Name,Products_ID,Products_PriceX","where Users_ID='".$_SESSION["Users_ID"]."' and Products_ID in(".$rsConfig["Fanben_Limit"].")");
                            while($r = $DB->fetch_assoc()){
                                echo '<option value="'.$r["Products_ID"].'">'.$r["Products_Name"].'---'.$r['Products_PriceX'].'</option>';							
                            }
                        }?>
                     </select>
                     <input type="hidden" id="limit" name="Limit[1]" value="<?php echo $rsConfig["Fanben_Type"]==1 && !empty($rsConfig["Fanben_Limit"]) ? ','.$rsConfig["Fanben_Limit"].',' : ''?>" />
                   </div>
                   
                   <div class="options_buttons">
                        <button type="button" class="button_remove">移除</button>
                        <button type="button" class="button_empty">清空</button>
                   </div>
                </div>
               </span>
               <div class="clear"></div>
             </div>
            </div>
        </div>
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">复销设置</h2>
        <div class="rows">
        	<label>复销规则</label>
            <span class="input">
            	<input type="radio" id="x_0" name="Fuxiao" id="Fuxiao_0" value="0"<?php echo $rsConfig["Fuxiao_Open"]==0 ? ' checked' : ''?> /><label for="x_0"> 关闭</label>&nbsp;&nbsp;<input type="radio" id="x_1" name="Fuxiao" id="Fuxiao_1" value="1"<?php echo $rsConfig["Fuxiao_Open"]==1 ? ' checked' : ''?> /><label for="x_1"> 开启</label>
                <div style="display:<?php echo $rsConfig["Fuxiao_Open"]==0 ? 'none' : 'block'?>; margin:5px 0px">
                	分销商每月需消费 <input type="text" name="Fuxiao_Rules[0]" class="form_input" size="5" value="<?php echo empty($Fuxiao_Rules[0]) ? 1 : $Fuxiao_Rules[0];?>" style="text-align:center" /> 元，否则冻结账户 <input type="text" name="Fuxiao_Rules[1]" class="form_input" size="5" value="<?php echo empty($Fuxiao_Rules[1]) ? 1 : $Fuxiao_Rules[1];?>" style="text-align:center" /> 天，提前 <input type="text" name="Fuxiao_Rules[2]" class="form_input" size="5" value="<?php echo empty($Fuxiao_Rules[2]) ? 1 : $Fuxiao_Rules[2];?>" style="text-align:center" /> 天开始提醒复销
                    <br /><span class="tips">注：分销商每月需消费M元以保持其分销商身份，否则。该分销账号将被冻结N天；在冻结期间每天将会向分销商发送复销提醒；若在冻结期间分销商没有进行复销，则该分销商账号将被删除；系统会在复销周期提前L天开始发送复销提醒</span>
                </div>
            </span>
            <div class="clear"></div>
        </div>
		
		<h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">邀请人限制</h2>
        <div class="rows">
        	<label>必须通过邀请人成为会员</label>
            <span class="input">
            	<input type="radio" id="b_0" name="Distribute_Limit" id="distribute_limit_0" value="0"<?php echo $rsConfig["Distribute_Limit"]==0 ? ' checked' : ''?> /><label for="b_0"> 关闭</label>&nbsp;&nbsp;<input type="radio" id="b_1" name="Distribute_Limit" id="distribute_limit_1" value="1"<?php echo $rsConfig["Distribute_Limit"]==1 ? ' checked' : ''?> /><label for="b_1"> 开启</label>
            </span>
            <div class="clear"></div>
        </div>
		
		<h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">进入商城限制</h2>
        <div class="rows">
        	<label>必须成为分销商</label>
            <span class="input">
            	<input type="radio" id="w_0" name="Distribute_ShopOpen" id="distribute_shop_0" value="0"<?php echo $rsConfig["Distribute_ShopOpen"]==0 ? ' checked' : ''?> /><label for="w_0"> 关闭</label>&nbsp;&nbsp;
				<input type="radio" id="w_1" name="Distribute_ShopOpen" id="distribute_shop_1" value="1"<?php echo $rsConfig["Distribute_ShopOpen"]==1 ? ' checked' : ''?> /><label for="w_1"> 开启</label>
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
        <input type="hidden" name="QrcodeBg" id="QrcodeBg" value="<?php echo $rsConfig["QrcodeBg"] ? $rsConfig["QrcodeBg"] : '/static/api/distribute/images/qrcode_bg.jpg';?>" /> 
        <input type="hidden" name="ApplyBanner" id="ApplyBanner" value="<?php echo $rsConfig["ApplyBanner"] ? $rsConfig["ApplyBanner"] : '/static/api/distribute/images/apply_distribute.png';?>" />
        </form>
    </div>
  </div>
</div>
</body>
</html>