<?php
require_once "config.inc.php";

$id = isset($_GET['id']) && $_GET['id']:0;
if($id){
    $result = $DB->Get("shop_articles_category", "Category_ID,Users_ID,Category_ParentID,Category_Name,Category_Index", "WHERE Users_ID='{$UsersID}' AND Category_ParentID = ".$id);
    $rsArticleList = $DB->toArray($result);
}else{
    $result = $DB->Get("shop_articles_category", "Category_ID,Users_ID,Category_ParentID,Category_Name,Category_Index", "WHERE Users_ID='{$UsersID}'");
    $rsArticleList = $DB->toArray($result);
    $rslist = [];
    if(!empty($rsArticleList)){
        foreach($rsArticleList as $k => $v){
            if($v['Category_ParentID']==0){ 
                $rslist[$k]['base'] = $v;
            }
        }
        foreach($rslist as $k => $v){
            foreach($rsArticleList as $key => $val){
                if($v['base']['Category_ID']==$val['Category_ParentID']){ 
                    $rslist[$k]['child'][$key] = $val;
                }
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
<title>学习中心 <?=isset($rsArticleList)?'列表':'' ?></title>
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
        <?php if(isset($rsArticleList) && !empty($rsArticleList)){ ?>
        <ul>
        <?php $count = 1; ?>
        <?php foreach($rsArticleList as $k => $v){ ?>
        	<li><a href='?act=learn&id=<?=$v['Category_ID'] ?>'><span class="left learn_number"><?=$count<10?"0".$count:$count?> ></span><span class="left learn_title"><?=$v['Category_Name'] ?></span></a><div class="clear"></div></li>
            <?php $count++; ?>
        <?php } ?>
        </ul>
        <?php } ?>
        
        <?php if(!empty($rslist)){ ?>
        <?php foreach($rslist as $k => $v){ ?>
    	<p><a href='?act=learn&id=<?=$v['Category_ID'] ?>'><?=$v['base']['Category_Name'] ?></a></p>
        <?php if(!empty($v['child'])){ ?>
        <?php $count = 1; ?>
    	<ul>
            <?php foreach($v['child'] as $key => $val){ ?>
        	<li><a href='?act=learn_list&id=<?=$val['Category_ID'] ?>'><span class="left learn_number"><?=$count<10?"0".$count:$count?> ></span><span class="left learn_title"><?=$val['Category_Name'] ?></span></a><div class="clear"></div></li>
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
