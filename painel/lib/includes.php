<?php
    session_start();

    // include("connect_local.php");

    include("/appinc/connect.php");
    $con = AppConnect('dawar');

    // include("/appinc/connect.php");
    include("fn.php");

    $md5 = md5(date("YmdHis"));

    include("dicionary_".(($_SESSION['lng'])?$_SESSION['lng']:'en').".php");

    $localPainel = "https://dawar.yobom.com.br/painel/";
    $localSite = "https://dawar.yobom.com.br/";

    if($_GET['ln']){
        $_SESSION['lng'] = $_GET['ln'];
    }