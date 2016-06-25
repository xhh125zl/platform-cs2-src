<?php
ini_set("display_errors","On");

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

$base_url = base_url();
$distribute_level = Dis_Level::get_dis_pro_level($_SESSION["Users_ID"]);

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
	$dis_config = Dis_Config::find($_SESSION["Users_ID"]);
	
	//提现门槛
	$dis_config->Withdraw_Type = $_POST['Type'];
	if($_POST['Type']==2){
		$dis_config->Withdraw_Limit = $_POST["Fanwei"].'|'.(empty($_POST['Limit'][$_POST['Type']]) ? '' : substr($_POST['Limit'][$_POST['Type']],1,-1));
	}elseif($_POST['Type']==3){
		$dis_config->Withdraw_Limit = empty($_POST['Limit'][$_POST['Type']]) ? 0 : $_POST['Limit'][$_POST['Type']];
	}else{
		$dis_config->Withdraw_Limit =empty($_POST['Limit'][$_POST['Type']]) ? 0 : $_POST['Limit'][$_POST['Type']];
	}
	$dis_config->Withdraw_PerLimit = empty($_POST['PerLimit']) ? 0 : $_POST['PerLimit'];
	$dis_config->Balance_Ratio = empty($_POST['Balance_Ratio']) ? 0 : $_POST['Balance_Ratio'];
	$dis_config->Poundage_Ratio = empty($_POST['Poundage_Ratio']) ? 0 : $_POST['Poundage_Ratio'];
	$dis_config->TxCustomize = empty($_POST['TxCustomize']) ? 0 : $_POST['TxCustomize'];
	$Flag = $dis_config->save();
	
	if($Flag){
		echo '<script language="javascript">alert("设置成功");window.location.href="setting_withdraw.php";</script>';
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
	
	//分销商门槛、提现门槛初始化
	$withdraw_limit = array();
	$withdraw_limit[0] = 0;
	
	if($rsConfig["Withdraw_Type"]==2){
		$withdraw_limit = explode("|",$rsConfig["Withdraw_Limit"]);
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
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/distribute/config.js?t=1253004587'></script>
    
<script type="text/javascript">
	$(document).ready(config_obj.withdraw_config);
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
        <li class="cur"><a href="setting_withdraw.php">提现设置</a></li>
        <li><a href="setting_other.php">其他设置</a></li>
        <li><a href="setting_protitle.php">爵位设置</a></li>
		<li><a href="setting_distribute.php">分销首页设置</a></li>
      </ul>
    </div>
    <div class="r_con_wrap">
      <form id="distribute_config_form" class="r_con_form" method="post" action="?">
		<div class="rows">
          <label>分销商提现门槛</label>
          <span class="input">
          	<select name="Type">
             	<option value="0"<?php echo $rsConfig["Withdraw_Type"]==0 ? ' selected' : '';?>>无限制</option>
                <option value="1"<?php echo $rsConfig["Withdraw_Type"]==1 ? ' selected' : '';?>>所得佣金限制</option>
                <option value="2"<?php echo $rsConfig["Withdraw_Type"]==2 ? ' selected' : '';?>>购买商品</option>
				<option value="3"<?php echo $rsConfig["Withdraw_Type"]==3 ? ' selected' : '';?>>等级限制</option>
           	</select>
          </span>
          <div class="clear"></div>
        </div>
        <div id="type_1"<?php echo $rsConfig["Withdraw_Type"] != 1 ? ' style="display:none"' : '';?>>
            <div class="rows">
              <label>最低佣金</label>
              <span class="input">
              <input type="text" name="Limit[1]" value="<?php echo $rsConfig["Withdraw_Type"]==1 ? $rsConfig["Withdraw_Limit"] : 0;?>" class="form_input" size="5" maxlength="10" /> <span class="tips">&nbsp;注:当分销商佣金达到此额度时才能有提现功能.</span>
              </span>
              <div class="clear"></div>
            </div>
        </div>
        <div id="type_2"<?php echo $rsConfig["Withdraw_Type"] != 2 ? ' style="display:none"' : '';?>>
            <div class="rows">
               <label>选择商品</label>
               <span class="input">
                <input type="radio" name="Fanwei" id="Fanwei_0" value="0"<?php echo $rsConfig["Withdraw_Type"]<>2 || $withdraw_limit[0]==0 ? ' checked' : ''?> /><label> 任意商品</label>&nbsp;&nbsp;<input type="radio" name="Fanwei" id="Fanwei_1" value="1"<?php echo $rsConfig["Withdraw_Type"]==2 && $withdraw_limit[0]==1 ? ' checked' : ''?> /><label> 特定商品</label>
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
                     <input type="hidden" id="limit" name="Limit[2]" value="<?php echo $rsConfig["Withdraw_Type"]==2 && $withdraw_limit[0]==1 && !empty($withdraw_limit[1]) ? ','.$withdraw_limit[1].',' : ''?>" />
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
		<div id="type_3"<?php echo $rsConfig["Withdraw_Type"] != 3 ? ' style="display:none"' : '';?>>
            <div class="rows">
              <label>最低等级</label>
              <span class="input">
              <select name="Limit[3]">
				 <option value="0" <?php echo ($rsConfig["Withdraw_Type"]==3 ? $rsConfig["Withdraw_Limit"] : 0) == 0 ? ' selected' : '';?>>---选择等级---</option>
				 <?php if(!empty($distribute_level)):?>
				 <?php foreach($distribute_level as $key=>$level):?>				 
                   <option value="<?=$level['Level_ID']?>" <?php echo $level['Level_ID']==($rsConfig["Withdraw_Type"]==3 ? $rsConfig["Withdraw_Limit"] : '') ? ' selected' : '';?>><?=$level['Level_Name']?></option>
				   <?php endforeach;?>
				   <?php else:?>
				   没有设置等级
				   <?php endif;?>
              </select>
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
		<div class="rows">
          <label>提现是否审核</label>
          <span class="input">
          <input type="radio" name="TxCustomize" id="c_0" value="0"<?php echo $rsConfig["TxCustomize"]==0 ? ' checked' : '';?>/><label for="c_0"> 关闭</label>&nbsp;&nbsp;
           <input type="radio" name="TxCustomize" id="c_1" value="1"<?php echo $rsConfig["TxCustomize"]==1 ? ' checked' : '';?>/><label for="c_1"> 开启</label><span class="tips">&nbsp;&nbsp;注:仅对微信红包及转账有效</span>
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