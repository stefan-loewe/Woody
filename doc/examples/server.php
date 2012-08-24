<?php

require_once('D:\workspace\programming\PHP\woody\source\bootstrap\bootstrap.php');

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

  $client->send(strrev($_SERVER['REQUEST_URI']));

  //echo $client->readLine(1024);

  $client->disconnect();

  $client->close();
}