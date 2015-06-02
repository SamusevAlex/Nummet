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
header("Content-Disposition: attachment;Filename=FirstTask.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo '<style> table{border: 1px solid black; border-collapse: collapse;} TD, TH {padding: 2px; text-align: center; border: 1px solid black;}</style>';
echo "<body>";
echo '<div>Багаторазово диференціюєма функція f(x) задана таблицею значень у<sub>i</sub>≈f(x<sub>i</sub>):</div>';

$data = $connect->getTaskData("1", $connect->getUserId($login, "users"), "tasktable");
$data = unserialize($data);
$goalPoints = $data["goalPoints"];
$xYData = $data["xyData"];
$xData = array_keys($xYData);
echo "<table border='1'><tr>";
foreach ($xData as $value) {
    echo "<td>$value</td>";
}
echo "</tr><tr>";
foreach ($xYData as $value) {
    echo "<td>$value</td>";
}
echo '</tr></table>';
$gp = "<div>Задані контрольні значення аргументу ";
foreach ($goalPoints as $key => $value) {
    if ($key == (count($goalPoints) - 1)) {
        $gp .= "$value";
    } else {
        $gp .= "$value, ";
    }
}
$gp .= ".</div>";
echo "$gp";
$gp = "<div>Записати підходящі для наближеного обчислення значень ";
foreach ($goalPoints as $key => $value) {
    if ($key == (count($goalPoints) - 1)) {
        $gp .= "y=f($value)";
    } else {
        $gp .= "y=f($value), ";
    }
}
$gp .= " конкретні інтерполяційні многочлени Лагранжа першого та другого ступеня і отримати ці значення.";
$gp .= "</div>";
echo "$gp";

echo "</body>";
echo "</html>";
