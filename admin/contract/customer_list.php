<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/customer/CustomerMgr.php";

$menuNo = [0,2,0];
$configFullScreen = 10;

if (LoginManager::getManagerLoginInfo("grade_0") < 10) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "50");

$_name = RequestUtil::getParam("_name", "");
$_rate_fee_from = RequestUtil::getParam("_rate_fee_from", "");
$_rate_fee_to = RequestUtil::getParam("_rate_fee_to", "");
$_calc_period = RequestUtil::getParam("_calc_period", "");
$_type = RequestUtil::getParam("_type", "");

$_order_by = RequestUtil::getParam("_order_by", "imc_idx");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$pg = new Page($currentPage, $pageSize);

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addAndLike("name",$_name);
$wq->addAndString("calc_period","=",$_calc_period);
$wq->addAndLike("type",$_type);

if($_rate_fee_from != "") {
	$wq->addAndString2("rate_fee", ">=", $_rate_fee_from);
}

if($_rate_fee_to != "") {
	$wq->addAndString2("rate_fee", "<=", $_rate_fee_to);
}

$wq->addOrderBy($_order_by,$_order_by_asc);
$wq->addOrderBy("imc_idx","desc");

$rs = CustomerMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_name" value="<?=$_name?>">
    <input type="hidden" name="_calc_period" value="<?=$_calc_period?>">
    <input type="hidden" name="_type" value="<?=$_type?>">
    <input type="hidden" name="_rate_fee_from" value="<?=$_rate_fee_from?>">
    <input type="hidden" name="_rate_fee_to" value="<?=$_rate_fee_to?>">

    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

<div class="list-area <?=$configFullScreen>1?"toggle_padding":""?>">
    <div class="title-area <?=$configFullScreen>0?"hide_full_screen":""?>">
        <h2>거래처 검색</h2>
        <div class="button-right">
            <a href="./customer_write.php" class="button Gray medium">추가</a>            
            <a href="#" name="btnExcelDownload" class="button excel medium">엑셀</a>
        </div>
                </div>
				
	<div class="list-search-wrap <?=$configFullScreen>0?"hide_full_screen":""?>">
        <form name="searchForm" method="get" action="customer_list.php">
            <input type="hidden" name="_order_by" value="<?=$_order_by?>">
            <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
            <table class="table-search">
                <colgroup>
                    <col style="width:6%;">
                    <col style="width:19%;">
                    <col style="width:6%;">
                    <col style="width:19%;">
                    <col style="width:6%;">
                    <col style="width:19%;">
                    <col style="width:6%;">
                    <col style="width:19%;">
                </colgroup>
                <tbody>
                    <tr>
                        <th>거래처명</th>
                        <td><input type="text" placeholder="거래처명으로 검색" name="_name" class="input01" value=<?=$_name?>></td>
                        <th>수수료율</th>
                        <td>
                            <input type="number" class="input01 fl" placeholder="" name="_rate_fee_from" style="width: 35%;" value=<?=$_rate_fee_from?>>
                            <span class="input_krw">%</span>
                            <span class="input_at">~</span>
                            <input type="number" class="input01 fl" placeholder="" name="_rate_fee_to" style="width: 35%;" value=<?=$_rate_fee_to?>>
                            <span class="input_krw">%</span>
                        </td>
                        <th>정산주기</th>
                        <td>
                            <div class="select-box">                                    
                                <select name="_calc_period">
                                    <option value="">정산 주기</option>
<?php
$arrCustomerCalcPeriodKey = array_keys($arrCustomerCalcPeriod);
$arrCustomerCalcPeriodVal = array_values($arrCustomerCalcPeriod);

for($ii=0;$ii<count($arrCustomerCalcPeriod);$ii++) {
?>
        <option value="<?=$arrCustomerCalcPeriodKey[$ii]?>" <?=$_calc_period==$arrCustomerCalcPeriodKey[$ii]?"selected":""?>><?=$arrCustomerCalcPeriodVal[$ii]?></option>
<?php
}
?>                                        
                                </select>    
                            </div>
                        </td>
                        <th>유형</th>
                        <td>
                            <div class="select-box">                                    
                                <select name="_type">
                                    <option value="">유형</option>
<?php
$arrCustomerTypeKey = array_keys($arrCustomerType);
$arrCustomerTypeVal = array_values($arrCustomerType);

