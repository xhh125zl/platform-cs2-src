<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
$base_url = base_url();

//未登录则跳转到登录页
if(empty($_SESSION["Distribute_ID"]))
{
	header("location:/dis/login.php");
}

$Users_ID  =  $_SESSION["Dis_Users_ID"]; 
$User_ID = $_SESSION["Distribute_ID"];

$rsConfig = shop_config($Users_ID);
//分销相关设置
$dis_config = dis_config($Users_ID);
//合并参数
$rsConfig = array_merge($rsConfig,$dis_config);
$level_config = $rsConfig['Dis_Level'];
$dis_title_level = Dis_Config::get_dis_pro_title($Users_ID );

if($User_ID == 0){
	echo '缺少分销账户ID';
	exit();
}

$rsAccount = Dis_Account::Multiwhere(array('Users_ID'=>$Users_ID,'User_ID'=>$User_ID)) ->first();
$posterity = $rsAccount->getPosterity($level_config);					  
$posterity_count = $posterity->count();

$userIds = $posterity->map(function($dsAccount){
				return $dsAccount->User_ID;
	        })->all(); 

$userDictionary = User::where('Users_ID',$Users_ID)
      				 ->whereIn('User_ID',$userIds)
					 ->get(array('User_ID','User_NickName'))
	   				 ->getDictionary();
					 
//生成drop_down数组
$user_dropdown = array();
foreach($userDictionary as $key=>$user){
 	$user_dropdown[$user->User_ID] = $user->User_NickName;	
}

$level = intval($request->input('level',1));
//var_dump($posterity->toArray());
$account_list = $posterity->where('level',$level);

//初始化分页类
$records_per_page = 15;
$total_num = $account_list->count();
$pagination = new Zebra_Pagination();
$pagination->records($total_num);
$pagination->records_per_page($records_per_page);

$account_list =  !empty($account_list)?$account_list->toArray():array();

$account_list = array_slice(
            $account_list,                                             //  from the original array we extract
            (($pagination->get_page() - 1) * $records_per_page),    //  starting with these records
            $records_per_page                                       //  this many records
);

$total_pages = $pagination->_properties['total_pages'];
$cur_page = $pagination->_properties['page'];  
$href_template = '?User_ID='.$User_ID.'&level='.$level.'&page={{number}}';

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title></title>
<link href='/static/css/bootstrap.css' rel='stylesheet' type='text/css' />
<link href='/static/style.css' rel='stylesheet' type='text/css' />
<link href='/static/css/global.css' rel='stylesheet' type='text/css' />
<link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />



<script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='/static/js/jquery.twbsPagination.min.js'></script>
<script type='text/javascript' src='/static/member/js/global.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
		$('#pagination').twbsPagination({
       totalPages:<?=$total_pages?>,
       visiblePages: 7,
        href: '<?=$href_template?>',
       onPageClick: function (event, page) {
           $('#page-content').text('Page ' + page);
       }
   });
});

	
</script>

<!--[if lte IE 9]><script type='text/javascript' src='/static/js/plugin/jquery/jquery.watermark-1.3.js'></script>
<![endif]-->

<div id="iframe_page">
  <div class="iframe_content">
	
   <!-- nav begin -->
   <?php $cur_nav = 'posterity'?>
   <?php require_once('nav.php')?>
   <!-- nav end-->
    
   
    <div class="r_con_wrap">
	 	<h3>下属分销商列表</h3>
     
    <!-- level filter begin -->
    	<?php
			$levelnames = array(1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七',
			          8=>'八',9=>'九');
		?>
        <div class="btn-group" id="level_filter">
           <?php for($i=1;$i<=$level_config;$i++):?>  
  			<a  class="btn btn-default <?=($level == $i)?'cur':''?>" href="?User_ID=<?=$User_ID?>&level=<?=$i?>"><?=$levelnames[$i]?>级分销商</a>
  		   <?php endfor;?>   
		</div>
        <p>共<?=$total_pages?>页,<?=$total_num?>个,当前第<?=$cur_page?>页</p>
    <!-- level filter end -->
   
    	<div id="level_filter_panel">
        	<table class="mytable" border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr bgcolor="#f5f5f5">
            <td width="50" align="center">#序号</td>
            <td width="50" align="center"><strong>微信昵称</strong></td>
            <td width="100" align="center"><strong>店名</strong></td>
            <td width="80" align="center"><strong>佣金余额</strong></td>
            <td width="100" align="center"><strong>审核状态</strong></td>
            <td width="100" align="center"><strong>总收入</strong></td>
            <td width="100" align="center"><strong>加入时间</strong></td>
            
          </tr>
          		  <?php $i=1; ?>
                  <?php foreach($account_list as $key=>$item):?>
                  	<tr onmouseover="this.bgColor='#D8EDF4';" onmouseout="this.bgColor='';" bgcolor="">
            			<td align="center" userid=<?=$item['User_ID']?>><?=$i?></td>
            			<td align="center"><?=!empty($user_dropdown[$item['User_ID']])?$user_dropdown[$item['User_ID']]:'<span class="red">信息缺失</span>'?></td>
             			<td align="center"><?=$item['Shop_Name']?></td>
            			<td align="center">&yen;<?=$item['balance']?></td>
            			<td align="center"><?=$item['Is_Audit']?'已通过':'未通过'?></td>
            			<td align="center">&yen;<?=$item['Total_Income']?></td>
            			<td align="center"><?=ldate($item['Account_CreateTime'])?></td> 
                    	
          			</tr>
                    <?php $i++; ?>
                <?php endforeach ;?>   	
                  
                    
                  </tbody></table>

        </div>
 		
        <div id="pagination_container">
        	<ul id="pagination" class="pagination-sm"></ul>
		</div>
        
    </div>
    
  </div>
</div>
</body>
</html>
