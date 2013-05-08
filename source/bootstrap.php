<?php

use \ws\loewe\Woody\Event\EventInfo;
use \ws\loewe\Woody\Event\EventFactory;
use \ws\loewe\Woody\Components\Component;

error_reporting(E_ALL | E_STRICT);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../lib/winbinder.php';
require_once __DIR__.'/../lib/fi/freeimage.inc.php';

$autoloader = new ws\loewe\Utils\Autoload\Autoloader('./source');

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
    throw new \ws\loewe\Woody\WinBinderErrorException(
      'Error when using WinBinder object - original error message was "'.$errstr.'"',
      0,
      $errorException);
  }
  else {
    throw $errorException;
  }
};

set_error_handler($callback);
