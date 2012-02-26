<?php

use \Utils\Geom\Point;
use \Utils\Geom\Dimension;
use \Woody\Components\Windows\MainWindow;
use \Woody\App\TestApplication;

use \Woody\Components\Timer\Timer;

use \Woody\Components\Controls\Frame;
use \Woody\Components\Controls\EditBox;
use \Woody\Components\Controls\Checkbox;

use \Woody\Util\ImageResource;

use \Utils\Http\HttpRequest;
use \Utils\Sockets\ServerSocket;

require_once(realpath(__DIR__.'../../../source/bootstrap/bootstrap.php'));

//var_dump(file_get_contents('http://php.net/manual/de/function.file-get-contents.php'));

$win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 500));
$win->create();

if(!TRUE) {
    $server = new \Woody\Server\BuiltInWebServer($win, 8008);
    $server->run();
} else {
    $server = new \Woody\Server\HtmlControlServer($win, 8008);
    $server->run(1000);
}

$htmlControl = new Woody\Components\Controls\HTMLControl('http://127.0.0.1:8888/index555.html', new Point(50, 50), new Dimension(600, 300));
$server->register($htmlControl);
$win->add($htmlControl);

$file = new stdClass();
$file->name = null;

$cb = function($event) use ($win, $file) {

        $contents = time();
        if($file->name != null)
            $contents = file_get_contents ($file->name);
/*adapt listener to work with built in server
actually, better write complete new example

maybe try this with AJAX approach !?!?!*/

        $time = '1';
        $event->type->write(str_repeat($time, 1024));

        /*$event->type->write('HTTP/1.1 200 OK
Date: Sun, 26 Feb 2012 17:04:30 GMT
Server: 127.0.0.1:8008
Content-Type: text/html
Last-Modified: Tue, 10 Jun 2008 20:10:20 GMT
ETag: "0:6r0x:484edfac:9b7"
Accept-Ranges: bytes
Connection: Keep-Alive
Content-Length: 2487

<html>
<head>
<title>Welcome to Nanoweb !</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#608A8E" text="#000000" link="#204A4E" vlink="#204A4E" alink="#204A4E">
<table bgcolor="#FFFFFF" width="980" align="center" cellspacing="0" cellpadding="0">
<tr>
<td width="14"></td>
<td>

<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td valign="top"><table align="center" cellspacing="0" cellpadding="0" width="100%">
<tr><td width="100%" align="left" valign="top">
</td></tr></table>
</td></tr></table>
</td>
<td width="14"></td>
</tr>
<tr height="14">
<td width="14"></td>
<td></td>
<td width="14"></td>
</tr>
</table>
</body>
</html>');*/
    };
$htmlControl->addActionListener(new \Woody\Event\ActionAdapter($cb));

$bntShowFileDialog = new \Woody\Components\Controls\PushButton("browse ...", new Point(50, 370), new Dimension(100, 22));
$bntShowFileDialog->addActionListener(new \Woody\Event\ActionAdapter(function() use ($win, $file) {
    $file->name = trim(wb_sys_dlg_open($win->getControlID(), 'select file ...'));
}));
$win->add($bntShowFileDialog);

$bntRefresh = new \Woody\Components\Controls\PushButton("refresh", new Point(170, 370), new Dimension(100, 22));
$bntRefresh->addActionListener(new \Woody\Event\ActionAdapter(function() use ($htmlControl) {
    $htmlControl->setUrl('http://127.0.0.1:8008/?id='.time());
}));
$win->add($bntRefresh);

$bntApi = new \Woody\Components\Controls\PushButton("refresh", new Point(270, 370), new Dimension(100, 22));
$bntApi->addActionListener(new \Woody\Event\ActionAdapter(function() use ($htmlControl) {
    //$htmlControl->setUrl('http://127.0.0.1/woody/doc/api/');
    $htmlControl->setUrl('http://127.0.0.1:8800');
}));
$win->add($bntApi);

 /*
    Woody\Event\EventHandler::addEventHandler($htmlControl2,
    function($control, ServerSocket $clientSocket, HttpRequest $httpRequest)
    {
        $keyValuePairs = $httpRequest->getKeyValuePairs();

        if($keyValuePairs['id'] == 3)
            $clientSocket->write('void');

        else
            $clientSocket->write('<a href="?id='.($keyValuePairs['id'] - 1)
                    .'">-</a><b style="color:red;">goodbye '
                    .$keyValuePairs['id'].'</b><a href="?id='
                    .($keyValuePairs['id'] + 1).'">+</a>');
    });

    $server->register($htmlControl2);
 */

$win->startEventHandler();

$server->shutdown();
