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

namespace Exakat;

use Exakat\Config;
use Exakat\Exakat;
use Exakat\Exceptions;

class Datastore {
    protected $sqliteRead = null;
    protected $sqliteWrite = null;
    protected $sqlitePath = null;

    const CREATE = 1;
    const REUSE = 2;
    const TIMEOUT_WRITE = 500;
    const TIMEOUT_READ = 3000;

    public function __construct(Config $config, $create = self::REUSE) {
        $this->sqlitePath = $config->projects_root.'/projects/'.$config->project.'/datastore.sqlite';

        // if project dir isn't created, we are about to create it.
        if (!file_exists($config->projects_root.'/projects/'.$config->project)) {
            return;
        }

        if ($create === self::CREATE) {
            if (file_exists($this->sqlitePath)) {
                unlink($this->sqlitePath);
            }
            // force creation
            $this->sqliteWrite = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
            $this->sqliteWrite->close();
            $this->sqliteWrite = null;
        }

        if ($this->sqliteWrite === null) {
            $this->sqliteWrite = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
            $this->sqliteWrite->busyTimeout(self::TIMEOUT_WRITE);
            // open the read connexion AFTER the write, to have the sqlite databse created
            $this->sqliteRead = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READONLY);
            $this->sqliteWrite->busyTimeout(self::TIMEOUT_READ);
        }

