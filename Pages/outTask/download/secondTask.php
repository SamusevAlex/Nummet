<?php

require_once '../../../Source/SQL/SqlConnectNumMet.php';
mb_internal_encoding("UTF-8");
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
if ($connect->isUserAdmin($login, "users")) {
    header("Location:../adminPages/admin.php");
}
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=secondTask.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo '<style> table{border: 1px solid black; border-collapse: collapse;} TD, TH {padding: 2px; text-align: center; border: 1px solid black;}</style>';
echo "<body>";
echo '<div>Встановити вид емпіричної формули y = f(x), використовуючи апроксимуючі залежності з двома параметрами α і β 
                            (див. Методичні вказівки), і визначити найкращі значення параметрів, якщо дослідні дані мають такий вигляд:</div>';
$data = $connect->getTaskData("2", $connect->getUserId($login, "users"), "tasktable");
$data = unserialize($data);
$data = $data["yData"];
$xData = array_keys($data);
echo "<table border='1'><tr class='outTask'>";
foreach ($xData as $value) {
    echo "<td class='outTask'>$value</td>";
}
echo "</tr><tr class='outTask'>";
foreach ($data as $value) {
    echo "<td>$value</td>";
}
echo '</tr></table><br>';
echo "</body>";
echo "</html>";
