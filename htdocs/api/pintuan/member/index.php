<?php
require_once('../comm/global.php');

$num0 = $num1 = $num2 = $num3 = 0;
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=0");
$num0 = $r["num"];
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=1");
$num1 = $r["num"];
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=2");
$num2 = $r["num"];
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=3");
$num3 = $r["num"];
$r = $DB->GetRs("user_order","count(*) as num","where Users_ID='".$UsersID."' and User_ID=".$_SESSION[$UsersID."User_ID"]." and Order_Type='shop' and Order_Status=4");
$num4 = $r["num"];
?>
