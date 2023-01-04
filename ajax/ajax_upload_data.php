<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractBaseChubbMgr.php";

$mode = RequestUtil::getParam("mode", "");
$grp_code = RequestUtil::getParam("grp_code", "");
$company_type = RequestUtil::getParam("company_type", "");

if(!LoginManager::isManagerLogined()) {
    $rtnVal['RESULTCD'] = "not_login";
    print_r(json_encode($rtnVal));
    exit;
}

if (!$grp_code ) {
    $rtnVal['RESULTCD'] = "no_grp_code";
    print_r(json_encode($rtnVal));
    exit;
}

switch($mode) {
    case "DEL":
        switch($company_type) {
            case "1":
                $row = ContractBaseChubbMgr::getInstance()->deleteByGrpCode($grp_code);
                break;
            case "4":
                $row = ContractBaseDbMgr::getInstance()->deleteByGrpCode($grp_code);
                break;
            default:
                $rtnVal['RESULTCD'] = "no_company_type";
                print_r(json_encode($rtnVal));
                exit;
        }

        $row = ContractMgr::getInstance()->deleteByGrpCode($grp_code);
        break;
    default:
        $rtnVal['RESULTCD'] = "no_mode";
        print_r(json_encode($rtnVal));
        exit;
}

$rtnVal['RESULTCD'] = "SUCCESS";

print_r(json_encode($rtnVal));
exit;
?>