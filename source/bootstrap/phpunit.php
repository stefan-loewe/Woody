<?php

require_once 'D:/programming/workspace/PHP/woody/source/Utils/Autoload/Autoloader.class.inc';
require_once 'D:/programming/workspace/PHP/woody/lib/winbinder.php';

use Woody\Utils\Autoload\Autoloader;

$autoloader = new Autoloader('D:/programming/workspace/PHP/woody/source/');

spl_autoload_register(array($autoloader, 'autoload'));