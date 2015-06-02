<?php
require_once '../../Source/SQL/SqlConnectNumMet.php';
require_once '../../Source/Registration.php';
require_once 'phpExcel/PHPExcel.php';
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
        <title></title>
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
                        Завантажте Excel файл наступного вигляду:<br>
                        <table class="task">
                            <tr class="task">
                                <td class="task">
                                    A
                                </td>
                                <td class="task">
                                    B
                                </td>
                                <td class="task">
                                    C
                                </td>
                                <td class="task">
                                    D
                                </td>
                                <td class="task">
                                    E
                                </td>
                            </tr>
                            <tr class="task">
                                <td class="task">
                                    1
                                </td>
                                <td class="task">
                                    Прізвище
                                </td>
                                <td class="task">
                                    Ім'я
                                </td>
                                <td class="task">
                                    Логін
                                </td>
                                <td class="task">
                                    Пароль
                                </td>
                            </tr>
                            <tr class="task">
                                <td class="task">
                                    ...
                                </td>
                                <td class="task">
                                    ...
                                </td>
                                <td class="task">
                                    ...
                                </td>
                                <td class="task">
                                    ...
                                </td>
                                <td class="task">
                                    ...
                                </td>
                            </tr>
                            <tr class="task">
                                <td class="task">
                                    n
                                </td>
                                <td class="task">
                                    Прізвище
                                </td>
                                <td class="task">
                                    Ім'я
                                </td>
                                <td class="task">
                                    Логін
                                </td>
                                <td class="task">
                                    Пароль
                                </td>
                            </tr>
                        </table><br>
                        Логін: поле може містити тільки латинські букви і цифри 0-9, не більше 24 символів.<br>
                        Пароль: повинен містити 5 або більше символів.<br>
                        Прізвище: поле може містити тільки латинські символи, кириличні символи, знак апострофа ('), дефіз (-), не більше 39 символів.<br>
                        Ім'я: поле може містити тільки латинські символи, кириличні символи, знак апострофа ('), дефіз (-), не більше 39 символів.<br>
                        Варіант (стовпчик A): поле повинно містити число від 1 до n, де числа від 1 до n - номер студента за журналом.<br>
                        <br><form action='regGroup.php' method='post' enctype='multipart/form-data'>
                            <input type='file' name='uploadfile' accept="application/vnd.ms-excel">
                            <input type='submit' value='Завантажити'>
                            <input type="hidden" name="submit" value="true">
                        </form>
                        <?php
                        if ($_POST['submit'] === "true") {
                            copy($_FILES['uploadfile']['tmp_name'], basename($_FILES['uploadfile']['name']));
                            $ar = array();
                            $file = basename($_FILES['uploadfile']['name']);
                            $group = preg_split("/\./", $file);
                            $group = $group[0];
                            $registrationSuccessful = true;
                            if (!preg_match('/^[\\\Є-Їa-zа-я0-9\-]{1,9}$/iu', $group)) {
                                $registrationSuccessful = FALSE;
                                $echo = "Назва повинна збігатися з назвою групи. Назва групи може містити тільки латинські символи, кириличні символи, цифри 0-9, дефіз (-), не більше 9 символів";
                            } else {
                                $inputFileType = PHPExcel_IOFactory::identify($file);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
                                $objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
                                $objPHPExcel = $objReader->load($file); // загружаем данные файла в объект
                                $ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив
                                $index = 1;
                                foreach ($ar as $ar_colls) {
                                    $var = $ar_colls[0];
                                    $lastName = $ar_colls[1];
                                    $firstName = $ar_colls[2];
                                    $login = $ar_colls[3];
                                    $password = $ar_colls[4];
                                    if (($reg = Registration::createNewUser($connect, $firstName, $lastName, $group, $var, $login, $password)) === true) {
                                        $index++;
                                    } else {
                                        $registrationSuccessful = false;
                                        if ($reg == "void field") {
                                            $echo = 'Заповніть всі поля. У рядку ' . $index;
                                        }
                                        if ($reg == "user Exist") {
                                            $echo = 'логін зайнятий. У рядку ' . $index;
                                        }
                                        if ($reg == "generation error") {
                                            $echo = 'Непередбачена помилка, переконайтеся що номер варіанта обраний правильно (номер варіанта повинен бути натуральним числом). У рядку ' . $index;
                                        }
                                        if ($reg === false) {
                                            $echo = "Заповніть поля відповідно до вимог. У рядку " . $index;
                                        }
                                        $connect->deleteGroup($group, "users");
                                        break;
                                    }
                                }
                                unlink($file);
                            }
                            if (isset($echo)) {
                                echo $echo;
                            } else {
                                echo 'Реєстрація завершена';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
