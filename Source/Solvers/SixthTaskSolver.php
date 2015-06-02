<?php

define("PRECISION_ROUND_SIXTH_SOL", 7);

class SixthTaskSolver {

    private $h;
    private $a;
    private $bData = array();
    private $function;
    private $e;
    private $y0;
    private $yRK = array();
    private $yMiln = array();
    private $nArray = array();

    public function __construct($h, $a, $endPoint, $function, $y0) {
        $this->y0 = $y0;
        $this->h = $h;
        $this->a = $a;
        $endPoint = $endPoint + 0.00001;
        for ($index = $a + $h; $index < $endPoint; $index+=$h) {
            $this->bData[] = $index;
        }
        $this->function = $function;
    }

    public function getRezult() {
        $rezult["yRK"] = $this->yRK;
        $rezult["nArray"] = $this->nArray;
        $rezult["Miln"] = $this->yMiln;
        return $rezult;
    }

    public function calculate($e) {
        $this->e = $e;
        foreach ($this->bData as $b) {
            $n = ceil(($b - $this->a) / pow($this->e, 1 / 4));
            $this->yRK[] = round($this->runKut($this->a, $b, $this->y0, $n), PRECISION_ROUND_SIXTH_SOL);
            $this->nArray[] = (int) $n;
        }
        $this->yMiln = $this->yRK;
        $this->yMiln = $this->miln($this->yMiln, $this->h, $this->bData, $this->e);
    }

    private function miln($yMiln, $h, $bData, $e) {
        for ($i = 3; $i < count($yMiln) - 1; $i++) {
            $ypr[$i + 1] = $yMiln[$i - 3] + 4 * $h / 3 * (2 * $this->calcY($bData[$i - 2], $yMiln[$i - 2]) - $this->calcY($bData[$i - 1], $yMiln[$i - 1]) + 2 * $this->calcY($bData[$i], $yMiln[$i]));
            $ykor[$i + 1] = $yMiln[$i - 1] + ($h / 3) * ($this->calcY($bData[$i - 1], $yMiln[$i - 1]) + 4 * $this->calcY($bData[$i], $yMiln[$i]) + $this->calcY($bData[$i + 1], $ypr[$i + 1]));
            $absPogr = 28 * abs($ykor[$i + 1] - $ypr[$i + 1]) / 29;
            if ($absPogr > $e) {
                $yMiln[$i + 1] = round($ykor[$i + 1], PRECISION_ROUND_SIXTH_SOL);
            } else {
                $yMiln[$i + 1] = round($ypr[$i + 1], PRECISION_ROUND_SIXTH_SOL);
            }
        }
        return $yMiln;
    }

    private function runKut($a, $b, $y0, $n) {
        $x = $a;
        $y = $y0;
        $h = ($b - $a) / $n;
        for ($i = 0; $i < $n; $i++) {
            $k1 = $h * $this->calcY($x, $y);
            $x1 = $x + $h / 2.;
            $y1 = $y + $k1 / 2.;
            $k2 = $h * $this->calcY($x1, $y1);
            $y1 = $y + $k2 / 2.;
            $k3 = $h * $this->calcY($x1, $y1);
            $x += $h;
            $y1 = $y + $k3;
            $k4 = $h * $this->calcY($x, $y1);
            $y += ($k1 + 2. * $k2 + 2. * $k3 + $k4) / 6.;
        }
        return $y;
    }

    private function calcY($x, $y) {
        $calc = 0;
        $calcFunc = preg_replace("/unk1/", "($x)", $this->function);
        $calcFunc = preg_replace("/unk2/", "($y)", $calcFunc);
        eval("\$calc = " . $calcFunc . ";"); //округление
        return $calc;
    }

}
