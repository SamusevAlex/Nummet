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
header("Content-Disposition: attachment;Filename=fouthTask.doc");
echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo '<style> table{border: 1px solid black; border-collapse: collapse;} TD, TH {padding: 2px; text-align: center; border: 1px solid black;}</style>';
echo "<body>";
        $data = $connect->getTaskData("4", $connect->getUserId($login, "users"), "tasktable");
        $data = unserialize($data);
        $func = $data["function"];
        $func = preg_replace("/unk/", "x", $func);
        $a = $data["a"];
        $b = $data["b"];
        echo " <div>Інтеграл заданий загальним видом визначеного інтеграла (див. методичні вказівки),
            де a = $a, b = $b, f(x) = $func</div>
        <div>Обчислити заданий інтеграл за формулами прямокутників, трапецій і Сімпсона, 
                            якщо відрізок інтегрування розбитий на n = 6 і n = 8 частин. 
                            Оцінити похибку результату.  
        </div> ";
echo "</body>";
echo "</html>";