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
if($connect->isUserAdmin($login, "users")){
    header("Location:../adminPages/admin.php");
}
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=fifthTask.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo '<style> table{border: 1px solid black; border-collapse: collapse;} TD, TH {padding: 2px; text-align: center; border: 1px solid black;}</style>';
echo "<body>";
echo '<div>Функція f(x)</div>';
      
        $data = $connect->getTaskData("5", $connect->getUserId($login, "users"), "tasktable");
        $data = unserialize($data);
        $xStart = $data['xStart'];
        $xEnd = $data['xEnd'];
        $h = $data['h'];
        $goalPoints = $data["GoalPoints"];
        foreach ($data["yData"] as $key => $value) {
            if ($key % 2 == 0) {
                $yData[] = $value;
            }
        }
        for ($index = $xStart; $index < $xEnd + $h / 100; $index+=$h * 2) {
            $xData[] = $index;
        }
        echo "<table border='1'><tr>";
        foreach ($xData as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr><tr>";
        foreach ($yData as $value) {
            echo "<td>$value</td>";
        }
        echo '</tr></table><br>';
        foreach ($goalPoints as $key => $value) {
            if ($key == (count($goalPoints) - 1)) {
                $gp .= "$value";
            } else {
                $gp .= "$value, ";
            }
        }

        echo "<div>Вибравши крок h = $h, за формулами знайти наближені значення"
                . " похідних f '(x) і f' '(x) в точках $gp, оцінити похибку обчислень.
        </div> ";
echo "</body>";
echo "</html>";