<?php
// 获取易极付用户可用余额信息
function userBalanceQuery($UsersID = '', $UserID = 0)
{
    global $DB;
    require_once (CMS_ROOT . '/include/api/const.php');
    require_once (CMS_ROOT . '/include/api/pay.class.php');
    $rsPayRes = pay::getlist();
    if ($rsPayRes['errorCode'] == 0) {
        $rsPay = $rsPayRes['payConfig'];
    } else {
        return [
            'status' => 0,
            'msg' => '账户支付配置信息不正确'
        ];
    }
    if(empty($UsersID) || !$UserID){
        $UsersID = isset($_SESSION['Users_ID']) && $_SESSION['Users_ID'] ? $_SESSION['Users_ID'] : '';
        $UserID = isset($_SESSION[$UsersID . 'User_ID']) && $_SESSION[$UsersID . 'User_ID'] ? $_SESSION[$UsersID . 'User_ID'] : 0;
    }
    $rsBindyijiPay = UserBindYijiPay::Multiwhere([
                        'Users_ID' => $UsersID,
                        'User_ID' => $UserID,
                        'status' => 2
                    ])->first();
    
    if ($rsBindyijiPay) {
        $data = [
            'userId' => $rsBindyijiPay->Yiji_UserID
        ];
        require_once (CMS_ROOT . '/pay/yijipay/autoload.php');
        $account = new Account();
        $result = $account->userBalanceQuery($data);
        $result = json_decode($result, true);
        if ($result['success'] == true && $result['resultCode'] == 'EXECUTE_SUCCESS') {
            return [
                'status' => 1,
                'balance' => $result['balance'],
                'availableBalance' => $result['availableBalance'],
                'freezenBalance' => $result['availableBalance']
            ];
        } else {
            return [
                'status' => 0,
                'msg' => '获取会员金额失败'
            ];
        }
    } else {
        return [
            'status' => 0,
            'msg' => '会员不存在'
        ];
    }
}

// 新规会员注册
function newRuleRegisterUser(array $param, $type = 'pc')
{
    global $DB;
    require_once (CMS_ROOT . '/include/api/const.php');
    require_once (CMS_ROOT . '/include/api/pay.class.php');
    $rsPayRes = pay::getlist();
    if ($rsPayRes['errorCode'] == 0) {
        $rsPay = $rsPayRes['payConfig'];
    } else {
        return [
            'status' => 0,
            'msg' => '支付配置信息不正确'
        ];
    }

    require_once (CMS_ROOT . '/pay/yijipay/autoload.php');
    $UsersID = isset($_SESSION['Users_ID']) && $_SESSION['Users_ID'] ? $_SESSION['Users_ID'] : (isset($_GET['UsersID']) ? $_GET['UsersID'] : '');
    $UserID = isset($_SESSION[$UsersID . 'User_ID']) && $_SESSION[$UsersID . 'User_ID'] ? $_SESSION[$UsersID . 'User_ID'] : 0;
    if ($type == 'pc') {
        $rsUser = UsersBindFrontRelation::find($UsersID);
        if (! empty($rsUser)) {
            $UserID = $rsUser->User_ID;
        } else {
            $data = [
                'status' => 0,
                'msg' => '您还没有绑定前台会员，请绑定会员'
            ];
            return $data;
        }
    }
    $objUserBindYijiPay = UserBindYijiPay::Multiwhere([
                'Users_ID' => $UsersID,
                'User_ID' => $UserID
            ])->first();

    if ($objUserBindYijiPay) {
        $flag = $objUserBindYijiPay->toArray();
        $data = [
            'status' => 1,
            'msg' => 'ok',
            'userid' => $flag['Yiji_UserID']
        ];
        return $data;
    }
    $account = new Account();
    $result = $account->ppmNewRuleRegisterUser($param);
    $result = json_decode($result, true);
    if ($result && $result['success'] == true && $result['resultCode'] == 'EXECUTE_SUCCESS') {
        $yiji_userid = $result['userId']; // 20160930010000754251
        $objUserBindYijiPay = new UserBindYijiPay();
        $objUserBindYijiPay->Users_ID = $UsersID;
        $objUserBindYijiPay->User_ID = $UserID;
        $objUserBindYijiPay->Yiji_UserID = $yiji_userid;
        
        $flag = $objUserBindYijiPay->save();
        if ($flag) {
            $data = [
                'status' => 1,
                'msg' => 'ok',
                'userid' => $yiji_userid,
                'OrderNo' => $result['orderNo']
            ];
        } else {
            $data = [
                'status' => 0,
                'msg' => '绑定会员账号失败'
            ];
        }
        return $data;
    }
    return [
        'data' => $result,
        'status' => - 1,
        'msg' => $result['resultMessage'],
        'userid' => ''
    ];
}

