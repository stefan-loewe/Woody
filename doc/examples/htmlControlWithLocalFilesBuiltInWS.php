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

$win = new MainWindow('built-in-webserver', new Point(50, 50), new Dimension(800, 500));
$win->create();


$server = new \Woody\Server\BuiltInWebServer($win, 5555);
$server->run();

$htmlControl = new Woody\Components\Controls\HTMLControl('http://127.0.0.1:5555', new Point(50, 50), new Dimension(600, 300));
$server->register($htmlControl);
$win->add($htmlControl);

$file = new stdClass();
$file->name = null;
$file->content = null;
$cb = function($event) use ($win, $file) {
        $file->content = str_repeat('<h1>header, large</h1>
            <br>followed by an image ...
            <br><img src="A1.png">', 1);
        $event->type->write($file->content);
    };
$htmlControl->addActionListener(new \Woody\Event\ActionAdapter($cb));

$bntRefresh = new \Woody\Components\Controls\PushButton("refresh", new Point(170, 370), new Dimension(100, 22));
$bntRefresh->addActionListener(new \Woody\Event\ActionAdapter(function() use ($htmlControl) {
    $htmlControl->setUrl('http://127.0.0.1:5555/jsdbdljbjsdfs.php?t='.time());
}));
$win->add($bntRefresh);

$win->startEventHandler();

$server->shutdown();
