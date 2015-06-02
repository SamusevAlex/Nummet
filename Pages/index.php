<?php
require_once '../Source/SQL/SqlConnectNumMet.php';
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
$login = $_COOKIE["login"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Головна сторінка</title>
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
                        <p><span class="tab"></span>
                            Даний веб-сайт був розроблений в рамках дипломного проекту з теми 
                            "Інформаційно-програмні заходи розробки елементів автоматизованої навчальної 
                            системи з дисципліни «Чисельні методи»". Розробник &horbar; студент групи 
                            РІ-101 Самусєв О.С. під керівництвом доцента кафедри ІТПЕТ Андріянова О.В.
                        </p>
                        <p><span class="tab"></span>
                            Основна функція веб-сайту автоматизувати перевірку розрахунково-графічної роботи 
                            з дисципліни "Чисельні методи". В програмі передбачено виконання 6 завдань з 
                            чисельних методів серед яких:
                        </p> 
                        <ol class="tab">
                            <li class="tab">Інтерполяція;</li>
                            <li class="tab">Апроксимація функцій, що задані таблицею;</li>
                            <li class="tab">Інтерполяція функцій кубічними сплайнами;</li>
                            <li class="tab">Чисельне інтегрування;</li>
                            <li class="tab">Чисельне диференціювання;</li>
                            <li class="tab">Чисельне рішення звичайних диференціальних рівнянь.</li>
                        </ol>
                        <p><span class="tab"></span>
                            Для кожного завдання був написаний генератор значень або функцій який забеспечує 
                            унікальність варіанту завдань для кожного студента. Також для кожного завдання був
                            написаний вирішувач, який забеспечує знаходження відповідей на згенеровані варіанти
                            для подальшої перевірки відповідей студента. 
                        </p>
                        <p><span class="tab"></span>
                            Для кожного завдання були написані форми виводу завдань з можливістю завантажити
                            завдання або методичні вказівки на пристрій користувача. Також були написані форми
                            вводу даних студента для подальшої перевірки відповіді на правильність.
                        </p>
                        <p><span class="tab"></span>
                            На веб-сайті був розроблений інтерфейс адміністратора з наступними можливостями:
                        </p>
                        <ol class="tab">
                            <li class="tab">Зареєструвати нового адміністратора;</li>
                            <li class="tab">Додати функцію (функції використовуються для генерації значень);</li>
                            <li class="tab">Видалити функцію;</li>
                            <li class="tab">Закрити/Відкрити реєстрацію;</li>
                            <li class="tab">
                                Зареєструвати групу (необхідно завантажити xls файл з 
                                заповненими полями зі списком групи);
                            </li>
                            <li class="tab">Видалити групу;</li>
                            <li class="tab">Перевірити завдання (виводяться сгенеровані дані та дані введені студентом).</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
