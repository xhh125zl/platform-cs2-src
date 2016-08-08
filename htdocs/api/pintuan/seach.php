<?php
	require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
	
	$id = isset($_GET["id"])?$_GET["id"]:0;
	$headTitle = "内容搜索";
	
	/**获取产品分类列表**/
	$res = $DB->query("SELECT * FROM `pintuan_category` where Users_ID='{$UsersID}' and parent_id=0");
	$catelist = $DB->toArray($res);
	if(!empty($catelist)){
     $firstRecord=$DB->fetch_assoc($res);
	}
	//对搜索页面的处理
	 if(IS_POST && !empty($_POST['name'])){
      $name=$_POST['name'];
	    $res = $DB->get("pintuan_products","*","WHERE Users_ID='{$UsersID}' AND Products_Name LIKE '%{$name}%' AND Products_Status=1");
	    $searchlist=$DB->toArray($res);
 	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?=$headTitle ?></title>
</head>
<link href="/static/api/pintuan/css/css.css" type="text/css" rel="stylesheet">
<link href="/static/api/pintuan/css/tab.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
<body>
<div class="w">
  <?php include_once("top.php"); ?>
<div>
	<form action="/api/<?=$UsersID ?>/pintuan/sousuo/<?=isset($firstRecord['cate_id'])?$firstRecord['cate_id']:$id ?>/" method="post">
      <input type = "hidden" name = "cateid" value = "<?=$id ?>"/>
			<span class="box l"><input type="text" class="text" name="name" value="" style="width:99%;"/></span>
			<span class="btnSubmit l"><input name="sousuo" type="submit" value="搜索"   class="btnSubmit" style="line-height: 28px;"/></span>
			
	</form>
</div>
<div class="clear"></div>
<!--代码开始 -->
<script type="text/javascript">
$(function(){
    $("#container_0").show();
    $("#menu ul li").hover(function(){
        var container = $(this).attr("container");
        $("#content >div").hide();
        $("#"+container).show();
    },function(){
        $("#"+container).hide();
    });
    $("#menu ul").mouseout(function(){
        $("#container_0").show();
    });
});

</script>
<style>
.hide{ display:none; }
</style>
<div id="wrap">
          <div id="menu">
                <ul>
         <?php
          if(!empty($catelist)){
              foreach($catelist as $key => $value) {
         ?>
                      <li class="cate" container="container_<?=$key?>"><a class="href" href="/api/<?=$UsersID?>/pintuan/seach/<?=$value['cate_id']?>/" ><?=$value['cate_name']?></a>
                      <input type="hidden" class="cate" value="<?=$value['cate_id']?>">
                      </li>
         <?php
              }
					}
          ?>	
                </ul>
          </div>
          <!-- 搜索列表栏目内容开始 -->
          <div id="content">
          <?php
          if(!empty($catelist)){
              foreach($catelist as $key => $value) {
                  if($id && $key==0){
                      $cateid = $id;
                  }else{
                      $cateid = $value['cate_id'];
                      
                  }
                  $_res = $DB->Get("pintuan_products","Products_JSON,Products_Name,Products_ID","WHERE Products_Category like '%,{$cateid},%' and Users_ID='{$UsersID}' AND Products_Status=1");
                  $goods = $DB->toArray($_res);
                  unset($_res);
         ?>
              <div id="container_<?=$key ?>" class="hide">
              
                  <ul>
         <?php 
                if(!empty($goods)){ 
                    foreach($goods as $k => $v){
         ?>
                     <li class="l">
                          <a href="/api/<?=$UsersID ?>/pintuan/xiangqing/<?=$v['Products_ID'] ?>/"><img style="height:80px;" src="<?=json_decode($v['Products_JSON'],true)['ImgPath']['0'] ?>"><br/><?=sub_str($v['Products_Name'],10,false)?></a></li>	
         
         <?php 
                    }
                }
         ?>
                  </ul>
              </div>
          <?php
              
              }
					}
          ?>
          </div>
    <div class="clear"></div>
    <!--代码结束 -->
    <div class="kb"></div>
    <div class="clear"></div>
    <div style="height:70px;"></div>
    <div class="cotrs">
        <a  href="/api/<?=$UsersID ?>/pintuan/"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br />首页</a>
        <a  class="thisclass" href="/api/<?=$UsersID ?>/pintuan/seach/"><img src="/static/api/pintuan/images/002-2.png" width="22px" height="22px" style="margin-top:3px;"/><br />搜索</a>
        <a href="/api/<?=$UsersID ?>/pintuan/user/"><img src="/static/api/pintuan/images/002-3.png" width="22px" height="22px" style="margin-top:3px;"/><br />我的</a>
    </div>
</div>
</body>
</html>
