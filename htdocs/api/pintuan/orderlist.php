<?PHP
  require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');

  setcookie('url_referer', $_SERVER["REQUEST_URI"], time()+3600, '/', $_SERVER['HTTP_HOST']);
  $order_status = empty($_GET['Order_Status']) ? 0 : intval($_GET['Order_Status']);
  $sql = "select * from `user_order` where Users_ID='$UsersID' and (Order_Type='pintuan' or Order_Type='dangou') and User_ID='$UserID' and order_status > 0";
  if ($order_status != 0) {
    $sql .= " and order_status=$order_status";
  }
  $sql .= "  order by order_createtime desc";
 
  $result = $DB->query($sql);
  $orders = array();
  while ($res = $DB->fetch_assoc($result)) {
    $orders[] = $res;
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>我的订单</title>
  <link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
  <link href="/static/api/pintuan/css/biaoqian.css" rel="stylesheet" type="text/css">
  <link href="/static/api/pintuan/css/zhezhao.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
  <script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script>
  <script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
</head>
<body>
<div class="dingdan">
  <span class="fanhui l"><a href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
  <span class="querendd l">全部订单</span>
  <div class="clear"></div>
</div>
<div id="con">
  <ul id="tags">
    <li style="border-width: 0px;" <?php echo $order_status == 0 ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/orderlist/0/"; ?>">全部</a></li>
    <li style="border-width: 0px;" <?php echo $order_status == 1 ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/orderlist/1/"; ?>">待付款</a></li>
    <li style="border-width: 0px;" <?php echo $order_status == 3 ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/orderlist/3/"; ?>">已发货</a></li>
    <li style="border-width: 0px;" <?php echo $order_status == 4 ? 'class=selectTag' : '' ?>><a href="<?php echo "/api/$UsersID/pintuan/orderlist/4/"; ?>">已完成</a></li>
  </ul>
  <div id="tagContent">
    <div id="tagContent0" class="tagContent selectTag">
        <?php if (!empty($orders)) {
          foreach ($orders as $item) {
            $pintuan_sp = json_decode(htmlspecialchars_decode($item["Order_CartList"]), true);
            $cc = $DB->query("select * from `pintuan_products` where Products_ID={$pintuan_sp['Products_ID']} and Users_ID='$UsersID';");
            $res = $DB->fetch_assoc($cc);
            
            $images = json_decode(htmlspecialchars_decode($res["Products_JSON"]), true)['ImgPath']['0'];
        ?>

        <div class="chanpin" id="<?php echo $item['Order_ID']; ?>">
          <input type="hidden" name="orderid" value="<?php echo $item['Order_ID']; ?>">
          <input type="hidden" name="usersid" value="<?php echo $UsersID; ?>">
            <div class="chanpin1">
                <a href="/api/<?php echo $UsersID ?>/pintuan/orderdetails/<?php echo $item['Order_ID']; ?>/">
                <span class="l"><img style="width:100px;height: 100px;" src="<?php echo empty($images) ? '' : $images; ?>"></span>
                <span class="cp l" style="width:50%;">
                  <ul>
                    <li><strong><?php echo $pintuan_sp['ProductsName']; ?></strong></li>
                    <!--<li><?php echo $res['Products_BriefDescription']; ?></li>-->
                  </ul>
                </span>
                <span class="jiage r">¥<?php echo number_format($item["Order_TotalPrice"]-$pintuan_sp['Shipping_fee'],2); ?>/件</span>
                </a>
                <div class="clear"></div>
            </div>
            <div class="t r">
              <span class="l">订单：<?php echo $item['Order_Code']  ?></span>
              <span>&nbsp;合计：<?php echo $item["Order_TotalPrice"]; ?>(含物流费：¥<?php echo $pintuan_sp['IsShippingFree'] == '1' ? 0 : floatval($pintuan_sp['Shipping_fee']); ?>)</span>
              <div class="clear"></div>
            </div>
            <div class="futime2">
              <?php switch ($item['Order_Status']) {
                case 0:
                  echo '<span class="fuk r"><a>待确认</a></span>';
                  break;
                case 1:
                  echo "<span class='fuk1 r'><a href='/api/{$UsersID}/pintuan/cart/payment/{$item['Order_ID']}/'>去付款</a></span><span class='fuk r'><a onclick='return cancelorder(\"{$item['Order_ID']}\",\"{$pintuan_sp['Products_ID']}\");'>取消订单</a></span>";
                  break;
                case 2:
                  echo '<span class="fuk r"><a>已付款</a></span>';
                  break;
                case 3:
                  echo '<span class="fuk r"><a>已发货</a></span>';
                  echo '<span class="fuk1 r"><a class="button" onclick="doconfirm('.$item['Order_ID'].')">确定收货</a></span>';
                  break;
                case 4:
                  $sql1 = "select count(*) as cnt from pintuan_commit where order_id = {$item['Order_ID']}";
                  $result1 = $DB->query($sql1);
                  $comment1 = $DB->fetch_assoc($result1);
                  if ($comment1['cnt'] > 0) {
                    echo "<span class='fuk r'><a>已评价</a></span>";
                  } else {
                    echo "<span class='fuk1 r'><a id='comment{$item['Order_ID']}' onclick='showdiv(\"{$item['Order_ID']}\", \"{$pintuan_sp['Products_ID']}\");'>去评价</a></span>";  
                  }
                  break;
                case 5:
                  echo '<span class="fuk r"><a>退款中</a></span>';
                  break;
                case 6:
                  echo '<span class="fuk r"><a>已退款</a></span>';
                  break;
                case 7:
                  echo '<span class="fuk r"><a>已手动退款</a></span>';
                  break;
              } ?>
            </div>
        </div>
    <?php } } ?>
    </div>
  </div>
</div>

<div id="bg1"></div>
<div id="show">
  <div class="zzbj">
    <span class="zzbj1 l"><img src="<?php echo isset($pintuan_sp['ImgPath'])?$pintuan_sp['ImgPath']:'';?>"></span>
    <span class="sr l">
      <input type="text" class="sr1" placeholder="请输入您对商品的评价"/>
    </span>
    <span class="guanb r"><input id="btnclose" type="button" onclick="hidediv();" class="guanb1"/></span>
    <div class="clear"></div>
    <div class="pingfen1">商品评分：</div>
    <div class="clear"></div>
    <div class="pingfen">
      <label class="pingf1" for="fruit1"><input name="Fruit" type="radio" value="1分" class="pingf" id="fruit1"/>1分</label>
      <label class="pingf1" for="fruit2"><input name="Fruit" type="radio" value="2分" class="pingf" id="fruit2"/>2分</label>
      <label class="pingf1" for="fruit3"><input name="Fruit" type="radio" value="3分" class="pingf" id="fruit3"/>3分</label>
      <label class="pingf1" for="fruit4"><input name="Fruit" type="radio" value="4分" class="pingf" id="fruit4"/>4分</label>
      <label class="pingf1" for="fruit5"><input name="Fruit" type="radio" value="5分" class="pingf" id="fruit5"/>5分</label>
    </div>
    <div class="clear"></div>
    <div><input type="button" value="评价" class="queding"/></div>
  </div>
</div>
<?php include 'bottom.php';?>

<script type="text/javascript">
  function showdiv(orderid, productid) {
    $('#bg1, #show').show();
    $('.queding').click(function() {
      addordercomment(orderid, productid);
    });
  }

  function hidediv() {
    $('#bg1, #show').hide();
    $('.queding').unbind('click');
  }

  //取消订单
  function cancelorder(orderid,goodsid) {
    if (!confirm('确定取消订单?')) {
      return false;
    }

    var info = {
      'action': 'qxdd',
      'orderid': orderid,
      'productID':goodsid,
      'UsersID':"<?php echo $UsersID;?>"
    };

    $.ajax({
      url: "/api/<?php echo $UsersID; ?>/pintuan/ajax/",
      data: info,
      type: "POST",
      dataType: "json",
      success: function (data) {
          if (data.code == "1001") {
            $('#' +  data.orderid).remove();
            return;
          }
          
          if (data.code == "1002"){
            layer.msg("操作失败！", {icon:1, time:2000});
            return ;
          }
      }
    });

    return false;
  }

  //添加订单评论
  function addordercomment(orderid, productid) {
    var comment = {
      'action': 'ptpinlun',
      'Users_ID': '<?php echo $UsersID; ?>',
      'Order_ID': orderid,
      'txt': $('.sr1').val(),
      'fenshu': $('input:radio[name="Fruit"]:checked').val(),
      'products_id': productid,
      'userid': <?php echo $UserID; ?>
    };

    if (comment.fenshu == null || comment.txt == null) {
      layer.msg("请输入您对商品的评价和评分", {icon:1, time:2000});
    }

    $.ajax({
      url: "/api/<?php echo $UsersID; ?>/pintuan/ajax/",
      data: comment,
      type: "POST",
      dataType: "json",
      success: function (data) {
          if (data.code == "1001") {
            layer.msg("评论成功！", {icon:1, time:2000});
            hidediv();
            $('#comment' + data.orderid).removeAttr('onclick').text('已评价').parent().removeClass('fuk1').addClass('fuk');
            return;
          }
          
          if (data.code == "1002"){
            layer.msg("评论失败！", {icon:1, time:2000});
            return ;
          }
      }
    });
  }
</script>
<script type="text/javascript">
  //确定收货按钮 
  function doconfirm(orderid){
      // 获取当前的orderid
      var usersid="<?=$UsersID ?>";
      var orther="confirm";
      $.post('/api/<?php echo $UsersID;?>/pintuan/ajax/',{orderid:orderid,usersid:usersid,orther:orther},function(data){
           if(data.status==1){
				alert(data.msg);
				//location.reload(); 
	            location.href = '<?php echo "/api/$UsersID/pintuan/orderlist/4/"; ?>';
           }else{
				alert(data.msg);
           }
            
      },"json");
  }

</script>
</body>
</html>