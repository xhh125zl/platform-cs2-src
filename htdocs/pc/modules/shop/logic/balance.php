<?php
namespace shop\logic;
class balance{
	private $usersid;

	function __construct($usersid) {
		$this->usersid = $usersid;	
	}
	
	public function add_sales($orderid) {
		$item = model('user_order')->field('*')->where(array('Order_ID'=>$orderid,'Order_Status'=>4,'Users_ID'=>$this->usersid))->find();

		$rs = model()->query('select SUM(b.Record_Money) as bonus from distribute_account_record as b LEFT JOIN distribute_record as r ON b.Ds_Record_ID=r.Record_ID where r.Order_ID=' . $orderid . ' limit 0,1', 'FIND');

		$bonus = $rs['bonus'] ? $rs['bonus'] : 0;

		if($item) {
			$Shipping = json_decode(htmlspecialchars_decode($item['Order_Shipping']), true);
			$Data = array(
				'Users_ID'=>$this->usersid,
				'Order_ID'=>$orderid,
				'Order_Json'=>$item['Order_CartList'],
				'Biz_ID'=>$item['Biz_ID'],
				'Bonus'=>$bonus,
				'Order_Amount'=>$item['Order_TotalAmount']-$item['Back_Amount'],
				'Order_Diff'=>$item['Coupon_Cash'],
				'Order_Shipping'=>empty($Shipping['Price']) ? 0 : $Shipping['Price'],
				'Order_TotalPrice'=>$item['Order_TotalPrice']-$item['Back_Amount'],
				'Record_CreateTime'=>time()
			);
			model('shop_sales_record')->insert($Data);
		}
	}
	
	public function get_sales_record($condition){//销售记录$type=1 销售详情 $type=0 辅助付款单生成
		$lists = array();
		$lists_tmp = model('shop_sales_record')->field('*')->where($condition)->select();
		foreach($lists_tmp as $r) {
			$lists[$r['Record_ID']] = $r;
		}
		return $lists;
	}
	
	public function create_payment($condition){//付款单生成详情
		$lists = $this->get_sales_record($condition);
		$products = array();
		$products_num = $sales = $alltotal = $cash = $total = $bonus = $web_total = $logistic = 0;
		foreach($lists as $key => $value){
			$cartlist = json_decode($value['Order_Json'], true);
			foreach($cartlist as $kk => $vv){
				foreach($vv as $k => $v){
					if(empty($products[$kk])){
						$products[$kk]['num'] = $v['Qty'];
						$products[$kk]['total'] = $v['Qty'] * $v['ProductsPriceX'];
						$products[$kk]['web'] = $v['Qty'] * $v['ProductsProfit'];
					}else{
						$products[$kk]['num'] += $v['Qty'];
						$products[$kk]['total'] += $v['Qty'] * $v['ProductsPriceX'];
						$products[$kk]['web'] += $v['Qty'] * $v['ProductsProfit'];
					}
					$sales += $v['Qty'];
					$web_total += $v['Qty'] * $v['ProductsProfit'];
				}
			}
			
			$alltotal += $value['Order_Amount'];
			$cash += $value['Order_Diff'];
			$bonus = $value['Bonus'];
			$total += $value['Order_TotalPrice'];
			$logistic += $value['Order_Shipping'];
		}
		
		$data = array(
			'products_num'=>count($products),
			'sales'=>$sales,
			'alltotal'=>$alltotal,
			'logistic'=>$logistic,
			'cash'=>$cash,
			'web'=>$web_total,
			'bonus'=>$bonus,
			'total'=>$total,
			'products'=>$products,
			'supplytotal'=>$total-$web_total
		);
		return $data;
	}
	
