<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/customer/CustomerMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/customer/CustomerFeeHistoryMgr.php";

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
    exit;
}

if (LoginManager::getManagerLoginInfo("grade_0") < 9) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$imc_idx = RequestUtil::getParam("imc_idx", "");
//$type = RequestUtil::getParam("type", "");
$name = RequestUtil::getParam("name", "");
$rate_fee = RequestUtil::getParam("rate_fee", "");
$rate_fee_old = RequestUtil::getParam("rate_fee_old", "");
$calc_period = RequestUtil::getParam("calc_period", "");
$email = RequestUtil::getParam("email", "");
$tel1 = RequestUtil::getParam("tel1", "");
$tel2 = RequestUtil::getParam("tel2", "");
$account_bank = RequestUtil::getParam("account_bank", "");
$account_no = RequestUtil::getParam("account_no", "");
$account_holder = RequestUtil::getParam("account_holder", "");
$memo = RequestUtil::getParam("memo", "");
$history = RequestUtil::getParam("history", "");
$arr_c_type = RequestUtil::getParam("c_type", "");

$c_type = implode($arr_c_type,",");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="INS") {
        
        if (!$name) {
            JsUtil::alertBack("거래처명을 입력해 주십시오.   ");
            exit;
        }

        $arrIns = array();
        $arrIns["type"] = $c_type;
        $arrIns["name"] = $name;
        $arrIns["rate_fee"] = $rate_fee;
        $arrIns["calc_period"] = $calc_period;
        $arrIns["email"] = $email;
        $arrIns["tel1"] = $tel1;
        $arrIns["tel2"] = $tel2;
        $arrIns["account_bank"] = $account_bank;
        $arrIns["account_no"] = $account_no;
        $arrIns["account_holder"] = $account_holder;
        $arrIns["memo"] = $memo;
        
        CustomerMgr::getInstance()->add($arrIns);
        
        JsUtil::alertReplace("등록되었습니다.    ", "./customer_list.php");
        
    } else if($mode=="UPD") {

        if (!$imc_idx) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
            exit;
        }
        
        if (!$name) {
            JsUtil::alertBack("거래처명을 입력해 주십시오.   ");
            exit;
        }
        
        $row_data = CustomerMgr::getInstance()->getByKey($imc_idx);
        
        if (empty($row_data)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        $uq = new UpdateQuery();
        $uq->add("type", $c_type);
        $uq->add("name", $name);
        $uq->add2("rate_fee", $rate_fee);
        $uq->add("calc_period", $calc_period);
        $uq->add("email", $email);
        $uq->add("tel1", $tel1);
        $uq->add("tel2", $tel2);
        $uq->add("account_bank", $account_bank);
        $uq->add("account_no", $account_no);
        $uq->add("account_holder", $account_holder);
        $uq->add("memo", $memo);
        
        CustomerMgr::getInstance()->edit($uq, $imc_idx);

        if($rate_fee != $rate_fee_old) {
            $arrIns["imc_idx"] = $imc_idx;
            $arrIns["rate_fee"] = $rate_fee_old;
            $arrIns["to_date"] = date("Y-m-d H:i:s", time());;
            CustomerFeeHistoryMgr::getInstance()->add($arrIns);
        }

        JsUtil::alertReplace("수정되었습니다.    ", "./customer_list.php");
        
    } else if($mode=="DEL") {
        
//        if (empty($userid)) {
        if (!$imc_idx) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x03)   ");
            exit;
        }
        
        $row_data = CustomerMgr::getInstance()->getByKey($imc_idx);
        
        //        if (empty($row_data)) {
        if (!$row_data) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        CustomerMgr::getInstance()->delete($imc_idx);
        
        JsUtil::alertReplace("삭제되었습니다.    ", "./customer_list.php");
        
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>