<?php

define('INSTALLATION_FOLDER', str_replace('\\', '/', realpath(__DIR__.'/../..')));
define('SOURCE_FOLDER', INSTALLATION_FOLDER.'/source');

require_once INSTALLATION_FOLDER.'/lib/winbinder.php';
require_once SOURCE_FOLDER.'/Utils/Autoload/Autoloader.class.inc';

$autoloader = new Woody\Utils\Autoload\Autoloader(SOURCE_FOLDER.'/');

spl_autoload_register(array($autoloader, 'autoload'));

function globalWinBinderEventHandler($window, $id, $control = 0, $param1 = 0, $param2 = 0)
{
    //var_dump(date('H:i:s').': calling globalWinBinderEventHandler in '.__FILE__.' at line '.__LINE__);
    //var_dump($window.', '.$id.', '.$control.', '.$param1.', '.$param2);

     \Woody\Event\EventHandler::handleEvent($window, $id, $control, $param1, $param2);
}