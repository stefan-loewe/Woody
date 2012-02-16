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


$win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
$win->create();

if(TRUE) {
    $server = new \Woody\Server\BuiltInWebServer($win, 8008);
    $server->run();
} else {
    $server = new \Woody\Server\HtmlControlServer($win, 8008);
    $server->run(100);
}

$htmlControl = new Woody\Components\Controls\HTMLControl('http://localhost:8008?id=3', new Point(50, 50), new Dimension(600, 400));
$win->add($htmlControl);

$bntShowFileDialog = new \Woody\Components\Controls\PushButton("browse ...", new Point(50, 500), new Dimension(100, 22));
$bntShowFileDialog->addActionListener(new \Woody\Event\ActionAdapter(function() use ($win) {
                                                                        $file = trim(wb_sys_dlg_open($win->getControlID(), 'select file ...'));
                                                                        var_dump($file);
                                                                        $contents = file_get_contents($file);
                                                                        file_put_contents('D:\workspace\programming\PHP\woody\source\buffer.html', $contents);
                                                                    }));
$win->add($bntShowFileDialog);
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
