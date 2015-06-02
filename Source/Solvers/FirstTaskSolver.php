<?php
define("PRECISION_ROUND_FIRST_SOL", 4);
/**
 * Description of FirstTaskSolver
 *
 * @author Freedom
 */
class FirstTaskSolver {

    private $goalPoints;
    private $xData;
    private $yData;
    private $rezult;

    public function __construct(array $goalPoints, array $xYData) {
        $this->goalPoints = $goalPoints;
        $this->xData = array_keys($xYData);
        $this->yData = array_values($xYData);
    }

    public function getXData() {
        return $this->xData;
    }

    public function getYData() {
        return $this->yData;
    }

    public function getGoalPoints() {
        return $this->goalPoints();
    }

    public function getRezult() {
        return $this->rezult;
    }

    public function setGoalPoints(array $goalPoints) {
        $this->goalPoints = $goalPoints;
    }

    public function setXYData(array $xYData) {
        $this->xData = array_keys($xYData);
        $this->yData = array_keys($xYData);
    }

    public function solver($DegreeLagrangePolynomial) {
        foreach ($this->goalPoints as $value) {
            $this->rezult["$value"] = $this->selectXYData($value, $DegreeLagrangePolynomial + 1);
        }
    }

    private function selectXYData($goalPoint, $numberOfValues) {
        if ($numberOfValues === sizeof($this->xData)) {
            return $this->InterpolateLagrangePolynomial($goalPoint, $this->xData, $this->yData);
        } elseif ($numberOfValues > sizeof($this->xData)) {
            throw new Exception("недопустимое количество значений для данных");
        } else {
            $lastIndex = sizeof($this->xData) - 1;
            if ($goalPoint < $this->xData[0]) {
                for ($index = 0; $index < $numberOfValues; $index++) {
                    $xData[$index] = $this->xData[$index];
                    $yData[$index] = $this->yData[$index];
                }
                return $this->InterpolateLagrangePolynomial($goalPoint, $xData, $yData);
            } elseif ($goalPoint > $this->xData[$lastIndex]) {
                for ($index = $lastIndex, $counter = 0; $counter < $numberOfValues; $counter++, $index--) {
                    $xData[$counter] = $this->xData[$index];
                    $yData[$counter] = $this->yData[$index];
                }
                $xData = array_reverse($xData);
                $yData = array_reverse($yData);
                return $this->InterpolateLagrangePolynomial($goalPoint, $xData, $yData);
            } else {
                for ($index = 0; $index < count($this->xData); $index++) {
                    if ($this->xData[$index] < $goalPoint) {
                        $firstIndex = $index;
                    }
                }
                $cont = true;
                do {
                    if (($lastIndex - $numberOfValues - $firstIndex + 1) >= 0) {
                        $cont = false;
                    } else {
                        $firstIndex--;
                    }
                } while ($cont);
                $xData = array_slice($this->xData, $firstIndex, $numberOfValues);
                $yData = array_slice($this->yData, $firstIndex, $numberOfValues);
                return $this->InterpolateLagrangePolynomial($goalPoint, $xData, $yData);
            }
        }
    }

    private function InterpolateLagrangePolynomial($goalPoint, array $xData, array $yData) {
        $lagrangePolynomial = 0;
        $size = sizeof($xData);
        for ($i = 0; $i < $size; $i++) {
            $basicsPolynom = 1;
            for ($j = 0; $j < $size; $j++) {
                if ($j == $i) {
                    continue;
                }
                $basicsPolynom *= ($goalPoint - $xData[$j]) / ($xData[$i] - $xData[$j]);
            }
            $lagrangePolynomial += $basicsPolynom * $yData[$i];
        }
        return round($lagrangePolynomial, PRECISION_ROUND_FIRST_SOL); //округление
    }

}
