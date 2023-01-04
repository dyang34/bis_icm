<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isManagerLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

if (!LoginManager::getManagerLoginInfo("grade")) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";
?>
<body>
	<div id="wrap">
    <!-- /** Left Menu area Start -->
<?php 
    include $_SERVER['DOCUMENT_ROOT']."/include/left_menu.php";
?>
    <!--Left Menu area End */ -->

    <!-- /** hearder area Start -->
		<header>
			<nav id="nav">
				<ul>
<?php
	for($i=0;$i<count($arrSystemMenu);$i++) {
		if(LoginManager::getManagerLoginInfo("grade_".$i) >= $arrSystemMenu[$i]["grade_min"]) {
?>
					<li><a href="<?=$arrSystemMenu[$i]["url"]?>" class="<?=$menuNo[0]==$arrSystemMenu[$i]["menu_no0"]?"active":""?>"><?=$arrSystemMenu[$i]["title"]?></a></li>
<?
		}
	}
?>
				</ul>
      		</nav>
      		<div class="right-menu">
        		<ul>
          			<li><span class="userid"><?=LoginManager::getManagerLoginInfo("name")?></span> 님 반갑습니다!</li>
					<li>
            			<a id="two" class="btn_modal logout">비번변경</a> <!-- /* href="/adm_mem_pw_change.php" */ -->
          			</li>
          			<li>
            			<a href="/admin_logout.php" class="logout">로그아웃</a>
          			</li>
        		</ul>
      		</div>
    	</header>
    <!-- hearder area End */ -->

	<!-- /** contents area Start -->
		<div id="conts" class="content-wrap">

<?php 
    include $_SERVER['DOCUMENT_ROOT']."/adm_mem_pw_change.php";
?>  