for($ii=0;$ii<count($arrCustomerType);$ii++) {
?>
        <option value="<?=$arrCustomerTypeKey[$ii]?>" <?=$_type==$arrCustomerTypeKey[$ii]?"selected":""?>><?=$arrCustomerTypeVal[$ii]?></option>
<?php
}
?>                                        
                                </select>    
                            </div>
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
        <h3>총 DATA <span class="number"><?=number_format($pg->getTotalCount())?></span>건</h3>
				
        <div class="filter-area">
            <a href="#none" name="_btn_sort" order_by="imc_idx" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="imc_idx" && $_order_by_asc=="desc"?"on":""?>">최신순</a>
            <a href="#none" name="_btn_sort" order_by="rate_fee" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="rate_fee" && $_order_by_asc=="asc"?"on":""?>">수수료율 <i class="icon-up">오름차순</i></a>
            <a href="#none" name="_btn_sort" order_by="rate_fee" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="rate_fee" && $_order_by_asc=="desc"?"on":""?>">수수료율 <i class="icon-down">내림차순</i></a>
            <a href="#none" name="_btn_sort" order_by="calc_period" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="calc_period" && $_order_by_asc=="asc"?"on":""?>">정산주기 <i class="icon-up">오름차순</i></a>
            <a href="#none" name="_btn_sort" order_by="calc_period" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="calc_period" && $_order_by_asc=="desc"?"on":""?>">정산주기 <i class="icon-down">내림차순</i></a>
            <a href="#none" name="_btn_sort" order_by="name" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="name" && $_order_by_asc=="asc"?"on":""?>">거래처명 <i class="icon-up">오름차순</i></a>
            <a href="#none" name="_btn_sort" order_by="name" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="name" && $_order_by_asc=="desc"?"on":""?>">거래처명 <i class="icon-down">내림차순</i></a>
        </div>
    </div>
           
    <div class="list-cont-wrap">
        <table class="table-basic">
        <colgroup>
            <col>
            <col>
<?/*            		
            <col>
*/?>            		
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
                <th>No</th>
<?php/*                
                        <th>거래처코드</th>
*/?>
                        <th>유형</th>
                        <th>거래처명</th>
                        <th>수수료율</th>
                        <th>정산주기</th>
                        <th>이메일</th>
                        <th>연락처1</th>
                        <th>연락처2</th>
                        <th>은행</th>
                        <th>계좌번호</th>
                        <th>예금주</th>
                        <th>메모</th>
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

                    	<td style="text-align:center;"><?=number_format($pg->getMaxNumOfPage() - $i)?></td>
                        <td style="text-align:center;">
<?php
/*
        $arrRowType = explode('|+|', $row['type']);
        $typeTxt = "";

        for($i_s=0;$i_s<count($arrCustomerType);$i_s++) {
            if($arrRowType[$i_s] > 0) {
                $typeTxt .= $arrCustomerType[$i_s][1];
            }
        }

        echo $typeTxt;
*/
        echo $row['type'];
?>                    
                    </td>
<?php/*                
                        <td style="text-align:center;"><?=$row["code"]?></td>
*/?>
                        <td><a href="./customer_write.php?mode=UPD&imc_idx=<?=$row["imc_idx"]?>"><?=$row["name"]?></a></td>
                        <td style="text-align:center;"><?=$row["rate_fee"]?></td>
                        <td style="text-align:center;">
<?php
                            $font_color = $row["calc_period"]=="D"?"#FF5A5A":"#4948FF";
?>
                            <span style="color:<?=$font_color?>"><?=$arrCustomerCalcPeriod[$row["calc_period"]]?></span>
            </td>
                        <td style="text-align:center;"><?=$row["email"]?></td>
                        <td style="text-align:center;"><?=$row["tel1"]?></td>
                        <td style="text-align:center;"><?=$row["tel2"]?></td>
                        <td style="text-align:center;"><?=$row["account_bank"]?></td>
                        <td style="text-align:center;"><?=$row["account_no"]?></td>
                        <td style="text-align:center;"><?=$row["account_holder"]?></td>
                        <td style="text-align:center;"><?=nl2br($row["memo"])?></td>
                        <td style="text-align:center;"><?=$row["reg_date"]?></td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="15" style="text-align:center;">No Data.</td></tr>
<?php
}
?>
                </tbody>
            </table>
    		</div>
            
	<div class="list-bottom-area">
		<?=$pg->getNaviForFuncBIS("goPage", "<<", "<", ">", ">>")?>
		<div class="button-right">
			<a href="./customer_write.php" class="button line-basic large">등록하기</a>
		</div>
    </div>

<script src="/js/ValidCheck.js"></script>
<script type="text/javascript">
$(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;
	
    f.submit();	
});

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "customer_list_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "customer_list.php";
	f.submit();
}

$(document).on('click', 'a[name=_btn_sort]', function() {
	goSort($(this).attr('order_by'), $(this).attr('order_by_asc'));
});

var goSort = function(p_order_by, p_order_by_asc) {
	var f = document.pageForm;
	f.currentPage.value = 1;
	f._order_by.value = p_order_by;
	f._order_by_asc.value = p_order_by_asc;
	f.action = "customer_list.php";
	f.submit();
}

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

@ $rs->free();
?>