<?php 
/*
 * 本文件主要是下订单不涉及支付情况
 */
require_once($_SERVER["DOCUMENT_ROOT"].'/Framework/Conn.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/shipping.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/tools.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_pintuan.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/lib_products.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/order.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/pay_order.class.php');
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/Framework/Ext/virtual.func.php');
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/Framework/Ext/sms.func.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/url.php');
$action = empty($_REQUEST["action"]) ? "" : $_REQUEST["action"];

if ($action!=="pintuanorder") {
    die(json_encode([ 'status'=>0, 'code'=>1002 ]));
}

if(!isset($_POST['ProductsID'])){
    die(json_encode([ 'status'=>0, 'code'=>-1 ,'msg'=>'非法参数提交'  ]));
}
if(!isset($_POST['Users_ID'])){
    die(json_encode([ 'status'=>0, 'code'=>-1 ,'msg'=>'不正确的商户ID'  ]));
}

$userid = $_SESSION[$_POST['Users_ID'].'User_ID'];
if(!$userid){
    die(json_encode([ 'status'=>0, 'code'=>-1000 ,'msg'=>'未登录用户'  ]));
}


$ProductsID         = $_POST['ProductsID'];
$Users_ID           = $_POST['Users_ID'];
$UserID             = $_POST["UserId"];

$stock = $DB->GetRs("pintuan_products","Products_Count","where Products_ID='{$ProductsID}' and Users_ID='{$Users_ID}'");
if($stock['Products_Count']==0){   //库存不足
    die(json_encode([ 'status'=>0, 'code'=>-1 ,'msg'=>'商品库存不足'  ]));
}

/* $id=$_SESSION[$_SESSION['Users_ID'].'User_ID']; */
$addressID          = isset($_POST['Shipping_ID'])?$_POST['Shipping_ID']:0;    //个人地址
$pintuanShopID      = isset($_POST["pintuanID"])?$_POST["pintuanID"]:0;   //pintuanShopID
$Data               = array();
$results            = array();
$shipp_TotalPrice   = 0;
$Order_Shipping     = array();
$speclist           = isset($_POST['spec_list']) ? $_POST['spec_list'] : '';
//获取商品信息
$goodsInfo=getProducts($ProductsID,$Users_ID,$speclist);
$products = $goodsInfo["products"];
$CartList = $goodsInfo["cartlist"];
$BizID = $goodsInfo["products"]["Biz_ID"];
$CartList['Shipping_fee'] = 0;
$goodsName = $CartList['ProductsName'];
$sessionid = session_id();
$CartList['TeamID']=isset($_SESSION[$sessionid.'_TeamID'])?$_SESSION[$sessionid.'_TeamID']:1;
//物流信息:代码开始
$wuliu = empty($_POST["wuliu"]) ? 0 : $_POST["wuliu"];
if($wuliu){
    $shipping = $DB->GetRs("shop_shipping_company","Shipping_Name","where Users_ID='".$Users_ID."' and Shipping_ID=".$_POST["wuliu"]);
    if($shipping){
        $shipp_TotalPrice = getShipFee($wuliu,$Users_ID,$BizID,$products['Products_Weight']);
        $Order_Shipping = array("Express"=>$shipping["Shipping_Name"],"Price"=>$shipp_TotalPrice?$shipp_TotalPrice:0);
        if($CartList['IsShippingFree']==1){ //包邮
           
            $shipp_TotalPrice = 0;
        }
    }
}
$CartList['Shipping_fee']=$shipp_TotalPrice;
if($pintuanShopID){
    $pintuan_shop=$DB->Get("pintuan_shop",'*',"where id='".$pintuanShopID."'");
    if(!$pintuan_shop) die(json_encode(['code'=>'1002','status'=>0], JSON_UNESCAPED_UNICODE));
    $pintuan_shops = array();
    while ($res=$DB->fetch_assoc()){
        $pintuan_shops["goodsid"]=$res["goodsid"];
        $pintuan_shops["is_One"]=$res["is_One"];//是否团购0：是1：不是
        $pintuan_shops["goods_price"]=$res["goods_price"];
        $pintuan_shops["is_vgoods"]=$res["is_vgoods"];//是否虚拟0：是1：不是
        $pintuan_shops["is_Draw"]=$res["is_Draw"];//是否抽奖0：是1：不是
        $pintuan_shops["goods_num"]=$res["goods_num"];//购买数量
        $pintuan_shops["goods_price"]=$res["goods_price"]?$res["goods_price"]:0;//价格
    }
}else{
    die(json_encode(['code'=>'1002','status'=>0], JSON_UNESCAPED_UNICODE));
}
if(empty($pintuan_shops)){
    die(json_encode(['code'=>'1005','status'=>0,'url'=>$_SERVER['HTTP_REFERER']], JSON_UNESCAPED_UNICODE));
}
//拼团结束时间
$pintuan_stop=date('Y-m-d',$products['stoptime']);
$pintuan_start=date('Y-m-d',$products['starttime']);
$now=date('Y-m-d',time());

