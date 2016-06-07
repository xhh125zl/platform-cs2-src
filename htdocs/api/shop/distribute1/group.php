<?php
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/General_tree.php');

//获取所有分销账号列表
$rsAccount = $DB->get('shop_distribute_account','User_ID,invite_id,level,Real_Name',"where Users_ID='".$UsersID."'");
$ds_list = $DB->toArray($rsAccount);
//实例化通用树类
$param = array('result'=>$ds_list,'fields'=>array('User_ID','invite_id'));
$generalTree = new General_tree($param);
//获取此分销商所有下属ID,包括自己的ID
$account_list = $generalTree->leafid($_SESSION[$UsersID.'User_ID']);

foreach($ds_list  as $kye=>$item){
	$ds_dropdown[$item['User_ID']] = $item; 
}

$level_list = array(1=>array(),2=>array(),3=>array());
foreach($account_list as $key=>$item){
	$level_list[$ds_dropdown[$item]['level']][$item] =$ds_dropdown[$item] ;
}

$level_name_list = array(1=>'一级分销商',2=>'二级分销商',3=>'三级分销商');

$header_title = '我的团队';
require_once('header.php');
?> 
<body>
<link href="/static/api/distribute/css/group.css" rel="stylesheet">
<div class="wrap">
	<div class="container">
    <h4 class="row page-title">我的团队</h4>
    </div>
    
  
    <ul id="distribute_group">
   		<li class="item cur"><a href="/api/<?=$UsersID?>/shop/distribute/group/?wxref=mp.weixin.qq.com">我的团队</a></li>
   		<li class="item "><a href="/api/<?=$UsersID?>/shop/distribute/my_distribute/?wxref=mp.weixin.qq.com">我的推广</a></li>
   		<li class="item"><a href="/api/<?=$UsersID?>/shop/distribute/income/?wxref=mp.weixin.qq.com">分销佣金</a></li>
  		<li class="clearfix"></li>
  	</ul>

  
  	<div class="list_item">
	<div class="dline"></div>
    <?php foreach($level_list as $key=>$account_list):?>
    <a href="javascript:void(0)" class="item item_0"><?=$level_name_list[$key]?><span class="jt"></span></a>
		<ul class="distribute_list">
        	<?php if(count($account_list) >0 ):?>
            	<?php foreach($account_list as $k=>$v):?>
                <li><?php if(strlen($v['Real_Name']>0)){
						echo $v['Real_Name'];
					}else{
						echo '暂无';
					}?>
                 &nbsp;&nbsp;&nbsp;&nbsp;<?=$shop_url.$v['User_ID']?>/</li>
           		<?php endforeach;?>
			<?php endif;?>
        </ul>
   <?php endforeach;?>
   
    </div>
  
</div>


</body>
</html>
