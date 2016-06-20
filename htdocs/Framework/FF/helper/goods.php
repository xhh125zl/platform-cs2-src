<?php
 /**

  *产品的帮助函数
  */
 
  

/**
 * 根据属性数组创建属性的表单
 *
 * @access  public
 * @param   int     $type_id     商品类型id
 * @param   int     $product_id   商品编号
 * @return  string
 */
function build_attr_html($type_id, $product_id = 0)
{
	
    $attr = get_attr_list_by_typeid($type_id,$product_id);
	
    $html = '<table width="100%" id="attrTable">';
    $spec = 0;
	

    foreach ($attr AS $key => $val)
    {
		
        $html .= "<tr><td class='label'>";
        if ($val['Attr_Type'] == 2||$val['Attr_Type'] == 1)
        {
            $html .= ($spec != $val['Attr_ID']) ?
                "<a href='javascript:;' onclick='addSpec(this)'>[+]</a>" :
                "<a href='javascript:;' onclick='removeSpec(this)'>[-]</a>";
            
			$spec = $val['Attr_ID'];
        }

        $html .= $val['Attr_Name']."</td><td><input type='hidden' name='attr_id_list[]' value='".$val['Attr_ID']."' />";

        if ($val['Attr_Input_Type'] == 0)
        {
            $html .= '<input  notnull name="attr_value_list[]" type="text" value="' .htmlspecialchars($val['Attr_Value']). '" size="40" /> ';
        }
        elseif ($val['Attr_Input_Type'] == 2)
        {
            $html .= '<textarea notnull name="attr_value_list[]" rows="3" cols="40">' .htmlspecialchars($val['Attr_Value']). '</textarea>';
        }
        else
        {
            $html .= '<select  name="attr_value_list[]" notnull>';
            $html .= '<option value="">请选择属性值</option>';

            $attr_values = explode("\n", $val['Attr_Values']);
			
			
            foreach ($attr_values AS $opt)
            {
                $opt    = trim(htmlspecialchars($opt));

                $html   .= ($val['Attr_Value'] != $opt) ?
                    '<option value="' . $opt . '">' . $opt . '</option>' :
                    '<option value="' . $opt . '" selected="selected">' . $opt . '</option>';
            }
            $html .= '</select> ';
        }

	
        $html .= ($val['Attr_Type'] == 1 || $val['Attr_Type'] == 2) ?
             '&nbsp;&nbsp;属性价格&nbsp;&nbsp;<input type="text" notnull name="attr_price_list[]" value="' . $val['Attr_Price'] . '" size="5" maxlength="10" />' :
            ' <input type="hidden" name="attr_price_list[]" value="0" />';

        $html .= '</td></tr>';
    }
	

    $html .= '</table>';
	
    return $html;
}


/**
 * 获得指定的商品类型下所有的属性分组
 *
 * @param   intege $type_id  商品类型ID
 *
 * @return  array
 */
function get_attr_groups($type_id,$Users_ID)
{
	global $DB1;
    $condition = "where Users_ID = '".$Users_ID."' and Type_ID=".$type_id;
    $rsTypes = $DB1->getRs('shop_product_type','Attr_Group',$condition);
	

	if($rsTypes){
		$grp = str_replace("\r", '', $rsTypes['Attr_Group']);
	}else{
		$grp = FALSE;
	}

    if ($grp)
    {
        return explode("\n", $grp);
    }
    else
    {
        return array();
    }
}


/**
 * 获取属性列表
 *
 * @access  public
 * @param
 *
 * @return void
 */
function get_attr_list($Users_ID)
{
	
	$table_attribute = 'shop_attribute';
	$product_type = 'shop_product_type';
	
    $sql = "SELECT a.Attr_ID, A.Type_ID, A.Attr_Name ".
           " FROM " .$table_attribute. " AS a,  ".
           $product_type. " AS t ".
           " WHERE  a.Type_ID = t.Type_ID AND t.Status = 1 ".
           " And a.Users_ID= '".$Users_ID."'".
		   " And t.Users_ID= '".$Users_ID."'".
		   " ORDER BY a.Type_ID , a.Sort_Order";
  	
	global $DB1;
    $rsAttributes = $DB1->query($sql);
	$arr = $DB1->toArray($rsAttributes);
    $list = array();

    foreach ($arr as $val)
    {
        $list[$val['Type_ID']][] = array($val['Attr_ID']=>$val['Attr_Name']);
    }
	
    return $list;
}
	
/**
 * 取得通用属性和某分类的属性，以及某商品的属性值
 * @param   int     $type_id     分类编号
 * @param   int     $products_id   商品编号
 * @return  array   规格与属性列表
 */
