<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/AdmMemberMgr.php";

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
    exit;
}

$mode = RequestUtil::getParam("chgPW_mode", "INS");
$passwd_old = RequestUtil::getParam("chgPW_passwd_old", "");
$passwd_new = RequestUtil::getParam("chgPW_passwd_new", "");
$auto_defense = RequestUtil::getParam("chgPW_auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="CHANGE_PW") {
        if (empty($passwd_old)) {
            JsUtil::alertBack("기존 비밀번호를 입력해 주십시오.   ");
            exit;
        }

        if (empty($passwd_new)) {
            JsUtil::alertBack("변경 비밀번호를 입력해 주십시오.   ");
            exit;
        }
        
        $wq = new WhereQuery(true, true);
        $wq->addAndString("userid","=",LoginManager::getManagerLoginInfo("userid"));
        $wq->addAndStringBind("passwd", "=", $passwd_old, "password('?')");
        $row_mem = AdmMemberMgr::getInstance()->getFirst($wq);
        
        if (empty($row_mem)) {
            JsUtil::alertBack("기존 비밀번호가 일치하지 않습니다.   ");
            exit;
        }

        $uq = new UpdateQuery();
        $uq->addWithBind("passwd", $passwd_new, "password('?')");

        AdmMemberMgr::getInstance()->edit($uq, LoginManager::getManagerLoginInfo("userid"));
        
        JsUtil::alertReplace("수정되었습니다. 다시 로그인 하십시오.   ", "/admin_logout.php");
        
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>