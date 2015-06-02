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
header("Content-Disposition: attachment;Filename=thirdTask.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo '<style> table{border: 1px solid black; border-collapse: collapse;} TD, TH {padding: 2px; text-align: center; border: 1px solid black;}</style>';
echo "<body>";
echo "<div>Задана функція y=f(x)</div>";
$data = $connect->getTaskData("3", $connect->getUserId($login, "users"), "tasktable");
$data = unserialize($data);
$yData = $data["yData"];
$xStart = $data["xStart"];
$xEnd = $data["xEnd"];
$goalPoints = $data["GoalPoints"];
$h = $data["h"];
$checkValue = $xEnd + $h / 1000;
for ($index = $xStart; $index < $checkValue; $index+=$h) {
    $xData[] = $index;
}
echo "<table border='1'><tr class='outTask'>";
foreach ($xData as $value) {
    echo "<td class='outTask'>$value</td>";
}
echo "</tr><tr class='outTask'>";
foreach ($yData as $value) {
    echo "<td>$value</td>";
}
echo '</tr></table><br>';
echo "Побудувати кубічний сплайн, що інтерполює функцію у = f(х) на відрізку  [$xStart; $xEnd] для "
 . "рівномірного розбиття з кроком h = $h при крайових умовах I або II типу.";
$gp = "<div>Знайти значення сплайна в точках ";
foreach ($goalPoints as $key => $value) {
    if ($key == (count($goalPoints) - 1)) {
        $gp .= "$value";
    } else {
        $gp .= "$value, ";
    }
}
$gp .= ".</div>";
echo $gp;
echo "</body>";
echo "</html>";