function get_attr_list_by_typeid($type_id, $products_id = 0)
{
    if (empty($type_id))
    {
        return array();
    }
	
	$table_attribute = 'shop_attribute';
	$product_attribute = 'shop_products_attr';
	
    // 查询属性值及商品的属性值
    $sql = "SELECT a.Attr_ID, a.Attr_Name, a.Attr_Input_Type, a.Attr_Type, a.Attr_Values, v.Attr_Value, v.Attr_Price ".
            "FROM " .$table_attribute. " AS a ".
            "LEFT JOIN " .$product_attribute. " AS v ".
            "ON v.Attr_ID = a.Attr_ID AND v.products_id = '$products_id' ".
            "WHERE a.Type_ID = " . intval($type_id) ." OR a.Type_ID = 0 ".
            "ORDER BY a.Sort_Order, a.Attr_Type, a.Attr_ID,v.Product_Attr_ID";
	

	global $DB1;
	
	
	$rsProductAttrs = $DB1->query($sql);
	
	if($rsProductAttrs){
		$result = $DB1->toArray($rsProductAttrs);
	}else{
		$result = array();
		
	}
	

    return $result;
}
	
/**
 * 获得商品类型的列表
 *
 * @access  public
 * @param   integer     $selected   选定的类型编号
 * @return  string
 */
function shop_product_type_list($selected,$Users_ID)
{
	
    $sql = "SELECT Type_ID, Type_Name FROM  shop_product_type WHERE Status = 1 and Users_ID='".$Users_ID."'";
    
	global $DB1;
	$res = $DB1->query($sql);
	$type_list = $DB1->toArray($res);
	
    $lst = '';
    foreach($type_list as $key=>$row)
    {
        $lst .= "<option value='$row[Type_ID]'";
        $lst .= ($selected == $row['Type_ID']) ? ' selected="true"' : '';
        $lst .= '>' . htmlspecialchars($row['Type_Name']). '</option>';
    }
	

    return $lst;
}


/**
 *处理产品属性，产品后台信息编辑的时候属性如何
 *
 */
function deal_with_attr($product_id){
	
	$product_type   = !empty($_POST['Type_ID'])?$_POST['Type_ID']:0; 
	global $DB1;
	//处理商品属性
	
	
	
	if ((isset($_POST['attr_id_list']) && isset($_POST['attr_value_list'])) || (empty($_POST['attr_id_list']) && empty($_POST['attr_value_list'])))
    {
        // 取得原有的属性值
        $products_attr_list = array();

        $sql = "SELECT p.*, A.Attr_Type
                FROM  shop_products_attr AS p
                    LEFT JOIN shop_attribute AS a
                        ON a.Attr_ID = p.Attr_ID
                WHERE p.Products_ID = '$product_id'";

        $res = $DB1->query($sql);
		$resulat_array = $DB1->toArray($res); 
	
        foreach($resulat_array as $key=>$row)
        {
            $products_attr_list[$row['Attr_ID']][$row['Attr_Value']] = array('sign' => 'delete', 'product_attr_id' => $row['Product_Attr_ID']);
        }
		
        // 循环现有的，根据原有的做相应处理
        if(isset($_POST['attr_id_list']))
        {
            foreach ($_POST['attr_id_list'] AS $key => $attr_id)
            {
                $attr_value = $_POST['attr_value_list'][$key];
                $attr_price = $_POST['attr_price_list'][$key];
               
			    if (!empty($attr_value))
                {
                    if (isset($products_attr_list[$attr_id][$attr_value]))
                    {
                        // 如果原来有，标记为更新
                        $products_attr_list[$attr_id][$attr_value]['sign'] = 'update';
                        $products_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
                    }
                    else
                    {
                        // 如果原来没有，标记为新增
                        $products_attr_list[$attr_id][$attr_value]['sign'] = 'insert';
                        $products_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
                    }
                    $val_arr = explode(' ', $attr_value);
                    
					
                }
            }
        }
		
		
	

        /* 插入、更新、删除数据 */
        foreach ($products_attr_list as $attr_id => $attr_value_list)
        {
            foreach ($attr_value_list as $attr_value => $info)
            {
                if ($info['sign'] == 'insert')
                {
                    $sql = "INSERT INTO shop_products_attr (Attr_ID, Products_ID, Attr_Value, Attr_Price)".
                            "VALUES ('$attr_id', '$product_id', '$attr_value', '$info[attr_price]')";
                }
                elseif ($info['sign'] == 'update')
                {
                    $sql = "UPDATE shop_products_attr SET Attr_Price = '$info[attr_price]' WHERE Product_Attr_ID = '$info[product_attr_id]' LIMIT 1";
                }
                else
                {
                    $sql = "DELETE FROM shop_products_attr WHERE Product_Attr_ID = '$info[product_attr_id]' LIMIT 1";
                }
				
                $DB1->query($sql);
            }
        }
    }
}



/**
 * 获得商品的属性和规格
 *
 * @access  public
 * @param   integer $product_id
 * @return  array
 */
