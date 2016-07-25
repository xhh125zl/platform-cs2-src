
<?php
 require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
  if (empty($_POST['name'])){
      echo'';
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>搜索</title>
</head>
<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
<script src="/static/api/pintuan/js/jquery.min.js"></script>
<body>
  <?php
      $DB->query("SELECT * FROM `pintuan_category` where Users_ID='".$UsersID."' and parent_id=0");
      $r=$DB->fetch_assoc();
  ?>
          <div>
                <form action="/api/<?php echo $UsersID;?>/pintuan/sousuo/<?php echo $r['cate_id'];?>/" method="post">
                      <span class="box l"><input type="text" class="text" name="name" value="" style="width:99%;"/></span>
                      <span class="btnSubmit l"><a id="seach">搜索</a></span>
                      <input name="sousuo" type="submit" value="搜索"  style="display:none;" class="btnSubmit"/>
                </form>
          </div>
        <div class="clear"></div>
<!--代码开始 -->
        <script type="text/javascript">

        $('#seach').click(function() {
          $('input[name=sousuo]').click();
        });
        </script>  


    <div class="tj">
          <ul>
          <?php
          $post=isset($_POST['name'])?$_POST['name']:'';
              $DB->query('select * from pintuan_products where Users_ID="'.$UsersID.'" and Products_Name like "%'.$post.'%"');
              $sosuo=$DB->toArray();
              if(empty($sosuo)) {
                  echo'对不起  没有您找的商品'; 
                  exit();
               }  
              foreach ($sosuo as $key => $val){
                    echo'<li class="li">
                     <br>
                    <a href="/api/'.$UsersID.'/pintuan/xiangqing/'.$val['Products_ID'].'/"><img style="width:90px;height:90px;" src="'.json_decode($val['Products_JSON'],true)['ImgPath']['0'].'">'.sub_str($val['Products_Name'],10,false).'<br/>
                    <span class="tjt l">¥'.$val['Products_PriceT'].'</span></a></li>';
                }
          ?>
          </ul>
    </div>
    <div class="kb"></div>
    <div class="clear"></div>

      <div style="height:70px;"></div>
      <div class="cotrs">
        <a  href="<?php echo "/api/$UsersID/pintuan/"; ?>"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br/>首页</a>
        <a  href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>" class="thisclass"><img src="/static/api/pintuan/images/002-2.png" width="22px" height="22px" style=" margin-top:3px;"/><br/>搜索</a>
        <a  href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/002-3.png" width="22px" height="22px" style=" margin-top:3px;"/><br/>我的</a>
      </div>

</div>
</body>
<script type="text/javascript">

  $('#seach').click(function() {
    $('#form1').submit();
  });

 </script>
</script>
</html>
