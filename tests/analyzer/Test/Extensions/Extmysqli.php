<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extmysqli extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extmysqli01()  { $this->generic_test('Extensions_Extmysqli.01'); }
    public function testExtensions_Extmysqli02()  { $this->generic_test('Extensions_Extmysqli.02'); }
}
?>