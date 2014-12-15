<?php

namespace Report\Content;

class Random extends \Report\Content {
    private $type = 'Hash';
    protected $name = "Random Content";
    
    public function setType($type) {
        $this->type = $type;
    }

    public function getDescription() {
        return "Random Description";
    }

    public function toArray() {
        $array = array();
        foreach(range('a', 'f') as $k => $v) {
            $array[$v] = rand(1, 10);
        }
        
        return $array;
    }
}

?>
