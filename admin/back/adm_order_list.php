<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/goods/GoodsItemMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/brand/BrandMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/channel/ChannelMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/category/CategoryMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/sales_type/SalesTypeMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/status/StatusMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/order/OrderMgr.php";

$menuCate = 2;
$menuNo = 25;

if (LoginManager::getManagerLoginInfo("iam_grade") < 8 || LoginManager::getManagerLoginInfo("iam_grade") == 9) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_order_date_from = RequestUtil::getParam("_order_date_from", date("Y-m-01"));
$_order_date_to = RequestUtil::getParam("_order_date_to", date("Y-m-d"));
$_imc_idx = RequestUtil::getParam("_imc_idx", "");
$_imb_idx = RequestUtil::getParam("_imb_idx", "");
$_cate1_idx = RequestUtil::getParam("_cate1_idx", "");
$_cate2_idx = RequestUtil::getParam("_cate2_idx", "");
$_cate3_idx = RequestUtil::getParam("_cate3_idx", "");
$_cate4_idx = RequestUtil::getParam("_cate4_idx", "");
$_tax_type = RequestUtil::getParam("_tax_type", "");
$_order_type = RequestUtil::getParam("_order_type", "");
$_goods_mst_code = RequestUtil::getParam("_goods_mst_code", "");
$_goods_name = RequestUtil::getParam("_goods_name", "");
$_item_code = RequestUtil::getParam("_item_code", "");
$_item_name = RequestUtil::getParam("_item_name", "");
$_except_cancel = RequestUtil::getParam("_except_cancel", "");
$_status = RequestUtil::getParam("_status", "");
$_order_no = RequestUtil::getParam("_order_no", "");

$_order_by = RequestUtil::getParam("_order_by", "order_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$pg = new Page($currentPage, $pageSize);

$arrDayOfWeek = array("일","월","화","수","목","금","토");

$arrChannel = $arrBrand = $arrCategory1 = $arrCategory2 = $arrCategory3 = $arrCategory4 = $arrSalesType = $arrStatus = array();

$wq = new WhereQuery(true, true);
$wq->addOrderBy("sort","asc");
$rs = StatusMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        array_push($arrStatus, $row);
    }
}

$wq = new WhereQuery(true, true);
$rs = SalesTypeMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $arrSalesType[$row["imst_idx"]] = $row["title"];
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");

$wq->addOrderBy("imst_idx","");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = ChannelMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_channel = $rs->fetch_assoc();
        
        array_push($arrChannel, $row_channel);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imb_fg_del","=","0");

$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = BrandMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_brand = $rs->fetch_assoc();
        
        array_push($arrBrand, $row_brand);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imct_fg_del","=","0");
$wq->addAndString("depth","=","1");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("title","asc");

$rs = CategoryMgr::getInstance()->getList($wq);

if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_category = $rs->fetch_assoc();
        
        array_push($arrCategory1, $row_category);
    }
}


if($_cate1_idx && $_cate1_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","2");
    $wq->addAndString("upper_imct_idx","=",$_cate1_idx);
    $wq->addOrderBy("sort","desc");
    $wq->addOrderBy("title","asc");
    
    $rs = CategoryMgr::getInstance()->getList($wq);
    
    if($rs->num_rows > 0) {
        for($i=0;$i<$rs->num_rows;$i++) {
            $row_category = $rs->fetch_assoc();
            
            array_push($arrCategory2, $row_category);
        }
    }
}

if($_cate2_idx && $_cate2_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","3");
    $wq->addAndString("upper_imct_idx","=",$_cate2_idx);
    $wq->addOrderBy("sort","desc");
    $wq->addOrderBy("title","asc");
    
    $rs = CategoryMgr::getInstance()->getList($wq);
    
    if($rs->num_rows > 0) {
        for($i=0;$i<$rs->num_rows;$i++) {
            $row_category = $rs->fetch_assoc();
            
            array_push($arrCategory3, $row_category);
        }
    }
}

