<?php
$base_url = base_url();

if(empty($_SESSION["Users_Account"]))
{
	header("location:/member/login.php");
}

$builder = Dis_Record::where('Users_ID', $_SESSION["Users_ID"])
				     ->with('DisAccountRecord')
					 ->with('product');
					
$url_param = array();	
					
if(isset($_GET["search"])){
	
	if($_GET["search"]==1){
		$url_param['search'] = 1;
		
		if(isset($_GET["Status"])){
			if($_GET["Status"]<>''){
				$builder->where('status',$_GET["Status"]);
			}
			$url_param['Status'] = $_GET["Status"];
		}
	
		if(!empty($_GET["AccTime_S"])){
			$builder->where('Record_CreateTime','>=',strtotime($_GET["AccTime_S"]));
			$url_param['AccTime_S'] = $_GET["AccTime_S"];
		}
	
		if(!empty($_GET["AccTime_E"])){
		   $builder->where('Record_CreateTime','<=',strtotime($_GET["AccTime_E"]));	
		   $url_param['AccTime_E'] = $_GET["AccTime_E"];
		}
		

	
		if(!empty($_GET["Keyword"])&&strlen(trim($_GET["Keyword"]))>0){
			
			$url_param['Search_Type'] = $_GET["Search_Type"];
			$url_param['Keyword'] = $_GET["Keyword"];
			
			//搜索用户
			if($_GET['Search_Type'] == 'buyer'){
				$builder->whereHas('Buyer',function($query){
						 $query->where('User_NickName','like','%'.$_GET["Keyword"].'%');
				});
			}
			
			if($_GET['Search_Type'] == 'owner'){
				$builder->whereHas('Owner',function($query){
						 $query->where('User_NickName','like','%'.$_GET["Keyword"].'%');
				});
			}
			
			if($_GET['Search_Type'] == 'product'){
				//搜索产品
				
				$builder->whereHas('product',function($query){
						  	$query->where('Products_Name','like','%'.$_GET["Keyword"].'%');
				});
			}	
		}
	}
	
}


$dis_record_paginator = $builder->orderBy('Record_CreateTime', 'desc')
                                ->paginate('10');
$dis_record_paginator->setPath(base_url('member/distribute/record.php'));

if(!empty($url_param)){
	$dis_record_paginator->appends($url_param);
}

$page_links = $dis_record_paginator->render();
$account_record_dropdown = array();

$user_id_array = array();
$record_list = array();

foreach($dis_record_paginator->items() as $key=>$record){
	
	$user_id_array[] = $record['Buyer_ID'];
	$user_id_array[] = $record['Owner_ID'];
	
	$record = $record->toArray();
	
	$record['Product_Name'] = $record['product']['Products_Name'];
	
	$record_list[$key] = $record;
	
	if(!empty($record['dis_account_record'])){
		foreach($record['dis_account_record'] as $key=>$account_record){
				$user_id_array[] = $account_record['User_ID'];
		}
	}
}

$user_id_array = array_unique($user_id_array);

$user_nickname_dropdown = array();

if(!empty($user_id_array)){
	$user_dictionary = User::whereIn('User_ID',$user_id_array)
	                  ->get()->getDictionary();
	
	if(!empty($user_dictionary)){
		foreach($user_dictionary as $User_ID=>$User){
			$user_dropdown[$User_ID] = $User->User_NickName;
		}
	
	}
	
}


