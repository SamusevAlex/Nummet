<?php
require_once '../../Source/SQL/SqlConnectNumMet.php';
require_once '../../Source/Registration.php';
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
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Первое задание</title>
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
                        $login = $_POST['user'];
                        $done = $connect->isDone(1, $connect->getUserId($login, "users"), "taskdone");
                        if ($done) {
                            echo 'Завдання було виконано ';
                            echo $connect->getDateDone(1, $connect->getUserId($login, "users"), "taskdone");
                            $dataStudent = $connect->getTaskRezult(1, $connect->getUserId($login, "users"), "taskdone");
                            $dataStudent = unserialize($dataStudent);
                            $dataSolver = $connect->getTaskRezult(1, $connect->getUserId($login, "users"), "tasktable");
                            $dataSolver = unserialize($dataSolver);
                            foreach ($dataSolver as $key => $value) {
                                $xData = array_keys($dataSolver[$key]);
                                $yDataSolver = array_values($dataSolver[$key]);
                                $yDataStudent = array_values($dataStudent[$key]);
                                echo "<br><div>Для полінома $key ступеня<div><br><table class='task'><tr class='task'><td class='task'>x</td>";
                                for ($index = 0; $index < count($xData); $index++) {
                                    echo '<td class="task">' . $xData[$index] . "</td>";
                                }
                                echo '</tr><tr class="task"><td class="task">Дані вирішувача</td>';
                                for ($index = 0; $index < count($yDataSolver); $index++) {
                                    echo '<td class="task">' . $yDataSolver[$index] . "</td>";
                                }
                                echo '</tr><tr class="task"><td class="task">Дані студента</td>';
                                for ($index = 0; $index < count($yDataStudent); $index++) {
                                    echo '<td class="task">' . round($yDataStudent[$index], 4) . "</td>";
                                }
                                echo '</tr><tr><td class="task">Відхилення</td>';
                                for ($index = 0; $index < count($yDataStudent); $index++) {
                                    echo '<td class="task">' . round(abs(abs($yDataStudent[$index]) - abs($yDataSolver[$index])), 7) . "</td>";
                                }
                                echo '</tr></table>';
                            }
                        } else {
                            $dataStudent = $connect->getTaskRezult(1, $connect->getUserId($login, "users"), "taskdone");
                            if (!is_null($dataStudent)) {
                                $cont = true;
                                $dataStudent = unserialize($dataStudent);
                            } else {
                                $cont = false;
                            }
                            echo 'Завдання не виконане.';
                            if ($cont) {
                                echo ' Останні дані введені ';
                                echo $connect->getDateDone(1, $connect->getUserId($login, "users"), "taskdone"), ".<br>";
                            }
                            $dataSolver = $connect->getTaskRezult(1, $connect->getUserId($login, "users"), "tasktable");
                            $dataSolver = unserialize($dataSolver);
                            foreach ($dataSolver as $key => $value) {
                                $xData = array_keys($dataSolver[$key]);
                                $yDataSolver = array_values($dataSolver[$key]);
                                if ($cont) {
                                    $yDataStudent = array_values($dataStudent[$key]);
                                }
                                echo "<br><div>Дані для полінома $key ступеня<div><br><table class='task'><tr class='task'><td class='task'>x</td>";
                                for ($index = 0; $index < count($xData); $index++) {
                                    echo '<td class="task">' . $xData[$index] . "</td>";
                                }
                                echo '</tr><tr class="task"><td class="task">Дані вирішувача</td>';
                                for ($index = 0; $index < count($yDataSolver); $index++) {
                                    echo '<td class="task">' . $yDataSolver[$index] . "</td>";
                                }
                                if ($cont) {
                                    echo '</tr><tr class="task"><td class="task">Дані студента</td>';
                                    for ($index = 0; $index < count($yDataStudent); $index++) {
                                        echo '<td class="task">' . round($yDataStudent[$index],4) . "</td>";
                                    }
                                    echo '</tr><tr class="task"><td class="task">Відхилення</td>';
                                    for ($index = 0; $index < count($yDataStudent); $index++) {
                                        echo '<td class="task">' . round(abs(abs($yDataStudent[$index]) - abs($yDataSolver[$index])), 7) . "</td>";
                                    }
                                }
                                echo '</tr></table>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
