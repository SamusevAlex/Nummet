<?php
define("PRECISION_ROUND_FOUTH_GEN", 5);

class FouthTaskDataGen {

    private $functions = array();
    private $func;
    private $lower;
    private $upper;
    private $deviratives;
    private $connect;

    function __construct(SqlConnectNumMet $connect) {
        $this->connect = $connect;
    }

    public function calculate() {
        $deviratives = $this->connect->getDerivatives("functionfouthtask", $this->func);
        foreach ($deviratives as $key => $value) {
            $this->deviratives[$key + 2] = $value;
        }
        $ar = $this->selectDomain();
        $min1 = (float) $ar[0][0];
        $max1 = (float) $ar[0][1];
        $min2 = (float) $ar[1][0];
        $max2 = (float) $ar[1][1];
        $ar = array();
        $ar[] = $this->randomFloat($min1, $max1);
        $ar[] = $this->randomFloat($min2, $max2);
        $this->lower = round(min($ar), 3);
        $this->upper = round(max($ar), 3);
    }

    private function randomFloat($min, $max) {
        return ($min + lcg_value() * (abs($max - $min)));
    }

    private function selectDomain() {
        $some2 = $this->connect->getDomain("functionfouthtask", $this->func);
        $ar = preg_split("/;/", $some2);
        $some2 = $ar[mt_rand(0, count($ar) - 1)];
        $ar = preg_split("/\,/", $some2);
        foreach ($ar as $value) {
            $arRet[] = preg_split("/\.\./", $value);
        }
        return $arRet;
    }

    public function getFunction() {
        return $this->func;
    }

    public function getA() {
        return $this->lower;
    }

    public function getB() {
        return $this->upper;
    }
    
    public function getDeviratives() {
        return $this->deviratives;
    }

    public function selectRandomFunction() {
        if (count($this->functions) == 0) {
            throw new Exception("Необходимо задать функции методом setFunction");
        }
        $this->func = $this->functions[mt_rand(0, count($this->functions) - 1)];
        //$this->func = $this->functions[mt_rand(0, 9)];
    }

    public function setFunctions(array $ar) {
        $this->functions = $ar;
    }

}
