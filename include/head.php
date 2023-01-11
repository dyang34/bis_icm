<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";

@session_start();
?>
<!DOCTYPE html>
<html lang="ko">
    <head>
        <title>통합 관리 시스템(ERP)</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta name="robots" content="noindex">  <!-- 검색엔진로봇 수집 차단. -->

        <link rel="shortcut icon" href="/images/common/favicon.ico" />
        <link rel="apple-touch-icon-precomposed" href="/images/common/apple-favicon.png"/>
        
        <link type="text/css" rel="stylesheet" href="/css/style.css" />
        <link type="text/css" rel="stylesheet" href="/css/basic.css" />
        <link type="text/css" rel="stylesheet" href="/css/button.css" />
        <link type="text/css" rel="stylesheet" href="/css/jquery-ui.css" />
        <link type="text/css" rel="stylesheet" href="/css/admin.css" />
        <link type="text/css" rel="stylesheet" href="/css/modal.css" />
        
        <script type="text/javascript" src="/js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>
        <script type="text/javascript" src="/js/script.js"></script>
    </head>