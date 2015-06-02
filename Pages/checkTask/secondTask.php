<?php
require_once '../../Source/SQL/SqlConnectNumMet.php';
require_once 'TaskCheck.php';
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

function commaToPoint(array $array) {
    foreach ($array as &$value) {
        if (is_array($value)) {
            $value = commaToPoint($value);
        } else {
            $value = preg_replace("/,/u", ".", $value);
        }
    }
    return $array;
}

if ($connect->isUserAdmin($login, "users")) {
    header("Location:../adminPages/admin.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Второе задание</title>
        <link rel="stylesheet" type="text/css" href="../../Source/test.css">
        <?php
        if ($isLogin) {
            echo '<script>var scrollStart = ' . 100 . ';</script>' . PHP_EOL;
        } else {
            echo '<script>var scrollStart = ' . 310 . ';</script>' . PHP_EOL;
        }
        ?>
        <script type="text/javascript" src="../../Source/js/jquery.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="../../Source/js/menu.js" charset="utf-8"></script>
    </head>
    <body>
        <div id="mid">
            <div id="header" class="obox">
                <h1>РГР. Чисельні методи</h1>
            </div>
            <div id="links">
                <div id="searchMid">
                    <div id="search" class="obox">
                        <?php if (!$isLogin) { ?>
                            <h2 class="heading">Авторизація</h2>
                            <div class="content">
                                <form action="../Login.php" method="POST" enctype='multipart/form-data'>
                                    <div><label class="task" for="login">Логін</label><input type="text" id="login" name="login" required></div>
                                    <div><label class="task" for="pass">Пароль</label><input type="password" id="pass" name="password" required></div>
                                    <input type="hidden" name="submit" value="true">
                                    <input type="submit" id="submit1" value="Ввійти">
                                </form>
                            </div>
                        <?php } else { ?>
                            <a href="../profile.php">Профіль</a> / <a href="../logout.php">Вийти</a>
                        <?php } ?>
                    </div>
                </div>
                <div id="menu" class="default">
                    <div id="categories" class="obox">
                        <h2 class="heading">Меню</h2>
                        <div class="content">
                            <ul>
                                <li><h3><a href="../index.php">Головна сторінка</a></h3></li>
                                <?php if ($isLogin) { ?>
                                    <li><h3><a href="../profile.php">Сторінка профілю</a></h3></li>
                                    <li><h3><a href="../changeUserData.php">Змінити профіль</a></h3></li>
                                <?php } else { ?>
                                    <li><h3><a href="../login.php">Ввійти</a></h3></li>
                                    <li><h3><a href="../Registration.php">Зареєструватися</a></h3></li>
                                <?php } ?>
                                <?php if (!$connect->isUserAdmin($login, "users")) { ?>
                                    <li><h3><a href="../outTask/outTask.php">Вивести завдання</a></h3></li>
                                    <li><a href="../outTask/firstTask.php">Завдання 1</a></li>
                                    <li><a href="../outTask/secondTask.php">Завдання 2</a></li>
                                    <li><a href="../outTask/thirdTask.php">Завдання 3</a></li>
                                    <li><a href="../outTask/fouthTask.php">Завдання 4</a></li>
                                    <li><a href="../outTask/fifthTask.php">Завдання 5</a></li>
                                    <li><a href="../outTask/sixthTask.php">Завдання 6</a></li>
                                    <li><h3><a href="../inTask/inTask.php">Ввести відповідь</a></h3></li>
                                    <li><a href="../inTask/firstTask.php">Завдання 1</a></li>
                                    <li><a href="../inTask/secondTask.php">Завдання 2</a></li>
                                    <li><a href="../inTask/thirdTask.php">Завдання 3</a></li>
                                    <li><a href="../inTask/fouthTask.php">Завдання 4</a></li>
                                    <li><a href="../inTask/fifthTask.php">Завдання 5</a></li>
                                    <li><a href="../inTask/sixthTask.php">Завдання 6</a></li>
                                <?php } else { ?>
                                    <li><h3><a href="../adminPages/admin.php">Адміністрування</a></h3></li>
                                    <li><a href="../adminPages/taskCheck.php">Перевірити завдання</a></li>
                                    <li><a href="../adminPages/addFunction.php">Додати функцію</a></li>
                                    <li><a href="../adminPages/deleteFunctionTask.php">Видалити функцію</a></li>
                                    <li><a href="../adminPages/regGroup.php">Зареєструвати групу</a></li>
                                    <li><a href="../adminPages/deleteGroup.php">Видалити групу</a></li>
                                    <li><a href="../adminPages/createNewAdmin.php">Новий адміністратор</a></li>
                                    <?php
                                    if ($reg = $connect->checkRegistration("setings")) {
                                        echo '<li><a href="../adminPages/changeReg.php">Закрити реєстрацію</a></li>';
                                    } else {
                                        echo '<a href="../adminPages/changeReg.php">Відкрити реєстрацію</a></li>';
                                    }
                                    ?>
                                    <li><a href=""></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="posts">
                <div class="post">
                    <div class="story">
                        <?php
                        if (!$connect->isDone("2", $connect->getUserId($login, "users"), "taskdone")) {
                            $done = true;
                            $rezult = commaToPoint($_POST);
                            foreach ($rezult as $value) {
                                if ($value == "" or ! is_numeric($value)) {
                                    $done = false;
                                }
                            }
                            if ($done) {
                                $data = $connect->getTaskRezult("2", $connect->getUserId($login, "users"), "tasktable");
                                $data = unserialize($data);
                                $done = TaskCheck::secondTaskCheck($data, $rezult);
                                $rezult = serialize($rezult);
                                if ($connect->taskCheckExist("2", $connect->getUserId($login, "users"), "taskdone")) {
                                    $connect->taskCheckUpdate("2", $connect->getUserId($login, "users"), $rezult, $done, "taskdone");
                                } else {
                                    $connect->taskCheck("2", $connect->getUserId($login, "users"), $rezult, $done, "taskdone");
                                }
                            } else {
                                echo 'Присутні поля заповнені невірними даними або порожні поля<br>';
                            }
                            if ($done) {
                                echo 'Завдання виконано<br>';
                            } else {
                                echo 'Завдання не виконане<br>';
                            }
                        } else {
                            $done = $connect->isDone("2", $connect->getUserId($login, "users"), "taskdone");
                            echo 'Завдання виконано<br>';
                        }
                        if ($done) {
                            $dataStudent = $connect->getTaskRezult(2, $connect->getUserId($login, "users"), "taskdone");
                            $dataStudent = unserialize($dataStudent);
                            $dataSolver = $connect->getTaskRezult(2, $connect->getUserId($login, "users"), "tasktable");
                            $dataSolver = unserialize($dataSolver);
                            echo "<table class='task'><tr class='task'>";
                            echo "<td class='task'></td>";
                            echo '<td class="task">' . "Дані студента" . "</td>";
                            echo '<td class="task">' . "Відхилення від рішення" . "</td>";
                            echo '</tr><tr class="task">';
                            echo "<td class='task'>Емпірична залежність</td>";
                            echo '<td class="task">' . round($dataStudent["n"]) . "</td>";
                            echo '<td class="task">' . abs(abs($dataStudent["n"]) - abs($dataSolver["n"])) . "</td>";
                            echo '</tr><tr class="task">';
                            echo "<td class='task'>Альфа</td>";
                            echo '<td class="task">' . round($dataStudent["alfa"], 4) . "</td>";
                            echo '<td class="task">' . abs(abs($dataStudent["alfa"]) - abs($dataSolver["alfa"])) . "</td>";
                            echo '</tr><tr class="task">';
                            echo '</tr><tr class="task">';
                            echo "<td class='task'>Бета</td>";
                            echo '<td class="task">' . round($dataStudent["beta"], 4) . "</td>";
                            echo '<td class="task">' . abs(abs($dataStudent["beta"]) - abs($dataSolver["beta"])) . "</td>";
                            echo '</tr><tr class="task">';
                            echo '</tr><tr class="task">';
                            echo "<td class='task'>Помилка</td>";
                            echo '<td class="task">' . round($dataStudent["d"], 6) . "</td>";
                            echo '<td class="task">' . abs(abs($dataStudent["d"]) - abs($dataSolver["d"])) . "</td>";
                            echo '</tr></table>';
                            echo '<br>На графіку зображені: '
                            . '<br>блакитним кольором емпірична залежність з мінімальною помилкою d'
                                    . '<br>салатовим кольором емпірична залежність де помилка d друга за мінімальностю<br>';
                            echo "<br><img alt='' src='image/secondTaskImage.php'>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
