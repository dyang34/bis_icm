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

$menuCate = 4;
$menuNo = 27;

if (LoginManager::getManagerLoginInfo("iam_grade") < 9) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$arrSalesType = array();

$wq = new WhereQuery(true, true);
$wq->addAndString("imst_idx","<>","1");
$rs = SalesTypeMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $arrSalesType[$row["imst_idx"]] = $row["title"];
    }
}

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_imst_idx = RequestUtil::getParam("_imst_idx", "");
$_name = RequestUtil::getParam("_name", "");

$_order_by = RequestUtil::getParam("_order_by", "name");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "asc");

$pg = new Page($currentPage, $pageSize);

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
$wq->addAndString("imst_idx","<>","1");
$wq->addAndString("imst_idx","=",$_imst_idx);
$wq->addAndLike("name",$_name);

$wq->addOrderBy("imst_idx","asc");
$wq->addOrderBy($_order_by,$_order_by_asc);

$rs = ChannelMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_imst_idx" value="<?=$_imst_idx?>">
    <input type="hidden" name="_name" value="<?=$_name?>">

    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

            <!-- 상품검색(s) -->
            <div>
                <div style="padding-left:20px;">
                    <h3 class="icon-search">도매 거래처 관리</h3>
                    <ul class="icon_Btn">
                    	<li><a href="./wholesale_customer_write.php">추가</a></li>
                        <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                    </ul>
                </div>
				<form name="searchForm" method="get" action="wholesale_customer_list.php">
				
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
                                <th>판매유형</th>
                            	<td>
									<select name="_imst_idx" class="sel_order_type">
                                    	<option value="">판매 유형</option>
<?php                                     	
foreach($arrSalesType as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$_imst_idx==$key?"selected":""?>><?=$value?></option>
<?php
}
?>
                                    </select>
                                </td>        
								<th>거래처명</th>
                            	<td colspan="3"><input type="text" placeholder="거래처명으로 검색" name="_name" style="width: 100%;" value=<?=$_name?>></td>
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
				<h3 class="float-l">총 DATA <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
				<p class="list-adding float-r">
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="asc" class="<?=$_order_by=="name" && $_order_by_asc=="asc"?"on":""?>">거래처명<em>▲</em></a>
					<a href="#none" name="_btn_sort" order_by="name" order_by_asc="desc" class="<?=$_order_by=="name" && $_order_by_asc=="desc"?"on":""?>">거래처명<em>▼</em></a>
				</p>
			</div>
           
            <!-- 메인TABLE(s) -->
            <table class="display" cellpadding="0" cellspacing="0">
            	<colgroup>
            		<col>
            		<col>
            	</colgroup>
                <thead>	
                	
                    <tr>
<?php /*                    
                        <th class="tbl_first">No</th>
                        <th>주문일시</th>
*/?>
                        <th>판매유형</th>
                        <th>거래처</th>
                        <th>등록일</th>
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
                        <td class="tbl_first txt_c"><?=$row["sales_type_title"]?></td>
                        <td><a href="./wholesale_customer_write.php?mode=UPD&imc_idx=<?=$row["imc_idx"]?>"><?=$row["name"]?></a></td>
                        <td class="txt_c"><?=$row["reg_date"]?></td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="3" class="txt_c">No Data.</td></tr>
<?php
}
?>
                </tbody>
            </table>
            <!-- 메인TABLE(e) -->
			<p class="hide"><strong>Pagination</strong></p>
			<div style="position: relative;">
    			<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
				<div style="position: absolute; right: 17px; bottom: 3px; text-align: center; line-height: 30px; border-radius: 10px; background-color: #313A3D;" class="rig_new"><a href="./wholesale_customer_write.php" style="display:inline-block;padding: 5px 22px;color: #fff;">등록하기</a></div>
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
	f.action = "wholesale_customer_list_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "wholesale_customer_list.php";
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
	f.action = "wholesale_customer_list.php";
	f.submit();
}

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

@ $rs->free();
?>