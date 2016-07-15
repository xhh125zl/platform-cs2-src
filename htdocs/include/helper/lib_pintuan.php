<?php
function doUse($UsersID, $Products_Relation_ID)
{
    global $DB;
    $res = $DB->Get("pintuan_virtual_card","*","WHERE Users_ID='{$UsersID}' AND Products_Relation_ID='{$Products_Relation_ID}' AND Card_Status=0");
    if($res){
        $vcard = $DB->toArray($res);
        $cardName = "";
        foreach ($vcard as $k => $v)
        {
            $cardName = $v['Card_Name'];
            $DB->Set("pintuan_virtual_card", [ 'Card_Status' => 1 ], "WHERE Users_ID='{$UsersID}' AND Card_Id = '{$v['Card_Id']}'");
            break;
        }
        return $cardName;
    }
}


function sendAlert($msg,$url,$timeout=3)
{
    echo '<!doctype html><html><head><title>'.$msg.'</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">';
    echo '<script type="text/javascript" src="/static/api/pintuan/js/jquery.min.js"></script><script type="text/javascript" src="/static/api/pintuan/js/layer/1.9.3/layer.js"></script></head><body>';
    echo '<script>layer.msg("'.$msg.'",{icon:1,time:'.($timeout*1000).'},function(){ window.location="'.$url.'";});</script>';
    echo "</body></html>";
}

function  sendWXMessage($UsersID,$orderid,$msg='',$userid=''){
    global $DB;
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/library/weixin_message.class.php');
    $weixin_message = new weixin_message($DB,$UsersID,!empty($userid)?$userid:$_SESSION[$UsersID."User_ID"]);
    $contentStr = $msg;
    $weixin_message->sendscorenotice($contentStr);
}

function addSales($goodsid,$sellerid,$char='+')
{
    global $DB;
    $flag = $DB->Set("pintuan_products", "Products_Sales=Products_Sales{$char}1","where Products_ID='{$goodsid}' and Users_ID='{$sellerid}'");
    if($flag){
        return true;
    }else{
        return false;
    }
}

