<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_CreatedOutsideItsNamespace extends Analyzer {
    /* 3 methods */

    public function testConstants_CreatedOutsideItsNamespace01()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.01'); }
    public function testConstants_CreatedOutsideItsNamespace02()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.02'); }
    public function testConstants_CreatedOutsideItsNamespace03()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.03'); }
}
?>