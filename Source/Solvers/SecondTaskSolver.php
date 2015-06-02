<?php

define("PRECISION_ROUND_SECOND_SOL", 4);

/**
 * Description of SecondTaskSolver
 *
 * График с dmin, dmin-1, по точкам.
 * 
 * 
 * @author freedom
 */
class SecondTaskSolver {

    private $y = array();
    private $x = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);
    private $m = 5;
    private $y1 = array();
    private $x1 = array();
    private $d = array();
    private $j;
    private $a = array();
    private $b = array();
    private $d1;
    private $k;
    private $kAnswer;
    private $bAnswer;
    private $alfa;
    private $beta;
    private $d2;
    private $k2;
    private $kAnswer2;
    private $bAnswer2;
    private $alfa2;
    private $beta2;

    public function __construct(array $y) {
        $this->y = $y;
    }

    public function solver() {
        for ($this->j = 0; $this->j <= 6; $this->j++) {
            switch ($this->j) {
                case 0:
                    for ($i = 1; $i <= $this->m; $i++) {
                        $this->x1[$i] = $this->x[$i];
                        $this->y1[$i] = $this->y[$i];
                    }
                    break;
                case 1:
                    for ($i = 1; $i <= $this->m; $i++) {
                        $this->y1[$i] = $this->x[$i] * $this->y[$i];
                    }
                    break;
                case 2:
                    for ($i = 1; $i <= $this->m; $i++) {
                        $this->y1[$i] = 1 / $this->y[$i];
                    }
                    break;
                case 3:
                    for ($i = 1; $i <= $this->m; $i++) {
                        $this->y1[$i] = $this->x[$i] / $this->y[$i];
                    }
                    break;
                case 4:
                    for ($i = 1; $i <= $this->m; $i++) {
                        $this->y1[$i] = log($this->y[$i]);
                    }
                    break;
                case 5:
                    for ($i = 1; $i <= $this->m; $i++) {
                        $this->x1[$i] = log($this->x[$i]);
                        $this->y1[$i] = $this->y[$i];
                    }
                    break;
                case 6:
                    for ($i = 1; $i <= $this->m; $i++) {
                        $this->y1[$i] = log($this->y[$i]);
                    }
                    break;
            }
            $this->calculateParameters();
        }
        $dim = $this->d;
        for ($d1 = $dim[0], $k = 0, $i = 1; $i <= 6; $i++) {
            if ($dim[$i] < $d1) {
                $d1 = $dim[$i];
                $k = $i;
            }
        }
        $this->kAnswer = round($this->a[$k], PRECISION_ROUND_SECOND_SOL); //округление
        $this->bAnswer = round($this->b[$k], PRECISION_ROUND_SECOND_SOL); //округление
        $this->d1 = round($d1, 6);
        $this->k = $k;
        $this->alfa = round($this->calculateAlfa($this->k, $this->kAnswer, $this->bAnswer), PRECISION_ROUND_SECOND_SOL);
        $this->beta = round($this->calculateBeta($this->k, $this->kAnswer, $this->bAnswer), PRECISION_ROUND_SECOND_SOL);
        unset($dim[$k]);
        $dim = array_values($dim);
        for ($d1 = $dim[0], $k = 0, $i = 1; $i < 6; $i++) {
            if ($dim[$i] < $d1) {
                $d1 = $dim[$i];
                $k = $i;
            }
        }
        $this->kAnswer2 = round($this->a[$k], PRECISION_ROUND_SECOND_SOL); //округление
        $this->bAnswer2 = round($this->b[$k], PRECISION_ROUND_SECOND_SOL); //округление
        $this->d2 = round($d1, 6);
        $this->k2 = $k;
        $this->alfa2 = round($this->calculateAlfa($this->k2, $this->kAnswer2, $this->bAnswer2), PRECISION_ROUND_SECOND_SOL);
        $this->beta2 = round($this->calculateBeta($this->k2, $this->kAnswer2, $this->bAnswer2), PRECISION_ROUND_SECOND_SOL);
    }

    public function getRezult() {
        $array = array(
            "n" => $this->k,
            "alfa" => $this->alfa,
            "beta" => $this->beta,
            "d" => $this->d1
        );
        return $array;
    }

    private function calculateAlfa($k, $kAnswer, $bAnswer) {
        switch ($k) {
            case 0:
                return $kAnswer;
            case 1:
                return $kAnswer;
            case 2:
                return $kAnswer;
            case 3:
                return $kAnswer;
            case 4:
                return exp($bAnswer);
            case 5:
                return $kAnswer;
            case 6:
                return exp($bAnswer);
        }
    }

    private function calculateBeta($k, $kAnswer, $bAnswer) {
        switch ($k) {
            case 0:
                return $bAnswer;
            case 1:
                return $bAnswer;
            case 2:
                return $bAnswer;
            case 3:
                return $bAnswer;
            case 4:
                return exp($kAnswer);
            case 5:
                return $bAnswer;
            case 6:
                return $kAnswer;
        }
    }

    public function setXYData($xYData) {
        $this->y = $xYData;
    }

    public function getAlfa() {
        return $this->alfa;
    }

    public function getBeta() {
        return $this->beta;
    }

    public function getAlfa2() {
        return $this->alfa2;
    }

    public function getBeta2() {
        return $this->beta2;
    }

    public function getArrayD() {
        return $this->d;
    }

    public function getEmpFormula() {
        return $this->k;
    }

    public function getEmpFormula2() {
        return $this->k2;
    }

    public function getkAnswer() {
        return $this->kAnswer;
    }

    public function getbAnswer() {
        return $this->bAnswer;
    }

    public function getkAnswer2() {
        return $this->kAnswer2;
    }

    public function getbAnswer2() {
        return $this->bAnswer2;
    }

    public function getD() {
        return $this->d1;
    }

    public function getD2() {
        return $this->d2;
    }

    public function getFuncDepend($k, $alfa, $beta) {
        switch ($k) {
            case 0:
                $str = preg_replace("/#alfa#/", $alfa, "#alfa# * unk + #beta#");
                return preg_replace("/#beta#/", $beta, $str);
            case 1:
                $str = preg_replace("/#alfa#/", $alfa, "#alfa# + #beta# / unk");
                return preg_replace("/#beta#/", $beta, $str);
            case 2:
                $str = preg_replace("/#alfa#/", $alfa, "1 / (#alfa# * unk + #beta#)");
                return preg_replace("/#beta#/", $beta, $str);
            case 3:
                $str = preg_replace("/#alfa#/", $alfa, "unk / (#alfa# * unk + #beta#)");
                return preg_replace("/#beta#/", $beta, $str);
            case 4:
                $str = preg_replace("/#alfa#/", $alfa, "#alfa# * pow(#beta#, unk)");
                return preg_replace("/#beta#/", $beta, $str);
            case 5:
                $str = preg_replace("/#alfa#/", $alfa, "#alfa# *log(unk) + #beta#");
                return preg_replace("/#beta#/", $beta, $str);
            case 6:
                $str = preg_replace("/#alfa#/", $alfa, "#alfa# * pow(unk, #beta#)");
                return preg_replace("/#beta#/", $beta, $str);
        }
    }

    private function calculateParameters() {
        for ($а1 = $b1 = $а2 = $b2 = 0, $i = 1; $i <= $this->m; $i++) {
            $a1 = $a1 + $this->x1[$i];
            $b1 = $b1 + $this->y1[$i];
            $a2 = $a2 + $this->x1[$i] * $this->x1[$i];
            $b2 = $b2 + $this->x1[$i] * $this->y1[$i];
        }
        $d1 = $this->m * $a2 - $a1 * $a1;
        $k0 = ($this->m * $b2 - $a1 * $b1) / $d1;
        $b0 = ($b1 * $a2 - $a1 * $b2) / $d1;
        for ($d1 = $f2 = 0, $i = 1; $i <= $this->m; $i++) {
            $f2 = $f2 + $this->y1[$i] * $this->y1[$i];
            $d1 = $d1 + ($this->y1[$i] - $k0 * $this->x1[$i] - $b0) * ($this->y1[$i] - $k0 * $this->x1[$i] - $b0);
        }
        $this->d[$this->j] = sqrt($d1 / $f2);
        $this->a[$this->j] = $k0;
        $this->b[$this->j] = $b0;
    }

}
