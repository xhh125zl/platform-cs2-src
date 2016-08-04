<?php
 require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
 $headTitle = "内容搜索";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?=$headTitle ?></title>
</head>
<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
<script src="/static/api/pintuan/js/jquery.min.js"></script>
<body>
    <div class="w">
      <?php include_once("top.php"); ?>
    <div>
    <div>
                <form action="/api/<?=$UsersID ?>/pintuan/sousuo/" method="post">
                      <span class="box l"><input type="text" class="text" name="name" value="" style="width:99%;"/></span>
                      <span class="btnSubmit l"><input name="sousuo" type="submit" value="搜索"   class="btnSubmit" style="line-height: 28px;"/></span>
                </form>
     </div>
     <div class="clear"></div> 
    <div class="tj">
          <ul>
          <?php
              $name=isset($_POST['name'])?$_POST['name']:'';
              $cateid = isset($_POST['cateid'])?$_POST['cateid']:'';
              $res = $DB->query("select Products_Name,Products_JSON,Products_ID,Products_PriceT from pintuan_products where Users_ID='{$UsersID}' ".($cateid ?"and Products_Category like '%,{$cateid},%'":"")." AND Products_Name like '%{$name}%' AND Products_Status=1");
              $goods=$DB->toArray($res); 
              if(!empty($goods)){
                foreach ($goods as $key => $val){
          ?>
              <li class="li">
                    <a href="/api/<?=$UsersID ?>/pintuan/xiangqing/<?=$val['Products_ID'] ?>/"><img style="width:90px;height:90px;" src="<?=json_decode($val['Products_JSON'],true)['ImgPath']['0'] ?>"><br/><?=sub_str($val['Products_Name'],10,false)?></a>
                    <br/>
                    <span class="tjt l">¥<?=$val['Products_PriceT'] ?></span>
              </li>
          <?php
                }
              }else{
          ?>
              <li>没有搜索到数据</li>
          <?php
              }
          ?>
          </ul>
    </div>
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
</script>
</html>
