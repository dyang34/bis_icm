<?php
require_once $_SERVER['DOCUMENT_ROOT']."/icm/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/icm/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

if (!LoginManager::getUserLoginInfo("icm_grade")) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

include $_SERVER['DOCUMENT_ROOT']."/icm/include/head.php";
?>
<body>
	<div id="wrap">
    <!-- /** Left Menu area Start -->
<?php 
    include $_SERVER['DOCUMENT_ROOT']."/icm/include/left_menu.php";
?>
    <!--Left Menu area End */ -->

    <!-- /** hearder area Start -->
		<header>
			<nav id="nav">
				<ul>
					<li><a href="/branch.php" class="<?=$menuNo[0]=="1"?"active":""?>">계약 관리</a></li>
<?/*		  
          <li><a href="#" class="<?=$menuNo[0]=="2"?"active":""?>">메뉴명02</a></li>
          <li><a href="#" class="<?=$menuNo[0]=="3"?"active":""?>">메뉴명03</a></li>
          <li><a href="#" class="<?=$menuNo[0]=="4"?"active":""?>">메뉴명04</a></li>
*/?>		  
				</ul>
      		</nav>
      		<div class="right-menu">
        		<ul>
          			<li><span class="userid"><?=LoginManager::getUserLoginInfo("icm_name")?></span> 님 반갑습니다!</li>
          			<li>
            			<a href="/admin_logout.php" class="logout">로그아웃</a>
          			</li>
        		</ul>
      		</div>
    	</header>
    <!-- hearder area End */ -->
    
    <!-- /** contents area Start -->
    	<div id="conts" class="content-wrap">