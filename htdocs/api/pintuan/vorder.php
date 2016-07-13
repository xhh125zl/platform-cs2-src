<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
require_once ('comm/global.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/url.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/include/helper/shipping.php');

if (isset($_GET["UsersID"])) {
    $UsersID = $_GET["UsersID"];
} else {
    echo '缺少必要的参数';
    exit();
}
if (isset($_GET["csid"])) {
    $ProductID = $_GET["csid"];
} else {
    echo '缺少必要的参数';
    exit();
}
// 获取会员id
$UserID = $_SESSION[$UsersID . 'User_ID'];
// 其他参数
$rsConfig = $DB->GetRs("shop_config", "*", "where Users_ID='" . $UsersID . "'");

$Default_Shipping = $rsConfig['Default_Shipping'];
// 获取平台支付信息
$rsPay = $DB->GetRs("users_payconfig", "*", "where Users_ID='" . $UsersID . "'");
// 获取shop表信息
$pintuan = $DB->query("SELECT * FROM `pintuan_shop` where id='" . $ProductID . "'");
while ($res = $DB->fetch_assoc($pintuan)) {
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
    $imgobj = json_decode(htmlspecialchars_decode($pintuanshop["Products_JSON"]), true);
    $images = $imgobj['ImgPath'][0];
    $pintuanshop['ImgPath'] = $images;
    $parm = json_decode(htmlspecialchars_decode($pintuanshop["Products_Parameter"]), true);
    $pintuanshop['parmArr'] = $parm;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>确认订单</title>
</head>
<link href="/static/api/pintuan/css/css.css" rel="stylesheet" type="text/css">
<link href="/static/api/pintuan/css/biaoqian.css" rel="stylesheet" type="text/css">
<link href="/static/api/pintuan/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/api/pintuan/js/scrolltopcontrol.js"></script>
<script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script>
<body>
	<div class="w">
		<div class="dingdan">
			<span class="fanhui l"><a href="<?php echo isset($_COOKIE['product_detail']) ? "javascript:location.href='{$_COOKIE['product_detail']}';" : 'javascript:history.go(-1)'; ?>"><img src="/static/api/pintuan/images/fanhui.png" width="17" height="17"></a></span> <span
				class="querendd l">确认订单</span>
			<div class="clear"></div>
		</div>
		<div class="dizhi"></div>
		<?php
		      $prices= $pintuanshop['goods_price'];
		          if($pintuanshop['is_One']==0){//单购
		              $prices += $pintuanshop['Products_PriceD'];
		          }else{  //团购
		              $prices += $pintuanshop['Products_PriceT'];
		          }
		          
		?>
		<div class="chanpin1">
			<span class="l"><img src="<?php echo $pintuanshop['ImgPath']?>"></span> <span class="cp l">
				<ul>
					<li><strong><?php echo $pintuanshop['goods_name']?></strong></li>
					<?php 
					    $orgPrice = $prices;
                        ?>
					<input type="hidden" name="products" value="<?php echo $pintuanshop['goodsid']?>" class='products' />
					<input type="hidden" name="pintuanID" value="<?php echo $pintuanshop['id']?>" class='pintuanID' />
				</ul>
				</span> <span class="jiage r"><?php echo $prices;?>/件</span>
		</div>
		<div class="wl1">总价：¥<?php echo $prices; ?></div>
		<div class="clear"></div>
		<div class="wuliu">
			<span class="sr2">请输入手机号码</span> <span class="sr"> <input type="num" class="sr1" id="num" name="number" value="" notnull />
			</span>
		</div>
		<div>
			<input type="submit" value="提交" class="zhifu" id="payfor"/>
		</div>
		<div class="clear"></div>
		<div class="pintuan1">
			<div class="pt">
				<span class="l">拼团玩法</span><span class="r"><a
					href="/api/<?php echo $UsersID;?>/pintuan/liucheng/">查看详情》</a></span>
				<div class="clear"></div>
				<ul>
					<li><span class="l"><img
							src="/static/api/pintuan/images/2p-5_03.png"></span><span
						class="ptt l">选择<br />心仪商品
					</span></li>
					<li><span class="l"><img
							src="/static/api/pintuan/images/2p-5_05.png"></span><span
						class="ptt1 l">支付开团<br />或参团
					</span></li>
					<li><span class="l"><img src="/static/api/pintuan/images/2p-5_07.png"></span><span
						class="ptt l">等待好友<br />参团支付
					</span></li>
					<li><span class="l"><img
							src="/static/api/pintuan/images/2p-5_09.png"></span><span
						class="ptt l">达到人数<br />团购成功
					</span></li>
				</ul>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(function(){
        $("#payfor").click(function(){
            var Users_ID="<?php echo $UsersID;?>";
            var UserID="<?php echo $UserID;?>";
            var ProductsID=$("input[name='products']").val();
            var pintuanorder="pintuanorder";
            var pintuanID=$("input[name='pintuanID']").val();
            var number = $("input[name='number']").val();

			if(number =="" || number==null) {
				layer.msg("手机号不能为空！",{icon:1,time:2000});
				return false;
			}
			var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/; 
			if(!myreg.test(number)) 
			{ 
				layer.msg("请输入有效的手机号码！",{icon:1,time:2000});
			    return false; 
			} 


            
             $.ajax({
                   url: "/api/"+Users_ID+"/pintuan/doOrder/",
                   data: {"ProductsID":ProductsID,"Users_ID":Users_ID,"action":pintuanorder,"UserId":UserID,'pintuanID':pintuanID,number:number},
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
                         layer.msg("订单生成失败！",{icon:1,time:2000});
                         return ;
                       }else if(data.code=="4001"){
                          
                          layer.msg("活动时间已到期，请重新选择！",{icon:1,time:2000},function(){
                        	  window.location="/api/"+Users_ID+"/pintuan/";
                           });
                       }else{
                    	   layer.msg(data.msg,{icon:1,time:2000});
                           return ;
                       }
                   }
           	}); 
    
    	});
    });
	</script>
</body>
</html>
