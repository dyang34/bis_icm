<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/customer/CustomerMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractBaseChubbMgr.php";

$menuNo = [0,1,1];
$configFullScreen = 10;

if (LoginManager::getManagerLoginInfo("grade_0") < 9) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "50");

$_fg_link = RequestUtil::getParam("_fg_link", "");
if($_fg_link=="Y") {
	$_apply_date_from = RequestUtil::getParam("_apply_date_from", date("Y-m-01", strtotime("-1 year", mktime(0,0,0, date("m"), 1, date("Y")))));
} else {
	$_apply_date_from = RequestUtil::getParam("_apply_date_from", date("Y-m-01", strtotime("-3 month", mktime(0,0,0, date("m"), 1, date("Y")))));
}
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

$pg = new Page($currentPage, $pageSize);

$arrCustomer = array();

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addOrderBy("name","asc");
$rs = CustomerMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
		$arrCustomer[$row["imc_idx"]] = $row["name"];
//        array_push($arrCustomer, $row);
    }
}

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

$rs = ContractMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_apply_date_from" value="<?=$_apply_date_from?>">
    <input type="hidden" name="_apply_date_to" value="<?=$_apply_date_to?>">
    <input type="hidden" name="_start_date_from" value="<?=$_start_date_from?>">
    <input type="hidden" name="_start_date_to" value="<?=$_start_date_to?>">
    <input type="hidden" name="_imc_idx" value="<?=$_imc_idx?>">
    <input type="hidden" name="_company_type" value="<?=$_company_type?>">
    <input type="hidden" name="_policy_no" value="<?=$_policy_no?>">
    <input type="hidden" name="_seller_id" value="<?=$_seller_id?>">
    <input type="hidden" name="_name" value="<?=$_name?>">
	<input type="hidden" name="_amt_type" value="<?=$_amt_type?>">
    <input type="hidden" name="_grp_code" value="<?=$_grp_code?>">
    <input type="hidden" name="_insurance_amt_from" value="<?=$_insurance_amt_from?>">
    <input type="hidden" name="_insurance_amt_to" value="<?=$_insurance_amt_to?>">
    <input type="hidden" name="_rate_fee_from" value="<?=$_rate_fee_from?>">
	<input type="hidden" name="_rate_fee_to" value="<?=$_rate_fee_to?>">
	<input type="hidden" name="_deposit_amt_from" value="<?=$_deposit_amt_from?>">
	<input type="hidden" name="_deposit_amt_to" value="<?=$_deposit_amt_to?>">
	<input type="hidden" name="_advance_amt_from" value="<?=$_advance_amt_from?>">
	<input type="hidden" name="_advance_amt_to" value="<?=$_advance_amt_to?>">
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

<div class="list-area <?=$configFullScreen>1?"toggle_padding":""?>">
    <div class="title-area <?=$configFullScreen>0?"hide_full_screen":""?>">
        <h2>통합 계약 내역 검색</h2>
        <div class="button-right">
<?/*						
            <a href="./contract_adm_write.php" class="button Gray medium">추가</a>            
*/?>						
            <a href="#" name="btnExcelDownload" class="button excel medium">엑셀</a>
		</div>
    </div>
				
	<div class="list-search-wrap <?=$configFullScreen>0?"hide_full_screen":""?>">
        <form name="searchForm" method="get" action="contract_adm_list.php">
			<input type="hidden" name="_order_by" value="<?=$_order_by?>">
            <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">

            <table class="table-search">
                        <colgroup>
					<col style="width:7%;">
					<col style="width:26%;">
					<col style="width:7%;">
					<col style="width:27%;">
					<col style="width:7%;">
					<col style="width:26%;">
                        </colgroup>
                        <tbody>
							<tr>
                                <th>청약일자</th>
                        <td>
                            <div class="date_picker fl" style="width:150px;">
                                <input type="text" class="input" name="_apply_date_from" id="_apply_date_from" value="<?=$_apply_date_from?>" placeholder="날짜 선택" readonly>
                            </div>
                            <div class="date_picker_at">~</div>
                            <div class="date_picker fl" style="width:150px;">
                                <input type="text" class="input" name="_apply_date_to" id="_apply_date_to" value="<?=$_apply_date_to?>" placeholder="날짜 선택" readonly>
                            </div>
                        </td>
                                <th>보험시작일</th>
                        <td>
                            <div class="date_picker fl" style="width:150px;">
                                <input type="text" class="input" name="_start_date_from" id="_start_date_from" value="<?=$_start_date_from?>" placeholder="날짜 선택" readonly>
                            </div>
                            <div class="date_picker_at">~</div>
                            <div class="date_picker fl" style="width:150px;">
                                <input type="text" class="input" name="_start_date_to" id="_start_date_to" value="<?=$_start_date_to?>" placeholder="날짜 선택" readonly>
                            </div>
                        </td>
                                <th>거래처</th>
                            	<td>
