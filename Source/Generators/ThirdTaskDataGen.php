<?php

define("PRECISION_ROUND_THIRD_GEN", 5);
$goalPointsThirdTask = array(0.05, 0.09, 0.13, 0.15, 0.17);
$startArXThirdTask = array(0.0, 0.04, 0.08, 0.12, 0.16, 0.20); //значения х должны быть отсортированы по возрастанию

/**
 * Description of NumMetRThirdTaskDataGen
 *
 * @author Freedom
 */
class ThirdTaskDataGen {

    private $functions = array();
    private $func;
    private $var;
    private $goalPoints = array();
    private $yData = array();
    private $arX;
    private $startX;
    private $endX;
    private $h;

    function __construct($var) {
        if ($var < 1) {
            throw new Exception("Такой номер варианта не существует");
        }
        $this->var = $var;
        for ($index = 1, $xStart = 1; $index < $this->var; $index++) {
            $xStart++;
            if ($xStart > 4) {
                $xStart = 1;
            }
        }
        global $goalPointsThirdTask;
        foreach ($goalPointsThirdTask as $value) {
            $this->goalPoints[] = $xStart + $value;
        }
        global $startArXThirdTask;
        foreach ($startArXThirdTask as $x) {
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
            eval("\$xyData['$x'] = round(" . $calcFunc . ", PRECISION_ROUND_THIRD_GEN);"); //округление
        }
        $this->yData = $xyData;
        $this->startX = $this->arX[0];
        $this->endX = $this->arX[count($this->arX) - 1];
        $this->h = $this->arX[1] - $this->arX[0];
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

    public function getStartX() {
        if (count($this->yData) == 0) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        return $this->startX;
    }

    public function getEndX() {
        if (count($this->yData) == 0) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        return $this->endX;
    }

    public function getH() {
        if (count($this->yData) == 0) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        return $this->h;
    }

    public function getYData() {
        if (count($this->yData) == 0) {
            throw new Exception("Данные не сгенерированы, сгенерируйте данные методом calculate");
        }
        return array_values($this->yData);
    }

    public function getGoalPoints() {
        return $this->goalPoints;
    }

}
