<?php
require_once '../Source/SQL/SqlConnectNumMet.php';
mb_internal_encoding("UTF-8");
define(HOST, "localhost");
define(USER, "root");
define(PASSWORD, "");
define(DB, "NumMet");
$connect = new SqlConnectNumMet(HOST, USER, PASSWORD, DB);

function mbTrim($strForTrim) {
    return preg_replace("/(^\s+)|(\s+$)/us", "", $strForTrim);
}

function clean($value) {
    $value = mbTrim($value);
    $value = strip_tags($value);
    $value = mysql_escape_string($value);
    $value = htmlspecialchars($value);
    return $value;
}

if ($connect->checkUserLogin($_COOKIE['login'], $_COOKIE['password'], "users")) {
    $isLogin = true;
} else {
    $isLogin = false;
}
if (!$isLogin) {
    header("Location:login.php");
}
$login = $_COOKIE["login"];
$userData = $connect->getUserData($login, "users");
if ($_POST["submit"] === "true") {
    if (sha1($_POST["password_old"]) === $_COOKIE['password']) {
        $firstName = clean($_POST['firstName']);
        $lastName = clean($_POST['lastName']);
        $group = clean($_POST['group']);
        $changeData = true;
        if ($_POST["password"] !== $_POST["password_re"]) {
            $changeData = false;
            $errors = "Паролі не співпадають";
        } else {
            if ($_POST["password"] !== "") {
                if (!preg_match("/^.{5,}$/us", $_POST["password"])) {
                    $changeData = false;
                    $errors = "Заповніть поля відповідно до вимог";
                }
            }
            if ($firstName == "") {
                $firstName = $userData[0];
            }
            if ($lastName == "") {
                $lastName = $userData[1];
            }
            if ($group == "") {
                $group = $userData[2];
            }
            if (!preg_match('/^[\\\Є-Їa-zа-я0-9\-]{1,9}$/iu', $group)) {
                $changeData = false;
                $errors = "Заповніть поля відповідно до вимог";
            }
            if (!preg_match('/^[\\\Є-Їa-zа-я\'\-]{1,39}$/iu', $firstName)) {
                $changeData = false;
                $errors = "Заповніть поля відповідно до вимог";
            }
            if (!preg_match('/^[\\\Є-Їa-zа-я\'\-]{1,39}$/iu', $lastName)) {
                $changeData = false;
                $errors = "Заповніть поля відповідно до вимог";
            }
            if ($changeData) {
                if ($_POST["password"] !== "") {
                    $connect->changeUserData($firstName, $lastName, $_POST["password"], $group, $login, "users");
                    setcookie("password", sha1($_POST["password"]));
                    header("Location:changeUserData.php");
                } else {
                    $connect->changeUserDataWithoutPass($firstName, $lastName, $group, $login, "users");
                    header("Location:changeUserData.php");
                }
            }
        }
    } else {
        $errors = "Неправильний пароль";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Змінити дані профілю</title>
        <link rel="stylesheet" type="text/css" href="../Source/test.css">
        <?php
        if ($isLogin) {
            echo '<script>var scrollStart = ' . 100 . ';</script>' . PHP_EOL;
        } else {
            echo '<script>var scrollStart = ' . 310 . ';</script>' . PHP_EOL;
        }
        ?>
        <script type="text/javascript" src="../Source/js/jquery.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="../Source/js/menu.js" charset="utf-8"></script>
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
                                <form action="Login.php" method="POST" enctype='multipart/form-data'>
                                    <div><label class="task" for="login">Логін</label><input type="text" id="login" name="login" required></div>
                                    <div><label class="task" for="pass">Пароль</label><input type="password" id="pass" name="password" required></div>
                                    <input type="hidden" name="submit" value="true">
                                    <input type="submit" id="submit1" value="Ввійти">
                                </form>
                            </div>
                        <?php } else { ?>
                            <a href="profile.php">Профіль</a> / <a href="logout.php">Вийти</a>
                        <?php } ?>
                    </div>
                </div>
                <div id="menu" class="default">
                    <div id="categories" class="obox">
                        <h2 class="heading">Меню</h2>
                        <div class="content">
                            <ul>
                                <li><h3><a href="index.php">Головна сторінка</a></h3></li>
                                <?php if ($isLogin) { ?>
                                    <li><h3><a href="profile.php">Сторінка профілю</a></h3></li>
                                    <li><h3><a href="changeUserData.php">Змінити профіль</a></h3></li>
                                <?php } else { ?>
                                    <li><h3><a href="login.php">Ввійти</a></h3></li>
                                    <li><h3><a href="Registration.php">Зареєструватися</a></h3></li>
                                <?php } ?>
                                <?php if (!$connect->isUserAdmin($login, "users")) { ?>
                                    <li><h3><a href="outTask/outTask.php">Вивести завдання</a></h3></li>
                                    <li><a href="outTask/firstTask.php">Завдання 1</a></li>
                                    <li><a href="outTask/secondTask.php">Завдання 2</a></li>
                                    <li><a href="outTask/thirdTask.php">Завдання 3</a></li>
                                    <li><a href="outTask/fouthTask.php">Завдання 4</a></li>
                                    <li><a href="outTask/fifthTask.php">Завдання 5</a></li>
                                    <li><a href="outTask/sixthTask.php">Завдання 6</a></li>
                                    <li><h3><a href="inTask/inTask.php">Ввести відповідь</a></h3></li>
                                    <li><a href="inTask/firstTask.php">Завдання 1</a></li>
                                    <li><a href="inTask/secondTask.php">Завдання 2</a></li>
                                    <li><a href="inTask/thirdTask.php">Завдання 3</a></li>
                                    <li><a href="inTask/fouthTask.php">Завдання 4</a></li>
                                    <li><a href="inTask/fifthTask.php">Завдання 5</a></li>
                                    <li><a href="inTask/sixthTask.php">Завдання 6</a></li>
                                <?php } else { ?>
                                    <li><h3><a href="adminPages/admin.php">Адміністрування</a></h3></li>
                                    <li><a href="adminPages/taskCheck.php">Перевірити завдання</a></li>
                                    <li><a href="adminPages/addFunction.php">Додати функцію</a></li>
                                    <li><a href="adminPages/deleteFunctionTask.php">Видалити функцію</a></li>
                                    <li><a href="adminPages/regGroup.php">Зареєструвати групу</a></li>
                                    <li><a href="adminPages/deleteGroup.php">Видалити групу</a></li>
                                    <li><a href="adminPages/createNewAdmin.php">Новий адміністратор</a></li>
                                    <?php
                                    if ($reg = $connect->checkRegistration("setings")) {
                                        echo '<li><a href="adminPages/changeReg.php">Закрити реєстрацію</a></li>';
                                    } else {
                                        echo '<a href="adminPages/changeReg.php">Відкрити реєстрацію</a></li>';
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
                        if ($errors != "") {
                            echo "<div class='varning'>" . $errors, "</div>";
                        }
                        ?>
                    </div>
                    <div id="newStory" class="story">
                        <form action="changeUserData.php" method="POST" enctype="multipart/form-data">
                            <div><label class="task" for="pass">Пароль</label><input type="password"  id="pass" class="pole" name="password_old"></div>
                            <div><label class="task" for="pass1">Новий пароль (Пароль повинен містити 5 або більше символів)</label><input type="password"  id="pass1" class="pole" name="password"></div>
                            <div><label class="task" for="pass2">Повторіть пароль (паролі повинні співпадати)</label><input type="password"  id="pass2" class="pole" name="password_re"></div>
                            <div><label class="task" for="lastName">Прізвище (поле може містити тільки латинські символи, кириличні символи, знак апострофа ('), дефіз (-), не більше 39 символів)</label><input type="text" id="lastName" class="pole" name="lastName" value="<?php echo $userData[1]; ?>"></div>
                            <div><label class="task" for="firstName">Ім'я (поле може містити тільки латинські символи, кириличні символи, знак апострофа ('), дефіз (-), не більше 39 символів)</label><input type="text" id="firstName" class="pole" name="firstName" value="<?php echo $userData[0]; ?>"></div>
                            <?php if (!$connect->isUserAdmin($login, "users")) { ?>
                                <div><label class="task" for="group">Група (поле може містити тільки латинські символи, кириличні символи, цифри 0-9, дефіз (-), не більше 9 символів)</label><input type="text" id="group" class="pole" name="group" value="<?php echo $userData[2]; ?>"></div>
                            <?php } ?>
                            <input type="hidden" name="submit" value="true">
                            <input type="submit" value="Змінити" style="margin-left: 18%;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