<?/*                            
                            <div class="select-box">
*/?>                            
									<select name="_imc_idx" class="sel_item">
                                    	<option value="">거래처 선택</option>
<?php                                     	
foreach($arrCustomer as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$_imc_idx==$key?"selected":""?>><?=$value?></option>
<?php
}
?>
                                    </select>
<?/*                            
                            </div>
*/?>                            
								</td>
							</tr>
                            <tr>
								<th>보험사</th>
                            	<td>
                            <div class="select-box">
                                <select name="_company_type">
                						<option value="">보험사 선택</option>
<?php                                     	
foreach($arrInsuranceCompany as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$_company_type==$key?"selected":""?>><?=$value?></option>
<?php
}
?>
                					</select>
                            </div>
                                </td>         
                            	<th>증권번호</th>
                        <td><input type="text" name="_policy_no" value="<?=$_policy_no?>" class="input01" style="width: 50%;" placeholder="증권번호로 검색"></td>
                            	<th>Seller ID</th>
                        <td><input type="text" name="_seller_id" value="<?=$_seller_id?>" class="input01" style="width: 50%;" placeholder="Seller ID로 검색"></td>
                            </tr>
							<tr>
								<th>이름</th>
                        <td><input type="text" name="_name" value="<?=$_name?>" class="input01" style="width: 50%;" placeholder="이름으로 검색"></td>
                            	<th>업로드코드</th>
                        <td><input type="text" name="_grp_code" value="<?=$_grp_code?>" class="input01" style="width: 50%;" placeholder="업로드코드로 검색"></td>
                            	<th>수수료율</th>
                        <td>
							<input type="number" class="input01 fl" placeholder="" name="_rate_fee_from" style="width: 40%;" value=<?=$_rate_fee_from?>>
							<span class="input_krw">%</span>
							<span class="input_at">~</span>
							<input type="number" class="input01 fl" placeholder="" name="_rate_fee_to" style="width: 40%;" value=<?=$_rate_fee_to?>>
							<span class="input_krw">%</span>
						</td>
                            </tr>
							<tr>
								<th>보험료</th>
                        <td>
							<input type="number" class="input01 fl" placeholder="" name="_insurance_amt_from" style="width: 40%;" value=<?=$_insurance_amt_from?>>
							<span class="input_krw">원</span>
							<span class="input_at">~</span>
							<input type="number" placeholder="" class="input01 fl" name="_insurance_amt_to" style="width: 40%;" value=<?=$_insurance_amt_to?>>
							<span class="input_krw">원</span>
						</td>
                            	<th>입금금액</th>
                        <td>
							<input type="number" class="input01 fl" placeholder="" name="_deposit_amt_from" style="width: 40%;" value=<?=$_deposit_amt_from?>>
							<span class="input_krw">원</span>
							<span class="input_at">~</span>
							<input type="number" class="input01 fl" placeholder="" name="_deposit_amt_to" style="width: 40%;" value=<?=$_deposit_amt_to?>>
							<span class="input_krw">원</span>
						</td>
                            	<th>선결제</th>
                        <td>
							<input type="number" class="input01 fl" placeholder="" name="_advance_amt_from" style="width: 40%;" value=<?=$_advance_amt_from?>>
							<span class="input_krw">원</span>
							<span class="input_at">~</span>
							<input type="number" class="input01 fl" placeholder="" name="_advance_amt_to" style="width: 40%;" value=<?=$_advance_amt_to?>>
							<span class="input_krw">원</span>
						</td>
                            </tr>
                        </tbody>
                    </table>
            <div class="button-center">
