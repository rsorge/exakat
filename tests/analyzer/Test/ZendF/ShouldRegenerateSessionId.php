<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ZendF_ShouldRegenerateSessionId extends Analyzer {
    /* 2 methods */

    public function testZendF_ShouldRegenerateSessionId01()  { $this->generic_test('ZendF/ShouldRegenerateSessionId.01'); }
    public function testZendF_ShouldRegenerateSessionId02()  { $this->generic_test('ZendF/ShouldRegenerateSessionId.02'); }
}
?>