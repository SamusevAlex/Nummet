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
if (!$connect->isUserAdmin($login, "users")) {
    header("Location:../index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Зарегистрировать нового администратора</title>
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
                        if ($_POST["submit"] === "true") {
                            $registrationSuccessful = true;
                            if ($_POST["password"] !== $_POST["password_re"]) {
                                $registrationSuccessful = false;
                                $echo = "Паролі не співпадають";
                            } else {
                                $lastName = $_POST["lastName"];
                                $firstName = $_POST["firstName"];
                                $group = "-";
                                $var = 0;
                                $login = $_POST["login"];
                                $password = $_POST["password"];
                                $rezult = Registration::createNewAdmin($connect, $firstName, $lastName, $group, $var, $login, $password);
                                if ($rezult === "void field") {
                                    $registrationSuccessful = false;
                                    $echo = "Заповніть всі поля";
                                }
                                if ($rezult === "user Exist") {
                                    $registrationSuccessful = false;
                                    $echo = "Логін зайнятий";
                                }
                                if ($rezult === false) {
                                    $registrationSuccessful = false;
                                    $echo = "Заповніть поля відповідно до вимог";
                                }
                                if ($rezult === "generation error") {
                                    $registrationSuccessful = false;
                                    $echo = "Непередбачена помилка, переконайтеся що номер варіанта обраний правильно (номер варіанта повинен бути натуральним числом)";
                                }
                            }
                        }
                        if ($echo !== "") {
                            echo "<div class='varning'>" . $echo, "</div>";
                        }
                        ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div><label class="task" for="login">Логін (поле може містити тільки латинські букви і цифри 0-9, не більше 24 символів)</label><input type="text"  id="login" class="pole" name="login" value="<?php echo $_POST["login"]; ?>"></div>
                            <div><label class="task" for="pass1">Пароль (Пароль повинен містити 5 або більше символів)</label><input type="password"  id="pass1" class="pole" name="password"></div>
                            <div><label class="task" for="pass2">Повторіть пароль (паролі повинні співпадати)</label><input type="password"  id="pass2" class="pole" name="password_re"></div>
                            <div><label class="task" for="lastName">Прізвище (поле може містити тільки латинські символи, кириличні символи, знак апострофа ('), дефіз (-), не більше 39 символів)</label><input type="text" id="lastName" class="pole" name="lastName" value="<?php echo $_POST["lastName"]; ?>"></div>
                            <div><label class="task" for="firstName">Ім'я (поле може містити тільки латинські символи, кириличні символи, знак апострофа ('), дефіз (-), не більше 39 символів)</label><input type="text" id="firstName" class="pole" name="firstName" value="<?php echo $_POST["firstName"]; ?>"></div>
                            <input type="hidden" name="submit" value="true">
                            <input type="submit" value="Зареєструвати" style="margin-left: 15%;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
