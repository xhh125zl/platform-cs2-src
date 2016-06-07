<?php
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/Framework/Conn.php');
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/Framework/Ext/property.func.php');
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/include/library/smarty.php');
require_once ($_SERVER ["DOCUMENT_ROOT"] . '/include/helper/shipping.php');

// 设置smarty
$smarty->left_delimiter = "{{";
$smarty->right_delimiter = "}}";
$template_dir = $_SERVER ["DOCUMENT_ROOT"] . '/biz/html';
$smarty->template_dir = $template_dir;

if ($_REQUEST ["action"]) {
	switch ($_REQUEST  ["action"]) {
		case 'property' :
			$UsersID = $_POST ["UsersID"];
			$BizID = intval ( $_POST ["BizID"] );
			$MuluID = intval ( $_POST ["MuluID"] );
			$ProductsID = intval ( $_POST ["ProductsID"] );
			$html = get_property ( $UsersID, $BizID, $MuluID, $ProductsID );
			$Data = array (
					"status" => 1,
					"msg" => $html 
			);
			break;
		
		case 'mulu' :
			$BizID = intval ( $_POST ["BizID"] );
			$MuluID = isset ( $_POST ["MuluID"] ) ? intval ( $_POST ["MuluID"] ) : 0;
			$html = '<label>产品目录：</label>
              <span class="input">
              <select name="MuluID" id="Mulu_ID" style="width:180px;">
               <option value="0">全部目录</option>';
			$DB->get ( "weicbd_mulu", "*", "where Biz_ID=" . $BizID . " order by Mulu_Index asc" );
			while ( $r = $DB->fetch_assoc () ) {
				$html .= '<option value="' . $r ["Mulu_ID"] . '"' . ($r ["Mulu_ID"] == $MuluID ? " selected" : "") . '>' . $r ["Mulu_Name"] . '</option>';
			}
			$html .= '</select></span><div class="clear"></div>';
			$Data = array (
					"status" => 1,
					"msg" => $html 
			);
			break;
		
		case 'get_deliver_content' :
			$shipping_company = $_GET ['Shipping_ID'];
			$shipping_by_method = $_GET ['By_Method'];
			$content = build_shipping_section_html ( $smarty, $_SESSION ['Users_ID'], $shipping_company, $shipping_by_method );
			$Data = array (
					'status' => 1,
					'content' => $content,
					'msg' => '获取信息成功' 
			);
			
			break;
		
		case 'get_shipping_company_edit_form' :
			
			// 获取所要编辑快递公司信息
			$condition = "where Users_ID='" . $_SESSION ["Users_ID"] . "' and Shipping_ID= '" . $_GET ['Shipping_ID'] . "'";
			$rsShipping = $DB->getRs ( 'shop_shipping_company', '*', $condition );
			
			$Business_Name_List = array (
					'express' => '快递',
					'common' => '平邮' 
			);
			
			$Business_List = array ();
			$Business_Checked_List = explode ( ',', $rsShipping ['Shipping_Business'] );
			
			foreach ( $Business_Name_List as $key => $item ) {
				$Business_List [$key] ['Name'] = $item;
				if (in_array ( $key, $Business_Checked_List )) {
					$Business_List [$key] ['Checked'] = 1;
				} else {
					$Business_List [$key] ['Checked'] = 0;
				}
			}
			
			$smarty->assign ( 'Shipping', $rsShipping );
			$smarty->assign ( 'Business_List', $Business_List );
			$content = $smarty->fetch ( 'shipping_company_edit_form.html' );
			$Data = array (
					"status" => 1,
					'content' => $content 
			);
			break;
	}
	
	echo json_encode ( $Data, JSON_UNESCAPED_UNICODE );
}
?>