function get_product_properties($product_id)
{
	$product_type_table = 'shop_product_type';
	$products_table = 'shop_products';	
    $product_attr_table = 'shop_products_attr';
	$attr_table = 'shop_attribute';
	/* 对属性进行重新排序和分组 */
    $sql = "SELECT Attr_Group ".
            "FROM  ".$product_type_table." AS pt, " . $products_table . " AS p ".
            "WHERE p.Products_ID='$product_id' AND pt.Type_ID = p.Products_Type ";
	
	$rsGroup = model()->query($sql, 'find');

	$grp = $rsGroup['Attr_Group'];
	
    if (!empty($grp)) {
        $groups = explode('\n', strtr($grp, '\r', ''));
    }
	
	
    /* 获得商品的规格 */
    $sql = "SELECT a.Attr_ID, a.Attr_Name, a.Attr_Group,a.Attr_Type, ".
                "pa.Product_Attr_Id, pa.Attr_Value, pa.Attr_Price " .
            'FROM ' . $product_attr_table . ' AS pa ' .
            'LEFT JOIN ' . $attr_table . ' AS a ON a.Attr_ID = pa.Attr_ID ' .
            "WHERE pa.Products_ID = '$product_id' " .
            'ORDER BY a.Sort_Order,pa.Product_Attr_ID';


    $res_array = model()->query($sql, 'select');
	
    $arr['pro'] = array();     // 属性
    $arr['spe'] = array();     // 规格
    

    foreach ($res_array AS $row) {
        $row['Attr_Value'] = str_replace('\n', '<br />', $row['Attr_Value']);
        if ($row['Attr_Type'] == 0) {
            $group = (isset($groups[$row['Attr_Group']])) ? $groups[$row['Attr_Group']] : '属性';

            $arr['pro'][$group][$row['Attr_ID']]['Name']  = $row['Attr_Name'];
            $arr['pro'][$group][$row['Attr_ID']]['Value'] = $row['Attr_Value'];
        }else {
            $arr['spe'][$row['Attr_ID']]['Attr_Type'] = $row['Attr_Type'];
            $arr['spe'][$row['Attr_ID']]['Name'] = $row['Attr_Name'];
            $arr['spe'][$row['Attr_ID']]['Values'][] = array(
															'label' => $row['Attr_Value'],
															'price' => $row['Attr_Price'],
															'format_price' => abs($row['Attr_Price']),
															'id' => $row['Product_Attr_Id']
														);
        }      
    }
    return $arr;
}	
	
function get_posterty_desc($attr_id_string, $Users_ID, $Products_ID){
    $fields = ' a.Attr_ID,a.Attr_Name,pa.Attr_Value';
    $table = 'shop_attribute as a,shop_products_attr as pa';
    $condition = "a.Attr_ID = pa.Attr_ID and pa.Products_ID =".$Products_ID;
    $condition .= " and pa.Product_Attr_ID in (".$attr_id_string.")";
    $condition .= " and a.Users_ID = '".$Users_ID."'";
  
    $sql = 'select '.$fields.' from '.$table.' where '.$condition;
 
    $rsArray = model()->query($sql);
    $attr_list = array();
    foreach($rsArray as $k => $item){
	    if(isset($attr_list[$item['Attr_ID']])){
	 	    $attr_list[$item['Attr_ID']]['Value'] .= ','.$item['Attr_Value']; 
	    }else{
			$attr_list[$item['Attr_ID']]['Name'] = $item['Attr_Name'];
			$attr_list[$item['Attr_ID']]['Value'] = $item['Attr_Value'];
	    }

    }
    return $attr_list;
}

/**
 *去除产品属性
 */
function remove_product_attr($Products_ID){
	
	global $DB1;
	$DB1->Del('shop_products_attr','Products_ID='.$Products_ID);
}

/**
 *付款后更改商品库存，若库存为零则下架
 */
function handle_products_count($UsersID, $rsOrder){
	$CartList = json_decode(htmlspecialchars_decode($rsOrder['order_cartlist']), true);
	if(empty($CartList)){
		return false;
	}
	//取出购物车所包含产品信息	
	foreach($CartList as $ProductID => $product_list){
		$qty = 0;
		foreach($product_list as $key => $item){
			$qty += $item['Qty'];
		}
		$pro_model = model('shop_products');
	    $condition = array(
		    'Users_ID'=>$UsersID,
			'Products_ID'=>$ProductID
		);
		
		$rsProduct  = $pro_model->field('Products_Count')->where($condition)->find();
		
		$Products_Count = $rsProduct['Products_Count'] - $qty;
		$product_data['Products_Count'] = $Products_Count;

		if($Products_Count == 0){
			$product_data['Products_SoldOut'] = 1;
		}
		$pro_model->where($condition)->update($product_data);
	}
}

  