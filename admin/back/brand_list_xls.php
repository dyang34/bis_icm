<?
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/brand/BrandMgr.php";

if (LoginManager::getManagerLoginInfo("iam_grade") < 10) {
    echo "작업 권한이 없습니다.    ";
    exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imb_fg_del","=","0");
$wq->addOrderBy("sort","desc");
$wq->addOrderBy("name","asc");

$rs = BrandMgr::getInstance()->getList($wq);


Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=ICM_브랜드 리스트_".date('Ymd').".xls");
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
		<th style="color:white;background-color:#000081;">코드</th>
		<th style="color:white;background-color:#000081;">명칭</th>
		<th style="color:white;background-color:#000081;">등록일</th>
	</tr>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
		<tr>
			<td><?=$row["code"]?></td>
            <td><?=$row["name"]?></td>
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