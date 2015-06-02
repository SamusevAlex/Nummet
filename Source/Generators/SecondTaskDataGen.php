<?php

define("PRECISION_ROUND_SECOND_GEN", 4);
define("MIN_EPS_VALUE", -3);
define("MAX_EPS_VALUE", 3);
define("DIVIDER_EPS_VALUE", 100); //test
$startArXSecondTask = array(1, 2, 3, 4, 5);

/**
 * Description of NumMetSecondTaskDataGen
 *
 * @author Freedom
 */
class SecondTaskDataGen {

    private $functions = array();
    private $func;
    private $var;
    private $xyData = array();

    function __construct($var) {
        $this->var = $var;
    }

    public function calculate() {
        if (count($this->functions) == 0) {
            throw new Exception("Необходимо задать функции методом setFunction");
        }
        if (empty($this->func)) {
            throw new Exception("Необходимо задать случайную фунцию методом selectRandomFunction");
        }
        $xyData = array();
        global $startArXSecondTask;
        $arX = $startArXSecondTask;
        foreach ($arX as $x) {
            $calcFunc = preg_replace("/unk/", $this->xDispersion($x), $this->func);
            eval("\$xyData['$x'] = round(" . $calcFunc . ", PRECISION_ROUND_SECOND_GEN);"); //округление
            
            $this->xyData = $xyData;
        }
    }
    
    

    private function xDispersion($x) {
        $disp = mt_rand(MIN_EPS_VALUE, MAX_EPS_VALUE) / DIVIDER_EPS_VALUE;
        return $x + $disp;
    }

    public function selectRandomFunction() {
        if (count($this->functions) == 0) {
            throw new Exception("Необходимо задать функции методом setFunction");
        }
        $func = $this->functions[mt_rand(0, count($this->functions) - 1)];
        $func = preg_replace("/#alfa#/", $this->genAlfa(), $func);
        $func = preg_replace("/#beta#/", $this->genBeta(), $func);
        $this->func = $func;
    }

    private function genAlfa() {
        $rand = mt_rand(1, 2);
        $rand2 = mt_rand(1, 1000);
        $rand = $rand . "." . $rand2;
        return (float) $rand;
    }

    private function genBeta() {
        $rand = mt_rand(0, 1);
        $rand2 = mt_rand(200, 1000);
        $rand = $rand . "." . $rand2;
        return (float) $rand;
    }

    public function setFunctions(array $ar) {
        $this->functions = $ar;
    }

    public function getXYData() {
        if (count($this->xyData) == 0) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        return $this->xyData;
    }

}
