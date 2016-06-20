<?php

if(empty($_SESSION["Users_Account"])){
	header("location:/member/login.php");
}

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

if(empty($_REQUEST['LevelID'])){
	echo '缺少必要的参数';
	exit;
}

$LevelID = $_REQUEST['LevelID'];

if(empty($_REQUEST['level'])){
	echo '缺少必要的参数';
	exit;
}

if(!isset($_REQUEST['type'])){
	echo '缺少必要的参数';
	exit;
}

$level = $_REQUEST['level'];
$type = $_REQUEST['type'];
$first = $DB->GetRs('distribute_level','Level_ID','where Users_ID="'.$_SESSION["Users_ID"].'" order by Level_ID asc');//获取第一个分销商级别
if($_POST){
	$PeopleLimit = array();
	foreach($_POST['PeopleLimit'] as $k=>$v){
		$PeopleLimit[$k] = empty($v) ? 0 : intval($v);
	}
	
	if($first['Level_ID']==$LevelID){
		if($_POST['Come_Type'] == 3){
			$type_confirm = 3;
		}else{
			$type_confirm = $type;
		}
	}else{
		$type_confirm = $type;
	}
	$Data=array(
		"Level_Name"=>$_POST['Name'],
		"Level_ImgPath"=>$_POST['ImgPath'],
		"Level_LimitType"=>$type_confirm,
		"Level_PeopleLimit"=>empty($PeopleLimit) ? '' : json_encode($PeopleLimit,JSON_UNESCAPED_UNICODE)
	);
	
	if($type==0){//直接购买
		if($first['Level_ID']==$LevelID){
			if($_POST['Come_Type']==1){
				if(empty($_POST['Price'])){
					echo '<script language="javascript">alert("请填写级别价格！");history.back();</script>';
					exit;
				}
				$Distributes = array();
				foreach($_POST['Distributes'] as $key=>$val){
					$Distributes[$key] = empty($val) ? 0 : number_format($val,2,'.','');
				}
				$Data['Level_LimitValue'] = $_POST['Price'];
				$Data['Level_Distributes'] = empty($Distributes) ? '' : json_encode($Distributes,JSON_UNESCAPED_UNICODE);				
			}else{
				$Data['Level_LimitValue'] = '';
				$Data['Level_Distributes'] = '';	
			}
		}else{
			$Distributes = array();
			foreach($_POST['Distributes'] as $key=>$val){
				$Distributes[$key] = empty($val) ? 0 : number_format($val,2,'.','');
			}
			$Data['Level_LimitValue'] = $_POST['Price'];
			$Data['Level_Distributes'] = empty($Distributes) ? '' : json_encode($Distributes,JSON_UNESCAPED_UNICODE);
		}
	}elseif($type==1){//消费额限制
		if($first['Level_ID']==$LevelID){
			if($_POST['Come_Type']==1){
				if(empty($_POST['Xiaofei_Amount'])){
					echo '<script language="javascript">alert("请填写消费额！");history.back();</script>';
					exit;
				}
				$Data['Level_LimitValue'] = $_POST['Xiaofei_Type'].'|'.number_format($_POST['Xiaofei_Amount'],2,'.','').'|'.$_POST['Xiaofei_Time'];
			}else{
				$Data['Level_LimitValue'] = '';
			}
		}else{
			$Data['Level_LimitValue'] = $_POST['Xiaofei_Type'].'|'.number_format($_POST['Xiaofei_Amount'],2,'.','').'|'.$_POST['Xiaofei_Time'];
		}		
	}elseif($type==2){//购买商品
		if($first['Level_ID']==$LevelID){
			if($_POST['Come_Type']==1){
				if($_POST['Fanwei']==1){
					if(empty($_POST['BuyIDs'])){
						echo '<script language="javascript">alert("请选择商品！");history.back();</script>';
						exit;
					}
					$Data['Level_LimitValue'] = $_POST['Fanwei'].'|'.substr($_POST['BuyIDs'],1,-1);
				}else{
					$Data['Level_LimitValue'] = $_POST['Fanwei'];
				}
			}else{
				$Data['Level_LimitValue'] = '';
			}
		}else{		
			if($_POST['Fanwei']==1){
				if(empty($_POST['BuyIDs'])){
					echo '<script language="javascript">alert("请选择商品！");history.back();</script>';
					exit;
				}
				$Data['Level_LimitValue'] = $_POST['Fanwei'].'|'.substr($_POST['BuyIDs'],1,-1);
			}else{
				$Data['Level_LimitValue'] = $_POST['Fanwei'];
			}
		}
	}
	
	//升级相关
	
	if($LevelID != $first['Level_ID']){
		if($_POST['Update_Type']==0){//补差价
			if(empty($_POST['UpdatePrice'])){
				echo '<script language="javascript">alert("请填写差价！");history.back();</script>';
				exit;
			}
			$UpdateDistributes = array();
			foreach($_POST['UpdateDistributes'] as $key=>$val){
				$UpdateDistributes[$key] = empty($val) ? 0 : number_format($val,2,'.','');
			}
			$Data['Level_UpdateValue'] = $_POST['UpdatePrice'];
			$Data['Level_UpdateDistributes'] = empty($UpdateDistributes) ? '' : json_encode($UpdateDistributes,JSON_UNESCAPED_UNICODE);
		}else{
			if(empty($_POST['UpdateBuyIDs'])){
				echo '<script language="javascript">alert("请选择商品！");history.back();</script>';
				exit;
			}
			$Data['Level_UpdateValue'] = substr($_POST['UpdateBuyIDs'],1,-1);
			$Data['Level_UpdateDistributes'] = '';
		}
		$Data['Level_UpdateType'] = $_POST['Update_Type'];
	}
	
	$Flag=$DB->Set("distribute_level",$Data,'where Users_ID="'.$_SESSION['Users_ID'].'" and Level_ID='.$LevelID);
	if($Flag){
		//更新分销商级别文件
		update_dis_level($DB,$_SESSION['Users_ID']);
		echo '<script language="javascript">alert("修改成功！");window.location.href="level.php?level='.$level.'&type='.$type.'";</script>';
		exit;
	}else{
		echo '<script language="javascript">alert("修改失败！");history.back();</script>';
		exit;
	}
}else{
	$rsLevel = $DB->GetRs('distribute_level','*','where Users_ID="'.$_SESSION["Users_ID"].'" and Level_ID='.$LevelID);
	if(!$rsLevel){
		echo '您要修改的级别不存在';
		exit;
	}
	
	$shop_config = shop_config($_SESSION["Users_ID"]);
	
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
	
	$PeopleLimit = json_decode($rsLevel['Level_PeopleLimit'],true);
	
	if($type==0){//直接购买
		if($rsLevel['Level_LimitType']==$type){
			$Distributes = json_decode($rsLevel['Level_Distributes'],true);
		}else{
			$rsLevel['Level_LimitValue'] = '';
			$Distributes = array();
		}
	}elseif($type==1){//消费额
		if($rsLevel['Level_LimitType']==$type){
			$limit_arr = explode('|',$rsLevel['Level_LimitValue']);
		}else{
			$limit_arr = array(0,'',4);
		}
	}elseif($type == 2){//购买产品
		$products = array();
		if($rsLevel['Level_LimitType']==$type){
			$limit_arr = explode('|',$rsLevel['Level_LimitValue']);
			if($limit_arr[0]==1 && $limit_arr[1]){
				$DB->Get('shop_products','Products_ID,Products_Name,Products_PriceX','where Users_ID="'.$_SESSION['Users_ID'].'" and Products_ID in('.$limit_arr[1].')');
				while($r = $DB->fetch_assoc()){
					$products[] = $r;
				}
			}
		}else{
			$limit_arr = array(0,'');
		}
	}
	$arr = array('一','二','三','四','五','六','七','八','九','十');
	$_TYPE = array('直接购买','消费额','购买商品','无门槛');
	
	//升级设置相关
	$UpdateDistributes = $updateproducts = array();
	if($rsLevel['Level_ID'] != $first['Level_ID']){//第一个级别没有升级设置
		if($rsLevel['Level_UpdateType']==0){//补差价
			$UpdateDistributes = json_decode($rsLevel['Level_UpdateDistributes'],true);
		}else{//购买指定商品
			if($rsLevel['Level_UpdateValue']){
				$DB->Get('shop_products','Products_ID,Products_Name,Products_PriceX','where Users_ID="'.$_SESSION['Users_ID'].'" and Products_ID in('.$rsLevel['Level_UpdateValue'].')');
				while($r = $DB->fetch_assoc()){
					$updateproducts[] = $r;
				}
			}
		}
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
<link href='/static/member/css/shop.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/member/js/distribute/dis_level.js'></script>
<link rel="stylesheet" href="/third_party/kindeditor/themes/default/default.css" />
<script type='text/javascript' src="/third_party/kindeditor/kindeditor-min.js"></script>
<script type='text/javascript' src="/third_party/kindeditor/lang/zh_CN.js"></script>
<script>
$(document).ready(dis_level.level_edit);
KindEditor.ready(function(K) {
	var editor = K.editor({
		uploadJson : '/member/upload_json.php?TableField=distribute',
		fileManagerJson : '/member/file_manager_json.php',
		showRemote : true,
		allowFileManager : true,
	});
	K('#ImgUpload').click(function(){
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				imageUrl : K('#ImgPath').val(),
				clickFn : function(url, title, width, height, border, align){
					K('#ImgPath').val(url);
					K('#ImgDetail').html('<img src="'+url+'" />');
					editor.hideDialog();
				}
			});
		});
	});
})
</script>
<style type="text/css">
#ImgDetail img{width:100px; margin-top:8px}
</style>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    
    <div id="level" class="r_con_wrap">
      <form id="level_form" class="r_con_form" method="post" action="?">
      	<h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">基本设置</h2>    
        <div class="rows">
          <label>级别名称</label>
          <span class="input">
          <input name="Name" value="<?php echo $rsLevel['Level_Name'];?>" type="text" class="form_input" size="20" notnull>
          <font class="fc_red">*</font>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>级别图片</label>
          <span class="input"> <span class="upload_file">
          <div>
            <div class="up_input">
              <input type="button" id="ImgUpload" value="添加图片" style="width:80px;" />
            </div>
            <div class="tips">图片建议尺寸：200*120</div>
            <div class="clear"></div>
          </div>
          <div class="img" id="ImgDetail"><?php if($rsLevel['Level_ImgPath']){?><img src="<?php echo $rsLevel['Level_ImgPath'];?>" width="100" /><?php }?></div>
          </span> </span>
          <input name="ImgPath" id="ImgPath" type="hidden" value="<?php echo $rsLevel['Level_ImgPath'];?>">
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>佣金人数限制</label>
          <span class="input">
            <table class="item_data_table" border="0" cellpadding="3" cellspacing="0">
            <?php 
				$arr = array('一','二','三','四','五','六','七','八','九','十');
				for($i=0;$i<$level;$i++){
			?>
				<tr>
					<td>
						<?php echo $arr[$i]?>级
                        &nbsp;&nbsp;
						<input name="PeopleLimit[<?php echo $i+1;?>]" value="<?php echo empty($PeopleLimit[$i+1]) ? 0 : $PeopleLimit[$i+1];?>" class="form_input" size="5" maxlength="10" type="text">
						个
					</td>
				</tr>
			<?php }?>
            </table>
            <div class="tips" style="height:80px; line-height:20px;">注：此级别的分销商获得佣金的人数限制。<br />
            如：一级 3、二级 -1、三级 -1，说明此级别分销商只能获得3个下属的一级佣金，不能获得二级、三级佣金；<br />
            0表示不限制，-1 表示禁止获得此级别佣金。<br />
            此设置对于发展下级会员人数不起作用
            </div>
          </span>
          <div class="clear"></div>
        </div>
        <?php if($first['Level_ID']==$LevelID){?>
        <div class="rows">
          <label>级别门槛</label>
          <span class="input">
           <input name="Come_Type" value="3" id="radio_0" type="radio"<?php echo $rsLevel['Level_LimitType']==3 ? ' checked' : ''?> ><label for="radio_0">无门槛</label>&nbsp;&nbsp;
           <input name="Come_Type" value="1" id="radio_1" type="radio"<?php echo $rsLevel['Level_LimitType']<>3 ? ' checked' : ''?>><label for="radio_1"><?php echo $_TYPE[$type];?></label>
          </span>
          <div class="clear"></div>
        </div>
        <?php }?>
        <?php if($first['Level_ID']==$LevelID){?>
        <div id="type_1"<?php if($rsLevel['Level_LimitType']==3){?> style="display:none"<?php }?>>
        <?php }?>
        <?php if($type==0){//直接购买?>
        <div class="rows">
          <label>级别价格</label>
          <span class="input">
           <input name="Price" value="<?php echo $rsLevel['Level_LimitValue'];?>" type="text" class="form_input" size="5" <?php if($first['Level_ID']==$LevelID){?><?php }else{?>notnull<?php }?>> 元<span class="tips">&nbsp;&nbsp;注：用户购买此级别的价格</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>佣金发放设置</label>
          <span class="input">
            <table class="item_data_table" border="0" cellpadding="3" cellspacing="0">
            <?php 
                $arr = array('一','二','三','四','五','六','七','八','九','十');
                for($i=0;$i<$level;$i++){
            ?>
                <tr>
                    <td>
                        <?php echo $arr[$i]?>级
                        &nbsp;&nbsp;
                        <input name="Distributes[<?php echo $i+1;?>]" value="<?php echo empty($Distributes[$i+1]) ? 0 : $Distributes[$i+1];?>" class="form_input" size="5" maxlength="10" type="text">
                        元
                    </td>
                </tr>
            <?php }?>
            </table>
            <div class="tips">注：会员购买此级别时，其上级获得的佣金</div>
          </span>
          <div class="clear"></div>
        </div>
        <?php }elseif($type==1){//消费额?>
        <div class="rows">
          <label>消费类型</label>
          <span class="input">
           <input name="Xiaofei_Type" value="0" id="xiaofei_0" type="radio"<?php echo $limit_arr[0]==0 ? ' checked' : ''?> ><label for="xiaofei_0">商城总消费</label>&nbsp;&nbsp;
           <input name="Xiaofei_Type" value="1" id="xiaofei_1" type="radio"<?php echo $limit_arr[0]==1 ? ' checked' : ''?>><label for="xiaofei_1">一次性消费</label>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>消费金额</label>
          <span class="input">
           <input name="Xiaofei_Amount" value="<?php echo $limit_arr[1]?>" type="text" class="form_input" size="5" <?php if($first['Level_ID']==$LevelID){?><?php }else{?>notnull<?php }?>> 元<span class="tips">&nbsp;&nbsp;注：用户需消费此额度才能成为该级别分销商</span>
          </span>
          <div class="clear"></div>
        </div>
        <div class="rows">
          <label>消费额计入时间</label>
          <span class="input">
           <input name="Xiaofei_Time" type="radio" value="2" id="time_2"<?php echo $limit_arr[2]==2 ? ' checked' : ''?>><label for="time_2">订单付款后计入</label>&nbsp;&nbsp;
           <input name="Xiaofei_Time" type="radio" value="4" id="time_4"<?php echo $limit_arr[2]==4 ? ' checked' : ''?>><label for="time_4">订单确认收货后计入</label>
          </span>
          <div class="clear"></div>
        </div>
        <?php }elseif($type==2){//购买商品?>
        <div class="rows">
               <label>选择商品</label>
               <span class="input">
                <input type="radio" name="Fanwei" id="Fanwei_0" value="0"<?php echo $limit_arr[0]==0 ? ' checked' : ''?> /><label> 任意商品</label>&nbsp;&nbsp;<input type="radio" name="Fanwei" id="Fanwei_1" value="1"<?php echo $limit_arr[0]==1 ? ' checked' : ''?> /><label> 特定商品</label>
                <div class="products_option"<?php echo $limit_arr[0]==0 ? ' style="display:none"' : ' style="display:block"'?>>
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
                     <select size='10' class="select_product0" style="width:240px; height:100px; display:block; float:left">
                     </select>
                     <button type="button" class="button_add">=></button>
                     <select size='10' class="select_product1" multiple style="width:240px; height:100px; display:block; float:left">
                     <?php if(!empty($products)){?>
                     	<?php foreach($products as $item){?>
                        <option value="<?php echo $item['Products_ID'];?>"><?php echo $item['Products_Name'];?>---<?php echo $item['Products_PriceX'];?></option>
                        <?php }?>
                     <?php }?>
                     </select>
                     <input type="hidden" name="BuyIDs" value="<?php echo !empty($limit_arr[1]) ? ','.$limit_arr[1].',' : '';?>" />
                   </div>
                   
                   <div class="options_buttons">
                        <button type="button" class="button_remove">移除</button>
                        <button type="button" class="button_empty">清空</button>
                   </div>
                </div>
               </span>
               <div class="clear"></div>
        </div>
        <?php }?>
        <?php if($first['Level_ID']==$LevelID){?>
        </div>
        <?php }?>
        <?php if($first['Level_ID'] != $LevelID){?>
        <h2 style="height:40px; line-height:40px; font-size:14px; font-weight:bold; background:#eee; text-indent:15px;">升级设置（低级分销商向本级分销商升级）</h2>
        <div class="rows">
          <label>升级门槛</label>
          <span class="input">
           <input name="Update_Type" value="0" id="update_0" type="radio"<?php echo $rsLevel['Level_UpdateType']==0 ? ' checked' : ''?> ><label for="update_0">补差价</label>&nbsp;&nbsp;
           <input name="Update_Type" value="1" id="update_1" type="radio"<?php echo $rsLevel['Level_UpdateType']==1 ? ' checked' : ''?>><label for="update_1">购买指定商品</label>
          </span>
          <div class="clear"></div>
        </div>
        <div id="update_div_0"<?php if($rsLevel['Level_UpdateType']==1){?> style="display:none"<?php }?>>
        	<div class="rows">
              <label>差价</label>
              <span class="input">
               <input name="UpdatePrice" value="<?php echo $rsLevel['Level_UpdateType']==0 ? $rsLevel['Level_UpdateValue'] : '';?>" type="text" class="form_input" size="5" > 元<span class="tips">&nbsp;&nbsp;注：会员升级到本级别时所需支付的金额</span>
              </span>
              <div class="clear"></div>
            </div>
            <div class="rows">
              <label>佣金发放设置</label>
              <span class="input">
                <table class="item_data_table" border="0" cellpadding="3" cellspacing="0">
                <?php 
                    $arr = array('一','二','三','四','五','六','七','八','九','十');
                    for($i=0;$i<$level;$i++){
                ?>
                    <tr>
                        <td>
                            <?php echo $arr[$i]?>级
                            &nbsp;&nbsp;
                            <input name="UpdateDistributes[<?php echo $i+1;?>]" value="<?php echo empty($UpdateDistributes[$i+1]) ? 0 : $UpdateDistributes[$i+1];?>" class="form_input" size="5" maxlength="10" type="text">
                            元
                        </td>
                    </tr>
                <?php }?>
                </table>
                <div class="tips">注：会员升级到本级别时，其上级获得的佣金</div>
              </span>
              <div class="clear"></div>
            </div>
        </div>
        <div id="update_div_1"<?php if($rsLevel['Level_UpdateType']==0){?> style="display:none"<?php }?>>
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
                     <select size='10' class="select_product0" style="width:240px; height:100px; display:block; float:left">
                     </select>
                     <button type="button" class="button_add">=></button>
                     <select size='10' class="select_product1" multiple style="width:240px; height:100px; display:block; float:left">
                     <?php if(!empty($updateproducts)){?>
                     	<?php foreach($updateproducts as $item){?>
                        <option value="<?php echo $item['Products_ID'];?>"><?php echo $item['Products_Name'];?>---<?php echo $item['Products_PriceX'];?></option>
                        <?php }?>
                     <?php }?>
                     </select>
                     <input type="hidden" name="UpdateBuyIDs" value="<?php echo !empty($rsLevel['Level_UpdateValue']) ? ','.$rsLevel['Level_UpdateValue'].',' : '';?>" />
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
        <?php }?>
        <div class="rows">
          <label></label>
          <span class="input">
          <input type="submit" class="btn_green" value="确定" name="submit_btn">
          <a href="javascript:void(0);" class="btn_gray" onClick="window.location.href='level.php?level=<?php echo $level;?>&type=<?php echo $type;?>'">返回</a></span>
          </span>
          <div class="clear"></div>
        </div>
        <input type="hidden" name="level" value="<?php echo $level;?>">
        <input type="hidden" name="type" value="<?php echo $type;?>">
        <input type="hidden" name="LevelID" value="<?php echo $LevelID;?>">
      </form>
    </div>
  </div>
</div>
</body>
</html>