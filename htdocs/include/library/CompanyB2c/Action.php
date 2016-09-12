<?php
require_once("autoload.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/Framework/Conn.php');
global $DB;
if ($_POST) {
    //更新用户收费字段
    if (isset($_POST['action']) && $_POST['action'] == 'updateUsersPayCharge') {
        $forwardResult = $DB->GetRs("shop_config", '*', "where Users_ID='" . $_SESSION["Users_ID"] . "'");
        $Users_PayCharge = preg_replace('/[^\d.]/', '',htmlspecialchars(trim($_POST['Users_PayCharge'])));
        //如果进行过滤处理后，不存在大于0的金额，则直接返回失败
        if (strlen($Users_PayCharge) == 0) {
            $result = [
                'status' => 0,
                'Users_PayCharge' => $forwardResult['Users_PayCharge'],
            ];
        }else{
            $updateChargeArr = [
                'Users_PayCharge' => floatval($Users_PayCharge),
            ];
            //更新收费金额
            $payChargeFlag = $DB->Set("shop_config", $updateChargeArr, "where Users_ID='" . $_SESSION["Users_ID"] . "'");
            //更新完进行查询,如果更新成功直接返回最新的金额到前台,如果更新失败则还返回原来的金额给前台
            $updateResult = $DB->GetRs("shop_config", '*', "where Users_ID='" . $_SESSION["Users_ID"] . "'");
            if ($payChargeFlag) {
                $result = [
                    'status' => 1,
                    'msg' => '更新成功',
                    'Users_PayCharge' => $updateResult['Users_PayCharge'],
                ];
            }else{
                $result = [
                    'status' => 0,
                    'msg' => '更新失败',
                    'Users_PayCharge' => $forwardResult['Users_PayCharge'],
                ];
            }
            //如果原先不收费，后来收费了，则把无限期的用户更改为从当前时间开始7天以后到期
            if ($forwardResult['Users_PayCharge'] == 0 && $Users_PayCharge > 0) {
                $data = [
                    'Users_ExpiresTime' => time()+3600*24*7,
                ];
                $updateExpiresTime = $DB->Set('biz', $data, "where Users_ExpiresTime = 0");
            }
        }
        echo json_encode($result);
    }

}