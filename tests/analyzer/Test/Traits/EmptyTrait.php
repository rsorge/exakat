<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Traits_EmptyTrait extends Analyzer {
    /* 2 methods */

    public function testTraits_EmptyTrait01()  { $this->generic_test('Traits_EmptyTrait.01'); }
    public function testTraits_EmptyTrait02()  { $this->generic_test('Traits/EmptyTrait.02'); }
}
?>