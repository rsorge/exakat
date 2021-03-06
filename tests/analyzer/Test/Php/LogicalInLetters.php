<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_LogicalInLetters extends Analyzer {
    /* 3 methods */

    public function testPhp_LogicalInLetters01()  { $this->generic_test('Php/LogicalInLetters.01'); }
    public function testPhp_LogicalInLetters02()  { $this->generic_test('Php/LogicalInLetters.02'); }
    public function testPhp_LogicalInLetters03()  { $this->generic_test('Php/LogicalInLetters.03'); }
}
?>