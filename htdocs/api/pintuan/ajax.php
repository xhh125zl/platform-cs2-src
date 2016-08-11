<?php
/*edit in 20160318*/
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
require_once($_SERVER["DOCUMENT_ROOT"].'/api/pintuan/comm/function.php');

if(isset($_POST['usersid'])){ 
	$UsersID=$_POST['usersid'];
	$UserID=$_SESSION[$UsersID."User_ID"];
}
$action = empty($_REQUEST["action"]) ? "" : $_REQUEST["action"];
//地址的写入  删除 修改  等
if($action=='address_edit_save'){
		$Flag=true;
		$AddressID=empty($_POST['AddressID'])?0:$_POST['AddressID'];
		$Data=array(
			"Address_Name"=>$_POST['Name'],
			"Address_Mobile"=>$_POST["Mobile"],
			"Address_Province"=>empty($_POST['Province'])?"":$_POST['Province'],
			"Address_City"=>empty($_POST['City'])?"":$_POST['City'],
			"Address_Area"=>empty($_POST['Area'])?"":$_POST['Area'],
			"Address_Detailed"=>$_POST["Detailed"],
			"Address_Is_Default"=>!empty($_POST["default"])?$_POST["default"]:0,
		);
		
		if(empty($_POST['AddressID'])){
			if($Data['Address_Is_Default'] == 1){
				$condition = "where Users_ID='".$UsersID."' and User_ID='".$UserID."'";
				$DB->set("user_address",array('Address_Is_Default'=>0),$condition);
			}
			//增加
			$Data["Users_ID"]=$UsersID;
			$Data["User_ID"]=$_SESSION[$UsersID."User_ID"];
			$Flag=$Flag&&$DB->Add("user_address",$Data);
		}else{
			if($Data['Address_Is_Default'] == 1){
				$condition = "where Users_ID='".$UsersID."' and User_ID='".$UserID."' and Address_ID != '".$AddressID."'";
				$DB->set("user_address",array('Address_Is_Default'=>0),$condition);
			}
			//修改
			$Flag=$Flag&&$DB->Set("user_address",$Data,"where Users_ID='".$UsersID."' and User_ID='".$UserID."' and Address_ID='".$AddressID."'");
		}
		if($Flag){
			$url = '/api/'.$UsersID.'/pintuan/my/address/';
			if(!empty($_SESSION[$UsersID."From_Checkout"])){
				if($_SESSION[$UsersID."From_Checkout"]==1){
					$url = $_SESSION[$UsersID."HTTP_REFERER"];
				}else{
					$url = $_SESSION[$UsersID."From_Checkout"];
				}				
				unset($_SESSION[$UsersID."From_Checkout"]);
			}
			if(!empty($_SESSION[$UsersID."Select_Model"])&&!empty($_POST['AddressID'])){
				$url = '/api/'.$UsersID.'/pintuan/my/address/'.$_POST['AddressID'].'/';
				unset($_SESSION[$UsersID."Select_Model"]);
			}
			$Data=array(
				'status'=>1,
				'msg'=>'操作成功',
				'url'=>$url
			);
		}else{
			$Data=array(
				'status'=>0,
				'msg'=>'网络拥堵，请稍后再试！'
			);
		}
		echo json_encode(empty($Data)?array('status'=>0,'msg'=>'请勿非法操作！'):$Data,JSON_UNESCAPED_UNICODE);		
	}
