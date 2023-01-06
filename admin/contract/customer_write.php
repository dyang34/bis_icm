<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/customer/CustomerMgr.php";

$menuNo = [0,2,0];

$mode = RequestUtil::getParam("mode", "INS");
$imc_idx = RequestUtil::getParam("imc_idx", "");

if (LoginManager::getManagerLoginInfo("grade_0") < 9) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$arrSalesType = array();

if ($mode=="UPD") {
    //    if(empty($userid)) {
    if(!$imc_idx) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
        exit;
    }
    
    $row = CustomerMgr::getInstance()->getByKey($imc_idx);
    
    //    if (empty($row)) {
    if (!$row) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
        exit;
    }
} else {
    //    if(!empty($userid)) {
    if($imc_idx) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x04)   ");
        exit;
    }
}

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<div class="write-area">
    <div class="title-area">
        <h2>거래처 등록</h2>
    </div>
    <div class="write-box">
        <form name="writeForm" action="./customer_write_act.php" method="post">
            <input type="hidden" name="mode" value="<?=$mode?>" />
            <input type="hidden" name="imc_idx" value="<?=$imc_idx?>" />
            <input type="hidden" name="auto_defense" />
            <table class="table-write">
                <colgroup>
                    <col width="12%">
                    <col width="*">
                </colgroup>
                <tbody>
<?/*
                    <tr>
                        <th>거래처코드</th>
                        <td>
                            <input type="text" name="name" value="<?=$row['name']?>" placeholder="거래처명을 입력하세요." style="width: 200px;">
                        </td>
                    </tr>
*/?>
                    <tr>
                        <th class="required">거래처명</th>
                        <td>
<?php
    if ($mode=="INS") {
?>        
                            <input type="text" name="name" value="<?=$row['name']?>" placeholder="거래처명을 입력하세요." class="input01" style="width: 500px;">
<?php
    } else {
?>
                            <input type="hidden" name="name" value="<?=$row['name']?>"><?=$row['name']?>
<?php        
    }
?>
                        </td>
                    </tr>
                    <tr>
                        <th class="required">유형</th>
                        <td>
<?php
    $arr_c_type = explode(",", $row['type']);

    $arrCustomerTypeKey = array_keys($arrCustomerType);
    $arrCustomerTypeVal = array_values($arrCustomerType);
    
    for($ii=0;$ii<count($arrCustomerType);$ii++) {
?>
                            <div class="choice-round">
                                <input type="checkbox" id="c_type_<?=$arrCustomerTypeKey[$ii]?>" name="c_type[]" value="<?=$arrCustomerTypeKey[$ii]?>" <?=in_array($arrCustomerTypeKey[$ii],$arr_c_type)?"checked='checked'":""?> />
                                <label for="c_type_<?=$arrCustomerTypeKey[$ii]?>"><?=$arrCustomerTypeVal[$ii]?><span class="box"></span></label>
                            </div>
<?
    }
?>
<?/*
                            <div class="select-box" style="width: 200px;">
                                <select name="type">
<?php
$arrCustomerTypeKey = array_keys($arrCustomerType);
$arrCustomerTypeVal = array_values($arrCustomerType);

for($ii=0;$ii<count($arrCustomerType);$ii++) {
?>
        <option value="<?=$arrCustomerTypeKey[$ii]?>" <?=$row['type']==$arrCustomerTypeKey[$ii]?"selected":""?>><?=$arrCustomerTypeVal[$ii]?></option>
<?php
}
?>                                        
                                </select>    
                            </div>
*/?>                            
                        </td>
                    </tr>
                    <tr>
                        <th class="required">수수료율</th>
                        <td>
                            <input type="number" name="rate_fee" value="<?=$row['rate_fee']?>" placeholder="수수료율" class="input01 fl" style="width: 100px;"><span class="input_krw">%</span>
                            <input type="hidden" name="rate_fee_old" value="<?=$row['rate_fee']?>" />
                        </td>
                    </tr>
                    <tr>
                        <th class="required">정산주기</th>
                        <td>
                            <div class="select-box" style="width: 200px;">
                                <select name="calc_period">
