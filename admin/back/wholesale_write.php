<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/channel/ChannelMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/sales_type/SalesTypeMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/status/StatusMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/order/OrderMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/icm/goods/GoodsItemMgr.php";

$menuCate = 4;
$menuNo = 8;

$mode = RequestUtil::getParam("mode", "INS");
$order_no = RequestUtil::getParam("order_no", "");

if (LoginManager::getManagerLoginInfo("iam_grade") < 9) {
    JsUtil::alertReplace("작업 권한이 없습니다.    ", "/");
    exit;
}

$arrChannel = $arrSalesType = $arrStatus = array();

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
$wq->addAndString("imst_idx","<>","1");
$rs = SalesTypeMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $arrSalesType[$row["imst_idx"]] = $row["title"];
    }
}

if ($mode=="UPD") {
    //    if(empty($userid)) {
    if(!$order_no) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
        exit;
    }
    
    $row = OrderMgr::getInstance()->getByKey($order_no);
    
    //    if (empty($row)) {
    if (!$row) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
        exit;
    }
} else {
    //    if(!empty($userid)) {
    if($order_no) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x04)   ");
        exit;
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString2("imc_fg_del","=","0");
if ($mode=="UPD") {
    $wq->addAndString("imst_idx","=",$row["order_type"]);
} else {
    $wq->addAndString("imst_idx","=","2");
}
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
$wq->addAndString2("img_fg_del","=","0");
$wq->addAndString2("imgi_fg_del","=","0");
//$wq->addAndLike("item_code","DG");

$wq->addOrderBy("item_name","asc");

$rs = GoodsItemMgr::getInstance()->getList($wq);

$arrGoods= array();
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row_goods = $rs->fetch_assoc();
        
        array_push($arrGoods, $row_goods);
    }
}

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
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

			<!-- 202112123 등록하기(s) -->
            <div class="gp_rig_search">
                <div style="padding-left:20px;">
                    <h3 class="wrt_icon_search">도매 판매 등록</h3>
                </div>
				<form name="writeForm" action="./wholesale_write_act.php" method="post">
					<input type="hidden" name="mode" value="<?=$mode?>" />
					<input type="hidden" name="order_no" value="<?=$order_no?>" />
					<input type="hidden" name="auto_defense" />

                    <table class="wrt_table">
                        <caption>등록하기</caption>
                        <colgroup>
                            <col style="width:16%;"><col>
                        </colgroup>
                        <tbody>

