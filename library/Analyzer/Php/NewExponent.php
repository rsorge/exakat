<?php

namespace Analyzer\Php;

use Analyzer;

class NewExponent extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->fullnspath('\\pow');
        $this->prepareQuery();
    }
}

?>