<?/*                
                <a href="#" class="button lineNavy large">초기화</a>
*/?>                
                <a href="#" class="button line-basic large mgl5" name="btnSearch">검색</a>
    				</div>
				</form>
			</div>
    <div class="list-title-area <?=$configFullScreen>0?"hide_full_screen":""?>">
        <h3>총 계약 <span class="number"><?=number_format($pg->getTotalCount())?></span>건</h3>
        <div class="filter-area">
            <a href="#" name="_btn_sort" order_by="ic_idx" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="ic_idx" && $_order_by_asc=="desc"?"active":""?>">최신순</a>
            <a href="#" name="_btn_sort" order_by="apply_date" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="apply_date" && $_order_by_asc=="desc"?"active":""?>">청약일순 <i class="icon-down">내림차순</i></a>
            <a href="#" name="_btn_sort" order_by="apply_date" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="apply_date" && $_order_by_asc=="asc"?"active":""?>">청약일순 <i class="icon-up">오름차순</i></a>
            <a href="#" name="_btn_sort" order_by="start_date" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="start_date" && $_order_by_asc=="desc"?"active":""?>">거래처순 <i class="icon-down">내림차순</i></a>
            <a href="#" name="_btn_sort" order_by="start_date" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="start_date" && $_order_by_asc=="asc"?"active":""?>">거래처순 <i class="icon-up">오름차순</i></a>
            <a href="#" name="_btn_sort" order_by="name" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="name" && $_order_by_asc=="desc"?"active":""?>">이름순 <i class="icon-down">내림차순</i></a>
            <a href="#" name="_btn_sort" order_by="name" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="name" && $_order_by_asc=="asc"?"active":""?>">이름순 <i class="icon-up">오름차순</i></a>
            <a href="#" name="_btn_sort" order_by="customer_name" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="customer_name" && $_order_by_asc=="desc"?"active":""?>">보험일순 <i class="icon-down">내림차순</i></a>
            <a href="#" name="_btn_sort" order_by="customer_name" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="customer_name" && $_order_by_asc=="asc"?"active":""?>">보험일순 <i class="icon-up">오름차순</i></a>
        </div>
	</div>
           
    <div class="list-cont-wrap">
        <table class="table-basic">
            	<colgroup>
				<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
					<col>
            		<col>
            		<col>
            		<col>
            	</colgroup>
                <thead>	
                    <tr>
                    <?/* <th>
                        <div class="choice-round">
                            <input type="checkbox" id="checktb0" name="checktable" />
                            <label for="checktb0"><span class="box"></span></label>
                        </div>
                    </th>*/ ?>
                    <th>No</th>
						<th>업로드코드</th>
						<th>보험사</th>
                        <th>청약일자</th>
                        <th>보험일</th>
                        <th>거래처명 / 원거래처명</th>
                        <th>정산방법</th>
                        <th>보험료</th>
                        <th>수수료율</th>
                        <th>입금금액</th>
                        <th>선결제</th>
                        <th>납입방법 및 입금일자</th>
                        <th>상품명</th>
                        <th>플랜명</th>
                        <th>증권번호</th>
                        <th>이름</th>
                        <th>SellerID</th>
                        <th>금액구분</th>
						<th>총인원</th>
						<th>여행지</th>
						<th>처리일자</th>
						<th>추가정보</th>
						<th>추가정보2</th>
						<th>등록일</th>
                    </tr>
                </thead>
            <tbody>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                    <?/* <td>
                        <div class="choice-round">
                            <input type="checkbox" id="checktb01" name="checktable" />
                            <label for="checktb01"><span class="box"></span></label>
                        </div>
                    </td> */?>
                    <td style="text-align:center;"><?=number_format($pg->getMaxNumOfPage() - $i)?></td>
					<td><?=$row["grp_code"]?></td>
					<td><?=$arrInsuranceCompany[$row["company_type"]]?></td>
                    <td style="text-align:center;"><?=substr($row["apply_date"],0,4)."-".substr($row["apply_date"],4,2)."-".substr($row["apply_date"],6,2)?></td>
                    <td style="text-align:center;"><?=substr($row["start_date"],0,4)."-".substr($row["start_date"],4,2)."-".substr($row["start_date"],6,2)?><br/>~<?=substr($row["end_date"],0,4)."-".substr($row["end_date"],4,2)."-".substr($row["end_date"],6,2)?></td>
                        <td>
