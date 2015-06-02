<?php

class TaskCheck {

    private static $firstValue = 0.002;
    private static $secondValueAlfa = 0.004;
    private static $secondValueBeta = 0.004; 
    private static $secondValueD = 0.0001;
    private static $thirdValue = 0.005; 
    private static $fouthValueE = 0.005; 
    private static $fouthEE = 0.0001; 
    private static $fifthFirstD = 0.0005;
    private static $fifthSecondD = 0.006;
    private static $sixthValueyRK = 0.003;
    private static $sixthValueMiln = 0.007; 

    public static function firstTaskCheck($arrayEt, $arrayCheck) {
        $arrayObject1 = new ArrayObject($arrayEt);
        $arrayObject2 = new ArrayObject($arrayCheck);
        $iterator1 = $arrayObject1->getIterator();
        $iterator2 = $arrayObject2->getIterator();
        for ($iterator1->rewind(), $iterator2->rewind(); $floatEt = $iterator1->current(), $floatCheck = $iterator2->current(); $iterator1->next(), $iterator2->next()) {
            $check[] = self::floatCheck($floatEt, $floatCheck, TaskCheck::$firstValue);
        }
        return self::checkBoolArray($check);
    }

    public static function secondTaskCheck($arrayEt, $arrayCheck) {
        $check[] = TaskCheck::floatCheck($arrayEt["alfa"], $arrayCheck["alfa"], TaskCheck::$secondValueAlfa);
        $check[] = TaskCheck::floatCheck($arrayEt["beta"], $arrayCheck["beta"], TaskCheck::$secondValueBeta);
        $check[] = TaskCheck::floatCheck($arrayEt["d"], $arrayCheck["d"], TaskCheck::$secondValueD);
        return self::checkBoolArray($check);
    }

    public static function thirdTaskCheck($arrayEt, $arrayCheck) {
        $arrayObject1 = new ArrayObject($arrayEt);
        $arrayObject2 = new ArrayObject($arrayCheck);
        $iterator1 = $arrayObject1->getIterator();
        $iterator2 = $arrayObject2->getIterator();
        for ($iterator1->rewind(), $iterator2->rewind(); $floatEt = $iterator1->current(), $floatCheck = $iterator2->current(); $iterator1->next(), $iterator2->next()) {
            $check[] = TaskCheck::floatCheck($floatEt, $floatCheck, TaskCheck::$thirdValue);
        }
        return self::checkBoolArray($check);
    }

    public static function fouthTaskCheck($arrayEt, $arrayCheck) {
        $arrayObject1 = new ArrayObject($arrayEt);
        $arrayObject2 = new ArrayObject($arrayCheck);
        $iterator1 = $arrayObject1->getIterator();
        $iterator2 = $arrayObject2->getIterator();
        for ($iterator1->rewind(), $iterator2->rewind(); $floatEt = $iterator1->current(), $floatCheck = $iterator2->current(); $iterator1->next(), $iterator2->next()) {
            $check[] = TaskCheck::floatCheck($floatEt[0], $floatCheck[0], TaskCheck::$fouthValueE);
            $check[] = TaskCheck::floatCheck($floatEt[1], $floatCheck[1], TaskCheck::$fouthEE);
        }
        return self::checkBoolArray($check);
    }

    public static function fifthTaskCheck($arrayEt, $arrayCheck) {
        $arrayObject1 = new ArrayObject($arrayEt);
        $arrayObject2 = new ArrayObject($arrayCheck);
        $iterator1 = $arrayObject1->getIterator();
        $iterator2 = $arrayObject2->getIterator();
        for ($iterator1->rewind(), $iterator2->rewind(); $floatEt = $iterator1->current(), $floatCheck = $iterator2->current(); $iterator1->next(), $iterator2->next()) {
            $check[] = TaskCheck::floatCheck($floatEt[1], $floatCheck[1], TaskCheck::$fifthFirstD);
            $check[] = TaskCheck::floatCheck($floatEt[2], $floatCheck[2], TaskCheck::$fifthSecondD);
        }
        return self::checkBoolArray($check);
    }

    public static function sixthTaskCheck($arrayEt, $arrayCheck) {
        foreach ($arrayEt as $key => $value) {
            if ($key == "nArray") {
                for ($index = 0; $index < count($arrayEt["nArray"]); $index++) {
                    $check[] = self::intCheck($arrayEt["nArray"][$index], $arrayCheck["nArray"][$index]);
                }
            } else {
                $text = "sixthValue" . $key;
                for ($index = 0; $index < count($arrayEt[$key]); $index++) {
                    $check[] = self::floatCheck($arrayEt[$key][$index], $arrayCheck[$key][$index], self::$$text);
                }
            }
        }
        return self::checkBoolArray($check);
    }

    private static function checkBoolArray($check) {
        foreach ($check as $value) {
            if (!$value) {
                return false;
            }
        }
        return true;
    }

    private static function floatCheck($floatEt, $floatCheck, $e) {
        return ($floatCheck >= ($floatEt - $e) and $floatCheck <= ($floatEt + $e));
    }

    private static function intCheck($intEt, $intCheck) {
        return $intEt == $intCheck;
    }

}
