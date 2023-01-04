<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/AdmMemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/SystemUtil.php";

$rtnUrl = RequestUtil::getParam("rtnUrl", "");

$icm_adm_ck_auto = CookieUtil::getCookieMd5("icm_adm_ck_auto");
$icm_adm_ck_userid = CookieUtil::getCookieMd5("icm_adm_ck_userid");

if(!$icm_adm_ck_auto) $icm_adm_ck_auto = "";

if (LoginManager::isManagerLogined() && !empty(LoginManager::getManagerLoginInfo("grade"))) {
    
    $wq = new WhereQuery(true, true);
    $wq->addAndString("userid", "=", LoginManager::getManagerLoginInfo("userid"));
    $wq->addAndString2("fg_del", "=", "0");
    
    $row = AdmMemberMgr::getInstance()->getFirst($wq);
    
    if ( empty($row) ) {
        JsUtil::replace("./admin_logout.php");
        exit;
    } else {
        
        if (empty($row["last_login"]) || $row["last_login"] < date("Y-m-d h:i:s",strtotime ("-30 minutes"))) {
            $uq = new UpdateQuery();
            $uq->addNotQuot("last_login", "now()");
            AdmMemberMgr::getInstance()->edit($uq, LoginManager::getManagerLoginInfo("userid"));
        }
    }
    
    if (!empty($rtnUrl)) {
        JsUtil::replace($rtnUrl);
        exit;
    } else {
        $rtnUrl = "/branch.php";
        JsUtil::replace($rtnUrl);
        exit;
    }
}

if(!empty($rtnUrl)) {
    $rtnUrl = urldecode($rtnUrl);
}

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";

if (!SystemUtil::isLocalhost() && 1==2) {
?>
<script>
if(window.location.protocol == "http:"){
	window.location.protocol = "https:";
}
</script>
<?php
}
?>
<?/*
	<body class="login_wrap">
		<div class="wrapper fadeInDown">
			<div id="formContent">
				<h2 class="active">통합 계약 관리 시스템(ICM)</h2>
				<form name="writeForm" class="custom-form" method="post" autocomplete="off">
                	<input type="hidden" name="auto_defense" />
                	<input type="hidden" name="mode" value="login" />
                	<input type="text" name="userid" id="userid" class="fadeIn second" placeholder="login" style="margin:6px;" />
					<input type="password" name="passwd"  id="passwd" class="fadeIn third" style="margin: 3px 0;" />
					<div class="bit_checks fadeIn third">
                        <input type="checkbox" id="nologin" name="ck_auto" value="1"><label for="nologin">자동 로그인</label>
                    </div>
					<input type="button" class="fadeIn fourth" value="LogIn" onClick="javascript:login_submit();return false;">
				</form>
				<div id="formFooter">
					<a class="underlineHover">Copyright ⓒ 2022 (주)비아이에스. All rights reserved.</a>
				</div>
			</div>
		</div>
*/?>

    <body id="lgoin">
        <div id="wrap">
            <div class="lg-box">
                <div class="log-area">
                    <h1><img src="/images/common/bis_logo.png" alt=""></h1>
                    <h2>통합 관리 시스템 (IMS)</h2>

                    <form name="writeForm" class="custom-form" method="post" autocomplete="off">
                        <input type="hidden" name="auto_defense" />
                        <input type="hidden" name="mode" value="login" />
                        <div class="id_pw_wrap inb">
                            <div class="input_row">
                                <div class="icon_cell">
                                    <span class="icon_id">
                                        <span class="blind">아이디</span>
                                    </span>
                                </div>
                                <input type="text" name="userid" id="userid" class="input-login" />
                            </div>
                            <div class="input_row">
                                <div class="icon_cell">
                                    <span class="icon_pw">
                                        <span class="blind">비밀번호</span>
                                    </span>
                                </div>
                                <input type="password" class="input-login" name="passwd"  id="passwd" />
                            </div>
                        </div>

                        <div class="check_box_wrap">
                            <div class="choice-round">
                                <input type="checkbox"  id="nologin" name="ck_auto" value="1" />
                                <label for="nologin">자동 로그인<span class="box"></span></label>
                            </div>
                        </div>
                        <div class="button-center">
                            <a href="#" onClick="javascript:login_submit();return false;" class="button login xlarge">로그인</a>
                        </div>
                    </form>
                </div>
        
                <div class="caption">
                    Copyright ⓒ 2022 (주)유라이프파이낸셜. All rights reserved.
                </div>
            </div>    
        </div>

<?php
if ($icm_adm_ck_auto=="icm_adm_auto_login" && !empty($icm_adm_ck_userid)) {
?>

<form name="autoLoginForm" method="post" action="./admin_login_act.php">
	<input type="hidden" name="mode" value="autologin" />
	<input type="hidden" name="auto_defense" value="identicharmc!@" />
    <input type="hidden" name="rtnUrl" value="<?=urlencode($rtnUrl)?>" />
    <input type="hidden" name="userid" value="<?=$icm_adm_ck_userid?>" />
</form>

<script type="text/javascript">
document.autoLoginForm.submit();
</script>

<?php 
}
?>       
        
<script src="/js/ValidCheck.js"></script>
<?php /*
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
*/?>
<script language="javascript">
//<![CDATA[

$(document).on('keypress','#userid, #passwd',function(e) {
	if (e.keyCode === 13) {
		login_submit();
		return false;
	}
});

function login_submit(){
	var f = document.writeForm;

    if ( VC_inValidText(f.userid, "아이디") ) return false;
    if ( f.userid.value == "아이디" ) {
    	alert("아이디를 입력해 주십시오.");
    	f.userid.focus();
		return false;
    }
    if ( VC_inValidText(f.passwd, "패스워드") ) return false;

<?php /*
    //f.action = "<?=SystemUtil::toSsl("http://".$_SERVER[SERVER_NAME]."/mcm/member/mb_login_act.php")?>";
*/?>

	f.auto_defense.value = "identicharmc!@";
	
    f.action = "./admin_login_act.php";
    f.submit();
}	

//]]>
</script>

    </body>
</html>
<?/* 장기여행자보험 발송 ID는 toursafe, 기업보험 ID는 insurance */?>
