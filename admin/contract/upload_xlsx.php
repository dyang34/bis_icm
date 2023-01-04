<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/UploadUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractBaseChubbMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractBaseMeritzMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractBaseDbMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/customer/CustomerMgr.php";

require_once $_SERVER['DOCUMENT_ROOT'].'/tools/Spout/Autoloader/autoload.php';

header("Cache-Control:no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '0');

if ( !$_FILES["up_file"]["name"] ) {
    JsUtil::alertBack("업로드할 파일을 지정해 주십시오.   ");
    exit;
}

$company_type = RequestUtil::getParam("company_type", "");

$newFileName = UploadUtil::getNewFileName();

$ret = UploadUtil::upload2("up_file", $newFileName, UploadUtil::$Excel_UpWebPath, UploadUtil::$Excel_MaxFileSize, UploadUtil::$Excel_AllowFileType, true);

if ( !empty($ret["err_code"]) ) {
    JsUtil::alertBack($ret["err_msg"]." ErrCode : ".$ret["err_code"]);
    exit;
}

$newWebPath = $ret["newWebPath"];
$newFileName = $ret["newFileName"];
$fileExtName = $ret["fileExtName"];
$fileSize = $ret["fileSize"];
    
//    $arrVal["wr_upload"] = $newFileName;
//    $arrVal["wr_filename"] = $_FILES["wr_upload"]["name"];

$file = $_SERVER['DOCUMENT_ROOT'].$newWebPath.$newFileName;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$reader = ReaderFactory::create(Type::XLSX);
$reader->open($file);

$no_sheet = 1;
$cnt_total = 1;
$arr_data = array();

$arr_customer = array();

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addAndString("type","=","A");

$rs = CustomerMgr::getInstance()->getList($wq);

if ($rs->num_rows > 0) {
    for($ii=0; $ii<$rs->num_rows; $ii++) {
        $row_customer = $rs->fetch_assoc();

        array_push($arr_customer, $row_customer["name"]);
    }
}

foreach ($reader->getSheetIterator() as $sheet) {
    
    $fg_first = true;
    $no = 1;
    
    if ($sheet->getIndex() > 0) {   // 작업자의 실수를 줄이기 위해 첫번째 시트만 등록하도록 수정.
        break;
    }
    
    foreach ($sheet->getRowIterator() as $row) {
        
        if($company_type=="1") {

            if ($fg_first) {
                // 상품명	플랜명	증권번호	청약번호	청약코드	이름	주민번호	청약일자	대리점ID	대리점명	거래처명	SellerID	보험료	금액구분	결제수단	보험시작일	보험종료일	총인원	여행지	처리일자	계약구분	기기구분	추가정보	추가정보2
                if ($row[0] != "상품명" || $row[1] != "플랜명" || $row[2] != "증권번호" || $row[3] != "청약번호" || $row[4] != "청약코드" || $row[5] != "이름" || $row[6] != "주민번호" || $row[7] != "청약일자" || $row[8] != "대리점ID" || $row[9] != "대리점명" || $row[10] != "거래처명" || $row[11] != "SellerID" || $row[12] != "보험료" || $row[13] != "금액구분" || $row[14] != "결제수단" || $row[15] != "보험시작일" || $row[16] != "보험종료일" || $row[17] != "총인원" || $row[18] != "여행지" || $row[19] != "처리일자" || $row[20] != "계약구분" || $row[21] != "기기구분" || $row[22] != "추가정보" || $row[23] != "추가정보2"/* || $row[24] != "입금금액" || $row[25] != "선결제"*/) {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] "."엑셀 양식이 일치하지 않습니다.    ");
                    exit;
                }
                
                $fg_first = false;
            } else {
                
                if ($row[0] && $row[1]) {

                    if (!$row[0]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [상품명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[1]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [플랜명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[2]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [증권번호] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[3]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [청약번호] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[4]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [청약코드] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[5]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [이름] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[6]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [주민번호] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})([0-9]{2})([0-9]{2})$/", $row[7])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [청약일자] 항목은 8자리 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[8]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [대리점ID] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[9]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [대리점명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[10]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [거래처명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!in_array($row[10], $arr_customer)) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [".$row[10]."]은 존재하지 않는 거래처입니다. 거래처를 먼저 등록해 주세요.   ");
                        exit;
                    }

                    if ($row[10]=="(주) 플레이스엠" || $row[10]=="주식회사 투어세이프") {
                        if (!in_array($row[22], $arr_customer)) {
                            JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행, 추가정보에 있는 [".$row[22]."]은 존재하지 않는 거래처입니다. 거래처를 먼저 등록해 주세요.   ");
                            exit;
                        }
                    }

                    if (!$row[11]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [SellerID] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[12]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험료] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[12])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험료] 항목은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[13]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [금액구분] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})([0-9]{2})([0-9]{2})$/", $row[15])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험시작일] 항목은 8자리 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})([0-9]{2})([0-9]{2})$/", $row[16])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험종료일] 항목은 8자리 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[17]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [총인원] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[17])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [총인원] 항목은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[18]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [여행지] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[20]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [계약구분] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[21]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [기기구분] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[24]) && !empty($row[24])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [입금금액] 항목은 공백 혹은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[25]) && !empty($row[25])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [선결제] 항목은 공백 혹은 숫자 타입만 가능합니다.    ");
                        exit;
                    }


                    array_push($arr_data, $row);
                }
            }
        } else if ($company_type=="2") {

            if ($fg_first) {
                // 견적번호	계약자	피보험자	대표피보험자주민번호	총피보험자수	플랜명	 발생보험료 	처리구분명	처리일자	결재수단	보험시작일자	보험종료일자	그룹번호	상품명	청약일자	대리점명	제휴사설계담당자명	추가정보1	추가정보2
                if ($row[0] != "견적번호" || $row[1] != "계약자" || $row[2] != "피보험자" || $row[3] != "대표피보험자주민번호" || $row[4] != "총피보험자수" || $row[5] != "플랜명" || $row[6] != "발생보험료" || $row[7] != "처리구분명" || $row[8] != "처리일자" || $row[9] != "결재수단" || $row[10] != "보험시작일자" || $row[11] != "보험종료일자" || $row[12] != "그룹번호" || $row[13] != "상품명" || $row[14] != "청약일자" || $row[15] != "대리점명" || $row[16] != "제휴사설계담당자명" || $row[17] != "추가정보1" || $row[18] != "추가정보2") {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] "."엑셀 양식이 일치하지 않습니다.    ");
                    exit;
                }
                
                $fg_first = false;
            } else {
                
                if ($row[0] && $row[1]) {

                    if (!$row[0]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [견적번호] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[1]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [계약자] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!in_array($row[1], $arr_customer)) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [".$row[1]."]은 존재하지 않는 거래처입니다. 거래처를 먼저 등록해 주세요.   ");
                        exit;
                    }

                    if ($row[1]=="주식회사 팔로미") {
                        if (!in_array($row[17], $arr_customer)) {
                            JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행, 추가정보1에 있는 [".$row[17]."]은 존재하지 않는 거래처입니다. 거래처를 먼저 등록해 주세요.   ");
                            exit;
                        }
                    }

                    if (!$row[2]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [피보험자] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[3]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [대표피보험자주민번호] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[4]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [총피보험자수] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[4])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [총피보험자수] 항목은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[5]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [플랜명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[6]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [발생보험료] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[6])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [발생보험료] 항목은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[7]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [처리구분명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[8]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [처리일자] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})([0-9]{2})([0-9]{2})$/", $row[8])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [처리일자] 항목은 [YYYYMMDD] 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[10]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험시작일자] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})([0-9]{2})([0-9]{2})$/", $row[10])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험시작일자] 항목은 [YYYYMMDD] 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[11]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험종료일자] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})([0-9]{2})([0-9]{2})$/", $row[11])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험종료일자] 항목은 [YYYYMMDD] 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[12]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [그룹번호] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[13]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [상품명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[14]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [청약일자] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})([0-9]{2})([0-9]{2})$/", $row[14])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [청약일자] 항목은 [YYYYMMDD] 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[15]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [대리점명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[16]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [제휴사설계담당자명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[19]) && !empty($row[19])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [입금금액] 항목은 공백 혹은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[20]) && !empty($row[20])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [선결제] 항목은 공백 혹은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    array_push($arr_data, $row);
                }
            }


        } else if ($company_type=="4") {

            if ($fg_first) {
                // 순번	거래처명	상품코드	행사명	보험종목	Master Policy	(개별)증권번호	청약일	보험기간	계약상태	플랜	피보험자	총인원	보험료(원화)
                if ($row[0] != "순번" || $row[1] != "거래처명" || $row[2] != "상품코드" || $row[3] != "행사명" || $row[4] != "보험종목" || $row[5] != "Master Policy" || $row[6] != "(개별)증권번호" || $row[7] != "청약일" || $row[8] != "보험기간" || $row[9] != "계약상태" || $row[10] != "플랜" || $row[11] != "피보험자" || $row[12] != "총인원" || $row[13] != "보험료(원화)") {
                    JsUtil::alertBack("[".$no_sheet."번째 sheet] "."엑셀 양식이 일치하지 않습니다.    ");
                    exit;
                }
                
                $fg_first = false;
            } else {
                
                if ($row[1] && $row[4]) {

                    if (!$row[1]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [거래처명] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!in_array($row[1], $arr_customer)) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [".$row[1]."]은 존재하지 않는 거래처입니다. 거래처를 먼저 등록해 주세요.   ");
                        exit;
                    }

                    if (!$row[4]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험종목] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[5]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [Master Policy] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[6]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [(개별)증권번호] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})\/([0-9]{2})\/([0-9]{2})$/", $row[7])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [청약일] 항목은 [YYYY/MM/DD] 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!preg_match("/^([2][0][0-9]{2})\/([0-9]{2})\/([0-9]{2}~[2][0][0-9]{2})\/([0-9]{2})\/([0-9]{2})$/", $row[8])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험기간] 항목은 [YYYY/MM/DD~YYYY/MM/DD] 날짜 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[9]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [계약상태] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[10]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [플랜] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[11]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [피보험자] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!$row[12]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [총인원] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[12])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [총인원] 항목은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!$row[13]) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험료(원화)] 항목은 필수값 입니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[13])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [보험료(원화)] 항목은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[14]) && !empty($row[14])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [입금금액] 항목은 공백 혹은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    if (!is_numeric($row[15]) && !empty($row[15])) {
                        JsUtil::alertBack("[".$no_sheet."번째 sheet] ".$no."번째 행의 [선결제] 항목은 공백 혹은 숫자 타입만 가능합니다.    ");
                        exit;
                    }

                    array_push($arr_data, $row);
                }
            }
        
        } else {
            JsUtil::alertBack("유효하지 않은 보험사입니다.    ");
            exit;
        }

        $no++;
        $cnt_total++;
    }
    
    $no_sheet++;
}

