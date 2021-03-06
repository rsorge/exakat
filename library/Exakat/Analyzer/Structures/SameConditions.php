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

class SameConditions extends Analyzer {
    public function analyze() {
        // if ($a) {} elseif ($a1) {} else {}
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->savePropertyAs('fullcode', 'condition')
             ->_as('results')
             ->inIs('CONDITION')
             ->raw('where( __.repeat( __.out("ELSE").coalesce(hasLabel("Sequence").has("count", 1).out("ELEMENT").hasLabel("Ifthen"),  __.filter{true} )).emit().times('.self::MAX_LOOPING.').out("CONDITION").filter{ it.get().value("fullcode") == condition; } )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
