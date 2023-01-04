<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractMgr.php";

$mode = RequestUtil::getParam("mode", "");
$dataArr = RequestUtil::getParam("dataArr", "");

if(!LoginManager::isManagerLogined()) {
    $rtnVal['RESULTCD'] = "not_login";
    echo json_encode($rtnVal);
    exit;
}

if (!$dataArr ) {
    $rtnVal['RESULTCD'] = "no_data";
    echo json_encode($rtnVal);
    exit;
}

switch($mode) {
    case "UPD_ARR":
        $dataArr = str_replace("\\\"", "\"", $dataArr);
        $data = json_decode($dataArr);

        $prev_ic_idx = 0;
        $uq = new UpdateQuery();

        for($i=0;$i<count($data);$i++) {

            if ($prev_ic_idx != $data[$i][0]) {

                if(!empty($prev_ic_idx)) {
                    ContractMgr::getInstance()->edit($uq, $prev_ic_idx);
                }

                $uq = new UpdateQuery();
            }

            $uq->add($data[$i][1], $data[$i][2]);

            $prev_ic_idx = $data[$i][0];
        }

        ContractMgr::getInstance()->edit($uq, $prev_ic_idx);

        break;
    default:
        $rtnVal['RESULTCD'] = "no_mode";
        echo json_encode($rtnVal);
        exit;
}

$rtnVal['RESULTCD'] = "SUCCESS";

echo json_encode($rtnVal);
exit;
?>