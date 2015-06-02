<?php

$h = 0.14;
$endValue = 1.26;
$e = 1.0E-5;

class SixthTaskDataGen {

    private $functions = array();
    private $func;
    private $var;
    private $x0 = null;
    private $y0 = null;

    function __construct($var) {
        if ($var < 1) {
            throw new Exception("Такой номер варианта не существует");
        }
        $this->var = $var;
    }

    public function getFunction() {
        return $this->func;
    }

    public function selectRandomFunction() {
        if (count($this->functions) == 0) {
            throw new Exception("Необходимо задать функции методом setFunction");
        }
        $continue = true;
        $count = count($this->functions);
        while ($continue) {
            if ($this->var > $count) {
                $this->var -= count($this->functions);
            } else {
                $this->func = $this->functions[$this->var - 1];
                $continue = false;
            }
        }
    }

    public function calculate(SqlConnectNumMet $connect) {
        $y0x0 = $connect->getY0X0("functionsixthtask", $this->func);
        $array = preg_split("/;/", $y0x0);
        foreach ($array as $value) {
            $array2[] = preg_split("/=/", $value);
        }
        $this->x0 = (float) $array2[1][1];
        $this->y0 = (float) $array2[0][1];
    }

    public function getYO() {
        if ($this->y0 === null) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        return $this->y0;
    }

    public function getE() {
        if ($this->x0 === null) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        global $e;
        return $e;
    }

    public function getEndValue() {
        if ($this->x0 === null) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        global $endValue;
        return $endValue + $this->x0;
    }

    public function getH() {
        if ($this->x0 === null) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        global $h;
        return $h;
    }

    public function getXO() {
        if ($this->x0 === null) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        return $this->x0;
    }

    public function setFunctions(array $ar) {
        $this->functions = $ar;
    }

}
