<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/contract/ContractBaseChubbMgr.php";

$menuNo = [1,2,1];

/*
if (LoginManager::getManagerLoginInfo("grade_0") < 8 || LoginManager::getManagerLoginInfo("grade_0") == 9) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}
*/

$wq = new WhereQuery(true, true);
$wq->addAndString2("fg_del", "=", "0");
$wq->addAnd2("reg_date >= date_format(now(), '%Y-%m-%d')");
$wq->addOrderBy("grp_code","desc");

$rs = ContractMgr::getInstance()->getListGroupCode($wq);

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<div class="list-area">
	<div class="title-area">
        <h2>Chubb 계약 파일 업로드</h2>
	</div>
    <div class="list-search-wrap">
        <form name="writeForm" method="post" action="./upload_xlsx.php" enctype="multipart/form-data">
            <input type="hidden" name="mode" value="UPLOAD" />
            <input type="hidden" name="auto_defense" />    									
            <table class="table-write">
                <colgroup>
                    <col width="12%">
                    <col width="*">
                </colgroup>
                <tbody>
                    <tr>
                        <th>엑셀 파일 업로드(.xlsx)</th>
                        <td class="left">
                        <div class="uploader">
                            <label for="up_file_txt">
                                <span data-file-name>업로드 엑셀 파일을 선택해 주세요.</span>
                            </label>
                            <input id="up_file_txt" name="up_file" type="file">
                            <button tabindex="-1" type="button" class="btn btn-choose-file">파일 찾기</button>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

        <div class="button-center">
            <a href="#" name="btnSave" class="button excel large">엑셀 업로드</a>
        </div>
    </div>

	<div class="list-title-area">
		<h3>금일 작업 리스트</h3>
	</div>
    <div class="list-cont-wrap">
        <table class="table-basic">
            <colgroup>
                <col style="width:50px">
                <col style="width:150px">
                <col style="width:200px">
                <col>
                <col style="width:100px">
                <col style="width:100px">
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>작업일자</th>
                    <th>보험사</th>
                    <th>작업코드</th>
                    <th>보기</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                        <td style="text-align:center;"><?=number_format($rs->num_rows-$i)?></td>
                        <td><?=$row["reg_date"]?></td>
                        <td><?=$arrInsuranceCompany[$row["company_type"]]?></td>
                        <td><?=$row["grp_code"]?></td>
                        <td style="text-align:center;">
							<a href="/icm/admin/contract_list.php?grp_code=<?=$row["grp_code"]?>" target="_blank" class="button green xxsmail">보기</a>
						</td>
                        <td style="text-align:center;">
							<a href="#" name="btnDel" grp_code="<?=$row["grp_code"]?>" class="button red xxsmail">삭제</a>
						</td>
                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="6" style="text-align:center;">No Data.</td></tr>
<?php
}
?>                     
            </tbody>
        </table>
    </div>
</div>
            
<script src="/js/ValidCheck.js?"></script>
<script type="text/javascript">
var mc_consult_submitted = false;

$(document).on("click","a[name=btnSave]",function() {

	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidText(f.up_file, "업로드할 파일") ) return false;

	if(!confirm("엑셀 파일을 업로드 하시겠습니까?\r\n\r\n데이터의 양에 따라 수분~수십분 시간이 소요될 수 있습니다.\r\n\r\n업로드 중 절대로 작업을 중단하지 마십시오.")) {
		return false;
	}
	
	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

$(document).on("click", "a[name=btnDel]", function() {
    var grp_code = $(this).attr('grp_code');

    if (confirm("정말 해당 작업을 삭제하시겠습니까?    ")) {

        $.ajax({
            url: '/icm/ajax/ajax_upload_data.php',
            type: 'POST',
            dataType: "json",
            async: true,
            cache: false,
            data: {
                mode : 'DEL',
                grp_code : grp_code
            },
            success: function (response) {
                switch(response.RESULTCD){
                    case "SUCCESS" :
                        location.reload();
                        break;
                    case "not_login" :
                        alert("로그인 후 작업하시기 바랍니다.    ");
                        break;                    
                    case "no_grp_code" :
                        alert("그룹코드 에러입니다.    ");
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
    }
});
</script>

<?/*
// 우 더블 클릭 
<script type="text/javascript">
var prev_time = "";
$(document).on('contextmenu','.list-area', function(e){
    
    var time = new Date().getTime();

    if (prev_time!="" && ((time-prev_time)<300)) {
        if($("#divA").css("display")=="none") {
            $("#divA").show();
        } else {
            $("#divA").hide();
        }
//        e.preventDefault();
//        location.href="http://www.naver.com";
        
    } else {
        prev_time = time;
    }

    //code
    return false;
});
</script>
*/?>

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>