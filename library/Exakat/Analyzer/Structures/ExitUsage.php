<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ExitUsage extends Analyzer {
    public function dependsOn() {
        return array('Structures/NoDirectAccess',
                     'Files/IsCliScript');
    }
    
    public function analyze() {
        // while (list($a, $b) = each($c)) {}
        $this->atomFunctionIs(array('exit', 'die'))
             ->goToInstruction('Ifthen')
             ->analyzerIsNot('Structures/NoDirectAccess')
             ->goToFile()
             ->analyzerIsNot('Files/IsCliScript')
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs(array('exit', 'die'))
             ->hasNoInstruction('Ifthen')
             ->goToFile()
             ->analyzerIsNot('Files/IsCliScript')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
