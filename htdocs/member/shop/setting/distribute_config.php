<?php

ini_set("display_errors","On");
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/smarty.php');

//设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$template_dir = BASEPATH.'/member/shop/html';
$smarty->template_dir = $template_dir;

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}
$max_level = 9;
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

if($_POST){	
	$Menu = array();	
	$shop_config = Shop_Config::find($_SESSION["Users_ID"]);
	
	//分销模式
	$shop_config->Distribute_Method = !empty($_POST['Distribute_Method'])?$_POST['Distribute_Method']:0;
	
	//星火燎原模式相关设置
	$xinghuo_data = array(
		'JoinBg'=>$_POST['JoinBg'],
		'JoinMentDisplay'=>$_POST['JoinMentDisplay'],
		'JoinMent'=>htmlspecialchars($_POST['JoinMent'], ENT_QUOTES),
		'JoinFields'=>empty($_POST['JoinFields']) ? '' : $_POST['JoinFields']
	);
	
	//分销商门槛
	$shop_config->Distribute_Type = $_POST['Type'];
	switch($_POST['Type']){
		case 2:
			$shop_config->Distribute_Limit = $_POST["XiaofeiType"].'|'.(empty($_POST['Limit'][$_POST['Type']]) ? 0 : $_POST['Limit'][$_POST['Type']]);
		break;
		case 3:
			$shop_config->Distribute_Limit = $_POST["Fanwei"].'|'.(empty($_POST['Limit'][$_POST['Type']]) ? '' : substr($_POST['Limit'][$_POST['Type']],1,-1));
			//自定义菜单
			if(!empty($_POST['MName'])){
				foreach($_POST['MName'] as $k=>$v){
					if($v){
						$Menu[] = array(
							"name"=>$v,
							"order"=>empty($_POST['MOrder'][$k]) ? 0 : $_POST['MOrder'][$k],
							"link"=>empty($_POST['MLink'][$k]) ? '' : $_POST['MLink'][$k]
						);
					}
				}
			}
		break;
		default:
			$shop_config->Distribute_Limit = empty($_POST['Limit'][$_POST['Type']]) ? 0 : $_POST['Limit'][$_POST['Type']];
		break;
	}
	
	//提现门槛
	$shop_config->Withdraw_Type = $_POST['DType'];
	if($_POST['DType']==2){
		$shop_config->Withdraw_Limit = $_POST["DFanwei"].'|'.(empty($_POST['DLimit'][$_POST['DType']]) ? '' : substr($_POST['DLimit'][$_POST['DType']],1,-1));
	}else{
		$shop_config->Withdraw_Limit =empty($_POST['DLimit'][$_POST['DType']]) ? 0 : $_POST['DLimit'][$_POST['DType']];
	}
	$shop_config->Withdraw_PerLimit = empty($_POST['PerLimit']) ? 0 : $_POST['PerLimit'];
	$shop_config->Balance_Ratio = empty($_POST['Balance_Ratio']) ? 0 : $_POST['Balance_Ratio'];
	$shop_config->Poundage_Ratio = empty($_POST['Poundage_Ratio']) ? 0 : $_POST['Poundage_Ratio'];
	
	$shop_config->Distribute_Customize = $_POST['Customize'];
	$shop_config->HIncomelist_Open = $_POST['HIncomelist_Open'];
	$shop_config->H_Incomelist_Limit = !empty($_POST['H_Incomelist_Limit'])?$_POST['H_Incomelist_Limit']:0;
	$shop_config->QrcodeBg = $_POST['QrcodeBg'];
	$shop_config->ApplyBanner = $_POST['ApplyBanner'];
	$shop_config->Dis_Agent_Type = $_POST['Dis_Agent_Type'];
	$shop_config->Dis_Level = $_POST['Dis_Level'];

	$shop_config->Dis_Mobile_Level = $_POST['Dis_Mobile_Level'];
	$shop_config->Dis_Self_Bonus = !empty($_POST['Dis_Self_Bonus'])?$_POST['Dis_Self_Bonus']:0;
	
	
	$Bonus_Limit = array();
	if(!empty($_POST['Bonus_Limit'])){
		$Bonus_Limit =  $_POST['Bonus_Limit'];
		foreach($Bonus_Limit as $level=>$item){
			$Bonus_Limit[$level]['Enable'] = !empty($item['Enable'])?1:0;
		}
	}
	

	$shop_config->Dis_Bonus_Limit = json_encode($Bonus_Limit,JSON_UNESCAPED_UNICODE);

	if($_POST['Dis_Agent_Type'] == 0){
		$Agent_Rate = '';
	}elseif($_POST['Dis_Agent_Type'] == 1){
		$Agent_Rate = $_POST['Agent_Rate'];
	}elseif($_POST['Dis_Agent_Type'] == 2){
		$Agent_Rate = json_encode($_POST['Agent_Rate'],JSON_UNESCAPED_UNICODE);		
	}
	
	$shop_config->Agent_Rate = $Agent_Rate;
	if($_POST['Type']==3){
		$shop_config->DiyMenu = empty($Menu) ? '' : json_encode($Menu,JSON_UNESCAPED_UNICODE);
	}
	
	//返本规则	
	$shop_config->Fanben_Open = $_POST["Fanben"];
	if($_POST["Fanben"]==0){//关闭
		$shop_config->Fanben_Rules = '';
	}else{
		$_POST["Fanben_Rules"][0] = empty($_POST["Fanben_Rules"][0]) ? 1 : intval($_POST["Fanben_Rules"][0]);
		$_POST["Fanben_Rules"][1] = empty($_POST["Fanben_Rules"][1]) ? 1 : number_format($_POST["Fanben_Rules"][1],2,'.','');
		$_POST["Fanben_Rules"][2] = empty($_POST["Fanben_Rules"][2]) ? 0 : intval($_POST["Fanben_Rules"][2]);
		$shop_config->Fanben_Rules = json_encode($_POST["Fanben_Rules"],JSON_UNESCAPED_UNICODE);
	}
	
	//返本限制
	$shop_config->Fanben_Type = $_POST['FType'];
	if($_POST['FType']==1){
		$shop_config->Fanben_Limit = (empty($_POST['FLimit'][$_POST['FType']]) ? '' : substr($_POST['FLimit'][$_POST['FType']],1,-1));
	}else{
		$shop_config->Fanben_Limit = '';
	}
	
	//复销规则
	$shop_config->Fuxiao_Open = $_POST["Fuxiao"];
	if($_POST["Fuxiao"]==0){//关闭
		$shop_config->Fuxiao_Rules = '';
	}else{
		$_POST["Fuxiao_Rules"][0] = empty($_POST["Fuxiao_Rules"][0]) ? 1 : number_format($_POST["Fuxiao_Rules"][0],2,'.','');
		$_POST["Fuxiao_Rules"][1] = empty($_POST["Fuxiao_Rules"][1]) ? 1 : intval($_POST["Fuxiao_Rules"][1]);
		$_POST["Fuxiao_Rules"][2] = empty($_POST["Fuxiao_Rules"][2]) ? 1 : intval($_POST["Fuxiao_Rules"][2]);		
		$shop_config->Fuxiao_Rules = json_encode($_POST["Fuxiao_Rules"],JSON_UNESCAPED_UNICODE);
	}
	
	$Flag = $shop_config->save();
	
	if($Flag){
		//更新星火燎原模式设置
		$DB->Set('shop_xinghuo_config',$xinghuo_data,'where Users_ID="'.$_SESSION['Users_ID'].'"');
		echo '<script language="javascript">alert("设置成功");window.location="distribute_config.php";</script>';
	}else{
		echo '<script language="javascript">alert("保存失败");history.back();</script>';
	}
	exit;
}else{

	$rsConfig = shop_config($_SESSION["Users_ID"]);
	$dis_bonus_trs = '';
	$level =  $rsConfig['Dis_Self_Bonus']?$rsConfig['Dis_Level']+1:$rsConfig['Dis_Level'];
	$dis_bonus_trs  =  Dis_Config::getDisBonusTrs($smarty,$level,$rsConfig['Dis_Bonus_Limit']);
	
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
	
	//分销商门槛、提现门槛初始化
	$dis_limit = $withdraw_limit = array();
	$dis_limit[0] = $withdraw_limit[0] = 0;
	if($rsConfig["Distribute_Type"]==2 || $rsConfig["Distribute_Type"]==3){
		$dis_limit = explode("|",$rsConfig["Distribute_Limit"]);
	}
	
	if($rsConfig["Withdraw_Type"]==2){
		$withdraw_limit = explode("|",$rsConfig["Withdraw_Limit"]);
	}
	
	//自定义菜单初始化
	$MenuList = array();
	if($rsConfig["DiyMenu"]){
		$MenuList = $rsConfig["DiyMenu"] ? json_decode($rsConfig['DiyMenu'],true) : array();
	}
	
	//返本规则初始化
	$Fanben_Rules = $rsConfig["Fanben_Rules"] ? json_decode($rsConfig['Fanben_Rules'],true) : array('1','1','0');
	
	//复销规则初始化
	$Fuxiao_Rules = $rsConfig["Fuxiao_Rules"] ? json_decode($rsConfig['Fuxiao_Rules'],true) : array('1','1','1');
	
	$rsXinghuo = $DB->GetRs('shop_xinghuo_config','*','where Users_ID="'.$_SESSION['Users_ID'].'"');
	if(!$rsXinghuo){
		$Data = array(
			'JoinBg'=>'',
			'JoinMentDisplay'=>1,
			'JoinMent'=>'',
			'JoinFields'=>'',
			'Users_ID'=>$_SESSION['Users_ID']
		);
		$DB->Add('shop_xinghuo_config',$Data);
		$rsXinghuo = $Data;
	}
	$rsConfig['JoinBg'] = $rsXinghuo['JoinBg'];
	$rsConfig['JoinMent'] = $rsXinghuo['JoinMent'];
	$rsConfig['JoinFields'] = $rsXinghuo['JoinFields'];
	$rsConfig['JoinMentDisplay'] = $rsXinghuo['JoinMentDisplay'];
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
<script type='text/javascript' src='/static/member/js/shop.js'></script>

<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
    
<script type="text/javascript">
 var base_url = '<?=$base_url?>';

$(document).ready(shop_obj.dis_config_init);
KindEditor.ready(function(K) {
	K.create('textarea[name="JoinMent"]', {
		themeType : 'simple',
		filterMode : false,
		uploadJson : '/member/upload_json.php?TableField=web_column&Users_ID=<?php echo $_SESSION["Users_ID"];?>',
		fileManagerJson : '/member/file_manager_json.php',
		allowFileManager : true,
	
	});
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
	
	K('#JoinBgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#JoinBg').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#JoinBg').val(url);
					K('#JoinBgDetail').html('<img src="'+url+'" />');
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
        <li><a href="config.php">基本设置</a></li>
        <!--<li class=""><a href="other_config.php">活动设置</a></li>-->
        <li class="cur"><a href="distribute_config.php">分销设置</a></li>
        <li><a href="skin.php">风格设置</a></li>
        <li><a href="home.php">首页设置</a></li>
      </ul>
    </div>
    <div id="products" class="r_con_wrap">
      <form id="distribute_config_form" class="r_con_form" method="post" action="distribute_config.php">
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">分销基本设置</h2>        
        <div class="rows">
          <label>分销模式</label>
          <span class="input">
           	 <input type="radio" name="Distribute_Method" id="method_0" value="0"<?php echo $rsConfig["Distribute_Method"]==0 ? ' checked' : '';?> /><label for="method_0">普通模式</label>&nbsp;
           	 <input type="radio" name="Distribute_Method" id="method_1" value="1"<?php echo $rsConfig["Distribute_Method"]==1 ? ' checked' : '';?> /><label for="method_1">星火燎原模式</label>
          </span>
          <div class="clear"></div>
        </div>
        <div id="dis_method_0"<?php echo $rsConfig["Distribute_Method"] != 0 ? ' style="display:none"' : '';?>>
            <div class="rows">
              <label>成为分销商门槛</label>
              <span class="input">
               <select name="Type" id="type">
                 <option value="0"<?php echo $rsConfig["Distribute_Type"]==0 ? ' selected' : '';?>>无门槛，自动成为分销商</option>
                 <option value="1"<?php echo $rsConfig["Distribute_Type"]==1 ? ' selected' : '';?>>积分限制</option>
                 <option value="2"<?php echo $rsConfig["Distribute_Type"]==2 ? ' selected' : '';?>>消费金额限制</option>
                 <option value="3"<?php echo $rsConfig["Distribute_Type"]==3 ? ' selected' : '';?>>购买商品</option>
                 <option value="4"<?php echo $rsConfig["Distribute_Type"]==4 ? ' selected' : '';?>>手动申请</option>
               </select>
              </span>
              <div class="clear"></div>
            </div>
        
            <div class="rows" id="rows_1"<?php echo $rsConfig["Distribute_Type"] != 1 ? ' style="display:none"' : '';?>>
              <label>最低积分</label>
              <span class="input">
              <input type="text" name="Limit[1]" value="<?php echo $rsConfig["Distribute_Type"]==1 ? $rsConfig["Distribute_Limit"] : 0;?>" class="form_input" size="5" maxlength="10" /> <span class="tips">&nbsp;注:当用户积分达到此额度时自动成为分销商.</span>
              </span>
              <div class="clear"></div>
            </div>
        
            <div class="rows" id="rows_2"<?php echo $rsConfig["Distribute_Type"] != 2 ? ' style="display:none"' : '';?>>
              <label>最低消费额</label>
              <span class="input">
              <input type="radio" name="XiaofeiType" id="XiaofeiType_0" value="0"<?php echo $rsConfig["Distribute_Type"]<>2 || $dis_limit[0]==0 ? ' checked' : ''?> /><label> 总消费</label>&nbsp;&nbsp;<input type="radio" name="XiaofeiType" id="XiaofeiType_1" value="1"<?php echo $rsConfig["Distribute_Type"]==2 && $dis_limit[0]==1 ? ' checked' : ''?> /><label> 一次性消费</label><br />
              <input type="text" name="Limit[2]" value="<?php echo $rsConfig["Distribute_Type"]==2 ? $dis_limit[1] : 0;?>" class="form_input" size="5" maxlength="10" style="margin-top:8px;" /> <span class="tips">&nbsp;注:当用户总消费(或一次性)金额达到此额度时自动成为分销商，单位是元.</span>
              </span>
              <div class="clear"></div>
            </div>
        
            <div id="rows_3"<?php echo $rsConfig["Distribute_Type"] != 3 ? ' style="display:none"' : '';?>>
             <div class="rows">
               <label>选择商品</label>
               <span class="input">
                <input type="radio" name="Fanwei" id="Fanwei_0" value="0"<?php echo $rsConfig["Distribute_Type"]<>3 || $dis_limit[0]==0 ? ' checked' : ''?> /><label> 任意商品</label>&nbsp;&nbsp;<input type="radio" name="Fanwei" id="Fanwei_1" value="1"<?php echo $rsConfig["Distribute_Type"]==3 && $dis_limit[0]==1 ? ' checked' : ''?> /><label> 特定商品</label>
                <div class="products_option" style="display:<?php echo $rsConfig["Distribute_Type"]<>3 || $dis_limit[0]==0 ? 'none' : 'block'?>">
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
                        <?php if($rsConfig["Distribute_Type"]==3 && $dis_limit[0]==1 && !empty($dis_limit[1])){
                            $DB->Get("shop_products","Products_Name,Products_ID,Products_PriceX","where Users_ID='".$_SESSION["Users_ID"]."' and Products_ID in(".$dis_limit[1].")");
                            while($r = $DB->fetch_assoc()){
                                echo '<option value="'.$r["Products_ID"].'">'.$r["Products_Name"].'---'.$r['Products_PriceX'].'</option>';							
                            }
                        }?>
                     </select>
                     <input type="hidden" id="limit" name="Limit[3]" value="<?php echo $rsConfig["Distribute_Type"]==3 && $dis_limit[0]==1 && !empty($dis_limit[1]) ? ','.$dis_limit[1].',' : ''?>" />
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
                
            <div class="rows" id="rows_4"<?php echo $rsConfig["Distribute_Type"] != 4 ? ' style="display:none"' : '';?>>
              <label>是否需要审核</label>
              <span class="input">
               <input type="radio" name="Limit[4]" id="l_0" value="0"<?php echo $rsConfig["Distribute_Limit"]==0 ? ' checked' : '';?>/><label for="l_0"> 关闭</label>&nbsp;&nbsp;
               <input type="radio" name="Limit[4]" id="l_1" value="1"<?php echo $rsConfig["Distribute_Limit"]==1 ? ' checked' : '';?>/><label for="l_1"> 开启</label>
               <span class="tips">&nbsp;&nbsp;(开启审核后，用户提交申请后，要经过后台审核才能成为分销商)</span>
              </span>
              <div class="clear"></div>
            </div>
        
            <div class="rows">
               <label>自定义菜单</label>
               <span class="input">
                <div class="menu_add tips"><img src="/static/member/images/ico/add.gif" align="absmiddle" /> 添加菜单<font style="color:#F00">(该菜单将在分销商门槛、分销商提现门槛设置购买特定商品的购买页种显示)</font></div>
                <div class="blank9"></div>
                <table id="for_menu">
                  <tbody> 
                    <tr>
                      <td>
                        <select name='MOrder[]' >
                          <?php $i=0; for($i=0; $i<11; $i++){?>
                          <option value='<?php echo $i?>'><?php echo $i==0 ? '默认' : $i;?></option>
                          <?php }?>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form_input" value="" name="MName[]" />
                      </td>
                      <td>
                        <input type="text" class="form_input" size="65" value="" name="MLink[]" /><img src="/static/member/images/ico/search.png" style="width:22px; height:22px; margin:0px 0px 0px 5px; vertical-align:middle; cursor:pointer" class="btn_select_url" />
                      </td>
                      <td align="center"><a href="javascript:void(0);" class="items_del"><img src="/static/member/images/ico/del.gif" /></a></td>
                    </tr>
                  </tbody>
                </table>
                <table border="0" cellpadding="5" cellspacing="0" class="reverve_field_table" id="menubox">
                  <thead>
                    <tr>
                      <td width="12%">排序</td>
                      <td width="20%">菜单名称</td>
                      <td width="60%">链接</td>
                      <td width="8%" align="center">操作</td>
                    </tr>
                  </thead>
                  
                  <?php
                   if(!empty($MenuList)){
                       foreach($MenuList as $km=>$m){
                  ?>
                  <tbody> 
                    <tr>
                      <td>
                        <select name='MOrder[]'>
                          <?php $i=0; for($i=0; $i<11; $i++){?>
                          <option value='<?php echo $i?>'<?php echo $m["order"]==$i ? ' selected' : '';?>><?php echo $i==0 ? '默认' : $i;?></option>
                          <?php }?>
                        </select>
                      </td>
                      <td>
                        <input type="text" class="form_input" value="<?php echo $m["name"];?>" name="MName[]" />
                      </td>
                      <td>
                        <input type="text" class="form_input" size="65" value="<?php echo $m["link"];?>" name="MLink[]" id="distribute_url_<?php echo $km;?>" /><img src="/static/member/images/ico/search.png" style="width:22px; height:22px; margin:0px 0px 0px 5px; vertical-align:middle; cursor:pointer" class="btn_select_url" ret="<?php echo $km;?>" />
                      </td>
                      <td align="center"><a href="javascript:void(0);" class="items_del"><img src="/static/member/images/ico/del.gif" /></a></td>
                    </tr>
                  </tbody>
                  <?php }}?>
                </table>
               </span>
               <div class="clear"></div>
             </div>
        
            <div class="rows">
              <label>分销商申请页面banner</label>
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
        </div>
        <div id="dis_method_1"<?php echo $rsConfig["Distribute_Method"] != 1 ? ' style="display:none"' : '';?>>
        	<div class="rows">
              <label>分销级别</label>
              <span class="input">
               <a href="../dis_level.php">[设置]</a>
              </span>
              <div class="clear"></div>
            </div>
            <div class="rows">
              <label>成为分销页面banner</label>
              <span class="input">
               <span class="upload_file">
                <div>
                 <div class="up_input"><input type="button" id="JoinBgUpload" value="上传图片" style="width:80px;" /></div>
                 <div class="tips">图片建议尺寸：640*自定义</div>
                 <div class="clear"></div>
                </div>
                <div class="img" id="JoinBgDetail" style="padding-top:8px;"><img src="/static/api/distribute/images/apply_distribute.png" /></div>
               </span>
              </span>
              <div class="clear"></div>
            </div>
            <div class="rows">
              <label>购买分销协议条款</label>
              <span class="input">
               <textarea class="ckeditor" name="JoinMent" style="width:700px; height:300px;"><?php echo $rsConfig["JoinMent"] ?></textarea>
              </span>
              <div class="clear"></div>
            </div>
            <div class="rows">
              <label>协议条款是否显示</label>
              <span class="input">
               <input type="radio" name="JoinMentDisplay" id="j_0" value="0"<?php echo $rsConfig["JoinMentDisplay"]==0 ? ' checked' : '';?>/><label for="j_0"> 不显示</label>&nbsp;&nbsp;
               <input type="radio" name="JoinMentDisplay" id="j_1" value="1"<?php echo $rsConfig["JoinMentDisplay"]==1 ? ' checked' : '';?>/><label for="j_1"> 显示</label>
               <span class="tips">&nbsp;&nbsp;(购买分销协议条款是否在购买页面中显示)</span>
              </span>
              <div class="clear"></div>
            </div>
        </div>
        <div class="rows">
          <label>自定义店名和头像</label>
          <span class="input">
           <input type="radio" name="Customize" id="c_0" value="0"<?php echo $rsConfig["Distribute_Customize"]==0 ? ' checked' : '';?>/><label for="c_0"> 关闭</label>&nbsp;&nbsp;
           <input type="radio" name="Customize" id="c_1" value="1"<?php echo $rsConfig["Distribute_Customize"]==1 ? ' checked' : '';?>/><label for="c_1"> 开启</label>
           <span class="tips">&nbsp;&nbsp;(设置分销商能否自定义店名与头像)</span>
          </span>
          <div class="clear"></div>
        </div>
        
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">分销商级别设置</h2>
        <div class="rows">
        	<label>分销级别</label>
            <span class="input">
            	<select name="Dis_Level" id="Dis_Level">
             	
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
        	<label>分销商获得佣金限制</label>
            <span class="input" >
            	 <label>分销商消费额达到文本框内数额，方可获得佣金，单位为元,画勾表示开启此级别限制</label>
                 <table class="reverve_field_table" border="0" cellpadding="5" cellspacing="0" id="dis_bonus_get_config">
                
                    <tbody id="dis_bonus_trs">
                    	<?=$dis_bonus_trs?>               
                    </tbody>
                 </table>
                 
        
            </span>
            <div class="clear"></div>
        </div>
        
        <div class="rows">
        	<label>手机分销中心显示级别</label>
            <span class="input">
              <select name="Dis_Mobile_Level" id="Dis_Mobile_Level">
                <?php for($i=1;$i<=$rsConfig['Dis_Level'];$i++):?>
                	
                    <option value="<?=$i?>" <?=($rsConfig['Dis_Mobile_Level'] == $i)?'selected':''?>><?=$i?>级</option>
				
				<?php endfor;?>
              </select>
            </span>
            <div class="clear"></div>
        </div>
        
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">分销商提现设置</h2>
		<div class="rows">
          <label>分销商提现门槛</label>
          <span class="input">
          	<select name="DType" id="dtype">
             	<option value="0"<?php echo $rsConfig["Withdraw_Type"]==0 ? ' selected' : '';?>>无限制</option>
                <option value="1"<?php echo $rsConfig["Withdraw_Type"]==1 ? ' selected' : '';?>>所得佣金限制</option>
                <option value="2"<?php echo $rsConfig["Withdraw_Type"]==2 ? ' selected' : '';?>>购买商品</option>
           	</select>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows" id="drows_1"<?php echo $rsConfig["Withdraw_Type"] != 1 ? ' style="display:none"' : '';?>>
          <label>最低佣金</label>
          <span class="input">
          <input type="text" name="DLimit[1]" value="<?php echo $rsConfig["Withdraw_Type"]==1 ? $rsConfig["Withdraw_Limit"] : 0;?>" class="form_input" size="5" maxlength="10" /> <span class="tips">&nbsp;注:当分销商佣金达到此额度时才能有提现功能.</span>
          </span>
          <div class="clear"></div>
        </div>
        <div id="drows_2"<?php echo $rsConfig["Withdraw_Type"] != 2 ? ' style="display:none"' : '';?>>
          <div class="rows">
           <label>选择商品</label>
           <span class="input">
            <input type="radio" name="DFanwei" id="DFanwei_0" value="0"<?php echo $rsConfig["Withdraw_Type"]<>2 || $withdraw_limit[0]==0 ? ' checked' : ''?> /><label> 任意商品</label>&nbsp;&nbsp;<input type="radio" name="DFanwei" id="DFanwei_1" value="1"<?php echo $rsConfig["Withdraw_Type"]==2 && $withdraw_limit[0]==1 ? ' checked' : ''?> /><label> 特定商品</label>
            <div class="products_option" style="display:<?php echo $rsConfig["Withdraw_Type"]<>2 || $withdraw_limit[0]==0 ? 'none' : 'block'?>">
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
                 	<?php if($rsConfig["Withdraw_Type"]==2 && $withdraw_limit[0]==1 && !empty($withdraw_limit[1])){
						$DB->Get("shop_products","Products_Name,Products_ID,Products_PriceX","where Users_ID='".$_SESSION["Users_ID"]."' and Products_ID in(".$withdraw_limit[1].")");
						while($r = $DB->fetch_assoc()){
							echo '<option value="'.$r["Products_ID"].'">'.$r["Products_Name"].'---'.$r['Products_PriceX'].'</option>';							
						}
					}?>
                 </select>
                 <input type="hidden" id="limit" name="DLimit[2]" value="<?php echo $rsConfig["Withdraw_Type"]==2 && $withdraw_limit[0]==1 && !empty($withdraw_limit[1]) ? ','.$withdraw_limit[1].',' : ''?>" />
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
        
        <div class="rows">
          <label>每次提现最小金额</label>
          <span class="input">
          <input type="text" name="PerLimit" value="<?php echo $rsConfig["Withdraw_PerLimit"];?>" class="form_input" size="5" maxlength="10" /> <span class="tips">&nbsp;注:分销商每次申请提现时，所填写金额不得小于该值</span>
          </span>
          <div class="clear"></div>
        </div>
		
		<div class="rows">
          <label>提现余额分配比例</label>
          <span class="input">
          <input type="text" name="Balance_Ratio" value="<?php echo empty($rsConfig["Balance_Ratio"]) ? '' :$rsConfig["Balance_Ratio"];?>" class="form_input" size="5" maxlength="10" />% <span class="tips">&nbsp;注:提现时，以此百分比计算的金额发放到余额，此金额无法提现</span>
          </span>
          <div class="clear"></div>
        </div>
		
		<div class="rows">
          <label>提现手续费</label>
          <span class="input">
          <input type="text" name="Poundage_Ratio" value="<?php echo empty($rsConfig["Poundage_Ratio"]) ? '' :$rsConfig["Poundage_Ratio"];?>" class="form_input" size="5" maxlength="10" />% <span class="tips">&nbsp;注:提现时，扣除用户以此百分比计算的手续费</span>
          </span>
          <div class="clear"></div>
        </div>
        
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">分销商排行设置</h2>
        <div class="rows">
        	<label>总部分销商排行榜</label>
            <span class="input">
            	  <input type="radio" name="HIncomelist_Open" 
                  value="1"<?php echo $rsConfig["HIncomelist_Open"]==1 ? ' checked' : '';?>/><label for="c_0">公开</label>&nbsp;&nbsp;
           <input type="radio" name="HIncomelist_Open"  value="0" <?php echo $rsConfig["HIncomelist_Open"]==0 ? ' checked' : '';?>/><label for="c_1">不公开</label>
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
        	<label>分销商代理类型</label>
            <span class="input">
                <?php
					$dis_type_list = array('关闭','合伙人','地区代理');
				?>
                <?php foreach($dis_type_list as $key=>$agent_name):?>
               <input type="radio" name="Dis_Agent_Type" value="<?=$key?>" <?=$key==$rsConfig["Dis_Agent_Type"]?'checked':''?> /><label for="c_0"><?=$agent_name?></label>&nbsp;&nbsp;                 
                <?php endforeach;?>
            </span>
            <div class="clear"></div>
        </div>
        
         <!-- 代理利润率begin -->
        <div  class="rows" id="Agent_Rate_Row">
        <label>代理利润率</label>
        <?php if($rsConfig['Dis_Agent_Type'] != 0):?>
        	
        	
        		
                 <span class="input" id="Agent_Rate_Input">
        		
				<?php if($rsConfig['Dis_Agent_Type'] == 1):?>
            	 %<input type="text" name="Agent_Rate" value="<?=$rsConfig['Agent_Rate']?>" class="form_input" size="3" maxlength="10" notnull /> <span class="tips">占产品售价的百分比</span>
                <?php else: ?>
                  <?php 
				    $Agent_Rate_list = json_decode($rsConfig['Agent_Rate'],TRUE);
				  ?>
            	 省%<input type="text" name="Agent_Rate[Province]" value="<?= $Agent_Rate_list['Province']?>" class="form_input" size="3" maxlength="10" notnull />
				 市%<input type="text" name="Agent_Rate[City]" value="<?= $Agent_Rate_list['City']?>" class="form_input" size="3" maxlength="10" notnull />	 
	   			<?php endif; ?>
                
                 </span>
       		
        <?php else: ?>  
         
       	 <span class="input" id="Agent_Rate_Input"></span>
        <?php endif; ?>
        <div class="clear"></div>
         </div>
         <!-- 代理利润率end -->

        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">杂项设置</h2>
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
        	<label>返本规则</label>
            <span class="input">
            	<input type="radio" name="Fanben" id="Fanben_0" value="0"<?php echo $rsConfig["Fanben_Open"]==0 ? ' checked' : ''?> /><label> 关闭</label>&nbsp;&nbsp;<input type="radio" name="Fanben" id="Fanben_1" value="1"<?php echo $rsConfig["Fanben_Open"]==1 ? ' checked' : ''?> /><label> 开启</label>
                <div style="display:<?php echo $rsConfig["Fanben_Open"]==0 ? 'none' : 'block'?>; margin:5px 0px">
                	直接下属 <input type="text" name="Fanben_Rules[0]" class="form_input" size="5" value="<?php echo empty($Fanben_Rules[0]) ? 1 : $Fanben_Rules[0];?>" style="text-align:center" /> 个，返现 <input type="text" name="Fanben_Rules[1]" class="form_input" size="5" value="<?php echo empty($Fanben_Rules[1]) ? 1 : $Fanben_Rules[1];?>" style="text-align:center" /> 元，返现 <input type="text" name="Fanben_Rules[2]" class="form_input" size="5" value="<?php echo empty($Fanben_Rules[2]) ? 0 : $Fanben_Rules[2];?>" style="text-align:center" /> 次
                    <br /><span class="tips">注：分销商每发展X个直接下属，则返现Y元，以此类推，可返Z次；返现次数若设置0，则不限次数。</span>
                </div>
            </span>
            <div class="clear"></div>
        </div>
        
        <div class="rows" id="fanben_limit"<?php echo $rsConfig["Fanben_Open"] != 1 ? ' style="display:none"' : '';?>>
          <label>直接下级限制</label>
          <span class="input">
          	<select name="FType" id="ftype">
             	<option value="0"<?php echo $rsConfig["Fanben_Type"]==0 ? ' selected' : '';?>>无限制</option>
                <option value="1"<?php echo $rsConfig["Fanben_Type"]==1 ? ' selected' : '';?>>购买商品</option>
           	</select>
          </span>
          <div class="clear"></div>
        </div> 
		
        <div id="frows_1"<?php echo $rsConfig["Fanben_Type"] != 1 || $rsConfig["Fanben_Open"]==0 ? ' style="display:none"' : '';?>>
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
                 <input type="hidden" id="limit" name="FLimit[1]" value="<?php echo $rsConfig["Fanben_Type"]==1 && !empty($rsConfig["Fanben_Limit"]) ? ','.$rsConfig["Fanben_Limit"].',' : ''?>" />
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
        
        <div class="rows">
        	<label>复销规则</label>
            <span class="input">
            	<input type="radio" name="Fuxiao" id="Fuxiao_0" value="0"<?php echo $rsConfig["Fuxiao_Open"]==0 ? ' checked' : ''?> /><label> 关闭</label>&nbsp;&nbsp;<input type="radio" name="Fuxiao" id="Fuxiao_1" value="1"<?php echo $rsConfig["Fuxiao_Open"]==1 ? ' checked' : ''?> /><label> 开启</label>
                <div style="display:<?php echo $rsConfig["Fuxiao_Open"]==0 ? 'none' : 'block'?>; margin:5px 0px">
                	分销商每月需消费 <input type="text" name="Fuxiao_Rules[0]" class="form_input" size="5" value="<?php echo empty($Fuxiao_Rules[0]) ? 1 : $Fuxiao_Rules[0];?>" style="text-align:center" /> 元，否则冻结账户 <input type="text" name="Fuxiao_Rules[1]" class="form_input" size="5" value="<?php echo empty($Fuxiao_Rules[1]) ? 1 : $Fuxiao_Rules[1];?>" style="text-align:center" /> 天，提前 <input type="text" name="Fuxiao_Rules[2]" class="form_input" size="5" value="<?php echo empty($Fuxiao_Rules[2]) ? 1 : $Fuxiao_Rules[2];?>" style="text-align:center" /> 天开始提醒复销
                    <br /><span class="tips">注：分销商每月需消费M元以保持其分销商身份，否则。该分销账号将被冻结N天；在冻结期间每天将会向分销商发送复销提醒；若在冻结期间分销商没有进行复销，则该分销商账号将被删除；系统会在复销周期提前L天开始发送复销提醒</span>
                </div>
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
        <input type="hidden" name="JoinBg" id="JoinBg" value="<?php echo $rsConfig["JoinBg"] ? $rsConfig["JoinBg"] : '/static/api/distribute/images/apply_distribute.png';?>" />     
      </form>
    </div>
  </div>
</div>
</body>
</html>