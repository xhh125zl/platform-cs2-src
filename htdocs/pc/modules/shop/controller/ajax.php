<?php
namespace shop\controller;
class ajaxController extends controllController {
    public function __construct() {
        parent::_initialize();
    }
    public function indexOp() {
        $action = empty($_REQUEST['action']) ? '' : $_REQUEST['action'];
        if (IS_AJAX) {
            $func_name = '_' . $action;
			$data = $this->$func_name();
            $this->ajaxReturn($data);
        }
    }
	private function _distribute_product() {
		//检测用户是否登陆
		$productid = $_POST['productid'];
		if(empty($_SESSION[$this->UsersID . 'User_ID'])) {
			$_SESSION[$UsersID . 'HTTP_REFERER'] = url('goods/index', array('id'=>$productid));
			$url = url('public/login');
			/*返回值*/
			$Data = array(
				'status' => 0,
				'info' => '您还为登陆，请登陆！',
				'url' => $url
			);
		}else {
			$condition = array(
			    'Users_ID' => $this->UsersID,
				'User_ID' => $_SESSION[$this->UsersID . 'User_ID']
			);
			$rsUser = model('user')->field('Is_Distribute')->where($condition)->find();
			if($rsUser['Is_Distribute']) {//如果此用户为分销用户
				$url = url('distribute/distribute_goods', array('ProductsID'=>$productid));
			}else {
				$url = url('distribute/join');
			}
			$Data = array(
				'status' => 1,
				'url' => $url
			);	
		}
		return $Data;
	}
	private function _shoucang() {
		//检测用户是否登陆
		if(empty($_SESSION[$this->UsersID . 'User_ID'])) {
			$_SESSION[$this->UsersID . 'HTTP_REFERER'] = url('goods/index', array('id'=>$_POST['productId']));
			$url = url('public/login');
			/*返回值*/
			$Data = array(
				'status' => 0,
				'info' => '您还未登陆，请登陆！',
				'url' => $url
			);
		}else {
			$productId = $_POST['productId'];
			$favourite_model = model('user_favourite_products');
			$favourite_flag = $favourite_model->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Products_ID'=>$productId))->total();
	        if($favourite_flag) {
				$insertInfo = array(
				    'User_ID' => $_SESSION[$this->UsersID . 'User_ID'],
					'Products_ID' => $productId,
					'IS_Attention' => 1
				);
				$favourite_model->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Products_ID'=>$productId))->delete();
				$Data = array(
					'status' => 1,
					'info' => '取消收藏成功！',	
				);
			}else {
				$insertInfo = array(
					'User_ID' => $_SESSION[$this->UsersID . 'User_ID'],
					'Products_ID' => $productId,
					'IS_Attention' => 1
				);
				$Result = $favourite_model->insert($insertInfo);
				$Data = array(
					"status"=>1,
					"info"=>"收藏成功！",
				);
			}		
     			
		}
		return $Data;
	}
    //点击不同属性获取价格
    private function _getPrice() {
        $goods_model = model('shop_products');
        $Products_ID = $_REQUEST['ProductsID'];
        $rsProducts = $goods_model->where(array(
            'Users_ID' => $this->UsersID,
            'Products_ID' => $Products_ID
        ))->find();
        $response = array(
            'msg' => '',
            'result' => '',
            'qty' => 1
        );
        $attr_ids = !empty($_REQUEST['attr']) ? $_REQUEST['attr'] : 0;
        $cur_price = $no_attr_price = $rsProducts['products_pricex'];
        if ($rsProducts) {
            $product_attrs = model('shop_products_attr')->where(array(
                'Products_ID' => $Products_ID,
                'Product_Attr_ID' => $attr_ids
            ))->select();
            if (!empty($product_attrs)) {
                foreach ($product_attrs as $key => $item) {
                    $cur_price += $item['attr_price'];
                }
            }
            //查看用户是否登陆
            //获取登录用户的用户级别及其是否对应优惠价
            if (!empty($_SESSION[$this->UsersID . 'User_ID'])) {
                $rsUser = model('user')->field('User_Level')->where(array(
                    'Users_ID' => $this->UsersID,
                    'User_ID' => $_SESSION[$this->UsersID . 'User_ID']
                ))->find();
                if ($rsUser['User_Level'] > 0) {
                    $rsUserConfig = model('user_config')->field('UserLevel')->where(array(
                        'Users_ID' => $this->UsersID
                    ))->find();
                    $discount_list = json_decode($rsUserConfig['UserLevel'], TRUE);
                    $discount = $discount_list[$rsUser['User_Level']]['Discount'];
                    $discount_price = $cur_price * (1 - $discount / 100);
                    $discount_price = round($discount_price, 2);
                    $cur_price = $discount_price;
                }
            }
            $response['result'] = number_format($cur_price, '2');
        }
        return $response;
    }
    private function _add_to_cart() {
        //参数判断
        if (isset($_POST['ProductsID'])) {
            $ProductsID = $_POST['ProductsID'];
        } else {
            $Data = array(
                'status' => 0,
                'msg' => '非法提交'
            );
            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
            exit;
        }
            
        $rsProducts = model()->query('select * from shop_products where Users_ID = "'.$this->UsersID.'" and Products_ID = '.$ProductsID.' and Products_SoldOut=0 and Products_Status=1');
        $rsProducts = array_change_key_case($rsProducts[0],CASE_LOWER);
        if (!$rsProducts) {
            $Data = array(
                'status' => 0,
                'msg' => '产品已下架'
            );
            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if ($rsProducts['products_count'] > 0 && $rsProducts['products_count'] < $_POST['Qty']) {
            $Data = array(
                'status' => 0,
                'msg' => '产品库存不足，最多能购买' . $rsProducts['products_count'] . '件'
            );
            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $BizID = $rsProducts['biz_id'];
        $cur_price = $rsProducts['products_pricex'];
        /*如果此产品包含属性*/
        $Property = array();
        if (!empty($_POST['spec_list'])) {
            $attr_ids = explode(',', $_POST['spec_list']);
            $product_attrs = model('shop_products_attr')->where(array(
                'Products_ID' => $ProductsID,
                'Product_Attr_ID' => $attr_ids
            ))->select();
            if (!empty($product_attrs)) {
                foreach ($product_attrs as $key => $item) {
                    $cur_price+= $item['attr_price'];
                }
            }
            $Property = get_posterty_desc($_POST['spec_list'], $this->UsersID, $_POST['ProductsID']);
        }
        $JSON = json_decode($rsProducts['products_json'], true); //产品图片
		
        $OwnerID = $this->owner['id']; //店主
        $cart_key = $this->UsersID . $_POST['cart_key'];
        $flag = true;
		if($cart_key == $this->UsersID . 'CartList') {
			if (!empty($_SESSION[$cart_key])) {
				$CartList = json_decode($_SESSION[$cart_key], true);
				if (!empty($CartList[$BizID][$ProductsID])) {
					foreach ($CartList[$BizID][$ProductsID] as $k => $v) {
						$spec_list = isset($_POST['spec_list']) ? $_POST['spec_list'] : '';
						$array = array_diff(explode(',', $spec_list) , explode(',', $v['spec_list'])); //计算数组的差集
						if (empty($array)) {
							$CartList[$BizID][$ProductsID][$k]['Qty'] += $_POST['Qty'];
							$flag = false;
							break;
						}
					}
				}
			}
		}else {
			$_SESSION[$cart_key] = '';
		}
        //更新购物车
        if ($flag) {
            $CartList[$BizID][$ProductsID][] = array(
                'ProductsName' => $rsProducts['products_name'],
                'ImgPath' => empty($JSON['ImgPath']) ? '' : $JSON['ImgPath'][0],
                'ProductsPriceX' => $cur_price,
                'ProductsPriceY' => $rsProducts['products_pricey'],
                'ProductsWeight' => $rsProducts['products_weight'],
                'Products_Shipping' => $rsProducts['products_shipping'],
                'Products_Business' => $rsProducts['products_business'],
                'Shipping_Free_Company' => $rsProducts['shipping_free_company'],
                'IsShippingFree' => $rsProducts['products_isshippingfree'],
                'OwnerID' => $OwnerID,
                'ProductsIsShipping' => $rsProducts['products_isshippingfree'],
                'Qty' => $_POST['Qty'],
                'spec_list' => isset($_POST['spec_list']) ? $_POST['spec_list'] : '',
                'Property' => $Property,
                "nobi_ratio" => $rsProducts["nobi_ratio"],
		"platForm_Income_Reward" => $rsProducts["platform_income_reward"],
				"area_Proxy_Reward" => $rsProducts["area_proxy_reward"],
				"sha_Reward" => $rsProducts["sha_reward"],
            );
        }
        $_SESSION[$cart_key] = json_encode($CartList, JSON_UNESCAPED_UNICODE);
        
        $total_price = 0;
        foreach ($CartList as $ks => $vs) {
            foreach ($vs as $k1 => $v1){
                foreach ($v1 as $k2 => $v2){
                    $total_price += $v2['ProductsPriceX']*$v2['Qty'];
                }
            }
        }

        //以下是为添加购物车服务的代码
		$qty = 0;
		if($cart_key == $this->UsersID . 'CartList') {
			foreach ($CartList as $bizid => $bizcart) {
				foreach ($bizcart as $key => $value) {
					foreach ($value as $v) {
						$qty += $v['Qty'];
					}
				}
			}
		}
        $Data = array(
            'status' => 1,
            'total_price' => $total_price,
            'qty' => $qty
        );
        return $Data;
    }
    private function _cart_update() {
        $BizID = $_POST['BizID'];
        $ProductsID = $_POST['ProductsID'];
        $CartID = $_POST['CartID'];
        $Type = $_POST['Type'];
        $Qty = $_POST['Qty'];
        if (empty($_SESSION[$this->UsersID . 'CartList'])) {
            $Data = array(
                'status' => 0,
                'msg' => '购物车空的，赶快去逛逛吧！'
            );
            echo json_encode($Data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $CartList = $_SESSION[$this->UsersID . 'CartList'] ? json_decode($_SESSION[$this->UsersID . 'CartList'], true) : array();
        $total = 0;
        $rsProducts = model('shop_products')->field('Products_Count')->where(array(
            'Users_ID' => $this->UsersID,
            'Products_ID' => $ProductsID
        ))->find();
        if (empty($CartList[$BizID][$ProductsID][$CartID]) || !$rsProducts) {
            foreach ($CartList as $bizid => $bizcart) {
                foreach ($bizcart as $productsid => $products) {
                    foreach ($products as $carid => $cart) {
                        $total+= $cart['Qty'] * $cart['ProductsPriceX'];
                    }
                }
            }
            echo json_encode(array(
                'status' => 0,
                'msg' => '该商品不存在',
                'total' => $total
            ) , JSON_UNESCAPED_UNICODE);
            exit;
        }
        $Data = array();
        switch ($Type) {
            case 'qty_less': //减少
                if ($Qty <= 1) {
                    $Data['status'] = 1;
                    $Data['qty'] = 1;
                    $Data['msg'] = '最小购买数量为1！';
                    $CartList[$BizID][$ProductsID][$CartID]['Qty'] = 1;
                } else {
                    $CartList[$BizID][$ProductsID][$CartID]['Qty'] = $Qty - 1;
                    $Data['status'] = 2;
                    $Data['qty'] = $Qty - 1;
                    $Data['msg'] = '';
                }
                break;

            case 'qty_add': //增加
                if ($Qty >= $rsProducts['Products_Count'] && $rsProducts['Products_Count'] > 0) {
                    $Data['status'] = 1;
                    $Data['qty'] = $rsProducts['Products_Count'];
                    $Data['msg'] = '产品库存不足，最多能购买' . $rsProducts['Products_Count'] . '件';
                    $CartList[$BizID][$ProductsID][$CartID]['Qty'] = $rsProducts['Products_Count'];
                } else {
                    $CartList[$BizID][$ProductsID][$CartID]['Qty'] = $Qty + 1;
                    $Data['status'] = 2;
                    $Data['qty'] = $Qty + 1;
                    $Data['msg'] = '';
                }
                break;

            case 'qty_input':
                if ($Qty < 1) {
                    $Data['status'] = 1;
                    $Data['qty'] = 1;
                    $Data['msg'] = '最小购买数量为1！';
                    $CartList[$BizID][$ProductsID][$CartID]['Qty'] = 1;
                } elseif ($Qty > $rsProducts['Products_Count'] && $rsProducts['Products_Count'] > 0) {
                    $Data['status'] = 1;
                    $Data['qty'] = $rsProducts['Products_Count'];
                    $Data['msg'] = '产品库存不足，最多能购买' . $rsProducts['Products_Count'] . '件';
                    $CartList[$BizID][$ProductsID][$CartID]['Qty'] = $rsProducts['Products_Count'];
                } else {
                    $CartList[$BizID][$ProductsID][$CartID]['Qty'] = $Qty;
                    $Data['status'] = 2;
                    $Data['qty'] = $Qty;
                    $Data['msg'] = '';
                }
                break;
        }
        $heji = 0; //店铺合计
        $xiaoji = 0; //商品小计
        foreach ($CartList as $bizid => $bizcart) {
            foreach ($bizcart as $productsid => $products) {
                foreach ($products as $carid => $cart) {
                    if ($bizid == $BizID) {
                        $heji+= $cart['Qty'] * $cart['ProductsPriceX'];
                    }
                    if ($productsid == $ProductsID && $carid == $CartID) {
                        $xiaoji+= $cart['Qty'] * $cart['ProductsPriceX'];
                    }
                    $total+= $cart['Qty'] * $cart['ProductsPriceX'];
                }
            }
        }
        $Data['total'] = $total;
        $Data['heji'] = $heji;
        $Data['xiaoji'] = $xiaoji;
        $_SESSION[$this->UsersID . 'CartList'] = json_encode($CartList, JSON_UNESCAPED_UNICODE);
		return $Data;
    }
    private function _cart_del() {
        $BizID = $_POST['BizID'];
        $ProductsID = $_POST['ProductsID'];
        $CartID = $_POST['CartID'];
        $Data = array();
        $Data['status'] = 1;
        $CartList = json_decode($_SESSION[$this->UsersID . 'CartList'], true);
        unset($CartList[$BizID][$ProductsID][$CartID]);
        if (count($CartList[$BizID][$ProductsID]) == 0) { //购物车中不存在该产品的存储，释放
            unset($CartList[$BizID][$ProductsID]);
        }
        if (count($CartList[$BizID]) == 0) { //购物车中不存在该商家的存储，释放
            unset($CartList[$BizID]);
            $Data['status'] = 2;
        }
        if (count($CartList) == 0) { //购物车中已无商品，释放
            $_SESSION[$this->UsersID . 'CartList'] = '';
            $Data['status'] = 3;
            $Data['total'] = 0;
        } else {
            $_SESSION[$this->UsersID . 'CartList'] = json_encode($CartList, JSON_UNESCAPED_UNICODE);
            $total = 0;
            foreach ($CartList as $bizid => $bizcart) {
                $heji = 0; //店铺合计
                foreach ($bizcart as $productsid => $products) {
                    foreach ($products as $carid => $cart) {
                        $total+= $cart['Qty'] * $cart['ProductsPriceX'];
                        $heji+= $cart['Qty'] * $cart['ProductsPriceX'];
                    }
                }
            }
            $Data['total'] = $total;
            $Data['heji'] = $heji;
        }
        return $Data;
    }
    private function _cart_check() {
        if (!empty($_SESSION[$this->UsersID . 'CartList'])) {
            $CartList = json_decode($_SESSION[$this->UsersID . 'CartList'], true);
            if (count($CartList) > 0) {
                $Data = array(
                    'status' => 1,
                    'msg' => ''
                );
            } else {
                $Data = array(
                    'status' => 0,
                    'msg' => '购物车空的，赶快去逛逛吧！'
                );
            }
        } else {
            $Data = array(
                'status' => 0,
                'msg' => '购物车空的，赶快去逛逛吧！'
            );
        }
        return $Data;
    }
    private function _get_shipping_list() {
        $BizID = $_POST['BizID'];
        //获取每个供货商的运费默认配置
        $condition = array(
            'Users_ID' => $this->UsersID,
            'Biz_ID' => $BizID,
        );
        $Biz_Config = model('biz')->field('Biz_ID,Shipping,Default_Shipping')->where($condition)->find();
        $biz_company_dropdown = get_front_shiping_company_dropdown($this->UsersID, $Biz_Config);
        if ($biz_company_dropdown) {
            $Data = array(
                'status' => 1,
                'Default_Shipping' => $Biz_Config['Default_Shipping'],
                'Biz_ID' => $BizID,
                'biz_company_dropdown' => $biz_company_dropdown
            );
        } else {
            $Data = array(
                'status' => 0,
                'biz_company_dropdown' => array()
            );
        }
        return $Data;
    }
    private function _change_shipping_method() {
        $Shipping_IDS = isset($_POST['Shipping_ID']) ? organize_shipping_id($_POST['Shipping_ID']) : array();
        $City_Code = $_POST['City_Code'];
        $Biz_ID = $_POST['Biz_ID'];
        $cart_key = $this->UsersID . $_POST['cart_key'];
        $CartList = json_decode($_SESSION[$cart_key], true);
        $total_info = get_order_total_info($this->UsersID, $CartList, $Shipping_IDS, $City_Code);
        $Data = array();
        if ($Biz_ID != 0) {
            $Data['biz_shipping_name'] = $total_info[$Biz_ID]['Shipping_Name'];
            $Data['biz_shipping_fee'] = $total_info[$Biz_ID]['total_shipping_fee'];
        }
        $Data['status'] = 1;
        $Data['total'] = $total_info['total'];
        $Data['total_shipping_fee'] = $total_info['total_shipping_fee'];
        return $Data;
    }
    private function _address_list() {
        $condition = array(
            'Users_ID' => $this->UsersID,
            'User_ID' => $_SESSION[$this->UsersID . 'User_ID'],
        );
		
        $rsList = model('user_address')->field('*')->where($condition)->select();
		$area_json = read_file(SITE_PATH . '/data/area.js');
        $area_array = json_decode($area_json, TRUE);
		$province_list = $area_array[0];
		foreach($rsList as $key => $val){
			$rsList[$key]['Province_name'] = $province_list[$val['Address_Province']];
			$rsList[$key]['City_name'] = $area_array['0,' . $val['Address_Province']][$val['Address_City']];
			$rsList[$key]['Area_name'] = $area_array['0,' . $val['Address_Province'] . ',' . $val['Address_City']][$val['Address_Area']];
		}
        if ($rsList) {
            $Data = array(
                'status' => 1,
                'list' => $rsList
            );
        } else {
            $Data = array(
                'status' => 0,
                'list' => array()
            );
        }
        return $Data;
    }
    private function _change_address() {
        $Address_ID = $_POST['AddressID'];
		$address_model = model('user_address');
        $address_model->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->update(array('Address_Is_Default'=>'0'));
		$address_model->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Address_ID'=>$Address_ID))->update(array('Address_Is_Default'=>1));
		
        $rsAddress = $address_model->field('*')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Address_ID'=>$Address_ID))->find();
		
		$area_json = read_file(SITE_PATH . '/data/area.js');
        $area_array = json_decode($area_json, TRUE);
		$province_list = $area_array[0];
		
		$rsAddress['Province_name'] = $province_list[$rsAddress['Address_Province']];
		$rsAddress['City_name'] = $area_array['0,' . $rsAddress['Address_Province']][$rsAddress['Address_City']];
		$rsAddress['Area_name'] = $area_array['0,' . $rsAddress['Address_Province'] . ',' . $rsAddress['Address_City']][$rsAddress['Address_Area']];
        if ($rsAddress) {
            $Data = array(
                'status' => 1,
                'address' => $rsAddress
            );
        } else {
            $Data = array(
                'status' => 0,
                'address' => array() ,
                'msg' => '非法操作'
            );
        }
        return $Data;
    }
    private function _diyong() {
       
            $this->rsUser = model('user')->field('*')->where(array('User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find(); //dump($this->rsUser);die;
	    $OrderID = empty($_POST['Order_ID']) ? 0 : $_POST['Order_ID'];
	    $rsOrder = model('user_order')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$OrderID))->find();
		$total = $rsOrder['order_totalprice'];
		$diyong_flag = false;
		$diyong_list = json_decode(htmlspecialchars_decode($this->shopConfig['integral_use_laws']), true);
		$diyong_intergral = 0;
		//用户设置了积分抵用规则，且抵用率大于零 
		if(count($diyong_list) > 0 && $this->shopConfig['integral_buy'] > 0) {
			$diyong_intergral = diyong_act($total, $diyong_list, $this->rsUser['User_Integral']);
			//如果符合抵用规则中的某一个规则,且此订单之前未执行过抵用操作
			if($diyong_intergral > 0 && $rsOrder['integral_consumption'] == 0 && $this->rsUser['User_Integral'] > 0) {
				$diyong_flag = true;
			}
		}
		
		if($diyong_flag){
		    if($this->rsUser['User_Integral'] > $diyong_intergral){
		        $Integral_Money = $diyong_intergral/$this->shopConfig['integral_buy'];
			}else{
			    $Integral_Money = $this->rsUser['User_Integral']/$this->shopConfig['integral_buy'];
			}
			//记录此订单消耗多少积分
			$data = 'Integral_Consumption=' . $diyong_intergral . ',Integral_Money=' . $Integral_Money . ',Order_TotalPrice=Order_TotalPrice-' . $Integral_Money;
			$condition = array(
				'Users_ID'=>$this->UsersID,
				'Order_ID'=>$OrderID,
			);
			$Flag_a = model('user_order')->where($condition)->update($data);
			//将积分移入不可用积分
			$Flag_b = $Flag_a && add_userless_integral($this->UsersID, $this->rsUser, $diyong_intergral);
						
			if($Flag_b) {
				$response = array('status'=>1, 'msg'=>'抵用消耗记录到订单成功', 'total_price'=>$total-$Integral_Money);
			}else {
				$response = array('status'=>0, 'msg'=>'抵用消耗记录到订单失败');
			}
		}else{
		    $response = array('status'=>0, 'msg'=>'非法操作！');
		}
		return $response;
    }
    private function _checkout() {
        $cart_key = $this->UsersID . $_POST['cart_key'];
        if (empty($_SESSION[$cart_key])) {
            $tip = array(
                'status' => 0,
                'msg' => '购物车空的，赶快去逛逛吧！'
            );
            echo json_encode($tip, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $Data = array(
            'Users_ID' => $this->UsersID,
            'User_ID' => $_SESSION[$this->UsersID . 'User_ID'],
			'Order_Type' => 'shop',
        );
		
        if (($cart_key == $this->UsersID . 'CartList' || $cart_key == $this->UsersID . 'DirectBuy') && $this->shopConfig['needshipping']) {
            $AddressID = isset($_POST['AddressID']) ? $_POST['AddressID'] : 0;
            $rsAddress = model('user_address')->where(array(
                'Users_ID' => $this->UsersID,
                'User_ID' => $_SESSION[$this->UsersID . 'User_ID'],
                'Address_Is_Default' => 1
            ))->find();
			if ($rsAddress) {//是否需要物流
                $Data['Address_Name'] = $rsAddress['address_name'];
                $Data['Address_Mobile'] = $rsAddress['address_mobile'];
                $Data['Address_Province'] = $rsAddress['address_province'];
                $Data['Address_City'] = $rsAddress['address_city'];
                $Data['Address_Area'] = $rsAddress['address_area'];
                $Data['Address_Detailed'] = $rsAddress['address_detailed'];
				$City_Code = empty($_POST['City_Code']) ? 0 : $_POST['City_Code'];
				$Shipping_IDS = empty($_POST['Biz_Shipping_ID']) ? array() : array_values($_POST['Biz_Shipping_ID']);
            }
        } else {
            $Data['Order_IsVirtual'] = 1;
            $Data['Order_IsRecieve'] = $_POST ['recieve'];
            $Data['Address_Mobile'] = empty($_POST['Mobile']) ? '' : $_POST['Mobile'];
			$City_Code = 0;
			$Shipping_IDS = array();
        }
       
        $OrderCart = $_SESSION[$cart_key];
        $CartList = json_decode($OrderCart, true);
        $CartList = get_filter_cart_list($CartList);
		
		$order_total_info = get_order_total_info($this->UsersID, $CartList, $Shipping_IDS, $City_Code);
        //生成订单
        $orderids = array();
        $pre_total = 0;
        $pre_sn = build_pre_order_no();
        $pre_sn = 'PRE' . $pre_sn;
        $Data['Owner_ID'] = $this->owner['id'];
        //是否加入分销记录
        $is_distribute = true;
        $error = false;
		
        foreach ($CartList as $Biz_ID => $BizCart) {
            $Data['Biz_ID'] = $Biz_ID;
            $Data['Order_Remark'] = $_POST['Remark'][$Biz_ID];
            //整理快递信息
            if ($cart_key == $this->UsersID . 'CartList' || $cart_key == $this->UsersID . 'DirectBuy') {
				if (empty($order_total_info[$Biz_ID]['error'])) {
                    $express = $order_total_info[$Biz_ID]['Shipping_Name'];
                    $price = $order_total_info[$Biz_ID]['total_shipping_fee'];
                    $shipping = array(
                        'Express' => $express,
                        'Price' => $price
                    );
                } else {
                    $shipping = array();
                }
            } else {
                $shipping = array(
                    'Express' => '',
                    'Price' => 0
                );
            }
            $Data['Order_Shipping'] = json_encode($shipping, JSON_UNESCAPED_UNICODE);
            $Data['Order_CartList'] = json_encode($BizCart, JSON_UNESCAPED_UNICODE);
            
            $Data['Order_TotalAmount'] = $order_total_info[$Biz_ID]['total'] + $order_total_info[$Biz_ID]['total_shipping_fee'];
            $Data['Order_NeedInvoice'] = isset($_POST['Order_NeedInvoice'][$Biz_ID]) ? $_POST['Order_NeedInvoice'][$Biz_ID] : 0;
            $Data['Order_InvoiceInfo'] = isset($_POST['Order_InvoiceInfo'][$Biz_ID]) ? $_POST['Order_InvoiceInfo'][$Biz_ID] : '';
            $Data['Order_TotalPrice'] = $order_total_info[$Biz_ID]['total'] + $order_total_info[$Biz_ID]['total_shipping_fee'];
            $pre_total += $Data['Order_TotalPrice'];
            $Data['Order_CreateTime'] = time();
            $Data['Order_Status'] = $this->shopConfig['checkorder'] == 1 ? 1 : 0;
            $neworderid = model('user_order')->insert($Data);
            if ($neworderid) {
                $orderids[] = $neworderid;
                //更新销售记录
                foreach ($BizCart as $kk => $vv) {
                    $qty = 0;
                    foreach ($vv as $k => $v) {
                        $qty += $v['Qty'];
                        //加入分销记录
                        if ($v['OwnerID'] > 0) {
                            add_distribute_record($this->UsersID, $v['OwnerID'], $v['ProductsPriceX'], $kk, $v['Qty'], $neworderid, $v['ProductsProfit'], $k);
                        }
                    }
                    $condition = array(
                        'Users_ID' => $this->UsersID,
                        'Products_ID' => $kk
                    );
                    model('shop_products')->where($condition)->update('Products_Sales=Products_Sales+' . $qty . ',Products_Count=Products_Count-' . $qty);
                }
            } else {
                $error = true;
            }
        }
        $_SESSION[$cart_key] = '';
        if ($error) {
            $Data = array(
                'status' => 0
            );
        } else {
            if ($this->shopConfig['checkorder'] == 1) {
                $Data = array(
                    'usersid' => $this->UsersID,
                    'userid' => $_SESSION[$this->UsersID . 'User_ID'],
                    'pre_sn' => $pre_sn,
                    'orderids' => implode(',', $orderids) ,
                    'total' => $pre_total,
                    'createtime' => time() ,
                    'status' => 1
                );
                $flag = model('user_pre_order')->insert($Data);
                if ($flag) {
                    $url = url('payment/index', array(
                        'OrderID' => $neworderid
                    ));
                    $Data = array(
                        'url' => $url,
                        'status' => 1
                    );
                } else {
                    $Data = array(
                        'msg' => '订单提交失败',
                        'status' => 0
                    );
                }
            } else {
                $url = url('member/status', array(
                    'Status' => 0
                ));
                $Data = array(
                    'url' => $url,
                    'status' => 1
                );
            }
        }
        return $Data;
    }
	function _payment() {
		$OrderID = empty($_POST['OrderID']) ? 0 : $_POST['OrderID'];
		$orderids = array();
		if(strpos($OrderID, 'PRE') !== false) {
			$pre_order = model('user_pre_order')->where(array('pre_sn'=>$OrderID,'usersid'=>$this->UsersID,'userid'=>$_SESSION [$this->UsersID . 'User_ID']))->find();
			$orderids = explode(',', $pre_order['orderids']);
			$order_status = $pre_order['status'];
			$order_total = $pre_order['total'];
		}else {
			$orderids[] = $OrderID;
			$rsOrder = model('user_order')->where(array('Users_ID'=>$this->UsersID,'Order_ID'=>$OrderID))->find();
			$order_status = $rsOrder["order_status"];
			$order_total = $rsOrder["order_totalprice"];
		}
		$rsUser = model('user')->field('User_Money,User_PayPassword,Is_Distribute,User_Name,User_NickName,Owner_Id,User_Integral')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->find();
		
		$PaymentMethod = array(
			'微支付' => '1',
			'支付宝' => '2',
			'线下支付' => '3',
			'易宝支付' => '4',
			'余额支付' => '5'
		);

		if($_POST['PaymentMethod'] == '线下支付' || $order_total <= 0){
			$Data = array(
				"Order_PaymentMethod"=>$_POST['PaymentMethod'],
				"Order_PaymentInfo"=>$_POST['PaymentInfo'],
				//"Order_DefautlPaymentMethod"=>$_POST["DefautlPaymentMethod"],
				"Order_Status"=>1
			);
			
			$Status = 1;
			$flag = model('user_order')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$orderids))->update($Data);
			$url = url('member/status', array('Status'=>$Status));
			if($flag) {
				$Data = array(
					'status'=>1,
					'url'=>$url
				);
			}else {
				$Data = array(
					'status'=>0,
					'msg'=>'线下支付提交失败'
				);
			}		
			
		}else if($_POST['PaymentMethod'] == '余额支付' && $rsUser['User_Money'] >= $order_total){//余额支付
			//增加资金流水
			if($order_status != 1) {
				$Data = array(
					'status'=>0,
					'msg'=>'该订单状态不是待付款状态，不能付款'
				);
			}elseif(!$_POST['PayPassword']) {
				$Data = array(
					'status'=>0,
					'msg'=>'请输入支付密码'
				);
				
			}elseif(md5($_POST['PayPassword']) != $rsUser['User_PayPassword']) {
				$Data = array(
					'status'=>0,
					'msg'=>'支付密码输入错误'
				);
			}else {
				$Data = array(
					'Users_ID' => $this->UsersID,
					'User_ID' => $_SESSION [$this->UsersID . 'User_ID'],
					'Type' => 0,
					'Amount' => $order_total,
					'Total' => $rsUser ['User_Money'] - $order_total,
					'Note' => '商城购买支出 -' . $order_total . ' (订单号:' . implode(',', $orderids) . ')',
					'CreateTime' => time () 		
				);
				$Flag = model('user_money_record')->insert($Data);
				//更新用户余额
				$Data = array(				
					'User_Money' => $rsUser['User_Money'] - $order_total				
				);
				$Flag = model('user')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID']))->update($Data);
				
				$Data = array(
					'Order_PaymentMethod'=>$_POST['PaymentMethod'],
					'Order_PaymentInfo'=>'',
					'Order_DefautlPaymentMethod'=>$_POST['DefautlPaymentMethod']	
				);
				model('user_order')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION[$this->UsersID . 'User_ID'],'Order_ID'=>$orderids))->update($Data);
				$pay_order = new \shop\controller\pay_orderController($OrderID);
				$Data = $pay_order->make_pay();
			}			
		} else {//在线支付
			$Data = array(
				'Order_PaymentMethod'=>$_POST['PaymentMethod'],
				'Order_PaymentInfo'=>'',
				'Order_DefautlPaymentMethod'=>$_POST['DefautlPaymentMethod']
			);
			$Flag = model('user_order')->where(array('Users_ID'=>$this->UsersID,'User_ID'=>$_SESSION [$this->UsersID . 'User_ID'],'Order_ID'=>$orderids))->update($Data);
			
			$url = url('payment/pay',array('OrderID'=>$OrderID,'Method'=>$PaymentMethod [$_POST ['PaymentMethod']]));
			
			if($Flag) {
				$Data = array(
					'status'=>1,
					'url'=>$url
				);
			}else {
				$Data = array(
					'status'=>0,
					'msg'=>'在线支付出现错误'
				);
			}	
		}
		return $Data;
	}
}
?>