?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/bootstrap.min.css' rel='stylesheet' type='text/css' />
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript' src='/static/member/js/distribute/record.js'></script>
<link href='/static/js/plugin/operamasks/operamasks-ui.css' rel='stylesheet' type='text/css' />
<script type='text/javascript' src='/static/js/plugin/operamasks/operamasks-ui.min.js'></script> 
    
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->
<div id="iframe_page">
  <div class="iframe_content">
    <link href='/static/member/css/distribute.css' rel='stylesheet' type='text/css' />
    <div class="r_nav">
      <ul>
        <li class="cur"><a href="record.php">分销记录</a> </li>
      </ul>
    </div>
    <script language="javascript">
	$(document).ready(function(){record_obj.record_init();});
	</script>
    <div id="record" class="r_con_wrap">
      <form class="search" id="search_form" method="get" action="?" style="margin-bottom:8px;">
        搜索类型:
        <select name="Search_Type">
          <option value='owner'>店主</option>
          <option value='buyer'>购买者</option>
          <option value='product'>产品</option>      
        </select>
        
        关键词：
        <input type="text" name="Keyword" value="" class="form_input" size="20" />
        状态：
        <select name="Status">
          <option value="">--请选择--</option>
          <option value='0'>未完成</option>
          <option value='1'>已完成</option>
      
        </select>
        时间：
        <input type="text" class="input" name="AccTime_S" value="" size="30" maxlength="20" />
        -
        <input type="text" class="input" name="AccTime_E" value="" size="30" maxlength="20" />
		<input type="hidden" value="1" name="search" />
        <input type="submit" class="search_btn" value="搜索" />
 
      </form>
      
      <table width="100%" align="center" border="0" cellpadding="5" cellspacing="0" class="r_con_table">
        <thead>
          <tr>
            <td width="5%" nowrap="nowrap">序号</td>
			<td width="8%" nowrap="nowrap">订单号</td>
            <td width="8%" nowrap="nowrap">店主</td>
            <td width="10%" nowrap="nowrap">购买者</td>
            <td width="10%" nowrap="nowrap">分销产品</td>
            <td width="12%" nowrap="nowrap">所获奖金</td>
            <td width="10%" nowrap="nowrap">状态</td>
            <td width="10%" nowrap="nowrap">时间</td>
          </tr>
        </thead>
        <tbody>
			<?php foreach($record_list as $key=>$rsRecord):?>
 			<?php if(!empty($rsRecord['dis_account_record'])){?>
 				<tr UserID="<?php echo $rsRecord['Record_ID'] ?>">
 					<td nowarp="nowrap"><?=$rsRecord['Record_ID']?></td>
 					<td nowrap="nowrap"><?php echo date("Ymd",$rsRecord["Record_CreateTime"]).$rsRecord["Order_ID"] ?></td>
 					<td nowarp="nowrap" field=1><?php 
 					echo empty($user_dropdown[$rsRecord['Owner_ID']]) ? '' : $user_dropdown[$rsRecord['Owner_ID']];?></td>
 					<td nowarp="nowrap"><?php echo empty($user_dropdown[$rsRecord['Buyer_ID']]) ? '' : $user_dropdown[$rsRecord['Buyer_ID']];?></td>
 					<td nowarp="nowrap">
 				
 					<?=!empty($rsRecord['Product_Name'])?$rsRecord['Product_Name']:'产品已删'; ?>
 
 					</td>
 					<td nowarp="nowrap">
 						<?php
 							
 
 							$level_name = array(1=>'一级',2=>'二级',3=>'三级',
 												4=>'四级',5=>'五级',6=>'六级',
 												7=>'七级',8=>'八级',9=>'九级',
 												10=>'十级');
 							
 							foreach($rsRecord['dis_account_record'] as $key=>$account_record){
 								if($rsRecord['Buyer_ID']==$account_record['User_ID']){
 									echo '<font style="color:blue">自销</font>&nbsp;&nbsp;';
 								}else{
 									echo $level_name[$account_record['level']].'&nbsp;&nbsp;';
 								}
 								
 								echo !empty($user_dropdown[$account_record['User_ID']])?$user_dropdown[$account_record['User_ID']]:'无昵称'.'&nbsp;&nbsp;';
 								
 								echo '<span class="red">&yen;'.round_pad_zero($account_record['Record_Money'],2).($account_record['Record_Money']<0 ? '用户退款减少佣金' : '').'</span><br/>';
 								
 								echo ($account_record['Nobi_Money']>0)?$account_record['Nobi_Level']:$account_record['Nobi_Description'].'<br>';
 								
 								echo (($account_record['Nobi_Money']>0)?'<span class="red">&nbsp;&nbsp;&yen;'.$account_record['Nobi_Money'].'</span><br/>':'');
 
 							}
 						?>
 					</td>
 					<td nowrap="nowrap"> 
 					<?php if($rsRecord['status'] == 0):?>
 						未完成
 					<?php else:?>
	
 						已完成
 					<?php endif;?>
 					</td>
 					<td nowrap="nowrap"><?php echo date("Y-m-d H:i:s",$rsRecord['Record_CreateTime']) ?></td>            
 				</tr>
 			<?php } ?>
         <?php endforeach; ?>
        </tbody>
      </table>
      <div class="page"><?=$page_links?></div>
    </div>
  </div>  
</div>
</div>
</body>
</html>