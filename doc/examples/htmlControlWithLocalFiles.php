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


$win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 500));
$win->create();

if(!TRUE) {
    $server = new \Woody\Server\BuiltInWebServer($win, 8008);
    $server->run();
} else {
    $server = new \Woody\Server\HtmlControlServer($win, 8008);
    $server->run(1000);
}

$htmlControl = new Woody\Components\Controls\HTMLControl('http://localhost:8008', new Point(50, 50), new Dimension(600, 300));
$server->register($htmlControl);
$win->add($htmlControl);

$file = new stdClass();
$file->name = null;

$cb = function($event) use ($win, $file) {
        $contents = time();
        if($file->name != null)
            $contents = file_get_contents ($file->name);

        $event->type->write('<html><body><a href=\'javascript:alert(navigator.appName + "\n" + "Browser Version: " + navigator.appVersion);\'>debug</a><a href=".">ping</a><br>'.$contents.'</body></html>');
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
    $htmlControl->setUrl('http://127.0.0.1/woody/doc/api/');
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
