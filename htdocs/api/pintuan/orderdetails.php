<?PHP
require_once($_SERVER["DOCUMENT_ROOT"].'/include/update/common.php');
isset($_GET["orderid"]) || die("缺少必要的参数");

$orderid=$_GET["orderid"];
$sql = "SELECT * FROM user_order o LEFT JOIN pintuan_order as p ON o.Order_ID=p.Order_ID WHERE o.Order_ID='{$orderid}' and o.Order_Status>=1";
$res=$DB->query($sql);
$orederInfo = $DB->fetch_assoc($res);
$orederInfo || die("订单不存在");
$goods = json_decode(htmlspecialchars_decode($orederInfo['Order_CartList']),true);
$orderStatus = array(
            '0'=>'待确认',
            '1'=>'未付款',
            '2'=>'已付款',
            '3'=>'已发货',
            '4'=>'完成',
            '5'=>'退款中',
            '6'=>'已退款',
            '7'=>'手动退款成功'
        );
$goodsInfo = $DB->GetRs("pintuan_products","*","where Products_ID={$goods['Products_ID']} and Users_ID='$UsersID' ");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>订单详情</title>
        <link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
        <script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script>
        <script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
        
  </head>
  <body>
  <div class="w">
      <div class="dingdan">
          <span class="fanhui l"><a href="javascript:history.go(-1);"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
          <span class="querendd l">订单详情</span>
          <div class="clear"></div>
      </div>
      <div class="ddxq">
          <span>订单号：<?php echo $orederInfo['Order_Code']; ?></span>
          
          <span class="ddxqr r"><?php echo $orderStatus[$orederInfo['Order_Status']]; ?></span>
      </div>
      <div class="clear"></div>
      <div class="adr">
          <div class="adr1 l"></div>
          <div class="cp l">
              <ul>
              	  <?php if($orederInfo['is_vgoods'] == 1){?>
                  <li>
                      <div>购买手机：<?=$orederInfo['Address_Mobile']; ?></div>
                  </li>
                  <?php if($goods['order_process'] == 1){?>
                  <li>消费劵码：
                      <span style="color: #ee0000;font-weight:bold;"><?=$orederInfo['Order_Virtual_Cards']?$orederInfo['Order_Virtual_Cards']:'暂无劵码'; ?></span>
                  </li>
                  <?php }else if( $goods['order_process'] == 2 ){
                    $vcard = $DB->GetRs("pintuan_virtual_card","*","WHERE Users_ID='{$UsersID}' AND Products_Relation_ID='{$goods['Products_ID']}' AND Card_Name='{$orederInfo['Order_Virtual_Cards']}'");
                    if(!empty($vcard)){
    ?>
                  <li>
                      <div>虚拟卡号：&nbsp;&nbsp;<?=$vcard['Card_Name']?></div>
                  </li>
                  <li>
                      <div>虚拟密码：&nbsp;&nbsp;<?=$vcard['Card_Password'] ?></div>
                  </li>
                  <?php 
                    }
                    }?>
                  <?php }else{?>
                  <li>
                      <div><?php echo $orederInfo['Address_Name']."&nbsp;&nbsp;".$orederInfo['Address_Mobile']; ?></div>
                      <div class="clear"></div>
                  </li>
                  <li><?php echo $orederInfo['Address_Detailed']; ?></li>
                  <?php } ?>
              </ul>
          </div>
      </div>
      <div class="jianju"></div>
      <div class="chanpin1">
          <span class="ll l"><img src="<?php echo $goods['ImgPath'];  ?>"></span>
          <span class="cp l">
              <ul>
                  <li><strong><?php echo $goods['ProductsName'];  ?></strong></li>
                  <li><?php echo $goodsInfo['Products_BriefDescription']; ?></li>
              </ul>
          </span>
          <span class="jiage r">¥<?php echo number_format('dangou'===$orederInfo['Order_Type']?$goods['ProductsPriceD']:$goods['ProductsPriceT'],2);  ?>/件</span>
      </div>
      <div class="clear"></div>
      <div class="jianju"></div>
       <?php
          if($orederInfo['Order_Shipping']){
              $shipping = json_decode($orederInfo['Order_Shipping'],JSON_UNESCAPED_UNICODE);
              if(!empty($shipping)){
              ?>
      <div class="ddxq">
          <span>配送物流</span><span class="r">
         <?php
              echo $shipping['Express']."物流";
          
          ?></span>
      </div>
      <?php } } ?>
      <div class="jianju"></div>
      <div class="fkuan">
          <span>商品总额</span><span class="ddxqr r">¥<?php echo number_format($orederInfo['Order_TotalPrice']-$goods['Shipping_fee'],2);  ?></span><br/>
          <span class="fkuan1">+运费</span><span class="fkuan2 r">¥ <?php echo number_format($goods['Shipping_fee'],2);  ?></span>
      </div>
      <div class="fkuan">
          <span class="ddxqr r">¥<?php echo $orederInfo['Order_TotalPrice'];  ?></span><span class=" r">实付款：&nbsp;</span><br/>
          <span class="fkuan1 r">下单时间：<?php  echo date("Y-m-d H:i:s",$orederInfo['Order_CreateTime']);?></span>
      </div>
      <div class="clear"></div>
      <?php if($orederInfo['Order_Status']==1){ ?>
      <div class="futime l"><span class="futime1">付款剩余时间：</span><br/><span id="shengyu"></span></div>
      <div class="futime2 r">
          <?php if(($orederInfo['Order_CreateTime']+24*3600)>time()){  ?>
          <span class="fuk1 r"><a href="<?='http://'.$_SERVER['HTTP_HOST'].'/'?>api/<?=$UsersID?>/pintuan/cart/payment/<?=$orderid; ?>/">去付款</a></span>
          <?php } ?>
          <span class="fuk r"><a onclick='return cancelorder("<?php echo $orderid; ?>");'>取消订单</a></span>
      </div>
      <?php } ?>
      <div class="clear"></div>
      <div class="jianju"></div>
      <div class="cotrs" style="position: relative;">
    <a id="f1" href="<?php echo "/api/$UsersID/pintuan/"; ?>"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br />首页</a>
    <a id="f2" href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"><img src="/static/api/pintuan/images/002-2.png" width="22px" height="22px" style=" margin-top:3px;"/><br />搜索</a>
    <a id="f3" href="<?php echo "/api/$UsersID/pintuan/user/"; ?>" class="thisclass"><img src="/static/api/pintuan/images/002-3.png" width="22px" height="22px" style=" margin-top:3px;"/><br />我的</a>
