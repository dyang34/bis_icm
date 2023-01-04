<?
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/customer/CustomerMgr.php";

if (LoginManager::getManagerLoginInfo("grade_0") < 10) {
    echo "작업 권한이 없습니다.    ";
    exit;
}

$_name = RequestUtil::getParam("_name", "");
$_rate_fee_from = RequestUtil::getParam("_rate_fee_from", "");
$_rate_fee_to = RequestUtil::getParam("_rate_fee_to", "");
$_calc_period = RequestUtil::getParam("_calc_period", "");
$_order_by = RequestUtil::getParam("_order_by", "name");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "asc");

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addAndLike("name",$_name);
$wq->addAndString("calc_period","=",$_calc_period);

if($_rate_fee_from != "") {
	$wq->addAndString2("rate_fee", ">=", $_rate_fee_from);
}

if($_rate_fee_to != "") {
	$wq->addAndString2("rate_fee", "<=", $_rate_fee_to);
}

$wq->addOrderBy($_order_by,$_order_by_asc);
$wq->addOrderBy("imc_idx","desc");

$rs = CustomerMgr::getInstance()->getList($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=IMS_계약관리_거래처 리스트_".date('Ymd').".xls");
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
	<tr style="height:30px;">
<?php/*    
        <th style="color:white;background-color:#000081;">거래처코드</th>
*/?>        
        <th style="color:white;background-color:#000081;">유형</th>
        <th style="color:white;background-color:#000081;">거래처명</th>
        <th style="color:white;background-color:#000081;">수수료율</th>
        <th style="color:white;background-color:#000081;">정산주기</th>
        <th style="color:white;background-color:#000081;">이메일</th>
        <th style="color:white;background-color:#000081;">연락처1</th>
        <th style="color:white;background-color:#000081;">연락처2</th>
        <th style="color:white;background-color:#000081;">은행</th>
        <th style="color:white;background-color:#000081;">계좌번호</th>
        <th style="color:white;background-color:#000081;">예금주</th>
        <th style="color:white;background-color:#000081;">메모</th>
        <th style="color:white;background-color:#000081;">등록일</th>
	</tr>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
		<tr>
<?php/*            
            <td><?=$row["code"]?></td>
*/?>
            <td><?=$arrCustomerType[$row["type"]]?></td>
            <td><?=$row["name"]?></td>
            <td><?=$row["rate_fee"]?></td>
            <td>
<?php
                $font_color = $row["calc_period"]=="D"?"#FF5A5A":"#4948FF";
?>
                <span style="color:<?=$font_color?>">월 정산</span>
            </td>
            <td><?=$row["email"]?></td>
            <td><?=$row["tel1"]?></td>
            <td><?=$row["tel2"]?></td>
            <td><?=$row["account_bank"]?></td>
            <td><?=$row["account_no"]?></td>
            <td><?=$row["account_holder"]?></td>
            <td><?=nl2br($row["memo"])?></td>
            <td><?=$row["reg_date"]?></td>

		</tr>
<?php        
    }
}
?>
</table>
<?php
@ $rs->free();
?>