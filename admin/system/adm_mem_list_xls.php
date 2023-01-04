<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/AdmMemberMgr.php";

if (LoginManager::getManagerLoginInfo("grade_0") < 10) {
    echo "작업 권한이 없습니다.    ";
    exit;
}

$_name = RequestUtil::getParam("_name", "");
$_grade = RequestUtil::getParam("_grade", "");
$_order_by = RequestUtil::getParam("_order_by", "reg_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$wq = new WhereQuery(true, true);
$wq->addAndString2("fg_del","=","0");
$wq->addAndLike("name",$_name);
/*
if(!empty($_grade)) {
    $wq->addAndString("grade","=",$_grade."|+|");
}
*/
$wq->addOrderBy($_order_by, $_order_by_asc);

$rs = AdmMemberMgr::getInstance()->getList($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=IMS_회원 리스트_".date('Ymd').".xls");
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
		<th style="color:white;background-color:#000081;">No</th>
		<th style="color:white;background-color:#000081;">아이디</th>
		<th style="color:white;background-color:#000081;">이름</th>
		<th style="color:white;background-color:#000081;">Hiworks ID</th>
		<th style="color:white;background-color:#000081;">휴대폰</th>
		<th style="color:white;background-color:#000081;">이메일</th>

<?php
    for($i_s=0;$i_s<count($arrSystemMenu);$i_s++) {
?>
		<th style="color:white;background-color:#000081;">권한 [<?=$arrSystemMenu[$i_s]["title"]?>]</th>
		<th style="color:white;background-color:#000081;">알림 [<?=$arrSystemMenu[$i_s]["title"]?>]</th>
<?php
    }
?>		
		
		<th style="color:white;background-color:#000081;">최종 로그인</th>
		<th style="color:white;background-color:#000081;">등록일</th>
	</tr>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();

		$arrRowGrade = explode('|+|', $row['grade']);
		$arrRowGradeAlarm = explode('|+|', $row['grade_alarm']);
?>
                    <tr>
                    	<td class="tbl_first"><?=$i+1?></td>
                        <td><?=$row["userid"]?></td>
                        <td><?=$row["name"]?></td>
						<td><?=$row["hiworks_id"]?></td>
                        <td><?=$row["hp_no"]?></td>
                        <td><?=$row["email"]?></td>
<?php
    for($i_s=0;$i_s<count($arrSystemMenu);$i_s++) {
?>
                    	<td><?=$arrSystemMenu[$i_s]["grade"][$arrRowGrade[$i_s]]?></td>
						<td><?=$arrRowGradeAlarm[$i_s]=="1"?"Y":"N"?></td>
<?php
    }
?>
						<td><?=$row["last_login"]?></td>
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