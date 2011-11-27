<?php

use \Woody\Utils\Geom\Point;
use \Woody\Utils\Geom\Dimension;
use \Woody\Components\Windows\MainWindow;
use \Woody\App\TestApplication;

use \Woody\Components\Timer\Timer;

use \Woody\Components\Controls\Frame;
use \Woody\Components\Controls\EditBox;

require_once(realpath(__DIR__.'/bootstrap/bootstrap.php'));

/*$fh = popen('start run_server.bat', 'r');
while(!feof($fh))
{
    echo fread($fh, 128);
}*/
/*
$desc = array(0 => array('file', 'D:/r.txt', 'a'), 1 => array('file', 'D:/w.txt', 'a'), 2 => array('file', 'D:/e.txt', 'a'));
$pipes = array();

chdir("C:/Program Files/PHP54/");
proc_open('"C:/Program Files/PHP54/php.exe" -S 127.0.0.1:8008', $desc, $pipes);

die;*/
var_dump(phpversion());

$win = new MainWindow('MyWin2', new Point(50, 50), new Dimension(800, 600));
$win->create();

$server = new \Woody\Utils\Sockets\HTMLControlServer(8008);

$timer = new Timer(function() use ($server){echo 'var_dumping ...'; $server->loopOnce();}
    , $win, 1000);

$htmlControl = new Woody\Components\Controls\HTMLControl('http://localhost:8008?id=3', new Point(50, 50), new Dimension(600, 400));
$win->add($htmlControl);

$timer->start();
$win->startEventHandler();
