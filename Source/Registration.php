<?php

mb_internal_encoding("UTF-8");
require_once 'Generators/FirstTaskDataGen.php';
require_once 'Generators/SecondTaskDataGen.php';
require_once 'Generators/ThirdTaskDataGen.php';
require_once 'Generators/FouthTaskDataGen.php';
require_once 'Generators/FifthTaskDataGen.php';
require_once 'Generators/SixthTaskDataGen.php';
require_once 'Solvers/FirstTaskSolver.php';
require_once 'Solvers/SecondTaskSolver.php';
require_once 'Solvers/ThirdTaskSolver.php';
require_once 'Solvers/FourthTaskSolver.php';
require_once 'Solvers/FifthTaskSolver.php';
require_once 'Solvers/SixthTaskSolver.php';
require_once 'SomeFunctions.php';
require_once 'SQL/SqlConnectNumMet.php';

class Registration {

    public static function createNewUser(SqlConnectNumMet $connect, $firstName, $lastName, $group, $var, $login = "", $password = "") {
        if ($login === "") {
            $login = self::generateLogin($connect);
            $password = self::generatePassword();
        }
        if ($firstName == "" or $lastName == "" or $group == "" or $var == "") {
            return "void field";
        }
        $firstName = self::clean($firstName);
        $lastName = self::clean($lastName);
        $group = self::clean($group);
        $var = self::clean($var);
        $login = self::clean($login);
        if (self::checkData($firstName, $lastName, $group, $var, $login, $password)) {
            if ($connect->isUserExist($login, "users")) {
                return "user Exist";
            }
            $passwordSha = sha1($password);
            $connect->addUser($login, $passwordSha, $firstName, $lastName, $group, $var, "users");
            $idUser = $connect->getUserId($login, "users");
            try {
                $array = self::firstTask($var, $connect);
                $data = $array["data"];
                $rezult = $array["rezult"];
                $connect->insertTask("1", $idUser, $data, $rezult, "tasktable");
                $array = self::secondTask($var, $connect);
                $data = $array["data"];
                $rezult = $array["rezult"];
                $connect->insertTask("2", $idUser, $data, $rezult, "tasktable");
                $array = self::thirdTask($var, $connect);
                $data = $array["data"];
                $rezult = $array["rezult"];
                $connect->insertTask("3", $idUser, $data, $rezult, "tasktable");
                $array = self::fouthTask($var, $connect);
                $data = $array["data"];
                $rezult = $array["rezult"];
                $connect->insertTask("4", $idUser, $data, $rezult, "tasktable");
                $array = self::fifthTask($var, $connect);
                $data = $array["data"];
                $rezult = $array["rezult"];
                $connect->insertTask("5", $idUser, $data, $rezult, "tasktable");
                $array = self::sixthTask($var, $connect);
                $data = $array["data"];
                $rezult = $array["rezult"];
                $connect->insertTask("6", $idUser, $data, $rezult, "tasktable");
            } catch (Exception $exc) {
                $connect->deleteUser($idUser, "users");
                return "generation error";
            }
            return true;
        } else {
            return false;
        }
    }

    public static function createNewAdmin(SqlConnectNumMet $connect, $firstName, $lastName, $group, $var, $login, $password) {
        if ($firstName == "" or $lastName == "" or $group == "" or $var === "" or $login == "" or $password == "") {
            return "void field";
        }
        $firstName = self::clean($firstName);
        $lastName = self::clean($lastName);
        $group = self::clean($group);
        $var = self::clean($var);
        $login = self::clean($login);
        if (self::checkData($firstName, $lastName, $group, $var, $login, $password)) {
            if ($connect->isUserExist($login, "users")) {
                return "user Exist";
            }
            $passwordSha = sha1($password);
            return $connect->addAdmin($login, $passwordSha, $firstName, $lastName, $group, $var, "users");
        }
    }

    public static function checkData($firstName, $lastName, $group, $var, $login, $password) {
        if (!preg_match("/^.{5,}$/us", $password)) {
            return FALSE;
        }
        if (!preg_match("/^\w{1,24}$/u", $login)) {
            return FALSE;
        }
        if (!preg_match('/^[\\\Є-Їa-zа-я\'\-]{1,39}$/iu', $firstName)) {
            return FALSE;
        }
        if (!preg_match('/^[\\\Є-Їa-zа-я\'\-]{1,39}$/iu', $lastName)) {
            return FALSE;
        }
        if (!is_numeric($var)) {
            return FALSE;
        }
        if (!preg_match('/^[\\\Є-Їa-zа-я0-9\-]{1,9}$/iu', $group)) {
            return FALSE;
        }
        return true;
    }