/**
 * 订单确认收货后进行分账操作
 * 
 * @param Object $DB
 *            数据库操作对象
 * @param String $OrderID
 *            用户订单号
 * @param Array $param
 *            分账参数 [['Users_Account_ID'=>'userid1','amount'=>'2.33','memo' =>'经销商佣金1'],['Users_Account_ID'=>'userid2','amount'=>'2.33','memo' =>'经销商佣金2'],['Users_Account_ID'=>'userid3','amount'=>'2.33','memo' =>'经销商佣金3']]
 * @return Bool $Falg 最终是否操作成功
 */
function confirm_finance($OrderID)
{
    global $DB;
    require_once (CMS_ROOT . '/include/api/pay.class.php');
    $rsPayRes = pay::getlist();
    
    if ($rsPayRes['errorCode'] == 0) {
        $rsPay = $rsPayRes['payConfig'];
    } else {
        return [
            'msg' => '支付方式不存在',
            'status' => 0
        ];
    }
    $rsOrder = Order::where("Order_Status",">=",2)->Multiwhere([
                    'Order_ID' => $OrderID,
                    'Order_PaymentMethod' => '易极付'
               ])->first();
    
    if (!$rsOrder) {
        return [
            'msg' => '没有可用于清分的订单',
            'status' => 0
        ];
    }
    $rsOrder = $rsOrder->toArray();
	if($rsOrder['Order_Status'] == 4 && !$rsOrder['Order_Virtual_Cards']){
		return [
			'msg' => 'ok',
			'status' => 1
		];
	}
    if (! $rsOrder['Service_billId']) {
        return [
            'msg' => '不正确的交易流水号',
            'status' => 0
        ];
    }
    $product = json_decode($rsOrder['Order_CartList'], true);
    $shareProfits = '';
    $description = '';
    $totalIncomeBalance = 0;
    $goodsUserID = 0;
    $param = [];
    $distributeBoins = 0; // 计算分销佣金
    require_once (CMS_ROOT . '/pay/yijipay/autoload.php');
    $finance = new Finance();
    $isPayAccount = true;
    if ($rsOrder['DistributeAccount_ID'] > 0) { // 有分销商
        $result = isYijiUserByDistributor($OrderID);
        if ($result['status'] == 0) {
            return $result;
        }
        $recordBonousList = $result['data'];
        $UsersID = '';
        $yijiUser_ID = '';
        $User_ID = 0;
        
        $result = isYijiUserBySeller($rsOrder['Users_ID']);
        if ($result['status'] == 1) {
            $isPayAccount = true;
        } else {
            $isPayAccount = false;
        }
        $goodsYijiUserID = $yijiUser_ID = $result['Yiji_UserID'];
        $goodsUserID = $User_ID = $result['User_ID'];
        
        if (strlen($rsOrder['Sales_By']) > 2) {
            // 获取卖家的Yiji_UserID
            $UsersID = $rsOrder['Sales_By'];
            $result = isYijiUserBySeller($UsersID);
            $yijiUser_ID = $result['Yiji_UserID'];
            $User_ID = $result['User_ID'];
        } else {
            $UsersID = $rsOrder['Users_ID'];
        }
        // 获取分销佣金相关信息
        $disAmountArr = getDisBonousInfo($OrderID, $yijiUser_ID, $User_ID);
        $platfromAmount = $disAmountArr['platfromAmount']; // 无易极付账号的分销商佣金
        $distributeBoins = $disAmountArr['distributeBouns']; // 有易极付账号的分销商佣金
        $shareProfits .= $disAmountArr['shareProfits'];
        $description .= $disAmountArr['description'];
        
        // 获取分销记录并计算供货价
        $totalBoins = getProductsTotalPrices($OrderID); // 计算总佣金
                                                        // 计算供货价
        $totalIncomeBalance = $rsOrder['Order_TotalPrice'] - $totalBoins;
        
        
        if (strlen($rsOrder['Sales_By']) > 2) {
            if ($goodsYijiUserID) {
                $description .= '供货商【支付账户】' . $goodsYijiUserID . '所得' . $totalIncomeBalance . '元|';
            } else {
                $description .= '供货商【普通】' . $rsOrder['Users_ID'] . '_' . $goodsUserID . '所得' . $totalIncomeBalance . '元|';
            }
            $amount = $totalBoins - $distributeBoins;
            if ($isPayAccount == true) {
                if ($amount > 0) {
                    $shareProfits .= PARTNER_ID . '~' . $amount . '~' . PARTNER_ID . '平台分润';
                    $description .= '平台' . PARTNER_ID . '获得' . $amount . '元佣金\r\n';
                }
                $shareProfits = trim($shareProfits, '^');
                if ($shareProfits) {
                    $param['shareProfits'] = $shareProfits;
                }
            }else{
                if ($amount > 0) {
                    $description .= '平台' . PARTNER_ID . '获得' . $amount . '元佣金\r\n';
                }
            }
        }else{
            //计算多级分销商（超过3级）分剩下的 供货商所应得到的
            $amount = $platfromAmount;
            $sellerprice = $totalBoins + ($totalIncomeBalance - $amount - $distributeBoins);
            if ($goodsYijiUserID) {
                $description .= '供货商【支付账户】' . $goodsYijiUserID . '所得' . $sellerprice . '元|';
            } else {
                $description .= '供货商【普通】' . $rsOrder['Users_ID'] . '_' . $goodsUserID . '所得' . $sellerprice . '元|';
            }
            if ($isPayAccount == true) {
                if ($amount > 0) {
                    $shareProfits .= PARTNER_ID . '~' . $amount . '~' . PARTNER_ID . '平台分润';
                    $description .= '平台' . PARTNER_ID . '获得' . $amount . '元佣金\r\n';
                }
                $shareProfits = trim($shareProfits, '^');
                if ($shareProfits) {
                    $param['shareProfits'] = $shareProfits;
                }
            }else{
                if ($amount > 0) {
                    $description .= '平台' . PARTNER_ID . '获得' . $amount . '元佣金\r\n';
                }
            }
			
        }
        
    } else {
        $result = isYijiUserBySeller($rsOrder['Users_ID']);
        if ($result['status'] == - 1) {
            return $result;
        }
        $goodsUserID = $result['User_ID'];
        if ($result['status'] == 1) {
            $isPayAccount = true;
        } else {
            $isPayAccount = false;
        }
        $totalIncomeBalance = getProductsTotalPrices($OrderID); // 计算供货价
    }
    if ($isPayAccount != true) {
        // 供货商无支付账户
        $objDisaccount = Dis_Account::Multiwhere([
            'Users_ID' => $rsOrder['Users_ID'],
            'User_ID' => $goodsUserID
        ])->first();
        if(!$objDisaccount) return [
                'msg' => '不正确的分销账号'
            ];
        $rsDisaccount = $objDisaccount->toArray();
        $objDisaccount->Total_Income = $rsDisaccount['Total_Income'] + $totalIncomeBalance;
        $objDisaccount->yiji_balance = $rsDisaccount['yiji_balance'] + $totalIncomeBalance;
        $flag = $objDisaccount->save();
        if ($flag) {
            return [
                'msg' => 'ok',
                'status' => 1
            ];
        } else {
            return [
                'msg' => '写入分销金额失败',
                'status' => 0
            ];
        }
    }
    
    $order_no = 'A' . date("YmdHis", time()) . time();
    $param['tradeNo'] = $rsOrder['Service_billId'];
    $param['orderNo'] = $order_no;
    $result = $finance->commandPayConfirm($param);
    
    logging("易极付分账调试", "({$OrderID})\r\n 佣金详情：" . $description . "\r\n佣金参数\r\n" . print_r($result, 1));
    if ($result['success'] == true && $result['resultCode'] == 'EXECUTE_SUCCESS') {
        // 分账成功
        require_once (CMS_ROOT . '/include/api/distribute.class.php');
        $data = [];
        if ($rsOrder['DistributeAccount_ID'] > 0) {
            foreach ($recordBonousList as $k => $v) {
                $data[] = [
                    'Users_ID' => $rsOrder['Users_ID'],
                    'DisAccount_Level' => $v['level'],
                    'Order_ID' => $OrderID,
                    'createTime' => time(),
                    'trade_no' => $result['tradeNo'],
                    'Record_Description' => $v['B2CRecord_Description'],
                    'Record_Money' => $v['amount'],
                    'Record_SN' => $v['Record_Sn'],
                    'FromBiz_Account' => $v['From_UsersAccount'],
                    'Biz_Account' => $v['Users_Account']
                ];
            }
            $result = distribute::addDisTradeIntro($data);
            if ($result['errorCode'] == 0) {
                return [
                    'msg' => 'ok',
                    'status' => 1
                ];
            } else {
                return [
                    'msg' => $result['msg'],
                    'status' => 0
                ];
            }
        } else {
            return [
                'msg' => 'ok',
                'status' => 1
            ];
        }
    } else {
        return [
            'msg' => isset($result['failReason']) ? $result['failReason'] : $result['resultMessage'],
            'status' => 0
        ];
    }
}

