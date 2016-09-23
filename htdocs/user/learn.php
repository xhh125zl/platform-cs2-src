<?php
require_once "config.inc.php";
$result = $DB->Get("web_column", "Column_ID,Users_ID,Column_Name,Column_LinkUrl,Column_PageType,Column_ParentID", "WHERE Users_ID='{$UsersID}'");
$rsArticleList = $DB->toArray($result);
$rslist = [];
if(!empty($rsArticleList)){
    foreach($rsArticleList as $k => $v){
        if($v['Column_ParentID']==0){ 
            $rslist[$k]['base'] = $v;
        }
    }
    foreach($rslist as $k => $v){
        foreach($rsArticleList as $key => $val){
            if($v['base']['Column_ID']==$val['Column_ParentID']){ 
                $rslist[$k]['child'][$key] = $val;
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">  
<meta name="app-mobile-web-app-capable" content="yes">
<title>学习中心</title>
</head>
<link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
<link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../static/js/jquery-1.7.2.min.js"></script>
<body>
<div class="w">
	<div class="back_x">
    	<a class="l" href="javascript:history.go(-1);"><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>学习中心
    </div>    
    <div class="learn_ll">
        <?php if(!empty($rslist)){ ?>
        <?php foreach($rslist as $k => $v){ ?>
    	<p><?=$v['base']['Column_Name'] ?></p>
        <?php if(!empty($v['child'])){ ?>
        <?php $count = 1; ?>
    	<ul>
            <?php foreach($v['child'] as $key => $val){ ?>
        	<li><a href='?act=learn_list&id=<?=$val['Column_ID'] ?>'><span class="left learn_number"><?=$count<10?"0".$count:$count?> ></span><span class="left learn_title"><?=$val['Column_Name'] ?></span></a><div class="clear"></div></li>
            <?php $count++; ?>
            <?php } ?>
        </ul>
        <?php } ?>
        <?php } ?>
        <?php } ?>
    </div>
</div>
</body>
</html>
