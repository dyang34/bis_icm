<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- /** css -->
  <link rel="stylesheet" type="text/css" href="/resource/css/style.css?v=<?=time()?>">
  <link rel="stylesheet" type="text/css" href="/resource/css/basic.css?v=<?=time()?>">
  <link rel="stylesheet" type="text/css" href="/resource/css/button.css?v=<?=time()?>">
  <link rel="stylesheet" type="text/css" href="/resource/css/jquery-ui.css?v=<?=time()?>">
  <link rel="stylesheet" type="text/css" href="/resource/css/admin.css?v=<?=time()?>">

  <!-- /** javascript -->
  <script type="text/javascript" src="/resource/js/jquery-3.6.0.js?v=<?=time()?>"></script>
  <script type="text/javascript" src="/resource/js/jquery-ui.min.js?v=<?=time()?>"></script>
  <script type="text/javascript" src="/resource/js/calendar-ui.js?v=<?=time()?>"></script>
  <script type="text/javascript" src="/resource/js/script.js?v=<?=time()?>"></script>

  <title>통합 계약 관리 시스템 (ICM)</title>
</head>
<body>
  <div id="wrap">
    <!-- /** Left Menu area Start -->
	<?php include $_SERVER['DOCUMENT_ROOT'].'/resource/layout/menu.php'; ?>
    <!--Left Menu area End */ -->

    <!-- /** hearder area Start -->
    <header>
      <nav>
        <ul>
          <li><a href="#" class="active">메뉴명01</a></li>
          <li><a href="#">메뉴명02</a></li>
          <li><a href="#">메뉴명03</a></li>
          <li><a href="#">메뉴명04</a></li>
        </ul>
      </nav>
      <div class="right-menu">
        <ul>
          <li><span class="userid">홍길동</span> 님 반갑습니다!</li>
          <li>
            <a href="#" class="logout">로그아웃</a>
          </li>
        </ul>
      </div>
    </header>
    <!-- hearder area End */ -->
    
    <!-- /** contents area Start -->
    <div class="content-wrap">
