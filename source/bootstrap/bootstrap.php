<?php

use \Woody\Event\EventInfo;
use \Woody\Event\EventFactory;
use \Woody\Event\EventDispatcher;

error_reporting(E_ALL | E_STRICT);

define('INSTALLATION_FOLDER', str_replace('\\', '/', realpath(__DIR__.'/../..')));
define('SOURCE_FOLDER', INSTALLATION_FOLDER.'/source');

// buffer file for the built-in-web server
define('WEB_SERVER_BUFFER', 'buffer.html');

require_once INSTALLATION_FOLDER.'/lib/winbinder.php';
require_once INSTALLATION_FOLDER.'/lib/fi/freeimage.inc.php';
require_once SOURCE_FOLDER.'/Utils/Autoload/Autoloader.inc';

$autoloader = new \Utils\Autoload\Autoloader(SOURCE_FOLDER.'/');

spl_autoload_register(array($autoloader, 'autoload'));

function globalWinBinderEventHandler($windowID, $id, $controlID = 0, $type = 0, $property = 0) {
  //var_dump(date('H:i:s').': calling globalWinBinderEventHandler in '.__FILE__.' at line '.__LINE__);
  //var_dump($windowID.', '.$id.', '.$controlID.', '.$type.', '.$property);

  $events = EventFactory::createEvent($eventInfo = new EventInfo($windowID, $id, $controlID, $type, $property));
  foreach($events as $event) {
    if($event != null) {
      //EventDispatcher::dispatchEvent($eventInfo, $event);
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