    public static function clean($value) {
        $value = self::mbTrim($value);
        $value = strip_tags($value);
        $value = mysql_escape_string($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    private static function mbTrim($strForTrim) {
        return preg_replace("/(^\s+)|(\s+$)/us", "", $strForTrim);
    }

    private static function generateLogin(SqlConnectNumMet $connect) {
        $cont = true;
        while ($cont) {
            $symbol_arr = array('aeiouy', 'bcdfghjklmnpqrstvwxz');
            $length = mt_rand(4, 10);
            $return = array();
            foreach ($symbol_arr as $k => $v) {
                $symbol_arr[$k] = str_split($v);
            }
            for ($i = 0; $i < $length; $i++) {
                while (true) {
                    $symbol_x = mt_rand(0, sizeof($symbol_arr) - 1);
                    $symbol_y = mt_rand(0, sizeof($symbol_arr[$symbol_x]) - 1);
                    if ($i > 0 && in_array($return[$i - 1], $symbol_arr[$symbol_x])) {
                        continue;
                    }
                    $return[] = $symbol_arr[$symbol_x][$symbol_y];
                    break;
                }
            }
            $return = ucfirst(implode('', $return));
            if (!$connect->isUserExist($return, "users")) {
                $cont = false;
            }
        }
        return $return;
    }

    private static function generatePassword() {
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max = 10;
        $size = StrLen($chars) - 1;
        $password = null;
        while ($max--) {
            $password.=$chars[rand(0, $size)];
        }
        return $password;
    }

    private static function firstTask($var, $connect) {
        $obj = new FirstTaskDataGen($var);
        $ar = $connect->getFunctions("functionfirsttask");
        $obj->setFunctions($ar);
        $obj->selectRandomFunction();
        $obj->calculate();
        $data["goalPoints"] = $obj->getGoalPoints();
        $data["xyData"] = $obj->getXYData();
        $serData = serialize($data);
        $solver = new FirstTaskSolver($data["goalPoints"], $data["xyData"]);
        $solver->solver(1);
        $rezult[1] = $solver->getRezult();
        $solver->solver(2);
        $rezult[2] = $solver->getRezult();
        $serRez = serialize($rezult);
        return array("data" => $serData, "rezult" => $serRez);
    }

    private static function secondTask($var, $connect) {
        $obj = new SecondTaskDataGen($var);
        $ar = $connect->getFunctions("functionsecondtask");
        $obj->setFunctions($ar);
        $obj->selectRandomFunction();
        $obj->calculate();
        $array["yData"] = $obj->getXYData();
        $serData = serialize($array);
        $solverObj = new SecondTaskSolver($array["yData"]);
        $solverObj->solver();
        $serRez = serialize($solverObj->getRezult());
        return array("data" => $serData, "rezult" => $serRez);
    }

    private static function thirdTask($var, $connect) {
        $obj = new ThirdTaskDataGen($var);
        $ar = $connect->getFunctions("functionthirdtask");
        $obj->setFunctions($ar);
        $obj->selectRandomFunction();
        $obj->calculate();
        $data["yData"] = $obj->getYData();
        $data["GoalPoints"] = $obj->getGoalPoints();
        $data["h"] = $obj->getH();
        $data["xStart"] = $obj->getStartX();
        $data["xEnd"] = $obj->getEndX();
        $serData = serialize($data);
        $solver = new ThirdTaskSolver($data["h"], $data["xStart"], $data["xEnd"], $data["yData"], $data["GoalPoints"]);
        $solver->solver();
        $serRez = serialize($solver->getRezult());
        return array("data" => $serData, "rezult" => $serRez);
    }

    private static function fouthTask($var, $connect) {
        $obj = new FouthTaskDataGen($connect);
        $ar = $connect->getFunctions("FunctionFouthTask");
        $obj->setFunctions($ar);
        $obj->selectRandomFunction();
        $obj->calculate();
        $data["function"] = $obj->getFunction();
        $data["a"] = $obj->getA();
        $data["b"] = $obj->getB();
        $serData = serialize($data);
        $solver = new FourthTaskSolver($data["function"], $data["a"], $data["b"], $obj->getDeviratives());
        $solver->solver(6);
        $rezult[6] = $solver->getRezult();
        $solver = new FourthTaskSolver($data["function"], $data["a"], $data["b"], $obj->getDeviratives());
        $solver->solver(8);
        $rezult[8] = $solver->getRezult();
        $serRez = serialize($rezult);
        return array("data" => $serData, "rezult" => $serRez);
    }

    private static function fifthTask($var, $connect) {
        $obj = new FifthTaskDataGen($var);
        $ar = $connect->getFunctions("functionfifthtask");
        $obj->setFunctions($ar);
        $obj->selectRandomFunction();
        $obj->calculate();
        $data["yData"] = $obj->getYData();
        $data["GoalPoints"] = $obj->getGoalPoints();
        $data["h"] = $obj->getH();
        $data["xStart"] = $obj->getStartX();
        $data["xEnd"] = $obj->getEndX();
        $serData = serialize($data);
        $solver = new FifthTaskSolver($data["h"], $data["xStart"], $data["xEnd"], $data["yData"], $data["GoalPoints"]);
        $solver->solver();
        $serRez = serialize($solver->getRezult());
        return array("data" => $serData, "rezult" => $serRez);
    }

    private static function sixthTask($var, $connect) {
        $obj = new SixthTaskDataGen($var);
        $ar = $connect->getFunctions("functionsixthtask");
        $obj->setFunctions($ar);
        $obj->selectRandomFunction();
        $obj->calculate($connect);
        $data["function"] = $obj->getFunction();
        $data["x0"] = $obj->getXO();
        $data["h"] = $obj->getH();
        $data["y0"] = $obj->getYO();
        $data["EndValue"] = $obj->getEndValue();
        $serData = serialize($data);
        $solver = new SixthTaskSolver($data["h"], $data["x0"], $data["EndValue"], $data["function"], $data["y0"]);
        $solver->calculate(pow(10, -5));
        $serRez = serialize($solver->getRezult());
        return array("data" => $serData, "rezult" => $serRez);
    }

}
