<?php

define("PRECISION_ROUND_FIRST_GEN", 5);
$goalPointsFirstTask = array(0.24, 0.93, 1.65);
$startArXFirstTask = array(0.0, 0.2, 0.4, 0.6, 0.8, 1.0, 1.2, 1.4, 1.6); //значения х должны быть отсортированы по возрастанию

/**
 * Description of NumMetFirstTaskDataGen
 *
 * @author Freedom
 */
class FirstTaskDataGen {

    private $functions = array();
    public $func;
    private $var;
    private $goalPoints = array();
    private $xyData = array();
    private $arX;

    function __construct($var) {
        if($var < 1){
            throw new Exception("Такой номер варианта не существует");
        }
        $this->var = $var;
        for ($index = 1, $xStart = 1; $index < $this->var; $index++) {
            $xStart++;
            if ($xStart > 4) {
                $xStart = 1;
            }
        }
        global $goalPointsFirstTask;
        foreach ($goalPointsFirstTask as $value) {
            $this->goalPoints[] = $xStart + $value;
        }
        global $startArXFirstTask;
        foreach ($startArXFirstTask as $x) {
            $this->arX[] = $x + $xStart;
        }
    }

    public function calculate() {
        if (count($this->functions) == 0) {
            throw new Exception("Необходимо задать функции методом setFunctions");
        }
        if (empty($this->func)) {
            throw new Exception("Необходимо задать случайную фунцию методом selectRandomFunction");
        }
        $xyData = array();
        foreach ($this->arX as $x) {
            $calcFunc = preg_replace("/unk/", $x, $this->func);
            eval("\$xyData['$x'] = round(" . $calcFunc . ", PRECISION_ROUND_FIRST_GEN);"); //округление
        }
        $this->xyData = $xyData;
    }

    public function selectRandomFunction() {
        if (count($this->functions) == 0) {
            throw new Exception("Необходимо задать функции методом setFunction");
        }
        $this->func = $this->functions[mt_rand(0, count($this->functions) - 1)];
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

    public function getGoalPoints() {
        return $this->goalPoints;
    }

}
