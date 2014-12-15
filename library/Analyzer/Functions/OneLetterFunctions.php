<?php

namespace Analyzer\Functions;

use Analyzer;

class OneLetterFunctions extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\Functionnames',
                     'Analyzer\\Classes\\Methoddefinition');
    }
    
    public function analyze() {
        $this->atomIs("Function")
             ->outIs('NAME')
             ->analyzerIs('Analyzer\\Functions\\Functionnames')
             ->analyzerIsNot('Analyzer\\Classes\\Methoddefinition')
             ->fullcodeLength(" == 1 ");
        $this->prepareQuery();
    }
}

?>