if($_cate3_idx && $_cate3_idx > 0) {
    $wq = new WhereQuery(true, true);
    $wq->addAndString2("imct_fg_del","=","0");
    $wq->addAndString("depth","=","4");
    $wq->addAndString("upper_imct_idx","=",$_cate3_idx);
    $wq->addOrderBy("sort","desc");
    $wq->addOrderBy("title","asc");
    
    $rs = CategoryMgr::getInstance()->getList($wq);
    
    if($rs->num_rows > 0) {
        for($i=0;$i<$rs->num_rows;$i++) {
            $row_category = $rs->fetch_assoc();
            
            array_push($arrCategory4, $row_category);
        }
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString("order_date", ">=", $_order_date_from);
$wq->addAndStringBind("order_date", "<", $_order_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("imc_idx", "=", $_imc_idx);
$wq->addAndString("imb_idx", "=", $_imb_idx);
$wq->addAndString("cate1_idx", "=", $_cate1_idx);
$wq->addAndString("cate2_idx", "=", $_cate2_idx);
$wq->addAndString("cate3_idx", "=", $_cate3_idx);
$wq->addAndString("cate4_idx", "=", $_cate4_idx);
$wq->addAndString("tax_type", "=", $_tax_type);
$wq->addAndString("order_type", "=", $_order_type);
$wq->addAndString("goods_mst_code", "=", $_goods_mst_code);
$wq->addAndString("a.item_code", "=", $_item_code);
$wq->addAndString("status", "=", $_status);
$wq->addAndString("order_no", "=", $_order_no);

$wq->addAndLike("name",$_goods_name);
$wq->addAndLike("item_name",$_item_name);

if($_except_cancel) {
    $wq->addAndNotIn("status", array("취소접수","취소완료","삭제"));
}

$wq->addOrderBy($_order_by, $_order_by_asc);

if ($_order_by=="cate1_name") {
    $wq->addOrderBy("cate2_name", "asc");
    $wq->addOrderBy("cate3_name", "asc");
    $wq->addOrderBy("cate4_name", "asc");
}

$wq->addOrderBy("order_date", "desc");

$rs = OrderMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_order_date_from" value="<?=$_order_date_from?>">
    <input type="hidden" name="_order_date_to" value="<?=$_order_date_to?>">
    <input type="hidden" name="_imc_idx" value="<?=$_imc_idx?>">
    <input type="hidden" name="_imb_idx" value="<?=$_imb_idx?>">
    <input type="hidden" name="_cate1_idx" value="<?=$_cate1_idx?>">
    <input type="hidden" name="_cate2_idx" value="<?=$_cate2_idx?>">
    <input type="hidden" name="_cate3_idx" value="<?=$_cate3_idx?>">
    <input type="hidden" name="_cate4_idx" value="<?=$_cate4_idx?>">
    <input type="hidden" name="_tax_type" value="<?=$_tax_type?>">
    <input type="hidden" name="_order_type" value="<?=$_order_type?>">
    <input type="hidden" name="_goods_mst_code" value="<?=$_goods_mst_code?>">
    <input type="hidden" name="_goods_name" value="<?=$_goods_name?>">
	<input type="hidden" name="_item_code" value="<?=$_item_code?>">
	<input type="hidden" name="_item_name" value="<?=$_item_name?>">
	<input type="hidden" name="_except_cancel" value="<?=$_except_cancel?>">
	<input type="hidden" name="_status" value="<?=$_status?>">
	<input type="hidden" name="_order_no" value="<?=$_order_no?>">
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

            <!-- 상품검색(s) -->
            <div>
                <div style="padding-left:20px;">
                    <h3 class="icon-search">판매 내역 검색</h3>
                    <ul class="icon_Btn">
                        <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                    </ul>
                </div>
				<form name="searchForm" method="get" action="adm_order_list.php">
				
                    <table class="adm-table">
                        <caption>상품 검색</caption>
                        <colgroup>
                            <col style="width:8%;">
                            <col style="width:25%;">
                            <col style="width:9%;">
                            <col style="width:25%;">
                            <col style="width:8%;">
                            <col style="width:25%;">
                        </colgroup>
                        <tbody>
							<tr>
                                <th>판매일자</th>
                                <td><input type="date" id="_order_date_from" name="_order_date_from" class="date_in" value="<?=$_order_date_from?>" style="padding:0 16px;">~<input type="date" id="_order_date_to" name="_order_date_to" value="<?=$_order_date_to?>" class="date_in" style="padding:0 16px;"></td>
                            	<th>판매유형/거래처(채널)</th>
                            	<td colspan="3">
									<select name="_order_type" class="sel_order_type">
                                    	<option value="">판매 유형</option>
<?php                                     	
foreach($arrSalesType as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$_order_type==$key?"selected":""?>><?=$value?></option>
<?php
}
?>
                                    </select>
                                    <select name="_imc_idx" class="sel_channel">
                						<option value="">거래처(채널) 선택</option>
                						<?php
                						foreach($arrChannel as $lt){
                							?>
                							<option value="<?=$lt['imc_idx']?>" <?=$_imc_idx==$lt['imc_idx']?"selected":""?>><?="[".$lt['sales_type_title']."] ".$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>                           
							</tr>
							<tr>
                                <th>카테고리</th>
                                <td colspan="3">
									<select name="_cate1_idx" class="sel_category" depth="1">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory1 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate1_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate2_idx" class="sel_category" depth="2" style="<?=(count($arrCategory2)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory2 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate2_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate3_idx" class="sel_category" depth="3" style="<?=(count($arrCategory3)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory3 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate3_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                					<select name="_cate4_idx" class="sel_category" depth="4" style="<?=(count($arrCategory4)>0)?"":"display:none;"?>">
                						<option value="">카테고리 선택</option>
                						<?php
                						foreach($arrCategory4 as $lt){
                							?>
                							<option value="<?=$lt['imct_idx']?>" <?=$_cate4_idx==$lt['imct_idx']?"selected":""?>><?=$lt['title']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                                <th>브랜드</th>
                                <td>
                                    <select name="_imb_idx" class="select_brand">
                						<option value="">브랜드 선택</option>
                						<?php
                						foreach($arrBrand as $lt){
                							?>
                							<option value="<?=$lt['imb_idx']?>" <?=$_imb_idx==$lt['imb_idx']?"selected":""?>><?=$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                            </tr>
                            <tr>
                            	<th>상품코드</th>
                            	<td><input type="text" placeholder="상품코드로 검색" name="_goods_mst_code" style="width: 100%;" value=<?=$_goods_mst_code?>></td>
                            	<th>상품명</th>
                            	<td><input type="text" placeholder="상품명으로 검색" name="_goods_name" style="width: 100%;" value=<?=$_goods_name?>></td>
                                <th>과세구분</th>
                                <td>
                                	<select name="_tax_type">
                                    	<option value="">과세 구분</option>
                                    	<option value="과세" <?=$_tax_type=="과세"?"selected":""?>>과세</option>
                                    	<option value="면세" <?=$_tax_type=="면세"?"selected":""?>>면세</option>
                                    </select>
								</td>
                            </tr>
                            <tr>
                            	<th>품목(옵션)코드</th>
                            	<td><input type="text" placeholder="품목(옵션)명으로 검색" name="_item_code" style="width: 100%;" value=<?=$_item_code?>></td>
                            	<th>품목(옵션)명</th>
                            	<td><input type="text" placeholder="품목(옵션)명으로 검색" name="_item_name" style="width: 100%;" value=<?=$_item_name?>></td>
                            	<th>상태</th>
                            	<td>
                            	<select name="_status" class="sel_status">
                                    	<option value="">상태</option>
<?php                                     	
foreach($arrStatus as $lt){
?>
                							<option value="<?=$lt['title_status']?>" <?=$_status==$lt['title_status']?"selected":""?>><?=$lt['title_status']?></option>
                							<?php
}?>
                                    </select>
                            	<input type="checkbox" value="1" name="_except_cancel" id="_except_cancel" <?=$_except_cancel?"checked='checked'":""?>><label for="_except_cancel">취소/삭제 제외</label></td>
                            </tr>
                            <tr>
                            	<th>주문번호</th>
                            	<td><input type="text" placeholder="주문번호로 검색" name="_order_no" style="width: 100%;" value=<?=$_order_no?>></td>
                            	<th></th>
                            	<td></td>
                            	<th></th>
                            	<td></td>
                            </tr>
                        </tbody>
                    </table>
    				<!-- 검색버튼 START -->
    				<div class="wms_searchBtn">
    					<a href="#" class="icm_btnSearch" name="btnSearch">검색</a>
    				</div>
    				<!-- 검색버튼 END -->
				</form>
			</div>
			<!-- 상품검색(e) -->
                
			<div class="float-wrap">
				<h3 class="float-l">총 판매 <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
				<p class="list-adding float-r">
					<a href="#none" name="_btn_sort" order_by="order_date" order_by_asc="desc" class="<?=$_order_by=="order_date" && $_order_by_asc=="desc"?"on":""?>" >판매일순<em>▼</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="asc" class="<?=$_order_by=="name" && $_order_by_asc=="asc"?"on":""?>">상품명<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="desc" class="<?=$_order_by=="name" && $_order_by_asc=="desc"?"on":""?>">상품명<em>▼</em></a>
				</p>
			</div>
           
            <!-- 메인TABLE(s) -->
            <table class="display odd_color" cellpadding="0" cellspacing="0">
            	<colgroup>
            		<col style="width:110px;">
            		<col style="width:70px;">
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col style="width:150px;">
            		<col>
            		<col>
            		<col>
            		<col style="width:80px;">
            		<col style="width:70px;">
            		<col style="width:70px;">
            		<col style="width:100px;">
            		<col style="width:70px;">
            	</colgroup>
                <thead>
                    <tr>
<?php /*                    
                        <th class="tbl_first">No</th>
                        <th>주문일시</th>
*/?>
                        <th>주문일시</th>
                        <th>주문번호</th>
                        <th>판매유형</th>
                        <th>거래처(채널)</th>
                        <th>브랜드</th>
                        <th>상품코드</th>
                        <th>상품명</th>
                        <th>옵션코드</th>
                        <th>옵션명</th>
                        <th>주문번호</th>
                        <th>수량</th>
                        <th>EA</th>
                        <th>판매가</th>
                        <th>상태</th>
                        <th>과/면세</th>
                        <th>작업일</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 2px solid #395467">
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
                    
                    <tr>
<?php /*                    
                    	<td class="tbl_first" style="text-align:center;"><?=number_format($pg->getMaxNumOfPage() - $i)?></td>
*/?>
                        <td class="tbl_first txt_c"><?=substr($row["order_date"],0,10)." ".$arrDayOfWeek[date('w', strtotime(substr($row["order_date"],0,10)))]?></td>
                        <td class="txt_c"><?=$row["order_no"]?></td>
                        <td class="txt_c" style="<?=$row["order_type"]>"1"?"color:green;":""?> ?>"><?=$arrSalesType[$row["order_type"]]?></td>
                        <td class="txt_c" style="<?=$row["imc_idx"]>"1"?"color:green;":""?> ?>"><?=$row["channel"]?></td>
                        <td class="txt_c"><?=$row["brand_name"]?></td>
                        <td><?=$row["code"]?></td>
                        <td><?=$row["name"]?></td>
                        <td><?=$row["item_code"]?></td>
                        <td><?=$row["item_name"]?></td>
                        <td class="txt_c"><?=$row["order_no"]?></td>
                        <td class="txt_r"><?=number_format($row["amount"])?></td>
                        <td class="txt_r"><?=number_format($row["ea"])?></td>
                        <td class="txt_r"><?=number_format($row["price"])?></td>
                        <td class="txt_c"><?=$row["status"]?></td>
                        <td class="txt_c"><?=$row["tax_type"]?></td>
                        <td class="txt_c"><?=substr($row["reg_date"],0,10)?></td>
                        <td style="text-align:center;">
<?php
if ($row["status"] != "삭제") {
?>                        
                        <a href="#" name="btnDel" order_no="<?=$row["order_no"]?>" style=" display: block; background-color: #1b80c3; padding: 6px 0px; width:50px; border-radius: 20px; color: #fff;">삭제</a>
<?php
}
?>
                        </td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="15" class="txt_c">No Data.</td></tr>
<?php
}
?>
                </tbody>
            </table>
            <!-- 메인TABLE(e) -->
			<p class="hide"><strong>Pagination</strong></p>
			<div style="position: relative;">
    			<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
<?php /*    			
    			<div style="position: absolute; right: 17px; bottom: 3px; text-align: center; line-height: 30px; border-radius: 10px; background-color: #313A3D;" class="rig_new"><a href="./goods_write.php" style="display:inline-block;padding: 5px 22px;color: #fff;">등록하기</a></div>
*/?>
    		</div>
    		
		<a href="#none" onclick="javascript:goPageTop();"  style="position: fixed; right: 31px; bottom: 31px; width: 67px; height: 67px; line-height: 70px; background-color: #313A3D; border: none; border-radius: 50%; z-index: 999; box-sizing: border-box; color: #fff; letter-spacing: .3px; text-align: center;">TOP<img src="/images/common/top.png" alt="" style=" margin: -2px 0 0 2px;"/></a>
    		
<script src="/js/ValidCheck.js"></script>
<script type="text/javascript">

function addMonth(date, month) {
    let addMonthFirstDate = new Date(date.getFullYear(),date.getMonth() + month,1);	// month달 후의 1일
    let addMonthLastDate = new Date(addMonthFirstDate.getFullYear(),addMonthFirstDate.getMonth() + 1, 0);	// month달 후의 말일
    
    let result = addMonthFirstDate;
    if(date.getDate() > addMonthLastDate.getDate()) {
    	result.setDate(addMonthLastDate.getDate());
    } else {
    	result.setDate(date.getDate());
    }
    
    return result;
}

$(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;

    if ( VC_inValidDate(f._order_date_from, "판매일자 시작일") ) return false;
    if ( VC_inValidDate(f._order_date_to, "판매일자 종료일") ) return false;

	let arrFromDate=f._order_date_from.value.split('-');
	let arrToDate=f._order_date_to.value.split('-');
	
	let fromDate = addMonth(new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]), 12);
	let toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);
		
	if (fromDate <= toDate) {
		alert("최대 1년 단위로 조회하실 수 있습니다.    ");
		f._order_date_from.focus();
	
		return false;
	}

    f.submit();	
});

$(document).on('change','.sel_category',function() {
	var obj_select, obj_select_other;
	var next_depth = parseInt($(this).attr('depth'))+1;
	var i;

	if($("option:selected", this).val()!=="") {
	
    	obj_select = $('.sel_category[depth='+next_depth+']');
    	
    	for(i=next_depth+1;i<=4;i++) {
    		$('.sel_category[depth='+i+']').css("display","none");
    		$('.sel_category[depth='+i+'] option:eq(0)').prop("selected",true);
    	}

    	$.ajax({
    		url: "/icm/ajax/ajax_category.php",
    		data: {upper_imct_idx: $("option:selected", this).val()},
    		async: true,
    		cache: false,
    		error: function(xhr){	},
    		success: function(data){
    		
    			if(data.length > 10) {
        			obj_select.html(data);
        			
        			obj_select.css("display","inline-block");
    			} else {
    				obj_select.css("display","none");
    			}
    		}
    	});
	} else {
    	for(i=next_depth;i<=4;i++) {
    		$('.sel_category[depth='+i+']').css("display","none");
    		$('.sel_category[depth='+i+'] option:eq(0)').prop("selected",true);
    	}
	}
});

$(document).on('change','.sel_order_type',function() {
	var obj_select

	obj_select = $('.sel_channel');

	$.ajax({
		url: "/icm/ajax/ajax_channel.php",
		data: {imst_idx: $("option:selected", this).val()},
		async: true,
		cache: false,
		error: function(xhr){	},
		success: function(data){
			obj_select.html(data);
		}
	});
});

$(document).on('click','a[name=btnExcelDownload]', function() {

	var f = document.pageForm;
	
	let arrFromDate=f._order_date_from.value.split('-');
	let arrToDate=f._order_date_to.value.split('-');
	
	let fromDate = addMonth(new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]), 1);
	let toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);

	if (fromDate <= toDate) {
		alert("엑셀 다운로드는 최대 1개월 단위로 다운로드 하실 수 있습니다.    ");
		f._order_date_from.focus();
	
		return false;
	}
	
	f.target = "_new";
	f.action = "adm_order_list_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "adm_order_list.php";
	f.submit();
}

$(document).on('click', 'a[name=_btn_sort]', function() {
	goSort($(this).attr('order_by'), $(this).attr('order_by_asc'));
});

$(document).on("click","a[name=btnDel]",function() {
	
	var order_no = $(this).attr("order_no");
	var obj_td = $(this).parents("td");
	var obj = $(this).parents("td").prev().prev().prev();
	
	if(!confirm("정말 삭제하시겠습니까?    ")) {
		return false;
	}
	
	$.ajax({
		url: '/icm/ajax/ajax_order_list.php',
		type: 'POST',
		dataType: "json",
		async: true,
		cache: false,
		data: {
			mode : 'DEL',
			order_no : order_no
		},
		success: function (response) {
			switch(response.RESULTCD){
                case "SUCCESS" :
					obj.html("삭제");
					obj_td.html("");
                    break;
                case "not_login" :
                    alert("로그인 후 작업하시기 바랍니다.    ");
                    break;                    
                case "no_item_code" :
                    alert("옵션코드 에러입니다.    ");
                    break;                    
                case "no_data" :
                    alert("해당 품목코드의 재고를 찾을 수 없습니다.    ");
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
	
	return false;
	
});

var goSort = function(p_order_by, p_order_by_asc) {
	var f = document.pageForm;
	f.currentPage.value = 1;
	f._order_by.value = p_order_by;
	f._order_by_asc.value = p_order_by_asc;
	f.action = "adm_order_list.php";
	f.submit();
}

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

@ $rs->free();
?>