$reader->close();

if ($cnt_total > 20000) {
    JsUtil::alertBack("[".$no_sheet."번째 sheet] 한번에 2만개 이상의 Data를 등록할 수 없습니다.    ");
    exit;
}

$grp_code = date("YmdHis");

$arr_insert = array();
if (count($arr_data) > 0) {
    for($i=0;$i<count($arr_data);$i++) {

        if ($company_type=="1") {
            $arr_insert['product_title'] = trim($arr_data[$i][0]);
            $arr_insert['plan_title'] = trim($arr_data[$i][1]);
            $arr_insert['policy_no'] = trim($arr_data[$i][2]);
            $arr_insert['apply_no'] = trim($arr_data[$i][3]);
            $arr_insert['plan_code'] = trim($arr_data[$i][4]);
            $arr_insert['name'] = trim($arr_data[$i][5]);
            $arr_insert['id_no'] = trim($arr_data[$i][6]);
            $arr_insert['apply_date'] = trim($arr_data[$i][7]);
            $arr_insert['agent_id'] = trim($arr_data[$i][8]);
            $arr_insert['agent_name'] = trim($arr_data[$i][9]);
            $arr_insert['customer_name'] = trim($arr_data[$i][10]);
            $arr_insert['seller_id'] = trim($arr_data[$i][11]);
            $arr_insert['insurance_amt'] = trim($arr_data[$i][12]);
            $arr_insert['amt_type'] = trim($arr_data[$i][13]);
            $arr_insert['pay_method'] = trim($arr_data[$i][14]);
            $arr_insert['start_date'] = trim($arr_data[$i][15]);
            $arr_insert['end_date'] = trim($arr_data[$i][16]);
            $arr_insert['cnt_member'] = trim($arr_data[$i][17]);
            $arr_insert['trip_place'] = trim($arr_data[$i][18]);
            $arr_insert['proc_date'] = trim($arr_data[$i][19]);
            $arr_insert['contract_type'] = trim($arr_data[$i][20]);
            $arr_insert['fg_mobile'] = trim($arr_data[$i][21]);
            $arr_insert['add_info1'] = trim($arr_data[$i][22]);
            $arr_insert['add_info2'] = trim($arr_data[$i][23]);
            $arr_insert['deposit_amt'] = trim($arr_data[$i][24]);
            $arr_insert['advance_amt'] = trim($arr_data[$i][25]);
            $arr_insert['pay_type'] = trim($arr_data[$i][26]);
            $arr_insert['grp_code'] = $grp_code;
            
            $row = ContractBaseChubbMgr::getInstance()->add($arr_insert);

            if ($row["rtn_val"] < 0) {
                JsUtil::alertReplace("업로드 중 에러가 발생하였습니다.\\r\\n\\r\\nError Code : ".$row["rtn_val"]."\\r\\nError Message : ".$row["rtn_msg"], "./upload_contract_data.php");
                exit;
            }
        } else if ($company_type=="2") {

            $arr_insert['policy_no'] = trim($arr_data[$i][0]);
            $arr_insert['customer_name'] = trim($arr_data[$i][1]);
            $arr_insert['name'] = trim($arr_data[$i][2]);
            $arr_insert['id_no'] = trim($arr_data[$i][3]);
            $arr_insert['cnt_member'] = trim($arr_data[$i][4]);
            $arr_insert['plan_title'] = trim($arr_data[$i][5]);
            $arr_insert['insurance_amt'] = trim($arr_data[$i][6]);
            $arr_insert['amt_type'] = trim($arr_data[$i][7]);
            $arr_insert['proc_date'] = trim($arr_data[$i][8]);
            $arr_insert['pay_method'] = trim($arr_data[$i][9]);
            $arr_insert['start_date'] = trim($arr_data[$i][10]);
            $arr_insert['end_date'] = trim($arr_data[$i][11]);
            $arr_insert['apply_no'] = trim($arr_data[$i][12]);
            $arr_insert['product_title'] = trim($arr_data[$i][13]);
            $arr_insert['apply_date'] = trim($arr_data[$i][14]);
            $arr_insert['agent_name'] = trim($arr_data[$i][15]);
            $arr_insert['seller_id'] = trim($arr_data[$i][16]);
            $arr_insert['add_info1'] = trim($arr_data[$i][17]);
            $arr_insert['add_info2'] = trim($arr_data[$i][18]);
            $arr_insert['deposit_amt'] = trim($arr_data[$i][19]);
            $arr_insert['advance_amt'] = trim($arr_data[$i][20]);
            $arr_insert['pay_type'] = trim($arr_data[$i][21]);
            $arr_insert['grp_code'] = $grp_code;
            
            $row = ContractBaseMeritzMgr::getInstance()->add($arr_insert);

            if ($row["rtn_val"] < 0) {
                JsUtil::alertReplace("업로드 중 에러가 발생하였습니다.\\r\\n\\r\\nError Code : ".$row["rtn_val"]."\\r\\nError Message : ".$row["rtn_msg"], "./upload_contract_data.php");
                exit;
            }

        } else if ($company_type=="4") {

            $insurance_date = explode("~", trim($arr_data[$i][8]));

            $arr_insert['customer_name'] = trim($arr_data[$i][1]);
            $arr_insert['add_info1'] = trim($arr_data[$i][2]);
            $arr_insert['add_info2'] = trim($arr_data[$i][3]);
            $arr_insert['product_title'] = trim($arr_data[$i][4]);
            $arr_insert['apply_no'] = trim($arr_data[$i][5]);
            $arr_insert['policy_no'] = trim($arr_data[$i][6]);
            $arr_insert['apply_date'] = trim(str_replace("/", "", $arr_data[$i][7]));
            $arr_insert['start_date'] = trim(str_replace("/", "", $insurance_date[0]));
            $arr_insert['end_date'] = trim(str_replace("/", "", $insurance_date[1]));
            $arr_insert['amt_type'] = trim($arr_data[$i][9]);
            $arr_insert['plan_title'] = trim($arr_data[$i][10]);
            $arr_insert['name'] = trim($arr_data[$i][11]);
            $arr_insert['cnt_member'] = trim($arr_data[$i][12]);
            $arr_insert['insurance_amt'] = trim($arr_data[$i][13]);
            $arr_insert['deposit_amt'] = trim($arr_data[$i][14]);
            $arr_insert['advance_amt'] = trim($arr_data[$i][15]);
            $arr_insert['pay_type'] = trim($arr_data[$i][16]);
            $arr_insert['grp_code'] = $grp_code;
            
            $row = ContractBaseDbMgr::getInstance()->add($arr_insert);

            if ($row["rtn_val"] < 0) {
                JsUtil::alertReplace("업로드 중 에러가 발생하였습니다.\\r\\n\\r\\nError Code : ".$row["rtn_val"]."\\r\\nError Message : ".$row["rtn_msg"], "./upload_contract_data.php");
                exit;
            }
        }
    }
    
    $row = ContractBaseChubbMgr::getInstance()->add_check(array("company_type"=>$company_type, "grp_code"=>$grp_code));

    if ($row["rtn_val"] < 0) {
        JsUtil::alertReplace("업로드 적용 중 에러가 발생하였습니다.\\r\\n\\r\\nError Code : ".$row["rtn_val"]."\\r\\nError Message : ".$row["rtn_msg"], "./upload_contract_data.php");
        exit;
    }

    JsUtil::alertReplace("총 ".($cnt_total-$no_sheet)."개의 Row가 등록되었습니다.    ", "./upload_contract_data.php");
    
}

@ $rs->free();
?>