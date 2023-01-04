<?php
require_once $_SERVER['DOCUMENT_ROOT']."/icm/common/icm_default_set.php";

@session_start();
?>
<!DOCTYPE html>
<html lang="ko">
    <head>
        <title>통합 관리 시스템(IMS)</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta name="robots" content="noindex">  <!-- 검색엔진로봇 수집 차단. -->

        <link rel="shortcut icon" href="/icm/images/common/favicon.ico?" />
        <link rel="apple-touch-icon-precomposed" href="/icm/images/common/apple-favicon.png?"/>
        
        <link type="text/css" rel="stylesheet" href="/icm/css/style.css?t=<?=time()?>" />
        <link type="text/css" rel="stylesheet" href="/icm/css/basic.css?t=<?=time()?>" />
        <link type="text/css" rel="stylesheet" href="/icm/css/button.css?t=<?=time()?>" />
        <link type="text/css" rel="stylesheet" href="/icm/css/jquery-ui.css?t=<?=time()?>" />
        <link type="text/css" rel="stylesheet" href="/icm/css/admin.css?t=<?=time()?>" />
        
        <script type="text/javascript" src="/icm/js/jquery-3.4.1.min.js?t=<?=time()?>"></script>
        <script type="text/javascript" src="/icm/js/jquery-ui.min.js?v=<?=time()?>"></script>
        <script type="text/javascript" src="/icm/js/common.js?t=<?=time()?>"></script>
        <script type="text/javascript" src="/icm/js/script.js?t=<?=time()?>"></script>
    </head>