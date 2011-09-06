<?php

define('INSTALLATION_FOLDER', str_replace('\\', '/', realpath(__DIR__.'/../..')));

define('SOURCE_FOLDER', INSTALLATION_FOLDER.'/source');

require_once INSTALLATION_FOLDER.'/lib/winbinder.php';
require_once SOURCE_FOLDER.'/Utils/Autoload/Autoloader.class.inc';

use Woody\Utils\Autoload\Autoloader;
use Woody\Utils\Geom\Point;
use Woody\Utils\Geom\Dimension;
use Woody\Components\Windows\MainWindow;

$autoloader = new Autoloader(SOURCE_FOLDER.'/');

spl_autoload_register(array($autoloader, 'autoload'));


$win = new MainWindow('myWin2', 'MyWin2', new Point(50, 50), new Dimension(300, 200));

$win->create()->startEventHandler();