<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Classes;

use Analyzer;

class SameNameAsFile extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs(array('Class', 'Interface', 'Trait'))
             ->outIs('NAME')
             ->savePropertyAs('code', 'classname')
             ->goToFile()
             // Is the clasname also the filename (case insensitive)
             ->regex('filename', '(?i)" + classname + "\\\\.php\\$')
             // Is the clasname also the filename (case sensitive)
             ->regexNot('filename', '" + classname + "\\\\.php\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>