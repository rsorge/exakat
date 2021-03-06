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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Datastore;

class FacetedJson extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'faceted';

    public function generate($dirName, $fileName = null) {
        $sqlite      = new \sqlite3($dirName.'/dump.sqlite', \SQLITE3_OPEN_READONLY);

        $sqlQuery = <<<SQL
SELECT  id AS id,
        fullcode AS code, 
        file AS file, 
        line AS line,
        analyzer AS analyzer
    FROM results 
    WHERE analyzer IN $this->themesList

SQL;
        $res = $sqlite->query($sqlQuery);

        $config = Config::factory();

        $datastore = new Datastore($config);

        $items = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $ini = parse_ini_file($config->dir_root.'/human/en/'.$row['analyzer'].'.ini');
            $row['error'] = $ini['name'];

            $a = Analyzer::getInstance($row['analyzer']);
            $row['severity'] = $a->getSeverity();
            $row['impact']   = $a->getTimeToFix();
            $row['recipes']  = $a->getThemes();

            $items[] = $row;
            $this->count();
        }

        if ($fileName === null) {
            $json = json_encode($items, JSON_PARTIAL_OUTPUT_ON_ERROR);
            // @todo Log if $json == false
            return $json;
        } else {
            file_put_contents($dirName.'/'.$fileName.'.'.self::FILE_EXTENSION, json_encode($items));
            return true;
        }
    }
}

?>