// 生成带前缀的请求订单号
function generate_orderno($prefix = '')
{
    mt_srand((double) microtime() * 1000000);
    return date('YmdHis') . str_pad(mt_rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
}

// 易极付站内转账
function transforBalance()
{
    global $DB;
    require_once (CMS_ROOT . '/include/api/const.php');
    require_once (CMS_ROOT . '/include/api/pay.class.php');
    $rsPayRes = pay::getlist();
    if ($rsPayRes['errorCode'] == 0) {
        $rsPay = $rsPayRes['payConfig'];
    } else {
        return [
            'status' => 0,
            'msg' => '支付配置信息不正确'
        ];
    }
    $orderNo = generate_orderno("T");
    
    // 获取易极付付款方账户里的余额
    
    require_once (CMS_ROOT . '/pay/yijipay/autoload.php');
    $data = [
        'userId' => PARTNER_ID
    ];
    $account = new Account();
    $result = $account->userBalanceQuery($data);
    $result = json_decode($result, true);
    $yijiavailamount = 0;
    if ($result['success'] == true && $result['resultCode'] == 'EXECUTE_SUCCESS') {
        $yijiavailamount = $result['availableBalance'];
    } else {
        return [
            'status' => 0,
            'msg' => '付款方账户没有可用余额'
        ];
    }
    $UsersID = isset($_SESSION['Users_ID']) && $_SESSION['Users_ID'] ? $_SESSION['Users_ID'] : '0';
    $UserID = isset($_SESSION[$UsersID . 'User_ID']) && $_SESSION[$UsersID . 'User_ID'] ? $_SESSION[$UsersID . 'User_ID'] : 0;
    $param = [
        'orderNo' => $orderNo,
        'merchOrderNo' => $UsersID . '_' . $UserID . '_' . $orderNo,
        'returnUrl' => SITE_URL . 'pay/yijipay/tfBalance_return_url.php',
        'notifyUrl' => SITE_URL . 'pay/yijipay/tfBalance_notify_url.php',
        'payerUserId' => PARTNER_ID
    ];
    $rsBindUser = UserBindYijiPay::Multiwhere([
        'Users_ID' => $UsersID,
        'User_ID' => $UserID,
        'status' => 2
    ])->first();
    
    if ($rsBindUser) {
        $rsBindUser = $rsBindUser->toArray();
        $rsDisaccount = Dis_Account::Multiwhere([
            'Users_ID' => $UsersID,
            'User_ID' => $UserID,
        ])->first();
        if(!$rsDisaccount){
            return [
                'status' => 0,
                'msg' => '不正确的账号记录'
            ];
        }
        $rsDisaccount = $rsDisaccount->toArray();
        $disamount = $rsDisaccount['yiji_balance'];
        if ($yijiavailamount < $disamount) {
            return [
                'status' => 0,
                'msg' => '付款方账户余额不足'
            ];
        }
        if ($disamount > 0) {
            $data = [
                'itemMerchOrderNo' => $orderNo,
                'payeeUserId' => $rsBindUser['Yiji_UserID'],
                'outPayeeShopName' => '转账',
                'money' => $disamount,
                'memo' => '会员转账'
            ];
            $param['toBalanceList'] = "[" . json_encode($data, JSON_UNESCAPED_UNICODE) . "]";
			logging("易极付zhuanzhang回调", $param);
            $account->qftBatchTransfer($param);
            exit();
        }else{
            return [
                'status' => 0,
                'msg' => '收款方账户余额不足'
            ];
        }
    } else {
        return [
            'status' => 0,
            'msg' => '会员不存在'
        ];
    }
}

/**
 * 确认收货后，本地执行逻辑处理
 * 
 * @param String $Order_ID
 *            订单ID
 * @return Bool $Falg 最终是否操作成功
 */
function confirm_self($Order_ID, $UsersID)
{
    global $DB;
    Order::observe(new OrderObserver());
    $order = Order::find($Order_ID);
    if ($order) {
        $Flag = $order->confirmReceive();
        // 添加订单信息 b2c商家消息页 已完成
        /*$msg_data = [
            'Users_ID' => $order->Users_ID,
            'User_ID' => $order->User_ID,
            'Sales_By' => $order->Sales_By, // Users_ID 和 Sales_By 结合判断信息归属
            'Order_ID' => $Order_ID,
            'Order_Status' => 4,
            'msg_title' => '【已完成】订单' . $Order_ID,
            'msg_describe' => '',
            'msg_status' => 0, // 信息状态 0：未读 1：已读
            'create_time' => time()
        ];
        $msg_res = $DB->Add('msg_order', $msg_data);*/
        
        // 添加提现信息 b2c商家消息页 易极付分账
        $msg_data = [
            'Users_ID' => $order->Users_ID,
            'User_ID' => $order->User_ID,
            'msg_title' => '订单' . $Order_ID . '确认收货并通过易极付分账',
            'msg_describe' => '',
            'msg_status' => 0, // 信息状态 0：未读 1：已读
            'create_time' => time()
        ];
        $msg_res = $DB->Add('msg_withdraw', $msg_data);
        
        $response = array(
            "status" => 1,
            "url" => '/api/' . $UsersID . '/shop/member/status/4/'
        );
    } else {
        $response = array(
            "status" => 0,
            "msg" => '确认收货失败'
        );
    }
    return $response;
}

/**
 * 跳转到易极付钱包
 * 
 * @param Array $param
 *            传递所需要的参数
 * @param String $type
 *            访问设备的类型，可用值为：pc和mobile
 * @return Bool $Falg 最终是否操作成功
 */
function wallet(array $param = [], $type = 'pc')
{
    global $DB;
    require_once (CMS_ROOT . '/include/api/const.php');
    require_once (CMS_ROOT . '/include/api/pay.class.php');
    $rsPayRes = pay::getlist();
    if ($rsPayRes['errorCode'] == 0) {
        $rsPay = $rsPayRes['payConfig'];
    } else {
        return [
            'status' => - 1,
            'data' => '',
            'msg' => '支付配置信息不正确'
        ];
    }
    require_once (CMS_ROOT . '/pay/yijipay/autoload.php');
    
    $UsersID = isset($_SESSION['Users_ID']) && $_SESSION['Users_ID'] ? $_SESSION['Users_ID'] : '';
    $UserID = isset($_SESSION[$UsersID . 'User_ID']) && $_SESSION[$UsersID . 'User_ID'] ? $_SESSION[$UsersID . 'User_ID'] : 0;
    
    // PC端
    if ($type == 'pc') {
        $rsObjUser =  UsersBindFrontRelation::find($UsersID);
        if ($rsObjUser) {
            $UserID = $rsObjUser->User_ID;
        } else {
            $data = [
                'status' => - 1,
                'msg' => '您还没有绑定前台会员，请绑定会员'
            ];
            return $data;
        }
    }
    
    $rsObjBindUser = UserBindYijiPay::Multiwhere([
                    'Users_ID' => $UsersID,
                    'User_ID' => $UserID,
					'status' => 2
                ])->first();
    if (!$rsObjBindUser) {
        $data = [
            'status' => - 1,
            'msg' => '您还未注册会员呢'
        ];
        return $data;
    }
    $param['userId'] = $rsObjBindUser->Yiji_UserID;
    $charge = new Charge();
    $charge->wallet($param);
}

// 检测经销商是否是易极付支付会员
function isYijiUserByDistributor($OrderID)
{
    global $DB;
    $data = [];
    $recordlist = Dis_Record::where("Order_ID", $OrderID)->get();

    if (!$recordlist) {
        $data = [
            'status' => 0,
            'msg' => '获取分销记录失败！'
        ];
    } else {
        $recordlist = $recordlist->toArray();
        $recordStr = '';
        foreach ($recordlist as $key => $val) {
            $recordStr .= $val['Record_ID'] . ',';
        }
        $recordStr = trim($recordStr, ',');
        $sql = "SELECT dr.Users_ID,dr.User_ID,dr.Record_Sn,dr.level,dr.Record_Money as amount,dr.B2CRecord_Description,dr.Users_Account,dr.From_UsersAccount,u.Yiji_UserID AS Users_Account_ID,u.isPayAccount,u.status FROM distribute_account_record AS dr LEFT JOIN user_bindyijipay AS u ON dr.User_ID=u.User_ID WHERE dr.Ds_Record_ID IN (" . $recordStr . ") ";
        $result = $DB->query($sql);
        $recordBonousList = $DB->toArray($result);
        if (empty($recordBonousList)) {
            $data = [
                'status' => - 1,
                'data' => [],
                'msg' => '获取分销佣金记录失败！'
            ];
        } else {
            $data = [
                'status' => 1,
                'data' => $recordBonousList,
                'msg' => 'ok'
            ];
        }
    }
    return $data;
}

/**
 * 检测卖家是否是易极付支付会员
 * 
 * @param String $UsersID
 *            卖家UsersID
 * @return Array 返回数组
 *         [
 *         status : 是否是易极付支付账号，1 是， 0 不是
 *         Yiji_UserID : 易极付支付User_ID
 *         User_ID : 平台User_ID
 *         msg : 返回消息提示
 *         ]
 */
function isYijiUserBySeller($UsersID)
{
    global $DB;
    $data = [];
    $rsBfUser = UsersBindFrontRelation::find($UsersID);
    if (!$rsBfUser) {
        $data = [
            'status' => - 1,
            'msg' => '您还没有绑定前台会员账号，请绑定会员账号！'
        ];
    } else {
        $rsBfUser = $rsBfUser->toArray();
        $rsBindUser = UserBindYijiPay::Multiwhere([
            'Users_ID' => $UsersID,
            'User_ID' => $rsBfUser['User_ID'],
			'status' => 2
        ])->first();
        
        if (!$rsBindUser) {
            $data = [
                'status' => 0,
                'Yiji_UserID' => '',
                'User_ID' => $rsBfUser['User_ID'],
                'msg' => '当前卖家或供货商不是支付会员！'
            ];
        } else {
            $data = [
                'status' => 1,
                'Yiji_UserID' => $rsBindUser->Yiji_UserID,
                'User_ID' => $rsBindUser->User_ID,
                'msg' => 'ok'
            ];
        }
    }
    return $data;
}

/**
 * 获取供货商所得的金额
 * 
 * @param BigInt $OrderID
 *            订单号
 * @return Decimal 返回供货商所得的金额
 */
function getProductsTotalPrices($OrderID)
{
    $totalBoins = 0;
    $rsOrder = Order::find($OrderID);
    if (! $rsOrder)
        return 0;
    $product = json_decode($rsOrder->Order_CartList, true);
    if (! $product)
        return 0;
    $disRecordList = Dis_Record::where('Order_ID', $OrderID)->get();
    if ($disRecordList) {
        foreach ($disRecordList as $v) {
            $result = $product[$v['Product_ID']][0];
            if (! empty($result)) {
                if (strlen($rsOrder['Sales_By']) > 2) {
                    $totalBoins += ($result['ProductsPriceX'] - $result['ProductsPriceS']) * $v['Qty'];
                } else {
                    $totalBoins += ($result['ProductsPriceX'] * $result['Products_Profit'] / 100) * $v['Qty'];
                }
            }
        }
    }
    return $totalBoins;
}

/**
 * 获取分销佣金相关信息
 * 
 * @param Integer $OrderID
 *            传递所需要的订单号
 * @param String $UsersAccountID
 *            卖家易极付User_ID
 * @param String $UserID
 *            卖家会员 User_ID
 * @return Array 返回数组
 *         [
 *         shareProfits : 表示易极付分账所需要的参数
 *         description : 分账金额详情表述
 *         platfromAmount : 无易极付支付会员分销商或者商家的金额
 *         distributeBouns : 易极付支付会员分销商或者商家的金额
 *         totalBouns: 分销商或者商家所得的金额
 *         ]
 */
function getDisBonousInfo($OrderID, $UsersAccountID, $UserID)
{
    $rsOrder = Order::find($OrderID);
    if (! $rsOrder)
        return [];
    $rsDistributor = isYijiUserByDistributor($OrderID);
    if ($rsDistributor == - 1)
        return [];
    $recordBonousList = $rsDistributor['data'];
    
    $shareProfits = '';
    $distributeBoins = 0;
    $platfromAmount = 0;
    $description = '';
    
    if ($recordBonousList && ! empty($recordBonousList)) {
        $data = [];
        foreach ($recordBonousList as $k => $v) {
            $data[$v['User_ID']] = $v;
        }
        foreach ($data as $k => $v) {
            foreach ($recordBonousList as $m => $q) {
                $flag = array_diff($v, $q);
                if (! empty($flag) && $v['User_ID'] == $q['User_ID']) {
                    $data[$k]['amount'] = $v['amount'] + $q['amount'];
                }
            }
        }
        $count = 1;
        foreach ($data as $k => $v) {
            if ($v["amount"] <= 0) {
                continue;
            }
            if ($v['Users_Account_ID'] && $v['status'] == 2) { // 存在易极付支付账号
                $shareProfits .= $v['Users_Account_ID'] . '~' . $v['amount'] . '~' . $v['Users_Account_ID'] . '分账^';
                $distributeBoins += $v['amount'];
                if ($UsersAccountID == $v['Users_Account_ID']) {
                    $description .= '卖家【支付账户】' . $v['Users_Account_ID'] . '获得' . $v['amount'] . '元佣金|';
                } else {
                    $description .= '分销商【支付账户】' . $v['Users_Account_ID'] . '获得' . $v['amount'] . '元佣金|';
                }
            } else {
                $platfromAmount += $v['amount'];
                if ($UserID == $v['User_ID']) {
                    $description .= '卖家【普通】' . $v['Users_ID'] . '_' . $v['User_ID'] . '获得' . $v['amount'] . '元佣金|';
                } else {
                    $description .= '分销商【普通】' . $v['Users_ID'] . '_' . $v['User_ID'] . '获得' . $v['amount'] . '元佣金|';
                }
            }
        }
    }
    return [
        'shareProfits' => $shareProfits,
        'description' => $description,
        'platfromAmount' => $platfromAmount,
        'distributeBouns' => $distributeBoins,
        'totalBouns' => $platfromAmount + $distributeBoins
    ];
}


// 过滤只剩下英文字母之外的其他字符
function filterWord($string)
{
    $string = cleanJsCss(trim($string));
    return preg_match_all("/([^A-Za-z])+/", $string);
}

// 过滤只剩下数字之外的其他字符
function filterDigit($string)
{
    $string = cleanJsCss(trim($string));
    return preg_match_all("/([^0-9])+/", $string);
}

// 过滤只剩下字母数字之外的其他字符
function filterWordDigit($string)
{
    $string = cleanJsCss(trim($string));
    return preg_match_all("/([^A-Za-z0-9])+/", $string);
}

// 过滤只剩下字母数字之外的其他字符
function filterEmail($string)
{
    $string = cleanJsCss(trim($string));
    return preg_match_all("/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,4}$/", $string);
}

// 过滤只剩下字母数字之外的其他字符
function filterMobile($string)
{
    $string = cleanJsCss(trim($string));
    return preg_match_all("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/", $string);
}

// 过滤只剩下汉字之外的其他字符
function filterCNDigit($string)
{
    $string = cleanJsCss(trim($string));
    return preg_match_all("/[^\x{4e00}-\x{9fa5}]+/u", $string);
}

function validateInput($post)
{
    if (! empty($post)) {
        // 全局过滤html标记,css和javascript
        foreach ($post as $k => $v) {
            $post[$k] = cleanJsCss($v);
        }
        
        $registerUserType = isset($post['registerUserType']) && $post['registerUserType'] ? filterWord($post['registerUserType']) : 0;
        if ($registerUserType == 0) {
            return [
                'status' => 0,
                'msg' => '不正确的用户注册类型'
            ];
        }
        $userName = isset($post['userName']) && $post['userName'] ? filterWord($post['userName']) : - 1;
        if ($userName == 0) {
            return [
                'status' => 0,
                'msg' => '不正确的用户名'
            ];
        }
        
        $email = isset($post['email']) && $post['email'] ? filterEmail($post['email']) : - 1;
        if ($email == 0) {
            return [
                'status' => 0,
                'msg' => '不正确的email'
            ];
        }
        
        $mobile = isset($post['mobile']) && $post['mobile'] ? filterMobile($post['mobile']) : 0;
        if ($mobile == 0) {
            return [
                'status' => 0,
                'msg' => '不正确的手机号'
            ];
        }
    }
}

