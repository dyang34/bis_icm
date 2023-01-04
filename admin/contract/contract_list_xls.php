<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractBaseChubbMgr.php";

$menuCate = 2;
$menuNo = 4;

if (LoginManager::getManagerLoginInfo("grade_0") < 9) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$_apply_date_from = RequestUtil::getParam("_apply_date_from", date("Y-m-01", strtotime("-3 month", mktime(0,0,0, date("m"), 1, date("Y")))));
$_apply_date_to = RequestUtil::getParam("_apply_date_to", date("Y-m-d"));
$_start_date_from = RequestUtil::getParam("_start_date_from", "");
$_start_date_to = RequestUtil::getParam("_start_date_to", "");
$_imc_idx = RequestUtil::getParam("_imc_idx", "");
$_company_type = RequestUtil::getParam("_company_type", "");
$_policy_no = RequestUtil::getParam("_policy_no", "");
$_seller_id = RequestUtil::getParam("_seller_id", "");
$_name = RequestUtil::getParam("_name", "");
$_grp_code = RequestUtil::getParam("_grp_code", "");
$_amt_type = RequestUtil::getParam("_amt_type", "");
$_insurance_amt_from = RequestUtil::getParam("_insurance_amt_from", "");
$_insurance_amt_to = RequestUtil::getParam("_insurance_amt_to", "");
$_rate_fee_from = RequestUtil::getParam("_rate_fee_from", "");
$_rate_fee_to = RequestUtil::getParam("_rate_fee_to", "");
$_deposit_amt_from = RequestUtil::getParam("_deposit_amt_from", "");
$_deposit_amt_to = RequestUtil::getParam("_deposit_amt_to", "");
$_advance_amt_from = RequestUtil::getParam("_advance_amt_from", "");
$_advance_amt_to = RequestUtil::getParam("_advance_amt_to", "");

$_order_by = RequestUtil::getParam("_order_by", "ic_idx");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$wq = new WhereQuery(true, true);
$wq->addAndString2("fg_del", "=", "0");
$wq->addAndStringBind("apply_date", ">=", $_apply_date_from, "date_format('?','%Y%m%d')");
$wq->addAndStringBind("apply_date", "<", $_apply_date_to, "date_format(date_add('?', interval 1 day),'%Y%m%d')");
$wq->addAndStringBind("start_date", ">=", $_start_date_from, "date_format('?','%Y%m%d')");
$wq->addAndStringBind("start_date", "<", $_start_date_to, "date_format(date_add('?', interval 1 day),'%Y%m%d')");
$wq->addAndString("imc_idx", "=", $_imc_idx);
$wq->addAndString("company_type", "=", $_company_type);
$wq->addAndString("policy_no", "=", $_policy_no);
$wq->addAndLike("name",$_name);
$wq->addAndLike("seller_id",$_seller_id);
$wq->addAndString("grp_code", "=", $_grp_code);
//$wq->addAndString("amt_type", "=", $_amt_type);

if($_insurance_amt_from != "") {
	$wq->addAndString2("insurance_amt", ">=", $_insurance_amt_from);
}

if($_insurance_amt_to != "") {
	$wq->addAndString2("insurance_amt", "<=", $_insurance_amt_to);
}

if($_rate_fee_from != "") {
	$wq->addAndString2("rate_fee", ">=", $_rate_fee_from);
}

if($_rate_fee_to != "") {
	$wq->addAndString2("rate_fee", "<=", $_rate_fee_to);
}

if($_deposit_amt_from != "") {
	$wq->addAndString2("deposit_amt", ">=", $_deposit_amt_from);
}

if($_deposit_amt_to != "") {
	$wq->addAndString2("deposit_amt", "<=", $_deposit_amt_to);
}

if($_advance_amt_from != "") {
	$wq->addAndString2("advance_amt", ">=", $_advance_amt_from);
}

if($_advance_amt_to != "") {
	$wq->addAndString2("advance_amt", "<=", $_advance_amt_to);
}

$wq->addOrderBy($_order_by, $_order_by_asc);
$wq->addOrderBy("apply_date", "desc");
$wq->addOrderBy("ic_idx", "desc");