<?php
$arrCustomerCalcPeriodKey = array_keys($arrCustomerCalcPeriod);
$arrCustomerCalcPeriodVal = array_values($arrCustomerCalcPeriod);

for($ii=0;$ii<count($arrCustomerCalcPeriod);$ii++) {
?>
        <option value="<?=$arrCustomerCalcPeriodKey[$ii]?>" <?=$row['calc_period']==$arrCustomerCalcPeriodKey[$ii]?"selected":""?>><?=$arrCustomerCalcPeriodVal[$ii]?></option>
<?php
}
?>                                        
                                </select>    
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>이메일</th>
                        <td>
                            <input type="text" name="email" value="<?=$row['email']?>" placeholder="이메일을 입력하세요." class="input01" style="width: 500px;">
                        </td>
                    </tr>

                    <tr>
                        <th>연락처1</th>
                        <td>
                            <input type="text" name="tel1" value="<?=$row['tel1']?>" placeholder="연락처1을 입력하세요." class="input01" style="width: 200px;">
                        </td>
                    </tr>

                    <tr>
                        <th>연락처2</th>
                        <td>
                            <input type="text" name="tel2" value="<?=$row['tel2']?>" placeholder="연락처2를 입력하세요." class="input01" style="width: 200px;">
                        </td>
                    </tr>

                    <tr>
                        <th>은행</th>
                        <td>
                            <input type="text" name="account_bank" value="<?=$row['account_bank']?>" placeholder="은행명을 입력하세요." class="input01" style="width: 200px;">
                        </td>
                    </tr>

                    <tr>
                        <th>계좌번호</th>
                        <td>
                            <input type="text" name="account_no" value="<?=$row['account_no']?>" placeholder="계좌번호 입력하세요." class="input01" style="width: 500px;">
                        </td>
                    </tr>

                    <tr>
                        <th>예금주</th>
                        <td>
                            <input type="text" name="account_holder" value="<?=$row['account_holder']?>" placeholder="예금주를 입력하세요." class="input01" style="width: 200px;">
                        </td>
                    </tr>

                    <tr>
                        <th>메모</th>
                        <td>
                            <textarea name="memo" class="textarea"><?=$row["memo"]?></textarea>
                        </td>
                    </tr>
<?php
    if($mode=="UPD") {
?>
                    <tr>
                        <th>수정 History</th>
                        <td>
                            <?=$row["history"]?>&nbsp;
                        </td>
                    </tr>

                    <tr>
                        <th>등록일</th>
                        <td>
                            <?=$row["reg_date"]?>
                        </td>
                    </tr>
<?php
    }
?>
                </tbody>
            </table>
        </form>


        <div class="button-center">
            <a href="#" name="btnCancel" class="button lineGray2 large">취소</a>
            <a href="#" name="btnSave" class="button line-basic large">저장</a>
            <?php
if ($mode=="UPD") {
?>
            <a href="#" name="btnDel" class="button lineRed large">삭제</a>
            <?php
}
?>
        </div>
    </div>
</div>
			
<script src="/js/ValidCheck.js?t=<?=filemtime($_SERVER['DOCUMENT_ROOT']."/js/ValidCheck.js")?>"></script>	
<script type="text/javascript">
var mc_consult_submitted = false;

$(document).on("click","a[name=btnSave]",function() {
	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidText(f.name, "거래처명") ) return false;
    if ( VC_inValidText(f.rate_fee, "수수료율") ) return false;

    if($('input[name="c_type[]"]:checked').length < 1) {
        alert("거래처 유형을 1개 이상 체크해 주십시오.    ");
        return false;
    }

	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

$(document).on("click","a[name=btnDel]",function() {
	if (!confirm("정말 삭제하시겠습니까?    ")) {
		return false;
	}
	
	if(mc_consult_submitted == true) { return false; }

	var f = document.writeForm;

	f.mode.value="DEL";
	
	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

$(document).on("click","a[name=btnCancel]",function() {

	history.back();

    return false;
});

</script>	

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>