<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_IsExtConstant extends Analyzer {
    /* 9 methods */

    public function testConstants_IsExtConstant01()  { $this->generic_test('Constants_IsExtConstant.01'); }
    public function testConstants_IsExtConstant02()  { $this->generic_test('Constants_IsExtConstant.02'); }
    public function testConstants_IsExtConstant03()  { $this->generic_test('Constants_IsExtConstant.03'); }
    public function testConstants_IsExtConstant04()  { $this->generic_test('Constants_IsExtConstant.04'); }
    public function testConstants_IsExtConstant05()  { $this->generic_test('Constants_IsExtConstant.05'); }
    public function testConstants_IsExtConstant06()  { $this->generic_test('Constants_IsExtConstant.06'); }
    public function testConstants_IsExtConstant07()  { $this->generic_test('Constants_IsExtConstant.07'); }
    public function testConstants_IsExtConstant08()  { $this->generic_test('Constants_IsExtConstant.08'); }
    public function testConstants_IsExtConstant09()  { $this->generic_test('Constants_IsExtConstant.09'); }
}
?>