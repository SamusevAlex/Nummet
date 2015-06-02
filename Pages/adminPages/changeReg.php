<?php

require_once '../../Source/SQL/SqlConnectNumMet.php';
define(HOST, "localhost");
define(USER, "root");
define(PASSWORD, "");
define(DB, "NumMet");
$connect = new SqlConnectNumMet(HOST, USER, PASSWORD, DB);
if ($connect->checkUserLogin($_COOKIE['login'], $_COOKIE['password'], "users")) {
    $isLogin = true;
} else {
    $isLogin = false;
}
if (!$isLogin) {
    header("Location:../login.php");
}
$login = $_COOKIE["login"];
if (!$connect->isUserAdmin($login, "users")) {
    header("Location:../index.php");
}
if ($reg = $connect->checkRegistration("setings")) {
    $connect->changeRegistration("setings", 0);
} else {
    $connect->changeRegistration("setings", 1);
}
header("Location:".$_SERVER['HTTP_REFERER']);