//收藏表ajax 插入
if(isset($_POST['orther']) && $_POST['orther']=='10001'){
	//对收藏的商品再次点击删除收藏
	$DB->query("SELECT * FROM `pintuan_collet` where users_id='".$_POST['UsersID']."'and userid ='".$_POST['userid']."'");
	$ress=$DB->toArray();
	$aa=false;
	foreach ($ress as $key => $value){
		if($value['productid']==$_POST['goodsid']){
					$aa=true;
		}
	}
	if($aa){
	$t=$DB->Del('pintuan_collet','productid='.$_POST['goodsid'].' and userid='.$_POST['userid'].' and users_id="'.$_POST['UsersID'].'"');
	$arr=json_encode(array('msg'=>'0','id'=>'0'), JSON_UNESCAPED_UNICODE);
			echo $arr;
	}else{
	$Data=array(
	'productid'=>$_POST['goodsid'],
	'userid'=>$_POST['userid'],
	'addtime'=>time(),
	'users_id'=>$_POST['UsersID']
	);
	$DB->Add('pintuan_collet',$Data);
	$id=mysql_insert_id(); 
	$arr=json_encode(array('id'=>$id,'msg'=>'1'), JSON_UNESCAPED_UNICODE);
	echo $arr;	
	}
}	
//收藏列表页面删除
if(isset($_POST['orther']) && $_POST['orther']=='10002'){
	$goodsid=$_POST['goodsid'];
	$userid=$_POST['userid'];
	$usersid=$_POST['UsersID'];
	$t=$DB->Del('pintuan_collet','productid='.$goodsid.' and userid='.$userid.' and users_id="'.$usersid.'"');
	$d=json_encode(array('t'=>$t,'msg'=>'取消收藏成功'), JSON_UNESCAPED_UNICODE);
	echo $d;
}
//对单购按钮的判断
if(isset($_POST['orther']) && $_POST['orther']=='10003'){
	$productsid=$_POST['goodsid'];
	$Users_ID=$_POST['UsersID'];
	$DB->get('pintuan_products','*',"where Products_ID = '".$productsid."' and Users_ID='".$Users_ID."'");
	$res=$DB->fetch_assoc();
	//is_buy  0是不支持单购
	if($res['is_buy']=='0'){
		$a=json_encode(array('t'=>'10000','msg'=>'不支持单购'), JSON_UNESCAPED_UNICODE);
		echo $a;
	}else{
		$a=json_encode(array('t'=>'10001','msg'=>'支持单购'), JSON_UNESCAPED_UNICODE);
		echo $a;
	}
}
//对团购按钮的限制
if(isset($_POST['orther']) && $_POST['orther']=='10004'){
	$productsid=$_POST['goodsid'];
	$Users_ID=$_POST['UsersID'];
	$DB->get('pintuan_products','*',"where Products_ID = '".$productsid."' and Users_ID='".$Users_ID."'");
	$res=$DB->fetch_assoc();
	//拼团结束时间
	$pintuan_stop=date('Y-m-d',$res['stoptime']);
	$pintuan_start=date('Y-m-d',$res['starttime']);
	$now=date('Y-m-d',time());
	//如果是拼团并且超过指定的时间
	if($pintuan_stop<$now){
   			$a=json_encode(array('t'=>'40001','msg'=>'时间过期 团购失效'), JSON_UNESCAPED_UNICODE);
   			echo $a;
	}
	if($pintuan_start>$now){
   			$a=json_encode(array('t'=>'40002','msg'=>'未开始 团购失效'), JSON_UNESCAPED_UNICODE);
   			echo $a;
	}
}
//加入购物车
if($action === 'addcart'){
    if(isset($_POST['goodsid']) && isset($_POST['UsersID'])){
        $goodsid=$_POST['goodsid'];
        $UsersID=$_POST['UsersID'];
        $userid=$_POST['userid'];
        $num=$_POST['num'];

        //参团的team_ID
        $teamid=isset($_POST['teamid'])?$_POST['teamid']:1;
        //是否参团进行判断
        if($teamid){
            $flagTeam = $DB->GetRs("pintuan_teamdetail","*","where teamid='{$teamid}' and userid='{$userid}'");
            if($flagTeam){
                die(json_encode(array('id'=>0,'v'=>0,'status'=>-1,'msg'=>'您已经参加过本团了'), JSON_UNESCAPED_UNICODE));
            }
        }
        
        $goodsInfo = $DB->GetRs("pintuan_products","*","where Users_ID='{$UsersID}' and Products_ID='{$goodsid}'");
        //产品库存判断
        if($goodsInfo['Products_Count']==0){
            die(json_encode(array('id'=>0,'v'=>0,'status'=>0,'msg'=>'商品库存不足'), JSON_UNESCAPED_UNICODE));
        }
        if($goodsInfo['order_process'] == 1||$goodsInfo['order_process'] ==2) {
            $isvirual=1;
        }else{
            $isvirual=0;
        }

        $sessionid = session_id();
        if(isset($_SESSION[$sessionid.'_cart']) && !empty($_SESSION[$sessionid.'_cart'])){
            $cartlist = unserialize($_SESSION[$sessionid.'_cart']);
            $arr = array_map(function($value){
                if($value['id']) return $value['id'];
            }, $cartlist);
            die(json_encode(array('id'=>$arr[0],'v'=>$isvirual,'status'=>1,'msg'=>''), JSON_UNESCAPED_UNICODE));
        }
        if($goodsInfo){
            if(isset($_POST['one']) && $_POST['one']==1){
                $price = $goodsInfo['Products_PriceT'];
                $time = time();
                $y = date("Y",$time);
                $m = date("m",$time);
                $d = date("d",$time);
                $times = "{$y}-{$m}-{$d}";
                $time = strtotime($times);
                if($goodsInfo['starttime']>$time){
                    die(json_encode(array('id'=>0,'v'=>0,'status'=>0,'msg'=>'活动未开始'), JSON_UNESCAPED_UNICODE));
                }
                if($goodsInfo['stoptime']<$time){
                    die(json_encode(array('id'=>0,'v'=>0,'status'=>0,'msg'=>'活动已结束'), JSON_UNESCAPED_UNICODE));
                }
                
            }else{
                $price = $goodsInfo['Products_PriceD'];
            }
            $Draw=$_POST['Draw'];
            $Data=array(
                'goodsid'=>$goodsid,
                'is_vgoods'=>$goodsInfo['order_process'],
                'usersid'=>$UsersID,
                'userid'=>$userid,
                'goods_price'=>$price,
                'goods_name'=>$goodsInfo['Products_Name'],
                'goods_num'=>$num,
                'is_Draw'=>$Draw,
                'is_One'=>$_POST['one']
            );
            

            $DB->Add('pintuan_shop',$Data);
            $id=mysql_insert_id();
            $sessionid = session_id();
            $_SESSION[$sessionid.'_TeamID']=$teamid;
            if(isset($_SESSION[$sessionid.'_cart']) && !empty($_SESSION[$sessionid.'_cart'])){
                $cart = unserialize($_SESSION[$sessionid.'_cart']);
                array_push($cart, ['id'=>$id]);
                $_SESSION[$sessionid.'_cart'] = serialize($cart);
            }else{
                $_SESSION[$sessionid.'_cart'] = serialize([['id'=>$id]]);
            }
            die(json_encode(array('id'=>$id,'v'=>$isvirual,'status'=>1,'msg'=>''), JSON_UNESCAPED_UNICODE));
        }
    }
}
//已收货按钮的控制
if(isset($_POST['orther']) && $_POST['orther']=='confirm'){
    $orderid=$_POST['orderid'];
    $usersid=$_POST['usersid'];
    $rsOrder = $DB->GetRs("user_order","Order_Status,Users_ID","where Order_ID=".$orderid);
    if(!$rsOrder){
		$response = array(
			"status"=>0,
			"msg"=>'该订单不存在'
		);
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if($rsOrder["Order_Status"]<>3){
		$response = array(
			"status"=>0,
			"msg"=>'只有在‘已发货’状态下才可确认收货'
		);
		echo json_encode($response,JSON_UNESCAPED_UNICODE);
		exit;
	}
    $Data = array(
         'Order_Status'=>'4'
    );
    $FF= $DB->Set('user_order',$Data,"where Users_ID='".$usersid."' and order_id=".$orderid);
    $FT= $DB->Set('pintuan_order',$Data,"where Users_ID='".$usersid."' and order_id=".$orderid);
    
    require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
    $balance_sales= new balance($DB1,$rsOrder['Users_ID']);
    $balance_sales->add_sales($orderid);
    
    if ($FF&&$FT){
        $response = array(
            "status"=>1,
            "msg"=>'收货成功'
        );
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
        exit;
    }else{
        $response = array(
            "status"=>0,
            "msg"=>'确认收货失败,请联系商城管理员'
        );
        echo json_encode($response,JSON_UNESCAPED_UNICODE);
        exit;
    }
}
//余额支付的
if($action=="payment"){
    if(isset($_POST["UsersID"]) && isset($_POST['OrderID'])){
        $UsersID=$_POST["UsersID"];
        $OrderID=$_POST['OrderID'];
    }else{
        $Data=array(
                "status"=>0,
                "msg"=>'不正确的参数传递'
         );
        die(json_encode($Data));
    }
    $orderids = '';
    $orderids = $OrderID;

    $sql = "SELECT u.Users_ID AS Users_ID,u.User_ID,u.Order_Status AS Order_Status,u.Order_CartList AS Order_CartList,u.Order_TotalPrice AS Order_TotalPrice,u.Order_Type AS Order_Type,u.Order_IsRecieve AS Order_IsRecieve,u.Order_Code AS Order_Code,u.Address_Mobile AS Address_Mobile,p.pintuan_status AS pintuan_status,p.products_status AS products_status,p.is_vgoods AS is_vgoods,p.order_status AS porder_status FROM user_order AS u LEFT JOIN pintuan_order p ON u.Order_ID=p.order_id WHERE u.Users_ID='{$UsersID}' AND u.Order_ID='{$OrderID}'";
    $orderFlag = $DB->query($sql);
    if(!$orderFlag) die(json_encode(["status"=>0,"msg"=>'订单不存在']));
    $rsOrder=$DB->fetch_assoc($orderFlag);
    
    $order_status = $rsOrder["Order_Status"];
    $order_total = $rsOrder["Order_TotalPrice"];
    $goodsInfo = json_decode($rsOrder['Order_CartList'],true);
    $goods_id = $goodsInfo['Products_ID'];
    if($goods_id){
        $info=$DB->GetRs("pintuan_products","*","WHERE Users_ID='{$UsersID}' AND Products_ID='{$goods_id}'");
        if($info){
                $time = time();
                $y = date("Y",$time);
                $m = date("m",$time);
                $d = date("d",$time);
                $times = "{$y}-{$m}-{$d}";
                $time = strtotime($times);
                if($info['starttime']>$time){
                
                    die(json_encode(array('status'=>1,'msg'=>'活动未开始','url'=>'/api/'.$UsersID.'/pintuan/'), JSON_UNESCAPED_UNICODE));
                }
                if($info['stoptime']<$time){
                    die(json_encode(array('status'=>1,'msg'=>'活动已结束','url'=>'/api/'.$UsersID.'/pintuan/'), JSON_UNESCAPED_UNICODE));
                }
        }
    }
    $rsUser = $DB->GetRs("user","User_Money,User_PayPassword,Is_Distribute,User_Name,User_NickName,Owner_Id,User_Integral,User_Cost","WHERE Users_ID='".$UsersID."' AND User_ID=".$_SESSION[$UsersID.'User_ID']);
    if(!$rsUser) die(json_encode(["status"=>0,"msg"=>'用户信息不存在']));
    $PaymentMethod = array(
        "微支付" => "1",
        "支付宝" => "2",
        "银联支付" => "3",
        "易宝支付" => "4",
        "银联支付" => "5"
    );
    $payData = [];
    $sendsms = 0;
    
    if($_POST['PaymentMethod'] && $rsUser["User_Money"] >= $order_total){//余额支付
        //增加资金流水
        
        if($order_status != 1){
            $Data=array(
                "status"=>0,
                "msg"=>'该订单状态不是待付款状态，不能付款'
            );
            die(json_encode($Data));
        }elseif(!$_POST["PayPassword"]){
            $Data = array(
                "status"=>0,
                "msg"=>'请输入支付密码'
            );
            die(json_encode($Data));
        }elseif(md5($_POST["PayPassword"]) != $rsUser["User_PayPassword"]){
            $Data = array(
                "status"=>0,
                "msg"=>'支付密码输入错误'
            );
            die(json_encode($Data));
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
                $transflag = true;
                //更新用户余额
                $Data = array(
                    'User_Money'=>$rsUser['User_Money'] - $order_total,
                    'User_Cost' =>$rsUser['User_Cost'] + $order_total
                );
                //开启事务
                mysql_query("SET AUTOCOMMIT=0");
                mysql_query("BEGIN");
                $Flag = $DB->Set('user',$Data,"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
                if(!$Flag){
                    $transflag = false;
                    mysql_query("ROLLBACK");
                }
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
                    "Order_PaymentMethod"=>$_POST['PaymentMethod'],
                    "Order_PaymentInfo"=>"余额支付",
                    "Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"]
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
                
                if($goodsInfo['order_process']==2){
                  require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
                  $balance_sales= new balance($DB,$UsersID);
                  $balance_sales->add_sales($orderids);
                }
                
	sendWXMessage( $UsersID,$orderids,$tdata['msg']."，支付金额：".$order_total."，订单号为：".$rsOrder["Order_Code"],$rsOrder['User_ID']);
                die(json_encode($tdata,JSON_UNESCAPED_UNICODE));
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
                $Flag = $DB->Set('user',['User_Money'=>$rsUser['User_Money'] - $order_total,'User_Cost' =>$rsUser['User_Cost'] + $order_total],"where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID.'User_ID']);
                if(!$Flag){
                    mysql_query("ROLLBACK");
                }
                $rflag = $DB->Add('user_money_record', $Data);
                if(!$rflag){
                    mysql_query("ROLLBACK");
                }
                $Card_Name = doUse($UsersID,$goods_id);
                $Data = array(
                    "Order_PaymentMethod"=>$_POST['PaymentMethod'],
                    "Order_PaymentInfo"=>"余额支付",
                    "Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"]
                );
                $Data = array_merge($Data,$payData['order']);
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
                $sflag = addSales($results["Products_ID"],$UsersID);
                if(!$sflag){
                    mysql_query("ROLLBACK");
                }
                mysql_query("COMMIT");
                if($goodsInfo['order_process']==2){
                  require_once($_SERVER["DOCUMENT_ROOT"].'/include/helper/balance.class.php');
                  $balance_sales= new balance($DB,$UsersID);
                  $balance_sales->add_sales($orderids);
                }
	sendWXMessage( $UsersID,$orderids,$tdata['msg']."，支付金额：".$order_total."，订单号为：".$rsOrder["Order_Code"],$rsOrder['User_ID']);
                die(json_encode($tdata,JSON_UNESCAPED_UNICODE));
            }	
        }
    }else{
        die(json_encode(["status"=>0,"msg"=>'不正确的支付方式']));
    }
    
}
//确认收货