function payDone($UsersID,$OrderID,$paymethod,$tid = '')
{
    global $DB;
    $orderids = '';
    $orderids = $OrderID;
    $sql = "SELECT u.Users_ID AS Users_ID,u.Order_Status AS Order_Status,u.Order_CartList AS Order_CartList,u.Order_TotalPrice AS Order_TotalPrice,u.Order_Type AS Order_Type,u.Order_IsRecieve AS Order_IsRecieve,u.Order_Code AS Order_Code,u.Address_Mobile AS Address_Mobile,p.pintuan_status AS pintuan_status,p.products_status AS products_status,p.is_vgoods AS is_vgoods,p.order_status AS porder_status FROM user_order AS u LEFT JOIN pintuan_order p ON u.Order_ID=p.order_id WHERE u.Users_ID='{$UsersID}' AND u.Order_ID='{$OrderID}'";
    $orderFlag = $DB->query($sql);
    $rsOrder=$DB->fetch_assoc($orderFlag);
    $isajax = false;
    if($isajax){
        if(!$orderFlag) die(json_encode(["status"=>0,"msg"=>'订单不存在'],JSON_UNESCAPED_UNICODE));
    }else{
        if(!$rsOrder){
            $Data=array(
                "status"=>0,
                "msg"=>'该订单不存在',
                'url'=>'/api/'.$UsersID.'/pintuan/orderlist/0/'
            );
            return $Data;
        }
    }


    $order_status = $rsOrder["Order_Status"];
    $order_total = $rsOrder["Order_TotalPrice"];
    $goodsInfo = json_decode($rsOrder['Order_CartList'],true);

    $goods_id = $goodsInfo['Products_ID'];
    if($goods_id){
        $info=$DB->GetRs("pintuan_products","*","where Users_ID='{$UsersID}' and Products_ID='{$goods_id}'");
        if($info){
            $time = time();
            $y = date("Y",$time);
            $m = date("m",$time);
            $d = date("d",$time);
            $times = "{$y}-{$m}-{$d}";
            $time = strtotime($times);
            if($info['starttime']>$time){
                $Data=array(
                    "status"=>0,
                    "msg"=>'活动未开始',
                    'url'=>'/api/'.$UsersID.'/pintuan/'
                );
                return $Data;
            }
            if($info['stoptime']<$time){
                $Data=array(
                    "status"=>0,
                    "msg"=>'活动已结束',
                    'url'=>'/api/'.$UsersID.'/pintuan/'
                );
                return $Data;
            }
        }
    }


    $rsUser = $DB->GetRs("user","User_Money,User_PayPassword,Is_Distribute,User_Name,User_NickName,Owner_Id,User_Integral,User_Cost","WHERE Users_ID='".$UsersID."' AND User_ID=".$_SESSION[$UsersID.'User_ID']);
    if($isajax){
        if(!$rsUser) die(json_encode(["status"=>0,"msg"=>'用户信息不存在'],JSON_UNESCAPED_UNICODE));
    }else{
        if(!$rsOrder){
            $Data=array(
                "status"=>0,
                "msg"=>'用户信息不存在',
                'url'=>'/api/'.$UsersID.'/pintuan/orderlist/0/'
            );
            return $Data;
        }
    }

    $PaymentMethod = array(
        "微支付" => "1",
        "支付宝" => "2",
        "银联支付" => "3",
        "易宝支付" => "4",
        "银联支付" => "5"
    );
    $payData = [];
    $sendsms = 0;
    if($PaymentMethod[$paymethod]==1 || $PaymentMethod[$paymethod]==2){//余额支付
        //增加资金流水

        if($order_status != 1){
            $Data=array(
                "status"=>0,
                "msg"=>'该订单状态不是待付款状态，不能付款',
                'url'=>'/api/'.$UsersID.'/pintuan/orderlist/0/'
            );
            if($isajax){
                die(json_encode($Data,JSON_UNESCAPED_UNICODE));
            }else{
                return $Data;
            }
        }else{
            $results = $goodsInfo;

            if($results['order_process']==0){
                $payData['order']['Order_Status']=2;
                $payData['pintuanorder']['order_status']=2;
                $payData['pintuanorder']['pintuan_status']=0;
            }elseif($results['order_process']==1){
                $payData['order']['Order_Status']=2;
                $payData['pintuanorder']['order_status']=2;
                //发送短信到用户手机中
                $sendsms = 1;
            }else{
                $payData['order']['Order_Status']=4;
                $payData['pintuanorder']['order_status']=4;
            }
            $Card_Name = "";
            //拼团开启
            if($rsOrder["Order_Type"]=='pintuan'){
                $tdata = [];
                $Data = [];
                mysql_query("SET AUTOCOMMIT=0");
                mysql_query("BEGIN");
                $Data = array(
                    'Users_ID' => $UsersID,
                    'User_ID' => $_SESSION [$UsersID . 'User_ID'],
                    'Type' => 0,
                    'Amount' => $order_total,
                    'Total' => $rsUser ['User_Money'] - $order_total,
                    'Note' => "拼团购买支出 -" . $order_total . " (订单号:" . $orderids . ")",
                    'CreateTime' => time ()
                );
                $Flag = $DB->Add('user_money_record', $Data);

                $FlagCost = $DB->Set('user',[ 'User_Cost' =>$rsUser['User_Cost'] + $order_total ],"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
                if(!$FlagCost){
                    $transflag = false;
                    mysql_query("ROLLBACK");
                }
                if(!$Flag){
                    $transflag = false;
                    mysql_query("ROLLBACK");
                }
                $teamFlag = addTeam($orderids,$UsersID);
                if($teamFlag ===-1){
                    $payData['pintuanorder']['order_status']=2;
                    $payData['pintuanorder']['pintuan_status']=0;
                    //拼团成功
                    $tdata = array(
                        "status"=>1,
                        "msg"=>'拼团成功',
                        'url'=>'/api/'.$UsersID.'/pintuan/orderlist/0/'
                    );
                    $Card_Name = doUse($UsersID,$goods_id);
                }else if($teamFlag===true){
                    $payData['pintuanorder']['order_status']=2;
                    $payData['pintuanorder']['pintuan_status']=2;
                    //支付成功
                    $tdata = [
                        'status'=>1,
                        'msg'=>'支付成功',
                        'url'=>'/api/'.$UsersID.'/pintuan/orderlist/0/'
                    ];
                    $Card_Name = doUse($Users_ID,$goods_id);
                }else{
                    $payData['pintuanorder']['order_status']=2;
                    //拼团发生异常
                    $tdata = [
                        'status'=>1,
                        'msg'=>'您已经拼过团了！',
                        'url'=>'/api/'.$UsersID.'/pintuan/'
                    ];
                    $transflag = false;
                    mysql_query("ROLLBACK");
                }

                $Data = array(
                    "Order_PaymentMethod"=>$paymethod,
                    "Order_PaymentInfo"=>"余额支付",
                    "Order_DefautlPaymentMethod"=>$paymethod,
                    "transaction_id"=>$tid
                );
                $Data = array_merge($Data,$payData['order']);
                if($rsOrder["is_vgoods"]==1 && $goodsInfo['order_process'] == 1){
                    $confirm_code=virtual_randchar();
                    $Data['Order_Virtual_Cards']=$confirm_code;
                }else if($goodsInfo['order_process'] == 2){
                    $Data['Order_Virtual_Cards']=$Card_Name;
                }
                //发送短信
                if($rsOrder["is_vgoods"]==1 && $rsOrder["Order_IsRecieve"]==0 && $goodsInfo['order_process'] == 1){
                    $sms_mess = '您已成功购买商品，订单号'.$rsOrder['Order_Code'].'，消费券码为 '.$confirm_code;
                    send_sms($rsOrder["Address_Mobile"], $sms_mess, $rsOrder["Users_ID"]);
                }
                $userorderFlag = $DB->Set ('user_order', $Data, "where Users_ID='" . $UsersID . "' and User_ID='" . $_SESSION [$UsersID . "User_ID"] . "' and Order_ID in(".$orderids.")");
                $pintuanorderFlag = $pintuan_orderone=$DB->Set('pintuan_order',$payData['pintuanorder'],"where order_id ='".$orderids."' ");
                if(!$userorderFlag){
                    $transflag = false;
                    mysql_query("ROLLBACK");
                }
                if(!$pintuanorderFlag){
                    $transflag = false;
                    mysql_query("ROLLBACK");
                }
                $sflag = addSales($results["Products_ID"],$UsersID);
                if(!$sflag){
                    $transflag = false;
                    mysql_query("ROLLBACK");
                }
                mysql_query("COMMIT");
                sendWXMessage($UsersID,$orderids,'您使用微信支付已'.$tdata['msg']."，支付金额：".$order_total."，订单号为：".$rsOrder["Order_Code"]);
                if($isajax){
                    die(json_encode($tdata,JSON_UNESCAPED_UNICODE));
                }else{
                    return $tdata;
                }
            }else{      //单购支付
                $tdata = [
                    'status'=>1,
                    'msg'=>'支付成功',
                    'url'=>'/api/'.$UsersID.'/pintuan/orderlist/0/'
                ];
                $Data = array(
                    'Users_ID' => $UsersID,
                    'User_ID' => $_SESSION [$UsersID . 'User_ID'],
                    'Type' => 0,
                    'Amount' => $order_total,
                    'Total' => $rsUser ['User_Money'] - $order_total,
                    'Note' => "拼团购买支出 -" . $order_total . " (订单号:" . $orderids . ")",
                    'CreateTime' => time ()
                );
                
                mysql_query("SET AUTOCOMMIT=0");
                mysql_query("BEGIN");
                $Flag = $DB->Set('user',['User_Cost' =>$rsUser['User_Cost'] + $order_total],"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
                if(!$Flag){
                    mysql_query("ROLLBACK");
                }
                $rflag = $DB->Add('user_money_record', $Data);
                if(!$rflag){
                    mysql_query("ROLLBACK");
                }
                $Card_Name = doUse($UsersID,$goods_id);
                $Data = array(
                    "Order_PaymentMethod"=>$paymethod,
                    "Order_PaymentInfo"=>"余额支付",
                    "Order_DefautlPaymentMethod"=>$paymethod,
                    "transaction_id"=>$tid
                );
                $Data = array_merge($Data,$payData['order']);
                if($rsOrder["is_vgoods"]==1){
                    $confirm_code=virtual_randchar();
                    $Data['Order_Virtual_Cards']=$confirm_code;
                }
                if($Card_Name){
                    $Data['Order_Virtual_Cards']=$Card_Name;
                }
                if($rsOrder["is_vgoods"]==1 && $goodsInfo['order_process'] == 1){
                    $confirm_code=virtual_randchar();
                    $Data['Order_Virtual_Cards']=$confirm_code;
                }else if($goodsInfo['order_process'] == 2){
                    $Data['Order_Virtual_Cards']=$Card_Name;
                }
                if($rsOrder["is_vgoods"]==1 && $rsOrder["Order_IsRecieve"]==0 && $goodsInfo['order_process'] == 1){
                    $sms_mess = '您已成功购买商品，订单号'.$rsOrder['Order_Code'].'，消费券码为 '.$confirm_code;
                    send_sms($rsOrder["Address_Mobile"], $sms_mess, $rsOrder["Users_ID"]);
                }
                $uflag = $DB->Set('user_order', $Data, "where Order_ID='".$orderids."'");
                if(!$uflag){
                    mysql_query("ROLLBACK");
                }
                $pintuan_orderone=$DB->Set('pintuan_order',$payData['pintuanorder'],"where order_id ='".$orderids."' and Users_ID='" . $UsersID . "'");
                if(!$pintuan_orderone){
                    mysql_query("ROLLBACK");
                }
                $sflag = addSales($goodsInfo['Products_ID'],$UsersID);
                if(!$sflag){
                    mysql_query("ROLLBACK");
                }
                mysql_query("COMMIT");
                sendWXMessage($UsersID,$orderids,'您使用微信支付已'.$tdata['msg']."，支付金额：".$order_total."，订单号为：".$rsOrder["Order_Code"]);
                if($isajax){
                    die(json_encode($tdata,JSON_UNESCAPED_UNICODE));
                }else{
                    return $tdata;
                }
            }
        }
    }
}


function Stock($goodsid,$sellerid,$char='-')
{
    global $DB;
    if(!$goodsid) return false;
    $stock = $DB->GetRs("pintuan_products","Products_Count,Products_Sales","where Products_ID='{$goodsid}' and Users_ID='{$sellerid}'");
    if($stock['Products_Count']==0 || $stock['Products_Sales']==0){  //库存不足
        return false;
    }

    $flag = $DB->Set("pintuan_products", "Products_Count=Products_Count{$char}1","where Products_ID='{$goodsid}' and Users_ID='{$sellerid}'");
    if($flag){
        return true;
    }else{
        return false;
    }
}

//移除购物车
function removeCar()
{
    global $DB;
    $sessionid = session_id();
    if(isset($_SESSION[$sessionid.'_cart']) && !empty($_SESSION[$sessionid.'_cart'])){
        $cartlist = unserialize($_SESSION[$sessionid.'_cart']);
        $arr = array_map(function($value){
            if($value['id']) return $value['id'];
        }, $cartlist);
            $idlist = implode(',', $arr);
            $flag = $DB->Del("pintuan_shop"," id in ({$idlist})");
            if($flag){
                $_SESSION[$sessionid.'_cart'] = "";
            }
    }
}

//生成订单编号
function order_no($no=''){
    global $DB;
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);
    $no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $flag = $DB->GetRs("user_order","Order_Code,Order_ID","where Order_Code='{$no}' and Order_Code is not NULL");
    if($flag){
        return order_no($no);
    }else{
        return $no;
    }
}


//物流费用计算
function getShipFee($shipid,$sellorid){
    global $DB;
    if($shipid){
        $shipping_t= $DB->GetRs("shop_shipping_template", "*", "where Users_ID='{$sellorid}' and Shipping_ID='{$shipid}'");
        if($shipping_t){
            if(!$shipping_t['Template_Status']) return 0;
            if(empty($shipping_t['Template_Content'])) return 0;
            $t_content = json_decode($shipping_t['Template_Content']);
            $default_value=$t_content->express->default;
            return $default_value->postage;
        }else{
            return 0;
        }
    }
    return 0;
}

//获取商品信息
function getProducts($goods_id,$sellor_id,$spec_list="")
{
    global $DB;
    $goodsInfo=$DB->GetRs('pintuan_products','*', 'where Products_ID="'.$goods_id.'" and Users_ID="'.$sellor_id.'"');
    if($goodsInfo){
        $JSON = json_decode($goodsInfo['Products_JSON'], true);
        return [
            'products'=>$goodsInfo,
            'cartlist'=>[
                "Products_ID"               =>  $goodsInfo['Products_ID'],
                "Products_Count"            =>  $goodsInfo['Products_Count'],
                "ProductsName"              =>  $goodsInfo["Products_Name"],
                "Products_Sales"            =>  $goodsInfo["Products_Sales"],
                "ImgPath"                   =>  !empty($JSON["ImgPath"]) ? $JSON["ImgPath"][0]:"",
                "ProductsPriceD"            =>  $goodsInfo["Products_PriceD"],
                "ProductsPriceT"            =>  $goodsInfo["Products_PriceT"],
                "ProductsWeight"            =>  $goodsInfo["Products_Weight"],
                "Products_Shipping"         =>  $goodsInfo["Products_Shipping"],
                "Products_Business"         =>  $goodsInfo["Products_Business"],
                "Shipping_Free_Company"     =>  $goodsInfo["Shipping_Free_Company"],
                "IsShippingFree"            =>  $goodsInfo["Products_pinkage"], // 1 包邮，0 不包邮
                "ProductsIsShipping"        =>  $goodsInfo["Products_pinkage"],
                "spec_list"                 =>  $spec_list,
                "nobi_ratio"                =>  $goodsInfo["nobi_ratio"],
                "platForm_Income_Reward"    =>  $goodsInfo["platForm_Income_Reward"],
                "area_Proxy_Reward"         =>  $goodsInfo["area_Proxy_Reward"],
                "sha_Reward"                =>  $goodsInfo["sha_Reward"],
                "Products_Profit"           =>  $goodsInfo["Products_Profit"],
                "Is_Draw"                   =>  $goodsInfo["Is_Draw"],
                "order_process"             =>  $goodsInfo["order_process"],
                "pintuan_type"              =>  $goodsInfo["pintuan_type"],
                "pintuan_id"                =>  $goodsInfo["pintuan_id"],
                "people_once"               =>  $goodsInfo["people_once"],
                "people_num"                =>  $goodsInfo["people_num"],
                "num"                       =>  1,
                "Biz_ID"                    =>  $goodsInfo["Biz_ID"],
                "starttime"                 =>  $goodsInfo["starttime"],
                "stoptime"                  =>  $goodsInfo["stoptime"],
                "Products_PriceSd"          =>  $goodsInfo["Products_PriceSd"],
                "Products_PriceSt"          =>  $goodsInfo["Products_PriceSt"],
                "Products_Weight"           =>  $goodsInfo["Products_Weight"],
                "Biz_ID"                    =>  $goodsInfo["Biz_ID"],
                "Products_FinanceRate"      =>  $goodsInfo["Products_FinanceRate"],
                "Products_FinanceType"      =>  $goodsInfo["Products_FinanceType"]
            ]
        ];
    }else{
        return [];
    }
}

