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
if (!$isLogin) {
    header("Location:login.php");
}
$login = $_COOKIE["login"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../Source/test.css">
        <title>Профіль</title>
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
                        <div>Дані аккаунта:</div>
                        <?php
                        $userData = $connect->getUserData($login, "users");
                        ?>
                        <div>Ім'я: <?php echo $userData[0]; ?></div>
                        <div>Прізвище: <?php echo $userData[1]; ?></div>
                        <?php if (!$connect->isUserAdmin($login, "users")) { ?>
                            <div>Група: <?php echo $userData[2]; ?></div>
                            <div>Варіант: <?php echo $userData[3]; ?></div>
                        <?php } ?>
                        <a href="changeUserData.php">Змінити</a><br><br>
                        <?php if (!$connect->isUserAdmin($login, "users")) { ?>
                            <div><h3>Таблиця завдань</h3></div><br>
                            <?php
                            for ($index = 0; $index < 6; $index++) {
                                $done[$index] = $connect->isDone($index + 1, $connect->getUserId($login, "users"), "taskdone");
                            }
                            echo "<table class='task' border='1'><tr class='task'>";
                            for ($index = 0; $index < count($done); $index++) {
                                $echoIndex = $index + 1;
                                echo "<td class='task'>Завдання $echoIndex</td>";
                            }
                            echo "</tr><tr class='task'>";
                            for ($index = 0; $index < count($done); $index++) {
                                if ($done[$index]) {
                                    echo "<td class='task'>Виконано</td>";
                                } else {
                                    echo "<td class='task'>Не виконано</td>";
                                }
                            }
                            echo '</tr></table><br>';
                            ?>
                            <?php if (!$done[0] or ! $done[1] or ! $done[2] or ! $done[3] or ! $done[4] or ! $done[5]) { ?>
                                <div><h3>Завдання</h3></div>
                            <?php } ?>
                            <?php if (!$done[0]) { ?>
                                <div><h4>Завдання 1. ІНТЕРПОЛЯЦІЯ</h4></div>
                                <div>Багаторазово диференціюєма функція f(x) задана таблицею значень у<sub>i</sub>&thickapprox;f(x<sub>i</sub>):</div>
                                <?php
                                $yData = array();
                                $xData = array();
                                $goalPoints = array();
                                $gp = "";
                                $data = $connect->getTaskData("1", $connect->getUserId($login, "users"), "tasktable");
                                $data = unserialize($data);
                                $goalPoints = $data["goalPoints"];
                                $xYData = $data["xyData"];
                                $xData = array_keys($xYData);
                                echo "<table class='task' border='1'><tr class='task'>";
                                foreach ($xData as $value) {
                                    echo "<td class='task'>$value</td>";
                                }
                                echo "</tr><tr class='task'>";
                                foreach ($xYData as $value) {
                                    echo "<td class='task'>$value</td>";
                                }
                                echo '</tr></table><br>';
                                $gp = "<div>Задані контрольні значення аргументу ";
                                foreach ($goalPoints as $key => $value) {
                                    if ($key == (count($goalPoints) - 1)) {
                                        $gp .= "$value";
                                    } else {
                                        $gp .= "$value, ";
                                    }
                                }
                                $gp .= ".</div>";
                                echo "$gp";
                                $gp = "<div>Записати підходящі для наближеного обчислення значень ";
                                foreach ($goalPoints as $key => $value) {
                                    if ($key == (count($goalPoints) - 1)) {
                                        $gp .= "y=f($value)";
                                    } else {
                                        $gp .= "y=f($value), ";
                                    }
                                }
                                $gp .= " конкретні інтерполяційні многочлени Лагранжа першого та другого ступеня і отримати ці значення.";
                                $gp .= "</div>";
                                echo "$gp";
                                ?>
                            <?php } ?>
                            <?php if (!$done[1]) { ?>
                                <div><h4>Завдання 2. АПРОКСИМАЦІЯ ФУНКЦІЙ, ЩО ЗАДАНІ ТАБЛИЦЕЮ</h4></div>
                                <div>Встановити вид емпіричної формули y = f(x), використовуючи апроксимуючі залежності з двома параметрами α і β 
                                    (див. Методичні вказівки), і визначити найкращі значення параметрів, якщо дослідні дані мають такий вигляд:</div>
                                <?php
                                $yData = array();
                                $xData = array();
                                $goalPoints = array();
                                $gp = "";
                                $data = $connect->getTaskData("2", $connect->getUserId($login, "users"), "tasktable");
                                $data = unserialize($data);
                                $data = $data["yData"];
                                $xData = array_keys($data);
                                echo "<table class='task' border='1'><tr  class='task'>";
                                foreach ($xData as $value) {
                                    echo "<td  class='task'>$value</td>";
                                }
                                echo "</tr><tr class='task'>";
                                foreach ($data as $value) {
                                    echo "<td  class='task'>$value</td>";
                                }
                                echo '</tr></table><br>';
                                $gp = ""
                                ?>
                            <?php } ?>
                            <?php if (!$done[2]) { ?>
                                <div><h4>Завдання 3. ІНТЕРПОЛЯЦІЯ ФУНКЦІЙ КУБІЧНИМИ СПЛАЙНАМИ</h4></div>
                                <div>Задана функция y=f(x)</div>
                                <?php
                                $yData = array();
                                $xData = array();
                                $goalPoints = array();
                                $gp = "";
                                $data = $connect->getTaskData("3", $connect->getUserId($login, "users"), "tasktable");
                                $data = unserialize($data);
                                $yData = $data["yData"];
                                $xStart = $data["xStart"];
                                $xEnd = $data["xEnd"];
                                $goalPoints = $data["GoalPoints"];
                                $h = $data["h"];
                                $checkValue = $xEnd + $h / 1000;
                                for ($index = $xStart; $index < $checkValue; $index+=$h) {
                                    $xData[] = $index;
                                }
                                echo "<table class='task' border='1'><tr class='task'>";
                                foreach ($xData as $value) {
                                    echo "<td class='task'>$value</td>";
                                }
                                echo "</tr><tr class='task'>";
                                foreach ($yData as $value) {
                                    echo "<td class='task'>$value</td>";
                                }
                                echo '</tr></table><br>';
                                echo "Побудувати кубічний сплайн, що інтерполює функцію у = f(х) на відрізку [$xStart; $xEnd] для "
                                . "рівномірного розбиття з кроком h = $h при крайових умовах I або II типу.";
                                $gp = "<div>Знайти значення сплайна в точках ";
                                foreach ($goalPoints as $key => $value) {
                                    if ($key == (count($goalPoints) - 1)) {
                                        $gp .= "$value";
                                    } else {
                                        $gp .= "$value, ";
                                    }
                                }
                                $gp .= ".</div>";
                                echo $gp;
                                ?>
                            <?php } ?>

                            <?php if (!$done[3]) { ?>
                                <div><h4>Завдання 4. ЧИСЕЛЬНЕ ІНТЕГРУВАННЯ</h4></div>
                                <?php
                                $yData = array();
                                $xData = array();
                                $goalPoints = array();
                                $gp = "";
                                $data = $connect->getTaskData("4", $connect->getUserId($login, "users"), "tasktable");
                                $data = unserialize($data);
                                $func = $data["function"];
                                $func = preg_replace("/unk/", "x", $func);
                                $a = $data["a"];
                                $b = $data["b"];
                                ?>
                                <div>Інтеграл заданий видом <img alt='integral' style="vertical-align: middle;" src="outTask/image/int.PNG">,
                                    де a = <?php echo $a; ?>, b=<?php echo $b; ?>, f(x) = <?php echo $func; ?></div>
                                <div>
                                    Обчислити заданий інтеграл за формулами прямокутників, трапецій і Сімпсона, 
                                    якщо відрізок інтегрування розбитий на n = 6 і n = 8 частин. 
                                    Оцінити похибку результату. 
                                </div>
                            <?php } ?>
                            <?php if (!$done[4]) { ?>
                                <div><h4>Завдання 5. ЧИСЕЛЬНЕ ДИФЕРЕНЦІЮВАННЯ</h4></div>
                                <div>Функція f(x)</div>
                                <?php
                                $yData = array();
                                $xData = array();
                                $goalPoints = array();
                                $gp = "";
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
                                echo "<table  class='task' border='1'><tr class='task'>";
                                foreach ($xData as $value) {
                                    echo "<td class='task'>$value</td>";
                                }
                                echo "</tr><tr class='task'>";
                                foreach ($yData as $value) {
                                    echo "<td class='task'>$value</td>";
                                }
                                echo '</tr></table><br>';
                                foreach ($goalPoints as $key => $value) {
                                    if ($key == (count($goalPoints) - 1)) {
                                        $gp .= "$value";
                                    } else {
                                        $gp .= "$value, ";
                                    }
                                }
                                ?>
                                <div>Вибравши крок h =<?php echo $h; ?>, за формулами знайти наближені значення
                                    похідних f '(x) і f' '(x) в точках <?php echo $gp; ?>; оцінити похибку обчислень.
                                </div> 
                            <?php } ?>
                            <?php if (!$done[5]) { ?>
                                <div><h4>Завдання 6. ЧИСЕЛЬНЕ РІШЕННЯ ЗВИЧАЙНИХ ДИФЕРЕНЦІАЛЬНИХ РІВНЯНЬ</h4></div>
                                <div>Вихідне рівняння:
                                    <?php
                                    $yData = array();
                                    $xData = array();
                                    $goalPoints = array();
                                    $gp = "";
                                    $data = $connect->getTaskData("6", $connect->getUserId($login, "users"), "tasktable");
                                    $data = unserialize($data);
                                    $h = $data["h"];
                                    $func = $connect->getFunctionEcho($data["function"], "functionsixthtask");
                                    $x0 = $data["x0"];
                                    $y0 = $data["y0"];
                                    echo $func;
                                    $endValue = $data["EndValue"];
                                    ?>
                                    і початкова умова y|<sub>x=<?php echo $x0; ?></sub>=<?php echo $y0; ?>
                                </div>
                                <div>
                                    Заповнити таблицю
                                </div>
                                <div>
                                    <?php
                                    for ($index = $x0 + $h; $index < $endValue + $h / 1000; $index+=$h) {
                                        $xData[] = $index;
                                    }
                                    echo "<table class='task' border='1'><tr class='task'><td class='task'>x</td>";
                                    foreach ($xData as $value) {
                                        echo "<td class='task'>$value</td>";
                                    }
                                    echo "</tr><tr class='task'><td class='task'>y</td>";
                                    foreach ($xData as $value) {
                                        echo "<td class='task'></td>";
                                    }
                                    echo "</tr><tr class='task'><td class='task'>n</td>";
                                    foreach ($xData as $value) {
                                        echo "<td class='task'></td>";
                                    }
                                    echo '</tr></table>';
                                    ?>
                                </div>
                                <div>
                                    наближеними значеннями y = y (x) даної задачі Коші, обчисленими з точністю &epsilon; = 10<sup>-5</sup> методом Рунге-Кутта з автоматичним вибором кроку (вказати остаточний розрахунковий крок у кожній точці таблиці).
                                    Використовуючи перші чотири значення рішення, продовжити обчислення до кінцевої точки з фіксованим кроком h = 0.14 методом Мілна.
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div>Вы администратор. <a href="adminPages/admin.php">Перейти на страницу администрирования.</a></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