        if ($create === self::CREATE) {
            $this->cleanTable('hash');
            $this->cleanTable('hashAnalyzer');
            $this->cleanTable('analyzed');
            $this->cleanTable('tokenCounts');
            $this->cleanTable('externallibraries');
            $this->cleanTable('ignoredFiles');
            $this->cleanTable('files');
            $this->cleanTable('shortopentag');
            $this->cleanTable('composer');
            $this->cleanTable('configFiles');

            $this->addRow('hash', array('exakat_version'       => Exakat::VERSION,
                                        'exakat_build'         => Exakat::BUILD,
                                        'datastore_creation'   => date('r', time()),
                                        'project'              => $config->project,
                                        ));
        }
    }

    public function addRow($table, $data) {
        if (empty($data)) {
            return true;
        }

        $this->checkTable($table);

        $first = current($data);
        if (is_array($first)) {
            $cols = array_keys($first);
        } else {
            $query = "PRAGMA table_info($table)";
            $res = $this->sqliteRead->query($query);

            $cols = array();
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                if ($row['name'] == 'id') { continue; }
                $cols[] = $row['name'];
            }

            if (count($cols) != 2) {
                throw new Exceptions\WrongNumberOfColsForAHash();
            }
        }

        foreach($data as $key => $row) {
            if (is_array($row)) {
                $d = array_values($row);
                foreach($d as &$e) {
                    $e = \Sqlite3::escapeString($e);
                }
                unset($e);

            } else {
                $d = array($key, \Sqlite3::escapeString($row));
            }

            $query = 'REPLACE INTO '.$table.' ('.implode(', ', $cols).") VALUES ('".implode("', '", $d)."')";
            $this->sqliteWrite->querySingle($query);
        }

        return true;
    }

    public function deleteRow($table, $data) {
        if (empty($data)) {
            return true;
        }

        $this->checkTable($table);

        $first = current($data);
        if (is_array($first)) {
            $cols = array_keys($first);
        } else {
            $query = "PRAGMA table_info($table)";
            $res = $this->sqliteRead->query($query);

            $cols = array();
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                if ($row['name'] == 'id') { continue; }
                $cols[] = $row['name'];
            }
        }

        foreach($data as $col => $row) {
            if (is_array($row)) {
                $d = array_values($row);
                foreach($d as &$e) {
                    $e = \Sqlite3::escapeString($e);
                }
                unset($e);
            } else {
                $d = array($row);
            }

            $query = 'DELETE FROM '.$table.' WHERE '.$col." IN ('".implode("', '", $d)."')";
            $this->sqliteWrite->querySingle($query);
        }

        return true;
    }

    public function getRow($table) {
        $return = array();
        try {
            $query = "SELECT * FROM $table";
            $res = $this->sqliteRead->query($query);
        } catch (\Exception $e) {
            return array();
        }

        if (!$res) {
            return array();
        }
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getCol($table, $col) {
        $return = array();

        $query = "SELECT $col FROM $table";
        $res = $this->sqliteRead->query($query);

        if (!$res) {
            return array();
        }
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row[$col];
        }

        return $return;
    }

    public function getHash($key) {
        $query = 'SELECT value FROM hash WHERE key=:key';
        $stmt = $this->sqliteRead->prepare($query);
        $stmt->bindValue(':key', $key, \SQLITE3_TEXT);
        $res = $stmt->execute();

        if (!$res) {
            return array();
        } else {
            $row = $res->fetchArray(\SQLITE3_ASSOC);
            return $row['value'];
        }
    }

    public function getHashAnalyzer($analyzer) {
        $query = 'SELECT key, value FROM hashAnalyzer WHERE analyzer=:analyzer';
        $stmt = $this->sqliteRead->prepare($query);
        $stmt->bindValue(':analyzer', $analyzer, \SQLITE3_TEXT);
        $res = $stmt->execute();

        if (!$res) {
            return array();
        }

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['key']] = $row['value'];
        }

        return $return;
    }

    public function addRowAnalyzer($analyzer, $key, $value = '') {
        if (is_array($key)) {
            foreach($key as &$v) {
                $v['analyzer'] = $analyzer;
            }
            unset($v);
            return $this->addRow('hashAnalyzer', $key);
        } else {
            return $this->addRow('hashAnalyzer', array('analyzer' => $analyzer,
                                                        $key      => $value));
        }
    }

    public function hasResult($table) {
        $query = "SELECT * FROM $table LIMIT 1";
        $r = $this->sqliteRead->querySingle($query);

        return !empty($r);
    }

    public function cleanTable($table) {
        if ($this->checkTable($table)) {
            $query = "DELETE FROM $table";
            $this->sqliteWrite->querySingle($query);
        }

        return true;
    }

    private function checkTable($table) {
        $res = $this->sqliteWrite->querySingle('SELECT count(*) FROM sqlite_master WHERE name="'.$table.'"');

        if ($res == 1) { return true; }

        switch($table) {
            case 'compilation52' :
                $createTable = <<<SQLITE
CREATE TABLE compilation52 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation53' :
                $createTable = <<<SQLITE
CREATE TABLE compilation53 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation54' :
                $createTable = <<<SQLITE
CREATE TABLE compilation54 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation55' :
                $createTable = <<<SQLITE
CREATE TABLE compilation55 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation56' :
                $createTable = <<<SQLITE
CREATE TABLE compilation56 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation70' :
                $createTable = <<<SQLITE
CREATE TABLE compilation70 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation71' :
                $createTable = <<<SQLITE
CREATE TABLE compilation71 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation72' :
                $createTable = <<<SQLITE
CREATE TABLE compilation72 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'shortopentag' :
                $createTable = <<<SQLITE
CREATE TABLE shortopentag (
  id INTEGER PRIMARY KEY,
  file TEXT
);
SQLITE;
                break;

            case 'files' :
                $createTable = <<<SQLITE
CREATE TABLE files (
  id INTEGER PRIMARY KEY,
  file TEXT
);
SQLITE;
                break;

            case 'ignoredFiles' :
                $createTable = <<<SQLITE
CREATE TABLE ignoredFiles (
  id INTEGER PRIMARY KEY,
  file TEXT,
  reason INTEGER DEFAULT 1
);
SQLITE;
                break;

            case 'hash' :
                $createTable = <<<SQLITE
CREATE TABLE hash (
  id INTEGER PRIMARY KEY,
  key TEXT UNIQUE,
  value TEXT
);
SQLITE;
                break;

            case 'hashAnalyzer' :
                $createTable = <<<SQLITE
CREATE TABLE hashAnalyzer (
  id INTEGER PRIMARY KEY,
  analyzer TEXT,
  key TEXT UNIQUE,
  value TEXT
);
SQLITE;
                break;

            case 'analyzed' :
                $createTable = <<<SQLITE
CREATE TABLE analyzed (
  id INTEGER PRIMARY KEY,
  analyzer TEXT UNIQUE,
  counts TEXT
);
SQLITE;
                break;

            case 'tokenCounts' :
                $createTable = <<<SQLITE
CREATE TABLE tokenCounts (
  id INTEGER PRIMARY KEY,
  token TEXT UNIQUE,
  counts INTEGER
);
SQLITE;
                break;

            case 'externallibraries' :
                $createTable = <<<SQLITE
CREATE TABLE externallibraries (
  id INTEGER PRIMARY KEY,
  library TEXT UNIQUE,
  file TEXT
);
SQLITE;
                break;

            case 'composer' :
                $createTable = <<<SQLITE
CREATE TABLE composer (
  id INTEGER PRIMARY KEY,
  component TEXT UNIQUE,
  version TEXT
);
SQLITE;
                break;

            case 'configFiles' :
                $createTable = <<<SQLITE
CREATE TABLE configFiles (
  id INTEGER PRIMARY KEY,
  file TEXT UNIQUE,
  name TEXT UNIQUE,
  homepage TEXT UNIQUE
);
SQLITE;
                break;

            default :
                throw new Exceptions\NoStructureForTable($table);
        }

        $this->sqliteWrite->query($createTable);

        return true;
    }

    public function reload() {
        $this->sqliteRead->close();
        $this->sqliteWrite->close();

        $this->sqliteWrite = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
        $this->sqliteWrite->busyTimeout(self::TIMEOUT_WRITE);
        // open the read connexion AFTER the write, to have the sqlite databse created
        $this->sqliteRead = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READONLY);
        $this->sqliteWrite->busyTimeout(self::TIMEOUT_READ);
    }
}

?>
