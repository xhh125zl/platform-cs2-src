<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
require_once ('comm/global.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/url.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/url.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/lib_pintuan.php');

$base_url = base_url();
$shop_url = shop_url();

if (isset($_GET["UsersID"])) {
    $UsersID = $_GET["UsersID"];
} else {
    echo '缺少必要的参数';
    exit();
}
if (isset($_GET["csid"])) {
    $ProductID = $_GET["csid"];
    $_SESSION[$UsersID.'csid'] = $ProductID;
} else {
    echo '缺少必要的参数';
    exit();
}

if (empty($_SESSION)) {
    header("location:/api/".$UsersID."/pintuan/");
    exit;
}

setcookie('url_referer', $_SERVER["REQUEST_URI"], time()+3600, '/', $_SERVER['HTTP_HOST']);
$UserID = $_SESSION[$UsersID . "User_ID"];
$rsPay = $DB->GetRs("users_payconfig", "*", "where Users_ID='" . $UsersID . "'");
$pintuans = array();
$address = array();
$msgisdefault = true;
if(isset($_GET['addressid']) && !empty($_GET['addressid'])){
    $address = $DB->Get('user_address', '*', "where Users_ID ='" . $UsersID . "' and User_ID='" . $UserID . "' and Address_ID='".$_GET['addressid']."'");
    $msgisdefault = false;
}else{
    $address = $DB->Get('user_address', '*', "where Users_ID ='" . $UsersID . "' and User_ID='" . $UserID . "' and Address_Is_Default=1 ");
    $msgisdefault = true;
}


while ($reg = $DB->fetch_assoc()) {
    $pintuans[] = $reg;
}

