<?php 
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

$ID =  isset($_GET['id']) && $_GET['id']?$_GET['id']:0;
$sql = "SELECT a.Type_ID,a.*,b.*,t.module,t.Type_Name FROM biz_active as b LEFT JOIN active as a ON b.Active_ID=a.Active_ID LEFT JOIN active_type AS t ON a.Type_ID=t.Type_ID  WHERE b.Users_ID='{$UsersID}' AND b.Biz_ID='{$BizID}' AND b.ID='{$ID}'";
$result = $DB->query($sql);
$rsActive = $DB->fetch_assoc($result);
if(!$rsActive){
    sendAlert("已申请的活动不存在");
}
$list = [];
if($rsActive['ListConfig']){
    if($rsActive['module']=='pintuan'){    //拼团
        $table = "pintuan_products";    
    }elseif($rsActive['Type_ID']=='cloud'){   //云购
        $table = "cloud_products";
    }elseif($rsActive['Type_ID']=='pifa'){   //批发
        $table = "pifa_products";
    }else{
        $table = "shop_products";
    }
    $result = $DB->Get($table,"*","WHERE Users_ID='{$UsersID}' AND Biz_ID='{$BizID}' AND Products_ID in ({$rsActive['ListConfig']})");
    if($result){
        while($res = $DB->fetch_assoc($result))
        {
            $list[$res['Products_ID']]=$res;
        }
    }
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <title>查看产品列表</title>
        <link href='/static/css/global.css' rel='stylesheet' type='text/css' />
        <link href='/static/member/css/main.css' rel='stylesheet' type='text/css' />
        <script type='text/javascript' src='/static/js/jquery-1.7.2.min.js'></script>
        <script type='text/javascript' src='/static/js/plugin/layer/layer.js'></script>
    </head>
	<body>
        <div id="iframe_page">
			<div class="iframe_content">
            	<div id="products" class="r_con_wrap ">
            	<div class="r_con_form skipForm">
              	   <div class="rows">
                      <label>推荐</label>
                      <span class="input">
                      	<ul>
                      	<?php 
                      	if(!empty($list)){ 
                      	     foreach ($list as $k=>$v){
                      	?>
                      	<li><?=$v['Products_Name'] ?> </li>
                      	<?php }
                      	}
                      	?>
                      	</ul>
                      	
                      </span>
                      <div class="clear"></div>
                    </div>
                    <div class="rows">
                      <label>推荐到首页</label>
                      <span class="input">
                      	<ul id="IndexCommit">
                      	<?php 
                      	if(!empty($rsActive['IndexConfig']) && $rsActive['IndexConfig']){
                      	    $indexList = explode(',', $rsActive['IndexConfig']);
                      	    if(!empty($indexList)){
                      	        foreach ($indexList as $v){
                      	?>
                      	<li><?=isset($list[$v]['Products_Name'])?$list[$v]['Products_Name']:'' ?></li>
                      	<?php 
                                }
                      	    }
                      	}
                      	?>
                      	</ul>
                      </span>
                      <div class="clear"></div>
                    </div>
                    </div>
            	</div>
          	</div>
        </div>
	</body>
</html>