<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("비정상적인 접근입니다."."/");
    exit;
}

$arrMemGrade = explode('|+|', LoginManager::getManagerLoginInfo('grade'));
$p_menu_no0 = $_REQUEST["p_menu_no0"];

if($p_menu_no0=="") {
    for($i=0;$i<count($arrMemGrade);$i++) {
        if($arrMemGrade[$i] > 0) {
            $p_menu_no0 = $arrSystemMenu[$i]["menu_no0"]; 
            break;
        }
    }
}

switch($p_menu_no0) {
    case "0":
        switch($arrMemGrade[$p_menu_no0]) {
            case "5":
            case "10":
                JsUtil::replace("/admin/contract/upload_contract_data.php");
                break;
            default:
                JsUtil::replace("/admin/contract/contract_list.php");
                break;
        }
        break;
    case "1":
        JsUtil::replace("/admin/corporation/contract_list.php");
        break;
    case "2":
        JsUtil::replace("/admin/long_trip/contract_list.php");
        break;
    case "9":
        JsUtil::replace("/admin/system/adm_mem_list.php");
        break;
    default:
        JsUtil::alertReplace("권한이 없습니다.    ".$p_menu_no0."|".LoginManager::getManagerLoginInfo('grade'),"/admin_logout.php");
        break;
}
?>