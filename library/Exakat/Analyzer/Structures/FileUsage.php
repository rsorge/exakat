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

class FileUsage extends Analyzer {
    public function analyze() {
        $this->atomFunctionIs(array('fopen', 'file_get_contents', 'file_put_contents'));
        $this->prepareQuery();

        $fileClasses = array('\\SplFileObject', '\\SplTempFileObject', '\\SplFileInfo');

        $this->atomIs('New')
             ->outIs('NEW')
             ->atomIs('Newcall')
             ->raw('where( __.out("NAME").hasLabel("Array", "Variable", "Property", "Staticproperty", "Methodcall", "Staticmethodcall").count().is(eq(0)))')
             ->tokenIs(self::$FUNCTIONS_TOKENS)
             ->fullnspathIs($fileClasses);
        $this->prepareQuery();

        $this->atomIs('New')
             ->outIs('NEW')
             ->atomIs(array('Identifier', 'Nsname'))
             ->tokenIs(self::$FUNCTIONS_TOKENS)
             ->fullnspathIs($fileClasses);
        $this->prepareQuery();

        $this->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticconstant'))
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspathIs($fileClasses);
        $this->prepareQuery();
    }
}

?>
