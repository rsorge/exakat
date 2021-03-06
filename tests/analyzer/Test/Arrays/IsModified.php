<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Arrays_IsModified extends Analyzer {
    /* 2 methods */

    public function testArrays_IsModified01()  { $this->generic_test('Arrays/IsModified.01'); }
    public function testArrays_IsModified02()  { $this->generic_test('Arrays/IsModified.02'); }
}
?>