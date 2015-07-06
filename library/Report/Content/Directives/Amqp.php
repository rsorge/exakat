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


namespace Report\Content\Directives;

use Everyman\Neo4j\Client;

class Amqp extends Directives {
    public function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Amqp';
        $this->hasDirective = self::ON;

        if ($this->checkPresence('Extensions\\Extamqp')) {
            $this->directives[] = array('name' => 'amqp.host',
                                        'suggested' => 'localhost',
                                        'documentation' => 'The host to which to connect.');

            $this->directives[] = array('name' => 'amqp.vhost',
                                        'suggested' => '',
                                        'documentation' => 'The virtual host on the broker to which to connect.');

            $this->directives[] = array('name' => 'amqp.port',
                                        'suggested' => '',
                                        'documentation' => 'The port on which to connect.');

            $this->directives[] = array('name' => 'amqp.login',
                                        'suggested' => 'guest',
                                        'documentation' => 'The login to use while connecting to the broker.');

            $this->directives[] = array('name' => 'amqp.password',
                                        'suggested' => 'guest',
                                        'documentation' => 'The password to use while connecting to the broker.');

            $this->directives[] = $this->extraConfiguration($this->name, 'amqp');
        }

    }
}

?>