	function repeat_list($lists){
		$user_order = model('user_order');
		foreach($lists as $id => $item){
			$r = $user_order->field('Order_CreateTime')->where(array('Users_ID'=>$this->usersid,'Order_ID'=>$item['Order_ID']))->find();
			$item['orderno'] = date('Ymd', $r['Order_CreateTime']) . $item['Order_ID'];
			$item['bonus'] = $item['Bonus'];
			$cartlist = json_decode($item['Order_Json'], true);
			$amount = $web = 0;
			foreach($cartlist as $key => $value){
				foreach($value as $kk => $vv){
					$amount += $vv['Qty'] * $vv['ProductsPriceX']; //商品总额
					$web += $vv['Qty'] * $vv['ProductsProfit']; //网站提成(网站所得+佣金)
				}
			}
			$item['web'] = $web;
			$item['product_amount'] = $amount;
			$item['supplytotal'] = $item['Order_TotalPrice'] - $item['web'];//结算价
			$lists[$id] = $item;
		}
		return $lists;
	}
	
	
	private function rmb_format($money = 0, $is_round = true, $int_unit = '元') {
		$chs     = array (0, '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
		$uni     = array ('', '拾', '佰', '仟' );
		$dec_uni = array ('角', '分' );
		$exp     = array ('','万','亿');
		$res     = '';
		// 以 元为单位分割
		$parts   = explode ( '.', $money, 2 );
		$int     = isset ( $parts [0] ) ? strval ( $parts [0] ) : 0;
		$dec     = isset ( $parts [1] ) ? strval ( $parts [1] ) : '';
		// 处理小数点
		$dec_len = strlen ( $dec );
		if (isset ( $parts [1] ) && $dec_len > 2) {
			$dec = $is_round ? substr ( strrchr ( strval ( round ( floatval ( '0.' . $dec ), 2 ) ), '.' ), 1 ) : substr ( $parts [1], 0, 2 );
		}
		// number= 0.00时，直接返回 0
		if (empty ( $int ) && empty ( $dec )) {
			return '零';
		}
		
		// 整数部分 从右向左
		for($i = strlen ( $int ) - 1, $t = 0; $i >= 0; $t++) {
			$str = '';
			// 每4字为一段进行转化
			for($j = 0; $j < 4 && $i >= 0; $j ++, $i --) {
				$u   = $int{$i} > 0 ? $uni [$j] : '';
				$str = $chs [$int {$i}] . $u . $str;
			}
			$str = rtrim ( $str, '0' );
			$str = preg_replace ( '/0+/', '零', $str );
			$u2  = $str != '' ? $exp [$t] : '';
			$res = $str . $u2 . $res;
		}
		$dec = rtrim ( $dec, '0' );
		// 小数部分 从左向右
		if (!empty ( $dec )) {
			$res .= $int_unit;
			$cnt =  strlen ( $dec );
			for($i = 0; $i < $cnt; $i ++) {
				$u = $dec {$i} > 0 ? $dec_uni [$i] : ''; // 非0的数字后面添加单位
				$res .= $chs [$dec {$i}] . $u;
			}
			if ($cnt == 1) $res .= '整';
			$res = rtrim ( $res, '0' ); // 去掉末尾的0
			$res = preg_replace ( '/0+/', '零', $res ); // 替换多个连续的0
		} else {
			$res .= $int_unit . '整';
		}
		return $res;
	}
	
	function echo_payment_info($condition,$array){
		$data = $this->get_sales_record($condition);
		if(count($data)==0){
			echo '';
			exit;
		}
		
		$data = $this->repeat_list($data);
		
		$html = '<div id="printPage"><table cellspacing="0" cellpadding="0" width="90%" style="border:2px #000 solid;">';
		
		/**/
		$html .= '
		<tr>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体;font-size:12pt; color:#000">单号</td>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$array["Payment_Sn"].'</td>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">时间</td>
		  <td style="text-align:center; height:28pt; width:25%; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.date("Y-m-d H:i:s",$array["CreateTime"]).'</td>
		</tr>
		<tr>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体;font-size:12pt; color:#000">商家名称</td>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$array["Biz"].'</td>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">结算时间</td>
		  <td style="text-align:center; height:28pt; width:25%; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.date("Y-m-d",$array["FromTime"]).' - '.date("Y-m-d",$array["EndTime"]).'</td>
		</tr>
		<tr>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体;font-size:12pt; color:#000">合计金额</td>
		  <td colspan="3" style="text-align:left; padding-left:15px; height:30pt; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">人民币：（大写）'.$this->rmb_format($array["Total"],false).'  ￥'.$array["Total"].'</td>
		</tr>
		<tr>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体;font-size:12pt; color:#000">收款银行</td>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$array["Bank"].'</td>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">银行卡号</td>
		  <td style="text-align:center; height:28pt; width:25%; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$array["BankNo"].'</td>
		</tr>
		<tr>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体;font-size:12pt; color:#000">收款人</td>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$array["BankName"].'</td>
		  <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">收款人手机</td>
		  <td style="text-align:center; height:28pt; width:25%; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$array["BankMobile"].'</td>
		</tr>
		<tr>
		    <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; border-bottom:1px #000 solid; font-family:宋体;font-size:12pt; color:#000">备注</td>
		    <td style="text-align:center; height:30pt; width:25%; border-bottom:1px #000 solid; font-family:宋体; font-size:12pt; color:#000" colspan="3"></td>
		</tr>
		<tr>
		    <td style="text-align:center; height:30pt; width:25%; border-right:1px #000 solid; font-family:宋体;font-size:12pt; color:#000">审批</td>
		    <td style="text-align:center; height:30pt; width:25%; font-family:宋体; font-size:12pt; color:#000" colspan="3"></td>
		</tr>
		';
		$html .= '</table>';
		$html .= '<table cellspacing="0" cellpadding="0" width="90%" style="border:1px #000 solid; margin-top:30px;">';
		$html .= '
		<tr>
		  <td style="text-align:center; height:30pt; width:25%; border-bottom:1px #000 solid; font-family:宋体;font-size:12pt; color:#000" colspan="8">'.$array["Payment_Sn"].'号付款单销售明细</td>
		</tr>';
		$html .= '
			<tr>
			<td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">订单号</td>
			<td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">商品总额</td>
			<td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">运费费用</td>
			<td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">优惠金额</td>
			<td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">订单实收</td>
			<td style="text-align:center; height:28pt; width:12.5%; border-right:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">网站所得</td>
			<td style="text-align:center; height:28pt; width:12.5%; border-right:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">分销佣金</td>
			<td style="text-align:center; height:28pt; width:12.5%; font-family:宋体; font-size:12pt; color:#000">结算金额</td>
			</tr>
			';
		$b0 = $b1 = $b2 = $b3 = $b4 = $b5 = $b6 = $b7 = 0;
		foreach($data as $recordid=>$value){
			$b0 += $value["product_amount"];
			$b1 += $value["Order_Shipping"];
			$b2 += $value["Order_Amount"];
			$b3 += $value["Order_Diff"];
			$b4 += $value["Order_TotalPrice"];
			$b5 += $value["web"]-$value["bonus"];
			$b6 += $value["bonus"];
			$b7 += $value["supplytotal"];
			$html .= '
			<tr>
		  <td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; border-top:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$value["orderno"].'</td>
		  <td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; border-top:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$value["product_amount"].'</td>
		  <td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; border-top:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$value["Order_Shipping"].'</td>
		  <td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; border-top:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$value["Order_Diff"].'</td>
		  <td style="text-align:center; height:30pt; width:12.5%; border-right:1px #000 solid; border-top:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$value["Order_TotalPrice"].'</td>
		  <td style="text-align:center; height:28pt; width:12.5%; border-right:1px #000 solid; border-top:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.($value["web"]-$value["bonus"]).'</td>
		  <td style="text-align:center; height:28pt; width:12.5%; border-right:1px #000 solid; border-top:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$value["bonus"].'</td>
		  <td style="text-align:center; height:28pt; width:12.5%; border-top:1px #000 solid; font-family:宋体; font-size:12pt; color:#000">'.$value["supplytotal"].'</td>
		  </tr>
			';
		}
		$html .= '</table></div>';
		echo $html;
	}
}
?>
