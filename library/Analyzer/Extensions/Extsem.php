<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsem extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'sem.ini';

        parent::analyze();
    }
}

?>