<?php
						if ($row["customer_name_org"]==$row["customer_name"]) {
							echo $row["customer_name"];
						} else {
							echo $row["customer_name"]."<br/><span style='color:orange;'>[".$row["customer_name_org"]."]</span>";
						}
?>
						</td>
                    <td style="text-align:center;"><?=$arrCustomerCalcPeriod[$row["calc_period"]]?></td>
                        <td style="text-align:right;"><?=number_format($row["insurance_amt"])?>원</td>
                        <td style="text-align:right;"><?=$row["rate_fee"]?>%</td>
						<td style="text-align:center;"><input type="number" class="input01 user_editable" name="in_deposit_amt" upd_target="deposit_amt" value="<?=$row["deposit_amt"]?>" ic_idx="<?=$row["ic_idx"]?>" fg_change="0" org_value="<?=$row["deposit_amt"]?>" style="width:100px;text-align:right;padding:0 5px;"></td>
						<td style="text-align:center;"><input type="number" class="input01 user_editable" name="in_advance_amt" upd_target="advance_amt" value="<?=$row["advance_amt"]?>" ic_idx="<?=$row["ic_idx"]?>" fg_change="0" org_value="<?=$row["advance_amt"]?>" style="width:100px;text-align:right;padding:0 5px;"></td>
                        <td style="text-align:center;"><input type="text" class="input01 user_editable" name="in_pay_type" upd_target="pay_type" value="<?=$row["pay_type"]?>" ic_idx="<?=$row["ic_idx"]?>" fg_change="0" org_value="<?=$row["pay_type"]?>" style="width:150px;"></td>
                        <td><?=$row["product_title"]?></td>
                        <td><?=$row["plan_title"]?></td>
                        <td><?=$row["policy_no"]?></td>
                        <td><?=$row["name"]?></td>
                        <td><?=$row["seller_id"]?></td>
                        <td><?=$row["amt_type"]?></td>
						<td style="text-align:right;"><?=number_format($row["cnt_member"])?>명</td>
                        <td><?=$row["trip_place"]?></td>
                    <td style="text-align:center;"><?=$row["proc_date"]?substr($row["proc_date"],0,4)."-".substr($row["proc_date"],4,2)."-".substr($row["proc_date"],6,2):""?></td>
						<td><?=$row["add_info1"]?></td>
						<td><?=$row["add_info2"]?></td>
						<td><?=substr($row["reg_date"],0,10)?></td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="24" style="text-align:center;">No Data.</td></tr>
<?php
}
?>
			</tbody>
		</table>
    </div>

	<div class="list-bottom-area">
		<?=$pg->getNaviForFuncBIS("goPage", "<<", "<", ">", ">>")?>
		<div class="button-right">
			<a href="#" name="btnSaveAll" class="button line-basic large">저장하기</a>
		</div>
	</div>

</div>

<link href="/css/select2.css?v=<?=time()?>" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".sel_item").select2();

	var w = $(".select2").css('width');
	add_w = parseInt(w)+50;
	$(".select2").css('width',add_w);
});

function reset_Select2(){
	$(".sel_item").val('');
	$(".sel_item").trigger('change');
}

</script>

<script type="text/javascript" src="/js/calendar-ui.js?v=<?=time()?>"></script>
<script type="text/javascript">
$(document).ready(function() {
<?/*    
	var today = new Date();
	var tomorrow = new Date(Date.parse(today) + (1000 * 60 * 60 * 24));
*/?>

    $("#_apply_date_from").datepicker();
	$("#_apply_date_to").datepicker();
    $("#_start_date_from").datepicker();
	$("#_start_date_to").datepicker();
});
</script>