//如果是拼团并且超过指定的时间
if(($pintuan_stop<$now || $now<$pintuan_start) && $pintuan_shops["is_One"]){
    die(json_encode(['code'=>'4001','status'=>0], JSON_UNESCAPED_UNICODE));
}
$Order_CartList = json_encode($CartList, JSON_UNESCAPED_UNICODE);
$order_no =order_no();
$order_id = 0;

//订单状态:  0：待确认       1：订单已生成未付款       2：已付款     3：已发货        4：完成
if($pintuan_shops["is_vgoods"]==0){    //实物订单
    if($pintuan_shops['is_One']==1){   //团购和单购的判断 1：团购 0 单购
        if($pintuan_shops['is_Draw']==0){     //抽奖和不抽奖的判断 0:抽奖1:不抽奖
                begin_trans();
                $address=$DB->get('user_address','*','where Address_ID="'.$addressID.'"');
                while ($res =$DB->fetch_assoc()) {
                    $Data['Address_Name']=$res['Address_Name'];
                    $Data['Address_Mobile']=$res['Address_Mobile'];
                    $Data['Address_Province']=$res['Address_Province'];
                    $Data['Address_City']=$res['Address_City'];
                    $Data['Address_Area']=$res['Address_Area'];
                    $Data['Address_Detailed']=$res['Address_Detailed'];
                }
                $Data['Users_ID']=$_POST['Users_ID'];
                $Data['Order_Type']='pintuan';
                $Data['Order_Status']='1';
                $Data['Order_CreateTime']=time();
                $Data['Order_TotalPrice']=$shipp_TotalPrice+$pintuan_shops["goods_num"]*$pintuan_shops["goods_price"];
                $Data['Order_TotalAmount'] = $Data['Order_TotalPrice'];
                $Data["Order_Shipping"]=json_encode($Order_Shipping,JSON_UNESCAPED_UNICODE);
                $Data["Order_CartList"]=$Order_CartList;
                $Data["Order_ShippingID"]=$wuliu;
                $Data["Order_Code"]=$order_no;
                $Data["Biz_ID"]=$BizID;
                /*$userid=
                 $_SESSION[$_SESSION['Users_ID'].'User_ID'];*/
                $Data['User_ID']=$UserID;
                $Flag=$DB->Add("user_order",$Data);
                $order_id = $neworderid = $DB->insert_id();
                $pintuandata=array();
                $pintuandata['order_id']=$neworderid;
                $pintuandata['pintuan_id']=$_POST['pintuanID'];
                $pintuandata['Users_ID']=$_POST['Users_ID'];
                $pintuandata['User_ID']=$UserID;
                $pintuandata['addtime']=time();
                $pintuandata['pintuan_status']='1';
                $pintuandata['order_status']="1";
                $pintuandata['products_status']='2';
                $pintuandata['is_vgoods']='0';

                $flags=$DB->Add('pintuan_order',$pintuandata);
                if($Flag && $flags){
                    
                    $pintuan_arr= json_encode(array('code'=>'1001','url'=>'/api/'.$Users_ID.'/pintuan/cart/payment/'.$order_id.'/'), JSON_UNESCAPED_UNICODE);
                    removeCar();
                    Stock($ProductsID,$Users_ID);
                    commit_trans();
                    // 此处调用微信推送接口，告知用户已下单
                    try {
                        sendWXMessage($Users_ID,$order_id,"您已成功提交拼团订单，订单号为：".$order_no,$UserID);
                    } catch (Exception $e) {
                        
                    }

                    //插入拼团表
                    echo $pintuan_arr;
                    exit;
                }else{
                    back_trans();
                    $pintuan_arr= json_encode(array('code'=>'1002'), JSON_UNESCAPED_UNICODE);
                    echo $pintuan_arr;
                    exit;
                }

            }else{  //不抽奖
                begin_trans();
                $address=$DB->get('user_address','*','where Address_ID="'.$addressID.'"');
                while ($res =$DB->fetch_assoc()) {
                    $Data['Address_Name']=$res['Address_Name'];
                    $Data['Address_Mobile']=$res['Address_Mobile'];
                    $Data['Address_Province']=$res['Address_Province'];
                    $Data['Address_City']=$res['Address_City'];
                    $Data['Address_Area']=$res['Address_Area'];
                    $Data['Address_Detailed']=$res['Address_Detailed'];
                }
                $Data['Users_ID']=$_POST['Users_ID'];
                $Data['Order_Type']='pintuan';
                $Data['Order_Status']='1';
                $Data['Order_CreateTime']=time();
                $Data['Order_TotalPrice']=$shipp_TotalPrice+$pintuan_shops["goods_num"]*$pintuan_shops["goods_price"];
                $Data['Order_TotalAmount'] = $Data['Order_TotalPrice'];
                $Data["Order_Shipping"]=json_encode($Order_Shipping,JSON_UNESCAPED_UNICODE);
                $Data["Order_CartList"]=$Order_CartList;
                $Data["Order_ShippingID"]=$wuliu;
                $Data["Order_Code"]=$order_no;
                $Data["Biz_ID"]=$BizID;
                /*$userid=
                 $_SESSION[$_SESSION['Users_ID'].'User_ID'];*/
                $Data['User_ID']=$UserID;
                $Flag=$DB->Add("user_order",$Data);
                $order_id = $neworderid = $DB->insert_id();
                $pintuandata=array();
                $pintuandata['order_id']=$neworderid;
                $pintuandata['pintuan_id']=$_POST['pintuanID'];
                $pintuandata['Users_ID']=$_POST['Users_ID'];
                $pintuandata['User_ID']=$UserID;
                $pintuandata['addtime']=time();
                $pintuandata['pintuan_status']='1';
                $pintuandata['order_status']="1";
                $pintuandata['products_status']='1';
                $pintuandata['is_vgoods']='0';
                $flags=$DB->Add('pintuan_order',$pintuandata);
                if($Flag && $flags){
                    
                    $pintuan_arr= json_encode(array('code'=>'1001','url'=>'/api/'.$Users_ID.'/pintuan/cart/payment/'.$order_id.'/'), JSON_UNESCAPED_UNICODE);
                    removeCar();
                    Stock($ProductsID,$Users_ID);
                    commit_trans();
                    // 此处调用微信推送接口，告知用户已下单
                    try {
                        sendWXMessage($Users_ID,$order_id,"您已成功提交拼团订单，订单号为：".$order_no,$UserID);
                    } catch (Exception $e) {
                        
                    }

                    echo $pintuan_arr;
                    exit;
                }else{
                    back_trans();
                    $pintuan_arr= json_encode(array('code'=>'1002'), JSON_UNESCAPED_UNICODE);
                    echo $pintuan_arr;
                    exit;
                }

            }
        }else{//单购
            begin_trans();
            $address=$DB->get('user_address','*','where Address_ID="'.$addressID.'"');
            while ($res =$DB->fetch_assoc()) {
                $Data['Address_Name']=$res['Address_Name'];
                $Data['Address_Mobile']=$res['Address_Mobile'];
                $Data['Address_Province']=$res['Address_Province'];
                $Data['Address_City']=$res['Address_City'];
                $Data['Address_Area']=$res['Address_Area'];
                $Data['Address_Detailed']=$res['Address_Detailed'];
            }
            $Data['Users_ID']=$_POST['Users_ID'];
            $Data['Order_Type']='dangou';
            $Data['Order_Status']='1';
            $Data['Order_CreateTime']=time();
            $Data['Order_TotalPrice']=$shipp_TotalPrice+$pintuan_shops["goods_num"]*$pintuan_shops["goods_price"];
            $Data['Order_TotalAmount'] = $Data['Order_TotalPrice'];
            $Data["Order_Shipping"]=json_encode($Order_Shipping,JSON_UNESCAPED_UNICODE);
            $Data["Order_CartList"]=$Order_CartList;
            $Data["Order_ShippingID"]=$wuliu;
            $Data["Order_Code"]=$order_no;
            $Data["Biz_ID"]=$BizID;

            /*$userid=
             $_SESSION[$_SESSION['Users_ID'].'User_ID'];*/
            $Data['User_ID']=$UserID;
            $Flag=$DB->Add("user_order",$Data);
            $neworderid = $DB->insert_id();
            $pintuandata=array();
            $pintuandata['order_id']=$neworderid;
            $pintuandata['pintuan_id']=$_POST['pintuanID'];
            $pintuandata['Users_ID']=$_POST['Users_ID'];
            $pintuandata['User_ID']=$UserID;
            $pintuandata['addtime']=time();
            $pintuandata['pintuan_status']='1';
            $pintuandata['order_status']="1";
            $pintuandata['products_status']='0';
            $pintuandata['is_vgoods']='0';
            $flags=$DB->Add('pintuan_order',$pintuandata);
            if($Flag && $flags){
                $pintuan_arr= json_encode(array('code'=>'1001','url'=>'/api/'.$Users_ID.'/pintuan/cart/payment/'.$neworderid.'/'), JSON_UNESCAPED_UNICODE);
                removeCar();
                Stock($ProductsID,$Users_ID);
                commit_trans();
                // 此处调用微信推送接口，告知用户已下单
                try {
                    sendWXMessage($Users_ID,$neworderid,"您已成功提订单，订单号为：".$order_no,$UserID);
                } catch (Exception $e) {
                    
                }

                echo $pintuan_arr;
                exit;
            }
            else{
                back_trans();
                $pintuan_arr= json_encode(array('code'=>'1002'), JSON_UNESCAPED_UNICODE);
                echo $pintuan_arr;
                exit;
            }

        }
         
    }else {//虚拟订单
        $shipp_TotalPrice = 0;
        $Data = [];
        $mobile = $_POST['number'];
        if(!$mobile || !preg_match("/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})/", $mobile)){
            die(json_encode(array('code'=>'1002','msg'=>'手机号不正确!'), JSON_UNESCAPED_UNICODE));
        }
        $Data['Address_Mobile']=$mobile;
        /** 团购或者单购判断开始 ***/
        if($pintuan_shops['is_One']==1){//团购和单购的判断 1：团购 0 单购
            /** 抽奖判断开始 ***/
            if($pintuan_shops['is_Draw']==1){//抽奖和不抽奖的判断 0:抽奖1:不抽奖
                begin_trans();
                $Data['Users_ID']=$_POST['Users_ID'];
                $Data['Order_Type']='pintuan';
                
                $Data['Order_Status']='1';
                $Data['Order_CreateTime']=time();
                $Data['Order_TotalPrice']=$shipp_TotalPrice+$pintuan_shops["goods_num"]*$pintuan_shops["goods_price"];
                $Data['Order_TotalAmount'] = $Data['Order_TotalPrice'];
                $Data["Order_Shipping"]=json_encode($Order_Shipping,JSON_UNESCAPED_UNICODE);
                $Data["Order_CartList"]=$Order_CartList;
                $Data["Order_ShippingID"]=$wuliu;
                $Data["Order_Code"]=$order_no;
                $Data["Biz_ID"]=$BizID;
                /*$userid=
                 $_SESSION[$_SESSION['Users_ID'].'User_ID'];*/
                $Data['User_ID']=$UserID;
                $Flag=$DB->Add("user_order",$Data);
                $order_id = $neworderid = $DB->insert_id();
                $pintuandata=array();
                $pintuandata['order_id']=$neworderid;
                $pintuandata['pintuan_id']=$_POST['pintuanID'];
                $pintuandata['Users_ID']=$_POST['Users_ID'];
                $pintuandata['User_ID']=$UserID;
                $pintuandata['addtime']=time();
                $pintuandata['pintuan_status']='1';
                $pintuandata['order_status']="1";
                $pintuandata['products_status']='1';
                $pintuandata['is_vgoods']='1';

                $flags=$DB->Add('pintuan_order',$pintuandata);
                if($Flag && $flags){
                    
                    $pintuan_arr= json_encode(array('code'=>'1001','url'=>'/api/'.$Users_ID.'/pintuan/cart/payment/'.$order_id.'/'), JSON_UNESCAPED_UNICODE);
                    removeCar();
                    Stock($ProductsID,$Users_ID);
                    commit_trans();
                    // 此处调用微信推送接口，告知用户已下单
                    try {
                        sendWXMessage($Users_ID,$neworderid,"您已成功提交拼团订单，订单号为：".$order_no,$UserID);
                    } catch (Exception $e) {
                        
                    }

                    echo $pintuan_arr;
                    exit;
                }else{
                    back_trans();
                    $pintuan_arr= json_encode(array('code'=>'1002'), JSON_UNESCAPED_UNICODE);
                    echo $pintuan_arr;
                    exit;
                }
            }else{
                begin_trans();;
                $Data['Users_ID']=$_POST['Users_ID'];
                $Data['Order_Type']='pintuan';
                $Data['Order_Status']='1';
                $Data['Order_CreateTime']=time();
                $Data['Order_TotalPrice']=$shipp_TotalPrice+$pintuan_shops["goods_num"]*$pintuan_shops["goods_price"];
                $Data['Order_TotalAmount'] = $Data['Order_TotalPrice'];
                $Data["Order_Shipping"]=json_encode($Order_Shipping,JSON_UNESCAPED_UNICODE);
                $Data["Order_CartList"]=$Order_CartList;
                $Data["Order_ShippingID"]=$wuliu;
                $Data['User_ID']=$UserID;
                $Data["Order_Code"]=$order_no;
                $Data["Biz_ID"]=$BizID;
                $Flag=$DB->Add("user_order",$Data);
                $order_id = $neworderid = $DB->insert_id();
                $pintuandata=array();
                $pintuandata['order_id']=$neworderid;
                $pintuandata['pintuan_id']=$_POST['pintuanID'];
                $pintuandata['Users_ID']=$_POST['Users_ID'];
                $pintuandata['User_ID']=$UserID;
                $pintuandata['addtime']=time();
                $pintuandata['pintuan_status']='1';
                $pintuandata['order_status']="1";
                $pintuandata['products_status']='2';
                $pintuandata['is_vgoods']='1';

                $flags=$DB->Add('pintuan_order',$pintuandata);
                if($Flag && $flags){
                    
                    $pintuan_arr= json_encode(array('code'=>'1001','url'=>'/api/'.$Users_ID.'/pintuan/cart/payment/'.$order_id.'/'), JSON_UNESCAPED_UNICODE);
                    removeCar();
                    Stock($ProductsID,$Users_ID);
                    commit_trans();
                    // 此处调用微信推送接口，告知用户已下单
                    try {
                        sendWXMessage($Users_ID,$order_id ,"您已成功提交拼团订单，订单号为：".$order_no,$UserID);
                    } catch (Exception $e) {
                        
                    }

                    echo $pintuan_arr;
                    exit;
                }
                else{
                    back_trans();
                    $pintuan_arr= json_encode(array('code'=>'1002'), JSON_UNESCAPED_UNICODE);
                    echo $pintuan_arr;
                    exit;
                }

            }
            /** 抽奖判断结束 ***/
        }else{//单购
            begin_trans();
            $Data['Users_ID']=$_POST['Users_ID'];
            $Data['Order_Type']='dangou';
            $Data['Order_Status']='1';
            $Data['Order_CreateTime']=time();
            $Data['Order_TotalPrice']=$shipp_TotalPrice+$pintuan_shops["goods_num"]*$pintuan_shops["goods_price"];
            $Data['Order_TotalAmount'] = $Data['Order_TotalPrice'];
            $Data["Order_Shipping"]=json_encode($Order_Shipping,JSON_UNESCAPED_UNICODE);
            $Data["Order_CartList"]=$Order_CartList;
            $Data["Order_ShippingID"]=$wuliu;
            $Data["Order_Code"]=$order_no;
            $Data["Biz_ID"]=$BizID;
            //$userid="491";
            //$_SESSION[$_SESSION['Users_ID'].'User_ID'];
            $Data['User_ID']=$UserID;
            $Flag=$DB->Add("user_order",$Data);
            $neworderid = $DB->insert_id();
            $pintuandata=array();
            $pintuandata['order_id']=$neworderid;
            $pintuandata['pintuan_id']=$_POST['pintuanID'];
            $pintuandata['Users_ID']=$_POST['Users_ID'];
            $pintuandata['User_ID']=$UserID;
            $pintuandata['addtime']=time();
            $pintuandata['pintuan_status']='1';
            $pintuandata['order_status']="1";
            $pintuandata['products_status']='0';
            $pintuandata['is_vgoods']='1';
            $flags=$DB->Add('pintuan_order',$pintuandata);
            if($Flag && $flags){
                
                removeCar();
                Stock($ProductsID,$Users_ID);
                commit_trans();
                // 此处调用微信推送接口，告知用户已下单
                try {
                    sendWXMessage($Users_ID,$neworderid ,"您已成功提交订单，订单号为：".$order_no,$UserID);
                } catch (Exception $e) {
                    
                }

                $pintuan_arr= json_encode(array('code'=>'1001','url'=>'/api/'.$Users_ID.'/pintuan/cart/payment/'.$neworderid.'/'), JSON_UNESCAPED_UNICODE);
                echo $pintuan_arr;
                exit;
            }else{
                back_trans();
                $pintuan_arr= json_encode(array('code'=>'1002'), JSON_UNESCAPED_UNICODE);
                echo $pintuan_arr;
                exit;
            }
        }
        /** 团购或者单购判断结束 ***/
    }


?>