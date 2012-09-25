<?php

use \Woody\Event\EventInfo;
use \Woody\Event\EventFactory;
use \Woody\Components\Component;

error_reporting(E_ALL | E_STRICT);

define('INSTALLATION_FOLDER', str_replace('\\', '/', realpath(__DIR__.'/../..')));
define('SOURCE_FOLDER', INSTALLATION_FOLDER.'/source');

require_once INSTALLATION_FOLDER.'/lib/winbinder.php';
require_once INSTALLATION_FOLDER.'/lib/fi/freeimage.inc.php';
require_once SOURCE_FOLDER.'/Utils/Autoload/Autoloader.inc';

$autoloader = new \Utils\Autoload\Autoloader(SOURCE_FOLDER.'/');

spl_autoload_register(array($autoloader, 'autoload'));

function globalWinBinderEventHandler($windowID, $id, $controlID = 0, $type = 0, $property = 0) {
  $events = EventFactory::createEvent($eventInfo = new EventInfo($windowID, $id, Component::getComponentByID($controlID), $type, $property));
  foreach($events as $event) {
    if($event != null) {
      $event->dispatch();
    }
  }
}

$callback = function($errno, $errstr, $errfile, $errline) {
  $errorException = new \ErrorException($errstr, 0, $errno, $errfile, $errline);

  if(strpos($errstr, 'wbIsWBObj:') === 0) {
    throw new Woody\WinBinderErrorException(
      'Error when using WinBinder object - original error message was "'.$errstr.'"',
      0,
      $errorException);
  }
  else {
    throw $errorException;
  }
};

set_error_handler($callback);