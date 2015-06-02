<?php
require_once 'FifthTaskHelper.php';
define("PRECISION_ROUND_FIFTH_GEN", 7);
$goalPointsFifthTask = array(0.05, 0.13);
$startArXFifthTask = array(0.0, 0.02, 0.04, 0.06, 0.08, 0.1, 0.12, 0.14, 0.16, 0.18, 0.20); //значения х должны быть отсортированы по возрастанию
$arXCacl = array(0.01, 0.03, 0.05, 0.07, 0.09, 0.11, 0.13, 0.15, 0.17, 0.19); //значения х должны быть отсортированы по возрастанию

/**
 * Description of NumMetRFifthTaskDataGen
 *
 * @author Freedom
 */
class FifthTaskDataGen {

    private $functions = array();
    public $func;
    private $var;
    private $goalPoints = array();
    private $yData = array();
    private $arX;
    private $startX;
    private $endX;
    private $h;
    private $xCalc;

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
        global $goalPointsFifthTask;
        foreach ($goalPointsFifthTask as $value) {
            $this->goalPoints[] = $xStart + $value;
        }
        global $arXCacl;
        foreach ($arXCacl as $xCalc) {
            $this->xCalc[] = $xCalc + $xStart;
        }
        global $startArXFifthTask;
        foreach ($startArXFifthTask as $x) {
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
            eval("\$xyData['$x'] = round(" . $calcFunc . ", PRECISION_ROUND_FIFTH_GEN);"); //округление
        }
        $solver = new FifthTaskHelper($this->xCalc, $xyData);
        $solver->solver(count($xyData)-1);
        $this->yData = $solver->getRezult() + $xyData;
        ksort($this->yData);
        $arX = array_keys($this->yData);
        $this->startX = (float) $arX[0];
        $this->endX = (float) $arX[count($arX) - 1];
        $this->h = ($this->endX - $this->startX) / (count($arX) - 1);
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
