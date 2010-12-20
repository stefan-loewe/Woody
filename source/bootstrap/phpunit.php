<?php

define('INSTALLATION_FOLDER', str_replace('\\', '/', realpath(__DIR__.'/../..')));

define('SOURCE_FOLDER', INSTALLATION_FOLDER.'/source');

require_once INSTALLATION_FOLDER.'/lib/winbinder.php';
require_once SOURCE_FOLDER.'/Utils/Autoload/Autoloader.class.inc';

use Woody\Utils\Autoload\Autoloader;

$autoloader = new Autoloader(SOURCE_FOLDER.'/');

spl_autoload_register(array($autoloader, 'autoload'));