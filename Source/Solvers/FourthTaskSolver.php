<?php

define("PRECISION_ROUND_FOUTH_SOL", 6);
define("PRECISION_ROUND_FOUTH_SOL_SIPMSON_R", 10);

/**
 * Description of FourthTaskSolver
 *
 * @author Freedom
 */
class FourthTaskSolver {

    private $function;
    private $a; //lower limit of integration
    private $b; //upper limit of integration
    private $rezult = array();
    private $deviratives;

    public function __construct($function, $a, $b, $deviratives) {
        $this->function = $function;
        $this->deviratives = $deviratives;
        if ($a > $b) {
            throw new Exception("a>b");
        }
        $this->a = $a;
        $this->b = $b;
    }

    public function getA() {
        return $this->a;
    }

    public function getB() {
        return $this->b;
    }

    public function getFunction() {
        return $this->function;
    }

    public function getRezult() {
        return $this->rezult;
    }

    public function setFunction($function) {
        $this->function = $function;
    }

    public function setA($a) {
        $this->a = $a;
    }

    public function setB($b) {
        $this->b = $b;
    }

    public function solver($n) {
        $h = ($this->b - $this->a) / $n;
        $xData = $this->calcXData($h);
        $xHalfData = $this->calcHalfXData($xData);
        $yData = $this->calcYData($xData);
        $yHalfData = $this->calcYData($xHalfData);
        $this->rezult["rectangle"][] = round($this->rectangleMethod($h, $yHalfData), PRECISION_ROUND_FOUTH_SOL);
        $this->rezult["trapezoidal"][] = round($this->trapezoidalMethod($h, $yData), PRECISION_ROUND_FOUTH_SOL);
        $this->rezult["simpson"][] = round($this->SimpsonMethod($h, $yHalfData, $yData), PRECISION_ROUND_FOUTH_SOL);
        $secondMaxDev = $this->maxDev(2);
        $fouthMaxDev = $this->maxDev(4);
        $this->rezult["rectangle"][] = round($this->rectangleRCalc($n, $secondMaxDev), PRECISION_ROUND_FOUTH_SOL);
        $this->rezult["trapezoidal"][] = round($this->trapezoidalRCalc($n, $secondMaxDev), PRECISION_ROUND_FOUTH_SOL);
        $this->rezult["simpson"][] = round($this->SimpsonRCalc($n, $fouthMaxDev), PRECISION_ROUND_FOUTH_SOL_SIPMSON_R);
    }

    private function maxDev($dev) {
        $x[] = $this->a;
        $x[] = $this->b;
        $xData = $this->calcXData(($this->b - $this->a) / 20);
        for ($index = 0; $index < count($xData) - 2; $index++) {
            $yl = $this->calcY($xData[$index], $this->deviratives[$dev + 1]);
            $yr = $this->calcY($xData[$index + 1], $this->deviratives[$dev + 1]);
            if ($yl * $yr < 0) {
                $x[] = $this->bisectionMethod($xData[$index], $xData[$index + 1], $dev);
            }
        }
        foreach ($x as $value) {
            $y[] = abs($this->calcY($value, $this->deviratives[$dev]));
        }
        return max($y);
    }

    private function bisectionMethod($xl, $xr, $dev) {
        $EPS = 0.001;
        $xd = $xr - $xl;
        while (abs($this->calcY($xl, $this->deviratives[$dev + 1])) > $EPS || abs($this->calcY($xr, $this->deviratives[$dev + 1])) > $EPS) {
            $xd = $xd / 2;
            $xm = $xl + $xd;
            if ($this->calcY($xl, $this->deviratives[$dev + 1]) > 0) {
                $signfxl = 1;
            } else {
                $signfxl = -1;
            }
            if ($this->calcY($xm, $this->deviratives[$dev + 1]) > 0) {
                $signfxm = 1;
            } else {
                $signfxm = -1;
            }
            if ($signfxl != $signfxm) {
                $xr = $xm;
            } else {
                $xl = $xm;
            }
        }
        return $xm;
    }

    private function rectangleRCalc($n, $secDer) {
        return pow($this->b - $this->a, 3) * $secDer / (24 * $n * $n);
    }

    private function trapezoidalRCalc($n, $secDer) {
        return pow($this->b - $this->a, 3) * $secDer / (12 * $n * $n);
    }

    private function SimpsonRCalc($n, $fouthDer) {
        return pow($this->b - $this->a, 5) * $fouthDer / (2880 * $n * $n * $n * $n);
    }

    private function rectangleMethod($h, $yHalfData) {
        $yHalfSum = 0;
        foreach ($yHalfData as $y) {
            $yHalfSum += $y;
        }
        return $h * $yHalfSum;
    }

    private function trapezoidalMethod($h, $yData) {
        $lastIndex = count($yData) - 1;
        $ySum = 0;
        for ($index = 1; $index < $lastIndex; $index++) {
            $ySum += $yData[$index];
        }
        $ySum += ($yData[0] + $yData[$lastIndex]) / 2;
        return $h * $ySum;
    }

    private function SimpsonMethod($h, $yHalfData, $yData) {
        $lastIndex = count($yData) - 1;
        $ySum = 0;
        for ($index = 1; $index < $lastIndex; $index++) {
            $ySum += $yData[$index];
        }
        $ySum *= 2;
        $yHalfSum = 0;
        foreach ($yHalfData as $y) {
            $yHalfSum += $y;
        }
        $yHalfSum *=4;
        $ySum = $ySum + $yHalfSum + $yData[0] + $yData[$lastIndex];
        return $h / 6 * $ySum;
    }

    private function calcYData($xData) {
        $yData = array();
        foreach ($xData as $x) {
            $calcFunc = preg_replace("/unk/", $x, $this->function);
            eval("\$yData[] = round(" . $calcFunc . ", PRECISION_ROUND_FOUTH_SOL);"); //округление
        }
        return $yData;
    }

    private function calcY($x, $function) {
        $y = 0;
        $calcFunc = preg_replace("/unk/", $x, $function);
        eval("\$y = round(" . $calcFunc . ", PRECISION_ROUND_FOUTH_SOL);"); //округление
        return $y;
    }

    private function calcHalfXData($xData) {
        for ($index = 1; $index < count($xData); $index++) {
            $xHalfData[] = ($xData[$index - 1] + $xData[$index]) / 2;
        }
        return $xHalfData;
    }

    private function calcXData($h) {
        $checkVal = $this->b + 0.000001;
        for ($index = $this->a; $index <= $checkVal; $index = $index + $h) {
            $xData[] = $index;
        }
        return $xData;
    }

}
