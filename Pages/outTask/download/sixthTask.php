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
header("Content-Disposition: attachment;Filename=sixthTask.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo '<style> table{border: 1px solid black; border-collapse: collapse;} TD, TH {padding: 2px; text-align: center; border: 1px solid black;}</style>';
echo "<body>";
echo '<div>Вихідне рівняння: ';
            
            $data = $connect->getTaskData("6", $connect->getUserId($login, "users"), "tasktable");
            $data = unserialize($data);
            $h = $data["h"];
            $func = $connect->getFunctionEcho($data["function"], "functionsixthtask");
            $x0 = $data["x0"];
            $y0 = $data["y0"];
            echo $func;
            $endValue = $data["EndValue"];
            
            echo " і початкова умова y|<sub>x = $x0</sub> = $y0";
            echo '</div>
        <div>
            Заповнити таблицю
        </div>
        <div>';
            for ($index = $x0+$h; $index < $endValue + $h / 1000; $index+=$h) {
                $xData[] = $index;
            }
            echo "<table border='1'><tr><td>x</td>";
            foreach ($xData as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr><tr><td>y</td>";
            foreach ($xData as $value) {
                echo "<td></td>";
            }
            echo "</tr><tr><td>n</td>";
            foreach ($xData as $value) {
                echo "<td></td>";
            }
            echo '</tr></table>';
            echo '</div>
        <div>
           наближеними значеннями y = y (x) даної задачі Коші, обчисленими з точністю &epsilon; = 10<sup>-5</sup> методом Рунге-Кутта з автоматичним вибором кроку (вказати остаточний розрахунковий крок у кожній точці таблиці).
                            Використовуючи перші чотири значення рішення, продовжити обчислення до кінцевої точки з фіксованим кроком h = 0.14 методом Мілна.
        </div>';
echo "</body>";
echo "</html>";