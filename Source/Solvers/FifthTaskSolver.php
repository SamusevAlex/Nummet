<?php

define("PRECISION_ROUND_FIFTH_SOL", 5);

class FifthTaskSolver {

    private $n; //n - число разбиений отрезка [a,b]
    private $a;
    //координаты концов отрезка а, b
    private $b;
    private $h;
    private $y = array(); //значения функции y(i) в узлах, у(0) = f(a), y(n) =f(b)
    private $goalPoints = array();
    private $rezult = array();

    public function __construct($h, $a, $b, array $y, array $goalPoints) {
        $this->h = $h;
        $this->n = (int) round(($b - $a) / $h);
        if ($this->n < 3) {
            throw new Exception("Слишком маленький шаг, шаг не может быть меньше 3");
        }
        $this->a = $a;
        $this->b = $b;
        $this->y = $y;
        $this->goalPoints = $goalPoints;
    }

    public function getH() {
        return $this->h;
    }

    public function getStartCoordSegment() {
        return $this->a;
    }

    public function getEndCoordSegment() {
        return $this->b;
    }

    public function getYData() {
        return $this->y;
    }

    public function getRezult() {
        return $this->rezult;
    }

    public function setH($h) {
        $this->n = $h;
    }

    public function setStartCoordSegment($a) {
        $this->a = $a;
    }

    public function setEndCoordSegment($b) {
        $this->b = $b;
    }

    public function setYData(array $y) {
        $this->y = $y;
    }

    public function solver() {
        foreach ($this->goalPoints as $value) {
            $this->rezult["$value"] = $this->derivativeCalculates($value);
        }
    }

    private function derivativeCalculates($x) {
        $this->n = (int) round(($this->b - $this->a) / $this->h);
        if ($this->n < 3) {
            throw new Exception("Слишком маленький шаг, шаг не может быть меньше 3");
        }
        $i = floor(($x - $this->a) / $this->h + $this->h / 2);
        $h1 = 2 * $this->h;
        $h2 = $this->h * $this->h;
        if ($i == 0) {
            $y1 = (-3 * $this->y[0] + 4 * $this->y[1] - $this->y[2]) / $h1;
            $y2 = (2 * $this->y[0] - 5 * $this->y[1] + 4 * $this->y[2] - $this->y[3]) / $h2;
        }
        if (($i > 0) && ($i < $this->n)) {
            $y1 = (-$this->y[$i - 1] + $this->y[$i + 1]) / $h1;
            $y2 = ($this->y[$i - 1] - 2 * $this->y[$i] + $this->y[$i + 1]) / $h2;
        }
        if ($i == $this->n) {
            
            $y1 = ($this->y[$this->n - 2] - 4 * $this->y[$this->n - 1] + 3 * $this->y[$this->n]) / $h1;
            $y2 = (-$this->y[$this->n - 3] +
                    4 * $this->y[$this->n - 2] -
                    5 * $this->y[$this->n - 1] +
                    2 * $this->y[$this->n]) / $h2;
        }
        return array(1 => round($y1, PRECISION_ROUND_FIFTH_SOL), 2 => round($y2, PRECISION_ROUND_FIFTH_SOL));
    }

}
