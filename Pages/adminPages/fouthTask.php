<?php
require_once '../../Source/SQL/SqlConnectNumMet.php';
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
if (!$connect->isUserAdmin($login, "users")) {
    header("Location:../index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Четвертое задание</title>
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
                        <h3>Форма вставки функції в завдання 4</h3>
                        <div>Приклад опису функції: 1/(1+exp(unk))</div>
                        <div>Приклад опису похідних: (exp(unk)*(exp(unk)-1))/pow((exp(unk)+1),3)</div>
                        <div>Приклад опису меж: -3..-2.5,-1.5..-1;-1..-0.8,0.8..1</div><br>
                        <form action="" method="POST">
                            <div class='inTask'><label class='inputTaskFouth' for='n'>Функція</label><input type="text" id='n' class='function' name="func"></div>
                            <div class='inTask'><label class='inputTaskFouth' for='d2'>Друга похідна</label><input type="text" id='d2' class='function' name="d2"></div>
                            <div class='inTask'><label class='inputTaskFouth' for='d3'>Третя похідна</label><input type="text" id='d3' class='function' name="d3"></div>
                            <div class='inTask'><label class='inputTaskFouth' for='d4'>Четверта похідна</label><input type="text" id='d4' class='function' name="d4"></div>
                            <div class='inTask'><label class='inputTaskFouth' for='d5'>П'ята похідна</label><input type="text" id='d5' class='function' name="d5"></div>
                            <div class='inTask'><label class='inputTaskFouth' for='p'>Межі інтегрування</label><input type="text" id='p' class='function' name="p"></div>
                            <input type="submit" value="Вставити функцію" style="margin-left: 10%;">
                        </form>
                        <?php
                        if (isset($_POST['func'])) {
                            $func = htmlspecialchars($_POST['func']);
                            $d2 = htmlspecialchars($_POST['d2']);
                            $d3 = htmlspecialchars($_POST['d3']);
                            $d4 = htmlspecialchars($_POST['d4']);
                            $d5 = htmlspecialchars($_POST['d5']);
                            $domain = htmlspecialchars($_POST['p']);
                            $connect = new SqlConnectNumMet(HOST, USER, PASSWORD, DB);
                            $connect->addFunctionsFouthTask($func, $d2, $d3, $d4, $d5, $domain, "functionfouthtask");
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>