if($action=='pttrue'){
	$order_id=$_POST["Order_ID"];
	$User_ID=$_POST["Users_ID"];
	
	mysql_query("SET AUTOCOMMIT=0");
	$flag=$DB->set("user_order",'Order_Status=4',
		'where Users_ID="'.$User_ID.'" and Order_ID="'.$order_id.'"');
	$flags=$DB->set("pintuan_order","order_status=4",
		"where order_id='".$order_id."'and Users_ID='".$User_ID."'");
	if($flag&&$flag){
      mysql_query("COMMIT");
	        $pintuan_arr= json_encode(array('code'=>'1001'), JSON_UNESCAPED_UNICODE);
	        echo $pintuan_arr;
	        exit;
	}
	else{
		mysql_query("ROLLBACK");
	        $pintuan_arr= json_encode(array('code'=>'1002'), JSON_UNESCAPED_UNICODE);
	        echo $pintuan_arr;
	        exit;
	}

	
}

//评论
if($action=="ptpinlun"){
	$Data=array();
	$Data["Product_ID"] = $_POST["products_id"];
	$Data["Order_ID"] = $_POST["Order_ID"];
    $Data["User_ID"] = $_POST["userid"];
	$Data["MID"] = "pintuan";
	$Data["CreateTime"] = time();
	$Data["Users_ID"] = $_POST["Users_ID"];
	$Data["Score"] = $_POST["fenshu"];
	$Data["Note"] = $_POST["txt"];
	$Data["Status"] = 0;
    $Data["pingfen"]=$_POST['fenshu'];
    
    $rsOrder = $DB->GetRs("user_order","Order_Status,Users_ID,Biz_ID","where Order_ID=".$Data["Order_ID"]);
    if(!empty($rsOrder) && $rsOrder['Biz_ID'])
    {
        $Data['Biz_ID'] = $rsOrder['Biz_ID'];
    }
	$Flag = $DB->Add("pintuan_commit", $Data);
	if($Flag){
		$pintuan_arr = json_encode(array('code' => '1001', 'orderid' => $Data['Order_ID']), JSON_UNESCAPED_UNICODE);
		echo $pintuan_arr;
		exit;
	}
	else {
		$pintuan_arr = json_encode(array('code' => '1002', 'orderid' => $Data['Order_ID']), JSON_UNESCAPED_UNICODE);
		echo $pintuan_arr;
		exit;
	}
}

