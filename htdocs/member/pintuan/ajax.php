<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');

  if(isset($_POST['price'])) {
        $orderid=$_POST['Order'];
        $DB->get('user_order','*',"where Order_ID = '".$orderid."'");
        $ff=$DB->fetch_assoc();
        if(($ff['Order_Status']=='4'||$ff['Order_Status']=='2'||$ff['Order_Status']=='5')||(($ff['Order_Status']=='4'||$ff['Order_Status']=='2'||$ff['Order_Status']=='5')&&$ff['Order_Type']=='dangou')){
           $arr=json_encode(array('t'=>'1','msg'=>'手动退款'), JSON_UNESCAPED_UNICODE);
            echo $arr;
        }else if($ff['Order_Status']=='7'||$ff['Order_Status']=='6'){
           $arr=json_encode(array('t'=>'2','msg'=>'退款成功'), JSON_UNESCAPED_UNICODE);
			echo $arr;
        }else{
        	$arr=json_encode(array('t'=>'3','msg'=>'退款不可用'), JSON_UNESCAPED_UNICODE);
        	echo $arr;
        }
        exit();
    }
  


  if($_POST['action']='40001') {
        $orderid=$_POST['Order'];
        $condition='where Order_ID='.$orderid.'';
        $ff=$DB->set("user_order",array('Order_Status' =>7),$condition);
        if($ff){
          $arr=json_encode(array('a'=>true,'msg'=>'退款成功'), JSON_UNESCAPED_UNICODE);
                echo $arr;
        }else{
          $arr=json_encode(array('a'=>false,'msg'=>'退款未成功'), JSON_UNESCAPED_UNICODE);
                echo $arr;
        }
        exit();
    }



?>