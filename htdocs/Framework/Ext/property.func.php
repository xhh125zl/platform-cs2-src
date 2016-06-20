<?php
basename($_SERVER['PHP_SELF'])=='property.func.php'&&header('Location:http://'.$_SERVER['HTTP_HOST']);
function get_bizs($usersid=""){
	global $DB1;
	$condition = "where 1";
	if($usersid)  $condition .= " and Users_ID='".$usersid."'";
	$DB1->get("weicbd_biz","*",$condition." order by Biz_ID asc");
	$lists = array();
	while($r=$DB1->fetch_assoc()){
		$lists[] = $r;
	}
	return $lists;
}

function get_property($usersid="", $bizid=0, $muluid=0, $ProductsID=0){
	global $DB1;
	$html = "";
	$PROPERTY = array();
	if($ProductsID){
		$rsProducts=$DB1->GetRs("weicbd_products","*","where Users_ID='".$usersid."' and Products_ID=".$ProductsID);
		if($rsProducts){
			$JSON=json_decode($rsProducts['Products_JSON'],true);
			if(!empty($JSON["Property"])){
				$PROPERTY = $JSON["Property"];
			}
		}
	}
	
	$DB1->get("weicbd_property","*","where Users_ID='".$usersid."' and Biz_ID=".$bizid." and (Mulu_ID=".$muluid." or Mulu_ID=0) order by Property_Index asc,Property_ID asc");
	while($r=$DB1->fetch_assoc()){
		if($r["Property_Type"]==0){//单行文本
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input"><input type="text" name="JSON[Property]['.$r["Property_Name"].']" value="'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) ? $PROPERTY[$r["Property_Name"]] : "").'" class="form_input" size="35" /></span>
			  <div class="clear"></div>
			</div>';
		}elseif($r["Property_Type"]==1){//多行文本
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input"><textarea name="JSON[Property]['.$r["Property_Name"].']" class="briefdesc">'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) ? $PROPERTY[$r["Property_Name"]] : "").'</textarea></span>
			  <div class="clear"></div>
			</div>';
		}elseif($r["Property_Type"]==2){//下拉框
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input"><select name="JSON[Property]['.$r["Property_Name"].']" style="width:180px">';
			  $List=json_decode($r["Property_Json"],true);
			  foreach($List as $key=>$value){
				  $html .='<option value="'.$value.'"'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) && $value==$PROPERTY[$r["Property_Name"]] ? " selected" : "").'>'.$value.'</option>';
			  }
			  $html .='</select></span>
			  <div class="clear"></div>
			</div>';
		}elseif($r["Property_Type"]==3){//多选框
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input">';
			  $List=json_decode($r["Property_Json"],true);
			  foreach($List as $key=>$value){
				  $html .='<input type="checkbox" name="JSON[Property]['.$r["Property_Name"].'][]" value="'.$value.'"'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) && in_array($value,$PROPERTY[$r["Property_Name"]]) ? " checked" : "").'>&nbsp;'.$value.'&nbsp;&nbsp;&nbsp;&nbsp;';
			  }
			  $html .='</span>
			  <div class="clear"></div>
			</div>';
		}else{//单选按钮
			$html .='<div class="rows">
			  <label>'.$r["Property_Name"].'</label>
			  <span class="input">';
			  $List=json_decode($r["Property_Json"],true);
			  foreach($List as $key=>$value){
				  $html .='<input type="radio" name="JSON[Property]['.$r["Property_Name"].']" value="'.$value.'"'.(!empty($PROPERTY) && !empty($PROPERTY[$r["Property_Name"]]) && $value==$PROPERTY[$r["Property_Name"]] ? " checked" : "").'/>&nbsp;'.$value.'&nbsp;&nbsp;&nbsp;&nbsp;';
			  }
			  $html .='</span>
			  <div class="clear"></div>
			</div>';
		}
	}
	return $html;
}
?>