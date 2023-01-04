<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/AdmMemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";

$menuNo = [9,0,0];

if (LoginManager::getManagerLoginInfo("grade_0") < 10) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_name = RequestUtil::getParam("_name", "");
$_grade = RequestUtil::getParam("_grade", "");
$_order_by = RequestUtil::getParam("_order_by", "reg_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$pg = new Page($currentPage, $pageSize);

$wq = new WhereQuery(true, true);
$wq->addAndString2("fg_del","=","0");
$wq->addAndLike("name",$_name);
/*
if(!empty($_grade)) {
    $wq->addAndString("grade","=",$_grade."|+|");
}
*/
$wq->addOrderBy($_order_by, $_order_by_asc);

$rs = AdmMemberMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">
    <input type="hidden" name="_name" value="<?=$_name?>">
    <input type="hidden" name="_grade" value="<?=$_grade?>">
    
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

<div class="list-area">
    <div class="title-area">
        <h2>회원 검색
        </h2>
        <div class="button-right">
            <a href="./adm_mem_write.php" class="button Gray medium">추가</a>            
            <a href="#" name="btnExcelDownload" class="button excel medium">엑셀</a>
        </div>
                </div>
				
	<div class="list-search-wrap">
        <form name="searchForm" method="get" action="adm_mem_list.php">
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
                                <th>이름</th> 
                                <td><input type="text" class="input01" placeholder="이름으로 검색" name="_name" value="<?=$_name?>"></td>
<?/*                                
                                <th>권한</th>
                                <td>
                                    <div class="select-box">
                                        <select name="_grade" depth="1">
                						    <option value="" <?=$_grade==""?"selected='selected'":""?>>권한 선택</option>
<?php
$arrMemGradeKey = array_keys($arrMemGrade[0]);
$arrMemGradeVal = array_values($arrMemGrade[0]);

for($ii=0;$ii<count($arrMemGradeKey);$ii++) {
?>
<option value="<?=$arrMemGradeKey[$ii]?>" <?=$_grade==$arrMemGradeKey[$ii]?"selected":""?>><?=$arrMemGradeVal[$ii]?></option>
<?php    
}
?>                					
                					    </select>
                                    </div>
                                </td>
*/?>
                            	<th></th>
                            	<td></td>
                            	<th></th>
                            	<td></td>
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
    <div class="list-title-area">
        <h3>등록 회원 <span class="number"><?=number_format($pg->getTotalCount())?></span>건</h3>
        <div class="filter-area">
            <a href="#none" name="_btn_sort" order_by="reg_date" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="reg_date"?"active":""?>">최신순</a>
            <a href="#none" name="_btn_sort" order_by="userid" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="userid" && $_order_by_asc=="desc"?"active":""?>">아이디 <i class="icon-down">내림차순</i></a>
            <a href="#none" name="_btn_sort" order_by="userid" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="userid" && $_order_by_asc=="asc"?"active":""?>">아이디 <i class="icon-up">오름차순</i></a>
            <a href="#none" name="_btn_sort" order_by="name" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="name" && $_order_by_asc=="desc"?"active":""?>">이름 <i class="icon-down">내림차순</i></a>
            <a href="#none" name="_btn_sort" order_by="name" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="name" && $_order_by_asc=="asc"?"active":""?>">이름 <i class="icon-up">오름차순</i></a>
<?/*            
            <a href="#none" name="_btn_sort" order_by="grade" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="grade" && $_order_by_asc=="desc"?"active":""?>">권한 <i class="icon-down">내림차순</i></a>
            <a href="#none" name="_btn_sort" order_by="grade" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="grade" && $_order_by_asc=="asc"?"active":""?>">권한 <i class="icon-up">오름차순</i></a>
*/?>            
            <a href="#none" name="_btn_sort" order_by="last_login" order_by_asc="desc" class="button filter xsmail <?=$_order_by=="last_login" && $_order_by_asc=="desc"?"active":""?>">최종로그인 <i class="icon-down">내림차순</i></a>
            <a href="#none" name="_btn_sort" order_by="last_login" order_by_asc="asc" class="button filter xsmail <?=$_order_by=="last_login" && $_order_by_asc=="asc"?"active":""?>">최종로그인 <i class="icon-up">오름차순</i></a>
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
<?php
    for($i_s=0;$i_s<count($arrSystemMenu);$i_s++) {
?>
                <col>
<?php
    }
?>

                <col>
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>Hiworks ID</th>
                    <th>휴대폰</th>
                    <th>이메일</th>

<?php
    for($i_s=0;$i_s<count($arrSystemMenu);$i_s++) {
?>
                    <th>권한 [<?=$arrSystemMenu[$i_s]["title"]?>]</th>
<?php
    }
?>


<?php/*                                                
                        <th>외부 접속</th>
*/?>
                    <th>최종 로그인</th>
                    <th>등록일</th>
                </tr>
            </thead>
            <tbody>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();

        $arrRowGrade = explode('|+|', $row['grade']);
?>
                <tr>
					<td><?=$pg->getMaxNumOfPage() - $i?></td>
                    <td><a href="./adm_mem_write.php?mode=UPD&userid=<?=$row["userid"]?>"><?=$row["userid"]?></a></td>
                    <td><a href="./adm_mem_write.php?mode=UPD&userid=<?=$row["userid"]?>"><?=$row["name"]?></a></td>
                    <td><a href="./adm_mem_write.php?mode=UPD&userid=<?=$row["userid"]?>"><?=$row["hiworks_id"]?></a></td>
                    <td><?=$row["hp_no"]?></td>
                    <td><?=$row["email"]?></td>

<?php
    for($i_s=0;$i_s<count($arrSystemMenu);$i_s++) {
?>
                    <td><?=$arrSystemMenu[$i_s]["grade"][$arrRowGrade[$i_s]]?></td>
<?php
    }
?>
<?php/*                        
							<td><?=$row["fg_outside"]>0?"<font color='blue'>가능</font>":"<font color='gray'>불가</font>"?></td>
*/?>                        
                    <td><?=$row["last_login"]?></td>
                    <td><?=$row["reg_date"]?></td>
                </tr>
<?php
    }
} else {
?>
						<tr><td colspan="20" style="text-align:center;">No Data.</td></tr>
<?php
}
?>                
            </tbody>
        </table>
    </div>
            
	<div class="list-bottom-area">
		<?=$pg->getNaviForFuncBIS("goPage", "<<", "<", ">", ">>")?>
		<div class="button-right">
			<a href="./adm_mem_write.php" class="button line-basic large">등록하기</a>
		</div>
    </div>

<script type="text/javascript">

$(document).on('click','a[name=btnExcelDownload]', function() {
	var f = document.pageForm;
	f.target = "_new";
	f.action = "adm_mem_list_xls.php";
	
	f.submit();
});

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "adm_mem_list.php";
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
	f.action = "adm_mem_list.php";
	f.submit();
}

$(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;
	
    f.submit();	
    
});

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

@ $rs->free();
?>