<?php
if ($mode=="UPD") {
?>
							<tr>
								<th>주문번호</th>
                                <td>
									<div style="height:30px;vertical-align:middle;padding:0 10px;margin:3px 0px;line-height:29px;"><?=$order_no?></div>
								</td>
							</tr>
<?php
}
?>
                            <tr>
                                <th>품목(옵션)명</th>
                                <td>
                                    <select name="item_code" class="sel_item" style="line-height: 30px;height:30px;">
                						<?php
                						foreach($arrGoods as $lt){
                							?>
                							<option value="<?=$lt['item_code']?>" <?=$row['item_code']==$lt['item_code']?"selected":""?> code="<?=$lt['code']?>" name="<?=$lt['name']?>" item_code="<?=$lt['item_code']?>"><?=$lt['item_name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>
                            </tr>
                            <tr>
                                <th>품목(옵션)코드</th>
                                <td>
                                	<div style="height:30px;vertical-align:middle;padding:0 10px;margin:3px 0px;line-height:29px;">
                                    	<span name="spanItemCode"><?=$row["item_code"]?></span>
									</div>
                                </td>
                            </tr>
                            <tr>
                                <th>상품코드</th>
                                <td>
                                	<div style="height:30px;vertical-align:middle;padding:0 10px;margin:3px 0px;line-height:29px;">
                                    	<span name="spanCode"><?=$row["code"]?></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>상품명</th>
                                <td>
                                	<div style="height:30px;vertical-align:middle;padding:0 10px;margin:3px 0px;line-height:29px;">
	                                    <span name="spanName"><?=$row["name"]?></span>
	                                </div>
                                </td>
                            </tr>
                            <tr>
                                <th>주문일자</th>
                                <td>
                                    <input type="date" id="order_date" name="order_date" class="date_in" value="<?=substr($row["order_date"],0,10)?>" placeholder="주문일자를 입력하세요." style="padding:0 16px;">
                                </td>
                            </tr>
							<tr>
                            	<th>판매유형/거래처(채널)</th>
                            	<td colspan="3">
									<select name="order_type" class="sel_order_type">
<?php                                     	
foreach($arrSalesType as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$row["order_type"]==$key?"selected":""?>><?=$value?></option>
<?php
}
?>
                                    </select>
                                    <select name="imc_idx" class="sel_channel">
                						<?php
                						foreach($arrChannel as $lt){
                							?>
                							<option value="<?=$lt['imc_idx']?>" <?=$row["imc_idx"]==$lt['imc_idx']?"selected":""?>><?="[".$lt['sales_type_title']."] ".$lt['name']?></option>
                							<?php
                						}
                						?>
                					</select>
                                </td>                           
							</tr>                            
                            <tr>
                                <th>수량</th>
                                <td>
                                    <input type="text" name="amount" value="<?=$row['amount']?>" placeholder="수량을 입력하세요." style="width: 200px;">
                                </td>
                            </tr>
                            <tr>
                                <th>EA</th>
                                <td>
                                    <input type="text" name="ea" value="<?=$row['ea']?>" placeholder="EA을 입력하세요." style="width: 200px;">
                                </td>
                            </tr>
                            <tr>
                                <th>금액</th>
                                <td>
                                    <input type="text" name="price" value="<?=$row['price']?>" placeholder="금액을 입력하세요." style="width: 200px;">
                                </td>
                            </tr>
<?php /*
                            <tr>
                                <th>한줄 메모</th>
                                <td>
                                    <input type="text" name="tmp_data3" value="<?=$row['tmp_data3']?>" placeholder="메모를 입력하세요." style="width: 80%;">
                                </td>
                            </tr>
*/?>
							<tr>
                                <th>과세구분</th>
                                <td>
                                    <select name="tax_type" class="sel_category">
                						<option value="과세" <?=$row["tax_type"]=="과세"?"selected='selected'":""?>>과세</option>
                						<option value="면세" <?=$row["tax_type"]=="면세"?"selected='selected'":""?>>면세</option>
									</select>
                                </td>
                            </tr>
							<tr>
                                <th>상태</th>
                                <td>
									<select name="status" class="sel_status">
<?php          
foreach($arrStatus as $lt){
?>
                							<option value="<?=$lt['title_status']?>" <?=$row["status"]==$lt['title_status']?"selected":""?>><?=$lt['title_status']?></option>
<?php
}
?>
                                    </select>

                                </td>
                            </tr>
                        </tbody>
                    </table>
				</form>
				<!-- 취소/등록 버튼 START -->
				<div style="overflow: hidden; display: flex; display: -webkit-flex; -webkit-align-items: center; align-items: center; flex-direction: inherit; justify-content: center; margin-top: 9px;">
					<div class="wrt_searchBtn">
						<a href="#" name="btnCancel">취소</a>
					</div>
					<div class="wrt_searchBtn">
						<a href="#" name="btnSave">저장</a>
					</div>
<?php
if ($mode=="UPD") {
?>
					<div class="wrt_searchBtn" style="margin-right: 0;">
						<a href="#" name="btnDel">삭제</a>
					</div>
<?php
}
?>
				</div>
				<!-- 취소/등록 버튼 END -->
			</div>
			
<script src="/js/ValidCheck.js"></script>	
<script type="text/javascript">
var mc_consult_submitted = false;

$(document).on("click","a[name=btnSave]",function() {
	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidDate(f.order_date, "주문일자") ) return false;
	
	if ( VC_inValidText(f.amount, "수량") ) return false;
	if ( VC_inValidNumber(f.amount, "수량") ) return false;
	if ( VC_inValidText(f.ea, "EA") ) return false;
	if ( VC_inValidNumber(f.ea, "EA") ) return false;
	if ( VC_inValidText(f.price, "금액") ) return false;
	if ( VC_inValidNumber(f.price, "금액") ) return false;

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


$(document).on('change','select[name=item_code]',function() {

	changeItemCode();
});

$(document).ready(function() {
	changeItemCode();
});

var changeItemCode = function() {

	if($("option:selected", $('select[name=item_code]')).val()!=="") {
		$('span[name=spanItemCode]').html($("option:selected", $('select[name=item_code]')).attr('item_code'));
		$('span[name=spanCode]').html($("option:selected", $('select[name=item_code]')).attr('code'));
		$('span[name=spanName]').html($("option:selected", $('select[name=item_code]')).attr('name'));
	} else {
		$('span[name=spanItemName]').html("");
		$('span[name=spanCode]').html("");
		$('span[name=spanName]').html("");
	}
}

$(document).on('change','.sel_order_type',function() {
	getSelChannel($("option:selected", this).val());
});

var getSelChannel = function(order_type) {
	var obj_select

	obj_select = $('.sel_channel');

	$.ajax({
		url: "/icm/ajax/ajax_channel.php",
		data: {imst_idx: order_type, p_fg_not_all:1},
		async: true,
		cache: false,
		error: function(xhr){	},
		success: function(data){
			obj_select.html(data);
		}
	});
}

</script>	

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>