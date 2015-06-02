<?php
define("PRECISION_ROUND_THIRD_SOL", 4);

/**
 * Description of ThirdTaskSolver
 *
 * @author freedom
 */

class ThirdTaskSolver {

    private $n; //число разбиений отрезка [a,b]
    private $xStartCoord;
    //координаты концов отрезка a, b
    private $xEndCoord;
    private $h;
    private $yData; //значения функции у(x) в узлах, у(0)=f(a), y(n)=f(b)
    private $goalPoints;
    private $rezult;

    public function __construct($h, $xStartCoord, $xEndCoord, array $yData, array $goalPoints) {
        $this->h = $h;
        $this->xStartCoord = $xStartCoord;
        $this->xEndCoord = $xEndCoord;
        $this->yData = $yData;
        $this->goalPoints = $goalPoints;
    }

    public function getH() {
        return $this->h;
    }

    public function getStartCoordSegment() {
        return $this->xStartCoord;
    }

    public function getEndCoordSegment() {
        return $this->xEndCoord;
    }

    public function getYData() {
        return $this->yData;
    }
    
    public function getRezult(){
        return $this->rezult;
    }

    public function setH($h) {
        $this->h = $h;
    }

    public function setStartCoordSegment($xStartCoord) {
        $this->xStartCoord = $xStartCoord;
    }

    public function setEndCoordSegment($xEndCoord) {
        $this->xEndCoord = $xEndCoord;
    }

    public function setYData(array $yData) {
        $this->yData = $yData;
    }

    public function solver() {
        foreach ($this->goalPoints as $x) {
            $this->rezult["$x"] = $this->splineCalculates($x);
        }
    }

    private function splineCalculates($x) {
        $this->n = round(($this->xEndCoord - $this->xStartCoord) / $this->h);
        for ($m[0] = 1, $m[$this->n] = $l[0] = 0, $i = 1; $i < $this->n; $i++) {
            $b[$i] = 3 * ($this->yData[$i + 1] - $this->yData[$i - 1]) / $this->h;
            $l[$i] = -1 / ($l[$i - 1] + 4);
            $m[$i] = $l[$i] * ($m[$i - 1] - $b[$i]);
        }
        for ($i = $this->n - 1; $i >= 1; $i--) {
            $m[$i] = $l[$i] * $m[$i + 1] + $m[$i];
        }
        if (($x >= $this->xStartCoord) and ( $x <= $this->xEndCoord)) {
            $i = floor(($x - $this->xStartCoord) / $this->h) + 1;
            $x0 = $this->xStartCoord + ($i - 1) * $this->h;
            $x1 = $x0 + $this->h;
            $s = $this->yData[$i - 1] * (($x - $x1) * ($x - $x1) * (2 * ($x - $x0) + $this->h)) / ($this->h * $this->h * $this->h) + $this->yData[$i] *
                    (($x - $x0) * ($x - $x0) * (2 * ($x1 - $x) + $this->h)) / ($this->h * $this->h * $this->h) + $m[$i - 1] * ($x - $x1) * ($x - $x1) *
                    ($x - $x0) / ($this->h * $this->h) + $m[$i] * ($x - $x0) * ($x - $x0) * ($x - $x1) / ($this->h * $this->h);
            return round($s, PRECISION_ROUND_THIRD_SOL); //округление
        } else {
            throw new Exception("х выходит за пределы отрезка [a,b]");
        }
    }

}