$rs = ContractMgr::getInstance()->getList($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=IMS_계약관리_계약 내역_".date('Ymd').".xls");
Header("Content-Description: PHP5 Generated Data");
Header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");
?>
<style>
td{font-size:11px;text-align:center;}
th{font-size:11px;text-align:center;color:white;background-color:#000081;}
</style>

<table cellpadding=3 cellspacing=0 border=1 bordercolor='#bdbebd' style='border-collapse: collapse'>
    <tr>
        <th style="color:white;background-color:#000081;">업로드코드</th>
        <th style="color:white;background-color:#000081;">보험사</th>
        <th style="color:white;background-color:#000081;">청약일자</th>
        <th style="color:white;background-color:#000081;">보험시작일</th>
        <th style="color:white;background-color:#000081;">보험종료일</th>
        <th style="color:white;background-color:#000081;">거래처명</th>
        <th style="color:white;background-color:#000081;">원거래처명</th>
        <th style="color:white;background-color:#000081;">정산방법</th>
        <th style="color:white;background-color:#000081;">보험료</th>
        <th style="color:white;background-color:#000081;">수수료율</th>
        <th style="color:white;background-color:#000081;">입금금액</th>
        <th style="color:white;background-color:#000081;">선결제</th>
        <th style="color:white;background-color:#000081;">납입방법 및 입금일자</th>
        <th style="color:white;background-color:#000081;">상품명</th>
        <th style="color:white;background-color:#000081;">플랜명</th>
        <th style="color:white;background-color:#000081;">증권번호</th>
        <th style="color:white;background-color:#000081;">이름</th>
        <th style="color:white;background-color:#000081;">SellerID</th>
        <th style="color:white;background-color:#000081;">금액구분</th>
        <th style="color:white;background-color:#000081;">총인원</th>
        <th style="color:white;background-color:#000081;">여행지</th>
        <th style="color:white;background-color:#000081;">처리일자</th>
        <th style="color:white;background-color:#000081;">추가정보</th>
        <th style="color:white;background-color:#000081;">추가정보2</th>
        <th style="color:white;background-color:#000081;">등록일</th>
    </tr>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
    <tr>
        <td style="mso-number-format:'\@';"><?=$row["grp_code"]?></td>
        <td><?=$arrInsuranceCompany[$row["company_type"]]?></td>
        <td><?=substr($row["apply_date"],0,4)."-".substr($row["apply_date"],4,2)."-".substr($row["apply_date"],6,2)?></td>
        <td><?=substr($row["start_date"],0,4)."-".substr($row["start_date"],4,2)."-".substr($row["start_date"],6,2)?></td>
        <td><?=substr($row["end_date"],0,4)."-".substr($row["end_date"],4,2)."-".substr($row["end_date"],6,2)?></td>
        <td><?=$row["customer_name"]?></td>
        <td><?=$row["customer_name"]!=$row["customer_name_org"]?$row["customer_name_org"]:""?></td>
        <td><?=$arrCustomerCalcPeriod[$row["calc_period"]]?></td>
        <td><?=number_format($row["insurance_amt"])?>원</td>
        <td><?=$row["rate_fee"]?>%</td>
        <td><?=number_format($row["deposit_amt"])?></tdalign:right;>
        <td><?=number_format($row["advance_amt"])?></td>
        <td><?=$row["pay_type"]?></td>
        <td><?=$row["product_title"]?></td>
        <td><?=$row["plan_title"]?></td>
        <td><?=$row["policy_no"]?></td>
        <td><?=$row["name"]?></td>
        <td><?=$row["seller_id"]?></td>
        <td><?=$row["amt_type"]?></td>
        <td style="text-align:right;"><?=number_format($row["cnt_member"])?>명</td>
        <td><?=$row["trip_place"]?></td>
        <td class="txt_c"><?=$row["proc_date"]?substr($row["proc_date"],0,4)."-".substr($row["proc_date"],4,2)."-".substr($row["proc_date"],6,2):""?></td>
        <td><?=$row["add_info1"]?></td>
        <td><?=$row["add_info2"]?></td>
        <td><?=substr($row["reg_date"],0,10)?></td>
    </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free();
?>