//取消订单
if ($action =="qxdd") {
	$orderid = $_POST['orderid'];
	$productID = $_POST['productID'];
	$UsersID = $_POST['UsersID'];
	/*
	$rsOrder = $DB->GetRs("user_order","*","WHERE Users_ID='{$UsersID}' AND Order_ID='{$orderid}'");
	if($rsOrder){
	    $Data = [
	        'usersid' => $rsOrder['Users_ID'],
	        'order_id' => $orderid,
	        'userid' => $rsOrder['User_ID'],
	        'total' => $rsOrder['Order_TotalPrice'],
	        'createtime' => $rsOrder['Order_CreateTime'],
	        'status' => $rsOrder['Order_Status']
	    ];
	    $DB->Add("user_cancel_order", $Data);
	}*/
  //增加库存
	$sql1 = "delete from user_order where order_id = $orderid;";
	$sql2 = "delete from pintuan_order where order_id = $orderid;";
	$DB->query($sql1);
	$DB->query($sql2);
	Stock($productID,$UsersID,"+");
	echo json_encode(array('code' => 1001, 'orderid' => $orderid));
}


//公共方法
function get_final_price($cur_price,$attr_id,$ProductID,$UsersID){
	
	global $DB1;
	
    $_SESSION[$UsersID.'attr_id'] = $attr_id;
	
	$attr_id_str = implode(',',$attr_id);
	
	$condition = "where  Products_ID =".$ProductID;
	$condition .= " And Product_Attr_ID in (".$attr_id_str.")";
   
	$rsProductAttrs = $DB1->Get("shop_products_attr","*",$condition);
	$product_attrs = $DB1->toArray($rsProductAttrs); 
    
	if(!empty($product_attrs)){
		foreach($product_attrs as $key=>$item){
			$cur_price += $item['Attr_Price']; 
		}
	}
	
	return $cur_price;
	
}
function organize_shipping_id($shipping_id) {
	$Shipping_IDS = array ();
	foreach ( $shipping_id as $key => $item ) {
		$Shipping_IDS [$item ['Biz_ID']] = $item ['Shipping_ID'];
	}
	
	return $Shipping_IDS;
}
function build_pre_order_no(){
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/*edit in 20160324--start--*/
/*
*   订单创建前产品属性信息
*   @param int $Post_Data 订单数据
*   @echo json 属性信息
*/
function checkout_property($request){
    global $DB1;
    $Products_ID = $request ["ProductsID"];
    $response = array (
        'err_msg' => '',
        'result' => '',
        'qty' => 1 
    );

    $attr_id = isset ( $request ['attr'] ) ? $request ['attr'] : array ();
    $qty = (isset ( $request ['qty'] )) ? intval ( $request ['qty'] ) : 1;
    $no_attr_price = $request ['no_attr_price'];
    $UsersID = $request ['UsersID'];

    if ($Products_ID == 0) {
            $response ['msg'] = '无产品ID';
            $response ['status'] = 0;
    } else {
        if ($qty == 0) {
                $response ['qty'] = $qty = 1;
        } else {
                $response ['qty'] = $qty;
        }
        //print_r($request["formSeriall"]);   
        $formSeriall= urldecode($request["formSeriall"]);
        $form_Seriall = explode("&",$formSeriall);
        //print_r($form_Seriall); //print_r($form_Seriall['cattsel']);
        foreach ($form_Seriall as $kfs=>$vfs){
            $new_array[$vfs] = explode("=",$vfs);
            $form_Serialls[] = array($new_array["$vfs"][0]=>$new_array[$vfs][1]);
        } 
        foreach($form_Serialls as $kfs1=>$vfs1){
            if(array_keys($vfs1)[0] == 'cattsel'){
               $cattsels[] =$vfs1["cattsel"];
            }
        }//print_r($form_Seriall);
       $cattsel = implode('|',$cattsels); //得到属性组合

       $cattsel = "'".$cattsel."'";  
        $row= $DB1->query('SELECT Attr_Price,Property_count,Product_Attr_ID,number_id FROM shop_products_attr where Products_ID ='.$Products_ID.' and Attr_Value='.$cattsel);       
        $Property_result=mysql_fetch_row($row); // print_r($Property_result);
        $response ['result'] = $no_attr_price+$Property_result[0];  //价格
        $response ['property_count'] = $Property_result[1]; //库存
        $response ['product_property_id'] = $Property_result[2]; //属性id
        $response ['number_id'] = $Property_result[3]; //商品编号
    }

    //---------
    //print_r($cattsels);
    if(count($cattsels)>1){
        foreach($cattsels as $kcs=>$vcs){
            $properties = get_product_properties($Products_ID);  // 获得商品所有的规格和属性
            if(!empty($properties['spe'])){
                    $specification = $properties['spe'];
            }else{
                    $specification = array();
            }
            foreach ($specification as $ks => $vs){  //如果是关联属性，将属性值字符串转化数组
                    $specification[$ks]['Name'] = explode('|',$vs['Name']);
                    foreach($vs['Values'] as $ks1 => $vs1){
                        $specification[$ks]['Values'][$ks1]['label'] = explode('|',$vs1['label']);
                    }
            }
            foreach($specification as $spec_key=>$spec){
                foreach($spec['Values'] as $kk=>$vv){
                    $array_new[$kk] = $vv['label'];
                }		
            }
			
            //对比得出和传来的属性相关联的属性
            foreach($array_new as $ka=>$va){
                if(in_array($vcs,$va)){
                   $result_array[$kcs][] = $va;
                }
            }
         
        }
         foreach($result_array as $kay =>$vay){
             foreach($vay as $kay2 => $vay2){
                 $result_array1[] = $vay2;
             }
         }
         $result_array1s = unique_arr($result_array1);//去除重复
         foreach($result_array1s as $kas=>$vas){  //选中的属性对所有属性对比
            foreach($cattsels as $kc2=>$vc2){
                if(!in_array($vc2,$vas)){
					//print_r($vc2);
                    continue 2; 
                }
             }
            $result_array1ss[] = $vas;
         }
         foreach($result_array1ss as $kes=>$ves){
             foreach($ves as $kes2 => $ves2){
                 $result_arrays[] = $ves2;
             }
             
         }
       
    }else{
        $aa = $request ["aa"];//print_r($aa); //获得点击传来的属性
        $properties = get_product_properties($Products_ID);  // 获得商品所有的规格和属性
        if(!empty($properties['spe'])){
                $specification = $properties['spe'];
        }else{
                $specification = array();
        }
        foreach ($specification as $ks => $vs){  //如果是关联属性，将属性值字符串转化数组
            //if($vs['attr_type'] == 1){
            $specification[$ks]['Name'] = explode('|',$vs['Name']);
            foreach($vs['Values'] as $ks1 => $vs1){
                $specification[$ks]['Values'][$ks1]['label'] = explode('|',$vs1['label']);
            }
            //}
        }
        foreach($specification as $spec_key=>$spec){
            //if($spec['attr_type'] == 1){
            foreach($spec['Values'] as $kk=>$vv){
                $array_new[$kk] = $vv['label'];
            }		
            //}
        }
        //对比得出和传来的属性相关联的属性
        foreach($array_new as $ka=>$va){
            if(in_array($aa,$va)){
                $result_array[] = $va;
            }
        }
        foreach($result_array as $ka1=>$va1){
            foreach($va1 as $ka2=>$va2){
                $result_arrays[] = $va2;
            }
        }

    }
    $response['result_arrays'] = $result_arrays;
    //print_r($response);
    return $response;
    //return json_encode ( $response );
}

//二维数组去除重复
function unique_arr($array2D,$stkeep=false,$ndformat=true)  {  
    if($stkeep) $stArr = array_keys($array2D);  
    if($ndformat) $ndArr = array_keys(end($array2D));  
    foreach ($array2D as $v){  
        $v = join(",",$v);   
        $temp[] = $v;  
    }  
    $temp = array_unique($temp);   
    foreach ($temp as $k => $v)  
    {  
        if($stkeep) $k = $stArr[$k];  
        if($ndformat)  
        {  
            $tempArr = explode(",",$v);   
            foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;  
        }  
        else $output[$k] = explode(",",$v);   
    }  
    return $output;  
}  
/*edit in 20160324--end--*/	
?>