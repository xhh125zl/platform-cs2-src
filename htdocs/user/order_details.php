<?php
if (!defined('USER_PATH')) exit();
require_once CMS_ROOT . "/user/config.inc.php";
require_once CMS_ROOT . '/include/api/ImplOrder.class.php';

//获取订单
function getOrders($Biz_Account, $page = 1, $orderID = '', $order_status){
    $transfer = ['Biz_Account' => $Biz_Account, 'pageSize' => 2, 'Order_ID' => $orderID, 'Order_Status' => $order_status];
    $res = ImplOrder::getOrders($transfer, $page);
    return $res;
}

//获取订单详情
function getOrderDetail($Biz_Account,$orderID){
    $transfer = ['Biz_Account' => $Biz_Account, 'pageSize' => 1, 'Order_ID' => $orderID];
    $res = ImplOrder::getOrders($transfer);
    return $res;
}

if (isset($_GET['orderid'])) {
    $Order_ID = $_GET['orderid'];
    $res = getOrderDetail($_SESSION['Biz_Account'],$_GET['orderid']);
    if ($res['errorCode'] == 0) {
        $orderDetail = $res['data'][0];
        //收货地址
        $area_json = read_file($_SERVER["DOCUMENT_ROOT"].'/data/area.js');
        $area_array = json_decode($area_json,TRUE);
        $province_list = $area_array[0];
        $Province = '';
        if(!empty($orderDetail['Address_Province'])){
            $Province = $province_list[$orderDetail['Address_Province']].',';
        }
        $City = '';
        if(!empty($orderDetail['Address_City'])){
            $City = $area_array['0,'.$orderDetail['Address_Province']][$orderDetail['Address_City']].',';
        }

        $Area = '';
        if(!empty($orderDetail['Address_Area'])){
            $Area = $area_array['0,'.$orderDetail['Address_Province'].','.$orderDetail['Address_City']][$orderDetail['Address_Area']];
        }

        $Shipping=json_decode(htmlspecialchars_decode($orderDetail["Order_Shipping"]),true);
    } else {
        echo '<div style="margin-top:100px; font-size:35px; color:red; text-align:center;">此订单已取消，或已被删除...<br/><br/><a href="javascript:history.back();" id="back">自动返回中...</a></div><script>setTimeout("history.back();",1000)</script>';exit;
    }
} else {
    echo '<div style="margin-top:100px; font-size:35px; color:red; text-align:center;">获取订单号失败...<br/><br/><a href="javascript:history.back();" id="back">自动返回中...</a></div><script>setTimeout("history.back();",1000)</script>';exit;
}

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="app-mobile-web-app-capable" content="yes">
    <title>订单详情</title>
    <link href="../static/user/css/product.css" type="text/css" rel="stylesheet">
    <link href="../static/user/css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="../static/user/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="../static/user/js/layer.js"></script>
    <style type="text/css">
        .input_write{ width: 200px; height: 25px; line-height: 25px; text-indent: 10px; border: 1px solid #d9d9d9;}
    </style>
</head>
<body>
<?php
if ($_POST) {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    if ($action == 'order_confirm') {
        $Data = array(
            "Address_Name" => $_POST['Name'],
            "Address_Mobile" => $_POST["Mobile"],
            "Order_Remark" => htmlspecialchars($_POST["Remark"]),
            "Order_SendTime" => time(),
            "Order_Status" => 1
        );
    } else if ($action == 'order_send') {
        $Data = array(
            "Address_Name" => $_POST['Name'],
            "Address_Mobile" => $_POST["Mobile"],
            "Order_ShippingID" => $_POST["ShippingID"],
            "Order_Remark" => htmlspecialchars($_POST["Remark"]),
            "Order_SendTime" => time(),
            "Order_Status" => 3
        );
    }
    $res = ImplOrder::actionOrdersend401(['Order_ID' => $orderDetail['Order_ID'], 'orderData' => $Data]);

    if (isset($res['errorCode']) && $res['errorCode'] == 0){
        echo '<script>window.location.href="";</script>';
    } else {
        echo '<script>layer.open({content: "操作失败，请重试！", btn: "确定", success: function(){window.location.href="";}});</script>';
    }
}
?>
<div class="w">
    <div class="slideTxtBox">
        <div class="back_x">
            <a class="l" href='javascript:history.back();'><i class="fa  fa-angle-left fa-2x" aria-hidden="true"></i></a>订单详情
        </div>
        <div class="bd order_x">
            <ul>
                <li>
                    <span class="left">订单编号：</span>
                    <span class="left"><?=$orderDetail['Order_ID']?></span>
                </li>
                <li>
                    <span class="left">订单时间：</span>
                    <span class="left"><?=date('Y-m-d H:i:s', $orderDetail['Order_CreateTime'])?></span>
                </li>
                <li>
                    <span class="left">订单总价：</span>
                    <span class="left" style="color:red"><?='￥'.$orderDetail['Order_TotalPrice']?></span>
                </li>
                <li>
                    <span class="left">订单状态：</span>
                    <span class="left" style="color:red">
                    <?php switch ($orderDetail['Order_Status']) {
                            case 0:
                                echo "待确认";
                                break;
                            case 1:
                                echo "未付款";
                                break;
                            case 2:
                                echo "已付款";
                                break;
                            case 3:
                                echo "已发货";
                                break;
                            case 4:
                                echo "已完成";
                                break;
                            case 5:
                                echo "申请退款中";
                                break;
                            default:
                                echo "状态获取失败";
                    } ?>
                    </span>
                </li>
                <li>
                    <span class="left">配送方式：</span>
                    <span class="left">
                    <?php
                        if(empty($Shipping)){
                            echo "暂无信息";
                        }else{
                            if(empty($Shipping["Express"])){
                                echo "暂无信息";
                            }else{
                                echo $Shipping["Express"];
                            }
                        }
                        //echo empty($Shipping)?"":$Shipping["Express"]
                    ?>
                        <strong style="color:#FF0000;">
                            <?php if(empty($Shipping["Price"])){ $fee = 0;?>
                                免运费
                            <?php }else{
                                $fee = $Shipping["Price"];
                                ?>
                                ￥<?php echo $Shipping["Price"];?>
                            <?php }?>
                        </strong>
                    </span>
                </li>
                <?php if ($orderDetail['Order_Status'] > 2) { ?>
                <li>
                    <span class="left">快递单号：</span>
                    <span class="left"><?php echo $orderDetail["Order_ShippingID"] ?></span>
                </li>
                <?php } ?>

                <?php if (in_array($orderDetail['Order_Status'], array(1, 3, 4, 5)) || ($orderDetail['Order_Status'] == 2 && strlen($orderDetail['Sales_By']) > 1) || ($orderDetail['Order_Status'] == 2 && $orderDetail["Order_IsVirtual"] == 1)) { ?>
                <li>
                    <span class="left">收&nbsp;&nbsp;货&nbsp;人：</span>
                    <span class="left"><?php echo $orderDetail["Address_Name"] ?></span>
                </li>
                <li>
                    <span class="left">手机号码：</span>
                    <span class="left"><?php echo $orderDetail["Address_Mobile"] ?></span>
                </li>
                <li>
                    <span class="left">收货地址：</span>
                    <span class="left"><?php echo $Province.$City.$Area.'【'.$orderDetail["Address_Name"].'，'.$orderDetail["Address_Mobile"].'】' ?></span>
                </li>
                <li>
                    <textarea name="Remark" disabled="disabled" style="width:97%; height:60px; border:1px solid #d9d9d9;" placeholder="请输入订单备注"><?php echo htmlspecialchars_decode($orderDetail['Order_Remark']); ?></textarea>
                </li>
                <?php } ?>

                <!-- 确认订单 -->
                <?php if ($orderDetail['Order_Status'] == 0) { ?>
                <form method="post" action="" id="order_confirm_form">
                    <input type="hidden" name="action" value="order_confirm" />
                    <li>
                        <span class="left">收&nbsp;&nbsp;货&nbsp;人：</span>
                        <span class="left"><input type="text" class="input_write" name="Name" value="<?php echo $orderDetail["Address_Name"] ?>" size="10"/></span>
                    </li>
                    <li>
                        <span class="left">手机号码：</span>
                        <span class="left"><input type="tel" class="input_write" name="Mobile" value="<?php echo $orderDetail["Address_Mobile"] ?>" size="15"/></span>
                    </li>
                    <li>
                        <span class="left">收货地址：</span>
                        <span class="left"><?php echo $Province.$City.$Area.'【'.$orderDetail["Address_Name"].'，'.$orderDetail["Address_Mobile"].'】' ?></span>
                    </li>
                    <li>
                        <textarea name="Remark" style="width:97%; height:60px; border:1px solid #d9d9d9;" placeholder="请输入订单备注"><?php echo htmlspecialchars_decode($orderDetail['Order_Remark']); ?></textarea>
                    </li>
                    <li>
                        <input type="button" value="确认订单" style="margin-left:70%; border:0; width:100px; height: 30px; background-color: green; border-radius: 15px; color: #fff;">
                    </li>
                </form>
                <?php } ?>

                <!-- 自营商品(非虚拟)发货 -->
                <?php if ($orderDetail['Order_Status'] == 2 && $orderDetail["Order_IsVirtual"]<>1 && $orderDetail['Sales_By'] == '0') { ?>
                <form method="post" action="" id="order_send_form">
                    <input type="hidden" name="action" value="order_send" />
                    <li>
                        <span class="left">快递单号：</span>
                        <span class="left"><input type="number" class="input_write" name="ShippingID" value="<?php echo $orderDetail["Order_ShippingID"] ?>"/></span>
                    </li>
                    <li>
                        <span class="left">收&nbsp;&nbsp;货&nbsp;人：</span>
                        <span class="left"><input type="text" class="input_write" name="Name" value="<?php echo $orderDetail["Address_Name"] ?>" size="10"/></span>
                    </li>
                    <li>
                        <span class="left">手机号码：</span>
                        <span class="left"><input type="tel" class="input_write" name="Mobile" value="<?php echo $orderDetail["Address_Mobile"] ?>" size="15"/></span>
                    </li>

                    <li>
                        <span class="left">收货地址：</span>
                        <span class="left"><?php echo $Province.$City.$Area.'【'.$orderDetail["Address_Name"].'，'.$orderDetail["Address_Mobile"].'】' ?></span>
                    </li>

                    <li>
                        <textarea name="Remark" style="width:97%; height:60px; border:1px solid #d9d9d9;" placeholder="请输入订单备注"><?php echo htmlspecialchars_decode($orderDetail['Order_Remark']); ?></textarea>
                    </li>
                    <li>
                        <input type="button" value="确认发货" style="margin-left:70%; border:0; width:100px; height: 30px; background-color: green; border-radius: 15px; color: #fff;">
                    </li>
                </form>
                <?php } ?>

                <?php $order_cartList = json_decode($orderDetail['Order_CartList'], true);
                if (!empty($order_cartList)) {
                    foreach ($order_cartList as $key => $val) {
                        foreach ($val as $goodskey => $goodsval) { ?>
                <li>
                    <div class="pro_xt" style="padding:0">
                        <div class="img_xt"><a href="#"><img src="<?=$goodsval['ImgPath']?>" height="90" width="90"></a></div>
                        <dl class="info_xt">
                            <dd class="name_xt"><a href="#"><?=$goodsval['ProductsName']?></a></dd>
                            <dd>￥<?=$goodsval['ProductsPriceX']?>×<?=$goodsval['Qty']?>=￥<?=$goodsval['ProductsPriceX'] * $goodsval['Qty']?></dd>
                            <dd>下单时间：<?=date('Y-m-d H:i:s', $orderDetail['Order_CreateTime'])?></dd>
                        </dl>
                        <div class="clear"></div>
                    </div>
                </li>
                <?php }}} ?>
                <li>
                    <span class="right">订单总价：<a style="color:red;">￥<?=$orderDetail['Order_TotalPrice'] - $fee?><?if ($fee > 0) {?>+￥<?=$fee?>(运费)=￥<?=$orderDetail['Order_TotalPrice']?><?}?></a></span>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    //检测输入框是否为空
    function check_null(input) {
        var self_attr = input.attr('style');
        var add_attr = ";border:1px solid red;";
        if($.trim(input.val()) == '') {
            input.attr('style', self_attr+add_attr);
            input.focus();
            return false;
        } else {
            if(self_attr) {
                self_attr = self_attr.replace(add_attr, '');
            }
            input.attr('style', self_attr);
            return true;
        }
    }
    $(function(){
        //确认订单
        $('#order_confirm_form input:button').click(function(){
            //联系人
            if (!check_null($('input[name="Name"]'))) {
                return false;
            }
            //联系电话
            if (!check_null($('input[name="Mobile"]'))) {
                return false;
            }
            $('#order_confirm_form').submit();
        });  
        //确认发货
        $('#order_send_form input:button').click(function(){
            //快递单号
            if (!check_null($('input[name="ShippingID"]'))) {
                return false;
            }
            //联系人
            if (!check_null($('input[name="Name"]'))) {
                return false;
            }
            //联系电话
            if (!check_null($('input[name="Mobile"]'))) {
                return false;
            }
            $('#order_send_form').submit();
        });
    });
</script>