</div>
      <script>
        minu();
        setInterval(function(){
            minu();
        },"30000");
        function minu()
        {
            var times=<?php echo $orederInfo['Order_CreateTime']; ?>;
            var nowTime = parseInt((new Date().getTime())/1000);
            if((times+3600*24)>nowTime){
                var interval = 3600*24-(nowTime-times);
                var hour = parseInt(interval%86400/3600);
                var minute = parseInt(interval%3600/60);
                var html = hour+"小时"+minute+"分钟";
                $("#shengyu").html(html);
            }else{
                $("#shengyu").html("下单已超过24小时");
            }
            
        }
      
        function cancelorder(orderid) {
          if (!confirm('确定取消订单?')) {
            return false;
          }

          var info = {
            'action': 'qxdd',
            'orderid': orderid,
            'productID':"<?php echo $goods['Products_ID'];?>",
            'UsersID':"<?php echo $UsersID;?>"
          };

          $.ajax({
            url: "/api/<?php echo $UsersID;  ?>/pintuan/ajax/",
            data: info,
            type: "POST",
            dataType: "json",
            success: function (data) {
                if (data.code == "1001") {
                  layer.msg("取消订单成功！", {icon:1, time:2000},function(){
                      location.href="/api/<?php echo $UsersID;  ?>/pintuan/orderlist/1/";
                  });
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
    

      </script>
</div>

</body>
</html>

