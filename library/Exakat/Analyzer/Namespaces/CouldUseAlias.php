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

namespace Exakat\Analyzer\Namespaces;

use Exakat\Analyzer\Analyzer;

class CouldUseAlias extends Analyzer {
    public function analyze() {
        // use a\b as C;
        // a\b::D(); and also a\b\d\e
        $this->atomIs(array('Nsname', 'Newcall'))
             ->hasNoIn(array('USE', 'NAME', 'METHOD', 'VARIABLE'))
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot('[')
             ->savePropertyAs('fullnspath', 'fnp')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->isNot('absolute', true)
             ->raw('filter{ (fnp =~ "^" + it.get().value("origin").replace("\\\\", "\\\\\\\\") + "(\\\\\\\\.*)?\\$" ).getCount() > 0 }')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
