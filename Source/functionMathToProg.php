<?php

/**
 * Description of functionMathToProg
 * 
 * не доделано
 *
 * @author Freedom
 */
class functionMathToProg {

    private $func;
    private $rule = array(
        "arccos" => "acos",
        "arcsin" => "asin"
    );

    public function __construct($func) {
        $this->func = $func;
    }
    
    public function getFunction() {
        return $this->func;
    }
    
    public function setFunction($func) {
        $this->func = $func;
    }

    public function convert() {
        $this->firstConvert();
    }

    private function firstConvert() {
        $this->func = strtr($this->func, $this->rule);
    }

}
