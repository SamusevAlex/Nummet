<?php

/**
 * Description of SqlConnect
 *
 * @author Freedom
 */
mb_internal_encoding("UTF-8");
class SqlConnect {

    protected $host;
    protected $user;
    protected $pass;
    protected $db;

    public function __construct($host, $user, $pass, $db) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
    }

    protected function sqlQuery($sql) {
        $connect = @mysql_connect($this->host, $this->user, $this->pass);
        if (!$connect) {
            throw new Exception("Can't connect to DB, try later");
        }
        mysql_select_db($this->db, $connect);
        mysql_query("SET NAMES utf8");
        $result = mysql_query($sql);
        if ($result === false) {
            throw new Exception("Can't execute query");
        }
        return $result;
    }

}
