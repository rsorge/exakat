<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Constants;

use Exakat\Analyzer\Analyzer;

class InconsistantCase extends Analyzer {

    public function analyze() {
        $query = <<<GREMLIN
g.V().hasLabel("<atom>").map{ if (it.get().value('code') == it.get().value('code').toLowerCase()) { 
                                    x2 = 'lower'; 
                                } else if (it.get().value('code') == it.get().value('code').toUpperCase()) { 
                                    x2 = 'upper'; 
                                } else {
                                    x2 = 'mixed'; 
                                }; }.groupCount("gf").cap("gf").sideEffect{ s = it.get().values().sum(); }.next().findAll{ it.value < s * 0.1; }.keySet()
GREMLIN;
        
        $atoms = ['Null', 'Boolean'];
        foreach($atoms as $atom) {
            $types = $this->query(str_replace('<atom>', $atom, $query) );

            if (count($types) > 0) {
                $typesList = '"'.implode('", "', $types).'"';
                $this->atomIs($atom)
                     ->raw('sideEffect{ if (it.get().value("code") == it.get().value("code").toLowerCase()) { 
                                x2 = "lower"; 
                            } else if (it.get().value("code") == it.get().value("code").toUpperCase()) { 
                                x2 = "upper"; 
                            } else {
                                x2 = "mixed"; 
                            }; }')
                      ->raw('filter{ x2 in ['.$typesList.']}');
                $this->prepareQuery();
            }
        }
    }
}

?>