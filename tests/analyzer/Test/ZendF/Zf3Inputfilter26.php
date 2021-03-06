<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ZendF_Zf3Inputfilter26 extends Analyzer {
    /* 2 methods */

    public function testZendF_Zf3Inputfilter2601()  { $this->generic_test('ZendF/Zf3Inputfilter26.01'); }
    public function testZendF_Zf3Inputfilter2602()  { $this->generic_test('ZendF/Zf3Inputfilter26.02'); }
}
?>