// 地址判断
if (! empty($pintuans)) {
    $pintuanshop = array();
    $result = $DB->Get("pintuan_shop", "*", "where id='" . $ProductID . "'");
    if (empty($result)) {
        header("location:/api/" . $UsersID . "/pintuan/");
    }
    
    while ($res = $DB->fetch_assoc()) {
        $pintuanshop['id'] = $res['id'];
        $pintuanshop['goodsid'] = $res['goodsid'];
        $pintuanshop['goods_name'] = $res['goods_name'];
        $pintuanshop['goods_canshu'] = $res['goods_canshu'];
        $pintuanshop['goods_num'] = $res['goods_num'];
        $pintuanshop['goods_price'] = $res['goods_price'];
        $pintuanshop['is_vgoods'] = $res['is_vgoods'];
        $pintuanshop['is_One'] = $res['is_One'];
        $result = $DB->GetRs("pintuan_products","*","where Products_ID='" . $pintuanshop['goodsid'] . "' and Users_ID='" . $UsersID . "'");
        $pintuanshop['goods_name'] = $result['Products_Name'];
        $pintuanshop['Products_Parameter'] = $result['Products_Parameter'];
        $pintuanshop['Products_PriceT'] = $result['Products_PriceT'];
        $pintuanshop['Products_PriceD'] = $result['Products_PriceD'];
        $pintuanshop['Products_Profit'] = $result['Products_Profit'];
        $pintuanshop['Products_Distributes'] = $result['Products_Distributes'];
        $pintuanshop['Products_JSON'] = $result['Products_JSON'];
        $pintuanshop['Products_BriefDescription'] = $result['Products_BriefDescription'];
        $pintuanshop['Products_CreateTime'] = $result['Products_CreateTime'];
        $pintuanshop['Products_Count'] = $result['Products_Count'];
        $pintuanshop['Is_Draw'] = $result['Is_Draw'];
        $pintuanshop['products_IsHot'] = $result['products_IsHot'];
        $pintuanshop['Products_pinkage'] = $result['Products_pinkage'];
        $pintuanshop['Products_IsShippingFree'] = $result['Products_IsShippingFree'];
        
        $imgobj = json_decode(htmlspecialchars_decode($pintuanshop["Products_JSON"]), true);
        $images = $imgobj['ImgPath'][0];
        $pintuanshop['ImgPath'] = $images;
    }
    $goods = $DB->GetRs("pintuan_products","Biz_ID","WHERE Products_ID='{$pintuanshop['goodsid']}'");
    $Biz_ID = 0;
    if(!empty($goods) && $goods['Biz_ID']){
        $Biz_ID = $goods['Biz_ID'];
    }
    // 主键参数
    
    $rsConfig = $DB->GetRs("biz", "*", "where Users_ID='" . $UsersID . "' AND Biz_ID={$Biz_ID}");
    $shipping_company_dropdown = get_front_shiping_company_dropdown($UsersID, $rsConfig);
    $Shipping_ID = ! empty($rsConfig['Default_Shipping']) ? $rsConfig['Default_Shipping'] : 0;
    $Shipping_Name = '';
    if ($Shipping_ID > 0 && empty($shipping_company_dropdown)) {
        $Shipping_Name = isset($shipping_company_dropdown[$Shipping_ID])?$shipping_company_dropdown[$Shipping_ID]:"";
    }
    $Default_Shipping = $rsConfig['Default_Shipping'];
} else {
    header("location:/api/" . $UsersID . "/pintuan/my/address/" . (empty($TypeID) ? '' : $TypeID . '/') . "?wxref=mp.weixin.qq.com");
    exit();
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>确认订单</title>
    <link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
    <link href="/static/api/pintuan/css/biaoqian.css" rel="stylesheet" type="text/css">
    <link href="/static/api/pintuan/css/style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script>
    <script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
</head>
<body>
	<input type="hidden" name="userid" value="<?php echo $UsersID;?>" class="userid" />
	<input type="hidden" name="userid1" value="<?php echo $UserID; ?>" class="userid1" />
<div class="dingdan">
  <span class="fanhui l"><a href="<?php echo isset($_COOKIE['product_detail']) ? "javascript:location.href='{$_COOKIE['product_detail']}';" : 'javascript:history.go(-1)'; ?>"><img src="/static/api/pintuan/images/fanhui.png" width="17px" height="17px"></a></span>
  <span class="querendd l">确认订单</span>
  <div class="clear"></div>
</div>
	<div class="w">
		<div>
    		<div class="dz l">送至</div>
    		<div class="dz1 l">
    			<?php 
    			$address_id = 0;
    			foreach ($pintuans as $r=>$d){
    			    $address_id = $d['Address_ID'];
    			?>
    			<ul>
    				<li><div class="l"><?php echo $d['Address_Name']?>&nbsp;&nbsp;<?php echo $d['Address_Mobile']?></div>
    					<?php if($msgisdefault){?><div class="mr_moren l">默认</div><?php } ?>
    					<div class="clear"></div>
    				</li>
    				<li><?php echo $d['Address_Detailed']?></li>
    			</ul>
    			<input type="hidden" name="AddressID" value="<?php echo $d['Address_ID']?>" class="AddressID" />
    			<?php }?>
    		</div>
    		<div class="more r">
    			<a href="/api/<?php echo $UsersID?>/pintuan/my/address/">></a>
    		</div>
        	
		</div>
		
		
		<div class="clear"></div>
		<div class="dizhi"></div>
 		<?php
            if (! empty($pintuanshop)) {
                $prices= $pintuanshop['goods_price'];
                /*
                if($pintuanshop['is_One']==0){//单购
                    $prices += $pintuanshop['Products_PriceD'];
                }else{  //团购
                    $prices += $pintuanshop['Products_PriceT'];
                }*/
        ?>
		<div class="chanpin1">
			<span class="l"><img style="width:100px;" src="<?php echo $pintuanshop['ImgPath'];?>"/></span>
			<span class="cp l" style="width: 50%;">
				<ul>
					<li><strong><?php echo $pintuanshop['goods_name']?></strong></li>
					<?php 
					    $orgPrice = $prices;
                    ?>
					<li><?php echo $pintuanshop['Products_BriefDescription']; ?></li>
					
					<input type="hidden" name="products" value="<?php echo $pintuanshop['goodsid']?>" class='products' />
					<input type="hidden" name="orgPrice" value="<?php echo $orgPrice?>" />
					<input type="hidden" name="pintuanID" value="<?php echo $pintuanshop['id']?>" class='pintuanID' />
				</ul>
			</span>
			<span class="jiage r"><?php echo $pintuanshop['goods_price']?>元/件</span>
		</div>
		<div class="wuliu">
			<div class="wt">选择物流</div>
			<div class="wl">
				<ul>
  				<?php
  				    $wuliuid = 0;
  				    $fee = 0;
  				    $defee = 0;
                    if (! empty($shipping_company_dropdown)) {
                        foreach ($shipping_company_dropdown as $key => $item) {
                            if($pintuanshop['Products_IsShippingFree']<=0 && !$pintuanshop['Products_pinkage']){ //有运费
                                $fee = getShipFee($key,$UsersID);   //获取物流费用
                                if($Default_Shipping == $key){  //设置默认快递
                                    $prices += $fee;
                                    $defee = $fee;
                                    $wuliuid = $key;
                                }
                                
                            }else{
                                $wuliuid = 0;
                            }
                            ?>
            		<li>
            			<span class="l">
            				<img src="/static/api/pintuan/images/<?=($Default_Shipping == $key)?'2p-5_08.png':'2p-5_06.png';?>">
            				<input type="radio" shipping_name="<?=$item?>" <?=($Default_Shipping == $key)?'checked':'';?> name="Shiping_ID" value="<?=$key?>" fee="<?php echo $fee?$fee:0 ?>"/>
            				
            			</span>
            			<span class="wlt l"><?php echo $item;?></span>
            		</li>
            	<?php
                        }
                    }
                ?>
				</ul>
			</div>
			<script type="text/javascript">
			$(".wuliu .wl .l input").hide();
			$(".wuliu .wl li").click(function(){
				$(this).find("img").attr("src","/static/api/pintuan/images/2p-5_08.png");
				$(this).siblings().find("img").attr("src","/static/api/pintuan/images/2p-5_06.png");
				$(this).find("input[name='Shiping_ID']").attr("checked","checked");
				var fee = $(this).find("input[name='Shiping_ID']").attr("fee")? parseFloat($(this).find("input[name='Shiping_ID']").attr("fee")):0;
    			var orgPrice = parseFloat($("input[name='orgPrice']").val());
    			var totalPrice  = fee + orgPrice;
    			$("input[name='wuliu']").val($(this).find("input[name='Shiping_ID']").val());
    			$("#myfee").html(fee);
    			$("#totalprice").html("总价：¥ "+totalPrice.toFixed(2));
			});
			</script>
		</div>
		<div class="wl1">
			<span class="wl2">运费: ¥ <span id="myfee"><?php echo $defee; ?></span>
			<input type="hidden" name="wuliu" value="<?=$wuliuid ?>"/>
			</span>&nbsp;&nbsp;
			<span id="totalprice">总价：¥ <?php echo $prices;?></span>
		</div>
        <?php
        }
        ?>
		<div class="clear"></div>
		<!-- <div class="wuliu">
        <div class="wt">选择支付方式</div>
        <div class="wl">
        <ul>
        <li><span class="l"><img src="images/2p-5_08.png"></span><span class="wlt l">支付宝</span></li>
        <li><span class="l"><img src="images/2p-5_06.png"></span><span class="wlt l">微信</span></li>
        <li><span class="l"><img src="images/2p-5_06.png"></span><span class="wlt l">财付通</span></li>
        </ul>
        </div>
        </div> -->
	<div>
	<input type="submit" value="提交订单" class="zhifu" id="payfor" />
</div>
<div class="clear"></div>
	<div class="pintuan1">
			<div class="pt">
				<span class="l">拼团玩法</span><span class="r"><a href="/api/<?=$UsersID ?>/pintuan/liucheng/">查看详情》</a></span>
				<div class="clear"></div>
				<ul>
					<li><span class="l"><img src="/static/api/pintuan/images/001_1.png"></span><span class="ptt l">选择<br />心仪商品 </span></li>
					<li><span class="l"><img src="/static/api/pintuan/images/001_2.png"></span><span class="ptt1 l">支付开团<br />或参团 </span></li>
					<li><span class="l"><img src="/static/api/pintuan/images/001_3.png"></span><span class="ptt l">等待好友<br />参团支付 </span></li>
					<li><span class="l"><img src="/static/api/pintuan/images/001_4.png"></span><span class="ptt l">达到人数<br />团购成功 </span></li>
				</ul>
			</div>
	</div>
</div>
<div style="height:70px;"></div>
<div class="cotrs">
  <a id="f1" href="<?php echo "/api/$UsersID/pintuan/"; ?>" class="thisclass"><img src="/static/api/pintuan/images/002-1.png" width="25px" height="25px" /><br/>首页</a>
  <a id="f2" href="<?php echo "/api/$UsersID/pintuan/seach/0/"; ?>"><img src="/static/api/pintuan/images/002-2.png" width="25px" height="25px" /><br/>搜索</a>
  <a id="f3" href="<?php echo "/api/$UsersID/pintuan/user/"; ?>"><img src="/static/api/pintuan/images/002-3.png" width="25px" height="25px" /><br/>我的</a>
</div>
</body>
<script type="text/javascript">
    $(function(){
    	  
            $("#payfor").click(function(){
                   var Users_ID=$('.userid').val();
                   var UserID=$('.userid1').val();
                   var Shipping_ID=$('.AddressID').val();
                   var ProductsID=$('.products').val();
                   var pintuanorder="pintuanorder";
                   var pintuanID=$('.pintuanID').val();
                   var wuliu=$('input[name="wuliu"]').val();
                   if(wuliu==undefined || wuliu==null || wuliu==""){
                	   layer.msg("不能回退进行订单提交!",{icon:1,time:2000},function(){
							window.location = "/api/"+Users_ID+"/pintuan/orderlist/1/";
                       });
                	   return false;
                   }
				   if(localStorage.getItem("<?=$UsersID ?>MyAddressID")){
					   Shipping_ID=localStorage.getItem("<?=$UsersID ?>MyAddressID");
					   localStorage.removeItem("<?=$UsersID ?>MyAddressID");
				   }

                    $.ajax({
                      url: "/api/"+Users_ID+"/pintuan/doOrder/",
                      data: {"Shipping_ID":Shipping_ID,"ProductsID":ProductsID,"Users_ID":Users_ID,
                      "action":pintuanorder,"UserId":UserID,'pintuanID':pintuanID,"wuliu":wuliu},
                      type: "POST",
                      dataType: "json",
                      success: function (data) {
                          if(data.code=="1001"){
                        	  window.location=data.url;
                          }else if(data.code=="1005"){
                              layer.msg("请勿重复提交订单！",{icon:1,time:2000},function(){
									window.location = data.url;
                              });
                              return ;
                          }else if(data.code=="1002"){
                            layer.msg("订单生成失败！",{icon:1,time:3000});
                            return ;
                          }else if(data.code=="4001"){
                        	  layer.msg("活动时间已到期，请重新选择！",{icon:1,time:3000},function(){
                        		  window.location="/api/"+Users_ID+"/pintuan/";
                              });
                              
                          }else{
                        	  layer.msg(data.msg,{icon:1,time:3000});
                              return ;
                          }
                      }
                  }) 
            
             });
    });
</script>
</html>



