<?php

require_once('bootstrap/bootstrap.php');

$requestUri = $_SERVER["REQUEST_URI"];

//$fh = fopen('php://stdout', 'w');
//fputs($fh, "\n\nrequest to: ".$requestUri);
//fclose($fh);
// serve the requested resource as-is
if(preg_match('/\.(?:png|jpg|jpeg|gif)$/', $requestUri)) {
  return false;
}

// serve the requested resource as-is
else if(preg_match('/\.(?:php)$/', $requestUri)) {
  return false;
}
else if($_SERVER['REQUEST_URI'] === '/') {
  foreach(glob($_SERVER['DOCUMENT_ROOT'].'\*') as $entry) {
    $filename = substr($entry, strrpos($entry, '\\') + 1);
    echo '<br><a href="'.$filename.'">'.$filename.'</a>';
  }
  return;
}
else {
  $client = new \Utils\Sockets\ClientSocket('127.0.0.1', 1234);

  $client->connect();

  $client->send($_SERVER['REQUEST_URI']);

  echo $client->read(1024);

  $client->disconnect();

  $client->close();
}
/*
deleteBuffer();

printCurrentUrl();

printBuffer();

function printBuffer() {
    while(!file_exists(WEB_SERVER_BUFFER)) {
        sleep(1);
    }

    echo file_get_contents(WEB_SERVER_BUFFER);
}


function deleteBuffer() {
    if(file_exists(WEB_SERVER_BUFFER)) {
        unlink(WEB_SERVER_BUFFER);
    }
}

function printCurrentUrl() {
    $fh = fopen('php://stdout', 'w');
    fputs($fh, $_SERVER['REQUEST_URI']);
    fclose($fh);
}
*/