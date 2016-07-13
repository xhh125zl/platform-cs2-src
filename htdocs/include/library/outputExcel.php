<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$

/**
 *
 * 将指定信息导出为Excel...
 * 其中包括
 * 	1.采购汇总表
 *  2.订单结算汇总表
 *  3.订单明细打印单
 *  4.退货汇总表
 *  5.订单详细汇总表
 *  6.客户明细汇总表
 *  7.产品详细汇总表
 * @author JohnKuo
 *
 */
//加载所需类
include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';
class OutputExcel {
    private $templates = array(
        'product_gross_info' => 'product_gross_info.xls',
        'order_detail_list' => 'order_detail_list.xls',
        'sales_record_list' => 'sales_record_list.xls',
        'users_pintuan_list' => 'users_pintuan_list.xls'
    );
    private $_objPHPExcel;
    private $_objReader;
    private $_objWriter;
    private $template_path;
    private $cur_row = 5; //excel所应输出数据到的当前行号
    
    /**
     *
     * 构造函数 ...
     */
    function __construct() {
        $this->_objPHPExcel = new PHPExcel();
        $this->_objReader = PHPExcel_IOFactory::createReader('Excel5');
        $this->_objWriter = new PHPExcel_Writer_Excel2007($this->_objPHPExcel);
        $this->template_path = $_SERVER["DOCUMENT_ROOT"] . '/data/excel_template/';
    }
    /**
     *
     * 生成 产品详细汇总表...
     * @param string $beingTime 开始时间
     * @param string $endTime 结束时间
     * @param array $data 需要填充的数据
     * @param int $offset 开始填充的数据行数
     */
    public function product_gross_info($data) {
        $this->_objPHPExcel = $this->_objReader->load($this->template_path . $this->templates['product_gross_info']);
        $objActSheet = $this->_objPHPExcel->getActiveSheet();
        //填充数据
        $baserow = 4;
        foreach ($data as $key => $product) {
            $row = $baserow + $key;
			$objActSheet->setCellValue('A' . $row, $key);
            $objActSheet->setCellValue('B' . $row, $product['Products_ID']);
            $objActSheet->setCellValue('C' . $row, $product['Products_Name']);
			$objActSheet->setCellValue('D' . $row, $product['Products_Property']);
            $objActSheet->setCellValue('E' . $row, $product['Products_Count']);
            $objActSheet->setCellValue('F' . $row, $product['Products_PriceX']);
            $objActSheet->setCellValue('G' . $row, $product['Products_Category']);
			$objActSheet->setCellValue('H' . $row, $product['Biz_Name']);
        }
        //设置单元格边框
        if (count($data) > 0) {
            $this->_objPHPExcel->getActiveSheet()->getStyle('A5:H' . $row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
        //$this->_objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleThinBlackBorderOutline);
        $filename = 'product_gross_info.xls';
        $this->__outputExcel($this->_objPHPExcel, $filename);
    }
    /*导出所有订单明细列表*/
    public function order_detail_list($beinTime, $endTime, $data) {
        $this->_objPHPExcel = $this->_objReader->load($this->template_path . $this->templates['order_detail_list']);
        $objActSheet = $this->_objPHPExcel->getActiveSheet();
        //设置日期
        $objActSheet->setCellValue('B2', $beinTime);
        $objActSheet->setCellValue('B3', $endTime);
        //输出信息
        //填充数据
        foreach ($data as $key => $order) {
            $order['Order_TotalPrice'] = $order['Order_TotalPrice'] >= $order['Back_Amount'] ? ($order['Order_TotalPrice'] - $order['Back_Amount']) : 0;
            $objActSheet->setCellValue('A' . $this->cur_row, ($key + 1));
            $objActSheet->setCellValue('B' . $this->cur_row, $order['Biz_Name']);
            $objActSheet->setCellValue('C' . $this->cur_row, $order['Address_Name']);
            $objActSheet->setCellValue('D' . $this->cur_row, $order['Address_Mobile']);
            $objActSheet->setCellValue('E' . $this->cur_row, date("Y-m-d H:i:s", $order['Order_CreateTime']));
            $objActSheet->setCellValue('F' . $this->cur_row, date("Ymd", $order['Order_CreateTime']) . $order['Order_ID']);
            $objActSheet->setCellValue('G' . $this->cur_row, $order['Order_TotalPrice']);
            $objActSheet->setCellValue('H' . $this->cur_row, $order['Order_Remark']);
            $Shipping = json_decode(htmlspecialchars_decode($order['Order_Shipping']), true);
            if (!empty($Shipping)) {
                $Shipping_Name = !empty($Shipping["Express"]) ? $Shipping["Express"] : '';
            } else {
                $Shipping_Name = '';
            }
            
            /*循环输出产品*/
            $cart_num = count($order['Order_CartList']);
            if ($cart_num > 0) {
                foreach ($order['Order_CartList'] as $key => $item) {
                    foreach ($item as $k => $v) {
                        $property = "";
						if(!empty($v["Property"])){
							foreach ($v["Property"] as $Attr_ID => $Attr) {
								$property.= $Attr['Name'] . ': ' . $Attr['Value'] . ';  ';
							}
						}
                        $objActSheet->setCellValue('I' . $this->cur_row, !empty($v['ProductsName']) ? $v['ProductsName'] : '');
                        $objActSheet->setCellValue('J' . $this->cur_row, $property);
                        $objActSheet->setCellValue('K' . $this->cur_row, !empty($v['Qty']) ? $v['Qty'] : '');
                        $objActSheet->setCellValue('L' . $this->cur_row, !empty($v['ProductsPriceX']) ? $v['ProductsPriceX'] : '');
                        if ($cart_num > 1) {
                            $this->cur_row = $this->cur_row + 1;
                        }
                    }
                }
            }
			$objActSheet->setCellValue('M' . $this->cur_row, $Shipping_Name);
            $objActSheet->setCellValue('N' . $this->cur_row, $order['receiver_address']);
            $this->cur_row = $this->cur_row + 1;
        }
        $filename = 'order_detail_list' . $beinTime . '_' . $endTime . '.xls';
        $this->__outputExcel($this->_objPHPExcel, $filename);
    }
    /*导出所有销售记录明细列表*/
    public function sales_record_list($beinTime, $endTime, $data) {
        $this->_objPHPExcel = $this->_objReader->load($this->template_path . $this->templates['sales_record_list']);
        $objActSheet = $this->_objPHPExcel->getActiveSheet();
        //设置日期
        $objActSheet->setCellValue('B2', $beinTime);
        $objActSheet->setCellValue('B3', $endTime);
        //输出信息
        //填充数据
        $i = 0;
        $_STATUS = array(
            '未结算',
            '已结算'
        );
        foreach ($data as $key => $value) {
            $i++;
            $objActSheet->setCellValue('A' . $this->cur_row, $i);
            $objActSheet->setCellValue('B' . $this->cur_row, $value['Biz_Name']);
            $objActSheet->setCellValue('C' . $this->cur_row, $value['orderno']);
            $objActSheet->setCellValue('D' . $this->cur_row, $value['product_amount']);
            $objActSheet->setCellValue('E' . $this->cur_row, $value['Order_Shipping']);
            $objActSheet->setCellValue('F' . $this->cur_row, $value['Order_Amount']);
            $objActSheet->setCellValue('G' . $this->cur_row, $value['Order_Diff']);
            $objActSheet->setCellValue('H' . $this->cur_row, $value['Order_TotalPrice']);
            $objActSheet->setCellValue('I' . $this->cur_row, $value['web']);
            $objActSheet->setCellValue('J' . $this->cur_row, $value['bonus']);
            $objActSheet->setCellValue('K' . $this->cur_row, $value['supplytotal']);
            $objActSheet->setCellValue('L' . $this->cur_row, $_STATUS[$value["Record_Status"]]);
            $objActSheet->setCellValue('M' . $this->cur_row, date("Y-m-d H:i:s", $value["Record_CreateTime"]));
            $this->cur_row = $this->cur_row + 1;
        }
        $filename = 'sales_record_list' . $beinTime . '_' . $endTime . '.xls';
        $this->__outputExcel($this->_objPHPExcel, $filename);
    }
    
    /*导出所有拼团明细列表*/
    public function order_pintuan_list($beinTime, $endTime, $data) {

        $this->_objPHPExcel = $this->_objReader->load($this->template_path . $this->templates['users_pintuan_list']);
        $objActSheet = $this->_objPHPExcel->getActiveSheet();
        //设置日期
        $objActSheet->setCellValue('B2', $beinTime);
        $objActSheet->setCellValue('B3', $endTime);
        //输出信息
        //填充数据
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
        
        foreach ($data as $key => $order) {
            $order['Order_TotalPrice'] = $order['Order_TotalPrice'] >= $order['Back_Amount'] ? ($order['Order_TotalPrice'] - $order['Back_Amount']) : 0;
            $objActSheet->setCellValue('A' . $this->cur_row, ($key + 1));
            $objActSheet->setCellValue('B' . $this->cur_row, $order['Users_ID']);
            $objActSheet->setCellValue('C' . $this->cur_row, $order['Address_Name']);
            $objActSheet->setCellValue('D' . $this->cur_row, $order['Address_Mobile'].' ');
            $objActSheet->setCellValue('E' . $this->cur_row, date("Y-m-d H:i:s", $order['Order_CreateTime']));
            $objActSheet->setCellValue('F' . $this->cur_row, $order['Order_Code'].' ');
            $objActSheet->setCellValue('G' . $this->cur_row, $order['Order_TotalPrice']);
            $objActSheet->setCellValue('H' . $this->cur_row, $order['Order_PaymentMethod']);
            $Shipping = json_decode(htmlspecialchars_decode($order['Order_Shipping']), true);
            if (!empty($Shipping)) {
                $Shipping_Name = !empty($Shipping["Express"]) ? $Shipping["Express"] : '';
            } else {
                $Shipping_Name = '';
            }
    
            /*循环输出产品*/
            $cart_num = count($order['Order_CartList']);
            if ($cart_num > 0) {
                $temp[] = $order['Order_CartList'];
                foreach ($temp as $key => $item) {
                        $objActSheet->setCellValue('I' . $this->cur_row, !empty($item['ProductsName']) ? $item['ProductsName'] : '');
                        $objActSheet->setCellValue('J' . $this->cur_row, $orderStatus[$order['Order_Status']]);
                        $objActSheet->setCellValue('K' . $this->cur_row, !empty($item['Qty']) ? $item['Qty'] : 1);
                        $objActSheet->setCellValue('L' . $this->cur_row, $order['Order_Type']=='dangou'?$item['ProductsPriceD']:$item['ProductsPriceT']);
                        
                }
            }
            $objActSheet->setCellValue('M' . $this->cur_row, $Shipping_Name);
            $objActSheet->setCellValue('N' . $this->cur_row, $order['receiver_address']);
            $this->cur_row = $this->cur_row + 1;
        }
        $filename = 'order_detail_list' . $beinTime . '_' . $endTime . '.xls';
        $this->__outputExcel($this->_objPHPExcel, $filename);
    }
    
    /**
     *输出这个表格
     *
     */
    private function __outputExcel($objPHPExcel, $type) {
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $type . '"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
}