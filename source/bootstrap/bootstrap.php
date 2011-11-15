<?php

define('INSTALLATION_FOLDER', str_replace('\\', '/', realpath(__DIR__.'/../..')));
define('SOURCE_FOLDER', INSTALLATION_FOLDER.'/source');

require_once INSTALLATION_FOLDER.'/lib/winbinder.php';
require_once SOURCE_FOLDER.'/Utils/Autoload/Autoloader.class.inc';

$autoloader = new Woody\Utils\Autoload\Autoloader(SOURCE_FOLDER.'/');

spl_autoload_register(array($autoloader, 'autoload'));

function globalWinBinderEventHandler($window, $id, $control = 0, $param1 = 0, $param2 = 0)
{
    var_dump('calling globalWinBinderEventHandler in '.__FILE__.' at line '.__LINE__);
    var_dump($window);
    var_dump($id);
    var_dump($control);
    var_dump($param1);
    var_dump($param2);
    if($id === IDCLOSE)
        wb_destroy_window($window);
}