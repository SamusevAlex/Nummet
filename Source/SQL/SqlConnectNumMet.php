<?php

require_once 'SqlConnect.php';
mb_internal_encoding("UTF-8");

class SqlConnectNumMet extends SqlConnect {

    public function addFunctions($newFunc, $table) {
        $sql = "insert into $table value('', '$newFunc');";
        return $this->sqlQuery($sql);
    }

    public function addFunctionsFouthTask($newFunc, $d2, $d3, $d4, $d5, $domain, $table) {
        $sql = "INSERT INTO $table VALUES ('','$newFunc', '$domain', '$d2', '$d3', '$d4', '$d5');";
        return $this->sqlQuery($sql);
    }

    public function addFunctionsSixthTask($newFunc, $funcEcho, $y0x0, $table) {
        $sql = "INSERT INTO $table VALUES ('','$newFunc', '$funcEcho', '$y0x0');";
        return $this->sqlQuery($sql);
    }

    public function deleteFunctions($oldFunc, $table) {
        $sql = "DELETE from $table WHERE function =  '$oldFunc'";
        return $this->sqlQuery($sql);
    }

    public function getFunctions($table) {
        $sql = "SELECT function FROM `$table` ORDER BY `$table`.`id` ASC";
        $result = $this->sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $row[] = mysql_fetch_row($result);
            $rowReturn[] = $row[$i][0];
        }
        return $rowReturn;
    }

    public function getFunctionEcho($function, $table) {
        $sql = "SELECT FunctionEcho FROM `$table` where function = '$function' limit 1";
        $result = $this->sqlQuery($sql);
        $rowReturn = mysql_fetch_row($result);
        return $rowReturn[0];
    }

    public function getDomain($table, $function) {
        $sql = "SELECT domain FROM `$table` where function = '$function' limit 1";
        $result = $this->sqlQuery($sql);
        $rowReturn = mysql_fetch_row($result);
        return $rowReturn[0];
    }

    public function getDerivatives($table, $function) {
        $sql = "SELECT dev2td, dev3td, dev4th, dev5th FROM `$table` where function = '$function' limit 1";
        $result = $this->sqlQuery($sql);
        $rowReturn = mysql_fetch_row($result);
        return $rowReturn;
    }

    public function getY0X0($table, $function) {
        $sql = "SELECT y0x0 FROM `$table` where function = '$function' limit 1";
        $result = $this->sqlQuery($sql);
        $rowReturn = mysql_fetch_row($result);
        return $rowReturn[0];
    }

    /////////////////////////////////////////////////////////////////////////////////////////


    public function addUser($login, $password, $firstName, $lastName, $group, $var, $table) {
        $sql = "INSERT INTO $table (`login`, `Password`, `FirstName`, `LastName`, `Group`, `Var`) "
                . "VALUES ('$login', '$password', '$firstName', '$lastName', '$group', '$var');";
        return $this->sqlQuery($sql);
    }

    public function deleteGroup($group, $table) {
        $sql = "DELETE FROM $table WHERE `Group` = '$group'";
        return $this->sqlQuery($sql);
    }

    public function getGroups($table) {
        $sql = "select distinct `Group` from $table where `Group` <> '-'";
        $result = $this->sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $row[] = mysql_fetch_row($result);
            $rowReturn[] = $row[$i][0];
        }
        return $rowReturn;
    }

    public function addAdmin($login, $password, $firstName, $lastName, $group, $var, $table) {
        $sql = "INSERT INTO $table (`login`, `Password`, `FirstName`, `LastName`, `Group`, `Var`, `AdminsRights`) "
                . "VALUES ('$login', '$password', '$firstName', '$lastName', '$group', '$var', '1');";
        return $this->sqlQuery($sql);
    }

    public function checkRegistration($table) {
        $sql = "Select work From $table Where seting = 'RegistrationOpen'";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return (bool) $row[0];
    }

    public function selectAllStudents($table) {
        $sql = "SELECT `login`,`LastName`,`FirstName`,`Group` FROM `$table` WHERE `AdminsRights` = 0";
        $result = $this->sqlQuery($sql);
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $row[] = mysql_fetch_row($result);
            $rowReturn[] = $row[$i];
        }
        return $rowReturn;
    }

    public function changeRegistration($table, $reg) {
        $sql = "UPDATE $table SET work = '$reg' WHERE seting = 'RegistrationOpen';";
        return $this->sqlQuery($sql);
    }

    public function deleteUser($idStudent, $table) {
        $sql = "DELETE FROM $table WHERE idStudent = '$idStudent'";
        return $this->sqlQuery($sql);
    }

    public function insertTask($task, $idStudent, $data, $rezult, $table) {
        $sql = "INSERT INTO $table (`Task`, `idStudent`, `Data`, `Rezult`) VALUES ($task, $idStudent, '$data', '$rezult')";
        return $this->sqlQuery($sql);
    }

    public function checkUser($user, $password, $table) {
        $sql = "Select Password From $table Where login = \"$user\"";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return sha1($password) === $row[0];
    }

    public function changeUserData($firstName, $lastName, $password, $group, $login, $table) {
        $password = sha1($password);
        $sql = "UPDATE $table SET `FirstName` = '$firstName', LastName = '$lastName', Password='$password', `Group` = '$group' WHERE login = '$login'";
        return $this->sqlQuery($sql);
    }

    public function changeUserDataWithoutPass($firstName, $lastName, $group, $login, $table) {
        $sql = "UPDATE $table SET `FirstName` = '$firstName', LastName = '$lastName', `Group` = '$group' WHERE login = '$login'";
        return $this->sqlQuery($sql);
    }

    public function checkUserLogin($user, $passwordSha1, $table) {
        $sql = "Select Password From $table Where login = \"$user\"";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        if(is_null($passwordSha1) or is_null($row[0])){
            return FALSE;
        } else {
        return $passwordSha1 === $row[0];
        }
    }

    public function getUserId($user, $table) {
        $sql = "SELECT idStudent FROM $table where Login = '$user'";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function getTaskData($task, $idStudent, $table) {
        $sql = "SELECT Data FROM $table where idStudent = '$idStudent' and Task='$task'";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function taskCheck($task, $idStudent, $rezult, $done, $table) {
        $sql = "INSERT INTO $table (`Task`, `idStudent`, `Rezult`, `Done`) VALUES ($task, $idStudent, '$rezult', '$done')";
        return $this->sqlQuery($sql);
    }

    public function taskCheckExist($task, $idStudent, $table) {
        $sql = "SELECT Rezult FROM $table where idStudent = '$idStudent' and Task='$task'";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return !empty($row);
    }

    public function isDone($task, $idStudent, $table) {
        $sql = "Select Done From $table WHERE Task = $task AND idStudent = $idStudent;";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return (bool) $row[0];
    }

    public function getDateDone($task, $idStudent, $table) {
        $sql = "Select Date From $table WHERE Task = $task AND idStudent = $idStudent;";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function taskCheckUpdate($task, $idStudent, $rezult, $done, $table) {
        $sql = "UPDATE $table SET Rezult = '$rezult', `Done` = '$done' WHERE Task = $task AND idStudent = $idStudent;";
        return $this->sqlQuery($sql);
    }

    public function getTaskRezult($task, $idStudent, $table) {
        $sql = "SELECT Rezult FROM $table where idStudent = '$idStudent' and Task='$task'";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return $row[0];
    }

    public function getUserData($user, $table) {
        $sql = "SELECT FirstName, LastName, `Group`, Var FROM $table where Login = '$user'";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return $row;
    }

    public function isUserAdmin($user, $table) {
        $sql = "SELECT AdminsRights FROM $table where Login = '$user'";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return (bool) $row[0];
    }

    public function isUserExist($user, $table) {
        $sql = "Select Password From $table Where Login = \"$user\"";
        $result = $this->sqlQuery($sql);
        $row = mysql_fetch_row($result);
        return !empty($row);
    }

}
