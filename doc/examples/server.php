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

  $client->send($_SERVER['REQUEST_URI']);

  echo getReply($client);

  $client->disconnect();

  $client->close();
}

/**
 * This function prints the reply. If we would do a blocking-read, this breaks BuiltInWebServerTest, as that would wait
 * endlesslly, as no reply is sent there. This work-around is not really nice but works with both the test case and the
 * htmlControlWithLocalFilesBuiltInWS example.
 *
 * @param \Utils\Sockets\ClientSocket $client the socket connection of the client connecting to the HtmlControlServer
 */
function getReply($client) {
  $client->setNonBlocking();
  $reply = '';
  for($i = 0; $i < 3; $i++) {

    $reply .= $client->readLine(1024);
    if(strlen($reply) > 0) {
      break;
    }
    sleep(1);
  }

  return $reply;
}