<script src="/js/ValidCheck.js?t=<?=filemtime($_SERVER['DOCUMENT_ROOT']."/js/ValidCheck.js")?>"></script>	
<script type="text/javascript">

var arr_paste_col_idx = [9, 10, 11];
var col_idx_deposit_amt = 9;
var col_idx_advance_amt = 10;
var col_idx_pay_type = 11;

$(document).ready(function() {

	$(document).on('paste', 'input', function(e){
		var $this= $(this);
		var pasted;
		var bbbb, cccc;

		if (window.clipboardData && window.clipboardData.getData) {	/** ie 용 **/
			
			pasted = window.clipboardData.getData('Text');
		} else if (e.originalEvent.clipboardData.getData) {			/** 그 외 **/
			pasted = e.originalEvent.clipboardData.getData('text/plain');
		}

		pasted = pasted.trim('\r\n');
		bbbb = pasted.split('\r\n');
		cccc = bbbb[0].split('\t');
		
		e.stopPropagation();
		e.preventDefault();    

		var td_idx = $this.closest('td').index();
		var tr_idx = $this.closest('tr').index();
//		var mm = $this.closest('td').length;
//		var obj = {};
		//console.log("tr Idx:"+tr_idx+"td Idx:"+td_idx+"클립보드 col수"+cccc.length);
/*		
		var limit_td = 14;
		if((td_idx+(cccc.length - 1)) > limit_td){
			alert('복사할 데이터가 셀보다 큽니다.');
			return false;
		}
*/
		var rejectno = 0;
		$.each(pasted.split('\r\n'), function(idx_y, val_y){
			$.each(val_y.split('\t'),function(idx_x, val_x){
				var row = tr_idx+idx_y, col = td_idx+idx_x;
//				obj['cell-'+row+'-'+col] = val_x;
				val_x = val_x.trim();
				
				if (arr_paste_col_idx.includes(col)) {
					if(col==col_idx_deposit_amt || col==col_idx_advance_amt) {
						val_x = parseInt(val_x);
					}
					
					obj_td = $this.closest('table').find('tr:eq('+(row+1)+') td:eq('+col+') input');
					obj_td.val(val_x);

					if(val_x != obj_td.attr("org_value")) {
						obj_td.closest("td").css('background-color','#FFA7A7');
						obj_td.attr("fg_change", "1");
					} else {
						obj_td.closest("td").css('background-color','white');
						obj_td.attr("fg_change", "0");
					}
/*
					//var prt_val = rep_juno(val_x);
					
					if(col == 4){
						prt_val = rep_juno(val_x);
						$this.closest('table').find('tr:eq('+(row+1)+') td:eq('+col+') input').val(prt_val);     
						console.log('지정된 col : '+ju_chk(val_x));

						if( ju_chk(prt_val) < 1){
							age_calcultor(prt_val, row, col);  
							$this.closest('table').find('tr:eq('+(row+1)+') td:eq('+col+') input').css('color', '#777');
						} else {
							$this.closest('table').find('tr:eq('+(row+1)+') td:eq('+col+') input').css('color', 'red');
							$this.closest('table').find('tr:eq('+(row+1)+') td:eq('+(col+1)+') input').val('');
							$this.closest('table').find('tr:eq('+(row+1)+') td:eq('+(col+1)+')').contents().filter(function(){return this.nodeType == 3}).remove();
							rejectno = rejectno + 1;
						}
					}
*/					
				}
			});
		});
		if(rejectno > 0){
			alert('적합하지않는 주민번호가 있습니다.');
			return false;
		}    
//		$('#viewprt').text(JSON.stringify(obj));
//		console.log(JSON.stringify(obj));
	});

	$(document).on("change",".user_editable",function() {

		$(this).val(parseInt($(this).val()));

		if($(this).val() != $(this).attr("org_value")) {
			$(this).closest("td").css('background-color','#FFA7A7');
			$(this).attr("fg_change", "1");
		} else {
			$(this).closest("td").css('background-color','white');
			$(this).attr("fg_change", "0");
		}
		
	});

	$(document).on("click","a[name=btnSearch]",function() {
		
		var f = document.searchForm;

		if ( VC_inValidDate(f._apply_date_from, "청약일자 시작일") ) return false;
		if ( VC_inValidDate(f._apply_date_to, "청약일자 종료일") ) return false;
	/*
		var arrFromDate=f._order_date_from.value.split('-');
		var arrToDate=f._order_date_to.value.split('-');
		
		var fromDate = new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]);
		var toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);

		toDate.setMonth(toDate.getMonth()-12);
		
		if (fromDate < toDate) {
			alert("최대 12개월 단위로 조회하실 수 있습니다.    ");
			f._order_date_from.focus();
		
			return false;
		}
	*/	
		f.submit();	
	});

	$(document).on('click','a[name=btnSaveAll]', function() {

		var arrData = new Array();

		if (confirm("저장하시겠습니까?    ")) {
	/*
			$("input[name=in_deposit_amt][fg_change=1]").each(function(index, item) {
				arrData.push(['deposit_amt', $(item).attr("ic_idx"), $(item).val()]);
			});

			$("input[name=in_advance_amt][fg_change=1]").each(function(index, item) {
				arrData.push(['advance_amt', $(item).attr("ic_idx"), $(item).val()]);
			});

			$("input[name=in_pay_type][fg_change=1]").each(function(index, item) {
				arrData.push(['pay_type', $(item).attr("ic_idx"), $(item).val()]);
			});
	*/

			$(".user_editable[fg_change=1]").each(function(index, item) {
				arrData.push([$(item).attr("ic_idx"), $(item).attr("upd_target"), $(item).val()]);
			});

			if(arrData.length > 0) {

				$.ajax({
					url: '/ajax/ajax_contract_arr_update.php',
					type: 'POST',
					dataType: "json",
					async: true,
					cache: false,
					data: {
						mode : 'UPD_ARR',
						dataArr : JSON.stringify(arrData)
					},
					success: function (response) {
						switch(response.RESULTCD){
							case "SUCCESS" :
								location.reload();
								break;
							case "not_login" :
								alert("로그인 후 작업하시기 바랍니다.    ");
								break;                    
							case "no_data" :
								alert("데이터 에러입니다.    ");
								break;                    
							case "no_mode" :
								alert("모드 에러입니다.    ");
								break;                    
							case "error" :
								alert("시스템 연동시 에러가 발생하였습니다.    ");
								break;                    
							default:
								alert("시스템 오류입니다.\r\n문의주시기 바랍니다.    ");
								break;
						}
					},
					complete:function(){},
					error: function(xhr){}
				});
			} else {
				alert("수정된 Data가 없습니다.    ");
			}

		}

	});

	$(document).on('click','a[name=btnExcelDownload]', function() {
<?php
	if($pg->getTotalCount() > 10000) {
?>
		alert("10,000건 이상의 대량 데이터는 다운로드 할 수 없습니다.    ");
		return false;
<?
	} else if($pg->getTotalCount() >= 1000) {
?>
		if (!confirm("1,000건 이상의 대량 데이터를 다운로드 하시겠습니까?    ")) {
			return false;
		}
<?php
	}
?>			
		var f = document.pageForm;
		f.target = "_new";
		f.action = "contract_list_xls.php";
		
		f.submit();
	});

	$(document).on('click', 'a[name=_btn_sort]', function() {
		goSort($(this).attr('order_by'), $(this).attr('order_by_asc'));
	});
});

const goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "contract_adm_list.php";
	f.submit();
}

const goSort = function(p_order_by, p_order_by_asc) {
	var f = document.pageForm;
	f.currentPage.value = 1;
	f._order_by.value = p_order_by;
	f._order_by_asc.value = p_order_by_asc;
	f.action = "contract_adm_list.php";
	f.submit();
}
</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

@ $rs->free();
?>