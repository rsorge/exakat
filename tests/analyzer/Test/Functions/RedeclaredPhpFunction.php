<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_RedeclaredPhpFunction extends Analyzer {
    /* 2 methods */

    public function testFunctions_RedeclaredPhpFunction01()  { $this->generic_test('Functions_RedeclaredPhpFunction.01'); }
    public function testFunctions_RedeclaredPhpFunction02()  { $this->generic_test('Functions_RedeclaredPhpFunction.02'); }
}
?>